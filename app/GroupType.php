<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_group_types';

    /**
     * Get the groups for the group type.
     */
    public function groups()
    {
        return $this->hasMany('App\Group');
    }
}
