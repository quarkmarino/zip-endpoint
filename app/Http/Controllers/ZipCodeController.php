<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipCodeResource;
use App\Models\CodigoPostal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ZipCodeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($zipCode, Request $request)
    {
        $zipCode = CodigoPostal::with([
            'entidadFederativa',
            'asentamientos',
            'municipio'
        ])
        ->where('d_codigo', $zipCode)
        ->firstOrFail();

        return new ZipCodeResource($zipCode);
    }
}
