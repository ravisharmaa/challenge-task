<?php

namespace App\Http\ValueObject;

use Carbon\Carbon;

class WorkerShiftValueObject
{
    private const RANGES = [
        ['start' => '00:00:00', 'end' => '08:00:00', 'label' => '0-8'],
        ['start' => '08:00:00', 'end' => '16:00:00', 'label' => '8-16'],
        ['start' => '16:00:00', 'end' => '23:59:59', 'label' => '16-24'],
    ];

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
        $rangeLabel = '';

        foreach (self::RANGES as $range) {
            $start = Carbon::parse($range['start']);
            $end = Carbon::parse($range['end']);
            if ($startAt->between($start, $end) && $endAt->between($start, $end)) {
                $rangeLabel = $range['label'];
            }
        }
        if (empty($rangeLabel)) {
           throw new \OutOfRangeException();
        }

        return $rangeLabel;
    }

    public function toArray(): array
    {
        return [
            'start_at' => $this->getStartAt(),
            'end_at' => $this->getEndAt(),
            'date' => $this->getDate(),
            'shift' => $this->getShiftSlot(),
            'worker' => $this->getWorkerId()
        ];
    }
}
