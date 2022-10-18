<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainExamStudentResult extends Model
{
    use HasFactory;

    protected $table = 'table_std_result';

    protected $connection = 'mysql2';

    public function student(){
        return $this->belongsTo(StudentDetail::class, 'std_id', 'std_id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function main_exam(){
        return $this->belongsTo(MainExam::class, 'exam_id', 'id');
    }
}
