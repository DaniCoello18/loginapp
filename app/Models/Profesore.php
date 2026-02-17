<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Especilidade;

class Profesore extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'cedula', 'nombre', 'apellido', 'correo', 'especialidad_id'
    ];
    protected $table = 'profesores';

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }
}
