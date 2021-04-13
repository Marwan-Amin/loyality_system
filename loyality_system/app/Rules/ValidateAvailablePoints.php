<?php

namespace App\Rules;

use App\Models\Transaction;
use Illuminate\Contracts\Validation\Rule;

class ValidateAvailablePoints implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $transaction = Transaction::find($value);
        if (auth()->user()->points < $transaction->points) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('transaction.not_enough_points');
    }
}
