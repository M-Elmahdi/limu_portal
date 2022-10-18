<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExam extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'table_sub_exams';

    public function main_exam(){
        return $this->belongsTo(MainExam::class, 'exam_id', 'id');
    }
}
