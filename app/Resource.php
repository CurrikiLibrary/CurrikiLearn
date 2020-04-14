<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'resourceid';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The list of the model's date columns.
     *
     * @var string
     */
    protected $dates = ['contributiondate', 'lasteditdate'];

    /**
     * The groups that belong to the resource.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'custom_group_resources', 'resource_id', 'group_id')->withTimestamps();
    }

    /**
     * The visibility that belong to the resource.
     * @param int $groupId
     */
    public function visibility($groupId)
    {
        // return $this->belongsToMany('App\UserRole', 'group_user_roles', 'user_id', 'role_id')->withTimestamps();
        return $this->belongsToMany('App\ResourceVisibilityOption', 'custom_group_resource_visibility_options', 'resource_id', 'visibility_id')->withTimestamps()->wherePivot('group_id', $groupId);
    }

    /**
     * The education levels that belong to the resource.
     */
    public function educationLevels()
    {
        return $this->belongsToMany('App\EducationLevel', 'resource_educationlevels', 'resourceid', 'educationlevelid');
    }

    /**
     * The subject areas that belong to the resource.
     */
    public function subjectAreas()
    {
        return $this->belongsToMany('App\SubjectArea', 'resource_subjectareas', 'resourceid', 'subjectareaid');
    }

    /**
     * The subject areas by subject that belong to the resource.
     */
    public function subjectAreasBySubject()
    {
        return $this->subjectAreas->groupBy(function ($subjectarea, $key) {
            return $subjectarea->subject->displayname;
        });
    }

    /**
     * Get the user that owns the subject area.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'contributorid', 'userid');
    }

    /**
     * Get the files for the resource.
     */
    public function files()
    {
        return $this->hasMany('App\ResourceFile', 'resourceid', 'resourceid');
    }

    // Returns unique education level groupings for this resource
    public function getEducationLevelGroupings(){
        $groupings = [];
        foreach($this->educationLevels as $level){
            foreach ($level->level_groupings as $grouping) {
                $groupings[] = $grouping;
            }
        }
        $col = collect($groupings);
        return $col->unique(function($item){ return $item->id; });
    }

    public function collection(){
        return $this->belongsToMany('App\Resource', 'collectionelements', 'resourceid', 'collectionid');
    }

    public function sub_resources(){
        return $this->belongsToMany('App\Resource', 'collectionelements', 'collectionid', 'resourceid');
    }
}
