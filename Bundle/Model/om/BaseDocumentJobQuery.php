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
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobPeer;
use Eulogix\Cool\Gendoc\Bundle\Model\DocumentJobQuery;
use Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocument;

/**
 * @method DocumentJobQuery orderByDocumentJobId($order = Criteria::ASC) Order by the document_job_id column
 * @method DocumentJobQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DocumentJobQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method DocumentJobQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method DocumentJobQuery orderByDocumentsPerIteration($order = Criteria::ASC) Order by the documents_per_iteration column
 * @method DocumentJobQuery orderByMinutesBetweenIterations($order = Criteria::ASC) Order by the minutes_between_iterations column
 * @method DocumentJobQuery orderByScheduleWeekdays($order = Criteria::ASC) Order by the schedule_weekdays column
 * @method DocumentJobQuery orderByScheduleHours($order = Criteria::ASC) Order by the schedule_hours column
 * @method DocumentJobQuery orderByLastIterationDate($order = Criteria::ASC) Order by the last_iteration_date column
 * @method DocumentJobQuery orderByStartCodeSnippetId($order = Criteria::ASC) Order by the start_code_snippet_id column
 * @method DocumentJobQuery orderByFinishCodeSnippetId($order = Criteria::ASC) Order by the finish_code_snippet_id column
 * @method DocumentJobQuery orderByExt($order = Criteria::ASC) Order by the ext column
 * @method DocumentJobQuery orderByCreationDate($order = Criteria::ASC) Order by the creation_date column
 * @method DocumentJobQuery orderByUpdateDate($order = Criteria::ASC) Order by the update_date column
 * @method DocumentJobQuery orderByCreationUserId($order = Criteria::ASC) Order by the creation_user_id column
 * @method DocumentJobQuery orderByUpdateUserId($order = Criteria::ASC) Order by the update_user_id column
 * @method DocumentJobQuery orderByRecordVersion($order = Criteria::ASC) Order by the record_version column
 *
 * @method DocumentJobQuery groupByDocumentJobId() Group by the document_job_id column
 * @method DocumentJobQuery groupByName() Group by the name column
 * @method DocumentJobQuery groupByDescription() Group by the description column
 * @method DocumentJobQuery groupByData() Group by the data column
 * @method DocumentJobQuery groupByDocumentsPerIteration() Group by the documents_per_iteration column
 * @method DocumentJobQuery groupByMinutesBetweenIterations() Group by the minutes_between_iterations column
 * @method DocumentJobQuery groupByScheduleWeekdays() Group by the schedule_weekdays column
 * @method DocumentJobQuery groupByScheduleHours() Group by the schedule_hours column
 * @method DocumentJobQuery groupByLastIterationDate() Group by the last_iteration_date column
 * @method DocumentJobQuery groupByStartCodeSnippetId() Group by the start_code_snippet_id column
 * @method DocumentJobQuery groupByFinishCodeSnippetId() Group by the finish_code_snippet_id column
 * @method DocumentJobQuery groupByExt() Group by the ext column
 * @method DocumentJobQuery groupByCreationDate() Group by the creation_date column
 * @method DocumentJobQuery groupByUpdateDate() Group by the update_date column
 * @method DocumentJobQuery groupByCreationUserId() Group by the creation_user_id column
 * @method DocumentJobQuery groupByUpdateUserId() Group by the update_user_id column
 * @method DocumentJobQuery groupByRecordVersion() Group by the record_version column
 *
 * @method DocumentJobQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DocumentJobQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DocumentJobQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DocumentJobQuery leftJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 * @method DocumentJobQuery rightJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 * @method DocumentJobQuery innerJoinCodeSnippetRelatedByStartCodeSnippetId($relationAlias = null) Adds a INNER JOIN clause to the query using the CodeSnippetRelatedByStartCodeSnippetId relation
 *
 * @method DocumentJobQuery leftJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 * @method DocumentJobQuery rightJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 * @method DocumentJobQuery innerJoinCodeSnippetRelatedByFinishCodeSnippetId($relationAlias = null) Adds a INNER JOIN clause to the query using the CodeSnippetRelatedByFinishCodeSnippetId relation
 *
 * @method DocumentJobQuery leftJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByCreationUserId relation
 * @method DocumentJobQuery rightJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByCreationUserId relation
 * @method DocumentJobQuery innerJoinAccountRelatedByCreationUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByCreationUserId relation
 *
 * @method DocumentJobQuery leftJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 * @method DocumentJobQuery rightJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 * @method DocumentJobQuery innerJoinAccountRelatedByUpdateUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByUpdateUserId relation
 *
 * @method DocumentJobQuery leftJoinQueuedDocument($relationAlias = null) Adds a LEFT JOIN clause to the query using the QueuedDocument relation
 * @method DocumentJobQuery rightJoinQueuedDocument($relationAlias = null) Adds a RIGHT JOIN clause to the query using the QueuedDocument relation
 * @method DocumentJobQuery innerJoinQueuedDocument($relationAlias = null) Adds a INNER JOIN clause to the query using the QueuedDocument relation
 *
 * @method DocumentJob findOne(PropelPDO $con = null) Return the first DocumentJob matching the query
 * @method DocumentJob findOneOrCreate(PropelPDO $con = null) Return the first DocumentJob matching the query, or a new DocumentJob object populated from the query conditions when no match is found
 *
 * @method DocumentJob findOneByName(string $name) Return the first DocumentJob filtered by the name column
 * @method DocumentJob findOneByDescription(string $description) Return the first DocumentJob filtered by the description column
 * @method DocumentJob findOneByData(string $data) Return the first DocumentJob filtered by the data column
 * @method DocumentJob findOneByDocumentsPerIteration(int $documents_per_iteration) Return the first DocumentJob filtered by the documents_per_iteration column
 * @method DocumentJob findOneByMinutesBetweenIterations(int $minutes_between_iterations) Return the first DocumentJob filtered by the minutes_between_iterations column
 * @method DocumentJob findOneByScheduleWeekdays(string $schedule_weekdays) Return the first DocumentJob filtered by the schedule_weekdays column
 * @method DocumentJob findOneByScheduleHours(string $schedule_hours) Return the first DocumentJob filtered by the schedule_hours column
 * @method DocumentJob findOneByLastIterationDate(string $last_iteration_date) Return the first DocumentJob filtered by the last_iteration_date column
 * @method DocumentJob findOneByStartCodeSnippetId(int $start_code_snippet_id) Return the first DocumentJob filtered by the start_code_snippet_id column
 * @method DocumentJob findOneByFinishCodeSnippetId(int $finish_code_snippet_id) Return the first DocumentJob filtered by the finish_code_snippet_id column
 * @method DocumentJob findOneByExt(string $ext) Return the first DocumentJob filtered by the ext column
 * @method DocumentJob findOneByCreationDate(string $creation_date) Return the first DocumentJob filtered by the creation_date column
 * @method DocumentJob findOneByUpdateDate(string $update_date) Return the first DocumentJob filtered by the update_date column
 * @method DocumentJob findOneByCreationUserId(int $creation_user_id) Return the first DocumentJob filtered by the creation_user_id column
 * @method DocumentJob findOneByUpdateUserId(int $update_user_id) Return the first DocumentJob filtered by the update_user_id column
 * @method DocumentJob findOneByRecordVersion(int $record_version) Return the first DocumentJob filtered by the record_version column
 *
 * @method array findByDocumentJobId(int $document_job_id) Return DocumentJob objects filtered by the document_job_id column
 * @method array findByName(string $name) Return DocumentJob objects filtered by the name column
 * @method array findByDescription(string $description) Return DocumentJob objects filtered by the description column
 * @method array findByData(string $data) Return DocumentJob objects filtered by the data column
 * @method array findByDocumentsPerIteration(int $documents_per_iteration) Return DocumentJob objects filtered by the documents_per_iteration column
 * @method array findByMinutesBetweenIterations(int $minutes_between_iterations) Return DocumentJob objects filtered by the minutes_between_iterations column
 * @method array findByScheduleWeekdays(string $schedule_weekdays) Return DocumentJob objects filtered by the schedule_weekdays column
 * @method array findByScheduleHours(string $schedule_hours) Return DocumentJob objects filtered by the schedule_hours column
 * @method array findByLastIterationDate(string $last_iteration_date) Return DocumentJob objects filtered by the last_iteration_date column
 * @method array findByStartCodeSnippetId(int $start_code_snippet_id) Return DocumentJob objects filtered by the start_code_snippet_id column
 * @method array findByFinishCodeSnippetId(int $finish_code_snippet_id) Return DocumentJob objects filtered by the finish_code_snippet_id column
 * @method array findByExt(string $ext) Return DocumentJob objects filtered by the ext column
 * @method array findByCreationDate(string $creation_date) Return DocumentJob objects filtered by the creation_date column
 * @method array findByUpdateDate(string $update_date) Return DocumentJob objects filtered by the update_date column
 * @method array findByCreationUserId(int $creation_user_id) Return DocumentJob objects filtered by the creation_user_id column
 * @method array findByUpdateUserId(int $update_user_id) Return DocumentJob objects filtered by the update_user_id column
 * @method array findByRecordVersion(int $record_version) Return DocumentJob objects filtered by the record_version column
 */
abstract class BaseDocumentJobQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDocumentJobQuery object.
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
            $modelName = 'Eulogix\\Cool\\Gendoc\\Bundle\\Model\\DocumentJob';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DocumentJobQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DocumentJobQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DocumentJobQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DocumentJobQuery) {
            return $criteria;
        }
        $query = new DocumentJobQuery(null, null, $modelAlias);

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
     * @return   DocumentJob|DocumentJob[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DocumentJobPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DocumentJobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DocumentJob A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByDocumentJobId($key, $con = null)
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
     * @return                 DocumentJob A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT document_job_id, name, description, data, documents_per_iteration, minutes_between_iterations, schedule_weekdays, schedule_hours, last_iteration_date, start_code_snippet_id, finish_code_snippet_id, ext, creation_date, update_date, creation_user_id, update_user_id, record_version FROM gendoc.document_job WHERE document_job_id = :p0';
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
            $obj = new DocumentJob();
            $obj->hydrate($row);
            DocumentJobPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DocumentJob|DocumentJob[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DocumentJob[]|mixed the list of results, formatted by the current formatter
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
     * @return DocumentJob[]
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $keys, Criteria::IN);
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
     * @param     mixed $documentJobId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByDocumentJobId($documentJobId = null, $comparison = null)
    {
        if (is_array($documentJobId)) {
            $useMinMax = false;
            if (isset($documentJobId['min'])) {
                $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $documentJobId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($documentJobId['max'])) {
                $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $documentJobId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $documentJobId, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::NAME, $name, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocumentJobPeer::DESCRIPTION, $description, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocumentJobPeer::DATA, $data, $comparison);
    }

    /**
     * Filter the query on the documents_per_iteration column
     *
     * Example usage:
     * <code>
     * $query->filterByDocumentsPerIteration(1234); // WHERE documents_per_iteration = 1234
     * $query->filterByDocumentsPerIteration(array(12, 34)); // WHERE documents_per_iteration IN (12, 34)
     * $query->filterByDocumentsPerIteration(array('min' => 12)); // WHERE documents_per_iteration >= 12
     * $query->filterByDocumentsPerIteration(array('max' => 12)); // WHERE documents_per_iteration <= 12
     * </code>
     *
     * @param     mixed $documentsPerIteration The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByDocumentsPerIteration($documentsPerIteration = null, $comparison = null)
    {
        if (is_array($documentsPerIteration)) {
            $useMinMax = false;
            if (isset($documentsPerIteration['min'])) {
                $this->addUsingAlias(DocumentJobPeer::DOCUMENTS_PER_ITERATION, $documentsPerIteration['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($documentsPerIteration['max'])) {
                $this->addUsingAlias(DocumentJobPeer::DOCUMENTS_PER_ITERATION, $documentsPerIteration['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::DOCUMENTS_PER_ITERATION, $documentsPerIteration, $comparison);
    }

    /**
     * Filter the query on the minutes_between_iterations column
     *
     * Example usage:
     * <code>
     * $query->filterByMinutesBetweenIterations(1234); // WHERE minutes_between_iterations = 1234
     * $query->filterByMinutesBetweenIterations(array(12, 34)); // WHERE minutes_between_iterations IN (12, 34)
     * $query->filterByMinutesBetweenIterations(array('min' => 12)); // WHERE minutes_between_iterations >= 12
     * $query->filterByMinutesBetweenIterations(array('max' => 12)); // WHERE minutes_between_iterations <= 12
     * </code>
     *
     * @param     mixed $minutesBetweenIterations The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByMinutesBetweenIterations($minutesBetweenIterations = null, $comparison = null)
    {
        if (is_array($minutesBetweenIterations)) {
            $useMinMax = false;
            if (isset($minutesBetweenIterations['min'])) {
                $this->addUsingAlias(DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS, $minutesBetweenIterations['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minutesBetweenIterations['max'])) {
                $this->addUsingAlias(DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS, $minutesBetweenIterations['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::MINUTES_BETWEEN_ITERATIONS, $minutesBetweenIterations, $comparison);
    }

    /**
     * Filter the query on the schedule_weekdays column
     *
     * Example usage:
     * <code>
     * $query->filterByScheduleWeekdays('fooValue');   // WHERE schedule_weekdays = 'fooValue'
     * $query->filterByScheduleWeekdays('%fooValue%'); // WHERE schedule_weekdays LIKE '%fooValue%'
     * </code>
     *
     * @param     string $scheduleWeekdays The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByScheduleWeekdays($scheduleWeekdays = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($scheduleWeekdays)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $scheduleWeekdays)) {
                $scheduleWeekdays = str_replace('*', '%', $scheduleWeekdays);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::SCHEDULE_WEEKDAYS, $scheduleWeekdays, $comparison);
    }

    /**
     * Filter the query on the schedule_hours column
     *
     * Example usage:
     * <code>
     * $query->filterByScheduleHours('fooValue');   // WHERE schedule_hours = 'fooValue'
     * $query->filterByScheduleHours('%fooValue%'); // WHERE schedule_hours LIKE '%fooValue%'
     * </code>
     *
     * @param     string $scheduleHours The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByScheduleHours($scheduleHours = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($scheduleHours)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $scheduleHours)) {
                $scheduleHours = str_replace('*', '%', $scheduleHours);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::SCHEDULE_HOURS, $scheduleHours, $comparison);
    }

    /**
     * Filter the query on the last_iteration_date column
     *
     * Example usage:
     * <code>
     * $query->filterByLastIterationDate('2011-03-14'); // WHERE last_iteration_date = '2011-03-14'
     * $query->filterByLastIterationDate('now'); // WHERE last_iteration_date = '2011-03-14'
     * $query->filterByLastIterationDate(array('max' => 'yesterday')); // WHERE last_iteration_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $lastIterationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByLastIterationDate($lastIterationDate = null, $comparison = null)
    {
        if (is_array($lastIterationDate)) {
            $useMinMax = false;
            if (isset($lastIterationDate['min'])) {
                $this->addUsingAlias(DocumentJobPeer::LAST_ITERATION_DATE, $lastIterationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastIterationDate['max'])) {
                $this->addUsingAlias(DocumentJobPeer::LAST_ITERATION_DATE, $lastIterationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::LAST_ITERATION_DATE, $lastIterationDate, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByStartCodeSnippetId($startCodeSnippetId = null, $comparison = null)
    {
        if (is_array($startCodeSnippetId)) {
            $useMinMax = false;
            if (isset($startCodeSnippetId['min'])) {
                $this->addUsingAlias(DocumentJobPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startCodeSnippetId['max'])) {
                $this->addUsingAlias(DocumentJobPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::START_CODE_SNIPPET_ID, $startCodeSnippetId, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByFinishCodeSnippetId($finishCodeSnippetId = null, $comparison = null)
    {
        if (is_array($finishCodeSnippetId)) {
            $useMinMax = false;
            if (isset($finishCodeSnippetId['min'])) {
                $this->addUsingAlias(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finishCodeSnippetId['max'])) {
                $this->addUsingAlias(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $finishCodeSnippetId, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocumentJobPeer::EXT, $ext, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByCreationDate($creationDate = null, $comparison = null)
    {
        if (is_array($creationDate)) {
            $useMinMax = false;
            if (isset($creationDate['min'])) {
                $this->addUsingAlias(DocumentJobPeer::CREATION_DATE, $creationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationDate['max'])) {
                $this->addUsingAlias(DocumentJobPeer::CREATION_DATE, $creationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::CREATION_DATE, $creationDate, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByUpdateDate($updateDate = null, $comparison = null)
    {
        if (is_array($updateDate)) {
            $useMinMax = false;
            if (isset($updateDate['min'])) {
                $this->addUsingAlias(DocumentJobPeer::UPDATE_DATE, $updateDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updateDate['max'])) {
                $this->addUsingAlias(DocumentJobPeer::UPDATE_DATE, $updateDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::UPDATE_DATE, $updateDate, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByCreationUserId($creationUserId = null, $comparison = null)
    {
        if (is_array($creationUserId)) {
            $useMinMax = false;
            if (isset($creationUserId['min'])) {
                $this->addUsingAlias(DocumentJobPeer::CREATION_USER_ID, $creationUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationUserId['max'])) {
                $this->addUsingAlias(DocumentJobPeer::CREATION_USER_ID, $creationUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::CREATION_USER_ID, $creationUserId, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByUpdateUserId($updateUserId = null, $comparison = null)
    {
        if (is_array($updateUserId)) {
            $useMinMax = false;
            if (isset($updateUserId['min'])) {
                $this->addUsingAlias(DocumentJobPeer::UPDATE_USER_ID, $updateUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updateUserId['max'])) {
                $this->addUsingAlias(DocumentJobPeer::UPDATE_USER_ID, $updateUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::UPDATE_USER_ID, $updateUserId, $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function filterByRecordVersion($recordVersion = null, $comparison = null)
    {
        if (is_array($recordVersion)) {
            $useMinMax = false;
            if (isset($recordVersion['min'])) {
                $this->addUsingAlias(DocumentJobPeer::RECORD_VERSION, $recordVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($recordVersion['max'])) {
                $this->addUsingAlias(DocumentJobPeer::RECORD_VERSION, $recordVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocumentJobPeer::RECORD_VERSION, $recordVersion, $comparison);
    }

    /**
     * Filter the query by a related CodeSnippet object
     *
     * @param   CodeSnippet|PropelObjectCollection $codeSnippet The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocumentJobQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCodeSnippetRelatedByStartCodeSnippetId($codeSnippet, $comparison = null)
    {
        if ($codeSnippet instanceof CodeSnippet) {
            return $this
                ->addUsingAlias(DocumentJobPeer::START_CODE_SNIPPET_ID, $codeSnippet->getCodeSnippetId(), $comparison);
        } elseif ($codeSnippet instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentJobPeer::START_CODE_SNIPPET_ID, $codeSnippet->toKeyValue('PrimaryKey', 'CodeSnippetId'), $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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
     * @return                 DocumentJobQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCodeSnippetRelatedByFinishCodeSnippetId($codeSnippet, $comparison = null)
    {
        if ($codeSnippet instanceof CodeSnippet) {
            return $this
                ->addUsingAlias(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $codeSnippet->getCodeSnippetId(), $comparison);
        } elseif ($codeSnippet instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentJobPeer::FINISH_CODE_SNIPPET_ID, $codeSnippet->toKeyValue('PrimaryKey', 'CodeSnippetId'), $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocumentJobQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByCreationUserId($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(DocumentJobPeer::CREATION_USER_ID, $account->getAccountId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentJobPeer::CREATION_USER_ID, $account->toKeyValue('PrimaryKey', 'AccountId'), $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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
     * @return                 DocumentJobQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByUpdateUserId($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(DocumentJobPeer::UPDATE_USER_ID, $account->getAccountId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocumentJobPeer::UPDATE_USER_ID, $account->toKeyValue('PrimaryKey', 'AccountId'), $comparison);
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
     * @return DocumentJobQuery The current query, for fluid interface
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
     * Filter the query by a related QueuedDocument object
     *
     * @param   QueuedDocument|PropelObjectCollection $queuedDocument  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocumentJobQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByQueuedDocument($queuedDocument, $comparison = null)
    {
        if ($queuedDocument instanceof QueuedDocument) {
            return $this
                ->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $queuedDocument->getDocumentJobId(), $comparison);
        } elseif ($queuedDocument instanceof PropelObjectCollection) {
            return $this
                ->useQueuedDocumentQuery()
                ->filterByPrimaryKeys($queuedDocument->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQueuedDocument() only accepts arguments of type QueuedDocument or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the QueuedDocument relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function joinQueuedDocument($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('QueuedDocument');

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
            $this->addJoinObject($join, 'QueuedDocument');
        }

        return $this;
    }

    /**
     * Use the QueuedDocument relation QueuedDocument object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery A secondary query class using the current class as primary query
     */
    public function useQueuedDocumentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinQueuedDocument($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'QueuedDocument', '\Eulogix\Cool\Gendoc\Bundle\Model\QueuedDocumentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   DocumentJob $documentJob Object to remove from the list of results
     *
     * @return DocumentJobQuery The current query, for fluid interface
     */
    public function prune($documentJob = null)
    {
        if ($documentJob) {
            $this->addUsingAlias(DocumentJobPeer::DOCUMENT_JOB_ID, $documentJob->getDocumentJobId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // auditable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DocumentJobPeer::UPDATE_DATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DocumentJobPeer::UPDATE_DATE);
    }

    /**
     * Order by update date asc
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DocumentJobPeer::UPDATE_DATE);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DocumentJobPeer::CREATION_DATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DocumentJobPeer::CREATION_DATE);
    }

    /**
     * Order by create date asc
     *
     * @return     DocumentJobQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DocumentJobPeer::CREATION_DATE);
    }
}
