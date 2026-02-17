<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Materia;
use App\Models\Edificio;
use Inertia\Inertia;

class HorarioController extends Controller
{
    // Listado de horarios con relaciones
    public function index()
    {
        return Inertia::render('Horarios/Index', [
            'horarios' => Horario::with('materia', 'edificio')->latest()->get(),
            'materias' => Materia::all(),
            'edificios' => Edificio::all(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $horarios = Horario::with('materia', 'edificio')
            ->where(function($query) use ($search) {
                $query->where('codigo', 'like', "%{$search}%")
                      ->orWhere('dia_semana', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Horarios/Index', [
            'horarios' => $horarios,
            'materias' => Materia::all(),
            'edificios' => Edificio::all(),
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:horarios,codigo|regex:/^[A-Z]{3}-\d{3}$/',
            'dia_semana' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion' => 'required|date_format:H:i',
            'materia_id' => 'required|exists:materias,id',
            'edificio_id' => 'required|exists:edificios,id',
        ]);

        Horario::create($request->only(['codigo', 'dia_semana', 'hora_inicio', 'duracion', 'materia_id', 'edificio_id']));

        return redirect()->back()->with('success', 'Horario creado correctamente');
    }

    // Actualizar
    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:horarios,codigo,' . $horario->id . '|regex:/^[A-Z]{3}-\d{3}$/',
            'dia_semana' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion' => 'required|date_format:H:i',
            'materia_id' => 'required|exists:materias,id',
            'edificio_id' => 'required|exists:edificios,id',
        ]);

        $horario->update($validated);

        return redirect()->back()->with('success', 'Horario actualizado correctamente');
    }

    // Eliminar
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()->back()->with('success', 'Horario eliminado correctamente');
    }
}
