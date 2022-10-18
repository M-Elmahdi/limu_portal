<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExamResetStudentResult extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'table_subexams_std_reset_result';

    public function exam_reset(){
        return $this->belongsTo(MainExamReset::class, 'exam_id', 'exam_id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function sub_exam_reset(){
        return $this->belongsTo(SubExamReset::class, 'sub_exam_id', 'sub_exams_id');
    }

    public function sub_reset_exam_eval(){
        return $this->belongsTo(SubExamResetEvaluate::class, 'sub_exam_eval_id', 'id');
    }
}
