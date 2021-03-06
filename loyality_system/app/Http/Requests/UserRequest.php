<?php

namespace App\Http\Requests;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use PHPUnit\TextUI\XmlConfiguration\Constant;

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
        if ($this->path() == 'api/points') {
            return [
                'user_id' => 'integer|min:1|exists:users,id'
            ];
        }

        if ($this->path() == 'api/transactions') {
            return [
                'filter' => 'string|in:' . Constants::STATUS_CONFIRMED . ',' . Constants::STATUS_EXPIRED . ',' . Constants::STATUS_PENDING,
                'user_id' => 'integer|min:1|exists:users,id||not_in:' . auth()->user()->id
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
}
