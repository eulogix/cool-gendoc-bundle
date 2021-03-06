<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\om;

use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountPeer;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\map\QueuedDocumentTableMap;

abstract class BaseQueuedDocumentPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'cool_db';

    /** the table name for this class */
    const TABLE_NAME = 'gendoc.queued_document';

    /** the related Propel class for this table */
    const OM_CLASS = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\QueuedDocument';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\map\\QueuedDocumentTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 25;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 25;

    /** the column name for the queued_document_id field */
    const QUEUED_DOCUMENT_ID = 'gendoc.queued_document.queued_document_id';

    /** the column name for the document_job_id field */
    const DOCUMENT_JOB_ID = 'gendoc.queued_document.document_job_id';

    /** the column name for the status field */
    const STATUS = 'gendoc.queued_document.status';

    /** the column name for the type field */
    const TYPE = 'gendoc.queued_document.type';

    /** the column name for the category field */
    const CATEGORY = 'gendoc.queued_document.category';

    /** the column name for the error field */
    const ERROR = 'gendoc.queued_document.error';

    /** the column name for the description field */
    const DESCRIPTION = 'gendoc.queued_document.description';

    /** the column name for the batch field */
    const BATCH = 'gendoc.queued_document.batch';

    /** the column name for the cluster field */
    const CLUSTER = 'gendoc.queued_document.cluster';

    /** the column name for the template_repository_id field */
    const TEMPLATE_REPOSITORY_ID = 'gendoc.queued_document.template_repository_id';

    /** the column name for the master_template field */
    const MASTER_TEMPLATE = 'gendoc.queued_document.master_template';

    /** the column name for the output_format field */
    const OUTPUT_FORMAT = 'gendoc.queued_document.output_format';

    /** the column name for the output_name field */
    const OUTPUT_NAME = 'gendoc.queued_document.output_name';

    /** the column name for the data field */
    const DATA = 'gendoc.queued_document.data';

    /** the column name for the overrideable_flag field */
    const OVERRIDEABLE_FLAG = 'gendoc.queued_document.overrideable_flag';

    /** the column name for the generation_date field */
    const GENERATION_DATE = 'gendoc.queued_document.generation_date';

    /** the column name for the attributes field */
    const ATTRIBUTES = 'gendoc.queued_document.attributes';

    /** the column name for the start_code_snippet_id field */
    const START_CODE_SNIPPET_ID = 'gendoc.queued_document.start_code_snippet_id';

    /** the column name for the finish_code_snippet_id field */
    const FINISH_CODE_SNIPPET_ID = 'gendoc.queued_document.finish_code_snippet_id';

    /** the column name for the ext field */
    const EXT = 'gendoc.queued_document.ext';

    /** the column name for the creation_date field */
    const CREATION_DATE = 'gendoc.queued_document.creation_date';

    /** the column name for the update_date field */
    const UPDATE_DATE = 'gendoc.queued_document.update_date';

    /** the column name for the creation_user_id field */
    const CREATION_USER_ID = 'gendoc.queued_document.creation_user_id';

    /** the column name for the update_user_id field */
    const UPDATE_USER_ID = 'gendoc.queued_document.update_user_id';

    /** the column name for the record_version field */
    const RECORD_VERSION = 'gendoc.queued_document.record_version';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of QueuedDocument objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array QueuedDocument[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. QueuedDocumentPeer::$fieldNames[QueuedDocumentPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('QueuedDocumentId', 'DocumentJobId', 'Status', 'Type', 'Category', 'Error', 'Description', 'Batch', 'Cluster', 'TemplateRepositoryId', 'MasterTemplate', 'OutputFormat', 'OutputName', 'Data', 'OverrideableFlag', 'GenerationDate', 'Attributes', 'StartCodeSnippetId', 'FinishCodeSnippetId', 'Ext', 'CreationDate', 'UpdateDate', 'CreationUserId', 'UpdateUserId', 'RecordVersion', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('queuedDocumentId', 'documentJobId', 'status', 'type', 'category', 'error', 'description', 'batch', 'cluster', 'templateRepositoryId', 'masterTemplate', 'outputFormat', 'outputName', 'data', 'overrideableFlag', 'generationDate', 'attributes', 'startCodeSnippetId', 'finishCodeSnippetId', 'ext', 'creationDate', 'updateDate', 'creationUserId', 'updateUserId', 'recordVersion', ),
        BasePeer::TYPE_COLNAME => array (QueuedDocumentPeer::QUEUED_DOCUMENT_ID, QueuedDocumentPeer::DOCUMENT_JOB_ID, QueuedDocumentPeer::STATUS, QueuedDocumentPeer::TYPE, QueuedDocumentPeer::CATEGORY, QueuedDocumentPeer::ERROR, QueuedDocumentPeer::DESCRIPTION, QueuedDocumentPeer::BATCH, QueuedDocumentPeer::CLUSTER, QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID, QueuedDocumentPeer::MASTER_TEMPLATE, QueuedDocumentPeer::OUTPUT_FORMAT, QueuedDocumentPeer::OUTPUT_NAME, QueuedDocumentPeer::DATA, QueuedDocumentPeer::OVERRIDEABLE_FLAG, QueuedDocumentPeer::GENERATION_DATE, QueuedDocumentPeer::ATTRIBUTES, QueuedDocumentPeer::START_CODE_SNIPPET_ID, QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, QueuedDocumentPeer::EXT, QueuedDocumentPeer::CREATION_DATE, QueuedDocumentPeer::UPDATE_DATE, QueuedDocumentPeer::CREATION_USER_ID, QueuedDocumentPeer::UPDATE_USER_ID, QueuedDocumentPeer::RECORD_VERSION, ),
        BasePeer::TYPE_RAW_COLNAME => array ('QUEUED_DOCUMENT_ID', 'DOCUMENT_JOB_ID', 'STATUS', 'TYPE', 'CATEGORY', 'ERROR', 'DESCRIPTION', 'BATCH', 'CLUSTER', 'TEMPLATE_REPOSITORY_ID', 'MASTER_TEMPLATE', 'OUTPUT_FORMAT', 'OUTPUT_NAME', 'DATA', 'OVERRIDEABLE_FLAG', 'GENERATION_DATE', 'ATTRIBUTES', 'START_CODE_SNIPPET_ID', 'FINISH_CODE_SNIPPET_ID', 'EXT', 'CREATION_DATE', 'UPDATE_DATE', 'CREATION_USER_ID', 'UPDATE_USER_ID', 'RECORD_VERSION', ),
        BasePeer::TYPE_FIELDNAME => array ('queued_document_id', 'document_job_id', 'status', 'type', 'category', 'error', 'description', 'batch', 'cluster', 'template_repository_id', 'master_template', 'output_format', 'output_name', 'data', 'overrideable_flag', 'generation_date', 'attributes', 'start_code_snippet_id', 'finish_code_snippet_id', 'ext', 'creation_date', 'update_date', 'creation_user_id', 'update_user_id', 'record_version', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. QueuedDocumentPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('QueuedDocumentId' => 0, 'DocumentJobId' => 1, 'Status' => 2, 'Type' => 3, 'Category' => 4, 'Error' => 5, 'Description' => 6, 'Batch' => 7, 'Cluster' => 8, 'TemplateRepositoryId' => 9, 'MasterTemplate' => 10, 'OutputFormat' => 11, 'OutputName' => 12, 'Data' => 13, 'OverrideableFlag' => 14, 'GenerationDate' => 15, 'Attributes' => 16, 'StartCodeSnippetId' => 17, 'FinishCodeSnippetId' => 18, 'Ext' => 19, 'CreationDate' => 20, 'UpdateDate' => 21, 'CreationUserId' => 22, 'UpdateUserId' => 23, 'RecordVersion' => 24, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('queuedDocumentId' => 0, 'documentJobId' => 1, 'status' => 2, 'type' => 3, 'category' => 4, 'error' => 5, 'description' => 6, 'batch' => 7, 'cluster' => 8, 'templateRepositoryId' => 9, 'masterTemplate' => 10, 'outputFormat' => 11, 'outputName' => 12, 'data' => 13, 'overrideableFlag' => 14, 'generationDate' => 15, 'attributes' => 16, 'startCodeSnippetId' => 17, 'finishCodeSnippetId' => 18, 'ext' => 19, 'creationDate' => 20, 'updateDate' => 21, 'creationUserId' => 22, 'updateUserId' => 23, 'recordVersion' => 24, ),
        BasePeer::TYPE_COLNAME => array (QueuedDocumentPeer::QUEUED_DOCUMENT_ID => 0, QueuedDocumentPeer::DOCUMENT_JOB_ID => 1, QueuedDocumentPeer::STATUS => 2, QueuedDocumentPeer::TYPE => 3, QueuedDocumentPeer::CATEGORY => 4, QueuedDocumentPeer::ERROR => 5, QueuedDocumentPeer::DESCRIPTION => 6, QueuedDocumentPeer::BATCH => 7, QueuedDocumentPeer::CLUSTER => 8, QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID => 9, QueuedDocumentPeer::MASTER_TEMPLATE => 10, QueuedDocumentPeer::OUTPUT_FORMAT => 11, QueuedDocumentPeer::OUTPUT_NAME => 12, QueuedDocumentPeer::DATA => 13, QueuedDocumentPeer::OVERRIDEABLE_FLAG => 14, QueuedDocumentPeer::GENERATION_DATE => 15, QueuedDocumentPeer::ATTRIBUTES => 16, QueuedDocumentPeer::START_CODE_SNIPPET_ID => 17, QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID => 18, QueuedDocumentPeer::EXT => 19, QueuedDocumentPeer::CREATION_DATE => 20, QueuedDocumentPeer::UPDATE_DATE => 21, QueuedDocumentPeer::CREATION_USER_ID => 22, QueuedDocumentPeer::UPDATE_USER_ID => 23, QueuedDocumentPeer::RECORD_VERSION => 24, ),
        BasePeer::TYPE_RAW_COLNAME => array ('QUEUED_DOCUMENT_ID' => 0, 'DOCUMENT_JOB_ID' => 1, 'STATUS' => 2, 'TYPE' => 3, 'CATEGORY' => 4, 'ERROR' => 5, 'DESCRIPTION' => 6, 'BATCH' => 7, 'CLUSTER' => 8, 'TEMPLATE_REPOSITORY_ID' => 9, 'MASTER_TEMPLATE' => 10, 'OUTPUT_FORMAT' => 11, 'OUTPUT_NAME' => 12, 'DATA' => 13, 'OVERRIDEABLE_FLAG' => 14, 'GENERATION_DATE' => 15, 'ATTRIBUTES' => 16, 'START_CODE_SNIPPET_ID' => 17, 'FINISH_CODE_SNIPPET_ID' => 18, 'EXT' => 19, 'CREATION_DATE' => 20, 'UPDATE_DATE' => 21, 'CREATION_USER_ID' => 22, 'UPDATE_USER_ID' => 23, 'RECORD_VERSION' => 24, ),
        BasePeer::TYPE_FIELDNAME => array ('queued_document_id' => 0, 'document_job_id' => 1, 'status' => 2, 'type' => 3, 'category' => 4, 'error' => 5, 'description' => 6, 'batch' => 7, 'cluster' => 8, 'template_repository_id' => 9, 'master_template' => 10, 'output_format' => 11, 'output_name' => 12, 'data' => 13, 'overrideable_flag' => 14, 'generation_date' => 15, 'attributes' => 16, 'start_code_snippet_id' => 17, 'finish_code_snippet_id' => 18, 'ext' => 19, 'creation_date' => 20, 'update_date' => 21, 'creation_user_id' => 22, 'update_user_id' => 23, 'record_version' => 24, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = QueuedDocumentPeer::getFieldNames($toType);
        $key = isset(QueuedDocumentPeer::$fieldKeys[$fromType][$name]) ? QueuedDocumentPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(QueuedDocumentPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, QueuedDocumentPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return QueuedDocumentPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. QueuedDocumentPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(QueuedDocumentPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(QueuedDocumentPeer::QUEUED_DOCUMENT_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::DOCUMENT_JOB_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::STATUS);
            $criteria->addSelectColumn(QueuedDocumentPeer::TYPE);
            $criteria->addSelectColumn(QueuedDocumentPeer::CATEGORY);
            $criteria->addSelectColumn(QueuedDocumentPeer::ERROR);
            $criteria->addSelectColumn(QueuedDocumentPeer::DESCRIPTION);
            $criteria->addSelectColumn(QueuedDocumentPeer::BATCH);
            $criteria->addSelectColumn(QueuedDocumentPeer::CLUSTER);
            $criteria->addSelectColumn(QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::MASTER_TEMPLATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::OUTPUT_FORMAT);
            $criteria->addSelectColumn(QueuedDocumentPeer::OUTPUT_NAME);
            $criteria->addSelectColumn(QueuedDocumentPeer::DATA);
            $criteria->addSelectColumn(QueuedDocumentPeer::OVERRIDEABLE_FLAG);
            $criteria->addSelectColumn(QueuedDocumentPeer::GENERATION_DATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::ATTRIBUTES);
            $criteria->addSelectColumn(QueuedDocumentPeer::START_CODE_SNIPPET_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::EXT);
            $criteria->addSelectColumn(QueuedDocumentPeer::CREATION_DATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::UPDATE_DATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::CREATION_USER_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::UPDATE_USER_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::RECORD_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.queued_document_id');
            $criteria->addSelectColumn($alias . '.document_job_id');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.category');
            $criteria->addSelectColumn($alias . '.error');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.batch');
            $criteria->addSelectColumn($alias . '.cluster');
            $criteria->addSelectColumn($alias . '.template_repository_id');
            $criteria->addSelectColumn($alias . '.master_template');
            $criteria->addSelectColumn($alias . '.output_format');
            $criteria->addSelectColumn($alias . '.output_name');
            $criteria->addSelectColumn($alias . '.data');
            $criteria->addSelectColumn($alias . '.overrideable_flag');
            $criteria->addSelectColumn($alias . '.generation_date');
            $criteria->addSelectColumn($alias . '.attributes');
            $criteria->addSelectColumn($alias . '.start_code_snippet_id');
            $criteria->addSelectColumn($alias . '.finish_code_snippet_id');
            $criteria->addSelectColumn($alias . '.ext');
            $criteria->addSelectColumn($alias . '.creation_date');
            $criteria->addSelectColumn($alias . '.update_date');
            $criteria->addSelectColumn($alias . '.creation_user_id');
            $criteria->addSelectColumn($alias . '.update_user_id');
            $criteria->addSelectColumn($alias . '.record_version');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return QueuedDocument
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = QueuedDocumentPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return QueuedDocumentPeer::populateObjects(QueuedDocumentPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param QueuedDocument $obj A QueuedDocument object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getQueuedDocumentId();
            } // if key === null
            QueuedDocumentPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A QueuedDocument object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof QueuedDocument) {
                $key = (string) $value->getQueuedDocumentId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or QueuedDocument object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(QueuedDocumentPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return QueuedDocument Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(QueuedDocumentPeer::$instances[$key])) {
                return QueuedDocumentPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (QueuedDocumentPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        QueuedDocumentPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to gendoc.queued_document
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = QueuedDocumentPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = QueuedDocumentPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                QueuedDocumentPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (QueuedDocument object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = QueuedDocumentPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = QueuedDocumentPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            QueuedDocumentPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related CodeSnippetRelatedByStartCodeSnippetId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCodeSnippetRelatedByStartCodeSnippetId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CodeSnippetRelatedByFinishCodeSnippetId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCodeSnippetRelatedByFinishCodeSnippetId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DocumentJob table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDocumentJob(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AccountRelatedByCreationUserId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAccountRelatedByCreationUserId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AccountRelatedByUpdateUserId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAccountRelatedByUpdateUserId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with their CodeSnippet objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCodeSnippetRelatedByStartCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        CodeSnippetPeer::addSelectColumns($criteria);

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (QueuedDocument) to $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByStartCodeSnippetId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with their CodeSnippet objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCodeSnippetRelatedByFinishCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        CodeSnippetPeer::addSelectColumns($criteria);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (QueuedDocument) to $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByFinishCodeSnippetId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with their DocumentJob objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDocumentJob(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        DocumentJobPeer::addSelectColumns($criteria);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DocumentJobPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DocumentJobPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DocumentJobPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (QueuedDocument) to $obj2 (DocumentJob)
                $obj2->addQueuedDocument($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAccountRelatedByCreationUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AccountPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (QueuedDocument) to $obj2 (Account)
                $obj2->addQueuedDocumentRelatedByCreationUserId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAccountRelatedByUpdateUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AccountPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (QueuedDocument) to $obj2 (Account)
                $obj2->addQueuedDocumentRelatedByUpdateUserId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined CodeSnippet rows

            $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByStartCodeSnippetId($obj1);
            } // if joined row not null

            // Add objects for joined CodeSnippet rows

            $key3 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = CodeSnippetPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = CodeSnippetPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CodeSnippetPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (CodeSnippet)
                $obj3->addQueuedDocumentRelatedByFinishCodeSnippetId($obj1);
            } // if joined row not null

            // Add objects for joined DocumentJob rows

            $key4 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = DocumentJobPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = DocumentJobPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocumentJobPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (DocumentJob)
                $obj4->addQueuedDocument($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key5 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = AccountPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = AccountPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    AccountPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj5 (Account)
                $obj5->addQueuedDocumentRelatedByCreationUserId($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key6 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = AccountPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = AccountPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    AccountPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj6 (Account)
                $obj6->addQueuedDocumentRelatedByUpdateUserId($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CodeSnippetRelatedByStartCodeSnippetId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCodeSnippetRelatedByStartCodeSnippetId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related CodeSnippetRelatedByFinishCodeSnippetId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCodeSnippetRelatedByFinishCodeSnippetId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related DocumentJob table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDocumentJob(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AccountRelatedByCreationUserId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptAccountRelatedByCreationUserId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AccountRelatedByUpdateUserId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptAccountRelatedByUpdateUserId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            QueuedDocumentPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects except CodeSnippetRelatedByStartCodeSnippetId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCodeSnippetRelatedByStartCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined DocumentJob rows

                $key2 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = DocumentJobPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = DocumentJobPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    DocumentJobPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (DocumentJob)
                $obj2->addQueuedDocument($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AccountPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (Account)
                $obj3->addQueuedDocumentRelatedByCreationUserId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AccountPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (Account)
                $obj4->addQueuedDocumentRelatedByUpdateUserId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects except CodeSnippetRelatedByFinishCodeSnippetId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCodeSnippetRelatedByFinishCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined DocumentJob rows

                $key2 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = DocumentJobPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = DocumentJobPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    DocumentJobPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (DocumentJob)
                $obj2->addQueuedDocument($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AccountPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (Account)
                $obj3->addQueuedDocumentRelatedByCreationUserId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AccountPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (Account)
                $obj4->addQueuedDocumentRelatedByUpdateUserId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects except DocumentJob.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDocumentJob(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CodeSnippet rows

                $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByStartCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined CodeSnippet rows

                $key3 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CodeSnippetPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CodeSnippetPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (CodeSnippet)
                $obj3->addQueuedDocumentRelatedByFinishCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AccountPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (Account)
                $obj4->addQueuedDocumentRelatedByCreationUserId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key5 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = AccountPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = AccountPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    AccountPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj5 (Account)
                $obj5->addQueuedDocumentRelatedByUpdateUserId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects except AccountRelatedByCreationUserId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptAccountRelatedByCreationUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CodeSnippet rows

                $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByStartCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined CodeSnippet rows

                $key3 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CodeSnippetPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CodeSnippetPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (CodeSnippet)
                $obj3->addQueuedDocumentRelatedByFinishCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined DocumentJob rows

                $key4 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DocumentJobPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DocumentJobPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocumentJobPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (DocumentJob)
                $obj4->addQueuedDocument($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of QueuedDocument objects pre-filled with all related objects except AccountRelatedByUpdateUserId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of QueuedDocument objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptAccountRelatedByUpdateUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);
        }

        QueuedDocumentPeer::addSelectColumns($criteria);
        $startcol2 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(QueuedDocumentPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(QueuedDocumentPeer::DOCUMENT_JOB_ID, DocumentJobPeer::DOCUMENT_JOB_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = QueuedDocumentPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = QueuedDocumentPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = QueuedDocumentPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                QueuedDocumentPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined CodeSnippet rows

                $key2 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CodeSnippetPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CodeSnippetPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (CodeSnippet)
                $obj2->addQueuedDocumentRelatedByStartCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined CodeSnippet rows

                $key3 = CodeSnippetPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = CodeSnippetPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = CodeSnippetPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    CodeSnippetPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (CodeSnippet)
                $obj3->addQueuedDocumentRelatedByFinishCodeSnippetId($obj1);

            } // if joined row is not null

                // Add objects for joined DocumentJob rows

                $key4 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DocumentJobPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DocumentJobPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocumentJobPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj4 (DocumentJob)
                $obj4->addQueuedDocument($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(QueuedDocumentPeer::DATABASE_NAME)->getTable(QueuedDocumentPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseQueuedDocumentPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseQueuedDocumentPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Eulogix\Cool\Gendoc\Bundle\Model\map\QueuedDocumentTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return QueuedDocumentPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a QueuedDocument or Criteria object.
     *
     * @param      mixed $values Criteria or QueuedDocument object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from QueuedDocument object
        }

        if ($criteria->containsKey(QueuedDocumentPeer::QUEUED_DOCUMENT_ID) && $criteria->keyContainsValue(QueuedDocumentPeer::QUEUED_DOCUMENT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.QueuedDocumentPeer::QUEUED_DOCUMENT_ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a QueuedDocument or Criteria object.
     *
     * @param      mixed $values Criteria or QueuedDocument object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(QueuedDocumentPeer::QUEUED_DOCUMENT_ID);
            $value = $criteria->remove(QueuedDocumentPeer::QUEUED_DOCUMENT_ID);
            if ($value) {
                $selectCriteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(QueuedDocumentPeer::TABLE_NAME);
            }

        } else { // $values is QueuedDocument object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the gendoc.queued_document table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(QueuedDocumentPeer::TABLE_NAME, $con, QueuedDocumentPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            QueuedDocumentPeer::clearInstancePool();
            QueuedDocumentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a QueuedDocument or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or QueuedDocument object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            QueuedDocumentPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof QueuedDocument) { // it's a model object
            // invalidate the cache for this single object
            QueuedDocumentPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);
            $criteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                QueuedDocumentPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(QueuedDocumentPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            QueuedDocumentPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given QueuedDocument object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param QueuedDocument $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(QueuedDocumentPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(QueuedDocumentPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(QueuedDocumentPeer::DATABASE_NAME, QueuedDocumentPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return QueuedDocument
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = QueuedDocumentPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);
        $criteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $pk);

        $v = QueuedDocumentPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return QueuedDocument[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);
            $criteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $pks, Criteria::IN);
            $objs = QueuedDocumentPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseQueuedDocumentPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseQueuedDocumentPeer::buildTableMap();

