<?php

namespace App\Models;

use App\Models\CodigoPostal;
use Illuminate\Database\Eloquent\Model;

class Asentamiento extends Model
{
    protected $table = 'asentamientos';

    # Relationships

    public function codigoPostal()
    {
        return $this->belongsTo(CodigoPostal::class, 'codigo_postal_id');
    }

    # Accessors

    public function getTipoAttribute()
    {
        return ['name' => $this->d_tipo_asenta];
    }
}
