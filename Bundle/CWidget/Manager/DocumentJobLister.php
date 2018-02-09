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

use Eulogix\Cool\Gendoc\Lib\DataSource\DocumentJobDataSource;
use Eulogix\Cool\Lib\Lister\Lister;

class DocumentJobLister extends Lister {

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);
        $ds = new DocumentJobDataSource();
        $this->setDataSource( $ds->build() );
    }

    /**
     * @inheritdoc
     */
    public function build() {
        parent::build();
        $this->setUpDefaultColumns();

        $this->attributes->set( self::ATTR_ROW_EDIT_FUNCTION, <<<JS
          function(editorWidgetParameters, rowData) {
                COOL.getDialogManager().openRouteInTabContainer(
                    dijit.byId('GendocTabContainer'),
                    'Job '+ rowData.document_job_id,
                    'GendocQueuedDocumentsManager',
                    { document_job_id : rowData.document_job_id },
                    { uid : 'documents_for_' + rowData.document_job_id }
                );
            }
JS
        );

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return "COOL_GENDOC_DOCUMENT_JOB_LISTER";
    }

    protected function setUpDefaultColumns()
    {
        foreach($this->getColumns() as $c)
            $c->setWidth(100);
        $this->getColumn('completion_percentage')->setUpAsProgressBar();
    }

}