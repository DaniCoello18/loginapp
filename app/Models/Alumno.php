<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Alumno extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'cedula', 'nombre', 'apellido', 'fecha_nacimiento', 'direccion', 'correo'
    ];


        public function materias()
    {
        return $this->belongsToMany(Materia::class, 'matriculas', 'alumno_id', 'materia_id')->withTimestamps();
    }

    // Relación con matriculas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'alumno_id');
    }
}
