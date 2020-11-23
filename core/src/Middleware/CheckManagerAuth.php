<?php namespace EvolutionCMS\Middleware;

use Closure;

class CheckManagerAuth
{
    public function handle($request, Closure $next)
    {
        if (\ManagerTheme::hasManagerAccess() === false) {
            return \Response::json(['error' => 'No Manager Access'], '403');

        }
        return $next($request);
    }
}
