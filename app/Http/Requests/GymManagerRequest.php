<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GymManagerRequest extends FormRequest
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
            'national_id' => ['required','string','unique:users,national_id,' . optional($this->gym_manager)->id],
            'email' => ['required', 'unique:users,email,' . optional($this->gym_manager)->id],
            'password' => [Rule::requiredIf($this->isMethod('POST')), Rule::when($this->isMethod('PUT'), ['nullable']), 'min:8' ,'confirmed'],
            'gym_id' => ['required', 'exists:gyms,id']
        ];
    }
}
