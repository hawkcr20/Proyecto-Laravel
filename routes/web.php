<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/registro', function () {
    return view('auth.registro');
});

Route::get('/adminDashboard', function () {
    return view('admin.dashboard');
});

Route::get('/usuariosVista', function () {
    return view('usuarios.index');
});

Route::get('/clasesVista', function () {
    return view('clases.index');
});

Route::get('/crearClase', function () {
    return view('clases.create');
});

Route::get('/horarioClases', function () {
    return view('clases.horario');
});

Route::get('/reservas/{id}', function ($id) {
    return view('reservas.show', ['idClase' => $id]);
});

Route::get('/historial', function () {
    return view('reservas.historial');
});

Route::get('/gestionReservas', function () {
    return view('reservas.gestion');
});
