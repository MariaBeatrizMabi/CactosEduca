<?php

namespace Database\Seeders;

use App\Models\County;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countys = [
           'Acre',
           'Alagoas',
           'Amazonas',
           'Bahia',
           'Ceará',
           'Espírito Santo',
           'Goiás',
           'Maranhão',
           'Mato Grosso',
           'Mato Grosso Do Sul',
           'Minas Gerais',
           'Pará',
           'Paraíba',
           'Paraná',
           'Pernambuco',
           'Piauí',
           'Rio De Janeiro',
           'Rio Grande Do Norte',
           'Rio Grande Do Sul',
           'Rondônia',
           'Roraima',
           'Santa Catarina',
           'São Paulo',
           'Sergipe',
           'Tocantins',
           'Distrito Federal',
        ];

        foreach ($countys as $county) {
            County::factory()->create([
                'name' => $county,
            ]);
        }
    }
}