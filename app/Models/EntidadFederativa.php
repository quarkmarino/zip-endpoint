<?php

namespace App\Models;

use App\Models\CodigoPostal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadFederativa extends Model
{
    use HasFactory;

    protected $table = 'entidades_federativas';

    # Relationships

    public function codigosPostales()
    {
        return $this->hasMany(CodigoPostal::class, 'entidad_federativa_id');
    }
}
