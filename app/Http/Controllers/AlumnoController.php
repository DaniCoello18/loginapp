<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Alumno;
use Inertia\Inertia;


class AlumnoController extends Controller
{
    // crud standar
    // manejamos logica

    public function index()
    {
        return Inertia::render('Alumnos/Index', [
            'alumnos' => Alumno::latest()->get()
        ]);
    }

    public function buscar(Request $request)
    {
        $search = $request->search;

        $alumnos = Alumno::where('cedula', 'like', "%{$search}%")
            ->orWhere('nombre', 'like', "%{$search}%")
            ->orWhere('apellido', 'like', "%{$search}%")
            ->orWhere('correo', 'like', "%{$search}%")
            ->latest()
            ->get();

        return Inertia::render('Alumnos/Index', [
            'alumnos' => $alumnos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|digits:10|unique:alumnos,cedula',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:100',
            'correo' => 'required|email|unique:alumnos,correo',
        ]);

        Alumno::create($request->all());

        return redirect()->back();
    }



    public function update(Request $request, Alumno $alumno)
{
    $validated = $request->validate([
        'cedula' => 'required|digits:10|unique:alumnos,cedula,' . $alumno->id,
        'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'apellido' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
        'fecha_nacimiento' => 'required|date',
        'direccion' => 'required|string|max:100',
        'correo' => 'required|email|max:100|unique:alumnos,correo,' . $alumno->id,
    ]);

    $alumno->update($validated);

    return redirect()->back()->with('success', 'Alumno actualizado correctamente');
}


    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return redirect()->back();
    }


}
