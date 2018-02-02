<?php

namespace Eulogix\Cool\Bundle\GendocBundle\Model\om;

use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountPeer;
use Eulogix\Cool\Bundle\GendocBundle\Model\QueuedDocument;
use Eulogix\Cool\Bundle\GendocBundle\Model\QueuedDocumentPeer;
use Eulogix\Cool\Bundle\GendocBundle\Model\map\QueuedDocumentTableMap;

abstract class BaseQueuedDocumentPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'cool_db';

    /** the table name for this class */
    const TABLE_NAME = 'gendoc.queued_document';

    /** the related Propel class for this table */
    const OM_CLASS = 'Eulogix\\Cool\\Bundle\\GendocBundle\\Model\\QueuedDocument';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Eulogix\\Cool\\Bundle\\GendocBundle\\Model\\map\\QueuedDocumentTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 20;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 20;

    /** the column name for the queued_document_id field */
    const QUEUED_DOCUMENT_ID = 'gendoc.queued_document.queued_document_id';

    /** the column name for the type field */
    const TYPE = 'gendoc.queued_document.type';

    /** the column name for the category field */
    const CATEGORY = 'gendoc.queued_document.category';

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
        BasePeer::TYPE_PHPNAME => array ('QueuedDocumentId', 'Type', 'Category', 'Description', 'Batch', 'Cluster', 'TemplateRepositoryId', 'MasterTemplate', 'OutputFormat', 'OutputName', 'Data', 'OverrideableFlag', 'GenerationDate', 'Attributes', 'Ext', 'CreationDate', 'UpdateDate', 'CreationUserId', 'UpdateUserId', 'RecordVersion', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('queuedDocumentId', 'type', 'category', 'description', 'batch', 'cluster', 'templateRepositoryId', 'masterTemplate', 'outputFormat', 'outputName', 'data', 'overrideableFlag', 'generationDate', 'attributes', 'ext', 'creationDate', 'updateDate', 'creationUserId', 'updateUserId', 'recordVersion', ),
        BasePeer::TYPE_COLNAME => array (QueuedDocumentPeer::QUEUED_DOCUMENT_ID, QueuedDocumentPeer::TYPE, QueuedDocumentPeer::CATEGORY, QueuedDocumentPeer::DESCRIPTION, QueuedDocumentPeer::BATCH, QueuedDocumentPeer::CLUSTER, QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID, QueuedDocumentPeer::MASTER_TEMPLATE, QueuedDocumentPeer::OUTPUT_FORMAT, QueuedDocumentPeer::OUTPUT_NAME, QueuedDocumentPeer::DATA, QueuedDocumentPeer::OVERRIDEABLE_FLAG, QueuedDocumentPeer::GENERATION_DATE, QueuedDocumentPeer::ATTRIBUTES, QueuedDocumentPeer::EXT, QueuedDocumentPeer::CREATION_DATE, QueuedDocumentPeer::UPDATE_DATE, QueuedDocumentPeer::CREATION_USER_ID, QueuedDocumentPeer::UPDATE_USER_ID, QueuedDocumentPeer::RECORD_VERSION, ),
        BasePeer::TYPE_RAW_COLNAME => array ('QUEUED_DOCUMENT_ID', 'TYPE', 'CATEGORY', 'DESCRIPTION', 'BATCH', 'CLUSTER', 'TEMPLATE_REPOSITORY_ID', 'MASTER_TEMPLATE', 'OUTPUT_FORMAT', 'OUTPUT_NAME', 'DATA', 'OVERRIDEABLE_FLAG', 'GENERATION_DATE', 'ATTRIBUTES', 'EXT', 'CREATION_DATE', 'UPDATE_DATE', 'CREATION_USER_ID', 'UPDATE_USER_ID', 'RECORD_VERSION', ),
        BasePeer::TYPE_FIELDNAME => array ('queued_document_id', 'type', 'category', 'description', 'batch', 'cluster', 'template_repository_id', 'master_template', 'output_format', 'output_name', 'data', 'overrideable_flag', 'generation_date', 'attributes', 'ext', 'creation_date', 'update_date', 'creation_user_id', 'update_user_id', 'record_version', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. QueuedDocumentPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('QueuedDocumentId' => 0, 'Type' => 1, 'Category' => 2, 'Description' => 3, 'Batch' => 4, 'Cluster' => 5, 'TemplateRepositoryId' => 6, 'MasterTemplate' => 7, 'OutputFormat' => 8, 'OutputName' => 9, 'Data' => 10, 'OverrideableFlag' => 11, 'GenerationDate' => 12, 'Attributes' => 13, 'Ext' => 14, 'CreationDate' => 15, 'UpdateDate' => 16, 'CreationUserId' => 17, 'UpdateUserId' => 18, 'RecordVersion' => 19, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('queuedDocumentId' => 0, 'type' => 1, 'category' => 2, 'description' => 3, 'batch' => 4, 'cluster' => 5, 'templateRepositoryId' => 6, 'masterTemplate' => 7, 'outputFormat' => 8, 'outputName' => 9, 'data' => 10, 'overrideableFlag' => 11, 'generationDate' => 12, 'attributes' => 13, 'ext' => 14, 'creationDate' => 15, 'updateDate' => 16, 'creationUserId' => 17, 'updateUserId' => 18, 'recordVersion' => 19, ),
        BasePeer::TYPE_COLNAME => array (QueuedDocumentPeer::QUEUED_DOCUMENT_ID => 0, QueuedDocumentPeer::TYPE => 1, QueuedDocumentPeer::CATEGORY => 2, QueuedDocumentPeer::DESCRIPTION => 3, QueuedDocumentPeer::BATCH => 4, QueuedDocumentPeer::CLUSTER => 5, QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID => 6, QueuedDocumentPeer::MASTER_TEMPLATE => 7, QueuedDocumentPeer::OUTPUT_FORMAT => 8, QueuedDocumentPeer::OUTPUT_NAME => 9, QueuedDocumentPeer::DATA => 10, QueuedDocumentPeer::OVERRIDEABLE_FLAG => 11, QueuedDocumentPeer::GENERATION_DATE => 12, QueuedDocumentPeer::ATTRIBUTES => 13, QueuedDocumentPeer::EXT => 14, QueuedDocumentPeer::CREATION_DATE => 15, QueuedDocumentPeer::UPDATE_DATE => 16, QueuedDocumentPeer::CREATION_USER_ID => 17, QueuedDocumentPeer::UPDATE_USER_ID => 18, QueuedDocumentPeer::RECORD_VERSION => 19, ),
        BasePeer::TYPE_RAW_COLNAME => array ('QUEUED_DOCUMENT_ID' => 0, 'TYPE' => 1, 'CATEGORY' => 2, 'DESCRIPTION' => 3, 'BATCH' => 4, 'CLUSTER' => 5, 'TEMPLATE_REPOSITORY_ID' => 6, 'MASTER_TEMPLATE' => 7, 'OUTPUT_FORMAT' => 8, 'OUTPUT_NAME' => 9, 'DATA' => 10, 'OVERRIDEABLE_FLAG' => 11, 'GENERATION_DATE' => 12, 'ATTRIBUTES' => 13, 'EXT' => 14, 'CREATION_DATE' => 15, 'UPDATE_DATE' => 16, 'CREATION_USER_ID' => 17, 'UPDATE_USER_ID' => 18, 'RECORD_VERSION' => 19, ),
        BasePeer::TYPE_FIELDNAME => array ('queued_document_id' => 0, 'type' => 1, 'category' => 2, 'description' => 3, 'batch' => 4, 'cluster' => 5, 'template_repository_id' => 6, 'master_template' => 7, 'output_format' => 8, 'output_name' => 9, 'data' => 10, 'overrideable_flag' => 11, 'generation_date' => 12, 'attributes' => 13, 'ext' => 14, 'creation_date' => 15, 'update_date' => 16, 'creation_user_id' => 17, 'update_user_id' => 18, 'record_version' => 19, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, )
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
            $criteria->addSelectColumn(QueuedDocumentPeer::TYPE);
            $criteria->addSelectColumn(QueuedDocumentPeer::CATEGORY);
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
            $criteria->addSelectColumn(QueuedDocumentPeer::EXT);
            $criteria->addSelectColumn(QueuedDocumentPeer::CREATION_DATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::UPDATE_DATE);
            $criteria->addSelectColumn(QueuedDocumentPeer::CREATION_USER_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::UPDATE_USER_ID);
            $criteria->addSelectColumn(QueuedDocumentPeer::RECORD_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.queued_document_id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.category');
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

        AccountPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

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

            // Add objects for joined Account rows

            $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = AccountPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj2 (Account)
                $obj2->addQueuedDocumentRelatedByCreationUserId($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = AccountPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (QueuedDocument) to the collection in $obj3 (Account)
                $obj3->addQueuedDocumentRelatedByUpdateUserId($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        $dbMap->addTableObject(new \Eulogix\Cool\Bundle\GendocBundle\Model\map\QueuedDocumentTableMap());
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

