<?php

namespace App\Http\Requests;

use App\Http\ValueObject\WorkerShiftValueObject;
use App\Rules\HourDifference;
use Illuminate\Foundation\Http\FormRequest;

class WorkerShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'worker_id' => 'required|exists:App\Models\Worker,id',
            'day_id' => 'required|exists:App\Models\Day,id',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => ['required','date_format:Y-m-d H:i:s','after:start_at', new HourDifference]
        ];
    }

    public function toValueObject(): WorkerShiftValueObject
    {
        return new WorkerShiftValueObject(
            workerId: $this->get('worker_id'),
            dayId: $this->get('day_id'),
            startAt: $this->get('start_at'),
            endAt: $this->get('end_at')
        );
    }
}
