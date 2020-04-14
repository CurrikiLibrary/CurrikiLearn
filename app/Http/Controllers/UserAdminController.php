<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\WpUser;
use App\User;
use App\UserRole;
use App\Group;
use App\Subject;
use App\GroupUserRole;
use App\LevelGrouping;
use App\Contracts\Services\WpApiInterface;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserAdminController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var UserRepositoryInterface $userRepositoryInterface
     */
    protected $userRepositoryInterface;

    /**
     * Create a new instance of UserAdminController.
     *
     * @param UserRepositoryInterface $userRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function index(Request $request)
    {
        $levels = LevelGrouping::all();
        $subjects = Subject::orderBy('displayname', 'ASC')->pluck('displayname', 'subjectid');
        $roles = UserRole::whereIn('name', ['admin', 'moderator', 'member', 'branch_admin'])->get();
        $groups = Group::all();
        $query = ($request->filled('query')) ? $request->input('query') : '';

        $userIds = \DB::table('custom_group_users')->whereIn('group_id', $groups->pluck('id'))->pluck('user_id');

        $users = WpUser::where(function ($q) use ($query){
            $q->where('display_name', 'like', '%'.$query.'%')
                ->orWhere('user_login', 'like', '%'.$query.'%')
                ->orWhere('user_email', 'like', '%'.$query.'%');
        })
        ->whereIn('ID', $userIds)
        ->orderBy('user_registered', 'desc')
        ->paginate(10);

        return view('user_admin.index', compact('query', 'users', 'levels', 'subjects', 'groups', 'roles'));
    }

    /**
     * Add user in WordPress
     *
     * @param Request $request
     * @param WpApiInterface $wpApi
     */
    public function add_user(Request $request, WpApiInterface $wpApi){
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'in:f,m',
//            'username' => 'required|not_regex:/\s/|unique:cur_users,user_login',
//            'email' => 'required|email|unique:cur_users,user_email',
            'username' => 'required|not_regex:/\s/',
            'email' => 'required|email',
            'group' => 'required',
            'levels' => 'required',
            'subjects' => 'required',
            'role' => 'required'
        ]);

        // Checking if user email exists
        $u = WpUser::where('user_email', $request->input('email'))->first();
        if(!empty($u)) {
            // Is the user already in BOCES?
            if($u->groups()->exists())
                return redirect('user_management')->withErrors(['error' => 'A user with this email address is already a member of the  ' . env('APP_NAME') . '  Hub.']);

            if($u->user_login != $request->input('username'))
                return redirect('user_management')->withErrors(['error' => 'A user with this email address already exists in Curriki but with a different username ('.$u->user_login.'). If you want to enroll this user in the  ' . env('APP_NAME') . '  Hub, please use the same username and email.']);

            $u->groups()->attach($request->input('group'));
            // If user email exists and has the same username, add to BOCES.
            //$u->groups()->attach(env('NASSAU_HUB_ID')); // Attach to hub 
            //$u->roles(env('NASSAU_HUB_ID'))->attach(3, ['group_id' => env('NASSAU_HUB_ID')]);    // with member role
            GroupUserRole::create([
                'group_id' => $request->input('group'),
                'user_id' => $u->ID,
                'role_id' => $request->input('role')
            ]);

            return redirect('user_management')->with('msg', 'Existing Curriki user enrolled in  ' . env('APP_NAME') . ' .');
        }
        // Checking if username exists
        $u = WpUser::where('user_login', $request->input('username'))->first();
        if(!empty($u))
            return redirect('user_management')->withErrors(['error' => 'A user with this user name already exists in Curriki but with a different e-mail address. If you wish to add a new user, use a different user name. If you wish to include an existing user in  ' . env('APP_NAME') . ' , make sure the user name and e-mail address match.']);

        $password = str_random(8);

        $params = [
            'form_params' => [
                //'group_id' => env('NASSAU_HUB_ID'),
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'gender' => $request->input('gender'),
                'pwd' => $password,
                'confirm_pwd' => $password
            ]
        ];

        $response = $wpApi->request('post', 'wp-json/genesis-curriki/v1/users/', $params);

        if ($response === false) {
            $responseContent['message'] = 'Failed to add user.';
        } else {
            $responseContent = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 200){
                $this->userRepositoryInterface->setupUserOnSignUp($request->input('username'), $request->input('email'), $password);
                $user = WpUser::where('user_email', $request->input('email'))->first();
                if(empty($user))
                    return redirect('user_management')->withErrors(['error' => 'There was an error during user creation.']);

                $user->groups()->attach($request->input('group'));
                GroupUserRole::create([
                    'group_id' => $request->input('group'),
                    'user_id' => $user->ID,
                    'role_id' => $request->input('role')
                ]);

                return redirect('user_management')->with('msg', 'User Created!');
            } else {
                return redirect('user_management')->withErrors(['error' => 'Error communicating with curriki API.']);
            }
        }

        return back()->withErrors(['error' => $responseContent['message']]);
    }

    /**
     * Update user in WordPress
     *
     * @param Request $request
     * @param WpApiInterface $wpApi
     */
    public function update_user(Request $request, WpApiInterface $wpApi){
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'levels' => 'required',
            'subjects' => 'required',
            'userpicture' => 'mimes:jpg,jpeg,png,gif|max:524288',
            'password' => 'nullable|min:8|max:20|confirmed'
        ]);

        $params = [
            'multipart' => [
                [
                    'name'     => 'firstname',
                    'contents' => $request->input('firstname')
                ],
                [
                    'name'     => 'lastname',
                    'contents' => $request->input('lastname')
                ]
            ]
        ];

        if ($request->hasFile('userpicture') && $request->file('userpicture')->isValid()) {
            $userpicturePath = $request->file('userpicture')->path();
            $userpictureFilename = $request->file('userpicture')->getClientOriginalName();

            $params['multipart'][] = [
                'name'     => 'my_photo',
                'contents' => file_get_contents($userpicturePath),
                'filename' => $userpictureFilename
            ];
        }

        foreach ($request->input('subjects') as $subject) {
            // Getting actual levels from the selected level groupings
            $level = 
            $params['multipart'][] = [
                'name'     => 'subjectarea[]',
                'contents' => $subject
            ];
        }

        if($request->filled('levels')){
            $levelGroupings = LevelGrouping::whereIn('id', $request->input('levels'))->get();

            foreach($levelGroupings as $levelGroup){
                foreach($levelGroup->levels as $level){
                    $params['multipart'][] = [
                        'name'     => 'educationlevel[]',
                        'contents' => $level->levelid
                    ];
                }
            }
        }

        $userId = Auth::user()->userid;
        $response = $wpApi->request('post', 'wp-json/genesis-curriki/v1/users/' . $userId, $params);

        if ($response === false) {
            $responseContent['message'] = 'Failed to update user.';
        } else {
            $responseContent = json_decode($response->getBody()->getContents(), true);

            if($response->getStatusCode() == 200){
                //Save the new password
                if($request->filled('password'))
                    $this->userRepositoryInterface->updateUserPassword($userId, $request->input('password'));
                
                return back()->with('msg', 'User updated!');
            } else {
                return back()->withErrors(['error'=>'An error occurred. Please check your information and try again.']);
            }
        }

        return back()->withErrors(['error' => $responseContent['message']]);
    }

    public function remove_user(Request $request, $user_id){
        $admin_user = Auth::user();
        $user = User::find($user_id);
        if(empty($user))
            return redirect('user_management')->withErrors(['error' => 'User not found in the  ' . env('APP_NAME') . '  hub.']);

        // Transfer all the user's resources to the user doing the deleting to avoid orphans
        foreach ($user->resources as $resource) {
            $resource->contributorid = $admin_user->userid;

            if($resource->lasteditorid == $user->userid)
                $resource->lasteditorid = $admin_user->userid;

            if($resource->resourcecheckid == $user->userid)
                $resource->resourcecheckid = $admin_user->userid;

            if($resource->reviewedbyid == $user->userid)
                $resource->reviewedbyid = $admin_user->userid;

            $resource->save();              
        }

        // Removing group roles
        foreach($user->groups as $group){
            $user->roles($group->id)->detach();
        }

        // Detaching from groups
        $user->groups()->detach();

        return redirect('user_management')->with('msg', 'User removed the  ' . env('APP_NAME') . '  hub.');
    }

    /**
     * Reset user's password in WordPress
     *
     * @param Request $request
     */
    public function reset_password(Request $request){
        $this->userRepositoryInterface->resetUserPassword($request->id);

        return back()->with('msg', 'Password Reset!');
    }

    public function fetchuser(Request $request){
        if(!$request->filled('username') && !$request->filled('email'))
            return json_encode([]);

        // Check if user exists in the database
        $query = WpUser::query();

        if($request->filled('username'))
            $query->orWhere('user_login', $request->input('username'));

        if($request->filled('email'))
            $query->orWhere('user_email', $request->input('email'));

        $user = $query->first();

        if(empty($user))
            return json_encode([]);

        $response = [
            'username' => $user->user_login,
            'email' => $user->user_email,
            'firstname' => $user->user->firstname,
            'lastname' => $user->user->lastname,
            'in_hub' => $user->groups()->exists()
        ];

        return json_encode($response);
    }
    
    public function user_groups(Request $request, $user_id){
        $user = WpUser::find($user_id);

        if(empty($user))
            return redirect('/user_management')->withErrors(['error' => 'User not found in the  ' . env('APP_NAME') . '  hub.']);

        $roles = UserRole::whereIn('name', ['admin', 'moderator', 'member'])->get();
        $groups = Group::find(env('NASSAU_HUB_ID'))->getAllChildren();

        return view('user_admin.user_groups', compact('user', 'roles', 'groups'));
    }

    public function add_group(Request $request, $user_id){
        $request->validate([
            'group' => 'required|numeric',
            'role' => 'required|numeric',
        ],
        [
            'group.*' => 'You must select a valid group.',
            'role.*' => 'You must select a valid role.',
        ]
        );

        $user = User::find($user_id);

        if(empty($user))
            return redirect('/user_management')->withErrors(['error' => 'User not found in the  ' . env('APP_NAME') . '  hub.']);

        if($user->roles($request->input('group'))->count())
            return redirect('/user_management/groups/'.$user_id)->withErrors(['error' => 'The user already has a role in the selected group.']);

        $user->groups()->attach($request->input('group'));
        $user->roles($request->input('group'))->attach($request->input('role'), ['group_id'=> $request->input('group')]);

        return redirect('/user_management/groups/'.$user_id)->with('msg', 'User added to group.');        
    }

    public function remove_group(Request $request){
        $user = WpUser::find($request->input('user_id'));

        if(empty($user))
            return redirect('/user_management')->withErrors(['error' => 'User not found in the  ' . env('APP_NAME') . '  hub.']);
        
        $user->roles($request->input('group_id'))->detach();
        $user->groups()->detach($request->input('group_id'));

        return redirect('/user_management/groups/'.$request->input('user_id'))->with('msg', 'User removed from group.');
    }
}
