<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matricula extends Model
{
    
            use HasFactory;

    protected $fillable = [
        'codigo', 'fecha_matricula', 'alumno_id', 'materia_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }


        public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}
