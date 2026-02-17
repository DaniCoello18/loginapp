<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edificio;
use Inertia\Inertia;

class EdificioController extends Controller
{
    // Listado de edificios
    public function index()
    {
        return Inertia::render('Edificios/Index', [
            'edificios' => Edificio::latest()->get(),
        ]);
    }

    // Búsqueda
    public function buscar(Request $request)
    {
        $search = $request->search;

        $edificios = Edificio::where('codigo', 'like', "%{$search}%")
            ->orWhere('nombre', 'like', "%{$search}%")
            ->orWhere('direccion', 'like', "%{$search}%")
            ->latest()
            ->get();

        return Inertia::render('Edificios/Index', [
            'edificios' => $edificios,
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:edificios,codigo|regex:/^[A-Z]{3}-\d{3}$/',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9]+$/',
            'direccion' => 'required|string|max:100',
        ]);

        Edificio::create($request->only(['codigo', 'nombre', 'direccion']));

        return redirect()->back()->with('success', 'Edificio creado correctamente');
    }

    // Actualizar
    public function update(Request $request, Edificio $edificio)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:edificios,codigo,' . $edificio->id . '|regex:/^[A-Z]{3}-\d{3}$/',
            'nombre' => 'required|string|max:50|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9]+$/',
            'direccion' => 'required|string|max:100',
        ]);

        $edificio->update($validated);

        return redirect()->back()->with('success', 'Edificio actualizado correctamente');
    }

    // Eliminar
    public function destroy(Edificio $edificio)
    {
        $edificio->delete();

        return redirect()->back()->with('success', 'Edificio eliminado correctamente');
    }
}
