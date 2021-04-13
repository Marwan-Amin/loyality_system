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
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function login(Request $request)
    {
        // Validate sign in input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->apiResponse->setError($validator->errors()->first())->setData()->returnJson();
        }

        // Check if user credentials is correct
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return $this->apiResponse->setError(__('auth.failed'))->setData()->returnJson();
        }

        // Access and refresh token generation
        $client = Client::where('password_client', 1)->first();
        $params = [
            "grant_type" => "password",
            "client_id" => $client->id,
            "client_secret" => $client->secret,
            "username" => $request->email,
            "password" => $request->password,
            "scope" => null,
        ];
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        $token = Route::dispatch($proxy);
        $response = json_decode($token->getContent(), true);
        return $this->apiResponse->setSuccess(__('auth.login_success'))->setData($response)->returnJson();
    }
}
