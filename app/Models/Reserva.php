<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'idUsuario',
        'idClase',
        'fechaReserva',
        'estado',
    ];

    protected $casts = [
        'fechaReserva' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'id');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'idClase', 'id');
    }
}