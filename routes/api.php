<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create', [PatientController::class, 'createPatient']);

Route::get('/patients', [PatientController::class, 'getPatients']);
