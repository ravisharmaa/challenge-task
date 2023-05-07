<?php

namespace Tests\Unit;

use App\Http\ValueObject\WorkerShiftValueObject;
use PHPUnit\Framework\TestCase;

class WorkerShiftValueObjectTest extends TestCase
{
    public function test_it_determines_the_proper_slot_from_given_time()
    {
        $valueObject = new WorkerShiftValueObject(
            '100',
            '2022-05-04',
            startAt: '08:00:00',
            endAt: '16:00:00'
        );
        $this->assertSame('8-16', $valueObject->getShiftSlot());
    }
}
