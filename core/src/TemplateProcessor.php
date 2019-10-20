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
        $templateAlias = SiteTemplate::select('templatealias')->find($doc['template'])->templatealias;

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
}
