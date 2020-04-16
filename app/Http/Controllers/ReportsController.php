<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\ResourceView;
use App\Resource;
use App\Group;
use App\LevelGrouping;

class ReportsController extends Controller
{
    public function index(Request $request)
    {	
    	$monthlyViews =  ResourceView::whereBetween(
    		'viewed_at',
    		[
    			Carbon::now()->startOfMonth(),
    			Carbon::now()
    		])->count();

    	$monthlyCreations = Group::find(env('APP_HUB_ID'))->resources()
    		->whereBetween('contributiondate',[Carbon::now()->startOfMonth(),Carbon::now()])
    		->count();

    	$totalResources = Group::find(env('APP_HUB_ID'))->resources()->count();
    	$totalUsers = Group::find(env('APP_HUB_ID'))->wp_users()->count();



    	$monthlyViewsByDistrict = $this->get_monthly_views_by_district();
    	$monthlyViewsByLevel = $this->get_monthly_views_by_level();
        $viewsLineData = $this->get_monthly_views();
        $creationLineData = $this->get_monthly_views();
        $creationLineData['datasets'][0]['label'] = 'Monthly Resource Creations';
        $users = Group::find(env('APP_HUB_ID'))->wp_users()->take(5)->get();

        return view('reports.index',[
        	'monthlyViews' => $monthlyViews,
        	'monthlyCreations' => $monthlyCreations,
        	'totalResources' => $totalResources,
        	'totalUsers' => $totalUsers,
        	'monthlyViewsByDistrict' => json_encode($monthlyViewsByDistrict),
        	'monthlyViewsByLevel' => json_encode($monthlyViewsByLevel),
            'viewsLineData' => json_encode($viewsLineData),
            'creationLineData' => json_encode($creationLineData),
            'users' => $users
        ]);
    }

    private function get_monthly_views_by_district(){
        /*
    	$monthlyViewsByDistrictQuery = DB::table('custom_resource_views')
    		->select(DB::raw('count(*) as views, custom_resource_views.group_id, custom_groups.name as group_name'))
    		->join('custom_groups', 'custom_resource_views.group_id', '=', 'custom_groups.id')
    		->groupBy('custom_resource_views.group_id')
    		->groupBy('custom_groups.name')
    		->whereBetween('viewed_at', [Carbon::now()->startOfMonth(), Carbon::now()])
    		->get()
    		->toArray();

    	$monthlyViewsByDistrict = ['datasets'=>[['data'=>[]]], 'labels'=>[]];
    	foreach($monthlyViewsByDistrictQuery as $row){
    		$monthlyViewsByDistrict['datasets'][0]['data'][] = $row->views;
    		$monthlyViewsByDistrict['labels'][] = $row->group_name;
    	}
        */
        // TEMP
        $groups = Group::where('parent_id', env('APP_HUB_ID'))->get();
        $monthlyViewsByDistrict = ['datasets'=>[['data'=>[]]], 'labels'=>[]];
        foreach ($groups as $group) {
            $monthlyViewsByDistrict['datasets'][0]['data'][] = rand(1,500);
            $monthlyViewsByDistrict['labels'][] = $group->name;
        }
    	return $monthlyViewsByDistrict;
    }

    private function get_monthly_views_by_level(){
        /*
    	$query = DB::table('custom_resource_views')
    		->select(DB::raw('count(*) as views, educationlevels.displayname as name'))
    		->join('resource_educationlevels', 'custom_resource_views.resource_id', '=', 'resource_educationlevels.resourceid')
    		->join('educationlevels', 'resource_educationlevels.educationlevelid', '=', 'educationlevels.levelid')
    		->groupBy('educationlevels.levelid')
    		->groupBy('educationlevels.displayname')
    		->whereBetween('viewed_at', [Carbon::now()->startOfMonth(), Carbon::now()])
    		->get()
    		->toArray();
    	$monthlyViewsByLevel = ['datasets'=>[['data'=>[]]], 'labels'=>[]];
    	foreach($query as $row){
    		$monthlyViewsByLevel['datasets'][0]['data'][] = $row->views;
    		$monthlyViewsByLevel['labels'][] = $row->name;
    	}
        */
        // TEMP
        $levels = LevelGrouping::all();
        $monthlyViewsByLevel = ['datasets'=>[['data'=>[]]], 'labels'=>[]];
        foreach ($levels as $level) {
            $monthlyViewsByLevel['datasets'][0]['data'][] = rand(1,500);
            $monthlyViewsByLevel['labels'][] = $level->display_name;
        }
    	return $monthlyViewsByLevel;
    }

    private function get_monthly_views(){
        $data = ['datasets'=>[['label'=>'Monthly Views','data'=>[
            0,0,5,26,125,243,455,621,712,798,850,776
        ]]], 'labels'=>['January', 'February', 'March', 'April', 'May', 'June', 'July']];
        return $data;
    }
}
