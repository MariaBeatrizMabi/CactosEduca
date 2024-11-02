<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterventionSeeder20241101 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('interventions')->insert([
            ['code' => 'INT-41', 'description' => 'Outros', 'evaluation_type' => 'pre_syllabic'],
            ['code' => 'INT-41', 'description' => 'Outros', 'evaluation_type' => 'syllabic'],
            ['code' => 'INT-41', 'description' => 'Outros', 'evaluation_type' => 'alphabetical'],
            ['code' => 'INT-41', 'description' => 'Outros', 'evaluation_type' => 'alphabetical_syllabic'],
        ]);
    }
}
