<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

    protected $table = 'student_detail';

    protected $connection = 'mysql2';

    public function main_exam_results(){
        return $this->hasMany(MainExamStudentResult::class, 'std_id', 'std_id');
    }

    public function main_reset_exam_results(){
        return $this->hasMany(MainExamResetStudentResult::class, 'std_id', 'std_id');
    }

    public function subexam_results(){
        return $this->hasMany(SubExamStudentResult::class, 'std_id', 'std_id');
    }

    public function subexam_reset_results(){
        return $this->hasMany(SubExamResetStudentResult::class, 'std_id', 'std_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'std_batch_id', 'batch_id');
    }

    public function year(){
        return $this->belongsTo(YearSemester::class, 'std_semester_id', 'semester_id');
    }

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'std_faculty_id', 'faculty_id');
    }
}
