<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horario extends Model
{
            use HasFactory;

    protected $fillable = [
        'codigo', 'dia_semana', 'hora_inicio', 'duracion','materia_id','edificio_id'
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }


        public function edificio()
    {
        return $this->belongsTo(Edificio::class);
    }
}
