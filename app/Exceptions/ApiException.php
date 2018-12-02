<?php

    namespace App\Exceptions;

    use Illuminate\Contracts\Support\Responsable;
    use Illuminate\Http\Response;
    use Illuminate\Support\Arr;

    abstract class ApiException extends \Exception implements Responsable
    {

        /**
         * Create an HTTP response that represents the object.
         *
         * @param  \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         * @throws \InvalidArgumentException
         */
        public function toResponse($request) : Response
        {
            $data = [
                'ok' => FALSE,
                'message' => $this->getMessage(),
                'type' => str_replace(
                    'App\\Exceptions\\',
                    '',
                    \get_class($this)
                ),
            ];
            if(config('app.debug'))
            {
                $data['file'] = $this->getFile();
                $data['line'] = $this->getLine();
                $data['trace'] = collect($this->getTrace())->map(
                    function ($trace) {
                        return Arr::except($trace, ['args']);
                    }
                )->all();
            }
            return new Response(
                $data, 400
            );
        }
    }
