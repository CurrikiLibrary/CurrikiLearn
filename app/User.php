<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\GroupUserRole;
use App\UserRole;
use App\LevelGrouping;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'userid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The list of the model's date columns.
     *
     * @var string
     */
    protected $dates = ['registerdate'];

    public $timestamps = false;

    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'custom_group_users', 'user_id', 'group_id')->withTimestamps();
    }

    // Gets groups taking into account user roles
    // Branch admins get admin rights to all child groups

    public function getAllGroups(){
        $groups = $this->groups;
        foreach($this->groups as $group){
            if(!$this->hasRole('branch_admin', $group->id))
                continue;
            $children = $group->getAllChildren();
            $groups = $groups->merge($children);
        }
        return $groups->unique();
    }

    // Gets groups where the user has mod rights

    public function getModGroups(){
        $roles = UserRole::whereIn('name', ['admin', 'branch_admin', 'moderator'])->get()->pluck('id');
        $branchAdminRoleId = UserRole::whereIn('name', ['branch_admin'])->first()->id;

        $groupRoles = GroupUserRole::where('user_id', $this->userid)
            ->whereIn('role_id', $roles)
            ->get();

        // Checking if branch admin
        $branchAdminGroups = $groupRoles->filter(function($value, $key) use ($branchAdminRoleId) {
            return ($value->role_id == $branchAdminRoleId) ? true : false;
        });
        
        $groups = $groupRoles->pluck('group');

        foreach($branchAdminGroups as $g){
            $children = $g->group->getAllChildren();
            $groups = $groups->merge($children);
        }

        return $groups->unique();
    }

    // Gets groups where the user has admin rights

    public function getAdminGroups(){
        $roles = UserRole::whereIn('name', ['admin', 'branch_admin'])->get()->pluck('id');
        $branchAdminRoleId = UserRole::whereIn('name', ['branch_admin'])->first()->id;

        $groupRoles = GroupUserRole::where('user_id', $this->userid)
            ->whereIn('role_id', $roles)
            ->get();

        // Checking if branch admin
        $branchAdminGroups = $groupRoles->filter(function($value, $key) use ($branchAdminRoleId) {
            return ($value->role_id == $branchAdminRoleId) ? true : false;
        });
        
        $groups = $groupRoles->pluck('group');

        foreach($branchAdminGroups as $g){
            $children = $g->group->getAllChildren();
            $groups = $groups->merge($children);
        }

        return $groups->unique();
    }

    // Get the hub the user belongs to
    public function hub(){
        return $this->belongsToMany('App\Hub', 'custom_group_users', 'user_id', 'group_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles($groupId = null)
    {
        if($groupId == null)
            $groupId = $this->groups()->first()->pluck('id');
        return $this->belongsToMany('App\UserRole', 'custom_group_user_roles', 'user_id', 'role_id')->withTimestamps()->wherePivot('group_id', $groupId);
    }

    // Determines if a user has a specific role in a certain group
    // If no group is provided, it checks all groups
    public function hasRole($role_name, $group_id = null){
        if($group_id){
            if($this->roles($group_id)->where('name', $role_name)->count())
                return true;
            else
                return false;
        } else {
            $role = UserRole::where('name', $role_name)->first();
            $userRole = GroupUserRole::where('group_id', '<>', env('APP_HUB_ID'))->where('user_id', $this->userid)->where('role_id', $role->id)->first();
            return ($userRole) ? true : false;
        }
    }

    /**
     * Get the resources for the user.
     */
    public function resources()
    {
        return $this->hasMany('App\Resource', 'contributorid', 'userid');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Get the language record associated with the user.
     */
    public function languageName()
    {
        return $this->hasOne('App\Language', 'language', 'language');
    }

    /**
     * Get the logins for the user.
     */
    public function logins()
    {
        return $this->hasMany('App\Login', 'userid', 'userid');
    }

    public function getTokenAttribute()
    {
        // Check if token exists in session
        if (session()->has('token')) {
            // If so return token
            return session('token');
        }

        // Return Null
        return NULL;
    }

    // each user has prefered or default levels and subjects. Here are the relationships for that

    public function prefered_levels(){
        return $this->belongsToMany('App\Level', 'custom_prefered_user_levels', 'user_id', 'level_id');
    }

    public function prefered_subjects(){
        return $this->belongsToMany('App\Level', 'custom_prefered_user_subjects', 'user_id', 'subject_id');
    }

    /**
     * The subjectareas that belong to the user.
     */
    public function subjectareas()
    {
        return $this->belongsToMany('App\SubjectArea', 'user_subjectareas', 'userid', 'subjectareaid');
    }

    /**
     * The subjectareas that belong to the user.
     */
    public function educationlevels()
    {
        return $this->belongsToMany('App\EducationLevel', 'user_educationlevels', 'userid', 'educationlevelid');
    }

    public function getEducationLevelGroupsAttribute(){
        $result = collect([]);
        $levels = $this->educationlevels()->with('level_groupings')->get();

        foreach($this->educationlevels as $level) {
            foreach($level->level_groupings as $group){
                if($result->contains('id', $group->id))
                    continue;
                $result->push($group);
            }
        }
        
        return $result;
    }
}
