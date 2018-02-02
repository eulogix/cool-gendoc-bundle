<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\map;

use \RelationMap;


/**
 * This class defines the structure of the 'gendoc.queued_document' table.
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
class QueuedDocumentTableMap extends \Eulogix\Cool\Lib\Database\Propel\CoolTableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.eulogix.cool-gendoc-bundle.Bundle.Model.map.QueuedDocumentTableMap';

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
        $this->setName('gendoc.queued_document');
        $this->setPhpName('QueuedDocument');
        $this->setClassname('Eulogix\\Cool\\Gendoc\\Bundle\\Model\\QueuedDocument');
        $this->setPackage('vendor.eulogix.cool-gendoc-bundle.Bundle.Model');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('gendoc.queued_document_queued_document_id_seq');
        // columns
        $this->addPrimaryKey('queued_document_id', 'QueuedDocumentId', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'LONGVARCHAR', false, null, null);
        $this->addColumn('category', 'Category', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('batch', 'Batch', 'LONGVARCHAR', false, null, null);
        $this->addColumn('cluster', 'Cluster', 'LONGVARCHAR', false, null, null);
        $this->addColumn('template_repository_id', 'TemplateRepositoryId', 'LONGVARCHAR', false, null, null);
        $this->addColumn('master_template', 'MasterTemplate', 'LONGVARCHAR', false, null, null);
        $this->addColumn('output_format', 'OutputFormat', 'LONGVARCHAR', false, null, null);
        $this->addColumn('output_name', 'OutputName', 'LONGVARCHAR', false, null, null);
        $this->addColumn('data', 'Data', 'LONGVARCHAR', false, null, null);
        $this->addColumn('overrideable_flag', 'OverrideableFlag', 'BOOLEAN', false, null, null);
        $this->addColumn('generation_date', 'GenerationDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('attributes', 'Attributes', 'LONGVARCHAR', false, null, null);
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
        $this->addRelation('AccountRelatedByCreationUserId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\Account', RelationMap::MANY_TO_ONE, array('creation_user_id' => 'account_id', ), 'RESTRICT', null);
        $this->addRelation('AccountRelatedByUpdateUserId', 'Eulogix\\Cool\\Bundle\\CoreBundle\\Model\\Core\\Account', RelationMap::MANY_TO_ONE, array('update_user_id' => 'account_id', ), 'RESTRICT', null);
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

} // QueuedDocumentTableMap
