<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'educationlevels';
    protected $primaryKey = 'levelid';

    public function level_groupings(){
        return $this->belongsToMany('App\LevelGrouping', 'custom_level_grouping_levels', 'level_id', 'level_grouping_id');
    }
}
