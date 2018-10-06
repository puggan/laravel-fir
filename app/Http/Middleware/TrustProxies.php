<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    public function __construct(Repository $config)
    {
        $this->headers = Request::HEADER_X_FORWARDED_ALL;
        parent::__construct($config);
    }
}
