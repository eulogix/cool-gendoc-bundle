<?php

/*
 * This file is part of the Eulogix\Cool package.
 *
 * (c) Eulogix <http://www.eulogix.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Eulogix\Cool\Gendoc\Lib;

use Eulogix\Cool\Lib\DataSource\DataSourceInterface;
use Eulogix\Lib\File\Proxy\FileProxyCollectionInterface;
use Eulogix\Lib\File\Proxy\FileProxyInterface;

/**
 * @author Pietro Baricco <pietro@eulogix.com>
 */

abstract class Campaign
{
    /**
     * @var DataSourceInterface
     */
    protected $dataSource = null;

    /**
     * @param DataSourceInterface $dataSource
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * provides the DataSource used to interpret the ids and provide variables to the templates
     * @return DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    abstract public function getType() : string;

    abstract public function getDescription() : string;

    /**
     * provides a collection of templates
     * @return FileProxyCollectionInterface
     */
    abstract public function getTemplates(): FileProxyCollectionInterface;

    /**
     * retrieves one template by id
     * @param string $id
     * @return FileProxyInterface
     */
    abstract public function getTemplate(string $id): FileProxyInterface;

    /**
     * return true to allow the user to create a template on the fly
     * @return bool
     */
    abstract public function onTheFlyTemplateAvailable() : bool;

    /**
     * returns the code snippets which can be applied to the generated entries
     * @return int[]
     */
    abstract public function getAvailableGenerationSnippetIds(): array;

    /**
     * affects the generated documents in the job
     * @return string
     */
    abstract public function getOutputFormat(): string;

}