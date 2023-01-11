<?php

namespace App\Imports;

use App\Models\Asentamiento;
use App\Models\CodigoPostal;
use App\Models\EntidadFederativa;
use App\Models\Municipio;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class CodigosPostalesImport implements ToCollection, WithHeadingRow, WithChunkReading, WithCustomCsvSettings, WithProgressBar
{
    use Importable;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        EntidadFederativa::unguard();
        Municipio::unguard();
        CodigoPostal::unguard();
        Asentamiento::unguard();

        $rows->each(function ($row) {
            $c_estado = Arr::get($row, 'c_estado');
            $this->entidadFederativa = Cache::rememberForever("entidad-federativa:{$c_estado}", function () use ($row) {
                return EntidadFederativa::firstOrCreate(
                    ['c_estado' => Arr::get($row, 'c_estado'),],
                    ['d_estado' => Arr::get($row, 'd_estado'),],
                );
            });

            $c_mnpio = Arr::get($row, 'c_mnpio');
            $this->municipio = Cache::rememberForever("municipio:{$c_estado}:{$c_mnpio}", function () use ($row) {
                return Municipio::firstOrCreate(
                    [
                        'c_mnpio' => Arr::get($row, 'c_mnpio'),
                        'entidad_federativa_id' => $this->entidadFederativa->id,
                    ],
                    ['d_mnpio' => Arr::get($row, 'd_mnpio'),]
                );
            });

            $d_codigo = Arr::get($row, 'd_codigo');
            $d_ciudad = Arr::get($row, 'd_ciudad');
            $this->codigoPostal = Cache::rememberForever("codigo-postal:{$c_estado}:{$d_ciudad}:{$c_mnpio}:{$d_codigo}", function () use ($row) {
                return CodigoPostal::firstOrCreate(
                    [
                        'd_codigo' => Arr::get($row, 'd_codigo'),
                        'd_ciudad' => Arr::get($row, 'd_ciudad')
                    ],
                    [
                        'entidad_federativa_id' => $this->entidadFederativa->id,
                        'municipio_id' => $this->municipio->id,
                    ]
                );
            });

            $this->asentamiento = Asentamiento::firstOrCreate(
                [
                    'id_asenta_cpcons' => Arr::get($row, 'id_asenta_cpcons'),
                    'd_asenta' => Arr::get($row, 'd_asenta'),
                    'd_zona' => Arr::get($row, 'd_zona'),
                    'd_tipo_asenta' => Arr::get($row, 'd_tipo_asenta'),
                ],
                [
                    'codigo_postal_id' => $this->codigoPostal->id,
                ]
            );
        });
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1',
            'delimiter' => '|'
        ];
    }
}
