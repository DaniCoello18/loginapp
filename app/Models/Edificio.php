<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Edificio extends Model
{
     //
            use HasFactory;

    protected $fillable = [
        'codigo', 'nombre', 'direccion'
    ];


        public function materias()
    {
        return $this->belongsToMany(Materia::class, 'horarios', 'materia_id', 'edificio_id')->withTimestamps();
    }

    // Relación con matriculas
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'edificio_id');
    }
    
}
