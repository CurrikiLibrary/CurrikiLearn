<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subjectareas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'subjectareaid';

    /**
     * The resources that belong to the education level.
     */
    public function resources()
    {
        return $this->belongsToMany('App\Resource', 'resource_subjectareas', 'subjectareaid', 'resourceid');
    }

    /**
     * Get the subject that owns the subject area.
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'subjectid', 'subjectid');
    }
}
