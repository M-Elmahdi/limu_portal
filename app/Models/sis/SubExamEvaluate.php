<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExamEvaluate extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'sub_exams_evaluate';


    public function sub_exam_eval(){
        return $this->belongsTo(SubExamStudentResult::class, 'id', 'sub_exam_eval_id');
    }
}
