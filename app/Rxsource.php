<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rxsource extends Model
{
    protected $table = 'custom_group_resources';
    protected $fillable = ['group_id', 'resource_id'];
    /**
     * The groups that belong to the resource.
     */
    public function groups()
    {
        return $this->belongsTo('App\Group', 'group_id');
    }
    
    public function resource(){
        return $this->belongsTo('App\Resource', 'resource_id');
    }

}
