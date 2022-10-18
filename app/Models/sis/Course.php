<?php

namespace App\Models\sis;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'courses';

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
