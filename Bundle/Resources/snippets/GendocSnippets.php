<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Bundle\Resources\snippets;

use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Lib\Annotation\SnippetMeta;

/**
 * Sample snippets that demonstrate how custom functionality can be linked to document/job generation events
 *
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class GendocSnippets
{
    /**
     * @SnippetMeta(category="gendoc_job", contextIgnore={"job"}, directInvocation="true", description="Notifies a user about the status of a gendoc job")
     *
     * @param DocumentJob $job
     * @param int $accountId The account id that will reveive the notification
     */
    public static function jobNotifyStatusToUser(DocumentJob $job, $accountId)
    {
        $pm = \Eulogix\Cool\Lib\Cool::getInstance()->getFactory()->getPushManager();
        $pm->pushUserNotification(
            \Eulogix\Cool\Bundle\CoreBundle\Model\Core\UserNotification::create(
                $accountId,
                "Gendoc Job {$job->getDocumentJobId()} status: {$job->getStatus()}"
            )
        );
    }

    /**
     * @SnippetMeta(category="gendoc_document", contextIgnore={"document"}, directInvocation="true", description="Attaches the generated document to a record")
     * @param QueuedDocument $document
     * @param string $schemaName
     * @param string|null $actualSchema
     * @param string $table
     * @param string $pk
     * @param string|null $category
     * @param string|null $newFileName
     * @throws \Exception
     */
    public static function documentAttachGeneratedDocumentToRecord(QueuedDocument $document, $schemaName, $actualSchema = null, $table, $pk, $category = null, $newFileName = null)
    {
        $schema = \Eulogix\Cool\Lib\Cool::getInstance()->getSchema($schemaName);
        if($actualSchema)
            $schema->setCurrentSchema($actualSchema);

        if($obj = $schema->getPropelObject($table, $pk)) {
            $proxy = $document->getRenderedFile();
            if($newFileName)
                $proxy->setName($newFileName);
            $obj->getFileRepository()->storeFileAt($proxy, $category ? 'cat_'.$category : null);
        }
    }

}