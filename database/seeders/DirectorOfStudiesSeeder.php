<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DirectorOfStudiesSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'firstName' => 'Lawrence',
            'lastName' => 'Lubega',
            'email' => 'director@school.com', // Use the actual email
            'password' => Hash::make('12345678'), // Use a strong password
            
        ]);
    }
}