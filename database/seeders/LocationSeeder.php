<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $Locations = [
            'Sede',
            'Zona Rural',
         ];
 
         foreach ($Locations as $Location) {
             Location::factory()->create([
                 'name' => $Location,
             ]);
         }
    }
}
