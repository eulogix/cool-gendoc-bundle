<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model;

use Eulogix\Cool\Gendoc\Bundle\Model\om\BaseQueuedDocument;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;
use Eulogix\Cool\Lib\File\FileRepositoryInterface;
use Eulogix\Cool\Lib\Template\Template;
use Eulogix\Lib\File\Proxy\FileProxyInterface;

class QueuedDocument extends BaseQueuedDocument
{
    const FILE_CATEGORY_CUSTOM_TEMPLATE = "CUSTOM_TEMPLATE";
    const FILE_CATEGORY_RENDERED_FILE = "RENDERED_FILE";

    const STATUS_PENDING = 'PENDING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_GENERATED = 'GENERATED';
    const STATUS_ERROR = 'ERROR';

    // if these keys are available in the template data, will be used in place of the whole data array
    const CONTEXT_START = 'start_snippet_data';
    const CONTEXT_FINISH = 'finish_snippet_data';

    /**
     * @throws \Exception
     * @throws \PropelException
     */
    public function process()
    {
        if($this->getStatus() == self::STATUS_PROCESSING) {
            try {

                if($startSnippet = $this->getCodeSnippetRelatedByStartCodeSnippetId())
                    $startSnippet->evaluate($this->getSnippetContext(self::CONTEXT_START));

                $this->getFileRepository()->storeFileAt( $this->render(), 'cat_' . self::FILE_CATEGORY_RENDERED_FILE);
                $this->setStatus(self::STATUS_GENERATED)
                     ->save();

                if($finishSnippet = $this->getCodeSnippetRelatedByFinishCodeSnippetId())
                    $finishSnippet->evaluate($this->getSnippetContext(self::CONTEXT_FINISH));

            } catch(\Exception $e) {
                $this ->setStatus(self::STATUS_ERROR)
                      ->setError($e->getMessage())
                      ->save();
            }
        }
    }

    /**
     * @return FileProxyInterface|null
     */
    public function getRenderedFile() {
        $files = $this->getFileRepository()->getChildrenOf('cat_' . self::FILE_CATEGORY_RENDERED_FILE);
        foreach($files->getIterator() as $f)
            return $f;
        return null;
    }

    /**
     * @param string|null $outputFormat
     * @return FileProxyInterface
     * @throws \Exception
     */
    public function render($outputFormat = null) {
        $wkOutputFormat = $outputFormat ?? $this->getOutputFormat();
        $renderer = $this->getRenderer();
        $output = $renderer->getRenderedOutput($wkOutputFormat);
        $output->setName( $this->getOutputName() ?? $this->getPrimaryKey().'.'.$wkOutputFormat);
        return $output;
    }

    /**
     * @return Template
     * @throws \Exception
     */
    public function getRenderer() {
        $template = $this->getTemplateProxy();
        if($renderer = Cool::getInstance()->getFactory()->getTemplateRendererFactory()->getRendererFor($template)) {
            $renderer->setData($this->getDataAsArray());
            return $renderer;
        } else throw new \Exception("no valid renderers found for document {$this->getPrimaryKey()} (template format: {$template->getCompleteExtension()})");
    }

    /**
     * @return array
     */
    public function getDataAsArray() {
        return json_decode($this->getData(), true) ?? [];
    }

    /**
     * @param string|array $v
     * @return QueuedDocument
     */
    public function setData($v) {
        return parent::setData( is_array($v) ? json_encode($v) : $v );
    }

    /**
     * @param FileProxyInterface $overriddenTemplate
     */
    public function setCustomTemplate($overriddenTemplate)
    {
        $this->getFileRepository()->storeFileAt($overriddenTemplate, 'cat_'.self::FILE_CATEGORY_CUSTOM_TEMPLATE);
    }

    /**
     * @return FileProxyInterface|null
     */
    public function getMasterTemplateProxy() {
        return $this->getMasterTemplateRepository()->get( $this->getMasterTemplate() );
    }

    /**
     * @return FileRepositoryInterface|null
     * @throws \Exception
     */
    public function getMasterTemplateRepository() {
        return FileRepositoryFactory::fromId( $this->getTemplateRepositoryId() );
    }

    /**
     * @return FileProxyInterface|null
     */
    public function getCustomTemplateProxy() {
        $prev = $this->getFileRepository()->getChildrenOf('cat_' . self::FILE_CATEGORY_CUSTOM_TEMPLATE, false);
        foreach($prev->getIterator() as $f)
            return $f;
        return null;
    }

    /**
     * @return FileProxyInterface|null
     */
    public function getTemplateProxy() {
        return $this->getCustomTemplateProxy() ?? $this->getMasterTemplateProxy();
    }

    /**
     * @param string $context
     * @return array
     */
    protected function getSnippetContext($context)
    {
        $data = $this->getDataAsArray();
        $baseContext = $data[$context] ?? $data;
        return array_merge($baseContext, [
            'document' => $this
        ]);
    }
}
