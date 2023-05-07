<?php

namespace App\Http\Repository;

use App\Http\ValueObject\WorkerShiftValueObject;

interface WorkerRepositoryInterface
{
    public function assignShiftToWorker(WorkerShiftValueObject $workerShiftValueObject);

    public function getAllWorkers();

    public function createWorker();
}
