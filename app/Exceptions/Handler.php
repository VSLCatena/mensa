<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Http\Request;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $exception, Request $request) {
             if($this->isHttpException($exception))
             {
                switch ($exception->getStatusCode())
                {
                    case 404:
                        return redirect(route('home'));
                        break;
                    case '500':
                        return redirect(route('home'));
                        break;

                    default:
                        return $this->renderHttpException($exception);
                        break;
                }
            }
            else
            {
                return parent::renderable(Throwable $exception, Request $request);
            }
        }
    }
}
