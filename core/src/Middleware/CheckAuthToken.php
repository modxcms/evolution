<?php namespace EvolutionCMS\Middleware;

use Carbon\Carbon;
use Closure;
use EvolutionCMS\Models\User;

class CheckAuthToken
{
    public function handle($request, Closure $next)
    {
        $headers = getallheaders();
        if (isset($headers['authorization'])) {
            $headers['Authorization'] = $headers['authorization'];
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);


        $user = User::query()->where('access_token', $token)->where('valid_to', '>', Carbon::now())->first();
        if (is_null($user)) {
            return \Response::json(['errors' => ['token' => ['invalid token']]], '403');
        }
        $request->attributes->add(['user' => $user]);
        return $next($request);
    }
}
