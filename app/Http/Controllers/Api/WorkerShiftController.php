<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidDataException;
use App\Exceptions\WorkerException;
use App\Http\Controllers\Controller;
use App\Http\Repository\WorkerRepositoryInterface;
use App\Http\Requests\WorkerShiftRequest;
use Symfony\Component\HttpFoundation\Response;

class WorkerShiftController extends Controller
{
    public function __construct(
        private readonly WorkerRepositoryInterface $workerRepository
    )
    {
    }

    public function index(string $worker): Response
    {
        $workerShifts = $this->workerRepository->getShiftForWorker($worker, request('date') ?? null);

        return response($workerShifts);
    }

    public function store(WorkerShiftRequest $request): Response
    {
        try {
            $this->workerRepository->assignShiftToWorker($request->toValueObject());
        } catch (InvalidDataException $exception) {
            return response([
                'error' => 'Cannot assign shift to worker'
            ], 400);
        }

        return response([
            'success' => [
                'message' => 'Shift added successfully.'
            ]
        ], 201);
    }

    public function update(WorkerShiftRequest $request): Response
    {
        try {
            $this->workerRepository->updateWorkerShift($request->toValueObject());
        } catch (WorkerException) {
            return response([
                'error' => 'Cannot update shift of worker'
            ], 422);
        }

        return response([
            'success' => [
                'message' => 'Shift added successfully.'
            ]
        ], 201);
    }

    public function delete(string $workerId, string $shiftId): Response
    {
        try {
            $this->workerRepository->deleteShift($workerId, $shiftId);
        } catch (WorkerException) {
            return response([
                'error' => 'Cannot update shift of worker'
            ], 422);
        }

        return response([
            'success' => [
                'message' => 'Shift added successfully.'
            ]
        ], 200);
    }
}
