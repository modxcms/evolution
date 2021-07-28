<?php namespace EvolutionCMS\Middleware;

use Closure;

class VerifyCsrfToken
{
    public function handle($request, Closure $next)
    {
        if ($request->has('_token') && isset($_SESSION['_token']) && $_SESSION['_token'] !== $request->input('_token')) {
            return \Response::json(['error' => 'CSRF token mismatch'], '403');

        }
        return $next($request);
    }
}
