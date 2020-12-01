<?php

namespace App\Entity\Base;

use \Exception;
use \PDO;
use App\Entity\Question as ChildQuestion;
use App\Entity\QuestionI18nQuery as ChildQuestionI18nQuery;
use App\Entity\QuestionQuery as ChildQuestionQuery;
use App\Entity\Map\QuestionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use gossi\propel\behavior\l10n\PropelL10n;

/**
 * Base class that represents a query for the 'ask_question' table.
 *
 *
 *
 * @method     ChildQuestionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildQuestionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildQuestionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildQuestionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildQuestionQuery orderByInterestedUsers($order = Criteria::ASC) Order by the interested_users column
 * @method     ChildQuestionQuery orderByStrippedTitle($order = Criteria::ASC) Order by the stripped_title column
 * @method     ChildQuestionQuery orderByReports($order = Criteria::ASC) Order by the reports column
 *
 * @method     ChildQuestionQuery groupById() Group by the id column
 * @method     ChildQuestionQuery groupByUserId() Group by the user_id column
 * @method     ChildQuestionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildQuestionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildQuestionQuery groupByInterestedUsers() Group by the interested_users column
 * @method     ChildQuestionQuery groupByStrippedTitle() Group by the stripped_title column
 * @method     ChildQuestionQuery groupByReports() Group by the reports column
 *
 * @method     ChildQuestionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildQuestionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildQuestionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildQuestionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildQuestionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildQuestionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildQuestionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildQuestionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildQuestionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildQuestionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildQuestionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildQuestionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildQuestionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildQuestionQuery leftJoinAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Answer relation
 * @method     ChildQuestionQuery rightJoinAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Answer relation
 * @method     ChildQuestionQuery innerJoinAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the Answer relation
 *
 * @method     ChildQuestionQuery joinWithAnswer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Answer relation
 *
 * @method     ChildQuestionQuery leftJoinWithAnswer() Adds a LEFT JOIN clause and with to the query using the Answer relation
 * @method     ChildQuestionQuery rightJoinWithAnswer() Adds a RIGHT JOIN clause and with to the query using the Answer relation
 * @method     ChildQuestionQuery innerJoinWithAnswer() Adds a INNER JOIN clause and with to the query using the Answer relation
 *
 * @method     ChildQuestionQuery leftJoinInterest($relationAlias = null) Adds a LEFT JOIN clause to the query using the Interest relation
 * @method     ChildQuestionQuery rightJoinInterest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Interest relation
 * @method     ChildQuestionQuery innerJoinInterest($relationAlias = null) Adds a INNER JOIN clause to the query using the Interest relation
 *
 * @method     ChildQuestionQuery joinWithInterest($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Interest relation
 *
 * @method     ChildQuestionQuery leftJoinWithInterest() Adds a LEFT JOIN clause and with to the query using the Interest relation
 * @method     ChildQuestionQuery rightJoinWithInterest() Adds a RIGHT JOIN clause and with to the query using the Interest relation
 * @method     ChildQuestionQuery innerJoinWithInterest() Adds a INNER JOIN clause and with to the query using the Interest relation
 *
 * @method     ChildQuestionQuery leftJoinQuestionTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the QuestionTag relation
 * @method     ChildQuestionQuery rightJoinQuestionTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the QuestionTag relation
 * @method     ChildQuestionQuery innerJoinQuestionTag($relationAlias = null) Adds a INNER JOIN clause to the query using the QuestionTag relation
 *
 * @method     ChildQuestionQuery joinWithQuestionTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the QuestionTag relation
 *
 * @method     ChildQuestionQuery leftJoinWithQuestionTag() Adds a LEFT JOIN clause and with to the query using the QuestionTag relation
 * @method     ChildQuestionQuery rightJoinWithQuestionTag() Adds a RIGHT JOIN clause and with to the query using the QuestionTag relation
 * @method     ChildQuestionQuery innerJoinWithQuestionTag() Adds a INNER JOIN clause and with to the query using the QuestionTag relation
 *
 * @method     ChildQuestionQuery leftJoinReportQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the ReportQuestion relation
 * @method     ChildQuestionQuery rightJoinReportQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ReportQuestion relation
 * @method     ChildQuestionQuery innerJoinReportQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the ReportQuestion relation
 *
 * @method     ChildQuestionQuery joinWithReportQuestion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ReportQuestion relation
 *
 * @method     ChildQuestionQuery leftJoinWithReportQuestion() Adds a LEFT JOIN clause and with to the query using the ReportQuestion relation
 * @method     ChildQuestionQuery rightJoinWithReportQuestion() Adds a RIGHT JOIN clause and with to the query using the ReportQuestion relation
 * @method     ChildQuestionQuery innerJoinWithReportQuestion() Adds a INNER JOIN clause and with to the query using the ReportQuestion relation
 *
 * @method     ChildQuestionQuery leftJoinSearchIndex($relationAlias = null) Adds a LEFT JOIN clause to the query using the SearchIndex relation
 * @method     ChildQuestionQuery rightJoinSearchIndex($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SearchIndex relation
 * @method     ChildQuestionQuery innerJoinSearchIndex($relationAlias = null) Adds a INNER JOIN clause to the query using the SearchIndex relation
 *
 * @method     ChildQuestionQuery joinWithSearchIndex($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SearchIndex relation
 *
 * @method     ChildQuestionQuery leftJoinWithSearchIndex() Adds a LEFT JOIN clause and with to the query using the SearchIndex relation
 * @method     ChildQuestionQuery rightJoinWithSearchIndex() Adds a RIGHT JOIN clause and with to the query using the SearchIndex relation
 * @method     ChildQuestionQuery innerJoinWithSearchIndex() Adds a INNER JOIN clause and with to the query using the SearchIndex relation
 *
 * @method     ChildQuestionQuery leftJoinQuestionI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the QuestionI18n relation
 * @method     ChildQuestionQuery rightJoinQuestionI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the QuestionI18n relation
 * @method     ChildQuestionQuery innerJoinQuestionI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the QuestionI18n relation
 *
 * @method     ChildQuestionQuery joinWithQuestionI18n($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the QuestionI18n relation
 *
 * @method     ChildQuestionQuery leftJoinWithQuestionI18n() Adds a LEFT JOIN clause and with to the query using the QuestionI18n relation
 * @method     ChildQuestionQuery rightJoinWithQuestionI18n() Adds a RIGHT JOIN clause and with to the query using the QuestionI18n relation
 * @method     ChildQuestionQuery innerJoinWithQuestionI18n() Adds a INNER JOIN clause and with to the query using the QuestionI18n relation
 *
 * @method     \App\Entity\UserQuery|\App\Entity\AnswerQuery|\App\Entity\InterestQuery|\App\Entity\QuestionTagQuery|\App\Entity\ReportQuestionQuery|\App\Entity\SearchIndexQuery|\App\Entity\QuestionI18nQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildQuestion findOne(ConnectionInterface $con = null) Return the first ChildQuestion matching the query
 * @method     ChildQuestion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildQuestion matching the query, or a new ChildQuestion object populated from the query conditions when no match is found
 *
 * @method     ChildQuestion findOneById(int $id) Return the first ChildQuestion filtered by the id column
 * @method     ChildQuestion findOneByUserId(int $user_id) Return the first ChildQuestion filtered by the user_id column
 * @method     ChildQuestion findOneByCreatedAt(string $created_at) Return the first ChildQuestion filtered by the created_at column
 * @method     ChildQuestion findOneByUpdatedAt(string $updated_at) Return the first ChildQuestion filtered by the updated_at column
 * @method     ChildQuestion findOneByInterestedUsers(int $interested_users) Return the first ChildQuestion filtered by the interested_users column
 * @method     ChildQuestion findOneByStrippedTitle(string $stripped_title) Return the first ChildQuestion filtered by the stripped_title column
 * @method     ChildQuestion findOneByReports(int $reports) Return the first ChildQuestion filtered by the reports column *

 * @method     ChildQuestion requirePk($key, ConnectionInterface $con = null) Return the ChildQuestion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOne(ConnectionInterface $con = null) Return the first ChildQuestion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildQuestion requireOneById(int $id) Return the first ChildQuestion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByUserId(int $user_id) Return the first ChildQuestion filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByCreatedAt(string $created_at) Return the first ChildQuestion filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByUpdatedAt(string $updated_at) Return the first ChildQuestion filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByInterestedUsers(int $interested_users) Return the first ChildQuestion filtered by the interested_users column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByStrippedTitle(string $stripped_title) Return the first ChildQuestion filtered by the stripped_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildQuestion requireOneByReports(int $reports) Return the first ChildQuestion filtered by the reports column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildQuestion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildQuestion objects based on current ModelCriteria
 * @method     ChildQuestion[]|ObjectCollection findById(int $id) Return ChildQuestion objects filtered by the id column
 * @method     ChildQuestion[]|ObjectCollection findByUserId(int $user_id) Return ChildQuestion objects filtered by the user_id column
 * @method     ChildQuestion[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildQuestion objects filtered by the created_at column
 * @method     ChildQuestion[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildQuestion objects filtered by the updated_at column
 * @method     ChildQuestion[]|ObjectCollection findByInterestedUsers(int $interested_users) Return ChildQuestion objects filtered by the interested_users column
 * @method     ChildQuestion[]|ObjectCollection findByStrippedTitle(string $stripped_title) Return ChildQuestion objects filtered by the stripped_title column
 * @method     ChildQuestion[]|ObjectCollection findByReports(int $reports) Return ChildQuestion objects filtered by the reports column
 * @method     ChildQuestion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class QuestionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Entity\Base\QuestionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'askeet', $modelName = '\\App\\Entity\\Question', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildQuestionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildQuestionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildQuestionQuery) {
            return $criteria;
        }
        $query = new ChildQuestionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildQuestion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(QuestionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = QuestionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildQuestion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id, created_at, updated_at, interested_users, stripped_title, reports FROM ask_question WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildQuestion $obj */
            $obj = new ChildQuestion();
            $obj->hydrate($row);
            QuestionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildQuestion|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QuestionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QuestionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the interested_users column
     *
     * Example usage:
     * <code>
     * $query->filterByInterestedUsers(1234); // WHERE interested_users = 1234
     * $query->filterByInterestedUsers(array(12, 34)); // WHERE interested_users IN (12, 34)
     * $query->filterByInterestedUsers(array('min' => 12)); // WHERE interested_users > 12
     * </code>
     *
     * @param     mixed $interestedUsers The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByInterestedUsers($interestedUsers = null, $comparison = null)
    {
        if (is_array($interestedUsers)) {
            $useMinMax = false;
            if (isset($interestedUsers['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_INTERESTED_USERS, $interestedUsers['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($interestedUsers['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_INTERESTED_USERS, $interestedUsers['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_INTERESTED_USERS, $interestedUsers, $comparison);
    }

    /**
     * Filter the query on the stripped_title column
     *
     * Example usage:
     * <code>
     * $query->filterByStrippedTitle('fooValue');   // WHERE stripped_title = 'fooValue'
     * $query->filterByStrippedTitle('%fooValue%', Criteria::LIKE); // WHERE stripped_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $strippedTitle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByStrippedTitle($strippedTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($strippedTitle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_STRIPPED_TITLE, $strippedTitle, $comparison);
    }

    /**
     * Filter the query on the reports column
     *
     * Example usage:
     * <code>
     * $query->filterByReports(1234); // WHERE reports = 1234
     * $query->filterByReports(array(12, 34)); // WHERE reports IN (12, 34)
     * $query->filterByReports(array('min' => 12)); // WHERE reports > 12
     * </code>
     *
     * @param     mixed $reports The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByReports($reports = null, $comparison = null)
    {
        if (is_array($reports)) {
            $useMinMax = false;
            if (isset($reports['min'])) {
                $this->addUsingAlias(QuestionTableMap::COL_REPORTS, $reports['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reports['max'])) {
                $this->addUsingAlias(QuestionTableMap::COL_REPORTS, $reports['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionTableMap::COL_REPORTS, $reports, $comparison);
    }

    /**
     * Filter the query by a related \App\Entity\User object
     *
     * @param \App\Entity\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \App\Entity\User) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QuestionTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \App\Entity\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\App\Entity\UserQuery');
    }

    /**
     * Filter the query by a related \App\Entity\Answer object
     *
     * @param \App\Entity\Answer|ObjectCollection $answer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByAnswer($answer, $comparison = null)
    {
        if ($answer instanceof \App\Entity\Answer) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $answer->getQuestionId(), $comparison);
        } elseif ($answer instanceof ObjectCollection) {
            return $this
                ->useAnswerQuery()
                ->filterByPrimaryKeys($answer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAnswer() only accepts arguments of type \App\Entity\Answer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Answer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinAnswer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Answer');

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
            $this->addJoinObject($join, 'Answer');
        }

        return $this;
    }

    /**
     * Use the Answer relation Answer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\AnswerQuery A secondary query class using the current class as primary query
     */
    public function useAnswerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAnswer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Answer', '\App\Entity\AnswerQuery');
    }

    /**
     * Filter the query by a related \App\Entity\Interest object
     *
     * @param \App\Entity\Interest|ObjectCollection $interest the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByInterest($interest, $comparison = null)
    {
        if ($interest instanceof \App\Entity\Interest) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $interest->getQuestionId(), $comparison);
        } elseif ($interest instanceof ObjectCollection) {
            return $this
                ->useInterestQuery()
                ->filterByPrimaryKeys($interest->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInterest() only accepts arguments of type \App\Entity\Interest or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Interest relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinInterest($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Interest');

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
            $this->addJoinObject($join, 'Interest');
        }

        return $this;
    }

    /**
     * Use the Interest relation Interest object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\InterestQuery A secondary query class using the current class as primary query
     */
    public function useInterestQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInterest($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Interest', '\App\Entity\InterestQuery');
    }

    /**
     * Filter the query by a related \App\Entity\QuestionTag object
     *
     * @param \App\Entity\QuestionTag|ObjectCollection $questionTag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByQuestionTag($questionTag, $comparison = null)
    {
        if ($questionTag instanceof \App\Entity\QuestionTag) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $questionTag->getQuestionId(), $comparison);
        } elseif ($questionTag instanceof ObjectCollection) {
            return $this
                ->useQuestionTagQuery()
                ->filterByPrimaryKeys($questionTag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQuestionTag() only accepts arguments of type \App\Entity\QuestionTag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the QuestionTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinQuestionTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('QuestionTag');

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
            $this->addJoinObject($join, 'QuestionTag');
        }

        return $this;
    }

    /**
     * Use the QuestionTag relation QuestionTag object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\QuestionTagQuery A secondary query class using the current class as primary query
     */
    public function useQuestionTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuestionTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'QuestionTag', '\App\Entity\QuestionTagQuery');
    }

    /**
     * Filter the query by a related \App\Entity\ReportQuestion object
     *
     * @param \App\Entity\ReportQuestion|ObjectCollection $reportQuestion the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByReportQuestion($reportQuestion, $comparison = null)
    {
        if ($reportQuestion instanceof \App\Entity\ReportQuestion) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $reportQuestion->getQuestionId(), $comparison);
        } elseif ($reportQuestion instanceof ObjectCollection) {
            return $this
                ->useReportQuestionQuery()
                ->filterByPrimaryKeys($reportQuestion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByReportQuestion() only accepts arguments of type \App\Entity\ReportQuestion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ReportQuestion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinReportQuestion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ReportQuestion');

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
            $this->addJoinObject($join, 'ReportQuestion');
        }

        return $this;
    }

    /**
     * Use the ReportQuestion relation ReportQuestion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\ReportQuestionQuery A secondary query class using the current class as primary query
     */
    public function useReportQuestionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinReportQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ReportQuestion', '\App\Entity\ReportQuestionQuery');
    }

    /**
     * Filter the query by a related \App\Entity\SearchIndex object
     *
     * @param \App\Entity\SearchIndex|ObjectCollection $searchIndex the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterBySearchIndex($searchIndex, $comparison = null)
    {
        if ($searchIndex instanceof \App\Entity\SearchIndex) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $searchIndex->getQuestionId(), $comparison);
        } elseif ($searchIndex instanceof ObjectCollection) {
            return $this
                ->useSearchIndexQuery()
                ->filterByPrimaryKeys($searchIndex->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySearchIndex() only accepts arguments of type \App\Entity\SearchIndex or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SearchIndex relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinSearchIndex($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SearchIndex');

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
            $this->addJoinObject($join, 'SearchIndex');
        }

        return $this;
    }

    /**
     * Use the SearchIndex relation SearchIndex object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\SearchIndexQuery A secondary query class using the current class as primary query
     */
    public function useSearchIndexQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSearchIndex($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SearchIndex', '\App\Entity\SearchIndexQuery');
    }

    /**
     * Filter the query by a related \App\Entity\QuestionI18n object
     *
     * @param \App\Entity\QuestionI18n|ObjectCollection $questionI18n the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByQuestionI18n($questionI18n, $comparison = null)
    {
        if ($questionI18n instanceof \App\Entity\QuestionI18n) {
            return $this
                ->addUsingAlias(QuestionTableMap::COL_ID, $questionI18n->getId(), $comparison);
        } elseif ($questionI18n instanceof ObjectCollection) {
            return $this
                ->useQuestionI18nQuery()
                ->filterByPrimaryKeys($questionI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQuestionI18n() only accepts arguments of type \App\Entity\QuestionI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the QuestionI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinQuestionI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('QuestionI18n');

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
            $this->addJoinObject($join, 'QuestionI18n');
        }

        return $this;
    }

    /**
     * Use the QuestionI18n relation QuestionI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\QuestionI18nQuery A secondary query class using the current class as primary query
     */
    public function useQuestionI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinQuestionI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'QuestionI18n', '\App\Entity\QuestionI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildQuestion $question Object to remove from the list of results
     *
     * @return $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function prune($question = null)
    {
        if ($question) {
            $this->addUsingAlias(QuestionTableMap::COL_ID, $question->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the ask_question table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            QuestionTableMap::clearInstancePool();
            QuestionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(QuestionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            QuestionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            QuestionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // l10n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'de-DE'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildQuestionQuery The current query, for fluid interface
     */
    public function joinI18n($locale = null, $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        $relationName = $relationAlias ? $relationAlias : 'QuestionI18n';

        return $this
            ->joinQuestionI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'de-DE'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = null, $joinType = Criteria::LEFT_JOIN)
    {
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('QuestionI18n');
        $this->with['QuestionI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'de-DE'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildQuestionI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = null, $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'QuestionI18n', '\App\Entity\QuestionI18nQuery');
    }

    protected $currentLocale;

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'de-DE'
     *
     * @return    $this|ChildQuestion The current object (for fluent API support)
     */
    public function setLocale($locale)
    {
        $this->currentLocale = PropelL10n::normalize($locale);

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'de-DE'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Filters the query with the
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
         *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByTitle($title , $comparison = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $token = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->useI18nQuery($locale)
            ->filterByTitle($title , $comparison)
        ->endUse();
    }

    /**
     * Finds objects in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->findByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion[]|ObjectCollection The results
     */
    public function findByTitle($title , $comparison = null, $locale = null)
    {
        return $this->filterByTitle($title , $comparison, $locale)
            ->find();
    }

    /**
     * Finds the first object in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->findByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion The result
     */
    public function findOneByTitle($title , $comparison = null, $locale = null)
    {
        return $this->filterByTitle($title , $comparison, $locale)
            ->findOne();
    }

    /**
     * Filters the query with the
     *
     * Example usage:
     * <code>
     * $query->filterByBody('fooValue');   // WHERE body = 'fooValue'
     * $query->filterByBody('%fooValue%'); // WHERE body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $body The value to use as filter.
         *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByBody($body , $comparison = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        if (null === $comparison) {
            if (is_array($body)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $body)) {
                $token = str_replace('*', '%', $body);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->useI18nQuery($locale)
            ->filterByBody($body , $comparison)
        ->endUse();
    }

    /**
     * Finds objects in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByBody('fooValue');   // WHERE body = 'fooValue'
     * $query->findByBody('%fooValue%'); // WHERE body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $body The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion[]|ObjectCollection The results
     */
    public function findByBody($body , $comparison = null, $locale = null)
    {
        return $this->filterByBody($body , $comparison, $locale)
            ->find();
    }

    /**
     * Finds the first object in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByBody('fooValue');   // WHERE body = 'fooValue'
     * $query->findByBody('%fooValue%'); // WHERE body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $body The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion The result
     */
    public function findOneByBody($body , $comparison = null, $locale = null)
    {
        return $this->filterByBody($body , $comparison, $locale)
            ->findOne();
    }

    /**
     * Filters the query with the
     *
     * Example usage:
     * <code>
     * $query->filterByHtmlBody('fooValue');   // WHERE html_body = 'fooValue'
     * $query->filterByHtmlBody('%fooValue%'); // WHERE html_body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $html_body The value to use as filter.
         *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestionQuery The current query, for fluid interface
     */
    public function filterByHtmlBody($html_body , $comparison = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        if (null === $comparison) {
            if (is_array($html_body)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $html_body)) {
                $token = str_replace('*', '%', $html_body);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->useI18nQuery($locale)
            ->filterByHtmlBody($html_body , $comparison)
        ->endUse();
    }

    /**
     * Finds objects in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByHtmlBody('fooValue');   // WHERE html_body = 'fooValue'
     * $query->findByHtmlBody('%fooValue%'); // WHERE html_body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $html_body The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion[]|ObjectCollection The results
     */
    public function findByHtmlBody($html_body , $comparison = null, $locale = null)
    {
        return $this->filterByHtmlBody($html_body , $comparison, $locale)
            ->find();
    }

    /**
     * Finds the first object in the query with the given filter
     *
     * Example usage:
     * <code>
     * $query->findByHtmlBody('fooValue');   // WHERE html_body = 'fooValue'
     * $query->findByHtmlBody('%fooValue%'); // WHERE html_body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $html_body The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     * @param     string $locale Overwrites the locale for this filter
     *
     * @return    ChildQuestion The result
     */
    public function findOneByHtmlBody($html_body , $comparison = null, $locale = null)
    {
        return $this->filterByHtmlBody($html_body , $comparison, $locale)
            ->findOne();
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(QuestionTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuestionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuestionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuestionTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(QuestionTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildQuestionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuestionTableMap::COL_CREATED_AT);
    }

} // QuestionQuery
