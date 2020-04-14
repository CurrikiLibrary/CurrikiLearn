<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    protected $table = 'resourcefiles';
    
    /**
     * Get the resource that owns the file.
     */
    public function resource(){
        return $this->belongsTo('App\Resource', 'resourceid', 'resourceid');
    }
}
