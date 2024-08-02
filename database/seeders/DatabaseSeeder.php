<?php

namespace Database\Seeders;

use App\Models\ManagementSchool;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CitiesSeeder::class);
        $this->call(LocationSeeder::class);

        User::factory()->create([
            'user_name' => 'Admin',
            'acess_cod'  => 'CactosAdmin',
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

        ManagementSchool::factory()->create([
            'name' => 'School',
            'city_id' => 1,
            'location_id' => 1,
            'user_id' => 4,
        ]);

        Teacher::factory()->create([
            'name' => 'Teacher',
            'school_id' => 1,
            'user_id' => 3
        ]);
    }
}
