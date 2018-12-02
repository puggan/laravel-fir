<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     */
    public function render($request, \Exception $e)
    {
        if(strpos($request->path(), 'api/') === 0) {
            $request->headers->set('accept', 'application/json', true);
        }
        return parent::render($request, $e);
    }
}
