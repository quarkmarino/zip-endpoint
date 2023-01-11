<?php

namespace App\Models;

use App\Models\CodigoPostal;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';

    # Relationships

    public function codigosPostales()
    {
        return $this->hasMany(CodigoPostal::class, 'municipio_id');
    }

    // public function entidadFederativa()
    // {
    //     return $this->belongsTo(EntidadFederativa::class, 'entidad_federativa_id');
    // }
}
