<?php
/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Bundle\CWidget\Manager;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery;
use Eulogix\Cool\Gendoc\Lib\DataSource\QueuedDocumentDataSource;
use Eulogix\Cool\Lib\Lister\Lister;

class QueuedDocumentLister extends Lister {

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);
        $ds = new QueuedDocumentDataSource();
        $this->setDataSource( $ds->build() );
    }

    /**
     * @inheritdoc
     */
    public function build() {
        parent::build();
        $this->setUpDefaultColumns();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return "COOL_GENDOC_QUEUED_DOCUMENT_LISTER";
    }

    protected function setUpDefaultColumns()
    {
        foreach($this->getColumns() as $c)
            $c->setWidth(100);

        $this->setUpDefaultColumn('CUSTOM_TEMPLATE', 200,'
            <A HREF="javascript:dijit.byId(\'{{_listerId}}\').mixAction(\'editTemplate\', {queued_document_id: {{queued_document_id}} })">Edit</A>&nbsp;
            {{#if CUSTOM_TEMPLATE}}
                <A HREF="javascript:dijit.byId(\'{{_listerId}}\').mixAction(\'removeTemplate\', {queued_document_id: {{queued_document_id}} })">Remove</A>&nbsp;
            {{/if}}
            <A HREF="javascript:dijit.byId(\'{{_listerId}}\').mixAction(\'preview\', {queued_document_id: {{queued_document_id}} })">Preview</A>
         ');
    }

    public function onPreview() {
        $id = $this->getRequest()->get('queued_document_id');
        /**
         * @var QueuedDocument $document
         */
        $document = QueuedDocumentQuery::create()->findPk($id);
        $this->previewOrDownloadFile($document->render());
    }

    public function onRemoveTemplate() {
        $id = $this->getRequest()->get('queued_document_id');
        /**
         * @var QueuedDocument $document
         */
        $document = QueuedDocumentQuery::create()->findPk($id);
        if($t = $document->getCustomTemplateProxy())
            $document->getFileRepository()->delete($t->getId());
        $this->reloadRows();
    }

    public function onEditTemplate() {
        $id = $this->getRequest()->get('queued_document_id');

        $frepoParameters = json_encode([
            'repositoryId'  => 'schema',
            'schema'        => 'gendoc',
            'table'         => 'queued_document',
            'pk'            => 'id'
        ]);

        /**
         * @var QueuedDocument $document
         */
        $document = QueuedDocumentQuery::create()->findPk($id);

        if(!$document->getCustomTemplateProxy()) {
            $document->setCustomTemplate($document->getMasterTemplateProxy());
        }

        $filePath = $document->getCustomTemplateProxy()->getId();

        $this->addCommandJs(<<<JS

            var d = COOL.getDialogManager().openWidgetDialog(
                'EulogixCoolCoreBundle/Files/Editor/TwigTemplateEditorForm',
                'Edit twig template',
                {filePath: '{$filePath}', repositoryParameters: '{$frepoParameters}', hideCloseButton:true},
                function(){
                    d.widget.mixAction('cleanup');
                    widget.reloadRows();
                }
            );
JS
        );

    }

}