<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceView extends Model
{
    protected $table = 'custom_resource_views';
    
    public function resource(){
        return $this->belongsTo('App\Resource', 'resource_id');
    }
}
