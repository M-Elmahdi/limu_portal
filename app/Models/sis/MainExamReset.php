<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainExamReset extends Model
{
    use HasFactory;

    protected $table = 'table_reset_exams';
    protected $connection = 'mysql2';

    public function sub_reset_exam(){
        return $this->hasMany(SubResetExam::class, 'exam_id', 'id');
    }

    public function main_exam_reset_student_results(){
        return $this->hasMany(MainExamResetStudentResult::class, 'exam_id', 'id');
    }

    public function sub_exam_reset_student_results(){
        return $this->hasMany(SubExamResetStudentResult::class, 'exam_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
