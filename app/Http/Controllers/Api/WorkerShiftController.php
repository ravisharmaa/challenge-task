<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidDataException;
use App\Http\Controllers\Controller;
use App\Http\Repository\WorkerRepositoryInterface;
use App\Http\Requests\WorkerShiftRequest;
use App\Models\Worker;
use Symfony\Component\HttpFoundation\Response;

class WorkerShiftController extends Controller
{
    public function __construct(
        private readonly WorkerRepositoryInterface $workerRepository
    )
    {
    }

    public function index(Worker $worker)
    {
        $this->workerRepository->getShiftForWorker($worker, request('date') ?? null);
    }

    public function store(WorkerShiftRequest $request): Response
    {
        try {
            $this->workerRepository->assignShiftToWorker($request->toValueObject());
        } catch (InvalidDataException $exception) {
            return response([
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ], 422);
        }

        return response([
            'success' => [
                'message' => 'Shift added successfully.'
            ]
        ], 201);
    }
}
