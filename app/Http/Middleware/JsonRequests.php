<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $type = $request->headers->get('Accept', 'application/json');
        if ($type != 'application/json' && $type != '*/*') {
            return response()->json(['error' => 'Server only accepts application/json requests'], 406);
        }

        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
