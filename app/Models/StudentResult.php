<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class StudentResult extends Model
{
    use HasFactory;

    protected $table = 'table_std_result';

    protected $connection = 'mysql2';

    public function student(){
        return $this->belongsTo(Student::class, 'std_id', 'std_id');
    }

}
