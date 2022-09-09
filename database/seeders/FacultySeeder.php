<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\SisFaculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sis_facs = SisFaculty::all();

        foreach($sis_facs as $faculty) {
           Faculty::create([
            'faculty_name' => $faculty->faculty_name,
            'faculty_sname' => $faculty->faculty_sname,
            'faculty_programme' => $faculty->faculty_programme,
            'faculty_barcode' => $faculty->faculty_barcode,
            'faculty_dean' => $faculty->faculty_dean,
            'faculty_registrar' => $faculty->faculty_registrar
           ]); 
        }
    }
}
