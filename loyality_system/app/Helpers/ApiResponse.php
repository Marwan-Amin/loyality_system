<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class ApiResponse
{
    protected $body;
    private $userModel;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * Set response data.
     *
     * @param $data
     * @return $this
     */
    public function setData($data = null): object
    {
        $this->body['data'] = $data;
        return $this;
    }

    public function setError($error): object
    {
        $this->body['status'] = false;
        $this->body['message'] = $error;
        return $this;
    }

    public function setSuccess($message): object
    {
        $this->body['status'] = true;
        $this->body['message'] = $message;
        return $this;
    }

    public function returnJson(): JsonResponse
    {
        $statusCode = 200;
        if ($this->body['status'] == false) {
            $statusCode = 400;
        }
        return $this->response->json($this->body, $statusCode, [], JSON_NUMERIC_CHECK);
    }

    public function notAuthenticated()
    {
        $this->body['status'] = false;
        $this->body['message'] = __('api.notauthenticated');
        $this->body['data'] = null;
        return $this;
    }
}
