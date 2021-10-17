<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function trainer(){
        return $this->belongsMany(Trainer::class, 'course_trainers', 'course_id', 'trainer_id');
    }

    public function trainee(){
        return $this->belongsMany(Trainer::class, 'course_trainees', 'course_id', 'trainee_id');
    }
}
