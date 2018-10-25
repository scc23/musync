<?php

namespace App;

class APIHelper
{
    public static function createPayload($payload, $status_code) {
        return response()->json([
            'payload' => $payload,
        ], $status_code);
    }

    public static function createError($error, $status_code) {
        return response()->json([
            'error' => $error,
        ], $status_code);
    }
}
