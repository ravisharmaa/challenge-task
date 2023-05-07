<?php

namespace Tests\Feature;

use App\Http\ValueObject\WorkerShiftValueObject;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkerShiftControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_validates_the_input_data()
    {
        $this->postJson(route('worker.shift.create'))->assertUnprocessable();
    }

    public function test_a_shift_should_have_at_least_8_hours_difference()
    {
        $worker = Worker::factory()->create();
        $this->postJson(route('worker.shift.create'), [
            'worker_id' => $worker->id,
            'start_at' => '08:00:00',
            'date' => now()->format('Y-m-d'),
            'end_at' => '12:00:00'
        ])->assertUnprocessable();

        $this->assertDatabaseCount('shifts', 0);
    }

    public function test_a_worker_can_book_a_shift()
    {
        $worker = Worker::factory()->create();
        $this->postJson(route('worker.shift.create'), [
            'worker_id' => $worker->id,
            'start_at' => '08:00:00',
            'date' => now()->format('Y-m-d'),
            'end_at' => '16:00:00'
        ]);

        $this->assertDatabaseCount('shifts', 1);
    }

    public function test_a_worker_cannot_book_two_shifts_on_the_same_day()
    {
        /**
         * @var $worker Worker
         */
        $worker = Worker::factory()->create();
        $valueObject = new WorkerShiftValueObject(
            workerId: $worker->id,
            date: now()->format('Y-m-d'),
            startAt: '08:00:00',
            endAt: '16:00:00'
        );
        $worker->shift()->create([
            'date' => $valueObject->getDate(),
            'start_at' => $valueObject->getStartAt(),
            'end_at' => $valueObject->getEndAt(),
            'slot' => $valueObject->getShiftSlot(),
        ]);

        $this->postJson(route('worker.shift.create'), [
            'worker_id' => $worker->id,
            'start_at' => '08:00:00',
            'date' => now()->format('Y-m-d'),
            'end_at' => '16:00:00'
        ])->assertStatus(400);

        $this->assertDatabaseCount('shifts', 1);
    }
}
