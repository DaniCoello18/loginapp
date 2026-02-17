<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidade;
use Inertia\Inertia;

class EspecialidadController extends Controller
{
    // Listado de especialidades
    public function index()
    {
        return Inertia::render('Especialidades/Index', [
            'especialidades' => Especialidade::latest()->get(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $especialidades = Especialidade::where('codigo', 'like', "%{$search}%")
            ->orWhere('nombre', 'like', "%{$search}%")
            ->latest()
            ->get();

        return Inertia::render('Especialidades/Index', [
            'especialidades' => $especialidades,
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:especialidades,codigo|regex:/^[A-Z]{3}-\d{3}$/',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        ]);

        Especialidade::create($request->only(['codigo', 'nombre']));

        return redirect()->back()->with('success', 'Especialidad creada correctamente');
    }

    // Actualizar
    public function update(Request $request, Especialidade $especialidade)
    {
        // Validación, ignorando el registro actual para unique
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:especialidades,codigo,' . $especialidade->id . '|regex:/^[A-Z]{3}-\d{3}$/',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        ]);

        // Actualiza correctamente
        $especialidade->update($validated);

        return redirect()->back()->with('success', 'Especialidad actualizada correctamente');
    }

    // Eliminar
    public function destroy(Especialidade $especialidade)
    {
        $especialidade->delete();

        return redirect()->back()->with('success', 'Especialidad eliminada correctamente');
    }
}
