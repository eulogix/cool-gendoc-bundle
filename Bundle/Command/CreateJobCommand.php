<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Bundle\Command;

use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Lib\Campaign;
use Eulogix\Cool\Gendoc\Lib\CampaignProvider;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\DataSource\DataSourceInterface;
use Eulogix\Cool\Lib\DataSource\DSRequest;
use Eulogix\Cool\Lib\File\FileRepositoryFactory;
use Eulogix\Cool\Lib\File\FileUtil;
use Eulogix\Cool\Lib\Symfony\Console\CoolCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class CreateJobCommand extends CoolCommand
{
    const NAME = 'cool:gendoc:createjob';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Asynchronously creates gendoc jobs. Used by the campaign wizard')
            ->addArgument('input_key', InputArgument::REQUIRED, "Shared cacher input key")
            ->setHelp(<<<EOF
EOF
            );
        parent::configure();
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $command = $this;

        $cacher = Cool::getInstance()->getFactory()->getSharedCacher();
        $input = $cacher->fetch($input->getArgument('input_key'));

        if($executionEnvironment = @unserialize($input['executionEnvironment']))
            Cool::getInstance()->restoreExcutionEnvironment($executionEnvironment);

        /** @var CampaignProvider $provider */
        $provider = unserialize($input['campaignProvider']);

        /** @var Campaign $campaign */
        $campaign = unserialize($input['campaign']);

        $formData = $input['formData'];

        $template = FileRepositoryFactory::fromId($formData['tempRepoKey'])->get($formData['temp_template_id']);

        $con = Cool::getInstance()->getSchema('gendoc')->getConnection();

        $con->beginTransaction();

        $job = new DocumentJob();
        $job/*->setCodeSnippetRelatedByStartCodeSnippetId($notificationSnippet)
            /->setCodeSnippetRelatedByFinishCodeSnippetId($notificationSnippet)
            ->setData([
                DocumentJob::CONTEXT_START => [
                    'userId' => 1,
                ],
                DocumentJob::CONTEXT_FINISH => [
                    'userId' => 1,
                ]
            ])*/
            ->setName($formData['job_name'])
            ->setDescription($formData['job_description'])
            ->save();

        $tempRepo = Cool::getInstance()->getFactory()->getTempFileRepository();
        $tempFolder = FileUtil::getTempFolder();
        $parts = mb_pathinfo($tempFolder);
        $tempMasterTemplate = $tempRepo->storeFileAt($template, '/'.$parts['filename']);

        $items = $provider->getItems();
        $ds = $campaign->getDataSource();

        foreach($items as $recordId) {

            $record = $ds->getDSRecord(
                $recordId,
                [],
                function(DSRequest $request) {
                    $request->setIncludeDecodings(true);
                }
             );

            $doc = new QueuedDocument();
            $doc->setDocumentJob($job)
                ->setTemplateRepositoryId('tempFiles')
                ->setMasterTemplate($tempMasterTemplate->getId())
                ->setOutputFormat($campaign->getOutputFormat())
                ->setOverrideableFlag(true)
                ->setGenerationDate(new \DateTime())
                ->setData($record->getValues())
                ->setFinishCodeSnippetId($formData['doc_snippet']);
        }

        $job->save($con);

        $con->commit();
    }

    /**
     * @param $ds
     * @param $recordId
     * @return mixed
     */
    private function getItemData($ds, $recordId)
    {
        $dsr = new DSRequest();

        $dsr->setOperationType($dsr::OPERATION_TYPE_FETCH)
            ->setStartRow(0)
            ->setEndRow(0)
            ->setIncludeDecodings(true)
            ->setParameters([DataSourceInterface::RECORD_IDENTIFIER => $recordId]);

        $response = $this->getCampaign()->getDataSource()->executeFetch($dsr);
        return $response->getDSRecord()->getValues();
    }
}