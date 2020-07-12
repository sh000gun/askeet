<?php

namespace App\Entity\Map;

use App\Entity\Answer;
use App\Entity\AnswerQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'ask_answer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AnswerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'App.Entity.Map.AnswerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'askeet';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'ask_answer';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\App\\Entity\\Answer';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'App.Entity.Answer';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'ask_answer.id';

    /**
     * the column name for the question_id field
     */
    const COL_QUESTION_ID = 'ask_answer.question_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'ask_answer.user_id';

    /**
     * the column name for the body field
     */
    const COL_BODY = 'ask_answer.body';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'ask_answer.created_at';

    /**
     * the column name for the relevancy_up field
     */
    const COL_RELEVANCY_UP = 'ask_answer.relevancy_up';

    /**
     * the column name for the relevancy_down field
     */
    const COL_RELEVANCY_DOWN = 'ask_answer.relevancy_down';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'ask_answer.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'QuestionId', 'UserId', 'Body', 'CreatedAt', 'RelevancyUp', 'RelevancyDown', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'questionId', 'userId', 'body', 'createdAt', 'relevancyUp', 'relevancyDown', 'updatedAt', ),
        self::TYPE_COLNAME       => array(AnswerTableMap::COL_ID, AnswerTableMap::COL_QUESTION_ID, AnswerTableMap::COL_USER_ID, AnswerTableMap::COL_BODY, AnswerTableMap::COL_CREATED_AT, AnswerTableMap::COL_RELEVANCY_UP, AnswerTableMap::COL_RELEVANCY_DOWN, AnswerTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'question_id', 'user_id', 'body', 'created_at', 'relevancy_up', 'relevancy_down', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'QuestionId' => 1, 'UserId' => 2, 'Body' => 3, 'CreatedAt' => 4, 'RelevancyUp' => 5, 'RelevancyDown' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'questionId' => 1, 'userId' => 2, 'body' => 3, 'createdAt' => 4, 'relevancyUp' => 5, 'relevancyDown' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(AnswerTableMap::COL_ID => 0, AnswerTableMap::COL_QUESTION_ID => 1, AnswerTableMap::COL_USER_ID => 2, AnswerTableMap::COL_BODY => 3, AnswerTableMap::COL_CREATED_AT => 4, AnswerTableMap::COL_RELEVANCY_UP => 5, AnswerTableMap::COL_RELEVANCY_DOWN => 6, AnswerTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'question_id' => 1, 'user_id' => 2, 'body' => 3, 'created_at' => 4, 'relevancy_up' => 5, 'relevancy_down' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('ask_answer');
        $this->setPhpName('Answer');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\App\\Entity\\Answer');
        $this->setPackage('App.Entity');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('question_id', 'QuestionId', 'INTEGER', 'ask_question', 'id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'ask_user', 'id', false, null, null);
        $this->addColumn('body', 'Body', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('relevancy_up', 'RelevancyUp', 'INTEGER', false, null, 0);
        $this->addColumn('relevancy_down', 'RelevancyDown', 'INTEGER', false, null, 0);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Question', '\\App\\Entity\\Question', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\App\\Entity\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Relevancy', '\\App\\Entity\\Relevancy', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':answer_id',
    1 => ':id',
  ),
), null, null, 'Relevancies', false);
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? AnswerTableMap::CLASS_DEFAULT : AnswerTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Answer object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AnswerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AnswerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AnswerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AnswerTableMap::OM_CLASS;
            /** @var Answer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AnswerTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = AnswerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AnswerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Answer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AnswerTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(AnswerTableMap::COL_ID);
            $criteria->addSelectColumn(AnswerTableMap::COL_QUESTION_ID);
            $criteria->addSelectColumn(AnswerTableMap::COL_USER_ID);
            $criteria->addSelectColumn(AnswerTableMap::COL_BODY);
            $criteria->addSelectColumn(AnswerTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(AnswerTableMap::COL_RELEVANCY_UP);
            $criteria->addSelectColumn(AnswerTableMap::COL_RELEVANCY_DOWN);
            $criteria->addSelectColumn(AnswerTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.question_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.body');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.relevancy_up');
            $criteria->addSelectColumn($alias . '.relevancy_down');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(AnswerTableMap::DATABASE_NAME)->getTable(AnswerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AnswerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AnswerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AnswerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Answer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Answer object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \App\Entity\Answer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AnswerTableMap::DATABASE_NAME);
            $criteria->add(AnswerTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AnswerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AnswerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AnswerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the ask_answer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AnswerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Answer or Criteria object.
     *
     * @param mixed               $criteria Criteria or Answer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Answer object
        }

        if ($criteria->containsKey(AnswerTableMap::COL_ID) && $criteria->keyContainsValue(AnswerTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AnswerTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AnswerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AnswerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AnswerTableMap::buildTableMap();
