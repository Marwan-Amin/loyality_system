<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function getPoints(UserRequest $request)
    {
        if (!$request->user_id || $request->user_id == auth()->user()->id) {
            $user = auth()->user();
        } else {
            $user = User::find($request->user_id);
        }
        return $this->apiResponse->setSuccess(__('user.get_user', ['user' => $user->name]))->setData(new UserResource($user))->returnJson();
    }

    public function getTransactions(UserRequest $request)
    {
        $transactions = Transaction::where(function ($query) use ($request) {
            $query->where('sender_id', auth()->user()->id);
            if ($request->user_id) {
                $query->where('receiver_id', $request->user_id);
            }
            if ($request->filter == 'confirmed') {
                $query->where('is_confirmed', 1);
            }
            if ($request->filter == 'expired') {
                $query->where('is_expired', 1);
            }
        })->get();
        return $this->apiResponse->setSuccess(__('user.get_transactions'))->setData(new TransactionCollection($transactions))->returnJson();
    }
}
