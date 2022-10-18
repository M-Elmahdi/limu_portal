<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchCourse extends Model
{
    use HasFactory;

    protected $table = 'batches_course';

    protected $connection = 'mysql2';

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }

    public function main_exam(){
        return $this->hasMany(MainExam::class, 'course_id', 'course_id');
    }

    public function student_repeat_courses(){
        return $this->belongsTo(StudentRepeatCourse::class, 'course_id', 'course_id');
    }
}
