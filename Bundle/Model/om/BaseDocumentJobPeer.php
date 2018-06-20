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
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\map\DocumentJobTableMap;

abstract class BaseDocumentJobPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'cool_db';

    /** the table name for this class */
    const TABLE_NAME = 'gendoc.document_job';

    /** the related Propel class for this table */
    const OM_CLASS = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\DocumentJob';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\map\\DocumentJobTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 17;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 17;

    /** the column name for the document_job_id field */
    const DOCUMENT_JOB_ID = 'gendoc.document_job.document_job_id';

    /** the column name for the name field */
    const NAME = 'gendoc.document_job.name';

    /** the column name for the description field */
    const DESCRIPTION = 'gendoc.document_job.description';

    /** the column name for the data field */
    const DATA = 'gendoc.document_job.data';

    /** the column name for the documents_per_iteration field */
    const DOCUMENTS_PER_ITERATION = 'gendoc.document_job.documents_per_iteration';

    /** the column name for the minutes_between_iterations field */
    const MINUTES_BETWEEN_ITERATIONS = 'gendoc.document_job.minutes_between_iterations';

    /** the column name for the schedule_weekdays field */
    const SCHEDULE_WEEKDAYS = 'gendoc.document_job.schedule_weekdays';

    /** the column name for the schedule_hours field */
    const SCHEDULE_HOURS = 'gendoc.document_job.schedule_hours';

    /** the column name for the last_iteration_date field */
    const LAST_ITERATION_DATE = 'gendoc.document_job.last_iteration_date';

    /** the column name for the start_code_snippet_id field */
    const START_CODE_SNIPPET_ID = 'gendoc.document_job.start_code_snippet_id';

    /** the column name for the finish_code_snippet_id field */
    const FINISH_CODE_SNIPPET_ID = 'gendoc.document_job.finish_code_snippet_id';

    /** the column name for the ext field */
    const EXT = 'gendoc.document_job.ext';

    /** the column name for the creation_date field */
    const CREATION_DATE = 'gendoc.document_job.creation_date';

    /** the column name for the update_date field */
    const UPDATE_DATE = 'gendoc.document_job.update_date';

    /** the column name for the creation_user_id field */
    const CREATION_USER_ID = 'gendoc.document_job.creation_user_id';

    /** the column name for the update_user_id field */
    const UPDATE_USER_ID = 'gendoc.document_job.update_user_id';

    /** the column name for the record_version field */
    const RECORD_VERSION = 'gendoc.document_job.record_version';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of DocumentJob objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array DocumentJob[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. DocumentJobPeer::$fieldNames[DocumentJobPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('DocumentJobId', 'Name', 'Description', 'Data', 'DocumentsPerIteration', 'MinutesBetweenIterations', 'ScheduleWeekdays', 'ScheduleHours', 'LastIterationDate', 'StartCodeSnippetId', 'FinishCodeSnippetId', 'Ext', 'CreationDate', 'UpdateDate', 'CreationUserId', 'UpdateUserId', 'RecordVersion', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('documentJobId', 'name', 'description', 'data', 'documentsPerIteration', 'minutesBetweenIterations', 'scheduleWeekdays', 'scheduleHours', 'lastIterationDate', 'startCodeSnippetId', 'finishCodeSnippetId', 'ext', 'creationDate', 'updateDate', 'creationUserId', 'updateUserId', 'recordVersion', ),
        BasePeer::TYPE_COLNAME => array (DocumentJobPeer::DOCUMENT_JOB_ID, DocumentJobPeer::NAME, DocumentJobPeer::DESCRIPTION, DocumentJobPeer::DATA, DocumentJobPeer::DOCUMENTS_PER_ITERATION, DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS, DocumentJobPeer::SCHEDULE_WEEKDAYS, DocumentJobPeer::SCHEDULE_HOURS, DocumentJobPeer::LAST_ITERATION_DATE, DocumentJobPeer::START_CODE_SNIPPET_ID, DocumentJobPeer::FINISH_CODE_SNIPPET_ID, DocumentJobPeer::EXT, DocumentJobPeer::CREATION_DATE, DocumentJobPeer::UPDATE_DATE, DocumentJobPeer::CREATION_USER_ID, DocumentJobPeer::UPDATE_USER_ID, DocumentJobPeer::RECORD_VERSION, ),
        BasePeer::TYPE_RAW_COLNAME => array ('DOCUMENT_JOB_ID', 'NAME', 'DESCRIPTION', 'DATA', 'DOCUMENTS_PER_ITERATION', 'MINUTES_BETWEEN_ITERATIONS', 'SCHEDULE_WEEKDAYS', 'SCHEDULE_HOURS', 'LAST_ITERATION_DATE', 'START_CODE_SNIPPET_ID', 'FINISH_CODE_SNIPPET_ID', 'EXT', 'CREATION_DATE', 'UPDATE_DATE', 'CREATION_USER_ID', 'UPDATE_USER_ID', 'RECORD_VERSION', ),
        BasePeer::TYPE_FIELDNAME => array ('document_job_id', 'name', 'description', 'data', 'documents_per_iteration', 'minutes_between_iterations', 'schedule_weekdays', 'schedule_hours', 'last_iteration_date', 'start_code_snippet_id', 'finish_code_snippet_id', 'ext', 'creation_date', 'update_date', 'creation_user_id', 'update_user_id', 'record_version', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. DocumentJobPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('DocumentJobId' => 0, 'Name' => 1, 'Description' => 2, 'Data' => 3, 'DocumentsPerIteration' => 4, 'MinutesBetweenIterations' => 5, 'ScheduleWeekdays' => 6, 'ScheduleHours' => 7, 'LastIterationDate' => 8, 'StartCodeSnippetId' => 9, 'FinishCodeSnippetId' => 10, 'Ext' => 11, 'CreationDate' => 12, 'UpdateDate' => 13, 'CreationUserId' => 14, 'UpdateUserId' => 15, 'RecordVersion' => 16, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('documentJobId' => 0, 'name' => 1, 'description' => 2, 'data' => 3, 'documentsPerIteration' => 4, 'minutesBetweenIterations' => 5, 'scheduleWeekdays' => 6, 'scheduleHours' => 7, 'lastIterationDate' => 8, 'startCodeSnippetId' => 9, 'finishCodeSnippetId' => 10, 'ext' => 11, 'creationDate' => 12, 'updateDate' => 13, 'creationUserId' => 14, 'updateUserId' => 15, 'recordVersion' => 16, ),
        BasePeer::TYPE_COLNAME => array (DocumentJobPeer::DOCUMENT_JOB_ID => 0, DocumentJobPeer::NAME => 1, DocumentJobPeer::DESCRIPTION => 2, DocumentJobPeer::DATA => 3, DocumentJobPeer::DOCUMENTS_PER_ITERATION => 4, DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS => 5, DocumentJobPeer::SCHEDULE_WEEKDAYS => 6, DocumentJobPeer::SCHEDULE_HOURS => 7, DocumentJobPeer::LAST_ITERATION_DATE => 8, DocumentJobPeer::START_CODE_SNIPPET_ID => 9, DocumentJobPeer::FINISH_CODE_SNIPPET_ID => 10, DocumentJobPeer::EXT => 11, DocumentJobPeer::CREATION_DATE => 12, DocumentJobPeer::UPDATE_DATE => 13, DocumentJobPeer::CREATION_USER_ID => 14, DocumentJobPeer::UPDATE_USER_ID => 15, DocumentJobPeer::RECORD_VERSION => 16, ),
        BasePeer::TYPE_RAW_COLNAME => array ('DOCUMENT_JOB_ID' => 0, 'NAME' => 1, 'DESCRIPTION' => 2, 'DATA' => 3, 'DOCUMENTS_PER_ITERATION' => 4, 'MINUTES_BETWEEN_ITERATIONS' => 5, 'SCHEDULE_WEEKDAYS' => 6, 'SCHEDULE_HOURS' => 7, 'LAST_ITERATION_DATE' => 8, 'START_CODE_SNIPPET_ID' => 9, 'FINISH_CODE_SNIPPET_ID' => 10, 'EXT' => 11, 'CREATION_DATE' => 12, 'UPDATE_DATE' => 13, 'CREATION_USER_ID' => 14, 'UPDATE_USER_ID' => 15, 'RECORD_VERSION' => 16, ),
        BasePeer::TYPE_FIELDNAME => array ('document_job_id' => 0, 'name' => 1, 'description' => 2, 'data' => 3, 'documents_per_iteration' => 4, 'minutes_between_iterations' => 5, 'schedule_weekdays' => 6, 'schedule_hours' => 7, 'last_iteration_date' => 8, 'start_code_snippet_id' => 9, 'finish_code_snippet_id' => 10, 'ext' => 11, 'creation_date' => 12, 'update_date' => 13, 'creation_user_id' => 14, 'update_user_id' => 15, 'record_version' => 16, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $toNames = DocumentJobPeer::getFieldNames($toType);
        $key = isset(DocumentJobPeer::$fieldKeys[$fromType][$name]) ? DocumentJobPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(DocumentJobPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, DocumentJobPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return DocumentJobPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. DocumentJobPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(DocumentJobPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(DocumentJobPeer::DOCUMENT_JOB_ID);
            $criteria->addSelectColumn(DocumentJobPeer::NAME);
            $criteria->addSelectColumn(DocumentJobPeer::DESCRIPTION);
            $criteria->addSelectColumn(DocumentJobPeer::DATA);
            $criteria->addSelectColumn(DocumentJobPeer::DOCUMENTS_PER_ITERATION);
            $criteria->addSelectColumn(DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS);
            $criteria->addSelectColumn(DocumentJobPeer::SCHEDULE_WEEKDAYS);
            $criteria->addSelectColumn(DocumentJobPeer::SCHEDULE_HOURS);
            $criteria->addSelectColumn(DocumentJobPeer::LAST_ITERATION_DATE);
            $criteria->addSelectColumn(DocumentJobPeer::START_CODE_SNIPPET_ID);
            $criteria->addSelectColumn(DocumentJobPeer::FINISH_CODE_SNIPPET_ID);
            $criteria->addSelectColumn(DocumentJobPeer::EXT);
            $criteria->addSelectColumn(DocumentJobPeer::CREATION_DATE);
            $criteria->addSelectColumn(DocumentJobPeer::UPDATE_DATE);
            $criteria->addSelectColumn(DocumentJobPeer::CREATION_USER_ID);
            $criteria->addSelectColumn(DocumentJobPeer::UPDATE_USER_ID);
            $criteria->addSelectColumn(DocumentJobPeer::RECORD_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.document_job_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.data');
            $criteria->addSelectColumn($alias . '.documents_per_iteration');
            $criteria->addSelectColumn($alias . '.minutes_between_iterations');
            $criteria->addSelectColumn($alias . '.schedule_weekdays');
            $criteria->addSelectColumn($alias . '.schedule_hours');
            $criteria->addSelectColumn($alias . '.last_iteration_date');
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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return DocumentJob
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = DocumentJobPeer::doSelect($critcopy, $con);
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
        return DocumentJobPeer::populateObjects(DocumentJobPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            DocumentJobPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

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
     * @param DocumentJob $obj A DocumentJob object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getDocumentJobId();
            } // if key === null
            DocumentJobPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A DocumentJob object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof DocumentJob) {
                $key = (string) $value->getDocumentJobId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or DocumentJob object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(DocumentJobPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return DocumentJob Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(DocumentJobPeer::$instances[$key])) {
                return DocumentJobPeer::$instances[$key];
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
        foreach (DocumentJobPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        DocumentJobPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to gendoc.document_job
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in QueuedDocumentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        QueuedDocumentPeer::clearInstancePool();
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
        $cls = DocumentJobPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = DocumentJobPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DocumentJobPeer::addInstanceToPool($obj, $key);
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
     * @return array (DocumentJob object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = DocumentJobPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = DocumentJobPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + DocumentJobPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DocumentJobPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            DocumentJobPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

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
     * Selects a collection of DocumentJob objects pre-filled with their CodeSnippet objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCodeSnippetRelatedByStartCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol = DocumentJobPeer::NUM_HYDRATE_COLUMNS;
        CodeSnippetPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to $obj2 (CodeSnippet)
                $obj2->addDocumentJobRelatedByStartCodeSnippetId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with their CodeSnippet objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCodeSnippetRelatedByFinishCodeSnippetId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol = DocumentJobPeer::NUM_HYDRATE_COLUMNS;
        CodeSnippetPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to $obj2 (CodeSnippet)
                $obj2->addDocumentJobRelatedByFinishCodeSnippetId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAccountRelatedByCreationUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol = DocumentJobPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to $obj2 (Account)
                $obj2->addDocumentJobRelatedByCreationUserId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAccountRelatedByUpdateUserId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol = DocumentJobPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to $obj2 (Account)
                $obj2->addDocumentJobRelatedByUpdateUserId($obj1);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

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
     * Selects a collection of DocumentJob objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol2 = DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to the collection in $obj2 (CodeSnippet)
                $obj2->addDocumentJobRelatedByStartCodeSnippetId($obj1);
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

                // Add the $obj1 (DocumentJob) to the collection in $obj3 (CodeSnippet)
                $obj3->addDocumentJobRelatedByFinishCodeSnippetId($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = AccountPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (DocumentJob) to the collection in $obj4 (Account)
                $obj4->addDocumentJobRelatedByCreationUserId($obj1);
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

                // Add the $obj1 (DocumentJob) to the collection in $obj5 (Account)
                $obj5->addDocumentJobRelatedByUpdateUserId($obj1);
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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

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
        $criteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocumentJobPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

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
     * Selects a collection of DocumentJob objects pre-filled with all related objects except CodeSnippetRelatedByStartCodeSnippetId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
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
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol2 = DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (DocumentJob) to the collection in $obj2 (Account)
                $obj2->addDocumentJobRelatedByCreationUserId($obj1);

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

                // Add the $obj1 (DocumentJob) to the collection in $obj3 (Account)
                $obj3->addDocumentJobRelatedByUpdateUserId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with all related objects except CodeSnippetRelatedByFinishCodeSnippetId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
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
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol2 = DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocumentJobPeer::CREATION_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::UPDATE_USER_ID, AccountPeer::ACCOUNT_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (DocumentJob) to the collection in $obj2 (Account)
                $obj2->addDocumentJobRelatedByCreationUserId($obj1);

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

                // Add the $obj1 (DocumentJob) to the collection in $obj3 (Account)
                $obj3->addDocumentJobRelatedByUpdateUserId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with all related objects except AccountRelatedByCreationUserId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
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
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol2 = DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to the collection in $obj2 (CodeSnippet)
                $obj2->addDocumentJobRelatedByStartCodeSnippetId($obj1);

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

                // Add the $obj1 (DocumentJob) to the collection in $obj3 (CodeSnippet)
                $obj3->addDocumentJobRelatedByFinishCodeSnippetId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocumentJob objects pre-filled with all related objects except AccountRelatedByUpdateUserId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocumentJob objects.
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
            $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);
        }

        DocumentJobPeer::addSelectColumns($criteria);
        $startcol2 = DocumentJobPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        CodeSnippetPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + CodeSnippetPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocumentJobPeer::START_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);

        $criteria->addJoin(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, CodeSnippetPeer::CODE_SNIPPET_ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocumentJobPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocumentJobPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocumentJobPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocumentJobPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (DocumentJob) to the collection in $obj2 (CodeSnippet)
                $obj2->addDocumentJobRelatedByStartCodeSnippetId($obj1);

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

                // Add the $obj1 (DocumentJob) to the collection in $obj3 (CodeSnippet)
                $obj3->addDocumentJobRelatedByFinishCodeSnippetId($obj1);

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
        return Propel::getDatabaseMap(DocumentJobPeer::DATABASE_NAME)->getTable(DocumentJobPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseDocumentJobPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseDocumentJobPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Eulogix\Cool\Gendoc\Bundle\Model\map\DocumentJobTableMap());
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
        return DocumentJobPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a DocumentJob or Criteria object.
     *
     * @param      mixed $values Criteria or DocumentJob object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from DocumentJob object
        }

        if ($criteria->containsKey(DocumentJobPeer::DOCUMENT_JOB_ID) && $criteria->keyContainsValue(DocumentJobPeer::DOCUMENT_JOB_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DocumentJobPeer::DOCUMENT_JOB_ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a DocumentJob or Criteria object.
     *
     * @param      mixed $values Criteria or DocumentJob object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(DocumentJobPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(DocumentJobPeer::DOCUMENT_JOB_ID);
            $value = $criteria->remove(DocumentJobPeer::DOCUMENT_JOB_ID);
            if ($value) {
                $selectCriteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(DocumentJobPeer::TABLE_NAME);
            }

        } else { // $values is DocumentJob object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the gendoc.document_job table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(DocumentJobPeer::TABLE_NAME, $con, DocumentJobPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DocumentJobPeer::clearInstancePool();
            DocumentJobPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a DocumentJob or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or DocumentJob object or primary key or array of primary keys
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
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            DocumentJobPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof DocumentJob) { // it's a model object
            // invalidate the cache for this single object
            DocumentJobPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DocumentJobPeer::DATABASE_NAME);
            $criteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                DocumentJobPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(DocumentJobPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            DocumentJobPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given DocumentJob object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param DocumentJob $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(DocumentJobPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(DocumentJobPeer::TABLE_NAME);

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

        return BasePeer::doValidate(DocumentJobPeer::DATABASE_NAME, DocumentJobPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return DocumentJob
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = DocumentJobPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(DocumentJobPeer::DATABASE_NAME);
        $criteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, $pk);

        $v = DocumentJobPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return DocumentJob[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(DocumentJobPeer::DATABASE_NAME);
            $criteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, $pks, Criteria::IN);
            $objs = DocumentJobPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseDocumentJobPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseDocumentJobPeer::buildTableMap();

