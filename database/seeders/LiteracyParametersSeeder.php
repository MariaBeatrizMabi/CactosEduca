<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiteracyParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('literacy_parameters')->insert([
            [
                'literacy_parameter' => 'write_name'
            ],
            [
                'literacy_parameter' => 'recognize_write_alphabet'
            ],
            [
                'literacy_parameter' => 'recognize_write_vocal_encounters'
            ],
            [
                'literacy_parameter' => 'recognize_write_syllable_family'
            ],
            [
                'literacy_parameter' => 'recognize_write_number'
            ],
        ]);
    }
}
