<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainExam extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'table_exams';

    public function sub_exams(){
        return $this->hasMany(SubExam::class, 'exam_id', 'id');
    }

    public function main_exam_student_results(){
        return $this->hasMany(MainExamStudentResult::class, 'exam_id', 'id');
    }

    public function sub_exam_reset_student_results(){
        return $this->hasMany(SubExamResetStudentResult::class, 'exam_id', 'id');
    }

    public function sub_exam_student_results(){
        return $this->hasMany(SubExamStudentResult::class, 'exam_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }

    public function batchCourse(){
        return $this->belongsTo(BatchCourse::class, 'batch_id', 'batch_id');
    }

    public function year(){
        return $this->belongsTo(YearSemester::class, 'year_id', 'semester_id');
    }
}
