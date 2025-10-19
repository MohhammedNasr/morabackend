<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success Response
     *
     * @param mixed $data
     * @param string $message
     * @param string|null $hint
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', ?string $hint = null, int $statusCode = 200)
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'hint' => $hint,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param string|null $hint
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = 'Error', ?string $hint = null, $data = null, int $statusCode = 400)
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'hint' => $hint,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Validation Error Response
     *
     * @param mixed $errors
     * @param string $message
     * @param string|null $hint
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($errors, string $message = 'Validation Error', ?string $hint = null)
    {
        return $this->errorResponse(
            message: $message,
            hint: $hint,
            data: ['errors' => $errors],
            statusCode: 422
        );
    }
}
