<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMeta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_group_meta';

    /**
     * Get the group that owns the meta data.
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
