<?php

namespace App\Models;

use App\Models\Asentamiento;
use App\Models\EntidadFederativa;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class CodigoPostal extends Model
{
    use PowerJoins;

    protected $table = 'codigos_postales';

    # Relationships

    public function entidadFederativa()
    {
        return $this->belongsTo(EntidadFederativa::class, 'entidad_federativa_id');
    }

    public function asentamientos()
    {
        return $this->hasMany(Asentamiento::class, 'codigo_postal_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}
