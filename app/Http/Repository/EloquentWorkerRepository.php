<?php

namespace App\Http\Repository;

use App\Exceptions\CouldNotCreateWorkerException;
use App\Exceptions\InvalidDataException;
use App\Exceptions\WorkerException;
use App\Exceptions\WorkerNotFoundException;
use App\Http\ValueObject\WorkerShiftValueObject;
use App\Http\ValueObject\WorkerValueObject;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;

class EloquentWorkerRepository implements WorkerRepositoryInterface
{

    public function __construct(
        private readonly LoggerInterface $logger
    )
    {

    }

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
        } catch (\OutOfRangeException | QueryException $exception) {
            $this->logger->info('Error while adding shift to a worker', [
                'exception' => $exception->getMessage(),
                'data' => $workerShiftValueObject->toArray()
            ]);
            throw new InvalidDataException('A worker cannot work for multiple shifts in a day.');
        }
    }

    public function getAllWorkers(): Builder
    {
        return $this->getWorkerModelQueryObject();
    }

    public function createWorker(WorkerValueObject $workerValueObject): void
    {
        try {
            $this->getWorkerModelQueryObject()->create($workerValueObject->toArray());
        } catch (\Exception $exception) {
            $this->logger->info('Error while creating a worker', [
                'exception' => $exception->getMessage()
            ]);
            throw new CouldNotCreateWorkerException();
        }

    }

    public function getShiftForWorker(string $workerId, ?string $date): Collection
    {
        /**
         * @var $worker Worker
         */
        try {
            $worker = $this->getWorkerModelQueryObject()->findOrFail($workerId);
        } catch (ModelNotFoundException) {
            throw new WorkerNotFoundException();
        }

        return $worker
            ->shift()
            ->when(!empty($date), function($query) use ($date){
                return $query->where('date', '>=', $date);
            })
            ->get();
    }

    public function updateWorker(string $workerId, WorkerValueObject $workerValueObject): void
    {
        try {
            /**
             * @var $worker Worker
             */
            $worker = $this->getWorkerModelQueryObject()->findOrFail($workerId);
        } catch (ModelNotFoundException) {
            $this->logger->info('Worker not found', [
                'worker' => $workerId
            ]);
            throw new WorkerNotFoundException();
        }
       try {
           $worker->update($workerValueObject->toArray());
       } catch (\Exception) {
           $this->logger->info('Worker not updated', [
               'worker' => $workerId
           ]);

           throw new WorkerException();
       }

    }

    public function deleteWorker(string $workerId): void
    {
        try {
            /**
             * @var $worker Worker
             */
            $worker = $this->getWorkerModelQueryObject()->findOrFail($workerId);
        } catch (ModelNotFoundException) {
            $this->logger->info('Worker not found', [
                'worker' => $workerId
            ]);
            throw new WorkerNotFoundException();
        }
        try {
            $worker->delete();
        } catch (\Exception) {
            $this->logger->info('Worker not updated', [
                'worker' => $workerId
            ]);

            throw new WorkerException();
        }
    }

    public function updateWorkerShift(WorkerShiftValueObject $workerShiftValueObject): void
    {
        try {
            /**
             * @var $worker Worker
             */
            $worker = $this->getWorkerModelQueryObject()->findOrFail($workerShiftValueObject->getWorkerId());
        } catch (ModelNotFoundException) {
            $this->logger->info('Worker not found', [
                'worker' => $workerShiftValueObject->getWorkerId()
            ]);
            throw new WorkerNotFoundException();
        }
        try {
            $worker->shift()->where('date', $workerShiftValueObject->getDate())->update([
                'start_at' => $workerShiftValueObject->getStartAt(),
                'end_at' => $workerShiftValueObject->getEndAt(),
                'slot' => $workerShiftValueObject->getShiftSlot()
            ]);
        } catch (\Exception $exception) {
            $this->logger->info('Worker shift not updated', [
                'worker' => $workerShiftValueObject
            ]);

            throw new WorkerException();
        }
    }

    public function deleteShift(string $workerId, string $shiftId): void
    {
        try {
            /**
             * @var $worker Worker
             */
            $worker = $this->getWorkerModelQueryObject()->findOrFail($workerId);
        } catch (ModelNotFoundException) {
            $this->logger->info('Worker not found', [
                'worker' => $workerId
            ]);
            throw new WorkerNotFoundException();
        }
        try {
            $worker->shift()->where('id', $shiftId)->delete();
        } catch (\Exception $exception) {
            $this->logger->info('Worker shift not deleted', [
                'worker' => $workerId,
                'shiftId' => $shiftId
            ]);

            throw new WorkerException();
        }
    }
}
