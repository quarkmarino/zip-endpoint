<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipCodeResource;
use App\Models\CodigoPostal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ZipCodeController extends Controller
{
    /**
     * Handle the request for a zip code value via standard Eloquent
     * with eager loading of 3 tables via its Eloquent relationship (entidadFederativa, municipio, asentamientos)
     *
     * Executes 4 queries
     *
     * @param  int     $zipCode [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function viaPureEloquent(int $zipCode, Request $request)
    {
        $zipCodeResult = CodigoPostal::where('d_codigo', $zipCode)
            ->with(['entidadFederativa', 'asentamientos', 'municipio'])
            ->firstOrFail();

        return new ZipCodeResource($zipCodeResult);
    }

    /**
     * Handle the request for a zip code value via standard Eloquent
     * joining 2 tables by its defined Eloquent relationship (entidadFederativa, municipio)
     * and eager loading of asentamientos
     *
     * Executes 2 queries
     *
     * @param  int     $zipCode [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function viaEloquentAndPowerJoins(int $zipCode, Request $request)
    {
        $zipCodeResult = CodigoPostal::addSelect([
                'codigos_postales.id',
                'codigos_postales.d_codigo',
                'codigos_postales.d_ciudad',
                'entidades_federativas.c_estado',
                'entidades_federativas.d_estado',
                'municipios.c_mnpio',
                'municipios.d_mnpio',
            ])
            ->joinRelationship('entidadFederativa')
            ->joinRelationship('municipio')
            ->where('d_codigo', $zipCode)
            ->with(['asentamientos'])
            ->firstOrFail();

        return new ZipCodeResource($zipCodeResult);
    }

    /**
     * Handle the request for a zip code value via standard Query Build
     * joining 2 other tables (`entidades_federativas`, `municipios`)
     * and a sub query for `asentamientos`
     *
     * Executes 1 whole query
     *
     * @param  int     $zipCode [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function viaQueryBuilder(int $zipCode, Request $request)
    {
        $zipCodeResult = DB::table('codigos_postales')
            ->addSelect([
                DB::raw("JSON_OBJECT(
                    'zip_code', UCASE(d_codigo),
                    'locality', UCASE(d_ciudad),
                    'federal_entity', JSON_OBJECT(
                        'key',UCASE(c_estado),
                        'name', UCASE(d_estado),
                        'code', NULL
                    ),
                    'municiplaity', JSON_OBJECT(
                        'key', UCASE(c_mnpio),
                        'name', UCASE(d_mnpio)
                    ),
                    'settlements', (SELECT
                        JSON_ARRAYAGG(
                            JSON_OBJECT(
                                'key', UCASE(id_asenta_cpcons),
                                'name', UCASE(d_asenta),
                                'zone_type', UCASE(d_zona),
                                'settlement_type', JSON_OBJECT('name', d_tipo_asenta)
                            )
                        )
                        FROM
                            asentamientos
                        WHERE
                            codigo_postal_id = codigos_postales.id
                    )
                ) as zip_code")
            ])
            ->where('d_codigo', $zipCode)
            ->join('entidades_federativas', 'entidades_federativas.id', '=', 'codigos_postales.entidad_federativa_id')
            ->join('municipios', 'municipios.id', '=', 'codigos_postales.municipio_id')
            ->value('zip_code');

        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $zipCodeResult);
    }
}
