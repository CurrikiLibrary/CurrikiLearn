<?php

namespace App\Http\View\Composers;

use App\Subject;
use App\LevelGrouping;
use Illuminate\View\View;

class EditUserProfileComposer
{
    /**
     * The educationLevels
     *
     * @var educationLevels
     */
    protected $educationLevels;

    /**
     * The subjects
     *
     * @var subjects
     */
    protected $subjects;

    /**
     * Create a new edit user profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->educationLevels = LevelGrouping::all()->pluck('display_name', 'id');
        $this->subjects = Subject::with(['subjectAreas' => function ($query) {
            $query->orderBy('displayname', 'asc');
        }])
        ->orderBy('displayname', 'asc')
        ->get();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $data = [
            'educationLevels'  => $this->educationLevels,
            'subjects' => $this->subjects
        ];

        $view->with($data);
    }
}
