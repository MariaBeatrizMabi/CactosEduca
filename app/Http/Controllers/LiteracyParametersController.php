<?php

namespace App\Http\Controllers;

use App\Http\Resources\LiteracyParametersResource;
use App\Models\ClassModel;
use App\Models\LiteracyParameter;
use App\Services\LiteracyParameters\GetLiteracyParametersExportDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LiteracyParametersController extends Controller
{
    public function index(){
        $literacyParameter = LiteracyParameter::all();
        return LiteracyParametersResource::collection($literacyParameter);
    }

    public function exportDocument(
        ClassModel $class,
        int $bimester,
        GetLiteracyParametersExportDataService $getLiteracyParametersExportDataService
    ){
        $data = $getLiteracyParametersExportDataService->run($class, $bimester);
//        dd($data);
        $pdf = Pdf::loadView('pdf.literacy-parameters-export', compact('data'));
        return $pdf->download('literacy-parameters.pdf');
//        return view('pdf.literacy-parameters-export', compact('data'));
    }
}
