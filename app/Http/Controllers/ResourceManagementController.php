<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Rxsource;
use App\Resource;
use App\Level;
use App\LevelGrouping;
use App\Subject;
use App\Group;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Services\WpApiInterface;
use Illuminate\Validation\Rule;
use App\Contracts\Repositories\ResourceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ResourceManagementController extends Controller
{
    /**
     * The resource repository instance.
     *
     * @var ResourceRepositoryInterface $resourceRepositoryInterface
     */
    protected $resourceRepositoryInterface;

    /**
     * Create a new instance of ResourceManagementController.
     *
     * @param ResourceRepositoryInterface $resourceRepositoryInterface
     */
    public function __construct(ResourceRepositoryInterface $resourceRepositoryInterface)
    {
        $this->resourceRepositoryInterface = $resourceRepositoryInterface;
    }


    public $params = [
                'size' => 10,
                'page' => 1,
                'sort' => ['field'=>'lasteditdate','order'=>'desc'],
                'query' => '',
                'levels' => [],
                'subjects' => [],
                'groups' => []
            ];

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the currently authenticated user...
        $user = Auth::user();

        if(Session::has('mgmt_search_params'))
            $this->params = Session::get('mgmt_search_params');

        $this->handle_params($request);

        $params = $this->params;

        // All my resources
        $resource_ids = $user->resources->pluck('resourceid')->toArray();
        // All resources in my admin/mod groups
        $mod_groups = $user->getModGroups()->pluck('id');
        if(!empty($mod_groups)){
            $mod_resources = Rxsource::whereHas('resource', function($q){
                $q->whereDoesntHave('collection');
            })->whereIn('group_id', $mod_groups)->get()->pluck('resource_id')->toArray();
            $resource_ids = array_unique(array_merge($resource_ids, $mod_resources));
        }
        // Querying
        $query = Resource::select('resourceid','title', 'type', 'description')->whereIn('resourceid', $resource_ids);

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
            $query->whereHas('educationLevels', function($q) use ($queryLevels){
                $q->whereIn('levelid', $queryLevels);
            });
        }

        if(!empty($this->params['subjects'])){
            $query->whereHas('subjectAreas', function($q) use ($params){
                $q->whereIn('subjectid', $params['subjects']);
            });
        }

        if(!empty($this->params['query'])){
            $query->where(function($q) use ($params) {
                $q->where('title', 'like', '%'.$params['query'].'%')
                    ->orWhere('description', 'like', '%'.$params['query'].'%');
            });
        }

        // Sorting
        if($this->params['sort']['field'] == 'title')
            $query->orderBy('resources.title', $this->params['sort']['order']);
        else
            $query->orderBy($this->params['sort']['field'], $this->params['sort']['order']);
        
        $result = $query->paginate(10);

        $subjects = Subject::orderBy('displayname', 'ASC')->get();
        $level_groups = LevelGrouping::all();
        $groups = Group::where('parent_id', env('APP_HUB_ID'))->orWhere('id', env('APP_HUB_ID'))->get();

        return view('resource_management.newindex', [
            'result' => $result,
            'resources' => $result,
            'query' => $query,
            'section' => 'resources',
            'level_groups' => $level_groups,
            'subjects' => $subjects,
            'groups' => $groups,
            'params' => $this->params
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

        Session::put('mgmt_search_params', $this->params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        return view('resource_management.show', compact('resource'));
    }

    public function create(Request $request){

        // Check if we're adding a new resource to an existing collection
        $collection = null;
        if($request->filled('collection')){
            $collection = Resource::find($request->input('collection'));
            if(empty($collection))
                return redirect('/resource_management')->withErrors(['error'=>'Collection not found.']);
        }
        // Get the currently authenticated user...
        $user = Auth::user();

        $levels = LevelGrouping::all();
        $subjects = Subject::orderBy('displayname', 'ASC')->pluck('displayname', 'subjectid');

        return view('resource_management.newcreate', compact('user', 'levels', 'subjects', 'collection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        // Get the currently authenticated user...
        $user = Auth::user();

        $levels = LevelGrouping::all();
        $selectedLevelGroupings = LevelGrouping::whereHas('levels', function($q) use($resource){
            $q->whereIn('levelid', $resource->educationLevels->pluck('levelid')->toArray());
        })->pluck('id')->toArray();
        
        $subjects = Subject::orderBy('displayname', 'ASC')->get();
        $selectedSubjects = [];
        foreach($resource->subjectAreas as $area){
            $selectedSubjects[] = $area->subject->subjectid;
        }

        return view('resource_management.newedit', compact('user', 'levels', 'subjects', 'resource', 'selectedLevelGroupings', 'selectedSubjectAreas', 'selectedSubjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @param WpApiInterface $wpApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource, WpApiInterface $wpApi)
    {

        $request->validate([
            'group' => 'required',
            'title' => 'required',
            'description' => 'required',
            'education_levels' => 'required|array',
            // 'subjectarea' => 'required|array',
            // 'resource_type' => [
            //     'required',
            //     Rule::in(['resource', 'collection']),
            // ],
            'content' => 'required',
            'keywords' => 'required',
            'mediatype' => 'required',
        ]);

        // Getting education levels array
        $levels = [];
        $queryLevelGroupings = LevelGrouping::whereIn('id', $request->input('education_levels'))->get();
        foreach($queryLevelGroupings as $queryGrouping){
            $levels = array_merge($levels, $queryGrouping->levels()->pluck('levelid')->toArray());
        }

        $params = [
            'form_params' => [
                'custom_group_id' => $request->input('group'),
                'groupid' => env('CURRIKI_GROUP_ID'),
                'prid' => $request->input('collection', null),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'education_levels' => $levels,
                'subjectarea' => $request->input('areas'),
                'resource_type' => (strtolower($request->input('resourceType', 'resource')) == 'collection') ? 'collection' : 'resource',
                'content' => $request->input('content'),
                'resourceid' => $request->input('resourceid'),
                'keywords' => $request->input('keywords'),
                'mediatype' => $request->input('mediatype'),
                'access' => 'private',
                'licenseid' => 1,
                'language' => 'eng',
                'active' => true,
                'resourcefiles' => $request->input('resourcefiles')
            ]
        ];

        $response = $wpApi->request('post', 'wp-json/genesis-curriki/v1/resources/0/', $params);

        if ($response === false) {
            $errorMessage = 'Failed to save resource.';
        } else if (get_class($response) == "Illuminate\Http\RedirectResponse") {
            return $response;
        } else {
            $responseContent = $response->getBody()->getContents();
            $strLength = strrpos($responseContent, "<!DOCTYPE html>");
            $responseContentJson = substr($responseContent, 0, $strLength);

            $responseJson = json_decode($responseContentJson);

            if (json_last_error() == JSON_ERROR_NONE) {
                if (isset($responseJson->resourceid)) {
                    $this->resourceRepositoryInterface->setupResourceOnCreate($responseJson->resourceid);
                    $res = Resource::find($responseJson->resourceid);
                    $res->groups()->detach();
                    //$res->groups()->attach(env('APP_HUB_ID')); // All resources must belong to the hub for search purposes
                    $res->groups()->attach($request->input('group'));

                    if($request->input('save_and_view', '') == 'view')
                        return redirect('/resource/'.$responseJson->resourceid);
                    else if($request->input('save_and_view', '') == 'edit')
                        return redirect('/resource_management/'.$responseJson->resourceid.'/edit');
                    else
                        return redirect('resource_management')->with('success', 'Resource Saved!');
                } else {
                    $errorMessage = $responseJson->msg;
                }
            } else {
                if(!empty($responseContentJson)){
                    $errorMessage = $responseContentJson;
                } else {
                    $errorMessage = 'Failed to save resource.';
                }
            }
        }

        return redirect('resource_management')->with('danger', $errorMessage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @param WpApiInterface $wpApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource, WpApiInterface $wpApi)
    {
        $url = "wp-json/genesis-curriki/v1/resources/" . $resource->resourceid;

        $response = $wpApi->request('delete', $url);

        if ($response === false) {
            return redirect('logout_api');
        } else if ($response->getStatusCode() == 200) {
            Session::flash('msg', 'Resource deleted.');
            return redirect('resource_management');            
        } else {
            return redirect('resource_management')->withErrors(['error' => 'Failed to delete resource.']);
        }
    }
}
