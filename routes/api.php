<?php

use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\WorkerShiftController;
use Illuminate\Support\Facades\Route;

Route::get('worker-shift/{workerId}', [ WorkerShiftController::class, 'index'])->name('worker-shift.index');
Route::post('worker-shift', [ WorkerShiftController::class, 'store'])->name('worker-shift.create');
Route::controller(WorkerController::class)->group(function() {
    Route::get('workers', 'index')->name('workers.index');
    Route::post('workers', 'store')->name('workers.create');
    Route::delete('workers/{workerId}', 'delete')->name('workers.delete');
    Route::put('workers/{workerId}', 'update')->name('workers.update');
});
