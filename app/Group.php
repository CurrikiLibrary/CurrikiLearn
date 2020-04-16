<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Group extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_groups';

    /**
     * The users that belong to the group.
     */
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
/*    public function resources()
    {
        return $this->belongsToMany('App\Resource')->withTimestamps();
    }*/

    public function resources()
    {
        return $this->belongsToMany('App\Resource', 'custom_group_resources', 'group_id', 'resource_id');
    }

    /**
     * Get the type that owns the group.
     */
    public function groupType()
    {
        return $this->belongsTo('App\GroupType', 'group_type_id');
    }

    /**
     * Get the meta data for the group.
     */
    public function metaData()
    {
        return $this->hasMany('App\GroupMeta');
    }

    // Gets children groups one level deep
    public function children(){
        return $this->hasMany('App\Group', 'parent_id', 'id');
    }

    // Gets all children, cascading through child relationships

    public function getAllChildren(){
        $groups = [$this];
        foreach($groups as &$group){
            foreach($group->children as $child){
                $groups[] = $child;
            }
        }
        return new Collection($groups);
    }

    // Get parent group
    public function parent(){
        return $this->belongsTo('App\Group', 'parent_id', 'id');
    }

    // All groups ultimately belong to a parent group called a hub.
    // They are stored in the same table, the only difference is that
    // the hub doesn't have a parent.

    // This function returns the parent hub of the instanced group
    public function getHub(){
        if($this->parent_id == null)
            return $this;
        
        return $this->parent->getHub();
    }
}
