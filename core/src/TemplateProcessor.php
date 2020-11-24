<?php namespace EvolutionCMS;

use EvolutionCMS\Interfaces\CoreInterface;
use EvolutionCMS\Models\SiteTemplate;

class TemplateProcessor
{
    /**
     * @var Interfaces\CoreInterface
     */
    protected $core;


    public function __construct(Interfaces\CoreInterface $core)
    {
        $this->core = $core;
    }

    public function getBladeDocumentContent()
    {
        $template = false;
        $doc = $this->core->documentObject;
        if(isset($this->core->documentObject['templatealias']) && $this->core->documentObject['templatealias'] != ''){
            $templateAlias = $this->core->documentObject['templatealias'];
        }else {
            $templateAlias = SiteTemplate::select('templatealias')->find($doc['template'])->templatealias;
        }

        switch (true) {
            case $this->core['view']->exists('tpl-' . $doc['template'] . '_doc-' . $doc['id']):
                $template = 'tpl-' . $doc['template'] . '_doc-' . $doc['id'];
                break;
            case $this->core['view']->exists('doc-' . $doc['id']):
                $template = 'doc-' . $doc['id'];
                break;
            case $this->core['view']->exists('tpl-' . $doc['template']):
                $template = 'tpl-' . $doc['template'];
                break;
            case $this->core['view']->exists($templateAlias):
               
                $baseClassName = $this->core->getConfig('ControllerNamespace') . 'BaseController';
                if (class_exists($baseClassName)) { //Проверяем есть ли Base класс
                    $classArray = explode('.', $templateAlias);
                    $classArray = array_map(
                        function ($item) {
                            return $this->setPsrClassNames($item);
                        },
                        $classArray
                    );
                    $classViewPart = implode('.', $classArray);
                    $className = str_replace('.', '\\', $classViewPart);
                    $className =
                        $this->core->getConfig('ControllerNamespace') . ucfirst($className) . 'Controller';
                    if (!class_exists(
                        $className
                    )) { //Проверяем есть ли контроллер по алиасу, если нет, то помещаем Base
                        $className = $baseClassName;
                    }
                    $controller = $this->core->make($className);
                    if (method_exists($controller, 'main')) {
                        $this->core->call([$controller, 'main']);
                    }
                }
                $template = $templateAlias;
                break;
            default:
                $content = $doc['template'] ? $this->core->documentContent : $doc['content'];
                if (!$content) {
                    $content = $doc['content'];
                }
                if (strpos($content, '@FILE:') === 0) {
                    $template = str_replace('@FILE:', '', trim($content));
                    if (!$this->core['view']->exists($template)) {
                        $this->core->documentObject['template'] = 0;
                        $this->core->documentContent = $doc['content'];
                    }
                }
        }
        return $template;
    }

    /**
     * @param $templateID
     * @return mixed
     */
    public function getTemplateCodeFromDB($templateID)
    {
        return SiteTemplate::findOrFail($templateID)->content;
    }

    /**
     * @param string $templateAlias
     * @return string
     */
    private function setPsrClassNames(string $templateAlias): string
    {
        $explodedTplName = explode('_', $templateAlias);
        $explodedTplName = array_map(
            function ($item) {
                return ucfirst(trim($item));
            },
            $explodedTplName
        );

        return join($explodedTplName);
    }
}
