<?php

namespace App\Http\Controllers;

use App\Http\Resources\LiteracyParametersResource;
use App\Models\LiteracyParameter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LiteracyParametersController extends Controller
{
    public function index(){
        $literacyParameter = LiteracyParameter::all();
        return LiteracyParametersResource::collection($literacyParameter);
    }

    public function exportDocument(){
        $pdf = Pdf::loadView('pdf.literacy-parameters-export');
        return $pdf->download('literacy-parameters.pdf');
//        return view('pdf.literacy-parameters-export');
    }
}
