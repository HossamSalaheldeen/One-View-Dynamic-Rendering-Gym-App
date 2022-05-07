<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'national_id' => ['nullable','string','unique:users,national_id,' . optional($this->user)->id],
            'date_of_birth' => ['required'],
            'email' => ['required', 'unique:users,email,' . optional($this->user)->id],
            'password' => ['required', 'min:8' ,'confirmed'],
        ];
    }
}
