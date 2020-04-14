<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\User;
use App\Group;
use App\WpUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\Services\WpApiInterface;
use App\Contracts\Repositories\UserRepositoryInterface;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logoutApi');
    }

    /**
     * Handle a login request to the api application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param WpApiInterface $wpApi
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function loginApi(Request $request, WpApiInterface $wpApi)
    {
        $params = [
            'form_params' => [
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ]
        ];

        $response = $wpApi->request('post', 'wp-json/jwt-auth/v1/token', $params);

        if ($response === false) {
            return redirect('logout_api');
        } else if($response->getStatusCode() == 200){
            $body = json_decode($response->getBody(), true);
            $groups = Group::find(env('NASSAU_HUB_ID'))->getAllChildren();
            $userGroupIds = $groups->pluck('id');

            $user = User::whereHas('groups', function (Builder $query) use ($userGroupIds) {
                            $query->whereIn('custom_groups.id', $userGroupIds);
                        })->where('userid', $body['id'])->first();

            if($user) {
                session(['token' => $body['token']]);
                Auth::login($user);
                return redirect('/')->with('status', $response->getStatusCode());
            }

            return redirect('logout_api');
        } else if($response->getStatusCode() == 403){
            return redirect('/')->withErrors(['error'=>'Incorrect username or password.']);
        } else {
            return redirect('/')->withErrors(['error'=>'There was a problem trying to contact the login API.']);
        }

        return redirect('/');
    }

    /**
     * Handle a logout request to the api application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logoutApi(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }

    public function forgotpass(Request $request)
    {
        return view('forgotpass');
    }    

    public function doresetpass(Request $request, UserRepositoryInterface $userRepositoryInterface)
    {
        $user = WpUser::where('user_email', $request->input('email'))->first();

        if(empty($user))
            return redirect('/')->withErrors(['error' => 'User not found in the  ' . env('APP_NAME') . '  hub.']);

        if(!$user->groups()->exists())
            return redirect('/')->withErrors(['error' => 'This user is not an active member of the  ' . env('APP_NAME') . '  hub.']);

        $userRepositoryInterface->resetUserPassword($user->ID);
        return redirect('/')->with('msg', 'E-mail with new credentials sent.');;
    }    
}
