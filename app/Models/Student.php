<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student_detail';

    public function marks(){
        return $this->hasMany(StudentResult::class, 'std_id', 'std_id');
    }
}
