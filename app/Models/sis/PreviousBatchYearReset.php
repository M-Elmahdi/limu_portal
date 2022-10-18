<?php

namespace App\Models\sis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousBatchYearReset extends Model
{
    use HasFactory;

    protected $table = 'tbl_std_reset_prev_fac_batch_year';
    protected $connection = 'mysql2';

    public function student(){
        return $this->belongsTo(StudentDetail::class, 'std_id', 'std_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'batch_id');
    }

    public function batch_courses(){
        return $this->hasMany(BatchCourse::class, 'batch_id', 'batch_id');
    }

    public function year(){
        return $this->belongsTo(YearSemester::class, 'year_id', 'semester_id');
    }
}
