<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearSemester extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'year_semester';

    public function main_exams(){
        return $this->hasMany(MainExam::class, 'year_id' , 'semester_id');
    }
}
