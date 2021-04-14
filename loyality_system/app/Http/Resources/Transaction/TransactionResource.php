<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'points' => $this->points,
            'is_expired' => $this->is_expired,
            'is_confirmed' => $this->is_confirmed,
        ];
    }
}
