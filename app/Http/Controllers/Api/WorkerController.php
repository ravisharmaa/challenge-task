<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CouldNotCreateWorkerException;
use App\Exceptions\WorkerException;
use App\Exceptions\WorkerNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Repository\WorkerRepositoryInterface;
use App\Http\Requests\WorkerRequest;
use Illuminate\Http\Response;

class WorkerController extends Controller
{
    public function __construct(
        private readonly WorkerRepositoryInterface $workerRepository
    )
    {

    }

    public function index(): Response
    {
        $workers = $this->workerRepository->getAllWorkers()->paginate(10);

        return response($workers, 200);
    }

    public function store(WorkerRequest $request): Response
    {
        try {
            $this->workerRepository->createWorker($request->toValueObject());
        } catch (CouldNotCreateWorkerException) {
            return response(['error' => 'Could not create worker'], 400);
        }

        return response()->noContent(201);
    }

    public function update(string $worker, WorkerRequest $request): Response
    {
        try {
            $this->workerRepository->updateWorker($worker, $request->toValueObject());
        } catch (WorkerException | WorkerNotFoundException) {
            return response(['error' => 'Could not find worker'], 400);
        }

        return response()->noContent(200);
    }

    public function delete(string $worker): Response
    {
        try {
            $this->workerRepository->deleteWorker($worker);
        } catch (WorkerException | WorkerNotFoundException) {
            return response(['error' => 'Could not find worker'], 400);
        }

        return response()->noContent(200);
    }
}
