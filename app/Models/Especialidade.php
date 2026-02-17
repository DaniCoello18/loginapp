<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Especialidade extends Model
{
    //
    use HasFactory;

     protected $fillable = [
        'codigo', 'nombre'
    ];

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
