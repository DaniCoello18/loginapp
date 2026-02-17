<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Especialidade;
use App\Models\Profesore;

class ProfesoreController extends Controller
{
    // Listar profesores y especialidades
    public function index()
    {
        return Inertia::render('Profesores/Index', [
            'profesores' => Profesore::with('especialidade')->latest()->get(),
            'especialidades' => Especialidade::all(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $profesores = Profesore::with('especialidade')
            ->where('cedula', 'like', "%{$search}%")
            ->orWhere('nombre', 'like', "%{$search}%")
            ->orWhere('apellido', 'like', "%{$search}%")
            ->orWhere('correo', 'like', "%{$search}%")
            ->latest()
            ->get();

        return Inertia::render('Profesores/Index', [
            'profesores' => $profesores,
            'especialidades' => Especialidade::all(),
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|digits:10|unique:profesores,cedula',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'correo' => 'required|email|max:100|unique:profesores,correo',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        Profesore::create($request->only(['cedula', 'nombre', 'apellido', 'correo', 'especialidad_id']));

        return redirect()->back()->with('success', 'Profesor creado correctamente');
    }

    // Actualizar
    public function update(Request $request, Profesore $profesore)
    {
        $validated = $request->validate([
            'cedula' => 'required|digits:10|unique:profesores,cedula,' . $profesore->id,
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'correo' => 'required|email|max:100|unique:profesores,correo,' . $profesore->id,
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        $profesore->update($validated);

        return redirect()->back()->with('success', 'Profesor actualizado correctamente');
    }

    // Eliminar
    public function destroy(Profesore $profesore)
    {
        $profesore->delete();
        return redirect()->back();
    }
}
