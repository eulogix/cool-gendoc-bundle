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

use Eulogix\Cool\Gendoc\Lib\DataSource\QueuedDocumentDataSource;
use Eulogix\Cool\Lib\Lister\Lister;

class AsyncJobLister extends Lister {

    public function __construct($parameters = [])
    {
        parent::__construct($parameters);
        $ds = new QueuedDocumentDataSource();
        $this->setDataSource( $ds->build() );
    }

    public function getId() {
        return "COOL_GENDOC_QUEUED_DOCUMENT_LISTER";
    }

}