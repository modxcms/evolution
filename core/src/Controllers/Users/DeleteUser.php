<?php namespace EvolutionCMS\Controllers\Users;

use EvolutionCMS\Controllers\AbstractController;
use EvolutionCMS\Exceptions\ServiceActionException;
use EvolutionCMS\Exceptions\ServiceValidationException;
use EvolutionCMS\Models;
use EvolutionCMS\Interfaces\ManagerTheme;

class DeleteUser extends AbstractController implements ManagerTheme\PageControllerInterface
{
    protected $view = '';

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
        return $this->managerTheme->getCore()->hasPermission('delete_user');
    }

    public function process() : bool
    {
        try {
            \UserManager::delete($_GET);
        }catch (ServiceValidationException $exception){
            foreach ($exception->getValidationErrors() as $errors){
                if(is_array($errors)){
                    foreach ($errors as $error){
                        EvolutionCMS()->webAlertAndQuit($error);
                    }
                }
            }
        }
        header("Location: index.php?a=99");
        exit();
    }




}
