<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Rol extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'idRol';

    protected $fillable = [
        'nombre',
    ];

    
    public function users()
    {
        return $this->hasMany(Usuario::class, 'idRol');
    }
}