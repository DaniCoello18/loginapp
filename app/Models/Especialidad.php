<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Materia;
use App\Models\Profesor;

class Especialidad extends Model
{
    //
     protected $fillable = [
        'codigo', 'nombre'
    ];
     protected $table = 'especialidades';

    // Relación con matriculas
    public function matriculas()
    {
        return $this->hasMany(Materia::class);
    }

        public function profesores()
    {
        return $this->hasMany(Profesor::class);
    }


}
