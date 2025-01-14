<?php

namespace App\Http\Controllers;

use App\Http\Resources\LiteracyParametersResource;
use App\Models\LiteracyParameter;
use Illuminate\Http\Request;

class LiteracyParametersController extends Controller
{
    public function index(){
        $literacyParameter = LiteracyParameter::all();
        return LiteracyParametersResource::collection($literacyParameter);
    }
}
