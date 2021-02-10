<?php namespace EvolutionCMS\Middleware;

use Closure;

class VerifyCsrfToken
{
    public function handle($request, Closure $next)
    {
        if ($_SESSION['_token'] !== $request->input('_token')) {
            return \Response::json(['error' => 'CSRF token mismatch'], '403');

        }
        return $next($request);
    }
}
