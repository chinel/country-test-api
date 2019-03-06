<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
/**
 * @SWG\Swagger(
 *   basePath="",
 *       host="localhost:9099",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="COUNTRY TEST API",
 *         @SWG\Contact(name="Chinelo Kwazu", url="nelokwaz@gmail.com"),
 *     )
 * )
 */


class ApiController extends Controller
{
    public $loginAfterSignUp = true;

    /**
     * @SWG\Post(
     *   path="/api/register",
     *   summary="Registers a new user",
     *   operationId="getUserId",
     *      *      operationId="setupRegistration",
     *      tags={"register"},
     *      consumes={"application/json"},
     *   @SWG\Parameter(
     *     name="firstname",
     *     in="path",
     *     description="Firstname of the user.",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="lastname",
     *     in="path",
     *     description="Lastname of the user",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="path",
     *     description="Email of the user",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="username",
     *     in="path",
     *     description="Username of the user",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="date_of_birth",
     *     in="path",
     *     description="Date of the birth",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     description="password of the user",
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
    * This method is used to register a new user
    * */
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string',
            'date_of_birth' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:10'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ]);

        }

        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->date_of_birth = $request->date_of_birth;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if($this->loginAfterSignUp){
            return $this->login($request);
        }

        return response()->json(
            ['success' => true,
                'data' => $user]
            ,200);
    }





    /**
     * @SWG\Post(
     *   path="/api/login",
     *   summary="logs in a new user and returns a token",
     *   operationId="getUserId",
     *      *      operationId="setupLogin",
     *      tags={"login"},
     *      consumes={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="path",
     *     description="Email of the user",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="path",
     *     description="password of the user",
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
    * This method logs in a user
    * */

    public function login(Request $request){
        $input = $request->only('email','password');
        $jwt_token = null;

        if(!$jwt_token = JWTAuth::attempt($input) ){

            return response()->json(
                ['success' => false,
                    'message' => 'Invalid Email or Password'],
                401);
        }

        return response()->json(
            ['success' => true,
                'token' => $jwt_token ]);
    }



    /*
    * This method logs out a user
    * */
    public function logout(Request $request){
        $this->validate($request,[
            'token' => 'required'
        ]);

        try{
            JWTAuth::invalidate($request->token);

            return response()->json(['success' => true,
                'message' => 'User logged out successfully'
            ]);
        }catch (JWTException $exception){

            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);

        }
    }


    /*
    * This method is retrieves a looged in user's details
    * */
    public function getAuthUser(Request $request){

        $this->validate($request,[
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

}
