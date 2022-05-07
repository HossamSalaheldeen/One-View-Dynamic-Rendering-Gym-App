<?php

namespace App\Rules;

use App\Models\TrainingSession;
use Illuminate\Contracts\Validation\Rule;

class NotOverlapped implements Rule
{
    private $trainingSession;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($training_session)
    {
        $this->trainingSession = $training_session;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->trainingSession) {
            $check = TrainingSession::query()
                ->where('id', '!=', $this->trainingSession->id)
                ->whereBetween($attribute, [request()->starts_at, request()->finishes_at])->exists();
        } else {
            $check = TrainingSession::query()
                ->whereBetween($attribute, [request()->starts_at, request()->finishes_at])->exists();
        }

        return !$check;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The period overlapping with other training sessions.';
    }
}
