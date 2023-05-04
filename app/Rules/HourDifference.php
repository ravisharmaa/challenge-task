<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class HourDifference implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startAt = $this->data['start_at'];
        $diff = date_diff(date_create($startAt), date_create($value));
        if ($diff->h !== 8) {
            $fail('The :attribute must be exactly 8 hours after the start date.');
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
