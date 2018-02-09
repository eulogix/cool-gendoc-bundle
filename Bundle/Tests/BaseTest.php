<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Bundle\Tests;

use Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery;
use Eulogix\Cool\Bundle\CoreBundle\Tests\Cases\baseTestCase;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class BaseTest extends baseTestCase
{
    const TEMPLATE_REPOSITORY_ID = 'gendoc_test_templates';
    const TEMPLATE_1 = '/template1.zip';
    const TEMPLATE_2 = '/template2.zip';

    /**
     * tests the basic functionality of document generation and template overriding
     *
     * @throws \Exception
     * @throws \PropelException
     */
    public function testGendoc() {
        $repo = $this->getTemplatesRepository();
        $overriddenTemplate = $repo->get(self::TEMPLATE_2);

        /**
         * @var QueuedDocument[] $docs
         */
        $docs = [];
        for($i=0; $i<5; $i++) {

            $doc = $this->getNewDoc();
            $doc->save();

            if($i%2)
                $doc->setCustomTemplate($overriddenTemplate);

            $docs[ $i ] = $doc;
        }

        foreach($docs as $i => $doc) {
            $renderedFile = $doc->render();

            if($i%2) $this->assertLessThan(5000, $renderedFile->getSize());
            else $this->assertGreaterThan(10000, $renderedFile->getSize());
        }

    }

    /**
     * @throws \Exception
     * @throws \PropelException
     */
    public function testJob() {

        $fileRepository = AccountQuery::create()->findPk(1)->getFileRepository();
        $fileCount = $fileRepository->getChildrenOf("cat_UNCATEGORIZED")->count();

        $notificationSnippet = CodeSnippetQuery::create()
            ->filterByNspace('Eulogix\Cool\Gendoc\Bundle\Resources\snippets\GendocSnippets')
            ->filterByName('jobNotifyStatusToUser')
            ->findOne();

        $copyFileSnippet = CodeSnippetQuery::create()
            ->filterByNspace('Eulogix\Cool\Gendoc\Bundle\Resources\snippets\GendocSnippets')
            ->filterByName('documentAttachGeneratedDocumentToRecord')
            ->findOne();

        $job = new DocumentJob();
        $job->setCodeSnippetRelatedByStartCodeSnippetId($notificationSnippet)
            ->setCodeSnippetRelatedByFinishCodeSnippetId($notificationSnippet)
            ->setData([
                DocumentJob::CONTEXT_START => [
                    'userId' => 1,
                ],
                DocumentJob::CONTEXT_FINISH => [
                    'userId' => 1,
                ]
            ])
            ->save();

        $jobNotificationKey = "Gendoc Job {$job->getPrimaryKey()} status";

        /**
         * @var QueuedDocument[] $docs
         */
        $docs = [];
        for($i=0; $i<5; $i++) {

            $doc = $this->getNewDoc();
            $doc->setDocumentJob($job)
                ->setCodeSnippetRelatedByFinishCodeSnippetId($copyFileSnippet)
                ->setData(array_merge($doc->getDataAsArray(), [
                    QueuedDocument::CONTEXT_FINISH => [
                        'schemaName' => 'core',
                        'table' => 'account',
                        'pk' => 1,
                        'category' => 'UNCATEGORIZED'
                    ]
                ]))
                ->save();

            $this->assertEquals(QueuedDocument::STATUS_PENDING, $doc->getStatus());
            $this->assertNull($doc->getRenderedFile());

            $docs[ $i ] = $doc;
        }

        $this->assertEquals(DocumentJob::STATUS_NOT_STARTED, $job->getStatus());
        $this->assertEquals(0, $this->countNotifications($jobNotificationKey));
        $this->assertEquals($fileCount, $fileRepository->getChildrenOf("cat_UNCATEGORIZED")->count());

        $job->process(new \DateTime(), 2);

        $this->assertEquals(DocumentJob::STATUS_PENDING, $job->getStatus());
        $this->assertEquals(1, $this->countNotifications($jobNotificationKey));
        $this->assertEquals($fileCount+2, $fileRepository->getChildrenOf("cat_UNCATEGORIZED")->count());

        $job->process(new \DateTime(), 20);

        $this->assertEquals(DocumentJob::STATUS_FINISHED, $job->getStatus());
        $this->assertEquals(2, $this->countNotifications($jobNotificationKey));
        $this->assertEquals($fileCount+5, $fileRepository->getChildrenOf("cat_UNCATEGORIZED")->count());
    }

    /**
     * @throws \Exception
     * @throws \PropelException
     */
    public function testJobs() {
        for($i = 0; $i < 10; $i++) {

            $genDate = new \DateTime();

            $job = new DocumentJob();
            $job->save();

            for($j = 0; $j < $i*100; $j ++) {

                $docGenDate = clone $genDate;

                if($i==1 || $j > 700)
                    $docGenDate->add(new \DateInterval('P10D'));

                $doc = $this->getNewDoc();
                $doc->setDocumentJob($job)
                    ->setGenerationDate($docGenDate)
                    ->save();

            }
        }
    }

    /**
     * @return QueuedDocument
     */
    protected function getNewDoc() {
        $doc = new QueuedDocument();
        $doc->setTemplateRepositoryId(self::TEMPLATE_REPOSITORY_ID)
            ->setMasterTemplate(self::TEMPLATE_1)
            ->setOutputFormat('pdf')
            ->setOverrideableFlag(true)
            ->setGenerationDate(new \DateTime())
            ->setData([
                'var1' => rand(0,100),
                'var2' => 'string'.rand(0,100)
            ]);
        return $doc;
    }

    /**
     * @return \Eulogix\Cool\Lib\File\FileRepositoryInterface
     * @throws \Exception
     */
    protected function getTemplatesRepository() {
        return FileRepositoryFactory::fromId(self::TEMPLATE_REPOSITORY_ID);
    }

    protected function countNotifications($substr) {
        return Cool::getInstance()->getCoreSchema()->fetch("select count(*) from core.user_notification where notification LIKE '%' || :s || '%'", [':s'=>$substr]);
    }
}
