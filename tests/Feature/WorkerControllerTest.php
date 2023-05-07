<?php

namespace Tests\Feature;

use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_get_all_the_workers()
    {
        $this->getJson(route('workers.index'))->assertOk();
    }

    public function test_it_should_validate_worker_creation_request()
    {
        $this->postJson(route('workers.create'))->assertUnprocessable();
    }

    public function test_it_should_create_a_worker()
    {
        $worker = Worker::factory()->make()->toArray();
        $this->postJson(route('workers.create'), $worker)->assertCreated();
        $this->assertDatabaseCount('workers', 1);
    }

    public function test_it_should_update_a_worker()
    {
        $worker = Worker::factory()->create();
        $newWorker = Worker::factory()->make()->toArray();
        $this->putJson(route('workers.update', ['workerId' => $worker->id]), $newWorker)->assertOk();
        $this->assertDatabaseCount('workers', 1);
        $this->assertSame($worker->fresh()->name, $newWorker['name']);
    }

    public function test_it_should_validate_worker_update_request()
    {
        $worker = Worker::factory()->create();
        $this->putJson(route('workers.update', ['workerId' => $worker->id]), [])->assertUnprocessable();
    }

    public function test_it_should_delete_a_worker()
    {
        $worker = Worker::factory()->create();
        $this->deleteJson(route('workers.delete', ['workerId' => $worker->id]), [])->assertOk();
        $this->assertDatabaseCount('workers', 0);
    }

    public function test_it_should_respond_properly_when_a_worker_is_not_found()
    {
        $this->deleteJson(route('workers.delete', ['workerId' => '1']), [])->assertBadRequest();
    }
}
