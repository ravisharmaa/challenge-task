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
            'date' => 'required|date_format:Y-m-d',
            'start_at' => 'required|date_format:H:i:s',
            'end_at' => ['required','date_format:H:i:s','after:start_at', new HourDifference]
        ];
    }

    public function toValueObject(): WorkerShiftValueObject
    {
        return new WorkerShiftValueObject(
            workerId: $this->get('worker_id'),
            date: $this->get('date'),
            startAt: $this->get('start_at'),
            endAt: $this->get('end_at')
        );
    }
}
