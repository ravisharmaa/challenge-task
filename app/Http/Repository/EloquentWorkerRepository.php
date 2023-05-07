<?php

namespace App\Http\Repository;

use App\Exceptions\InvalidDataException;
use App\Http\ValueObject\WorkerShiftValueObject;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class EloquentWorkerRepository implements WorkerRepositoryInterface
{
    public function getWorkerModelQueryObject(): Builder
    {
        return Worker::query();
    }

    /**
     * @param WorkerShiftValueObject $workerShiftValueObject
     * @return void
     * @throws InvalidDataException
     */
    public function assignShiftToWorker(WorkerShiftValueObject $workerShiftValueObject): void
    {
        try {
            $queryObject = $this->getWorkerModelQueryObject();
            /**
             * @var $worker Worker
             */
            $worker = $queryObject->findOrFail($workerShiftValueObject->getWorkerId());
        } catch (ModelNotFoundException $exception) {
            return;
        }
        try {
            $worker->shift()->create([
                'start_at' => $workerShiftValueObject->getStartAt(),
                'end_at' => $workerShiftValueObject->getEndAt(),
                'slot' => $workerShiftValueObject->getShiftSlot(),
                'date' => $workerShiftValueObject->getDate()
            ]);
        } catch (QueryException $exception) {
            throw new InvalidDataException('A worker cannot work for multiple shifts in a day.');
        }
    }

    public function getAllWorkers(): Collection
    {
        return $this->getWorkerModelQueryObject()->get();
    }

    public function createWorker()
    {

    }
}
