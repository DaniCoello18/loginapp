<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\ProfesoreController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MatriculaController;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('alumnos', AlumnoController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('alumnos/buscar', [AlumnoController::class, 'buscar'])
        ->name('alumnos.buscar');
});

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('especialidades', EspecialidadController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('especialidades/buscar', [EspecialidadController::class, 'buscar'])
        ->name('especialidades.buscar');
});
Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('profesores', ProfesoreController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('profesores/buscar', [ProfesoreController::class, 'buscar'])
        ->name('profesores.buscar');
});
Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('materias', MateriaController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('materias/buscar', [MateriaController::class, 'buscar'])
        ->name('materias.buscar');
});

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('edificios', EdificioController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('edificios/buscar', [EdificioController::class, 'buscar'])
        ->name('edificios.buscar');
});

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('horarios', HorarioController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('horarios/buscar', [HorarioController::class, 'buscar'])
        ->name('horarios.buscar');
});

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('matriculas', MatriculaController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Ruta separada para búsqueda
    Route::get('matriculas/buscar', [MatriculaController::class, 'buscar'])
        ->name('matriculas.buscar');
});


require __DIR__.'/settings.php';
