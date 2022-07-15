<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $res = new \stdClass();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5|max:50',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                $res->status = 0;
                $res->errors = $validator->errors();
                $res->message = 'Validation Failed!';

                return response()->json($res, 400);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
    
            $token = $user->createToken('Laravel9PassportAuth')->accessToken;
    
            $res->status = 1;
            $res->message = 'Registeration Successful!';
            $res->token = $token;

            return response()->json($res, 200);

        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
  
    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $res = new \stdClass();
        try {
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];
    
            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('Laravel9PassportAuth')->accessToken;

                $res->status= 1;
                $res->message = 'Login Successful!';
                $res->token = $token;
                return response()->json($res, 200);
            } else {
                $res->status = 0;
                $res->message = 'Unauthorized!';
                return response()->json($res, 200);
            }
        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
 
    public function userInfo() 
    {
        $res = new \stdClass();
        try {
            $user = auth()->user();
        
            $res->status = 1;
            $res->data = $user;
            return response()->json($res, 200);
        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
 
    public function logout() 
    {
        $res = new \stdClass();
        try {
            $user = auth()->user()->token();
            $user->revoke();
        
            $res->status = 1;
            $res->message = 'Logout Successful!';
            return response()->json($res, 200);
        } catch (\Exception $e) {
            $res->status = 0;
            $res->message = 'Exception Error!';
            $res->developer_message = $e->getMessage();

            return response()->json($res, 400);
        }
    }
}
