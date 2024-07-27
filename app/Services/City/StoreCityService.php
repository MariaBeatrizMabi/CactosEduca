<?php

namespace App\Services\City;

use App\Models\Cities;

class StoreCityService
{
    public function __construct(private readonly Cities $city) {}

    public function run(array $data): Cities
    {
        return $this->city->create($data);
    }
}
