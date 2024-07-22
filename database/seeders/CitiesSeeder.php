<?php

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
 
    public function run(): void
    {
        $citiess = [
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

        foreach ($citiess as $cities) {
            Cities::factory()->create([
                'name' => $cities,
            ]);
        }
    }
}