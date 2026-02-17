<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'cedula', 'nombre', 'apellido', 'correo', 'especialidad_id'
    ];
    protected $table = 'profesores';

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }
}
