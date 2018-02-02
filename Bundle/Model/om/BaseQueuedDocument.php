<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\om;

use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\Account;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery;
use Eulogix\Cool\Lib\Cool;
use Eulogix\Cool\Lib\Database\Propel\CoolPropelObject;

abstract class BaseQueuedDocument extends CoolPropelObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Eulogix\\Cool\\Bundle\\GendocBundle\\Model\\QueuedDocumentPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        QueuedDocumentPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the queued_document_id field.
     * @var        int
     */
    protected $queued_document_id;

    /**
     * The value for the type field.
     * @var        string
     */
    protected $type;

    /**
     * The value for the category field.
     * @var        string
     */
    protected $category;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the batch field.
     * @var        string
     */
    protected $batch;

    /**
     * The value for the cluster field.
     * @var        string
     */
    protected $cluster;

    /**
     * The value for the template_repository_id field.
     * @var        string
     */
    protected $template_repository_id;

    /**
     * The value for the master_template field.
     * @var        string
     */
    protected $master_template;

    /**
     * The value for the output_format field.
     * @var        string
     */
    protected $output_format;

    /**
     * The value for the output_name field.
     * @var        string
     */
    protected $output_name;

    /**
     * The value for the data field.
     * @var        string
     */
    protected $data;

    /**
     * The value for the overrideable_flag field.
     * @var        boolean
     */
    protected $overrideable_flag;

    /**
     * The value for the generation_date field.
     * @var        string
     */
    protected $generation_date;

    /**
     * The value for the attributes field.
     * @var        string
     */
    protected $attributes;

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
     * @var        Account
     */
    protected $aAccountRelatedByCreationUserId;

    /**
     * @var        Account
     */
    protected $aAccountRelatedByUpdateUserId;

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
     * Get the [queued_document_id] column value.
     *
     * @return int
     */
    public function getQueuedDocumentId()
    {

        return $this->queued_document_id;
    }

    /**
     * Get the [type] column value.
     *
     * @return string
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [category] column value.
     *
     * @return string
     */
    public function getCategory()
    {

        return $this->category;
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
     * Get the [batch] column value.
     * custom identifier used to group documents
     * @return string
     */
    public function getBatch()
    {

        return $this->batch;
    }

    /**
     * Get the [cluster] column value.
     * used to group documents by some attribute
     * @return string
     */
    public function getCluster()
    {

        return $this->cluster;
    }

    /**
     * Get the [template_repository_id] column value.
     * the id of the repository in which the master template is stored
     * @return string
     */
    public function getTemplateRepositoryId()
    {

        return $this->template_repository_id;
    }

    /**
     * Get the [master_template] column value.
     * the name of the master template, will be used if not overridden
     * @return string
     */
    public function getMasterTemplate()
    {

        return $this->master_template;
    }

    /**
     * Get the [output_format] column value.
     *
     * @return string
     */
    public function getOutputFormat()
    {

        return $this->output_format;
    }

    /**
     * Get the [output_name] column value.
     *
     * @return string
     */
    public function getOutputName()
    {

        return $this->output_name;
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
     * Get the [overrideable_flag] column value.
     * if set, the template will be editable/overrideable
     * @return boolean
     */
    public function getOverrideableFlag()
    {

        return $this->overrideable_flag;
    }

    /**
     * Get the [optionally formatted] temporal [generation_date] column value.
     * date in which the document will be finalized
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getGenerationDate($format = null)
    {
        if ($this->generation_date === null) {
            return null;
        }


        try {
            $dt = new DateTime($this->generation_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->generation_date, true), $x);
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
     * Get the [attributes] column value.
     * custom attributes
     * @return string
     */
    public function getAttributes()
    {

        return $this->attributes;
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
     * Set the value of [queued_document_id] column.
     *
     * @param  int $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setQueuedDocumentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->queued_document_id !== $v) {
            $this->queued_document_id = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::QUEUED_DOCUMENT_ID;
        }


        return $this;
    } // setQueuedDocumentId()

    /**
     * Set the value of [type] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Set the value of [category] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setCategory($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->category !== $v) {
            $this->category = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::CATEGORY;
        }


        return $this;
    } // setCategory()

    /**
     * Set the value of [description] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [batch] column.
     * custom identifier used to group documents
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setBatch($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->batch !== $v) {
            $this->batch = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::BATCH;
        }


        return $this;
    } // setBatch()

    /**
     * Set the value of [cluster] column.
     * used to group documents by some attribute
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setCluster($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cluster !== $v) {
            $this->cluster = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::CLUSTER;
        }


        return $this;
    } // setCluster()

    /**
     * Set the value of [template_repository_id] column.
     * the id of the repository in which the master template is stored
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setTemplateRepositoryId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->template_repository_id !== $v) {
            $this->template_repository_id = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID;
        }


        return $this;
    } // setTemplateRepositoryId()

    /**
     * Set the value of [master_template] column.
     * the name of the master template, will be used if not overridden
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setMasterTemplate($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->master_template !== $v) {
            $this->master_template = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::MASTER_TEMPLATE;
        }


        return $this;
    } // setMasterTemplate()

    /**
     * Set the value of [output_format] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setOutputFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->output_format !== $v) {
            $this->output_format = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::OUTPUT_FORMAT;
        }


        return $this;
    } // setOutputFormat()

    /**
     * Set the value of [output_name] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setOutputName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->output_name !== $v) {
            $this->output_name = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::OUTPUT_NAME;
        }


        return $this;
    } // setOutputName()

    /**
     * Set the value of [data] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->data !== $v) {
            $this->data = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::DATA;
        }


        return $this;
    } // setData()

    /**
     * Sets the value of the [overrideable_flag] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * if set, the template will be editable/overrideable
     * @param boolean|integer|string $v The new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setOverrideableFlag($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->overrideable_flag !== $v) {
            $this->overrideable_flag = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::OVERRIDEABLE_FLAG;
        }


        return $this;
    } // setOverrideableFlag()

    /**
     * Sets the value of [generation_date] column to a normalized version of the date/time value specified.
     * date in which the document will be finalized
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setGenerationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->generation_date !== null || $dt !== null) {
            $currentDateAsString = ($this->generation_date !== null && $tmpDt = new DateTime($this->generation_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->generation_date = $newDateAsString;
                $this->modifiedColumns[] = QueuedDocumentPeer::GENERATION_DATE;
            }
        } // if either are not null


        return $this;
    } // setGenerationDate()

    /**
     * Set the value of [attributes] column.
     * custom attributes
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setAttributes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->attributes !== $v) {
            $this->attributes = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::ATTRIBUTES;
        }


        return $this;
    } // setAttributes()

    /**
     * Set the value of [ext] column.
     *
     * @param  string $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setExt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ext !== $v) {
            $this->ext = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::EXT;
        }


        return $this;
    } // setExt()

    /**
     * Sets the value of [creation_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setCreationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->creation_date !== null || $dt !== null) {
            $currentDateAsString = ($this->creation_date !== null && $tmpDt = new DateTime($this->creation_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->creation_date = $newDateAsString;
                $this->modifiedColumns[] = QueuedDocumentPeer::CREATION_DATE;
            }
        } // if either are not null


        return $this;
    } // setCreationDate()

    /**
     * Sets the value of [update_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setUpdateDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->update_date !== null || $dt !== null) {
            $currentDateAsString = ($this->update_date !== null && $tmpDt = new DateTime($this->update_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->update_date = $newDateAsString;
                $this->modifiedColumns[] = QueuedDocumentPeer::UPDATE_DATE;
            }
        } // if either are not null


        return $this;
    } // setUpdateDate()

    /**
     * Set the value of [creation_user_id] column.
     *
     * @param  int $v new value
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setCreationUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->creation_user_id !== $v) {
            $this->creation_user_id = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::CREATION_USER_ID;
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
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setUpdateUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->update_user_id !== $v) {
            $this->update_user_id = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::UPDATE_USER_ID;
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
     * @return QueuedDocument The current object (for fluent API support)
     */
    public function setRecordVersion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->record_version !== $v) {
            $this->record_version = $v;
            $this->modifiedColumns[] = QueuedDocumentPeer::RECORD_VERSION;
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

            $this->queued_document_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->type = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->category = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->batch = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->cluster = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->template_repository_id = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->master_template = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->output_format = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->output_name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->data = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->overrideable_flag = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
            $this->generation_date = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->attributes = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->ext = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->creation_date = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->update_date = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->creation_user_id = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
            $this->update_user_id = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->record_version = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 20; // 20 = QueuedDocumentPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating QueuedDocument object", $e);
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
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = QueuedDocumentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAccountRelatedByCreationUserId = null;
            $this->aAccountRelatedByUpdateUserId = null;
        } // if (deep)
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
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = QueuedDocumentQuery::create()
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
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                QueuedDocumentPeer::addInstanceToPool($this);
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

        $this->modifiedColumns[] = QueuedDocumentPeer::QUEUED_DOCUMENT_ID;
        if (null !== $this->queued_document_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . QueuedDocumentPeer::QUEUED_DOCUMENT_ID . ')');
        }
        if (null === $this->queued_document_id) {
            try {
                $stmt = $con->query("SELECT nextval('gendoc.queued_document_queued_document_id_seq')");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $this->queued_document_id = $row[0];
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(QueuedDocumentPeer::QUEUED_DOCUMENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'queued_document_id';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::CATEGORY)) {
            $modifiedColumns[':p' . $index++]  = 'category';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::BATCH)) {
            $modifiedColumns[':p' . $index++]  = 'batch';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::CLUSTER)) {
            $modifiedColumns[':p' . $index++]  = 'cluster';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'template_repository_id';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::MASTER_TEMPLATE)) {
            $modifiedColumns[':p' . $index++]  = 'master_template';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::OUTPUT_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = 'output_format';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::OUTPUT_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'output_name';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::DATA)) {
            $modifiedColumns[':p' . $index++]  = 'data';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::OVERRIDEABLE_FLAG)) {
            $modifiedColumns[':p' . $index++]  = 'overrideable_flag';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::GENERATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'generation_date';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::ATTRIBUTES)) {
            $modifiedColumns[':p' . $index++]  = 'attributes';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::EXT)) {
            $modifiedColumns[':p' . $index++]  = 'ext';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::CREATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'creation_date';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::UPDATE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'update_date';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::CREATION_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'creation_user_id';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::UPDATE_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'update_user_id';
        }
        if ($this->isColumnModified(QueuedDocumentPeer::RECORD_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'record_version';
        }

        $sql = sprintf(
            'INSERT INTO gendoc.queued_document (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'queued_document_id':
                        $stmt->bindValue($identifier, $this->queued_document_id, PDO::PARAM_INT);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'category':
                        $stmt->bindValue($identifier, $this->category, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'batch':
                        $stmt->bindValue($identifier, $this->batch, PDO::PARAM_STR);
                        break;
                    case 'cluster':
                        $stmt->bindValue($identifier, $this->cluster, PDO::PARAM_STR);
                        break;
                    case 'template_repository_id':
                        $stmt->bindValue($identifier, $this->template_repository_id, PDO::PARAM_STR);
                        break;
                    case 'master_template':
                        $stmt->bindValue($identifier, $this->master_template, PDO::PARAM_STR);
                        break;
                    case 'output_format':
                        $stmt->bindValue($identifier, $this->output_format, PDO::PARAM_STR);
                        break;
                    case 'output_name':
                        $stmt->bindValue($identifier, $this->output_name, PDO::PARAM_STR);
                        break;
                    case 'data':
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_STR);
                        break;
                    case 'overrideable_flag':
                        $stmt->bindValue($identifier, $this->overrideable_flag, PDO::PARAM_BOOL);
                        break;
                    case 'generation_date':
                        $stmt->bindValue($identifier, $this->generation_date, PDO::PARAM_STR);
                        break;
                    case 'attributes':
                        $stmt->bindValue($identifier, $this->attributes, PDO::PARAM_STR);
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


            if (($retval = QueuedDocumentPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = QueuedDocumentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getQueuedDocumentId();
                break;
            case 1:
                return $this->getType();
                break;
            case 2:
                return $this->getCategory();
                break;
            case 3:
                return $this->getDescription();
                break;
            case 4:
                return $this->getBatch();
                break;
            case 5:
                return $this->getCluster();
                break;
            case 6:
                return $this->getTemplateRepositoryId();
                break;
            case 7:
                return $this->getMasterTemplate();
                break;
            case 8:
                return $this->getOutputFormat();
                break;
            case 9:
                return $this->getOutputName();
                break;
            case 10:
                return $this->getData();
                break;
            case 11:
                return $this->getOverrideableFlag();
                break;
            case 12:
                return $this->getGenerationDate();
                break;
            case 13:
                return $this->getAttributes();
                break;
            case 14:
                return $this->getExt();
                break;
            case 15:
                return $this->getCreationDate();
                break;
            case 16:
                return $this->getUpdateDate();
                break;
            case 17:
                return $this->getCreationUserId();
                break;
            case 18:
                return $this->getUpdateUserId();
                break;
            case 19:
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
        if (isset($alreadyDumpedObjects['QueuedDocument'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['QueuedDocument'][$this->getPrimaryKey()] = true;
        $keys = QueuedDocumentPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getQueuedDocumentId(),
            $keys[1] => $this->getType(),
            $keys[2] => $this->getCategory(),
            $keys[3] => $this->getDescription(),
            $keys[4] => $this->getBatch(),
            $keys[5] => $this->getCluster(),
            $keys[6] => $this->getTemplateRepositoryId(),
            $keys[7] => $this->getMasterTemplate(),
            $keys[8] => $this->getOutputFormat(),
            $keys[9] => $this->getOutputName(),
            $keys[10] => $this->getData(),
            $keys[11] => $this->getOverrideableFlag(),
            $keys[12] => $this->getGenerationDate(),
            $keys[13] => $this->getAttributes(),
            $keys[14] => $this->getExt(),
            $keys[15] => $this->getCreationDate(),
            $keys[16] => $this->getUpdateDate(),
            $keys[17] => $this->getCreationUserId(),
            $keys[18] => $this->getUpdateUserId(),
            $keys[19] => $this->getRecordVersion(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAccountRelatedByCreationUserId) {
                $result['AccountRelatedByCreationUserId'] = $this->aAccountRelatedByCreationUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAccountRelatedByUpdateUserId) {
                $result['AccountRelatedByUpdateUserId'] = $this->aAccountRelatedByUpdateUserId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = QueuedDocumentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setQueuedDocumentId($value);
                break;
            case 1:
                $this->setType($value);
                break;
            case 2:
                $this->setCategory($value);
                break;
            case 3:
                $this->setDescription($value);
                break;
            case 4:
                $this->setBatch($value);
                break;
            case 5:
                $this->setCluster($value);
                break;
            case 6:
                $this->setTemplateRepositoryId($value);
                break;
            case 7:
                $this->setMasterTemplate($value);
                break;
            case 8:
                $this->setOutputFormat($value);
                break;
            case 9:
                $this->setOutputName($value);
                break;
            case 10:
                $this->setData($value);
                break;
            case 11:
                $this->setOverrideableFlag($value);
                break;
            case 12:
                $this->setGenerationDate($value);
                break;
            case 13:
                $this->setAttributes($value);
                break;
            case 14:
                $this->setExt($value);
                break;
            case 15:
                $this->setCreationDate($value);
                break;
            case 16:
                $this->setUpdateDate($value);
                break;
            case 17:
                $this->setCreationUserId($value);
                break;
            case 18:
                $this->setUpdateUserId($value);
                break;
            case 19:
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
        $keys = QueuedDocumentPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setQueuedDocumentId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setType($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCategory($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setBatch($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCluster($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setTemplateRepositoryId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setMasterTemplate($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setOutputFormat($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setOutputName($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setData($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setOverrideableFlag($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setGenerationDate($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setAttributes($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setExt($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setCreationDate($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setUpdateDate($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setCreationUserId($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setUpdateUserId($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setRecordVersion($arr[$keys[19]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);

        if ($this->isColumnModified(QueuedDocumentPeer::QUEUED_DOCUMENT_ID)) $criteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $this->queued_document_id);
        if ($this->isColumnModified(QueuedDocumentPeer::TYPE)) $criteria->add(QueuedDocumentPeer::TYPE, $this->type);
        if ($this->isColumnModified(QueuedDocumentPeer::CATEGORY)) $criteria->add(QueuedDocumentPeer::CATEGORY, $this->category);
        if ($this->isColumnModified(QueuedDocumentPeer::DESCRIPTION)) $criteria->add(QueuedDocumentPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(QueuedDocumentPeer::BATCH)) $criteria->add(QueuedDocumentPeer::BATCH, $this->batch);
        if ($this->isColumnModified(QueuedDocumentPeer::CLUSTER)) $criteria->add(QueuedDocumentPeer::CLUSTER, $this->cluster);
        if ($this->isColumnModified(QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID)) $criteria->add(QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID, $this->template_repository_id);
        if ($this->isColumnModified(QueuedDocumentPeer::MASTER_TEMPLATE)) $criteria->add(QueuedDocumentPeer::MASTER_TEMPLATE, $this->master_template);
        if ($this->isColumnModified(QueuedDocumentPeer::OUTPUT_FORMAT)) $criteria->add(QueuedDocumentPeer::OUTPUT_FORMAT, $this->output_format);
        if ($this->isColumnModified(QueuedDocumentPeer::OUTPUT_NAME)) $criteria->add(QueuedDocumentPeer::OUTPUT_NAME, $this->output_name);
        if ($this->isColumnModified(QueuedDocumentPeer::DATA)) $criteria->add(QueuedDocumentPeer::DATA, $this->data);
        if ($this->isColumnModified(QueuedDocumentPeer::OVERRIDEABLE_FLAG)) $criteria->add(QueuedDocumentPeer::OVERRIDEABLE_FLAG, $this->overrideable_flag);
        if ($this->isColumnModified(QueuedDocumentPeer::GENERATION_DATE)) $criteria->add(QueuedDocumentPeer::GENERATION_DATE, $this->generation_date);
        if ($this->isColumnModified(QueuedDocumentPeer::ATTRIBUTES)) $criteria->add(QueuedDocumentPeer::ATTRIBUTES, $this->attributes);
        if ($this->isColumnModified(QueuedDocumentPeer::EXT)) $criteria->add(QueuedDocumentPeer::EXT, $this->ext);
        if ($this->isColumnModified(QueuedDocumentPeer::CREATION_DATE)) $criteria->add(QueuedDocumentPeer::CREATION_DATE, $this->creation_date);
        if ($this->isColumnModified(QueuedDocumentPeer::UPDATE_DATE)) $criteria->add(QueuedDocumentPeer::UPDATE_DATE, $this->update_date);
        if ($this->isColumnModified(QueuedDocumentPeer::CREATION_USER_ID)) $criteria->add(QueuedDocumentPeer::CREATION_USER_ID, $this->creation_user_id);
        if ($this->isColumnModified(QueuedDocumentPeer::UPDATE_USER_ID)) $criteria->add(QueuedDocumentPeer::UPDATE_USER_ID, $this->update_user_id);
        if ($this->isColumnModified(QueuedDocumentPeer::RECORD_VERSION)) $criteria->add(QueuedDocumentPeer::RECORD_VERSION, $this->record_version);

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
        $criteria = new Criteria(QueuedDocumentPeer::DATABASE_NAME);
        $criteria->add(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $this->queued_document_id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getQueuedDocumentId();
    }

    /**
     * Generic method to set the primary key (queued_document_id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setQueuedDocumentId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getQueuedDocumentId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of QueuedDocument (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setType($this->getType());
        $copyObj->setCategory($this->getCategory());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setBatch($this->getBatch());
        $copyObj->setCluster($this->getCluster());
        $copyObj->setTemplateRepositoryId($this->getTemplateRepositoryId());
        $copyObj->setMasterTemplate($this->getMasterTemplate());
        $copyObj->setOutputFormat($this->getOutputFormat());
        $copyObj->setOutputName($this->getOutputName());
        $copyObj->setData($this->getData());
        $copyObj->setOverrideableFlag($this->getOverrideableFlag());
        $copyObj->setGenerationDate($this->getGenerationDate());
        $copyObj->setAttributes($this->getAttributes());
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

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setQueuedDocumentId(NULL); // this is a auto-increment column, so set to default value
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
     * @return QueuedDocument Clone of current object.
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
     * @return QueuedDocumentPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new QueuedDocumentPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return QueuedDocument The current object (for fluent API support)
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
            $v->addQueuedDocumentRelatedByCreationUserId($this);
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
                $this->aAccountRelatedByCreationUserId->addQueuedDocumentsRelatedByCreationUserId($this);
             */
        }

        return $this->aAccountRelatedByCreationUserId;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return QueuedDocument The current object (for fluent API support)
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
            $v->addQueuedDocumentRelatedByUpdateUserId($this);
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
                $this->aAccountRelatedByUpdateUserId->addQueuedDocumentsRelatedByUpdateUserId($this);
             */
        }

        return $this->aAccountRelatedByUpdateUserId;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->queued_document_id = null;
        $this->type = null;
        $this->category = null;
        $this->description = null;
        $this->batch = null;
        $this->cluster = null;
        $this->template_repository_id = null;
        $this->master_template = null;
        $this->output_format = null;
        $this->output_name = null;
        $this->data = null;
        $this->overrideable_flag = null;
        $this->generation_date = null;
        $this->attributes = null;
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
            if ($this->aAccountRelatedByCreationUserId instanceof Persistent) {
              $this->aAccountRelatedByCreationUserId->clearAllReferences($deep);
            }
            if ($this->aAccountRelatedByUpdateUserId instanceof Persistent) {
              $this->aAccountRelatedByUpdateUserId->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

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
        return (string) $this->exportTo(QueuedDocumentPeer::DEFAULT_STRING_FORMAT);
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
