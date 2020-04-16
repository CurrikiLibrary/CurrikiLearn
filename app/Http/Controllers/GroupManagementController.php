<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Group;
use App\User;
use App\WpUser;
use App\UserRole;
use App\GroupUserRole;

class GroupManagementController extends Controller
{
    public function index(Request $request)
    {
        // Get the currently authenticated user...
        $user = Auth::user();
        $groups = $user->getAllGroups();
        $adminGroups = $user->getAdminGroups()->pluck('id')->toArray();

        $query = '';
        $queryStringArray = [];
        if($request->has('query')) {
            $query = $request->input('query');
            $groups = $groups->where('name', 'like', "%$query%");
            $queryStringArray = ['query' => $query];
        }

        //$groups = $groups->paginate(5)->appends($queryStringArray);

        return view('group_management.newindex', [
            'groups' => $groups,
            'adminGroups' => $adminGroups,
            'query' => $query,
            'section' => 'groups'
        ]);
    }

    public function view(Request $request, $id){
        $query = ($request->filled('query')) ? $request->input('query') : '';
        $roles = UserRole::whereIn('name', ['admin', 'moderator', 'member'])->get();
        $group = Group::find($id);
        $users = $group->wp_users()
            ->where(function($q) use ($query){
                $q->where('display_name', 'LIKE', '%'.$query.'%')
                ->orWhere('user_email', 'LIKE', '%'.$query.'%');
            })
            ->paginate(10);
        return view('group_management.view', [
            'group'=>$group,
            'roles' => $roles,
            'users' => $users,
            'query' => $query
        ]);
    }

    // Finds users in the hub to which the group_id belongs
    public function user_search(Request $request, $group_id){
        if($request->has('user_id')){
            $user = WpUser::find($request->input('user_id'));
            $response = [
                'name' => $user->display_name,
                'id' => $user->ID,
                'email' => $user->user_email,
                'avatar_filename' => $user->user->uniqueavatarfile
            ];
        } else { 
            $users = Group::find($group_id)
                ->getHub()
                ->wp_users()
                ->where('display_name', 'LIKE', '%'.$request->term.'%')
                ->get();

            $response = [];
            foreach ($users as $user) {
                $response[] = [
                    'label' => $user->display_name,
                    'value' => $user->display_name,
                    'user_id' => $user->ID,
                    'email' => $user->user_email,
                    'avatar_filename' => $user->user->uniqueavatarfile
                ];
            }
        }       

        return json_encode($response);
    }

    public function add_user(Request $request, $group_id){
        $group = Group::find($group_id);
        if($group->wp_users->where('ID', $request->userid)->count() > 0)
            return back()->withErrors(['error' => 'The user you selected already belongs to this group.']);

        $group->wp_users()->attach($request->userid);
        GroupUserRole::create([
            'group_id' => $group_id,
            'user_id' => $request->userid,
            'role_id' => $request->role
        ]);
        Session::flash('msg', 'User successfully added to group.');
        return redirect('/group_management/'.$group_id);
    }

    public function update_user(Request $request, $group_id){
        GroupUserRole::where('group_id', $group_id)->where('user_id', $request->user_id)->delete();
        GroupUserRole::create([
            'group_id' => $group_id,
            'user_id' => $request->user_id,
            'role_id' => $request->role
        ]);
        return redirect('/group_management/'.$group_id);
    }

    public function remove_user(Request $request, $group_id){
        $user = WpUser::find($request->input('user_id'));

        if(empty($user))
            return redirect('/user_management')->withErrors(['error' => 'User not found in the ' . env('APP_NAME') . ' hub.']);
        
        $user->roles($group_id)->detach();
        $user->groups()->detach($group_id);

        return redirect('/group_management/'.$group_id)->with('msg', 'User removed from group.');
    }
}
