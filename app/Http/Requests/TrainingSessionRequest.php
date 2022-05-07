<?php

namespace App\Http\Requests;

use App\Rules\NotOverlapped;
use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'starts_at' => ['sometimes','required','date_format:Y-m-d H:i:s', new NotOverlapped($this->training_session)],
            'finishes_at' => ['sometimes','required','date_format:Y-m-d H:i:s','after:starts_at',new NotOverlapped($this->training_session)],
            'coaches' => ['array'],
            'coaches.*' => ['required','exists:coaches,id']
        ];
    }
}
