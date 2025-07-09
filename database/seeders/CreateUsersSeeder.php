<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'sam',
                'email' => 'schivunga@lapf.co.zw',
                'first_name' => 'Sam',
                'last_name' => 'Chivunga',
                'address' => '24753 Chitungwiza',
                'mobile' => '0783519199',
                'extension' => '251',
                'jobtitle' => 'ICT Administrator',
                'department' => 'Finance',
                'section' => 'ICT',
                'gender' => 'Male',
                'dob' => '1993-04-15',
                'grade' => '8',
                'is_admin' => '0',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'tadiwa',
                'email' => 'tadiwa@lapf.co.zw',
                'first_name' => 'Tadiwa',
                'last_name' => 'Dauya',
                'address' => '24753 Chitungwiza',
                'mobile' => '0783519199',
                'extension' => '251',
                'jobtitle' => 'ICT Officer',
                'department' => 'Finance',
                'section' => 'ICT',
                'gender' => 'Male',
                'dob' => '1994-04-15',
                'grade' => '6',
                'is_admin' => '1',
                'password' => bcrypt('makanakamwari'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}