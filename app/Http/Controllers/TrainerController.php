<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\CourseTrainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list()
    {
        try {
            if (Gate::denies('only-secretary-and-trainer')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            if (Gate::allows('only-trainer')) {
                $id = $this->guard()->user()->id;
                return redirect()->route('trainers.show', $id);
            }

            $function = new Trainer();
            $trainers = $function->trainers();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'trainers',
                    'attributes' => $trainers
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
            if (Gate::denies('only-secretary')) {
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
                'courses' => 'required|array|min:1',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }


            $user = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'bi' => $request->input('bi'),
                'address' => $request->input('address'),
                'password' => Hash::make('123456'),
            ];

            $user_saved = User::create($user);

            $trainer = [
                'user_id' => $user_saved->id,
                'gender_id' => $request->input('gender'),
            ];

            $trainer_saved = Trainer::create($trainer);

            $role_user = [
                'user_id' => $user_saved->id,
                'role_id' => Role::where('type', 'trainer')->first()->id,
            ];

            RoleUser::create($role_user);

            $courses = $request->input('courses');

            foreach ($courses as $course) {
                $course_trainer = [
                    'trainer_id' => $trainer_saved->id,
                    'course_id' => $course,
                ];

                CourseTrainer::create($course_trainer);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer created successfully',
                'data' => [
                    'type' => 'trainers',
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|between:3,100',
                'email' => 'required|email',
                'phone' => 'required',
                'bi' => 'required|size:14',
                'address' => 'required',
                'courses' => 'required|array|min:1',
                'gender' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 422);
            }

            $user = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'bi' => $request->input('bi'),
                'address' => $request->input('address'),
            ];

            DB::table('users')->where('id', $id)->update($user);
            $user_update = User::find($id);

            $trainer = [
                'user_id' => $user_update->id,
                'gender_id' => $request->input('gender'),
            ];

            DB::table('trainers')->where('user_id', $id)->update($trainer);
            $trainer_update = Trainer::where('user_id', $id)->first();

            if (Gate::allows('only-secretary')) {
                // Delete all courses teaching from this trainers firstly 
                DB::table('course_trainers')->where('trainer_id', $trainer_update->id)->delete();
                $courses = $request->input('courses');

                foreach ($courses as $course) {
                    $course_trainer = [
                        'trainer_id' => $trainer_update->id,
                        'course_id' => $course,
                    ];
                    CourseTrainer::create($course_trainer);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer updated successfully',
                'data' => [
                    'type' => 'trainers',
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
            if (Gate::denies('only-secretary-and-trainer')) {
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
                    'message' => 'Trainer not found!',
                    'data' => null
                ], 404);
            }

            $function = new Trainer();
            $trainer = $function->trainer($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'trainers',
                    'attributes' => $trainer
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
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unathorized',
                    'data' => null
                ], 401);
            }

            $id = $request->input('id');

            $trainer =  Trainer::where('user_id', $id)->first();

            if (!$trainer) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Trainer not found!',
                    'data' => null
                ], 404);
            }

            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Trainer deleted successfully',
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
