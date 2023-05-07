<?php

use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\WorkerShiftController;
use Illuminate\Support\Facades\Route;

Route::get('worker-shift/{workerId}', [ WorkerShiftController::class, 'index'])->name('worker-shift.index');
Route::post('worker-shift', [ WorkerShiftController::class, 'store'])->name('worker-shift.create');
Route::controller(WorkerController::class)->group(function() {
    Route::get('workers', 'index');
    Route::post('workers', 'store');
    Route::delete('workers/{workerId}', 'delete');
    Route::put('workers/{workerId}', 'update');
});
