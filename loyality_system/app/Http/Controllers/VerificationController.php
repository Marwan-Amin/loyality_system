<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;

class VerificationController extends Controller
{
    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function verify(VerifyRequest $request)
    {
        $user = User::where('email', base64_decode($request->code))->get()->first();
        if ($user->hasVerifiedEmail()) {
            return $this->apiResponse->setSuccess(__('auth.already_verified'))->setData(new UserResource($user))->returnJson();
        }
        $user->markEmailAsVerified();
        return $this->apiResponse->setSuccess(__('auth.success_verified'))->setData(new UserResource($user))->returnJson();
    }
}
