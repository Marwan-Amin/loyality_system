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
        $this->status = '';
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
        if (!$transaction) {
            $this->status = "not_found";
            return false;
        }
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
        if ($this->status == 'not_found') {
            return __('transaction.not_valid_transaction_id');
        }
        
        return __('transaction.not_enough_points');
    }
}
