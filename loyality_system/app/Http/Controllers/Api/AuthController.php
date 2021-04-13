<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Notifications\ActivateUserEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function register(AuthRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated());
            $user->notify(new ActivateUserEmail($user));
            DB::commit();
        } catch (Exception $exception) {
            dd($exception);
            DB::rollBack();
            return $this->apiResponse->setError(__('auth.register_failed'))->setData()->returnJson();
        }
        return $this->apiResponse->setSuccess(__('auth.register_success'))->setData(new UserResource($user))->returnJson();
    }
}
