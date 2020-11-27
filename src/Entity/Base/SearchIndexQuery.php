<?php

namespace App\Entity\Base;

use \Exception;
use App\Entity\SearchIndex as ChildSearchIndex;
use App\Entity\SearchIndexQuery as ChildSearchIndexQuery;
use App\Entity\Map\SearchIndexTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'ask_search_index' table.
 *
 *
 *
 * @method     ChildSearchIndexQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method     ChildSearchIndexQuery orderByWord($order = Criteria::ASC) Order by the word column
 * @method     ChildSearchIndexQuery orderByWeight($order = Criteria::ASC) Order by the weight column
 *
 * @method     ChildSearchIndexQuery groupByQuestionId() Group by the question_id column
 * @method     ChildSearchIndexQuery groupByWord() Group by the word column
 * @method     ChildSearchIndexQuery groupByWeight() Group by the weight column
 *
 * @method     ChildSearchIndexQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSearchIndexQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSearchIndexQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSearchIndexQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSearchIndexQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSearchIndexQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSearchIndexQuery leftJoinQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Question relation
 * @method     ChildSearchIndexQuery rightJoinQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Question relation
 * @method     ChildSearchIndexQuery innerJoinQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the Question relation
 *
 * @method     ChildSearchIndexQuery joinWithQuestion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Question relation
 *
 * @method     ChildSearchIndexQuery leftJoinWithQuestion() Adds a LEFT JOIN clause and with to the query using the Question relation
 * @method     ChildSearchIndexQuery rightJoinWithQuestion() Adds a RIGHT JOIN clause and with to the query using the Question relation
 * @method     ChildSearchIndexQuery innerJoinWithQuestion() Adds a INNER JOIN clause and with to the query using the Question relation
 *
 * @method     \App\Entity\QuestionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSearchIndex findOne(ConnectionInterface $con = null) Return the first ChildSearchIndex matching the query
 * @method     ChildSearchIndex findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSearchIndex matching the query, or a new ChildSearchIndex object populated from the query conditions when no match is found
 *
 * @method     ChildSearchIndex findOneByQuestionId(int $question_id) Return the first ChildSearchIndex filtered by the question_id column
 * @method     ChildSearchIndex findOneByWord(string $word) Return the first ChildSearchIndex filtered by the word column
 * @method     ChildSearchIndex findOneByWeight(int $weight) Return the first ChildSearchIndex filtered by the weight column *

 * @method     ChildSearchIndex requirePk($key, ConnectionInterface $con = null) Return the ChildSearchIndex by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSearchIndex requireOne(ConnectionInterface $con = null) Return the first ChildSearchIndex matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSearchIndex requireOneByQuestionId(int $question_id) Return the first ChildSearchIndex filtered by the question_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSearchIndex requireOneByWord(string $word) Return the first ChildSearchIndex filtered by the word column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSearchIndex requireOneByWeight(int $weight) Return the first ChildSearchIndex filtered by the weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSearchIndex[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSearchIndex objects based on current ModelCriteria
 * @method     ChildSearchIndex[]|ObjectCollection findByQuestionId(int $question_id) Return ChildSearchIndex objects filtered by the question_id column
 * @method     ChildSearchIndex[]|ObjectCollection findByWord(string $word) Return ChildSearchIndex objects filtered by the word column
 * @method     ChildSearchIndex[]|ObjectCollection findByWeight(int $weight) Return ChildSearchIndex objects filtered by the weight column
 * @method     ChildSearchIndex[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SearchIndexQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Entity\Base\SearchIndexQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'askeet', $modelName = '\\App\\Entity\\SearchIndex', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSearchIndexQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSearchIndexQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSearchIndexQuery) {
            return $criteria;
        }
        $query = new ChildSearchIndexQuery();
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
     * @return ChildSearchIndex|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The SearchIndex object has no primary key');
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        throw new LogicException('The SearchIndex object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The SearchIndex object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The SearchIndex object has no primary key');
    }

    /**
     * Filter the query on the question_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestionId(1234); // WHERE question_id = 1234
     * $query->filterByQuestionId(array(12, 34)); // WHERE question_id IN (12, 34)
     * $query->filterByQuestionId(array('min' => 12)); // WHERE question_id > 12
     * </code>
     *
     * @see       filterByQuestion()
     *
     * @param     mixed $questionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(SearchIndexTableMap::COL_QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(SearchIndexTableMap::COL_QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SearchIndexTableMap::COL_QUESTION_ID, $questionId, $comparison);
    }

    /**
     * Filter the query on the word column
     *
     * Example usage:
     * <code>
     * $query->filterByWord('fooValue');   // WHERE word = 'fooValue'
     * $query->filterByWord('%fooValue%', Criteria::LIKE); // WHERE word LIKE '%fooValue%'
     * </code>
     *
     * @param     string $word The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByWord($word = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($word)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SearchIndexTableMap::COL_WORD, $word, $comparison);
    }

    /**
     * Filter the query on the weight column
     *
     * Example usage:
     * <code>
     * $query->filterByWeight(1234); // WHERE weight = 1234
     * $query->filterByWeight(array(12, 34)); // WHERE weight IN (12, 34)
     * $query->filterByWeight(array('min' => 12)); // WHERE weight > 12
     * </code>
     *
     * @param     mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByWeight($weight = null, $comparison = null)
    {
        if (is_array($weight)) {
            $useMinMax = false;
            if (isset($weight['min'])) {
                $this->addUsingAlias(SearchIndexTableMap::COL_WEIGHT, $weight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weight['max'])) {
                $this->addUsingAlias(SearchIndexTableMap::COL_WEIGHT, $weight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SearchIndexTableMap::COL_WEIGHT, $weight, $comparison);
    }

    /**
     * Filter the query by a related \App\Entity\Question object
     *
     * @param \App\Entity\Question|ObjectCollection $question The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSearchIndexQuery The current query, for fluid interface
     */
    public function filterByQuestion($question, $comparison = null)
    {
        if ($question instanceof \App\Entity\Question) {
            return $this
                ->addUsingAlias(SearchIndexTableMap::COL_QUESTION_ID, $question->getId(), $comparison);
        } elseif ($question instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SearchIndexTableMap::COL_QUESTION_ID, $question->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByQuestion() only accepts arguments of type \App\Entity\Question or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Question relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function joinQuestion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Question');

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
            $this->addJoinObject($join, 'Question');
        }

        return $this;
    }

    /**
     * Use the Question relation Question object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Entity\QuestionQuery A secondary query class using the current class as primary query
     */
    public function useQuestionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Question', '\App\Entity\QuestionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSearchIndex $searchIndex Object to remove from the list of results
     *
     * @return $this|ChildSearchIndexQuery The current query, for fluid interface
     */
    public function prune($searchIndex = null)
    {
        if ($searchIndex) {
            throw new LogicException('SearchIndex object has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the ask_search_index table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SearchIndexTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SearchIndexTableMap::clearInstancePool();
            SearchIndexTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SearchIndexTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SearchIndexTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SearchIndexTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SearchIndexTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SearchIndexQuery
