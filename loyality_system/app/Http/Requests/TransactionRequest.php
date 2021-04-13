<?php

namespace App\Http\Requests;

use App\Rules\ValidateAvailablePoints;
use App\Rules\ValidateConfirmedTransaction;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
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
        if ($this->path() == 'api/transaction') {
            return [
                'user_id' => 'required|integer|min:1|exists:users,id|not_in:' . auth()->user()->id,
                'points' => 'required|integer|min:1|max:' . auth()->user()->points
            ];
        }

        if ($this->path() == 'api/transaction/confirm') {
            return [
                'transaction_id' => ['required', 'integer', 'min:1', Rule::exists('transactions', 'id')->where(function ($query) {
                    return $query->where('sender_id', auth()->user()->id);
                }), new ValidateConfirmedTransaction, new ValidateAvailablePoints],
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => null
                ],
                400
            )
        );
    }

    public function messages()
    {
        return [
            'user_id.not_in' => __('transaction.current_user_id'),
            'points.max' => __('transaction.max_points'),
            'transaction_id.exists' => __('transaction.not_valid_transaction_id')
        ];
    }
}
