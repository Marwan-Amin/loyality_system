<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function Transaction(TransactionRequest $request)
    {
        $transaction = Transaction::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->user_id,
            'points' => $request->points,
            'is_expired' => 0,
        ]);

        $receiverUser = User::find($request->user_id);
        return $this->apiResponse
            ->setSuccess(__('transaction.create_success', ['points' => $request->points, 'user' => $receiverUser->name]))
            ->setData(new TransactionResource($transaction))
            ->returnJson();
    }

    public function confirm(TransactionRequest $request)
    {
        try {
            $transaction = Transaction::find($request->transaction_id);
            DB::transaction(function () use ($transaction) {
                $transaction->update([
                    'is_confirmed' => true
                ]);
                $sender = User::find($transaction->sender_id);
                $sender->subtractPoints($transaction->points);
                $receiver = User::find($transaction->receiver_id);
                $receiver->transferPoints($transaction->points);
            });
        } catch (Exception $exception) {
            return $this->apiResponse
                ->setError(__('transaction.confirm_failed'))
                ->setData()
                ->returnJson();
        }

        return $this->apiResponse
            ->setSuccess(__('transaction.confirm_success', ['points' => $transaction->points]))
            ->setData(new TransactionResource($transaction))
            ->returnJson();
    }
}
