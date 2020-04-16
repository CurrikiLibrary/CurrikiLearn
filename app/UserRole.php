<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_user_roles';

    /**
     * The roles that belong to the user.
     * @param int $groupId
     */
    public function users($groupId)
    {
        return $this->belongsToMany('App\User', 'custom_group_user_roles', 'role_id', 'user_id')->wherePivot('group_id', $groupId);
    }
}
