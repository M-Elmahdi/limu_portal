<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExamResetEvaluate extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'sub_reset_exams_evaluate';

    public function sub_exam_reset(){
        return $this->belongsTo(SubExamReset::class, 'sub_exams_id', 'sub_exams_id');
    }
    
}
