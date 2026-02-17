<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidade;
use App\Models\Materia;
use Inertia\Inertia;

class MateriaController extends Controller
{
    // Listado de materias y especialidades
    public function index()
    {
        return Inertia::render('Materias/Index', [
            'materias' => Materia::latest()->get(),
            'especialidades' => Especialidade::all(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $materias = Materia::with('especialidad')
            ->where(function($query) use ($search) {
                $query->where('codigo', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Materias/Index', [
            'materias' => $materias,
            'especialidades' => Especialidade::all(),
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:materias,codigo|regex:/^[A-Z]{3}-\d{3}$/',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'descripcion' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        Materia::create($request->only(['codigo', 'nombre', 'descripcion', 'especialidad_id']));

        return redirect()->back()->with('success', 'Materia creada correctamente');
    }

    // Actualizar
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:materias,codigo,' . $materia->id,
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'descripcion' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        $materia->update($validated);

        return redirect()->back()->with('success', 'Materia actualizada correctamente');
    }

    // Eliminar
    public function destroy(Materia $materia)
    {
        $materia->delete();

        return redirect()->back()->with('success', 'Materia eliminada correctamente');
    }
}
