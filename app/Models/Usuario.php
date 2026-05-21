<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rol;
use App\Models\Reserva;

class Usuario extends Model
{
    protected $table = 'usuarios';

    

    protected $fillable = [
        'nombre',
        'apellidoUno',
        'apellidoDos',
        'email',
        'telefono',
        'userName',
        'password',
        'idRol',
    ];

    protected $hidden = [
        'password',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'idUsuario', 'id');
    }
}