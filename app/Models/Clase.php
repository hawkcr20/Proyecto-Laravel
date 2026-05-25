<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clases';

    protected $fillable = [
        'nombre',
        'descripcion',
        'diaSemana',
        'horario',
        'capacidad',
    ];

    protected $casts = [
        'capacidad' => 'integer',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idClase', 'id');
    }
}