<?php

namespace App\Http\ValueObject;

class WorkerValueObject
{
    public function __construct(
        private readonly string $name,
        private readonly string $jobTitle
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'job_title' => $this->getJobTitle()
        ];
    }
}
