<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200,
    ): JsonResponse {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function created(
        mixed $data = null,
        string $message = 'Created successfully',
    ): JsonResponse {
        return $this->success($data, $message, 201);
    }

    protected function error(
        string $message = 'An error occurred',
        int $status = 400,
        mixed $errors = null,
    ): JsonResponse {
        $payload = [
            'status'  => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    protected function validationError(mixed $errors): JsonResponse
    {
        return $this->error('Validation failed', 422, $errors);
    }
}
