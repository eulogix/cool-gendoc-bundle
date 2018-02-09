<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\om;

use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\Account;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippet;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\Database\Propel\CoolPropelObject;

abstract class BaseDocumentJob extends CoolPropelObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\DocumentJobPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DocumentJobPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the document_job_id field.
     * @var        int
     */
    protected $document_job_id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the data field.
     * @var        string
     */
    protected $data;

    /**
     * The value for the start_code_snippet_id field.
     * @var        int
     */
    protected $start_code_snippet_id;

    /**
     * The value for the finish_code_snippet_id field.
     * @var        int
     */
    protected $finish_code_snippet_id;

    /**
     * The value for the ext field.
     * @var        string
     */
    protected $ext;

    /**
     * The value for the creation_date field.
     * @var        string
     */
    protected $creation_date;

    /**
     * The value for the update_date field.
     * @var        string
     */
    protected $update_date;

    /**
     * The value for the creation_user_id field.
     * @var        int
     */
    protected $creation_user_id;

    /**
     * The value for the update_user_id field.
     * @var        int
     */
    protected $update_user_id;

    /**
     * The value for the record_version field.
     * @var        int
     */
    protected $record_version;

    /**
     * @var        CodeSnippet
     */
    protected $aCodeSnippetRelatedByStartCodeSnippetId;

    /**
     * @var        CodeSnippet
     */
    protected $aCodeSnippetRelatedByFinishCodeSnippetId;

    /**
     * @var        Account
     */
    protected $aAccountRelatedByCreationUserId;

    /**
     * @var        Account
     */
    protected $aAccountRelatedByUpdateUserId;

    /**
     * @var        PropelObjectCollection|QueuedDocument[] Collection to store aggregation of QueuedDocument objects.
     */
    protected $collQueuedDocuments;
    protected $collQueuedDocumentsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $queuedDocumentsScheduledForDeletion = null;

    /**
     * Get the [document_job_id] column value.
     *
     * @return int
     */
    public function getDocumentJobId()
    {

        return $this->document_job_id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {

        return $this->description;
    }

    /**
     * Get the [data] column value.
     *
     * @return string
     */
    public function getData()
    {

        return $this->data;
    }

    /**
     * Get the [start_code_snippet_id] column value.
     *
     * @return int
     */
    public function getStartCodeSnippetId()
    {

        return $this->start_code_snippet_id;
    }

    /**
     * Get the [finish_code_snippet_id] column value.
     *
     * @return int
     */
    public function getFinishCodeSnippetId()
    {

        return $this->finish_code_snippet_id;
    }

    /**
     * Get the [ext] column value.
     *
     * @return string
     */
    public function getExt()
    {

        return $this->ext;
    }

    /**
     * Get the [optionally formatted] temporal [creation_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreationDate($format = null)
    {
        if ($this->creation_date === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->creation_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->creation_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [update_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdateDate($format = null)
    {
        if ($this->update_date === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->update_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->update_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [creation_user_id] column value.
     *
     * @return int
     */
    public function getCreationUserId()
    {

        return $this->creation_user_id;
    }

    /**
     * Get the [update_user_id] column value.
     *
     * @return int
     */
    public function getUpdateUserId()
    {

        return $this->update_user_id;
    }

    /**
     * Get the [record_version] column value.
     *
     * @return int
     */
    public function getRecordVersion()
    {

        return $this->record_version;
    }

    /**
     * Set the value of [document_job_id] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setDocumentJobId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->document_job_id !== $v) {
            $this->document_job_id = $v;
            $this->modifiedColumns[] = DocumentJobPeer::DOCUMENT_JOB_ID;
        }


        return $this;
    } // setDocumentJobId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DocumentJobPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = DocumentJobPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [data] column.
     *
     * @param  string $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->data !== $v) {
            $this->data = $v;
            $this->modifiedColumns[] = DocumentJobPeer::DATA;
        }


        return $this;
    } // setData()

    /**
     * Set the value of [start_code_snippet_id] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setStartCodeSnippetId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->start_code_snippet_id !== $v) {
            $this->start_code_snippet_id = $v;
            $this->modifiedColumns[] = DocumentJobPeer::START_CODE_SNIPPET_ID;
        }

        if ($this->aCodeSnippetRelatedByStartCodeSnippetId !== null && $this->aCodeSnippetRelatedByStartCodeSnippetId->getCodeSnippetId() !== $v) {
            $this->aCodeSnippetRelatedByStartCodeSnippetId = null;
        }


        return $this;
    } // setStartCodeSnippetId()

    /**
     * Set the value of [finish_code_snippet_id] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setFinishCodeSnippetId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->finish_code_snippet_id !== $v) {
            $this->finish_code_snippet_id = $v;
            $this->modifiedColumns[] = DocumentJobPeer::FINISH_CODE_SNIPPET_ID;
        }

        if ($this->aCodeSnippetRelatedByFinishCodeSnippetId !== null && $this->aCodeSnippetRelatedByFinishCodeSnippetId->getCodeSnippetId() !== $v) {
            $this->aCodeSnippetRelatedByFinishCodeSnippetId = null;
        }


        return $this;
    } // setFinishCodeSnippetId()

    /**
     * Set the value of [ext] column.
     *
     * @param  string $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setExt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ext !== $v) {
            $this->ext = $v;
            $this->modifiedColumns[] = DocumentJobPeer::EXT;
        }


        return $this;
    } // setExt()

    /**
     * Sets the value of [creation_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setCreationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->creation_date !== null || $dt !== null) {
            $currentDateAsString = ($this->creation_date !== null && $tmpDt = new DateTime($this->creation_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->creation_date = $newDateAsString;
                $this->modifiedColumns[] = DocumentJobPeer::CREATION_DATE;
            }
        } // if either are not null


        return $this;
    } // setCreationDate()

    /**
     * Sets the value of [update_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setUpdateDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->update_date !== null || $dt !== null) {
            $currentDateAsString = ($this->update_date !== null && $tmpDt = new DateTime($this->update_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->update_date = $newDateAsString;
                $this->modifiedColumns[] = DocumentJobPeer::UPDATE_DATE;
            }
        } // if either are not null


        return $this;
    } // setUpdateDate()

    /**
     * Set the value of [creation_user_id] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setCreationUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->creation_user_id !== $v) {
            $this->creation_user_id = $v;
            $this->modifiedColumns[] = DocumentJobPeer::CREATION_USER_ID;
        }

        if ($this->aAccountRelatedByCreationUserId !== null && $this->aAccountRelatedByCreationUserId->getAccountId() !== $v) {
            $this->aAccountRelatedByCreationUserId = null;
        }


        return $this;
    } // setCreationUserId()

    /**
     * Set the value of [update_user_id] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setUpdateUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->update_user_id !== $v) {
            $this->update_user_id = $v;
            $this->modifiedColumns[] = DocumentJobPeer::UPDATE_USER_ID;
        }

        if ($this->aAccountRelatedByUpdateUserId !== null && $this->aAccountRelatedByUpdateUserId->getAccountId() !== $v) {
            $this->aAccountRelatedByUpdateUserId = null;
        }


        return $this;
    } // setUpdateUserId()

    /**
     * Set the value of [record_version] column.
     *
     * @param  int $v new value
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setRecordVersion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->record_version !== $v) {
            $this->record_version = $v;
            $this->modifiedColumns[] = DocumentJobPeer::RECORD_VERSION;
        }


        return $this;
    } // setRecordVersion()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->document_job_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->data = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->start_code_snippet_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->finish_code_snippet_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->ext = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->creation_date = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->update_date = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->creation_user_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->update_user_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->record_version = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = DocumentJobPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DocumentJob object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aCodeSnippetRelatedByStartCodeSnippetId !== null && $this->start_code_snippet_id !== $this->aCodeSnippetRelatedByStartCodeSnippetId->getCodeSnippetId()) {
            $this->aCodeSnippetRelatedByStartCodeSnippetId = null;
        }
        if ($this->aCodeSnippetRelatedByFinishCodeSnippetId !== null && $this->finish_code_snippet_id !== $this->aCodeSnippetRelatedByFinishCodeSnippetId->getCodeSnippetId()) {
            $this->aCodeSnippetRelatedByFinishCodeSnippetId = null;
        }
        if ($this->aAccountRelatedByCreationUserId !== null && $this->creation_user_id !== $this->aAccountRelatedByCreationUserId->getAccountId()) {
            $this->aAccountRelatedByCreationUserId = null;
        }
        if ($this->aAccountRelatedByUpdateUserId !== null && $this->update_user_id !== $this->aAccountRelatedByUpdateUserId->getAccountId()) {
            $this->aAccountRelatedByUpdateUserId = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DocumentJobPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCodeSnippetRelatedByStartCodeSnippetId = null;
            $this->aCodeSnippetRelatedByFinishCodeSnippetId = null;
            $this->aAccountRelatedByCreationUserId = null;
            $this->aAccountRelatedByUpdateUserId = null;
            $this->collQueuedDocuments = null;

        } // if (deep)

        $this->reloadCalculatedFields();

    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DocumentJobQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DocumentJobPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCodeSnippetRelatedByStartCodeSnippetId !== null) {
                if ($this->aCodeSnippetRelatedByStartCodeSnippetId->isModified() || $this->aCodeSnippetRelatedByStartCodeSnippetId->isNew()) {
                    $affectedRows += $this->aCodeSnippetRelatedByStartCodeSnippetId->save($con);
                }
                $this->setCodeSnippetRelatedByStartCodeSnippetId($this->aCodeSnippetRelatedByStartCodeSnippetId);
            }

            if ($this->aCodeSnippetRelatedByFinishCodeSnippetId !== null) {
                if ($this->aCodeSnippetRelatedByFinishCodeSnippetId->isModified() || $this->aCodeSnippetRelatedByFinishCodeSnippetId->isNew()) {
                    $affectedRows += $this->aCodeSnippetRelatedByFinishCodeSnippetId->save($con);
                }
                $this->setCodeSnippetRelatedByFinishCodeSnippetId($this->aCodeSnippetRelatedByFinishCodeSnippetId);
            }

            if ($this->aAccountRelatedByCreationUserId !== null) {
                if ($this->aAccountRelatedByCreationUserId->isModified() || $this->aAccountRelatedByCreationUserId->isNew()) {
                    $affectedRows += $this->aAccountRelatedByCreationUserId->save($con);
                }
                $this->setAccountRelatedByCreationUserId($this->aAccountRelatedByCreationUserId);
            }

            if ($this->aAccountRelatedByUpdateUserId !== null) {
                if ($this->aAccountRelatedByUpdateUserId->isModified() || $this->aAccountRelatedByUpdateUserId->isNew()) {
                    $affectedRows += $this->aAccountRelatedByUpdateUserId->save($con);
                }
                $this->setAccountRelatedByUpdateUserId($this->aAccountRelatedByUpdateUserId);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->queuedDocumentsScheduledForDeletion !== null) {
                if (!$this->queuedDocumentsScheduledForDeletion->isEmpty()) {
                    QueuedDocumentQuery::create()
                        ->filterByPrimaryKeys($this->queuedDocumentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->queuedDocumentsScheduledForDeletion = null;
                }
            }

            if ($this->collQueuedDocuments !== null) {
                foreach ($this->collQueuedDocuments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = DocumentJobPeer::DOCUMENT_JOB_ID;
        if (null !== $this->document_job_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DocumentJobPeer::DOCUMENT_JOB_ID . ')');
        }
        if (null === $this->document_job_id) {
            try {
                $stmt = $con->query("SELECT nextval('gendoc.document_job_document_job_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->document_job_id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DocumentJobPeer::DOCUMENT_JOB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'document_job_id';
        }
        if ($this->isColumnModified(DocumentJobPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(DocumentJobPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(DocumentJobPeer::DATA)) {
            $modifiedColumns[':p' . $index++]  = 'data';
        }
        if ($this->isColumnModified(DocumentJobPeer::START_CODE_SNIPPET_ID)) {
            $modifiedColumns[':p' . $index++]  = 'start_code_snippet_id';
        }
        if ($this->isColumnModified(DocumentJobPeer::FINISH_CODE_SNIPPET_ID)) {
            $modifiedColumns[':p' . $index++]  = 'finish_code_snippet_id';
        }
        if ($this->isColumnModified(DocumentJobPeer::EXT)) {
            $modifiedColumns[':p' . $index++]  = 'ext';
        }
        if ($this->isColumnModified(DocumentJobPeer::CREATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'creation_date';
        }
        if ($this->isColumnModified(DocumentJobPeer::UPDATE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'update_date';
        }
        if ($this->isColumnModified(DocumentJobPeer::CREATION_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'creation_user_id';
        }
        if ($this->isColumnModified(DocumentJobPeer::UPDATE_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'update_user_id';
        }
        if ($this->isColumnModified(DocumentJobPeer::RECORD_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'record_version';
        }

        $sql = sprintf(
            'INSERT INTO gendoc.document_job (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'document_job_id':
                        $stmt->bindValue($identifier, $this->document_job_id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'data':
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_STR);
                        break;
                    case 'start_code_snippet_id':
                        $stmt->bindValue($identifier, $this->start_code_snippet_id, PDO::PARAM_INT);
                        break;
                    case 'finish_code_snippet_id':
                        $stmt->bindValue($identifier, $this->finish_code_snippet_id, PDO::PARAM_INT);
                        break;
                    case 'ext':
                        $stmt->bindValue($identifier, $this->ext, PDO::PARAM_STR);
                        break;
                    case 'creation_date':
                        $stmt->bindValue($identifier, $this->creation_date, PDO::PARAM_STR);
                        break;
                    case 'update_date':
                        $stmt->bindValue($identifier, $this->update_date, PDO::PARAM_STR);
                        break;
                    case 'creation_user_id':
                        $stmt->bindValue($identifier, $this->creation_user_id, PDO::PARAM_INT);
                        break;
                    case 'update_user_id':
                        $stmt->bindValue($identifier, $this->update_user_id, PDO::PARAM_INT);
                        break;
                    case 'record_version':
                        $stmt->bindValue($identifier, $this->record_version, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCodeSnippetRelatedByStartCodeSnippetId !== null) {
                if (!$this->aCodeSnippetRelatedByStartCodeSnippetId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCodeSnippetRelatedByStartCodeSnippetId->getValidationFailures());
                }
            }

            if ($this->aCodeSnippetRelatedByFinishCodeSnippetId !== null) {
                if (!$this->aCodeSnippetRelatedByFinishCodeSnippetId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCodeSnippetRelatedByFinishCodeSnippetId->getValidationFailures());
                }
            }

            if ($this->aAccountRelatedByCreationUserId !== null) {
                if (!$this->aAccountRelatedByCreationUserId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aAccountRelatedByCreationUserId->getValidationFailures());
                }
            }

            if ($this->aAccountRelatedByUpdateUserId !== null) {
                if (!$this->aAccountRelatedByUpdateUserId->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aAccountRelatedByUpdateUserId->getValidationFailures());
                }
            }


            if (($retval = DocumentJobPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collQueuedDocuments !== null) {
                    foreach ($this->collQueuedDocuments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = DocumentJobPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getDocumentJobId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getData();
                break;
            case 4:
                return $this->getStartCodeSnippetId();
                break;
            case 5:
                return $this->getFinishCodeSnippetId();
                break;
            case 6:
                return $this->getExt();
                break;
            case 7:
                return $this->getCreationDate();
                break;
            case 8:
                return $this->getUpdateDate();
                break;
            case 9:
                return $this->getCreationUserId();
                break;
            case 10:
                return $this->getUpdateUserId();
                break;
            case 11:
                return $this->getRecordVersion();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['DocumentJob'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DocumentJob'][$this->getPrimaryKey()] = true;
        $keys = DocumentJobPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDocumentJobId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getData(),
            $keys[4] => $this->getStartCodeSnippetId(),
            $keys[5] => $this->getFinishCodeSnippetId(),
            $keys[6] => $this->getExt(),
            $keys[7] => $this->getCreationDate(),
            $keys[8] => $this->getUpdateDate(),
            $keys[9] => $this->getCreationUserId(),
            $keys[10] => $this->getUpdateUserId(),
            $keys[11] => $this->getRecordVersion(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCodeSnippetRelatedByStartCodeSnippetId) {
                $result['CodeSnippetRelatedByStartCodeSnippetId'] = $this->aCodeSnippetRelatedByStartCodeSnippetId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCodeSnippetRelatedByFinishCodeSnippetId) {
                $result['CodeSnippetRelatedByFinishCodeSnippetId'] = $this->aCodeSnippetRelatedByFinishCodeSnippetId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAccountRelatedByCreationUserId) {
                $result['AccountRelatedByCreationUserId'] = $this->aAccountRelatedByCreationUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAccountRelatedByUpdateUserId) {
                $result['AccountRelatedByUpdateUserId'] = $this->aAccountRelatedByUpdateUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collQueuedDocuments) {
                $result['QueuedDocuments'] = $this->collQueuedDocuments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = DocumentJobPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setDocumentJobId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setData($value);
                break;
            case 4:
                $this->setStartCodeSnippetId($value);
                break;
            case 5:
                $this->setFinishCodeSnippetId($value);
                break;
            case 6:
                $this->setExt($value);
                break;
            case 7:
                $this->setCreationDate($value);
                break;
            case 8:
                $this->setUpdateDate($value);
                break;
            case 9:
                $this->setCreationUserId($value);
                break;
            case 10:
                $this->setUpdateUserId($value);
                break;
            case 11:
                $this->setRecordVersion($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = DocumentJobPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setDocumentJobId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setData($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setStartCodeSnippetId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFinishCodeSnippetId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setExt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreationDate($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdateDate($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setCreationUserId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setUpdateUserId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setRecordVersion($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DocumentJobPeer::DATABASE_NAME);

        if ($this->isColumnModified(DocumentJobPeer::DOCUMENT_JOB_ID)) $criteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, $this->document_job_id);
        if ($this->isColumnModified(DocumentJobPeer::NAME)) $criteria->add(DocumentJobPeer::NAME, $this->name);
        if ($this->isColumnModified(DocumentJobPeer::DESCRIPTION)) $criteria->add(DocumentJobPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(DocumentJobPeer::DATA)) $criteria->add(DocumentJobPeer::DATA, $this->data);
        if ($this->isColumnModified(DocumentJobPeer::START_CODE_SNIPPET_ID)) $criteria->add(DocumentJobPeer::START_CODE_SNIPPET_ID, $this->start_code_snippet_id);
        if ($this->isColumnModified(DocumentJobPeer::FINISH_CODE_SNIPPET_ID)) $criteria->add(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $this->finish_code_snippet_id);
        if ($this->isColumnModified(DocumentJobPeer::EXT)) $criteria->add(DocumentJobPeer::EXT, $this->ext);
        if ($this->isColumnModified(DocumentJobPeer::CREATION_DATE)) $criteria->add(DocumentJobPeer::CREATION_DATE, $this->creation_date);
        if ($this->isColumnModified(DocumentJobPeer::UPDATE_DATE)) $criteria->add(DocumentJobPeer::UPDATE_DATE, $this->update_date);
        if ($this->isColumnModified(DocumentJobPeer::CREATION_USER_ID)) $criteria->add(DocumentJobPeer::CREATION_USER_ID, $this->creation_user_id);
        if ($this->isColumnModified(DocumentJobPeer::UPDATE_USER_ID)) $criteria->add(DocumentJobPeer::UPDATE_USER_ID, $this->update_user_id);
        if ($this->isColumnModified(DocumentJobPeer::RECORD_VERSION)) $criteria->add(DocumentJobPeer::RECORD_VERSION, $this->record_version);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(DocumentJobPeer::DATABASE_NAME);
        $criteria->add(DocumentJobPeer::DOCUMENT_JOB_ID, $this->document_job_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getDocumentJobId();
    }

    /**
     * Generic method to set the primary key (document_job_id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setDocumentJobId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getDocumentJobId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of DocumentJob (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setData($this->getData());
        $copyObj->setStartCodeSnippetId($this->getStartCodeSnippetId());
        $copyObj->setFinishCodeSnippetId($this->getFinishCodeSnippetId());
        $copyObj->setExt($this->getExt());
        $copyObj->setCreationDate($this->getCreationDate());
        $copyObj->setUpdateDate($this->getUpdateDate());
        $copyObj->setCreationUserId($this->getCreationUserId());
        $copyObj->setUpdateUserId($this->getUpdateUserId());
        $copyObj->setRecordVersion($this->getRecordVersion());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getQueuedDocuments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQueuedDocument($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setDocumentJobId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return DocumentJob Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return DocumentJobPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DocumentJobPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a CodeSnippet object.
     *
     * @param                  CodeSnippet $v
     * @return DocumentJob The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCodeSnippetRelatedByStartCodeSnippetId(CodeSnippet $v = null)
    {
        if ($v === null) {
            $this->setStartCodeSnippetId(NULL);
        } else {
            $this->setStartCodeSnippetId($v->getCodeSnippetId());
        }

        $this->aCodeSnippetRelatedByStartCodeSnippetId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CodeSnippet object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentJobRelatedByStartCodeSnippetId($this);
        }


        return $this;
    }


    /**
     * Get the associated CodeSnippet object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CodeSnippet The associated CodeSnippet object.
     * @throws PropelException
     */
    public function getCodeSnippetRelatedByStartCodeSnippetId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCodeSnippetRelatedByStartCodeSnippetId === null && ($this->start_code_snippet_id !== null) && $doQuery) {
            $this->aCodeSnippetRelatedByStartCodeSnippetId = CodeSnippetQuery::create()->findPk($this->start_code_snippet_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCodeSnippetRelatedByStartCodeSnippetId->addDocumentJobsRelatedByStartCodeSnippetId($this);
             */
        }

        return $this->aCodeSnippetRelatedByStartCodeSnippetId;
    }

    /**
     * Declares an association between this object and a CodeSnippet object.
     *
     * @param                  CodeSnippet $v
     * @return DocumentJob The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCodeSnippetRelatedByFinishCodeSnippetId(CodeSnippet $v = null)
    {
        if ($v === null) {
            $this->setFinishCodeSnippetId(NULL);
        } else {
            $this->setFinishCodeSnippetId($v->getCodeSnippetId());
        }

        $this->aCodeSnippetRelatedByFinishCodeSnippetId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the CodeSnippet object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentJobRelatedByFinishCodeSnippetId($this);
        }


        return $this;
    }


    /**
     * Get the associated CodeSnippet object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return CodeSnippet The associated CodeSnippet object.
     * @throws PropelException
     */
    public function getCodeSnippetRelatedByFinishCodeSnippetId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCodeSnippetRelatedByFinishCodeSnippetId === null && ($this->finish_code_snippet_id !== null) && $doQuery) {
            $this->aCodeSnippetRelatedByFinishCodeSnippetId = CodeSnippetQuery::create()->findPk($this->finish_code_snippet_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCodeSnippetRelatedByFinishCodeSnippetId->addDocumentJobsRelatedByFinishCodeSnippetId($this);
             */
        }

        return $this->aCodeSnippetRelatedByFinishCodeSnippetId;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return DocumentJob The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccountRelatedByCreationUserId(Account $v = null)
    {
        if ($v === null) {
            $this->setCreationUserId(NULL);
        } else {
            $this->setCreationUserId($v->getAccountId());
        }

        $this->aAccountRelatedByCreationUserId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentJobRelatedByCreationUserId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getAccountRelatedByCreationUserId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aAccountRelatedByCreationUserId === null && ($this->creation_user_id !== null) && $doQuery) {
            $this->aAccountRelatedByCreationUserId = AccountQuery::create()->findPk($this->creation_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccountRelatedByCreationUserId->addDocumentJobsRelatedByCreationUserId($this);
             */
        }

        return $this->aAccountRelatedByCreationUserId;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return DocumentJob The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccountRelatedByUpdateUserId(Account $v = null)
    {
        if ($v === null) {
            $this->setUpdateUserId(NULL);
        } else {
            $this->setUpdateUserId($v->getAccountId());
        }

        $this->aAccountRelatedByUpdateUserId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addDocumentJobRelatedByUpdateUserId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getAccountRelatedByUpdateUserId(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aAccountRelatedByUpdateUserId === null && ($this->update_user_id !== null) && $doQuery) {
            $this->aAccountRelatedByUpdateUserId = AccountQuery::create()->findPk($this->update_user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccountRelatedByUpdateUserId->addDocumentJobsRelatedByUpdateUserId($this);
             */
        }

        return $this->aAccountRelatedByUpdateUserId;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('QueuedDocument' == $relationName) {
            $this->initQueuedDocuments();
        }
    }

    /**
     * Clears out the collQueuedDocuments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DocumentJob The current object (for fluent API support)
     * @see        addQueuedDocuments()
     */
    public function clearQueuedDocuments()
    {
        $this->collQueuedDocuments = null; // important to set this to null since that means it is uninitialized
        $this->collQueuedDocumentsPartial = null;

        return $this;
    }

    /**
     * reset is the collQueuedDocuments collection loaded partially
     *
     * @return void
     */
    public function resetPartialQueuedDocuments($v = true)
    {
        $this->collQueuedDocumentsPartial = $v;
    }

    /**
     * Initializes the collQueuedDocuments collection.
     *
     * By default this just sets the collQueuedDocuments collection to an empty array (like clearcollQueuedDocuments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQueuedDocuments($overrideExisting = true)
    {
        if (null !== $this->collQueuedDocuments && !$overrideExisting) {
            return;
        }
        $this->collQueuedDocuments = new PropelObjectCollection();
        $this->collQueuedDocuments->setModel('QueuedDocument');
    }

    /**
     * Gets an array of QueuedDocument objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DocumentJob is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|QueuedDocument[] List of QueuedDocument objects
     * @throws PropelException
     */
    public function getQueuedDocuments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collQueuedDocumentsPartial && !$this->isNew();
        if (null === $this->collQueuedDocuments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQueuedDocuments) {
                // return empty collection
                $this->initQueuedDocuments();
            } else {
                $collQueuedDocuments = QueuedDocumentQuery::create(null, $criteria)
                    ->filterByDocumentJob($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collQueuedDocumentsPartial && count($collQueuedDocuments)) {
                      $this->initQueuedDocuments(false);

                      foreach ($collQueuedDocuments as $obj) {
                        if (false == $this->collQueuedDocuments->contains($obj)) {
                          $this->collQueuedDocuments->append($obj);
                        }
                      }

                      $this->collQueuedDocumentsPartial = true;
                    }

                    $collQueuedDocuments->getInternalIterator()->rewind();

                    return $collQueuedDocuments;
                }

                if ($partial && $this->collQueuedDocuments) {
                    foreach ($this->collQueuedDocuments as $obj) {
                        if ($obj->isNew()) {
                            $collQueuedDocuments[] = $obj;
                        }
                    }
                }

                $this->collQueuedDocuments = $collQueuedDocuments;
                $this->collQueuedDocumentsPartial = false;
            }
        }

        return $this->collQueuedDocuments;
    }

    /**
     * Sets a collection of QueuedDocument objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $queuedDocuments A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DocumentJob The current object (for fluent API support)
     */
    public function setQueuedDocuments(PropelCollection $queuedDocuments, PropelPDO $con = null)
    {
        $queuedDocumentsToDelete = $this->getQueuedDocuments(new Criteria(), $con)->diff($queuedDocuments);


        $this->queuedDocumentsScheduledForDeletion = $queuedDocumentsToDelete;

        foreach ($queuedDocumentsToDelete as $queuedDocumentRemoved) {
            $queuedDocumentRemoved->setDocumentJob(null);
        }

        $this->collQueuedDocuments = null;
        foreach ($queuedDocuments as $queuedDocument) {
            $this->addQueuedDocument($queuedDocument);
        }

        $this->collQueuedDocuments = $queuedDocuments;
        $this->collQueuedDocumentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related QueuedDocument objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related QueuedDocument objects.
     * @throws PropelException
     */
    public function countQueuedDocuments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collQueuedDocumentsPartial && !$this->isNew();
        if (null === $this->collQueuedDocuments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQueuedDocuments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQueuedDocuments());
            }
            $query = QueuedDocumentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDocumentJob($this)
                ->count($con);
        }

        return count($this->collQueuedDocuments);
    }

    /**
     * Method called to associate a QueuedDocument object to this object
     * through the QueuedDocument foreign key attribute.
     *
     * @param    QueuedDocument $l QueuedDocument
     * @return DocumentJob The current object (for fluent API support)
     */
    public function addQueuedDocument(QueuedDocument $l)
    {
        if ($this->collQueuedDocuments === null) {
            $this->initQueuedDocuments();
            $this->collQueuedDocumentsPartial = true;
        }

        if (!in_array($l, $this->collQueuedDocuments->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddQueuedDocument($l);

            if ($this->queuedDocumentsScheduledForDeletion and $this->queuedDocumentsScheduledForDeletion->contains($l)) {
                $this->queuedDocumentsScheduledForDeletion->remove($this->queuedDocumentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	QueuedDocument $queuedDocument The queuedDocument object to add.
     */
    protected function doAddQueuedDocument($queuedDocument)
    {
        $this->collQueuedDocuments[]= $queuedDocument;
        $queuedDocument->setDocumentJob($this);
    }

    /**
     * @param	QueuedDocument $queuedDocument The queuedDocument object to remove.
     * @return DocumentJob The current object (for fluent API support)
     */
    public function removeQueuedDocument($queuedDocument)
    {
        if ($this->getQueuedDocuments()->contains($queuedDocument)) {
            $this->collQueuedDocuments->remove($this->collQueuedDocuments->search($queuedDocument));
            if (null === $this->queuedDocumentsScheduledForDeletion) {
                $this->queuedDocumentsScheduledForDeletion = clone $this->collQueuedDocuments;
                $this->queuedDocumentsScheduledForDeletion->clear();
            }
            $this->queuedDocumentsScheduledForDeletion[]= $queuedDocument;
            $queuedDocument->setDocumentJob(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocumentJob is new, it will return
     * an empty collection; or if this DocumentJob has previously
     * been saved, it will retrieve related QueuedDocuments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocumentJob.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|QueuedDocument[] List of QueuedDocument objects
     */
    public function getQueuedDocumentsJoinCodeSnippetRelatedByStartCodeSnippetId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QueuedDocumentQuery::create(null, $criteria);
        $query->joinWith('CodeSnippetRelatedByStartCodeSnippetId', $join_behavior);

        return $this->getQueuedDocuments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocumentJob is new, it will return
     * an empty collection; or if this DocumentJob has previously
     * been saved, it will retrieve related QueuedDocuments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocumentJob.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|QueuedDocument[] List of QueuedDocument objects
     */
    public function getQueuedDocumentsJoinCodeSnippetRelatedByFinishCodeSnippetId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QueuedDocumentQuery::create(null, $criteria);
        $query->joinWith('CodeSnippetRelatedByFinishCodeSnippetId', $join_behavior);

        return $this->getQueuedDocuments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocumentJob is new, it will return
     * an empty collection; or if this DocumentJob has previously
     * been saved, it will retrieve related QueuedDocuments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocumentJob.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|QueuedDocument[] List of QueuedDocument objects
     */
    public function getQueuedDocumentsJoinAccountRelatedByCreationUserId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QueuedDocumentQuery::create(null, $criteria);
        $query->joinWith('AccountRelatedByCreationUserId', $join_behavior);

        return $this->getQueuedDocuments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocumentJob is new, it will return
     * an empty collection; or if this DocumentJob has previously
     * been saved, it will retrieve related QueuedDocuments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocumentJob.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|QueuedDocument[] List of QueuedDocument objects
     */
    public function getQueuedDocumentsJoinAccountRelatedByUpdateUserId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QueuedDocumentQuery::create(null, $criteria);
        $query->joinWith('AccountRelatedByUpdateUserId', $join_behavior);

        return $this->getQueuedDocuments($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->document_job_id = null;
        $this->name = null;
        $this->description = null;
        $this->data = null;
        $this->start_code_snippet_id = null;
        $this->finish_code_snippet_id = null;
        $this->ext = null;
        $this->creation_date = null;
        $this->update_date = null;
        $this->creation_user_id = null;
        $this->update_user_id = null;
        $this->record_version = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collQueuedDocuments) {
                foreach ($this->collQueuedDocuments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCodeSnippetRelatedByStartCodeSnippetId instanceof Persistent) {
              $this->aCodeSnippetRelatedByStartCodeSnippetId->clearAllReferences($deep);
            }
            if ($this->aCodeSnippetRelatedByFinishCodeSnippetId instanceof Persistent) {
              $this->aCodeSnippetRelatedByFinishCodeSnippetId->clearAllReferences($deep);
            }
            if ($this->aAccountRelatedByCreationUserId instanceof Persistent) {
              $this->aAccountRelatedByCreationUserId->clearAllReferences($deep);
            }
            if ($this->aAccountRelatedByUpdateUserId instanceof Persistent) {
              $this->aAccountRelatedByUpdateUserId->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collQueuedDocuments instanceof PropelCollection) {
            $this->collQueuedDocuments->clearIterator();
        }
        $this->collQueuedDocuments = null;
        $this->aCodeSnippetRelatedByStartCodeSnippetId = null;
        $this->aCodeSnippetRelatedByFinishCodeSnippetId = null;
        $this->aAccountRelatedByCreationUserId = null;
        $this->aAccountRelatedByUpdateUserId = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DocumentJobPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
