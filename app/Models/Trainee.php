<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    protected $guarded = [];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_trainees', 'trainee_id', 'course_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    
    public function trainees()
    {
        $data = DB::table('trainees')
            ->join('users', 'users.id', '=', 'trainees.user_id')
            ->join('genders', 'genders.id', '=', 'users.gender_id')
            ->select('users.*', 'trainees.id as trainee_id', 'genders.type as gender')
            ->get();

        $courseTrainee = CourseTrainee::all();

        $trainees = [];

        foreach ($data as $i) {
            $courses = [];

            foreach ($courseTrainee as $ii) {
                if ($ii->trainee_id == $i->trainee_id) {
                    $course = Course::where('id', $ii->course_id)->first();
                    array_push($courses, $course->name);
                }
            }

            $trainee = [
                'id' => $i->id,
                'name' => $i->name,
                'gender' => $i->gender,
                'email' => $i->email,
                'phone' => $i->phone,
                'bi' => $i->bi,
                'address' => $i->address,
                'courses' => $courses,
            ];

            array_push($trainees, $trainee);
            unset($courses);
        }

        return $trainees;
    }


    public function trainee($id)
    {
        $data = DB::table('trainees')
            ->join('users', function ($join) use ($id) {
                $join->on('users.id', '=', 'trainees.user_id')
                    ->where([['users.id', '=', $id]]);
            })
            ->join('genders', 'genders.id', '=', 'users.gender_id')
            ->select('users.*', 'trainees.id as trainee_id', 'genders.type as gender')
            ->first();

        $courseTrainee = CourseTrainee::all();

        $courses = [];

        foreach ($courseTrainee as $ii) {
            if ($ii->trainee_id == $data->trainee_id) {
                $course = Course::where('id', $ii->course_id)->first();
                array_push($courses, $course->name);
            }
        }

        $trainee = [
            'id' => $data->id,
            'name' => $data->name,
            'gender' => $data->gender,
            'email' => $data->email,
            'phone' => $data->phone,
            'bi' => $data->bi,
            'address' => $data->address,
            'courses' => $courses,
        ];

        return $trainee;
    }

}
