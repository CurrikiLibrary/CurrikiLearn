<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUserRole extends Model
{
    protected $table = 'custom_group_user_roles';
    protected $primaryKey = 'id';
    protected $fillable = ['group_id', 'user_id', 'role_id'];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
