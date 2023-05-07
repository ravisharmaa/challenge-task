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
        $this->postJson(route('worker-shift.create'))->assertUnprocessable();
    }

    public function test_a_shift_should_have_at_least_8_hours_difference()
    {
        $worker = Worker::factory()->create();
        $this->postJson(route('worker-shift.create'), [
            'worker_id' => $worker->id,
            'start_at' => now()->toTimeString(),
            'date' => now()->format('Y-m-d'),
            'end_at' => now()->addHours(9)->toTimeString()
        ])->assertUnprocessable();

        $this->assertDatabaseCount('shifts', 0);
    }

    public function test_a_worker_can_book_a_shift()
    {
        $worker = Worker::factory()->create();
        $this->postJson(route('worker-shift.create'), [
            'worker_id' => $worker->id,
            'start_at' => now()->toTimeString(),
            'date' => now()->format('Y-m-d'),
            'end_at' => now()->addHours(8)->toTimeString()
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
            startAt: now()->toTimeString(),
            endAt: now()->addHours(8)->toTimeString()
        );
        $worker->shift()->create([
            'date' => $valueObject->getDate(),
            'start_at' => $valueObject->getStartAt(),
            'end_at' => $valueObject->getEndAt(),
            'slot' => $valueObject->getShiftSlot(),
        ]);

        $this->postJson(route('worker-shift.create'), [
            'worker_id' => $worker->id,
            'date' => $valueObject->getDate(),
            'start_at' => now()->toTimeString(),
            'end_at' => now()->addHours(8)->toTimeString()
        ]);

        $this->assertDatabaseCount('shifts', 1);
    }
}
