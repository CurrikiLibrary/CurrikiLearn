<?php

namespace App\Repositories;

use App\User;
use App\WpUser;
use Carbon\Carbon;
use App\Mail\ResetUserPassword;
use App\Mail\NewUserCredentials;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Contracts\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface {

    /**
     * Model class for this repository.
     *
     * @return \App\User
     */
    public function model() {
        return User::class;
    }

    /**
     * Setup user on sign up.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return WpUser
     */
    public function setupUserOnSignUp($username, $email, $password) {
        DB::beginTransaction();

        try
        {
            $user = User::where('user_login', $username)->first();
            // $user->groups()->attach(env('APP_HUB_ID'));
            // $user->roles(env('APP_HUB_ID'))->attach(3, [
            //     'group_id' => env('APP_HUB_ID')
            // ]);

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            throw $e;
        }

        // Send email with user credentials
        Mail::to($email)
        ->send(new NewUserCredentials([
            'username'=>$username,
            'password'=>$password
            ])
        );
    }

    /**
     * Reset User Password.
     *
     * @param int $userId
     */
    public function resetUserPassword($userId) {
        $password = str_random(8);

        DB::beginTransaction();

        try
        {
            $wpUser = WpUser::find($userId);
            $wpUser->user_pass = md5($password);
            $wpUser->save();

            User::where('userid', $userId)
            ->update([
                'indexrequired' => 'T',
                'indexrequireddate' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            throw $e;
        }

        // Send email with user password
        Mail::to($wpUser->user_email)
            ->send(
                new ResetUserPassword([
                    'username' => $wpUser->user_login,
                    'password' => $password
                ])
            );
    }

    public function updateUserPassword($userId, $password) {
        DB::beginTransaction();

        try
        {
            $wpUser = WpUser::find($userId);
            $wpUser->user_pass = md5($password);
            $wpUser->save();

            User::where('userid', $userId)
            ->update([
                'indexrequired' => 'T',
                'indexrequireddate' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            throw $e;
        }
    }

}