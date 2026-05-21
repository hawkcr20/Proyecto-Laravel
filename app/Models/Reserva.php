<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Clase;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $primaryKey = 'idReserva';

    protected $fillable = [
        'idUsuario',
        'idClase',
        'fechaReserva',
        'estado',
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