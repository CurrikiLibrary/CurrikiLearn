<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Resource;

class ResourceController extends Controller
{
    public function view(Request $request, $id)
    {
        $resource = Resource::find($id);
        if(empty($resource))
            return redirect('/')->withErrors(["error"=>'Resource not found.']);

        return view('resource.view', [
        	'resource' => $resource
        ]);
    }
}
