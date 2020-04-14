<?php

namespace App\Repositories;

use App\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Contracts\Repositories\ResourceRepositoryInterface;

class ResourceRepository extends BaseRepository implements ResourceRepositoryInterface {

    /**
     * Model class for this repository.
     *
     * @return \App\Resource
     */
    public function model() {
        return Resource::class;
    }

    /**
     * Setup resource on create.
     *
     * @param int $resourceid
     * @param Request $request
     */
    public function setupResourceOnCreate($resourceid) {

        DB::beginTransaction();

        try
        {
            $resource = Resource::where('resourceid', $resourceid)->first();
            $resource->groups()->attach(env('NASSAU_HUB_ID'));
            $resource->visibility(env('NASSAU_HUB_ID'))->attach(3, [
                'group_id' => env('NASSAU_HUB_ID')
            ]);

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            throw $e;
        }
    }
}