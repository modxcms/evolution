<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class Password extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = 'page.password';

    /**
     * {@inheritdoc}
     */
    public function checkLocked(): ?string
    {
        $out = Models\ActiveUser::locked(28)
            ->first();
        if ($out !== null) {
            return sprintf($this->managerTheme->getLexicon('error_no_privileges'), $out->username);
        }

        return $out;
    }

    /**
     * {@inheritdoc}
     */
    public function canView(): bool
    {
        return $this->managerTheme->getCore()->hasPermission('change_password');
    }

    public function getParameters(array $params = []): array
    {
        return [
            'actionButtons' => $this->parameterActionButtons()
        ];
    }

    protected function parameterActionButtons()
    {
        return [
            'save' => 1,
            'cancel' => 1
        ];
    }
}
