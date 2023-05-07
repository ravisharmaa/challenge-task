<?php

namespace App\Http\ValueObject;

use Carbon\Carbon;

class WorkerShiftValueObject
{
    public function __construct(
        private int $workerId,
        private string $date,
        private string $startAt,
        private string $endAt
    )
    {
    }

    public function getWorkerId(): int
    {
        return $this->workerId;
    }

    public function getStartAt(): string
    {
        return $this->startAt;
    }

    public function getEndAt(): string
    {
        return $this->endAt;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getShiftSlot(): string
    {
        $startAt = Carbon::createFromFormat('H:i:s', $this->getStartAt());
        return match ($startAt) {
            $startAt->between('00:00:00', '07:59:59') => '0-8',
            $startAt->between('08:00:00', '15:59:59') => '8-16',
            default => '16-24'
        };
    }
}
