<?php

namespace EvolutionCMS\Middleware;

use Closure, ManagerTheme;

class Manager
{
    public function handle($request, Closure $next)
    {
        // Update last action in table active_users
        global $action;
        $action = ManagerTheme::getActionId();

        // accesscontrol.php checks to see if the user is logged in. If not, a log in form is shown
        if (0 !== $action && ManagerTheme::isAuthManager() === false) {
            return ManagerTheme::renderLoginPage();
        }

        // Ignore Logout and LogIn action
        if (8 !== $action && 0 !== $action && ManagerTheme::hasManagerAccess() === false) {
            return ManagerTheme::renderAccessPage();
        }

        return $next($request);
    }
}
