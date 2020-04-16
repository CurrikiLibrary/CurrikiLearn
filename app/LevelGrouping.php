<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelGrouping extends Model
{
    protected $table = 'custom_level_groupings';

    public function levels(){
        return $this->belongsToMany('App\Level', 'custom_level_grouping_levels', 'level_grouping_id', 'level_id');
    }
}
