<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\WorkerRepositoryInterface;
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
        $this->workerRepository->getAllWorkers()->paginate(10);

        return response([], 200);
    }

    public function store()
    {

    }
}
