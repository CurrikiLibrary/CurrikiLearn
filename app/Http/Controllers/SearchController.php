<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Level;
use App\LevelGrouping;
use App\Subject;
use App\ApiResource;
use App\Group;
use App\Resource;

class SearchController extends Controller
{

    public $params = [
                'size' => 10,
                'page' => 1,
                'sort' => ['field'=>'lasteditdate','order'=>'desc'],
                'query' => '',
                'levels' => [],
                'subjects' => [],
                'groups' => []
            ];

    public function index(Request $request)
    {
        if($request->has('wide')){
            if($request->get('wide') == 'true')
                Session::put('wide', true);
            else
                Session::forget('wide');
        }

        if(Session::has('search_params'))
            $this->params = Session::get('search_params');

        $this->handle_params($request);

        $params = $this->params;

        $query = Group::find(env('APP_HUB_ID'))->resources()->whereDoesntHave('collection');

        if(!empty($this->params['groups'])){
            $query->whereHas('groups', function($q) use ($params) {
                $q->whereIn('custom_groups.id', $params['groups']);
            });
        }

        if(!empty($this->params['levels'])){
            $queryLevels = [];
            $queryLevelGroupings = LevelGrouping::whereIn('id', $this->params['levels'])->get();
            foreach($queryLevelGroupings as $queryGrouping){
                $queryLevels = array_merge($queryLevels, $queryGrouping->levels()->pluck('levelid')->toArray());
            }
            $query->whereHas('educationLevels', function($q) use ($queryLevels) {
                $q->whereIn('levelid', $queryLevels);
            });
        }

        if(!empty($this->params['subjects'])){
            $query->whereHas('subjectAreas', function($q) use ($params) {
                $q->whereIn('subjectid', $params['subjects']);
            });
        }

        if(!empty($this->params['query'])){
            $query->where(function($q) use ($params) {
                $q->where('title', 'like', '%'.$params['query'].'%')
                  ->orWhere('content', 'like', '%'.$params['query'].'%');
            });
        }

        // Sorting
        $results = $query->orderBy($this->params['sort']['field'], $this->params['sort']['order'])->paginate(10);

    	$subjects = Subject::orderBy('displayname', 'ASC')->get();
    	$level_groups = LevelGrouping::all();
        $groups = Group::where('parent_id', env('APP_HUB_ID'))->orWhere('id', env('APP_HUB_ID'))->get(); // Hardcoding groups with hub group as parent

        return view('search.search', [
        	'level_groups' => $level_groups,
        	'subjects' => $subjects,
            'groups' => $groups,
            'results' => $results,
        	'params' => $this->params,
            'debug' => $results
        ]);
    }

    // Handles search parameters and filters
    private function handle_params($request){
        if($request->has('sort')){
            if($request->input('sort') == 'date_asc')
                $this->params['sort'] = ['field'=>'lasteditdate', 'order'=>'asc'];
            else if($request->input('sort') == 'date_desc')
                $this->params['sort'] = ['field'=>'lasteditdate', 'order'=>'desc'];
            else if($request->input('sort') == 'al_asc')
                $this->params['sort'] = ['field'=>'title', 'order'=>'asc'];
            else if($request->input('sort') == 'al_desc')
                $this->params['sort'] = ['field'=>'title', 'order'=>'desc'];
            else
                $this->params['sort'] = ['field'=>'lasteditdate', 'order'=>'desc'];
        }

        if($request->has('page'))
            $this->params['page'] = $request->input('page');

        if($request->has('query'))
            $this->params['query'] = $request->input('query');

        // Add filters
        if($request->filled('level') && !in_array($request->input('level'), $this->params['levels']))
            $this->params['levels'][] = $request->input('level');
        if($request->filled('subject') && !in_array($request->input('subject'), $this->params['subjects']))
            $this->params['subjects'][] = $request->input('subject');
        if($request->filled('group') && !in_array($request->input('group'), $this->params['groups']))
            $this->params['groups'][] = $request->input('group');

        // Remove filters
        if($request->filled('rlevel') && in_array($request->input('rlevel'), $this->params['levels']))
            unset($this->params['levels'][array_search($request->input('rlevel'), $this->params['levels'])]);
        if($request->filled('rsubject') && in_array($request->input('rsubject'), $this->params['subjects']))
            unset($this->params['subjects'][array_search($request->input('rsubject'), $this->params['subjects'])]);
        if($request->filled('rgroup') && in_array($request->input('rgroup'), $this->params['groups']))
            unset($this->params['groups'][array_search($request->input('rgroup'), $this->params['groups'])]);

        if($request->filled('rall')){   // Remove all filters
            $this->params['levels'] = [];
            $this->params['subjects'] = [];
            $this->params['groups'] = [];
        } else{     // Reset indexes
            array_values($this->params['levels']);
            array_values($this->params['subjects']);
            array_values($this->params['groups']);
        }

        Session::put('search_params', $this->params);
    }
}
