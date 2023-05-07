<?php

use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\WorkerShiftController;
use Illuminate\Support\Facades\Route;

Route::controller(WorkerShiftController::class)->group(function () {
    Route::get('worker/{workerId}/shift',  'index')->name('worker.shift.index');
    Route::delete('worker/{workerId}/shift/{shiftId}', 'delete')->name('worker.shift.delete');
    Route::post('worker/shift', 'store')->name('worker.shift.create');
    Route::put('worker/shift', 'update')->name('worker.shift.update');
});



Route::controller(WorkerController::class)->group(function() {
    Route::get('workers', 'index')->name('workers.index');
    Route::post('workers', 'store')->name('workers.create');
    Route::delete('workers/{workerId}', 'delete')->name('workers.delete');
    Route::put('workers/{workerId}', 'update')->name('workers.update');
});
