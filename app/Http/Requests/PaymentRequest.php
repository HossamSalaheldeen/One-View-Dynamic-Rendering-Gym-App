<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'user_id' => ['sometimes','required','exists:users,id'],
            'training_package_id' => ['required','exists:training_packages,id'],
            'gym_id' => ['sometimes','required','exists:gyms,id'],
        ];
    }
}
