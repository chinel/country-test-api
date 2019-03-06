<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\Activity;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class CountryController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    /*
     * This method is used to retrieve all countries created
     * By a particular user
     * */
    public function index(){

        return $this->user->countries()
            ->get(['name','continent'])
            ->toArray();
    }

    /*
    * This method is used to retrieve a country by id
    * */
    public function show($id){

        $country = $this->user->countries()->find($id);

        if(!$country){
            return response()->json(
                ['success' => false,
                    'message' => 'Sorry country with id'. $id ." cannot be found"
                ], 400);
        }

        return $country;
    }



    /**
     * @SWG\Post(
     *   path="/api/countries",
     *   summary="creates a new country",
     *   operationId="getCountry",
     *      *      operationId="setupCountry",
     *      tags={"create counry"},
     *      consumes={"application/json"},
     *   @SWG\Parameter(
     *     name="name",
     *     in="path",
     *     description="name of country",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="continent",
     *     in="path",
     *     description="continent of country",
     *     required=true,
     *     type="string"
     *   ),
     *          @SWG\Parameter(
     *     name="token",
     *     in="path",
     *     description="token generated after login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */

    /*
    * This method is used to store a country
    * */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'continent' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ]);

        }


        $country = new  Country();
        $country->name = $request->name;
        $country->continent = $request->continent;

        if ($this->user->countries()->save($country)){
            $activity =  new Activity();
            $activity->comment = " created a country";
            $this->user->activities()->save($activity);
            return response()->json([
                'success' => true,
                'country' => $country
            ]);
        }else{

            return response()->json([
                'success' => false,
                'message' => 'Sorry country could not be saved'
            ], 500);
        }
    }




    /**
     * @SWG\Put(
     *   path="/api/country/{id}",
     *   summary="updates country",
     *   operationId="getCountry",
     *      *      operationId="setupCountry",
     *      tags={"updates counry"},
     *      consumes={"application/json"},
     *   @SWG\Parameter(
     *     name="name",
     *     in="path",
     *     description="name of country",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="continent",
     *     in="path",
     *     description="continent of country",
     *     required=true,
     *     type="string"
     *   ),
     *         @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="id of country to be passed via query string",
     *     required=true,
     *     type="string"
     *   ),
     *         @SWG\Parameter(
     *     name="token",
     *     in="path",
     *     description="token generated after login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */

    /*
    * This method is used to update a country
    * */
    public function update(Request $request, $id){
        $country = $this->user->countries()->find($id);

        if(!$country){
            return response()->json([
                'success' => false,
                'message' => 'Sorry country with id '. $id . " cannot be found"
            ],400);
        }

        $updated = $country->fill($request->all())->save();

        if ($updated){
            $activity =  new Activity();
            $activity->comment = " updated a country";
            $this->user->activities()->save($activity);
            return response()->json([
                'success' => true
            ]);
        }else{

            return response()->json([
                'success' => false,
                'message' => 'Sorry, country could not be updated'
            ], 500);
        }

    }


    /**
     * @SWG\Delete(
     *   path="/api/country/{id}",
     *   summary="deletes country",
     *   operationId="getCountry",
     *      *      operationId="setupCountry",
     *      tags={"Deletes a  counry"},
     *      consumes={"application/json"},
     *         @SWG\Parameter(
     *     name="token",
     *     in="path",
     *     description="token generated after login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */

    /*
    * This method is used to delete a country by id
    * */
    public function delete($id){

        $country = $this->user->countries()->find($id);

        if(!$country){
            return response()->json([
                'success' => false,
                'message' => 'Sorry country with id '. $id . " could not be found"
            ], 400);
        }

        if($country->delete()){
            $activity =  new Activity();
            $activity->comment = " deleted a country";
            $this->user->activities()->save($activity);
            return response()->json([
                'success' => true
            ]);
        }else{

            return response()->json([
                'success' => false,
                'message' => 'Sorry country could not be deleted'
            ], 500);
        }
    }
}
