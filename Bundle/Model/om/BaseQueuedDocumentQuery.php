<?php

namespace Eulogix\Cool\Gendoc\Bundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\Account;
use Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippet;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJob;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery;

/**
 * @method QueuedDocumentQuery orderByQueuedDocumentId($order = Criteria::ASC) Order by the queued_document_id column
 * @method QueuedDocumentQuery orderByDocumentJobId($order = Criteria::ASC) Order by the document_job_id column
 * @method QueuedDocumentQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method QueuedDocumentQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method QueuedDocumentQuery orderByCategory($order = Criteria::ASC) Order by the category column
 * @method QueuedDocumentQuery orderByError($order = Criteria::ASC) Order by the error column
 * @method QueuedDocumentQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method QueuedDocumentQuery orderByBatch($order = Criteria::ASC) Order by the batch column
 * @method QueuedDocumentQuery orderByCluster($order = Criteria::ASC) Order by the cluster column
 * @method QueuedDocumentQuery orderByTemplateRepositoryId($order = Criteria::ASC) Order by the template_repository_id column
 * @method QueuedDocumentQuery orderByMasterTemplate($order = Criteria::ASC) Order by the master_template column
 * @method QueuedDocumentQuery orderByOutputFormat($order = Criteria::ASC) Order by the output_format column
 * @method QueuedDocumentQuery orderByOutputName($order = Criteria::ASC) Order by the output_name column
 * @method QueuedDocumentQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method QueuedDocumentQuery orderByOverrideableFlag($order = Criteria::ASC) Order by the overrideable_flag column
 * @method QueuedDocumentQuery orderByGenerationDate($order = Criteria::ASC) Order by the generation_date column
 * @method QueuedDocumentQuery orderByAttributes($order = Criteria::ASC) Order by the attributes column
 * @method QueuedDocumentQuery orderByStartCodeSnippetId($order = Criteria::ASC) Order by the start_code_snippet_id column
 * @method QueuedDocumentQuery orderByFinishCodeSnippetId($order = Criteria::ASC) Order by the finish_code_snippet_id column
 * @method QueuedDocumentQuery orderByExt($order = Criteria::ASC) Order by the ext column
 * @method QueuedDocumentQuery orderByCreationDate($order = Criteria::ASC) Order by the creation_date column
 * @method QueuedDocumentQuery orderByUpdateDate($order = Criteria::ASC) Order by the update_date column
 * @method QueuedDocumentQuery orderByCreationUserId($order = Criteria::ASC) Order by the creation_user_id column
 * @method QueuedDocumentQuery orderByUpdateUserId($order = Criteria::ASC) Order by the update_user_id column
 * @method QueuedDocumentQuery orderByRecordVersion($order = Criteria::ASC) Order by the record_version column
 *
 * @method QueuedDocumentQuery groupByQueuedDocumentId() Group by the queued_document_id column
 * @method QueuedDocumentQuery groupByDocumentJobId() Group by the document_job_id column
 * @method QueuedDocumentQuery groupByStatus() Group by the status column
 * @method QueuedDocumentQuery groupByType() Group by the type column
 * @method QueuedDocumentQuery groupByCategory() Group by the category column
 * @method QueuedDocumentQuery groupByError() Group by the error column
 * @method QueuedDocumentQuery groupByDescription() Group by the description column
 * @method QueuedDocumentQuery groupByBatch() Group by the batch column
 * @method QueuedDocumentQuery groupByCluster() Group by the cluster column
 * @method QueuedDocumentQuery groupByTemplateRepositoryId() Group by the template_repository_id column
 * @method QueuedDocumentQuery groupByMasterTemplate() Group by the master_template column
 * @method QueuedDocumentQuery groupByOutputFormat() Group by the output_format column
 * @method QueuedDocumentQuery groupByOutputName() Group by the output_name column
 * @method QueuedDocumentQuery groupByData() Group by the data column
 * @method QueuedDocumentQuery groupByOverrideableFlag() Group by the overrideable_flag column
 * @method QueuedDocumentQuery groupByGenerationDate() Group by the generation_date column
 * @method QueuedDocumentQuery groupByAttributes() Group by the attributes column
 * @method QueuedDocumentQuery groupByStartCodeSnippetId() Group by the start_code_snippet_id column
 * @method QueuedDocumentQuery groupByFinishCodeSnippetId() Group by the finish_code_snippet_id column
 * @method QueuedDocumentQuery groupByExt() Group by the ext column
 * @method QueuedDocumentQuery groupByCreationDate() Group by the creation_date column
 * @method QueuedDocumentQuery groupByUpdateDate() Group by the update_date column
 * @method QueuedDocumentQuery groupByCreationUserId() Group by the creation_user_id column
 * @method QueuedDocumentQuery groupByUpdateUserId() Group by the update_user_id column
 * @method QueuedDocumentQuery groupByRecordVersion() Group by the record_version column
 *
 * @method QueuedDocumentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method QueuedDocumentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method QueuedDocumentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method QueuedDocumentQuery leftJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 * @method QueuedDocumentQuery rightJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 * @method QueuedDocumentQuery innerJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a INNER JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 *
 * @method QueuedDocumentQuery leftJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 * @method QueuedDocumentQuery rightJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 * @method QueuedDocumentQuery innerJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a INNER JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 *
 * @method QueuedDocumentQuery leftJoinDocumentJob($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocumentJob relation
 * @method QueuedDocumentQuery rightJoinDocumentJob($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocumentJob relation
 * @method QueuedDocumentQuery innerJoinDocumentJob($relationAlias = null) Adds a INNER JOIN clause to the query using the DocumentJob relation
 *
 * @method QueuedDocumentQuery leftJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByCreationUserId relation
 * @method QueuedDocumentQuery rightJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByCreationUserId relation
 * @method QueuedDocumentQuery innerJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByCreationUserId relation
 *
 * @method QueuedDocumentQuery leftJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 * @method QueuedDocumentQuery rightJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 * @method QueuedDocumentQuery innerJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 *
 * @method QueuedDocument findOne(PropelPDO $con = null) Return the first QueuedDocument matching the query
 * @method QueuedDocument findOneOrCreate(PropelPDO $con = null) Return the first QueuedDocument matching the query, or a new QueuedDocument object populated from the query conditions when no match is found
 *
 * @method QueuedDocument findOneByDocumentJobId(int $document_job_id) Return the first QueuedDocument filtered by the document_job_id column
 * @method QueuedDocument findOneByStatus(string $status) Return the first QueuedDocument filtered by the status column
 * @method QueuedDocument findOneByType(string $type) Return the first QueuedDocument filtered by the type column
 * @method QueuedDocument findOneByCategory(string $category) Return the first QueuedDocument filtered by the category column
 * @method QueuedDocument findOneByError(string $error) Return the first QueuedDocument filtered by the error column
 * @method QueuedDocument findOneByDescription(string $description) Return the first QueuedDocument filtered by the description column
 * @method QueuedDocument findOneByBatch(string $batch) Return the first QueuedDocument filtered by the batch column
 * @method QueuedDocument findOneByCluster(string $cluster) Return the first QueuedDocument filtered by the cluster column
 * @method QueuedDocument findOneByTemplateRepositoryId(string $template_repository_id) Return the first QueuedDocument filtered by the template_repository_id column
 * @method QueuedDocument findOneByMasterTemplate(string $master_template) Return the first QueuedDocument filtered by the master_template column
 * @method QueuedDocument findOneByOutputFormat(string $output_format) Return the first QueuedDocument filtered by the output_format column
 * @method QueuedDocument findOneByOutputName(string $output_name) Return the first QueuedDocument filtered by the output_name column
 * @method QueuedDocument findOneByData(string $data) Return the first QueuedDocument filtered by the data column
 * @method QueuedDocument findOneByOverrideableFlag(boolean $overrideable_flag) Return the first QueuedDocument filtered by the overrideable_flag column
 * @method QueuedDocument findOneByGenerationDate(string $generation_date) Return the first QueuedDocument filtered by the generation_date column
 * @method QueuedDocument findOneByAttributes(string $attributes) Return the first QueuedDocument filtered by the attributes column
 * @method QueuedDocument findOneByStartCodeSnippetId(int $start_code_snippet_id) Return the first QueuedDocument filtered by the start_code_snippet_id column
 * @method QueuedDocument findOneByFinishCodeSnippetId(int $finish_code_snippet_id) Return the first QueuedDocument filtered by the finish_code_snippet_id column
 * @method QueuedDocument findOneByExt(string $ext) Return the first QueuedDocument filtered by the ext column
 * @method QueuedDocument findOneByCreationDate(string $creation_date) Return the first QueuedDocument filtered by the creation_date column
 * @method QueuedDocument findOneByUpdateDate(string $update_date) Return the first QueuedDocument filtered by the update_date column
 * @method QueuedDocument findOneByCreationUserId(int $creation_user_id) Return the first QueuedDocument filtered by the creation_user_id column
 * @method QueuedDocument findOneByUpdateUserId(int $update_user_id) Return the first QueuedDocument filtered by the update_user_id column
 * @method QueuedDocument findOneByRecordVersion(int $record_version) Return the first QueuedDocument filtered by the record_version column
 *
 * @method array findByQueuedDocumentId(int $queued_document_id) Return QueuedDocument objects filtered by the queued_document_id column
 * @method array findByDocumentJobId(int $document_job_id) Return QueuedDocument objects filtered by the document_job_id column
 * @method array findByStatus(string $status) Return QueuedDocument objects filtered by the status column
 * @method array findByType(string $type) Return QueuedDocument objects filtered by the type column
 * @method array findByCategory(string $category) Return QueuedDocument objects filtered by the category column
 * @method array findByError(string $error) Return QueuedDocument objects filtered by the error column
 * @method array findByDescription(string $description) Return QueuedDocument objects filtered by the description column
 * @method array findByBatch(string $batch) Return QueuedDocument objects filtered by the batch column
 * @method array findByCluster(string $cluster) Return QueuedDocument objects filtered by the cluster column
 * @method array findByTemplateRepositoryId(string $template_repository_id) Return QueuedDocument objects filtered by the template_repository_id column
 * @method array findByMasterTemplate(string $master_template) Return QueuedDocument objects filtered by the master_template column
 * @method array findByOutputFormat(string $output_format) Return QueuedDocument objects filtered by the output_format column
 * @method array findByOutputName(string $output_name) Return QueuedDocument objects filtered by the output_name column
 * @method array findByData(string $data) Return QueuedDocument objects filtered by the data column
 * @method array findByOverrideableFlag(boolean $overrideable_flag) Return QueuedDocument objects filtered by the overrideable_flag column
 * @method array findByGenerationDate(string $generation_date) Return QueuedDocument objects filtered by the generation_date column
 * @method array findByAttributes(string $attributes) Return QueuedDocument objects filtered by the attributes column
 * @method array findByStartCodeSnippetId(int $start_code_snippet_id) Return QueuedDocument objects filtered by the start_code_snippet_id column
 * @method array findByFinishCodeSnippetId(int $finish_code_snippet_id) Return QueuedDocument objects filtered by the finish_code_snippet_id column
 * @method array findByExt(string $ext) Return QueuedDocument objects filtered by the ext column
 * @method array findByCreationDate(string $creation_date) Return QueuedDocument objects filtered by the creation_date column
 * @method array findByUpdateDate(string $update_date) Return QueuedDocument objects filtered by the update_date column
 * @method array findByCreationUserId(int $creation_user_id) Return QueuedDocument objects filtered by the creation_user_id column
 * @method array findByUpdateUserId(int $update_user_id) Return QueuedDocument objects filtered by the update_user_id column
 * @method array findByRecordVersion(int $record_version) Return QueuedDocument objects filtered by the record_version column
 */
abstract class BaseQueuedDocumentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseQueuedDocumentQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'cool_db';
        }
        if (null === $modelName) {
            $modelName = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\QueuedDocument';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new QueuedDocumentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   QueuedDocumentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return QueuedDocumentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof QueuedDocumentQuery) {
            return $criteria;
        }
        $query = new QueuedDocumentQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   QueuedDocument|QueuedDocument[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QueuedDocumentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(QueuedDocumentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 QueuedDocument A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByQueuedDocumentId($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 QueuedDocument A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT queued_document_id, document_job_id, status, type, category, error, description, batch, cluster, template_repository_id, master_template, output_format, output_name, data, overrideable_flag, generation_date, attributes, start_code_snippet_id, finish_code_snippet_id, ext, creation_date, update_date, creation_user_id, update_user_id, record_version FROM gendoc.queued_document WHERE queued_document_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new QueuedDocument();
            $obj->hydrate($row);
            QueuedDocumentPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return QueuedDocument|QueuedDocument[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|QueuedDocument[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Find objects by primary key while maintaining the original sort order of the keys
     * <code>
     * $objs = $c->findPksKeepingKeyOrder(array(12, 56, 832), $con);

     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return QueuedDocument[]
     */
    public function findPksKeepingKeyOrder($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $ret = array();

        foreach($keys as $key)
            $ret[ $key ] = $this->findPk($key, $con);

        return $ret;
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the queued_document_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQueuedDocumentId(1234); // WHERE queued_document_id = 1234
     * $query->filterByQueuedDocumentId(array(12, 34)); // WHERE queued_document_id IN (12, 34)
     * $query->filterByQueuedDocumentId(array('min' => 12)); // WHERE queued_document_id >= 12
     * $query->filterByQueuedDocumentId(array('max' => 12)); // WHERE queued_document_id <= 12
     * </code>
     *
     * @param     mixed $queuedDocumentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByQueuedDocumentId($queuedDocumentId = null, $comparison = null)
    {
        if (is_array($queuedDocumentId)) {
            $useMinMax = false;
            if (isset($queuedDocumentId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $queuedDocumentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($queuedDocumentId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $queuedDocumentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $queuedDocumentId, $comparison);
    }

    /**
     * Filter the query on the document_job_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDocumentJobId(1234); // WHERE document_job_id = 1234
     * $query->filterByDocumentJobId(array(12, 34)); // WHERE document_job_id IN (12, 34)
     * $query->filterByDocumentJobId(array('min' => 12)); // WHERE document_job_id >= 12
     * $query->filterByDocumentJobId(array('max' => 12)); // WHERE document_job_id <= 12
     * </code>
     *
     * @see       filterByDocumentJob()
     *
     * @param     mixed $documentJobId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByDocumentJobId($documentJobId = null, $comparison = null)
    {
        if (is_array($documentJobId)) {
            $useMinMax = false;
            if (isset($documentJobId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::DOCUMENT_JOB_ID, $documentJobId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($documentJobId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::DOCUMENT_JOB_ID, $documentJobId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::DOCUMENT_JOB_ID, $documentJobId, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%'); // WHERE status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $status The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $status)) {
                $status = str_replace('*', '%', $status);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the category column
     *
     * Example usage:
     * <code>
     * $query->filterByCategory('fooValue');   // WHERE category = 'fooValue'
     * $query->filterByCategory('%fooValue%'); // WHERE category LIKE '%fooValue%'
     * </code>
     *
     * @param     string $category The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByCategory($category = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($category)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $category)) {
                $category = str_replace('*', '%', $category);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::CATEGORY, $category, $comparison);
    }

    /**
     * Filter the query on the error column
     *
     * Example usage:
     * <code>
     * $query->filterByError('fooValue');   // WHERE error = 'fooValue'
     * $query->filterByError('%fooValue%'); // WHERE error LIKE '%fooValue%'
     * </code>
     *
     * @param     string $error The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByError($error = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($error)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $error)) {
                $error = str_replace('*', '%', $error);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::ERROR, $error, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the batch column
     *
     * Example usage:
     * <code>
     * $query->filterByBatch('fooValue');   // WHERE batch = 'fooValue'
     * $query->filterByBatch('%fooValue%'); // WHERE batch LIKE '%fooValue%'
     * </code>
     *
     * @param     string $batch The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByBatch($batch = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($batch)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $batch)) {
                $batch = str_replace('*', '%', $batch);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::BATCH, $batch, $comparison);
    }

    /**
     * Filter the query on the cluster column
     *
     * Example usage:
     * <code>
     * $query->filterByCluster('fooValue');   // WHERE cluster = 'fooValue'
     * $query->filterByCluster('%fooValue%'); // WHERE cluster LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cluster The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByCluster($cluster = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cluster)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cluster)) {
                $cluster = str_replace('*', '%', $cluster);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::CLUSTER, $cluster, $comparison);
    }

    /**
     * Filter the query on the template_repository_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTemplateRepositoryId('fooValue');   // WHERE template_repository_id = 'fooValue'
     * $query->filterByTemplateRepositoryId('%fooValue%'); // WHERE template_repository_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $templateRepositoryId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByTemplateRepositoryId($templateRepositoryId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($templateRepositoryId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $templateRepositoryId)) {
                $templateRepositoryId = str_replace('*', '%', $templateRepositoryId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::TEMPLATE_REPOSITORY_ID, $templateRepositoryId, $comparison);
    }

    /**
     * Filter the query on the master_template column
     *
     * Example usage:
     * <code>
     * $query->filterByMasterTemplate('fooValue');   // WHERE master_template = 'fooValue'
     * $query->filterByMasterTemplate('%fooValue%'); // WHERE master_template LIKE '%fooValue%'
     * </code>
     *
     * @param     string $masterTemplate The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByMasterTemplate($masterTemplate = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($masterTemplate)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $masterTemplate)) {
                $masterTemplate = str_replace('*', '%', $masterTemplate);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::MASTER_TEMPLATE, $masterTemplate, $comparison);
    }

    /**
     * Filter the query on the output_format column
     *
     * Example usage:
     * <code>
     * $query->filterByOutputFormat('fooValue');   // WHERE output_format = 'fooValue'
     * $query->filterByOutputFormat('%fooValue%'); // WHERE output_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $outputFormat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByOutputFormat($outputFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($outputFormat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $outputFormat)) {
                $outputFormat = str_replace('*', '%', $outputFormat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::OUTPUT_FORMAT, $outputFormat, $comparison);
    }

    /**
     * Filter the query on the output_name column
     *
     * Example usage:
     * <code>
     * $query->filterByOutputName('fooValue');   // WHERE output_name = 'fooValue'
     * $query->filterByOutputName('%fooValue%'); // WHERE output_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $outputName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByOutputName($outputName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($outputName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $outputName)) {
                $outputName = str_replace('*', '%', $outputName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::OUTPUT_NAME, $outputName, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * Example usage:
     * <code>
     * $query->filterByData('fooValue');   // WHERE data = 'fooValue'
     * $query->filterByData('%fooValue%'); // WHERE data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $data The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($data)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $data)) {
                $data = str_replace('*', '%', $data);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::DATA, $data, $comparison);
    }

    /**
     * Filter the query on the overrideable_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByOverrideableFlag(true); // WHERE overrideable_flag = true
     * $query->filterByOverrideableFlag('yes'); // WHERE overrideable_flag = true
     * </code>
     *
     * @param     boolean|string $overrideableFlag The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByOverrideableFlag($overrideableFlag = null, $comparison = null)
    {
        if (is_string($overrideableFlag)) {
            $overrideableFlag = in_array(strtolower($overrideableFlag), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(QueuedDocumentPeer::OVERRIDEABLE_FLAG, $overrideableFlag, $comparison);
    }

    /**
     * Filter the query on the generation_date column
     *
     * Example usage:
     * <code>
     * $query->filterByGenerationDate('2011-03-14'); // WHERE generation_date = '2011-03-14'
     * $query->filterByGenerationDate('now'); // WHERE generation_date = '2011-03-14'
     * $query->filterByGenerationDate(array('max' => 'yesterday')); // WHERE generation_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $generationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByGenerationDate($generationDate = null, $comparison = null)
    {
        if (is_array($generationDate)) {
            $useMinMax = false;
            if (isset($generationDate['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::GENERATION_DATE, $generationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($generationDate['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::GENERATION_DATE, $generationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::GENERATION_DATE, $generationDate, $comparison);
    }

    /**
     * Filter the query on the attributes column
     *
     * Example usage:
     * <code>
     * $query->filterByAttributes('fooValue');   // WHERE attributes = 'fooValue'
     * $query->filterByAttributes('%fooValue%'); // WHERE attributes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $attributes The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByAttributes($attributes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($attributes)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $attributes)) {
                $attributes = str_replace('*', '%', $attributes);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::ATTRIBUTES, $attributes, $comparison);
    }

    /**
     * Filter the query on the start_code_snippet_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStartCodeSnippetId(1234); // WHERE start_code_snippet_id = 1234
     * $query->filterByStartCodeSnippetId(array(12, 34)); // WHERE start_code_snippet_id IN (12, 34)
     * $query->filterByStartCodeSnippetId(array('min' => 12)); // WHERE start_code_snippet_id >= 12
     * $query->filterByStartCodeSnippetId(array('max' => 12)); // WHERE start_code_snippet_id <= 12
     * </code>
     *
     * @see       filterByCodeSnippetRelatedByStartCodeSnippetId()
     *
     * @param     mixed $startCodeSnippetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByStartCodeSnippetId($startCodeSnippetId = null, $comparison = null)
    {
        if (is_array($startCodeSnippetId)) {
            $useMinMax = false;
            if (isset($startCodeSnippetId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startCodeSnippetId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId, $comparison);
    }

    /**
     * Filter the query on the finish_code_snippet_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFinishCodeSnippetId(1234); // WHERE finish_code_snippet_id = 1234
     * $query->filterByFinishCodeSnippetId(array(12, 34)); // WHERE finish_code_snippet_id IN (12, 34)
     * $query->filterByFinishCodeSnippetId(array('min' => 12)); // WHERE finish_code_snippet_id >= 12
     * $query->filterByFinishCodeSnippetId(array('max' => 12)); // WHERE finish_code_snippet_id <= 12
     * </code>
     *
     * @see       filterByCodeSnippetRelatedByFinishCodeSnippetId()
     *
     * @param     mixed $finishCodeSnippetId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByFinishCodeSnippetId($finishCodeSnippetId = null, $comparison = null)
    {
        if (is_array($finishCodeSnippetId)) {
            $useMinMax = false;
            if (isset($finishCodeSnippetId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finishCodeSnippetId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId, $comparison);
    }

    /**
     * Filter the query on the ext column
     *
     * Example usage:
     * <code>
     * $query->filterByExt('fooValue');   // WHERE ext = 'fooValue'
     * $query->filterByExt('%fooValue%'); // WHERE ext LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ext The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByExt($ext = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ext)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ext)) {
                $ext = str_replace('*', '%', $ext);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::EXT, $ext, $comparison);
    }

    /**
     * Filter the query on the creation_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationDate('2011-03-14'); // WHERE creation_date = '2011-03-14'
     * $query->filterByCreationDate('now'); // WHERE creation_date = '2011-03-14'
     * $query->filterByCreationDate(array('max' => 'yesterday')); // WHERE creation_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $creationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByCreationDate($creationDate = null, $comparison = null)
    {
        if (is_array($creationDate)) {
            $useMinMax = false;
            if (isset($creationDate['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::CREATION_DATE, $creationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationDate['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::CREATION_DATE, $creationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::CREATION_DATE, $creationDate, $comparison);
    }

    /**
     * Filter the query on the update_date column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdateDate('2011-03-14'); // WHERE update_date = '2011-03-14'
     * $query->filterByUpdateDate('now'); // WHERE update_date = '2011-03-14'
     * $query->filterByUpdateDate(array('max' => 'yesterday')); // WHERE update_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $updateDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByUpdateDate($updateDate = null, $comparison = null)
    {
        if (is_array($updateDate)) {
            $useMinMax = false;
            if (isset($updateDate['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::UPDATE_DATE, $updateDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updateDate['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::UPDATE_DATE, $updateDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::UPDATE_DATE, $updateDate, $comparison);
    }

    /**
     * Filter the query on the creation_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationUserId(1234); // WHERE creation_user_id = 1234
     * $query->filterByCreationUserId(array(12, 34)); // WHERE creation_user_id IN (12, 34)
     * $query->filterByCreationUserId(array('min' => 12)); // WHERE creation_user_id >= 12
     * $query->filterByCreationUserId(array('max' => 12)); // WHERE creation_user_id <= 12
     * </code>
     *
     * @see       filterByAccountRelatedByCreationUserId()
     *
     * @param     mixed $creationUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByCreationUserId($creationUserId = null, $comparison = null)
    {
        if (is_array($creationUserId)) {
            $useMinMax = false;
            if (isset($creationUserId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::CREATION_USER_ID, $creationUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationUserId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::CREATION_USER_ID, $creationUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::CREATION_USER_ID, $creationUserId, $comparison);
    }

    /**
     * Filter the query on the update_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdateUserId(1234); // WHERE update_user_id = 1234
     * $query->filterByUpdateUserId(array(12, 34)); // WHERE update_user_id IN (12, 34)
     * $query->filterByUpdateUserId(array('min' => 12)); // WHERE update_user_id >= 12
     * $query->filterByUpdateUserId(array('max' => 12)); // WHERE update_user_id <= 12
     * </code>
     *
     * @see       filterByAccountRelatedByUpdateUserId()
     *
     * @param     mixed $updateUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByUpdateUserId($updateUserId = null, $comparison = null)
    {
        if (is_array($updateUserId)) {
            $useMinMax = false;
            if (isset($updateUserId['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::UPDATE_USER_ID, $updateUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updateUserId['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::UPDATE_USER_ID, $updateUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::UPDATE_USER_ID, $updateUserId, $comparison);
    }

    /**
     * Filter the query on the record_version column
     *
     * Example usage:
     * <code>
     * $query->filterByRecordVersion(1234); // WHERE record_version = 1234
     * $query->filterByRecordVersion(array(12, 34)); // WHERE record_version IN (12, 34)
     * $query->filterByRecordVersion(array('min' => 12)); // WHERE record_version >= 12
     * $query->filterByRecordVersion(array('max' => 12)); // WHERE record_version <= 12
     * </code>
     *
     * @param     mixed $recordVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function filterByRecordVersion($recordVersion = null, $comparison = null)
    {
        if (is_array($recordVersion)) {
            $useMinMax = false;
            if (isset($recordVersion['min'])) {
                $this->addUsingAlias(QueuedDocumentPeer::RECORD_VERSION, $recordVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($recordVersion['max'])) {
                $this->addUsingAlias(QueuedDocumentPeer::RECORD_VERSION, $recordVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QueuedDocumentPeer::RECORD_VERSION, $recordVersion, $comparison);
    }

    /**
     * Filter the query by a related CodeSnippet object
     *
     * @param   CodeSnippet|PropelObjectCollection $codeSnippet The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 QueuedDocumentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCodeSnippetRelatedByStartCodeSnippetId($codeSnippet, $comparison = null)
    {
        if ($codeSnippet instanceof CodeSnippet) {
            return $this
                ->addUsingAlias(QueuedDocumentPeer::START_CODE_SNIPPET_ID, $codeSnippet->getCodeSnippetId(), $comparison);
        } elseif ($codeSnippet instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QueuedDocumentPeer::START_CODE_SNIPPET_ID, $codeSnippet->toKeyValue('PrimaryKey', 'CodeSnippetId'), $comparison);
        } else {
            throw new PropelException('filterByCodeSnippetRelatedByStartCodeSnippetId() only accepts arguments of type CodeSnippet or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function joinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CodeSnippetRelatedByStartCodeSnippetId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CodeSnippetRelatedByStartCodeSnippetId');
        }

        return $this;
    }

    /**
     * Use the CodeSnippetRelatedByStartCodeSnippetId relation CodeSnippet object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery A secondary query class using the current class as primary query
     */
    public function useCodeSnippetRelatedByStartCodeSnippetIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCodeSnippetRelatedByStartCodeSnippetId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CodeSnippetRelatedByStartCodeSnippetId', '\Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery');
    }

    /**
     * Filter the query by a related CodeSnippet object
     *
     * @param   CodeSnippet|PropelObjectCollection $codeSnippet The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 QueuedDocumentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCodeSnippetRelatedByFinishCodeSnippetId($codeSnippet, $comparison = null)
    {
        if ($codeSnippet instanceof CodeSnippet) {
            return $this
                ->addUsingAlias(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, $codeSnippet->getCodeSnippetId(), $comparison);
        } elseif ($codeSnippet instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QueuedDocumentPeer::FINISH_CODE_SNIPPET_ID, $codeSnippet->toKeyValue('PrimaryKey', 'CodeSnippetId'), $comparison);
        } else {
            throw new PropelException('filterByCodeSnippetRelatedByFinishCodeSnippetId() only accepts arguments of type CodeSnippet or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function joinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CodeSnippetRelatedByFinishCodeSnippetId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CodeSnippetRelatedByFinishCodeSnippetId');
        }

        return $this;
    }

    /**
     * Use the CodeSnippetRelatedByFinishCodeSnippetId relation CodeSnippet object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery A secondary query class using the current class as primary query
     */
    public function useCodeSnippetRelatedByFinishCodeSnippetIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CodeSnippetRelatedByFinishCodeSnippetId', '\Eulogix\Cool\Bundle\CoreBundle\Model\Core\CodeSnippetQuery');
    }

    /**
     * Filter the query by a related DocumentJob object
     *
     * @param   DocumentJob|PropelObjectCollection $documentJob The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 QueuedDocumentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDocumentJob($documentJob, $comparison = null)
    {
        if ($documentJob instanceof DocumentJob) {
            return $this
                ->addUsingAlias(QueuedDocumentPeer::DOCUMENT_JOB_ID, $documentJob->getDocumentJobId(), $comparison);
        } elseif ($documentJob instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QueuedDocumentPeer::DOCUMENT_JOB_ID, $documentJob->toKeyValue('PrimaryKey', 'DocumentJobId'), $comparison);
        } else {
            throw new PropelException('filterByDocumentJob() only accepts arguments of type DocumentJob or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocumentJob relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function joinDocumentJob($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocumentJob');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'DocumentJob');
        }

        return $this;
    }

    /**
     * Use the DocumentJob relation DocumentJob object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery A secondary query class using the current class as primary query
     */
    public function useDocumentJobQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocumentJob($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocumentJob', '\Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 QueuedDocumentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByCreationUserId($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(QueuedDocumentPeer::CREATION_USER_ID, $account->getAccountId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QueuedDocumentPeer::CREATION_USER_ID, $account->toKeyValue('PrimaryKey', 'AccountId'), $comparison);
        } else {
            throw new PropelException('filterByAccountRelatedByCreationUserId() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccountRelatedByCreationUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function joinAccountRelatedByCreationUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccountRelatedByCreationUserId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AccountRelatedByCreationUserId');
        }

        return $this;
    }

    /**
     * Use the AccountRelatedByCreationUserId relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountRelatedByCreationUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccountRelatedByCreationUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccountRelatedByCreationUserId', '\Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 QueuedDocumentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByUpdateUserId($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(QueuedDocumentPeer::UPDATE_USER_ID, $account->getAccountId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QueuedDocumentPeer::UPDATE_USER_ID, $account->toKeyValue('PrimaryKey', 'AccountId'), $comparison);
        } else {
            throw new PropelException('filterByAccountRelatedByUpdateUserId() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccountRelatedByUpdateUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function joinAccountRelatedByUpdateUserId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccountRelatedByUpdateUserId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AccountRelatedByUpdateUserId');
        }

        return $this;
    }

    /**
     * Use the AccountRelatedByUpdateUserId relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountRelatedByUpdateUserIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccountRelatedByUpdateUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccountRelatedByUpdateUserId', '\Eulogix\Cool\Bundle\CoreBundle\Model\Core\AccountQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   QueuedDocument $queuedDocument Object to remove from the list of results
     *
     * @return QueuedDocumentQuery The current query, for fluid interface
     */
    public function prune($queuedDocument = null)
    {
        if ($queuedDocument) {
            $this->addUsingAlias(QueuedDocumentPeer::QUEUED_DOCUMENT_ID, $queuedDocument->getQueuedDocumentId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // auditable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(QueuedDocumentPeer::UPDATE_DATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(QueuedDocumentPeer::UPDATE_DATE);
    }

    /**
     * Order by update date asc
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(QueuedDocumentPeer::UPDATE_DATE);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(QueuedDocumentPeer::CREATION_DATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(QueuedDocumentPeer::CREATION_DATE);
    }

    /**
     * Order by create date asc
     *
     * @return     QueuedDocumentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(QueuedDocumentPeer::CREATION_DATE);
    }
}
