<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $token_validity = 24 * 60;

            $this->guard()->factory()->setTTL($token_validity);
            $token = $this->guard()->attempt($validator->validated());

            if (!$token) {
                return response()->json([
                    'error' => 'E-mail or Password not found'
                ], 401);
            }

            return $this->respondWithToken($token);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }


    public function logout()
    {
        try {
            $this->guard()->logout();

            return response()->json([
                'message' => 'User logged out successfully'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function profile()
    {
        try {
            $id = $this->guard()->user()->id;

            $user = DB::table('users')
                ->join('genders', function ($join) use ($id) {
                    $join->on('users.gender_id', '=', 'genders.id')
                        ->where([['users.id', '=', $id]]);
                })
                ->select('users.id','users.name','genders.type as gender', 'users.email','users.phone','users.bi','users.address')
                ->first();

            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function refresh(Request $request)
    {
        try {
            return $this->respondWithToken($this->guard()->refresh());
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }
}
