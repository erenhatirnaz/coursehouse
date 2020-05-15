<?php

use Illuminate\Http\Response;

if (! function_exists('show_error')) {
    function show_error(int $code, string $message): Response
    {
        return response()->view('error', [
            'code' => $code,
            'message' => $message
        ])->setStatusCode($code);
    }
}
