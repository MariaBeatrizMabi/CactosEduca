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
           'AC-Acre',
           'Al-Alagoas',
           'Am-Amazonas',
           'Ba-Bahia',
           'Ce-Ceará',
           'Es-Espírito Santo',
           'Go-Goiás',
           'Ma-Maranhão',
           'Mt-Mato Grosso',
           'Ms-Mato Grosso Do Sul',
           'Mg-Minas Gerais',
           'PA-Pará',
           'PB-Paraíba',
           'PR-Paraná',
           'PE-Pernambuco',
           'PI-Piauí',
           'RJ-Rio De Janeiro',
           'RN-Rio Grande Do Norte',
           'RS-Rio Grande Do Sul',
           'RO-Rondônia',
           'RR-Roraima',
           'SC-Santa Catarina',
           'SP-São Paulo',
           'SE-Sergipe',
           'TO-Tocantins',
           'DF-Distrito Federal',
        ];

        foreach ($countys as $county) {
            County::factory()->create([
                'name' => $county,
            ]);
        }
    }
}