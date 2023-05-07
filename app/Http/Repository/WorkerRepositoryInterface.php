<?php

namespace App\Http\Repository;

use App\Http\ValueObject\WorkerShiftValueObject;
use App\Http\ValueObject\WorkerValueObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface WorkerRepositoryInterface
{
    public function assignShiftToWorker(WorkerShiftValueObject $workerShiftValueObject);

    public function getAllWorkers(): Builder;

    public function createWorker(WorkerValueObject $workerValueObject);

    public function getShiftForWorker(string $workerId, ?string $date): Collection;

    public function updateWorker(string $workerId, WorkerValueObject $workerValueObject): void;

    public function deleteWorker(string $workerId): void;

    public function updateWorkerShift(WorkerShiftValueObject $workerShiftValueObject): void;

    public function deleteShift(string $workerId, string $shiftId): void;
}
