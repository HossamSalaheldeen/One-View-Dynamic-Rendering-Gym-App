<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'national_id' => ['sometimes','required','string','unique:users,national_id,' . optional($this->user)->id],
            'date_of_birth' => ['sometimes','required','date'],
            'gender' => ['sometimes','required'],
            'email' => ['required', 'unique:users,email,' . optional($this->user)->id],
            'password' => [Rule::requiredIf($this->isMethod('POST')), Rule::when($this->isMethod('PUT'), ['nullable']), 'min:6' ,'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:3072']
        ];
    }
}
