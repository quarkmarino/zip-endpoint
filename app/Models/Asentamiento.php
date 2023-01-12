<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asentamiento extends Model
{
    protected $table = 'asentamientos';

    # Accessors

    public function getTipoAttribute()
    {
        return ['name' => $this->d_tipo_asenta];
    }
}
