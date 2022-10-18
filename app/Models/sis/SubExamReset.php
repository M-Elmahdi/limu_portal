<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubExamReset extends Model
{
    use HasFactory;

    protected $table = 'table_sub_reset_exams';
    protected $connection = 'mysql2';
}
