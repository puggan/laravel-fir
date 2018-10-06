<?php
    /**
     * Created by PhpStorm.
     * User: puggan
     * Date: 2018-10-05
     * Time: 21:34
     */

    namespace App\Exceptions;

    use Illuminate\Contracts\Support\Responsable;
    use Illuminate\Http\Response;

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
        public function toResponse($request)
        {
            return new Response(
                [
                    'ok' => FALSE,
                    'message' => $this->getMessage(),
                    'type' => str_replace(
                        'App\\Exceptions\\',
                        '',
                        \get_class($this)
                    ),
                ],
                400
            );
        }
    }
