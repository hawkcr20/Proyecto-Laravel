<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;

class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = [
        'nombre',
        'descripcion',
        'diaSemana',
        'horario',
        'capacidad',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idClase', 'id');
    }
}
