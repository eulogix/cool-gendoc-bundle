<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\map;

use \RelationMap;


/**
 * This class defines the structure of the 'gendoc.document_job' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.vendor.eulogix.cool-gendoc-bundle.Bundle.Model.map
 */
class DocumentJobTableMap extends \Eulogix\Cool\Lib\Database\Propel\CoolTableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.eulogix.cool-gendoc-bundle.Bundle.Model.map.DocumentJobTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('gendoc.document_job');
        $this->setPhpName('DocumentJob');
        $this->setClassname('Eulogix\\Cool\\Gendoc\\Bundle\\Model\\DocumentJob');
        $this->setPackage('vendor.eulogix.cool-gendoc-bundle.Bundle.Model');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('gendoc.document_job_document_job_id_seq');
        // columns
        $this->addPrimaryKey('document_job_id', 'DocumentJobId', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('data', 'Data', 'LONGVARCHAR', false, null, null);
        $this->addColumn('documents_per_iteration', 'DocumentsPerIteration', 'INTEGER', false, null, null);
        $this->addColumn('minutes_between_iterations', 'MinutesBetweenIterations', 'INTEGER', false, null, null);
        $this->addColumn('schedule_weekdays', 'ScheduleWeekdays', 'LONGVARCHAR', false, null, null);
        $this->addColumn('schedule_hours', 'ScheduleHours', 'LONGVARCHAR', false, null, null);
        $this->addColumn('last_iteration_date', 'LastIterationDate', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('start_code_snippet_id', 'StartCodeSnippetId', 'INTEGER', 'core.code_snippet', 'code_snippet_id', false, null, null);
        $this->addForeignKey('finish_code_snippet_id', 'FinishCodeSnippetId', 'INTEGER', 'core.code_snippet', 'code_snippet_id', false, null, null);
        $this->addColumn('ext', 'Ext', 'LONGVARCHAR', false, null, null);
        $this->addColumn('creation_date', 'CreationDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('update_date', 'UpdateDate', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('creation_user_id', 'CreationUserId', 'INTEGER', 'core.account', 'account_id', false, null, null);
        $this->addForeignKey('update_user_id', 'UpdateUserId', 'INTEGER', 'core.account', 'account_id', false, null, null);
        $this->addColumn('record_version', 'RecordVersion', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CodeSnippetRelatedByStartCodeSnippetId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\CodeSnippet', RelationMap::MANY_TO_ONE, array('start_code_snippet_id' => 'code_snippet_id', ), 'RESTRICT', null);
        $this->addRelation('CodeSnippetRelatedByFinishCodeSnippetId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\CodeSnippet', RelationMap::MANY_TO_ONE, array('finish_code_snippet_id' => 'code_snippet_id', ), 'RESTRICT', null);
        $this->addRelation('AccountRelatedByCreationUserId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\Account', RelationMap::MANY_TO_ONE, array('creation_user_id' => 'account_id', ), 'RESTRICT', null);
        $this->addRelation('AccountRelatedByUpdateUserId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\Account', RelationMap::MANY_TO_ONE, array('update_user_id' => 'account_id', ), 'RESTRICT', null);
        $this->addRelation('QueuedDocument', 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\QueuedDocument', RelationMap::ONE_TO_MANY, array('document_job_id' => 'document_job_id', ), 'CASCADE', null, 'QueuedDocuments');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'extendable' =>  array (
  'container_column' => 'ext',
),
            'auditable' =>  array (
  'create_column' => 'creation_date',
  'created_by_column' => 'creation_user_id',
  'update_column' => 'update_date',
  'updated_by_column' => 'update_user_id',
  'version_column' => 'record_version',
  'target' => 'EulogixCoolGendocBundle/gendoc',
),
        );
    } // getBehaviors()

} // DocumentJobTableMap
