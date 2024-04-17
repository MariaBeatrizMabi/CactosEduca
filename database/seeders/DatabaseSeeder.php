<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        User::factory()->create([
            'user_name' => 'Admin',
            'acess_cod' => 'adminCactusEduca',
            'type' => 'admin',
            'password' => bcrypt('12345678') 
        ]);

        User::factory()->create([
            'user_name' => 'Seduc',
            'acess_cod' => 'adminSeduc',
            'type' => 'admin_seduc',
            'password' => bcrypt('seduc') 
        ]);

        User::factory()->create([
            'user_name' => 'Teacher',
            'acess_cod' => 'teacherTest',
            'type' => 'teacher',
            'password' => bcrypt('teacher') 
        ]);

        User::factory()->create([
            'user_name' => 'School',
            'acess_cod' => 'school',
            'type' => 'school',
            'password' => bcrypt('school') 
        ]);

        $this->call(CountySeeder::class);
    }
}
