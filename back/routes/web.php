<?php
use App\Http\Controllers\Api\DesaparecidosController;
use Illuminate\Support\Facades\Route;


Route::get('/desaparecidos/recentes', [DesaparecidosController::class, 'recentes']);
