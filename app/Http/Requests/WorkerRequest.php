<?php

namespace App\Http\Requests;

use App\Http\ValueObject\WorkerValueObject;
use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'job_title' => 'required'
        ];
    }

    public function toValueObject(): WorkerValueObject
    {
        return new WorkerValueObject(
            name: $this->get('name'),jobTitle: $this->get('job_title')
        );
    }
}
