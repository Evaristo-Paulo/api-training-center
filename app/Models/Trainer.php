<?php

namespace App\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $guarded = [];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_trainers', 'trainer_id', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function trainers()
    {
        $data = DB::table('trainers')
            ->join('users', 'users.id', '=', 'trainers.user_id')
            ->join('genders', 'genders.id', '=', 'trainers.gender_id')
            ->select('users.*', 'trainers.id as trainer_id', 'genders.type as gender')
            ->get();

        $courseTrainer = CourseTrainer::all();

        $trainers = [];

        foreach ($data as $i) {
            $courses = [];

            foreach ($courseTrainer as $ii) {
                if ($ii->trainer_id == $i->trainer_id) {
                    $course = Course::where('id', $ii->course_id)->first();
                    array_push($courses, $course->name);
                }
            }

            $trainer = [
                'id' => $i->id,
                'name' => $i->name,
                'gender' => $i->gender,
                'email' => $i->email,
                'phone' => $i->phone,
                'bi' => $i->bi,
                'address' => $i->address,
                'courses' => $courses,
            ];

            array_push($trainers, $trainer);
            unset($courses);
        }

        return $trainers;
    }


    public function trainer($id)
    {
        $data = DB::table('trainers')
            ->join('users', function ($join) use ($id) {
                $join->on('users.id', '=', 'trainers.user_id')
                    ->where([['users.id', '=', $id]]);
            })
            ->join('genders', 'genders.id', '=', 'trainers.gender_id')
            ->select('users.*', 'trainers.id as trainer_id', 'genders.type as gender')
            ->first();

        $courseTrainer = CourseTrainer::all();

        $courses = [];

        foreach ($courseTrainer as $ii) {
            if ($ii->trainer_id == $data->trainer_id) {
                $course = Course::where('id', $ii->course_id)->first();
                array_push($courses, $course->name);
            }
        }

        $trainer = [
            'id' => $data->id,
            'name' => $data->name,
            'gender' => $data->gender,
            'email' => $data->email,
            'phone' => $data->phone,
            'bi' => $data->bi,
            'address' => $data->address,
            'courses' => $courses,
        ];

        return $trainer;
    }
}
