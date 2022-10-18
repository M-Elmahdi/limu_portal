<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubResetExam extends Model
{
    use HasFactory;

    protected $table = 'table_sub_reset_exams';
    protected $connection = 'mysql2';

    public function reset_exam(){
        return $this->belongsTo(MainExamReset::class, 'exam_id', 'id');
    }
}
