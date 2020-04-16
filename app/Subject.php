<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subjectid';

    /**
     * Get the subject areas for the subject.
     */
    public function subjectAreas()
    {
        return $this->hasMany('App\SubjectArea', 'subjectid', 'subjectid');
    }
}
