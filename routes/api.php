<?php

use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\WorkerShiftController;
use Illuminate\Support\Facades\Route;

Route::apiResource('workers', WorkerController::class);
Route::post('worker-shift', [ WorkerShiftController::class, 'store'])->name('worker-shift.create');
