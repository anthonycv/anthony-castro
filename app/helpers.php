<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('success_api_response')) {
    function success_api_response($message, $data, $code = 200): JsonResponse
    {
        return response()->json(
            [
                'error' => false,
                'message' => $message,
                'data' => $data
            ], $code);
    }
}

if (!function_exists('error_api_response')) {
    function error_api_response($message, $trace, $code = 500): JsonResponse
    {
        return response()->json(
            [
                'error' => true,
                'message' => $message,
                'data' => $trace
            ], $code);
    }
}
