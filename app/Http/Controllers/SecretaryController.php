<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SecretaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list()
    {
        try {
            if (Gate::denies('only-admin-and-secretary')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            if (Gate::allows('only-secretary')) {
                $id = $this->guard()->user()->id;
                return redirect()->route('secretaries.show', $id);
            }

            $secretaries = DB::table('secretaries')
                ->join('users', 'users.id', '=', 'secretaries.user_id')
                ->join('genders', 'genders.id', '=', 'users.gender_id')
                ->select('users.id', 'users.name', 'genders.type as gender', 'users.email', 'users.phone', 'users.bi', 'users.address')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'secretaries',
                    'attributes' => $secretaries
                ]
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),

            ], 400);
        }
    }


    public function store(Request $request)
    {
        try {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|between:3,100',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required',
                'bi' => 'required|size:14',
                'address' => 'required',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        $validator->errors()
                    ]
                ], 400);
            }


            $user = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'bi' => $request->input('bi'),
                'address' => $request->input('address'),
                'password' => Hash::make('123456'),
                'gender_id' => $request->input('gender'),

            ];

            $user_saved = User::create($user);

            $secretary = [
                'user_id' => $user_saved->id,
            ];

            Secretary::create($secretary);

            $role_user = [
                'user_id' => $user_saved->id,
                'role_id' => Role::where('type', 'secretary')->first()->id,
            ];

            RoleUser::create($role_user);

            return response()->json([
                'status' => 'success',
                'message' => 'Secretary created successfully',
                'data' => [
                    'type' => 'secretaries',
                    'attributes' => $user_saved
                ]
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),

            ], 400);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|between:3,100',
                'email' => 'required|email',
                'phone' => 'required',
                'bi' => 'required|size:14',
                'address' => 'required',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        $validator->errors()
                    ]
                ], 400);
            }

            $user = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'bi' => $request->input('bi'),
                'address' => $request->input('address'),
                'gender_id' => $request->input('gender'),
            ];

            DB::table('users')->where('id', $id)->update($user);
            $user_update = User::find($id);

            $secretary = [
                'user_id' => $user_update->id,
            ];

            DB::table('secretaries')->where('user_id', $id)->update($secretary);
            Secretary::where('user_id', $id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Secretary updated successfully',
                'data' => [
                    'type' => 'secretaries',
                    'attributes' => $user_update
                ]
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),

            ], 400);
        }
    }


    public function show($id)
    {
        try {
            if (Gate::denies('only-admin-and-secretary')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Secretary not found!',
                    'data' => null
                ], 404);
            }

            $secretary = DB::table('secretaries')
                ->join('users', function ($join) use ($id) {
                    $join->on('users.id', '=', 'secretaries.user_id')
                        ->where([['users.id', '=', $id]]);
                })
                ->join('genders', 'genders.id', '=', 'users.gender_id')
                ->select('users.id', 'users.name', 'genders.type as gender', 'users.email', 'users.phone', 'users.bi', 'users.address')
                ->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'secretaries',
                    'attributes' => $secretary
                ]
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            if (Gate::denies('only-admin')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            $id = $request->input('id');

            $secretary =  Secretary::where('user_id', $id)->first();

            if (!$secretary) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Secretary not found!',
                    'data' => null
                ], 404);
            }

            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Secretary deleted successfully',
                'data' => null
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage(),

            ], 400);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
