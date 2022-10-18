<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResetCourse extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'tbl_reset_course';

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
