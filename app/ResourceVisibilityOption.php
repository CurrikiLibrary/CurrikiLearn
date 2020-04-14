<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceVisibilityOption extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_resource_visibility_options';

    /**
     * The resources that belong to the Visibility.
     * @param int $groupId
     */
    public function resources($groupId)
    {
        return $this->belongsToMany('App\Resource', 'custom_group_resource_visibility_options', 'visibility_id', 'resource_id')->wherePivot('group_id', $groupId);
    }
}
