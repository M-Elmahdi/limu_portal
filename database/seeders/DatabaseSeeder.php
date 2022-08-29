<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin',
            'desc' => 'blabla'
        ]);

        Role::create([
            'name' => 'Student',
            'desc' => 'blabla'
        ]);
    }
}
