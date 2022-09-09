<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_name',
        'faculty_sname',
        'faculty_programme',
        'faculty_barcode',
        'faculty_dean',
        'faculty_registrar',
    ];

    public function user(){
        return $this->hasMany(User::class);
    }
}
