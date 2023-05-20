<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception  $exception
     * @return Response
     */
    public function render($request, Throwable $exception)
    {
        // For some reason exceptions skip middlewares, so we re-do the json header for api requests here. We put the
        // scope to v1 only just so it is encapsulated to v1, which will allow more flexibility in the future.
        if (str_starts_with($request->path(), 'api/v1/')) {
            $request->headers->set('Accept', 'application/json');
        }

        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                // not found
                case 404:
                    return redirect(route('home'));
                    break;

                    // internal error
                case '500':
                    return redirect(route('home'));
                    break;

                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        } else {
            return parent::render($request, $exception);
        }
    }
}
