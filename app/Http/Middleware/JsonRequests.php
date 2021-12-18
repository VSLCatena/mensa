<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class JsonRequests
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $type = $request->headers->get('Accept', 'application/json');
        if (!Str::contains($type, ['application/json', '*/*'])) {
            return response()->json(['error' => 'Server only accepts application/json requests'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
