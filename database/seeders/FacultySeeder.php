<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Faculty',
            'email' => 'itstd.3194@gmail.com',
            'password' => Hash::make('12345678'),
            'faculty_id' => 4,
            'std_id' => 2100
        ]);
        
        $user->assignRole('Faculty');
    }
}
