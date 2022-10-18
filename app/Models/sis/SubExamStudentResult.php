<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExamStudentResult extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'table_subexams_std_result';

    public function main_exam(){
        return $this->belongsTo(MainExam::class, 'exam_id', 'id');
    }

    public function sub_exam(){
        return $this->belongsTo(SubExam::class, 'sub_exam_id', 'sub_exams_id');
    }

    public function sub_exam_eval(){
        return $this->belongsTo(SubExamEvaluate::class, 'sub_exam_eval_id', 'id');
    }

    public function sub_reset_exam_eval(){
        return $this->belongsTo(SubExamResetEvaluate::class, 'sub_exam_eval_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function student(){
        return $this->belongsTo(StudentDetail::class, 'std_id', 'std_id');
    }
}
