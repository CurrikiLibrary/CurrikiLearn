<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'educationlevels';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'levelid';

    /**
     * The resources that belong to the education level.
     */
    public function resources()
    {
        return $this->belongsToMany('App\Resource', 'resource_educationlevels', 'educationlevelid', 'resourceid');
    }

    public function level_groupings(){
        return $this->belongsToMany('App\LevelGrouping', 'custom_level_grouping_levels', 'level_id', 'level_grouping_id');
    }
}
