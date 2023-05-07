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
        $startAt = Carbon::parse($this->getStartAt());
        $endAt = Carbon::parse($this->getEndAt());

        $ranges = [
            ['start' => '00:00:00', 'end' => '08:00:00', 'label' => '0-8'],
            ['start' => '08:00:00', 'end' => '16:00:00', 'label' => '8-16'],
            ['start' => '16:00:00', 'end' => '23:59:59', 'label' => '16-24'],
        ];

        foreach ($ranges as $range) {
            $start = Carbon::parse($range['start']);
            $end = Carbon::parse($range['end']);
            if ($startAt->between($start, $end) && $endAt->between($start, $end)) {
                return $range['label'];
            }
        }

        $labels = [];
        foreach ($ranges as $range) {
            $start = Carbon::parse($range['start']);
            $end = Carbon::parse($range['end']);
            if ($startAt->lt($end) && $endAt->gt($start)) {
                $labels[] = $range['label'];
            }
        }

        return implode(', ', $labels);
    }
}
