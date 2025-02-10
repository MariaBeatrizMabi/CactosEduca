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
        $pdf = Pdf::loadView('pdf.literacy-parameters-export', compact('data'));
        $className = strtolower(str_replace(' ', '_', $class->name));
        return $pdf->download('dados_da_turma_' . $className . '.pdf');
    }
}
