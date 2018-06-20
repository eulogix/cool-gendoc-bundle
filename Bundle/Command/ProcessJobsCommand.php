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

use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\Schema;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\Symfony\Console\CoolCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

class ProcessJobsCommand extends CoolCommand
{
    const NAME = 'cool:gendoc:processjob';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->addOption('job_ids', null, InputOption::VALUE_OPTIONAL, "(optional) the job ids (comma separated) to process. By default, it processes everything")
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, "(optional) processes only up to [limit] documents per cycle, per job")
            ->setDescription('Processes documents from a single job');
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validate($input);

        /** @var Schema $gendoc */
        $gendoc = Cool::getInstance()->getSchema('gendoc');

        //TODO
        $jobIds = $input->getOption('job_ids');
        $limit = $input->getOption('limit') ?? 10;

        $jobsToProcess = $gendoc->getJobsToProcess();
        foreach($jobsToProcess as $job)
            $job->process(new \DateTime(), $limit);

    }

}