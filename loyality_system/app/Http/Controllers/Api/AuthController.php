<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function register(AuthRequest $request)
    {
        $user = User::create($request->validated());
        return $this->apiResponse->setSuccess(__('auth.register_success'))->setData(new UserResource($user))->returnJson();
    }
}
