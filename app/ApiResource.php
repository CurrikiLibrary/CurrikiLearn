<?php

namespace App;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Level;
use App\Subject;

// This model handles all communication to the resource API
// TODO:
//		- Abstract apiUrl and partnerId to config .env values
//		- Create more flexible query system
class ApiResource
{
    protected $apiUrl = 'https://www.curriki.org/search-api-2-0';
    protected $partnerId = '40001';
    public $error = null;

    public function search($params) {
    	$query = $this->build_query($params);
    	$start =  (intval($params['page']) - 1) * intval($params['size']);
    	$req_url = $this->apiUrl.
    		'?query=('.$query.')'.
    		'&q.parser=lucene'.
    		'&partnerid='.$this->partnerId.
    		'&size='.$params['size'].
    		'&start='.$start.
    		'&sort='.$params['sort'];
        try {
        	$r = file_get_contents($req_url);
            $result = json_decode($r, true);
        } catch (\Exception $e){
            $this->error = 'Request to API failed.';
            return false;
        }
        $result['url'] = $req_url;
        $result['paginator'] = new LengthAwarePaginator(
        	$result['response'], 
        	intval($result['status']['found']),
        	intval($params['size']),
        	intval($params['page'])
        );
        return $result;
    }

    public function find($id){
    	$req_url = $this->apiUrl.
    		'?query=(id:'.$id.')'.
    		'&q.parser=lucene'.
    		'&partnerid='.$this->partnerId;
        try {
        	$r = file_get_contents($req_url);
            $result = json_decode($r, true);
        } catch (\Exception $e){
            $this->error = 'Request to API failed. Resource not found.';
            return false;
        }
        return $result['response'][0];
    }

    // Builds out the lucene query with the specified parameters
    private function build_query($params){
    	$query = '';
    	// Adding text search query
    	$query .= "title:'".$params['query']."'";

    	// Adding levels
    	if(!empty($params['levels'])){
    		// Fetching levels
    		$levels = Level::whereIn('levelid', $params['levels'])->get();
    		$last = $levels->count() - 1;
    		$query .= ' AND (';
    		foreach($levels as $i=>$level){
    			if($i == $last)
    				$query .= 'educationlevel:'.$level->identifier.')';
    			else
    				$query .= 'educationlevel:'.$level->identifier.' OR ';
    		}
    	}

    	// Adding subjects
    	if(!empty($params['subjects'])){
    		// Fetching subjects
    		$subjects = Subject::whereIn('subjectid', $params['subjects'])->get();
    		$last = $subjects->count() - 1;
    		$query .= ' AND (';
    		foreach($subjects as $i=>$subject){
    			if($i == $last)
    				$query .= 'subjectarea:"'.$subject->subject.'")';
    			else
    				$query .= 'subjectarea:"'.$subject->subject.'" OR ';
    		}
    	}

    	return urlencode($query);
    }

    public function hasError(){
    	return ($this->error) ? $this->error : false;
    }

    public function getError(){
    	return ($this->hasError()) ? $this->error : '';
    }
}
