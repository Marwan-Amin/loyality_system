<?php

namespace App\Rules;

use App\Models\Transaction;
use Illuminate\Contracts\Validation\Rule;

class ValidateConfirmedTransaction implements Rule
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
        $transaction = Transaction::where([
            'id' => $value,
            'is_confirmed' => 1,
        ])->get()->first();
        if ($transaction) {
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
        return __('transaction.transaction_already_confirmed');
    }
}
