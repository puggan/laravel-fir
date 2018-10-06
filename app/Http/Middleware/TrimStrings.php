<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    public function __construct()
    {
        $this->except = [
            'password',
            'password_confirmation',
        ];
    }
}
