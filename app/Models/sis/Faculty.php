<?php

namespace App\Models\sis;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'faculty';

    public function course(){
        return $this->hasMany(User::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }

    public function batch(){
        return $this->hasMany(Batch::class, 'faculty_id', 'faculty_id');
    }


}
