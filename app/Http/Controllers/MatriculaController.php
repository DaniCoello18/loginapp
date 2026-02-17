<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Matricula;
use App\Models\Alumno;
use App\Models\Materia;

class MatriculaController extends Controller
{
    // Listado de matrículas con relaciones
    public function index()
    {
        return Inertia::render('Matriculas/Index', [
            'matriculas' => Matricula::with('alumno', 'materia')->latest()->get(),
            'alumnos' => Alumno::all(),
            'materias' => Materia::all(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $matriculas = Matricula::with('alumno', 'materia')
            ->where('codigo', 'like', "%{$search}%")
            ->orWhereHas('alumno', function($query) use ($search) {
                $query->where('cedula', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%");
            })
            ->orWhereHas('materia', function($query) use ($search) {
                $query->where('codigo', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Matriculas/Index', [
            'matriculas' => $matriculas,
            'alumnos' => Alumno::all(),
            'materias' => Materia::all(),
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:matriculas,codigo|regex:/^[A-Z]{3}-\d{3}$/',
            'fecha_matricula' => 'required|date',
            'alumno_id' => 'required|exists:alumnos,id',
            'materia_id' => 'required|exists:materias,id',
        ]);

        Matricula::create($request->only(['codigo', 'fecha_matricula', 'alumno_id', 'materia_id']));

        return redirect()->back()->with('success', 'Matrícula creada correctamente');
    }

    // Actualizar
    public function update(Request $request, Matricula $matricula)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:matriculas,codigo,' . $matricula->id . '|regex:/^[A-Z]{3}-\d{3}$/',
            'fecha_matricula' => 'required|date',
            'alumno_id' => 'required|exists:alumnos,id',
            'materia_id' => 'required|exists:materias,id',
        ]);

        $matricula->update($validated);

        return redirect()->back()->with('success', 'Matrícula actualizada correctamente');
    }

    // Eliminar
    public function destroy(Matricula $matricula)
    {
        $matricula->delete();

        return redirect()->back()->with('success', 'Matrícula eliminada correctamente');
    }
}
