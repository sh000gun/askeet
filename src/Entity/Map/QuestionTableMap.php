<?php

namespace App\Entity\Map;

use App\Entity\Question;
use App\Entity\QuestionQuery;
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
 * This class defines the structure of the 'ask_question' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class QuestionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'App.Entity.Map.QuestionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'askeet';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'ask_question';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\App\\Entity\\Question';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'App.Entity.Question';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'ask_question.id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'ask_question.user_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'ask_question.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'ask_question.updated_at';

    /**
     * the column name for the interested_users field
     */
    const COL_INTERESTED_USERS = 'ask_question.interested_users';

    /**
     * the column name for the stripped_title field
     */
    const COL_STRIPPED_TITLE = 'ask_question.stripped_title';

    /**
     * the column name for the reports field
     */
    const COL_REPORTS = 'ask_question.reports';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'CreatedAt', 'UpdatedAt', 'InterestedUsers', 'StrippedTitle', 'Reports', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'createdAt', 'updatedAt', 'interestedUsers', 'strippedTitle', 'reports', ),
        self::TYPE_COLNAME       => array(QuestionTableMap::COL_ID, QuestionTableMap::COL_USER_ID, QuestionTableMap::COL_CREATED_AT, QuestionTableMap::COL_UPDATED_AT, QuestionTableMap::COL_INTERESTED_USERS, QuestionTableMap::COL_STRIPPED_TITLE, QuestionTableMap::COL_REPORTS, ),
        self::TYPE_FIELDNAME     => array('id', 'user_id', 'created_at', 'updated_at', 'interested_users', 'stripped_title', 'reports', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'CreatedAt' => 2, 'UpdatedAt' => 3, 'InterestedUsers' => 4, 'StrippedTitle' => 5, 'Reports' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'createdAt' => 2, 'updatedAt' => 3, 'interestedUsers' => 4, 'strippedTitle' => 5, 'reports' => 6, ),
        self::TYPE_COLNAME       => array(QuestionTableMap::COL_ID => 0, QuestionTableMap::COL_USER_ID => 1, QuestionTableMap::COL_CREATED_AT => 2, QuestionTableMap::COL_UPDATED_AT => 3, QuestionTableMap::COL_INTERESTED_USERS => 4, QuestionTableMap::COL_STRIPPED_TITLE => 5, QuestionTableMap::COL_REPORTS => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_id' => 1, 'created_at' => 2, 'updated_at' => 3, 'interested_users' => 4, 'stripped_title' => 5, 'reports' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('ask_question');
        $this->setPhpName('Question');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\App\\Entity\\Question');
        $this->setPackage('App.Entity');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'ask_user', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('interested_users', 'InterestedUsers', 'INTEGER', false, null, 0);
        $this->addColumn('stripped_title', 'StrippedTitle', 'VARCHAR', false, 255, null);
        $this->addColumn('reports', 'Reports', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\App\\Entity\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Answer', '\\App\\Entity\\Answer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Answers', false);
        $this->addRelation('Interest', '\\App\\Entity\\Interest', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Interests', false);
        $this->addRelation('QuestionTag', '\\App\\Entity\\QuestionTag', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'QuestionTags', false);
        $this->addRelation('ReportQuestion', '\\App\\Entity\\ReportQuestion', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'ReportQuestions', false);
        $this->addRelation('SearchIndex', '\\App\\Entity\\SearchIndex', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':question_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'SearchIndices', false);
        $this->addRelation('QuestionI18n', '\\App\\Entity\\QuestionI18n', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, 'QuestionI18ns', false);
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
            'l10n' => array('i18n_table' => '%TABLE%_i18n', 'i18n_phpname' => '%PHPNAME%I18n', 'i18n_columns' => 'title, body, html_body', 'i18n_pk_column' => '', 'locale_column' => 'locale', 'locale_length' => '76', 'locale_alias' => '', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to ask_question     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        AnswerTableMap::clearInstancePool();
        InterestTableMap::clearInstancePool();
        QuestionTagTableMap::clearInstancePool();
        ReportQuestionTableMap::clearInstancePool();
        SearchIndexTableMap::clearInstancePool();
        QuestionI18nTableMap::clearInstancePool();
    }

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
        return $withPrefix ? QuestionTableMap::CLASS_DEFAULT : QuestionTableMap::OM_CLASS;
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
     * @return array           (Question object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = QuestionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = QuestionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + QuestionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = QuestionTableMap::OM_CLASS;
            /** @var Question $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            QuestionTableMap::addInstanceToPool($obj, $key);
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
            $key = QuestionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = QuestionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Question $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                QuestionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(QuestionTableMap::COL_ID);
            $criteria->addSelectColumn(QuestionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(QuestionTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(QuestionTableMap::COL_UPDATED_AT);
            $criteria->addSelectColumn(QuestionTableMap::COL_INTERESTED_USERS);
            $criteria->addSelectColumn(QuestionTableMap::COL_STRIPPED_TITLE);
            $criteria->addSelectColumn(QuestionTableMap::COL_REPORTS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.interested_users');
            $criteria->addSelectColumn($alias . '.stripped_title');
            $criteria->addSelectColumn($alias . '.reports');
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
        return Propel::getServiceContainer()->getDatabaseMap(QuestionTableMap::DATABASE_NAME)->getTable(QuestionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(QuestionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(QuestionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new QuestionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Question or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Question object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \App\Entity\Question) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(QuestionTableMap::DATABASE_NAME);
            $criteria->add(QuestionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = QuestionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            QuestionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                QuestionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the ask_question table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return QuestionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Question or Criteria object.
     *
     * @param mixed               $criteria Criteria or Question object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Question object
        }

        if ($criteria->containsKey(QuestionTableMap::COL_ID) && $criteria->keyContainsValue(QuestionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.QuestionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = QuestionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // QuestionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
QuestionTableMap::buildTableMap();
