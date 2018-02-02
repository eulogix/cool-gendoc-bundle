<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Lib\DataSource;

use Eulogix\Cool\Lib\DataSource\CoolCrudDataSource as CD;
use Eulogix\Cool\Lib\DataSource\CoolCrudTableRelation as Rel;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class QueuedDocumentDataSource extends CD {

    public function __construct()
    {
        return parent::__construct('gendoc', [
            CD::PARAM_TABLE_RELATIONS=>[

                Rel::build()
                    ->setTable('queued_document')
                    ->setDeleteFlag(true),

            ]
        ]);
    }
}
