<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CheckEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('email', $request->email)->get()->first();
        if (!$user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => __('auth.failed'),
                    'data' => null
                ],
                400
            );
        }
        if (!$user->hasVerifiedEmail()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => __('auth.not_verified'),
                    'data' => null
                ],
                400
            );
        }

        return $next($request);
    }
}
