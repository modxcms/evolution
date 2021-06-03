<?php

namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme;
use Illuminate\Http\Response;

class Actions extends AbstractController implements ManagerTheme\PageControllerInterface
{
    public function handleAction()
    {
        global $action;
        // Update last action in table active_users
        $action = $this->managerTheme->getActionId();

        $output = '';

        if ($action === null) {
            $_style = $this->managerTheme->getStyle();
            // first we check to see if this is a frameset request
            if (!isset($_POST['updateMsgCount'])) {
                \EvolutionCMS\Tracy\Debugger::$showBar = false;
                // this looks to be a top-level frameset request, so let's serve up a frameset
                $output = $this->managerTheme->handle(1, ['frame' => 1]);
            }
        } else {
            $output = $this->managerTheme->handle($action);
        }

        if ($output instanceof Response) {
            return $output;
        }

        $isRedirect = array_reduce(headers_list(), function($result, $header) {
            return strpos($header, 'Location') === 0;
        }, 0);

        return response()->make($output)->setStatusCode($isRedirect ? 302 : 200);
    }

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return true;
    }

    public function process() : bool
    {
        return true;
    }
}
