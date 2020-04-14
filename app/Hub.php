<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Hub extends Model
{
    protected $table = 'custom_groups';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('parent_id', function (Builder $builder) {
            $builder->where('parent_id', NULL);
        });
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'custom_group_users', 'group_id', 'user_id')->withTimestamps();
    }


    // Same user data but coming from the main wordpress user table in curriki
    public function wp_users()
    {
        return $this->belongsToMany('App\WpUser', 'custom_group_users', 'group_id', 'user_id')->withTimestamps();
    }

    /**
     * The resources that belong to the group.
     */
    public function resources()
    {
        return $this->belongsToMany('App\Resource', 'custom_group_resources', 'group_id', 'resource_id');
    }

}
