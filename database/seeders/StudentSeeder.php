<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
class StudentSeeder extends Seeder
{

    public function run()
    {
        Student::create([
            'name' => 'John Doe',
            'class' => 'Primary 6',
            'gender' => 'Male',
            'dob' => '2012-03-15',
            'guardian_name' => 'Jane Doe',
            'contact' => '0700123456',
        ]);
    
        // Add more if you want
    }
}


