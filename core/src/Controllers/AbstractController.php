<?php namespace EvolutionCMS\Controllers;

use EvolutionCMS\Interfaces\ManagerTheme\ControllerInterface;
use EvolutionCMS\Interfaces\ManagerThemeInterface;
use View;
use Illuminate\Contracts\View\View as ViewContract;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @var ManagerThemeInterface
     */
    protected $managerTheme;

    /** @var int */
    protected $index;

    protected $parameters = [];

    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerThemeInterface $managerTheme, array $data = [])
    {
        $this->managerTheme = $managerTheme;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getView(): ?string
    {
        return $this->view;
    }

    /**
     * {@inheritdoc}
     */
    public function setView($view) : bool
    {
        if (View::exists($this->managerTheme->getViewName($view))) {
            $this->view = $view;
        }
        return $view === $this->getView();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function canView(): bool;

    /**
     * {@inheritdoc}
     */
    abstract public function checkLocked(): ?string;

    /**
     * {@inheritdoc}
     */
    public function getParameters(array $params = []): array
    {
        return array_merge($this->parameters, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function process() : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $params = []): string
    {
        return $this->managerTheme->view(
            $this->getView(),
            $this->getParameters($params)
        )->with([
            'controller' => $this
        ])->render();
    }

    /**
     * {@inheritdoc}
     */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getElementId(): int
    {
        return (int)get_by_key($_REQUEST, 'id', 0, 'is_scalar');
    }
}
