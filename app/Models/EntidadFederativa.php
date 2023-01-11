<?php

namespace App\Models;

use App\Models\CodigoPostal;
use Illuminate\Database\Eloquent\Model;

class EntidadFederativa extends Model
{
    protected $table = 'entidades_federativas';

    # Relationships

    public function codigosPostales()
    {
        return $this->hasMany(CodigoPostal::class, 'entidad_federativa_id');
    }
}
