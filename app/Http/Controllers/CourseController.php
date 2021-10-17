<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['list']]);
    }

    public function list()
    {
        try {
            return response()->json([
                'status' => true,
                'courses' => Course::all()
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'message' => 'Unathorized'
                ], 401);
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|unique:courses,name',
                'date_begin' => 'required|date',
                'date_end' => 'required|date',
                'price' => 'required|numeric',
                'description' => 'max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $course = [
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'date_begin' => $request->input('date_begin'),
                'date_end' => $request->input('date_end'),
                'price' => $request->input('price'),
                'description' => $request->input('description')
            ];

            $course_saved = Course::create($course);

            return response()->json([
                'status' => true,
                'message' => 'Course created successfully',
                'course' => $course_saved,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'message' => 'Unathorized'
                ], 401);
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'date_begin' => 'required|date',
                'date_end' => 'required|date',
                'price' => 'required|numeric',
                'description' => 'max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $course = [
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'date_begin' => $request->input('date_begin'),
                'date_end' => $request->input('date_end'),
                'price' => $request->input('price'),
                'description' => $request->input('description')
            ];
            DB::table('courses')->where('id', $id)->update($course);
            $course_updated = DB::table('courses')->where('id', $id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Course updated successfully',
                'course' => $course_updated
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    
    public function search_by_name($slug)
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found!',
                    'course' => []
                ], 403);
            }

            return response()->json([
                'status' => true,
                'course' => $course,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found!',
                    'course' => []
                ], 403);
            }

            return response()->json([
                'status' => true,
                'course' => $course,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'message' => 'Unathorized'
                ], 401);
            }
            $id = $request->input('id');

            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found!',
                    'course' => []
                ], 403);
            }

            DB::table('courses')->where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Course deleted successfully',
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function completed(Request $request)
    {
        try {
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'message' => 'Unathorized'
                ], 401);
            }
            $id = $request->input('id');
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found!',
                    'course' => []
                ], 403);
            }

            $course = [
                'completed' => 1,
            ];

            DB::table('courses')->where('id', $id)->update($course);
            $course_updated = DB::table('courses')->where('id', $id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Course completed successfully',
                'course' => $course_updated
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function incompleted(Request $request)
    {
        try {
            if (Gate::denies('only-secretary')) {
                return response()->json([
                    'message' => 'Unathorized'
                ], 401);
            }
            $id = $request->input('id');
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'status' => false,
                    'message' => 'Course not found!',
                    'course' => []
                ], 403);
            }

            $course = [
                'completed' => 0,
            ];

            DB::table('courses')->where('id', $id)->update($course);
            $course_updated = DB::table('courses')->where('id', $id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Course available successfully',
                'course' => $course_updated
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Oops! Something went wrong .. . Verify your request and try again!',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
