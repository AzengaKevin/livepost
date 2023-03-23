<?php

namespace App\Exceptions\Api;

use Exception;
use Illuminate\Support\Facades\Log;

class GeneralApiException extends Exception
{
    public function report()
    {
        Log::error($this->message);
    }

    public function render()
    {
        $message = $this->getMessage();

        return response()->json(compact('message'), $this->code);
    }
    
}
