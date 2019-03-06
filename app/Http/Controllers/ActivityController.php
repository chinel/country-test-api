<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;

class ActivityController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/api/activities",
     *   summary="History of all activities on the site",
     *   operationId="getCustomerRates",
     *     tags={"activities"},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */


    public function index(){
        return Activity::get()
            ->toArray();
    }
}
