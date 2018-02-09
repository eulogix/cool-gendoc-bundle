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

use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Lib\DataSource\CoolCrudDataSource as CD;
use Eulogix\Cool\Lib\DataSource\CoolCrudTableRelation as Rel;
use Eulogix\Cool\Lib\DataSource\SimpleValueMap;
use Eulogix\Cool\Lib\Translation\Translator;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class DocumentJobDataSource extends CD {

    public function __construct()
    {
        return parent::__construct('gendoc', [
            CD::PARAM_TABLE_RELATIONS=>[

                Rel::build()
                    ->setTable('document_job')
                    ->setAlias('job')
                    ->setDeleteFlag(true),

                Rel::build()
                    ->setView('document_job_calc')
                    ->setAlias('calc')
                    ->setJoinCondition('calc.document_job_id = job.document_job_id')

            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function build($parameters=[])
    {
        parent::build();

        $this->getField('status')->setValueMap(
            new SimpleValueMap([
                DocumentJob::STATUS_NOT_STARTED,
                DocumentJob::STATUS_PENDING,
                DocumentJob::STATUS_PROCESSING,
                DocumentJob::STATUS_FINISHED,
            ], Translator::fromDomain('GENDOC_JOB_STATUS'))
        );

        return $this;
    }
}
