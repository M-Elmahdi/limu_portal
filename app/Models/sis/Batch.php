<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'batches';

    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id');
    }

    public function batch_course(){
        return $this->hasMany(BatchCourse::class, 'batch_id', 'batch_id');
    }
}
