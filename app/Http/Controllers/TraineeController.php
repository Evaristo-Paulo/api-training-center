<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\CourseTrainee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TraineeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list()
    {
        try {
            if (Gate::allows('only-trainee')) {
                $id = $this->guard()->user()->id;
                return redirect()->route('trainees.show', $id);
            }

            $function = new Trainee();
            $trainees = $function->trainees();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'trainees',
                    'attributes' => $trainees
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

            $trainee = [
                'user_id' => $user_saved->id,
                'gender_id' => $request->input('gender'),
            ];

            $trainee_saved = Trainee::create($trainee);

            $role_user = [
                'user_id' => $user_saved->id,
                'role_id' => Role::where('type', 'trainee')->first()->id,
            ];

            RoleUser::create($role_user);

            $courses = $request->input('courses');

            foreach ($courses as $course) {
                $copy_course = Course::find($course);

                if ($copy_course->completed != 1) {
                    $copy_course->trainee_qty = $copy_course->trainee_qty + 1;
                    $copy_course->save();

                    $course_trainee = [
                        'trainee_id' => $trainee_saved->id,
                        'course_id' => $course,
                    ];

                    CourseTrainee::create($course_trainee);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Trainee created successfully',
                'data' => [
                    'type' => 'trainees',
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

            $trainee = [
                'user_id' => $user_update->id,
                'gender_id' => $request->input('gender'),
            ];

            DB::table('trainees')->where('user_id', $id)->update($trainee);
            $trainee_update = Trainee::where('user_id', $id)->first();

            if (Gate::allows('only-secretary')) {
                //reduce qty from any course where they applied for
                $copy_courses = CourseTrainee::where('trainee_id', $trainee_update->id)->get();
                foreach ($copy_courses as $item) {
                    $copy_course = Course::where('id', $item->course_id)->first();
                    $copy_course->trainee_qty = $copy_course->trainee_qty - 1;
                    $copy_course->save();
                }

                // Delete all courses teaching from this trainers firstly 
                DB::table('course_trainees')->where('trainee_id', $trainee_update->id)->delete();
                $courses = $request->input('courses');

                foreach ($courses as $course) {
                    $copy_course = Course::where('id', $course)->first();

                    if ($copy_course->completed != 1) {
                        $copy_course->trainee_qty = $copy_course->trainee_qty + 1;
                        $copy_course->save();

                        $course_trainee = [
                            'trainee_id' => $trainee_update->id,
                            'course_id' => $course,
                        ];
                        CourseTrainee::create($course_trainee);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Trainee updated successfully',
                'data' => [
                    'type' => 'trainees',
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
            if (Gate::denies('only-secretary-and-trainee')) {
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
                    'message' => 'Trainee not found!',
                    'data' => null
                ], 404);
            }

            $function = new Trainee();
            $trainee = $function->trainee($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'type' => 'trainees',
                    'attributes' => $trainee
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

            $trainee =  Trainee::where('user_id', $id)->first();

            if (!$trainee) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Trainee not found!',
                    'data' => null
                ], 404);
            }

            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Trainee deleted successfully',
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
