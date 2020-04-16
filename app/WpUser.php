<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WpUser extends Model
{
    protected $table = 'cur_users';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'custom_group_users', 'user_id', 'group_id')->withTimestamps();
    }

    // Linking to the user model
    public function user(){
    	return $this->hasOne('App\User', 'userid');
    }

    public function roles($groupId)
    {
        return $this->belongsToMany('App\UserRole', 'custom_group_user_roles', 'user_id', 'role_id')->wherePivot('group_id', $groupId);
    }

    // each user has prefered or default levels and subjects. Here are the relationships for that

    public function prefered_levels(){
        return $this->belongsToMany('App\Level', 'custom_prefered_user_levels', 'user_id', 'level_id');
    }

    public function prefered_subjects(){
        return $this->belongsToMany('App\Level', 'custom_prefered_user_subjects', 'user_id', 'subject_id');
    }
}
