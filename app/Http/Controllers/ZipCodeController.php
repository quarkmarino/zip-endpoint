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
        $zipCode = CodigoPostal::addSelect([
            'codigos_postales.id',
            'codigos_postales.d_codigo',
            'codigos_postales.d_ciudad',
            'entidades_federativas.c_estado',
            'entidades_federativas.d_estado',
            'municipios.c_mnpio',
            'municipios.d_mnpio',
        ])
            ->where('d_codigo', $zipCode)
            ->joinRelationship('entidadFederativa')
            ->joinRelationship('municipio')
            ->with([
                'asentamientos' => function ($asentamientos) {
                    $asentamientos->addSelect([
                        'id_asenta_cpcons',
                        'd_asenta',
                        'd_zona',
                        'd_tipo_asenta',
                        'codigo_postal_id',
                    ]);
                },
            ])
            ->firstOrFail();

        // $zipCode = CodigoPostal::where('d_codigo', $zipCode)
        //     ->with([
        //         'entidadFederativa',
        //         'asentamientos',
        //         'municipio'
        //     ])
        //     ->firstOrFail();

        return new ZipCodeResource($zipCode);
    }
}
