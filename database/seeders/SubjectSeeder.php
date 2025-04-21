<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $subjects = [
        ['name' => 'Mathematics', 'code' => 'MTH101'],
        ['name' => 'English', 'code' => 'ENG101'],
        ['name' => 'Biology', 'code' => 'BIO101'],
        ['name' => 'Physics', 'code' => 'PHY101'],
    ];

    foreach ($subjects as $subject) {
        Subject::create($subject);
    }
}
}
