<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Especialidade;

class Materia extends Model
{
    //
            use HasFactory;

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'especialidad_id'
    ];

    public function especialidad()
    {
        return $this->belongsTo(Especialidade::class);
    }


        public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'matriculas', 'alumno_id', 'materia_id')->withTimestamps();
    }

    // Relación con matriculas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'materia_id');
    }


        public function edficios()
    {
        return $this->belongsToMany(Edificio::class, 'horarios', 'materia_id', 'edificio_id')->withTimestamps();
    }

    // Relación con horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'materia_id');
    }

}
