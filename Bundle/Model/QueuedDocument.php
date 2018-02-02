<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model;

use Eulogix\Cool\Gendoc\Bundle\Model\om\BaseQueuedDocument;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;
use Eulogix\Cool\Lib\File\FileRepositoryInterface;
use Eulogix\Lib\File\Proxy\FileProxyInterface;

class QueuedDocument extends BaseQueuedDocument
{

    const FILE_CATEGORY_CUSTOM_TEMPLATE = "CUSTOM_TEMPLATE";

    /**
     * @return FileProxyInterface
     * @throws \Exception
     */
    public function render() {
        $template = $this->getTemplateProxy();
        if($renderer = Cool::getInstance()->getFactory()->getTemplateRendererFactory()->getRendererFor($template)) {
            //$renderer->getParameters()->replace([]);
            $renderer->setData($this->getDataAsArray());
            $output = $renderer->getRenderedOutput($this->getOutputFormat());
            return $output;
        } else throw new \Exception("no valid renderers found for template $templatePath");
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
     * @return FileProxyInterface
     */
    public function getMasterTemplateProxy() {
        return $this->getMasterTemplateRepository()->get( $this->getMasterTemplate() );
    }

    /**
     * @return FileRepositoryInterface
     * @throws \Exception
     */
    public function getMasterTemplateRepository() {
        return FileRepositoryFactory::fromId( $this->getTemplateRepositoryId() );
    }

    /**
     * @return FileProxyInterface
     */
    public function getCustomTemplateProxy() {
        $prev = $this->getFileRepository()->getChildrenOf('cat_' . self::FILE_CATEGORY_CUSTOM_TEMPLATE, false);
        foreach($prev->getIterator() as $f)
            return $f;
        return null;
    }

    /**
     * @return FileProxyInterface
     */
    public function getTemplateProxy() {
        return $this->getCustomTemplateProxy() ?? $this->getMasterTemplateProxy();
    }
}
