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

        if ($transaction->isConfirmed()) {
            $this->status = "confirmed";
            return false;
        }
        if ($transaction->isExpired()) {
            $this->status = "expired";
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
        if ($this->status == 'confirmed') {
            return __('transaction.transaction_already_confirmed');
        }

        if ($this->status == 'expired') {
            return __('transaction.transaction_expired');
        }

        if ($this->status == 'not_found') {
            return __('transaction.not_valid_transaction_id');
        }
    }
}
