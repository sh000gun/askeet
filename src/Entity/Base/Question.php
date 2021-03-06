<?php

namespace App\Entity\Base;

use \DateTime;
use \Exception;
use \PDO;
use App\Entity\Answer as ChildAnswer;
use App\Entity\AnswerQuery as ChildAnswerQuery;
use App\Entity\Interest as ChildInterest;
use App\Entity\InterestQuery as ChildInterestQuery;
use App\Entity\Question as ChildQuestion;
use App\Entity\QuestionI18n as ChildQuestionI18n;
use App\Entity\QuestionI18nQuery as ChildQuestionI18nQuery;
use App\Entity\QuestionQuery as ChildQuestionQuery;
use App\Entity\QuestionTag as ChildQuestionTag;
use App\Entity\QuestionTagQuery as ChildQuestionTagQuery;
use App\Entity\ReportQuestion as ChildReportQuestion;
use App\Entity\ReportQuestionQuery as ChildReportQuestionQuery;
use App\Entity\SearchIndex as ChildSearchIndex;
use App\Entity\SearchIndexQuery as ChildSearchIndexQuery;
use App\Entity\User as ChildUser;
use App\Entity\UserQuery as ChildUserQuery;
use App\Entity\Map\AnswerTableMap;
use App\Entity\Map\InterestTableMap;
use App\Entity\Map\QuestionI18nTableMap;
use App\Entity\Map\QuestionTableMap;
use App\Entity\Map\QuestionTagTableMap;
use App\Entity\Map\ReportQuestionTableMap;
use App\Entity\Map\SearchIndexTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use gossi\propel\behavior\l10n\PropelL10n;

/**
 * Base class that represents a row from the 'ask_question' table.
 *
 *
 *
 * @package    propel.generator.App.Entity.Base
 */
abstract class Question implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\App\\Entity\\Map\\QuestionTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * The value for the interested_users field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $interested_users;

    /**
     * The value for the stripped_title field.
     *
     * @var        string
     */
    protected $stripped_title;

    /**
     * The value for the reports field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $reports;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildAnswer[] Collection to store aggregation of ChildAnswer objects.
     */
    protected $collAnswers;
    protected $collAnswersPartial;

    /**
     * @var        ObjectCollection|ChildInterest[] Collection to store aggregation of ChildInterest objects.
     */
    protected $collInterests;
    protected $collInterestsPartial;

    /**
     * @var        ObjectCollection|ChildQuestionTag[] Collection to store aggregation of ChildQuestionTag objects.
     */
    protected $collQuestionTags;
    protected $collQuestionTagsPartial;

    /**
     * @var        ObjectCollection|ChildReportQuestion[] Collection to store aggregation of ChildReportQuestion objects.
     */
    protected $collReportQuestions;
    protected $collReportQuestionsPartial;

    /**
     * @var        ObjectCollection|ChildSearchIndex[] Collection to store aggregation of ChildSearchIndex objects.
     */
    protected $collSearchIndices;
    protected $collSearchIndicesPartial;

    /**
     * @var        ObjectCollection|ChildQuestionI18n[] Collection to store aggregation of ChildQuestionI18n objects.
     */
    protected $collQuestionI18ns;
    protected $collQuestionI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // l10n behavior
    protected $currentLocale;

    /**
     * Current translation objects
     * @var        array[ChildQuestionI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAnswer[]
     */
    protected $answersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInterest[]
     */
    protected $interestsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildQuestionTag[]
     */
    protected $questionTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildReportQuestion[]
     */
    protected $reportQuestionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSearchIndex[]
     */
    protected $searchIndicesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildQuestionI18n[]
     */
    protected $questionI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->interested_users = 0;
        $this->reports = 0;
    }

    /**
     * Initializes internal state of App\Entity\Base\Question object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Question</code> instance.  If
     * <code>obj</code> is an instance of <code>Question</code>, delegates to
     * <code>equals(Question)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Get the [interested_users] column value.
     *
     * @return int
     */
    public function getInterestedUsers()
    {
        return $this->interested_users;
    }

    /**
     * Get the [stripped_title] column value.
     *
     * @return string
     */
    public function getStrippedTitle()
    {
        return $this->stripped_title;
    }

    /**
     * Get the [reports] column value.
     *
     * @return int
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[QuestionTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int|null $v New value
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[QuestionTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[QuestionTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[QuestionTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [interested_users] column.
     *
     * @param int|null $v New value
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setInterestedUsers($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->interested_users !== $v) {
            $this->interested_users = $v;
            $this->modifiedColumns[QuestionTableMap::COL_INTERESTED_USERS] = true;
        }

        return $this;
    } // setInterestedUsers()

    /**
     * Set the value of [stripped_title] column.
     *
     * @param string|null $v New value
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setStrippedTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stripped_title !== $v) {
            $this->stripped_title = $v;
            $this->modifiedColumns[QuestionTableMap::COL_STRIPPED_TITLE] = true;
        }

        return $this;
    } // setStrippedTitle()

    /**
     * Set the value of [reports] column.
     *
     * @param int|null $v New value
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function setReports($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reports !== $v) {
            $this->reports = $v;
            $this->modifiedColumns[QuestionTableMap::COL_REPORTS] = true;
        }

        return $this;
    } // setReports()

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
            if ($this->interested_users !== 0) {
                return false;
            }

            if ($this->reports !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
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
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : QuestionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : QuestionTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : QuestionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : QuestionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : QuestionTableMap::translateFieldName('InterestedUsers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->interested_users = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : QuestionTableMap::translateFieldName('StrippedTitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stripped_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : QuestionTableMap::translateFieldName('Reports', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reports = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = QuestionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\App\\Entity\\Question'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(QuestionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildQuestionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collAnswers = null;

            $this->collInterests = null;

            $this->collQuestionTags = null;

            $this->collReportQuestions = null;

            $this->collSearchIndices = null;

            $this->collQuestionI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Question::setDeleted()
     * @see Question::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildQuestionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(QuestionTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(QuestionTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(QuestionTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                QuestionTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->answersScheduledForDeletion !== null) {
                if (!$this->answersScheduledForDeletion->isEmpty()) {
                    \App\Entity\AnswerQuery::create()
                        ->filterByPrimaryKeys($this->answersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->answersScheduledForDeletion = null;
                }
            }

            if ($this->collAnswers !== null) {
                foreach ($this->collAnswers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->interestsScheduledForDeletion !== null) {
                if (!$this->interestsScheduledForDeletion->isEmpty()) {
                    \App\Entity\InterestQuery::create()
                        ->filterByPrimaryKeys($this->interestsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->interestsScheduledForDeletion = null;
                }
            }

            if ($this->collInterests !== null) {
                foreach ($this->collInterests as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->questionTagsScheduledForDeletion !== null) {
                if (!$this->questionTagsScheduledForDeletion->isEmpty()) {
                    \App\Entity\QuestionTagQuery::create()
                        ->filterByPrimaryKeys($this->questionTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->questionTagsScheduledForDeletion = null;
                }
            }

            if ($this->collQuestionTags !== null) {
                foreach ($this->collQuestionTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->reportQuestionsScheduledForDeletion !== null) {
                if (!$this->reportQuestionsScheduledForDeletion->isEmpty()) {
                    \App\Entity\ReportQuestionQuery::create()
                        ->filterByPrimaryKeys($this->reportQuestionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->reportQuestionsScheduledForDeletion = null;
                }
            }

            if ($this->collReportQuestions !== null) {
                foreach ($this->collReportQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->searchIndicesScheduledForDeletion !== null) {
                if (!$this->searchIndicesScheduledForDeletion->isEmpty()) {
                    \App\Entity\SearchIndexQuery::create()
                        ->filterByPrimaryKeys($this->searchIndicesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->searchIndicesScheduledForDeletion = null;
                }
            }

            if ($this->collSearchIndices !== null) {
                foreach ($this->collSearchIndices as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->questionI18nsScheduledForDeletion !== null) {
                if (!$this->questionI18nsScheduledForDeletion->isEmpty()) {
                    \App\Entity\QuestionI18nQuery::create()
                        ->filterByPrimaryKeys($this->questionI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->questionI18nsScheduledForDeletion = null;
                }
            }

            if ($this->collQuestionI18ns !== null) {
                foreach ($this->collQuestionI18ns as $referrerFK) {
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
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[QuestionTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . QuestionTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(QuestionTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_INTERESTED_USERS)) {
            $modifiedColumns[':p' . $index++]  = 'interested_users';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_STRIPPED_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'stripped_title';
        }
        if ($this->isColumnModified(QuestionTableMap::COL_REPORTS)) {
            $modifiedColumns[':p' . $index++]  = 'reports';
        }

        $sql = sprintf(
            'INSERT INTO ask_question (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'interested_users':
                        $stmt->bindValue($identifier, $this->interested_users, PDO::PARAM_INT);
                        break;
                    case 'stripped_title':
                        $stmt->bindValue($identifier, $this->stripped_title, PDO::PARAM_STR);
                        break;
                    case 'reports':
                        $stmt->bindValue($identifier, $this->reports, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = QuestionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUserId();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
                return $this->getUpdatedAt();
                break;
            case 4:
                return $this->getInterestedUsers();
                break;
            case 5:
                return $this->getStrippedTitle();
                break;
            case 6:
                return $this->getReports();
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
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Question'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Question'][$this->hashCode()] = true;
        $keys = QuestionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
            $keys[4] => $this->getInterestedUsers(),
            $keys[5] => $this->getStrippedTitle(),
            $keys[6] => $this->getReports(),
        );
        if ($result[$keys[2]] instanceof \DateTimeInterface) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collAnswers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'answers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_answers';
                        break;
                    default:
                        $key = 'Answers';
                }

                $result[$key] = $this->collAnswers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInterests) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'interests';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_interests';
                        break;
                    default:
                        $key = 'Interests';
                }

                $result[$key] = $this->collInterests->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQuestionTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'questionTags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_question_tags';
                        break;
                    default:
                        $key = 'QuestionTags';
                }

                $result[$key] = $this->collQuestionTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collReportQuestions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'reportQuestions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_report_questions';
                        break;
                    default:
                        $key = 'ReportQuestions';
                }

                $result[$key] = $this->collReportQuestions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSearchIndices) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'searchIndices';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_search_indices';
                        break;
                    default:
                        $key = 'SearchIndices';
                }

                $result[$key] = $this->collSearchIndices->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQuestionI18ns) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'questionI18ns';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_question_i18ns';
                        break;
                    default:
                        $key = 'QuestionI18ns';
                }

                $result[$key] = $this->collQuestionI18ns->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\App\Entity\Question
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = QuestionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\App\Entity\Question
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUserId($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
                $this->setUpdatedAt($value);
                break;
            case 4:
                $this->setInterestedUsers($value);
                break;
            case 5:
                $this->setStrippedTitle($value);
                break;
            case 6:
                $this->setReports($value);
                break;
        } // switch()

        return $this;
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
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = QuestionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUserId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCreatedAt($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUpdatedAt($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setInterestedUsers($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setStrippedTitle($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setReports($arr[$keys[6]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\App\Entity\Question The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(QuestionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(QuestionTableMap::COL_ID)) {
            $criteria->add(QuestionTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_USER_ID)) {
            $criteria->add(QuestionTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_CREATED_AT)) {
            $criteria->add(QuestionTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_UPDATED_AT)) {
            $criteria->add(QuestionTableMap::COL_UPDATED_AT, $this->updated_at);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_INTERESTED_USERS)) {
            $criteria->add(QuestionTableMap::COL_INTERESTED_USERS, $this->interested_users);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_STRIPPED_TITLE)) {
            $criteria->add(QuestionTableMap::COL_STRIPPED_TITLE, $this->stripped_title);
        }
        if ($this->isColumnModified(QuestionTableMap::COL_REPORTS)) {
            $criteria->add(QuestionTableMap::COL_REPORTS, $this->reports);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildQuestionQuery::create();
        $criteria->add(QuestionTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \App\Entity\Question (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setInterestedUsers($this->getInterestedUsers());
        $copyObj->setStrippedTitle($this->getStrippedTitle());
        $copyObj->setReports($this->getReports());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAnswers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAnswer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInterests() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInterest($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQuestionTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuestionTag($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getReportQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReportQuestion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSearchIndices() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSearchIndex($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQuestionI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuestionI18n($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \App\Entity\Question Clone of current object.
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addQuestions($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Answer' === $relationName) {
            $this->initAnswers();
            return;
        }
        if ('Interest' === $relationName) {
            $this->initInterests();
            return;
        }
        if ('QuestionTag' === $relationName) {
            $this->initQuestionTags();
            return;
        }
        if ('ReportQuestion' === $relationName) {
            $this->initReportQuestions();
            return;
        }
        if ('SearchIndex' === $relationName) {
            $this->initSearchIndices();
            return;
        }
        if ('QuestionI18n' === $relationName) {
            $this->initQuestionI18ns();
            return;
        }
    }

    /**
     * Clears out the collAnswers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAnswers()
     */
    public function clearAnswers()
    {
        $this->collAnswers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAnswers collection loaded partially.
     */
    public function resetPartialAnswers($v = true)
    {
        $this->collAnswersPartial = $v;
    }

    /**
     * Initializes the collAnswers collection.
     *
     * By default this just sets the collAnswers collection to an empty array (like clearcollAnswers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAnswers($overrideExisting = true)
    {
        if (null !== $this->collAnswers && !$overrideExisting) {
            return;
        }

        $collectionClassName = AnswerTableMap::getTableMap()->getCollectionClassName();

        $this->collAnswers = new $collectionClassName;
        $this->collAnswers->setModel('\App\Entity\Answer');
    }

    /**
     * Gets an array of ChildAnswer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAnswer[] List of ChildAnswer objects
     * @throws PropelException
     */
    public function getAnswers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAnswersPartial && !$this->isNew();
        if (null === $this->collAnswers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collAnswers) {
                    $this->initAnswers();
                } else {
                    $collectionClassName = AnswerTableMap::getTableMap()->getCollectionClassName();

                    $collAnswers = new $collectionClassName;
                    $collAnswers->setModel('\App\Entity\Answer');

                    return $collAnswers;
                }
            } else {
                $collAnswers = ChildAnswerQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAnswersPartial && count($collAnswers)) {
                        $this->initAnswers(false);

                        foreach ($collAnswers as $obj) {
                            if (false == $this->collAnswers->contains($obj)) {
                                $this->collAnswers->append($obj);
                            }
                        }

                        $this->collAnswersPartial = true;
                    }

                    return $collAnswers;
                }

                if ($partial && $this->collAnswers) {
                    foreach ($this->collAnswers as $obj) {
                        if ($obj->isNew()) {
                            $collAnswers[] = $obj;
                        }
                    }
                }

                $this->collAnswers = $collAnswers;
                $this->collAnswersPartial = false;
            }
        }

        return $this->collAnswers;
    }

    /**
     * Sets a collection of ChildAnswer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $answers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setAnswers(Collection $answers, ConnectionInterface $con = null)
    {
        /** @var ChildAnswer[] $answersToDelete */
        $answersToDelete = $this->getAnswers(new Criteria(), $con)->diff($answers);


        $this->answersScheduledForDeletion = $answersToDelete;

        foreach ($answersToDelete as $answerRemoved) {
            $answerRemoved->setQuestion(null);
        }

        $this->collAnswers = null;
        foreach ($answers as $answer) {
            $this->addAnswer($answer);
        }

        $this->collAnswers = $answers;
        $this->collAnswersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Answer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Answer objects.
     * @throws PropelException
     */
    public function countAnswers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAnswersPartial && !$this->isNew();
        if (null === $this->collAnswers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAnswers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAnswers());
            }

            $query = ChildAnswerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collAnswers);
    }

    /**
     * Method called to associate a ChildAnswer object to this object
     * through the ChildAnswer foreign key attribute.
     *
     * @param  ChildAnswer $l ChildAnswer
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addAnswer(ChildAnswer $l)
    {
        if ($this->collAnswers === null) {
            $this->initAnswers();
            $this->collAnswersPartial = true;
        }

        if (!$this->collAnswers->contains($l)) {
            $this->doAddAnswer($l);

            if ($this->answersScheduledForDeletion and $this->answersScheduledForDeletion->contains($l)) {
                $this->answersScheduledForDeletion->remove($this->answersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAnswer $answer The ChildAnswer object to add.
     */
    protected function doAddAnswer(ChildAnswer $answer)
    {
        $this->collAnswers[]= $answer;
        $answer->setQuestion($this);
    }

    /**
     * @param  ChildAnswer $answer The ChildAnswer object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeAnswer(ChildAnswer $answer)
    {
        if ($this->getAnswers()->contains($answer)) {
            $pos = $this->collAnswers->search($answer);
            $this->collAnswers->remove($pos);
            if (null === $this->answersScheduledForDeletion) {
                $this->answersScheduledForDeletion = clone $this->collAnswers;
                $this->answersScheduledForDeletion->clear();
            }
            $this->answersScheduledForDeletion[]= $answer;
            $answer->setQuestion(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Question is new, it will return
     * an empty collection; or if this Question has previously
     * been saved, it will retrieve related Answers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Question.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAnswer[] List of ChildAnswer objects
     */
    public function getAnswersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAnswerQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getAnswers($query, $con);
    }

    /**
     * Clears out the collInterests collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInterests()
     */
    public function clearInterests()
    {
        $this->collInterests = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInterests collection loaded partially.
     */
    public function resetPartialInterests($v = true)
    {
        $this->collInterestsPartial = $v;
    }

    /**
     * Initializes the collInterests collection.
     *
     * By default this just sets the collInterests collection to an empty array (like clearcollInterests());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInterests($overrideExisting = true)
    {
        if (null !== $this->collInterests && !$overrideExisting) {
            return;
        }

        $collectionClassName = InterestTableMap::getTableMap()->getCollectionClassName();

        $this->collInterests = new $collectionClassName;
        $this->collInterests->setModel('\App\Entity\Interest');
    }

    /**
     * Gets an array of ChildInterest objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInterest[] List of ChildInterest objects
     * @throws PropelException
     */
    public function getInterests(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInterestsPartial && !$this->isNew();
        if (null === $this->collInterests || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collInterests) {
                    $this->initInterests();
                } else {
                    $collectionClassName = InterestTableMap::getTableMap()->getCollectionClassName();

                    $collInterests = new $collectionClassName;
                    $collInterests->setModel('\App\Entity\Interest');

                    return $collInterests;
                }
            } else {
                $collInterests = ChildInterestQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInterestsPartial && count($collInterests)) {
                        $this->initInterests(false);

                        foreach ($collInterests as $obj) {
                            if (false == $this->collInterests->contains($obj)) {
                                $this->collInterests->append($obj);
                            }
                        }

                        $this->collInterestsPartial = true;
                    }

                    return $collInterests;
                }

                if ($partial && $this->collInterests) {
                    foreach ($this->collInterests as $obj) {
                        if ($obj->isNew()) {
                            $collInterests[] = $obj;
                        }
                    }
                }

                $this->collInterests = $collInterests;
                $this->collInterestsPartial = false;
            }
        }

        return $this->collInterests;
    }

    /**
     * Sets a collection of ChildInterest objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $interests A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setInterests(Collection $interests, ConnectionInterface $con = null)
    {
        /** @var ChildInterest[] $interestsToDelete */
        $interestsToDelete = $this->getInterests(new Criteria(), $con)->diff($interests);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->interestsScheduledForDeletion = clone $interestsToDelete;

        foreach ($interestsToDelete as $interestRemoved) {
            $interestRemoved->setQuestion(null);
        }

        $this->collInterests = null;
        foreach ($interests as $interest) {
            $this->addInterest($interest);
        }

        $this->collInterests = $interests;
        $this->collInterestsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Interest objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Interest objects.
     * @throws PropelException
     */
    public function countInterests(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInterestsPartial && !$this->isNew();
        if (null === $this->collInterests || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInterests) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInterests());
            }

            $query = ChildInterestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collInterests);
    }

    /**
     * Method called to associate a ChildInterest object to this object
     * through the ChildInterest foreign key attribute.
     *
     * @param  ChildInterest $l ChildInterest
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addInterest(ChildInterest $l)
    {
        if ($this->collInterests === null) {
            $this->initInterests();
            $this->collInterestsPartial = true;
        }

        if (!$this->collInterests->contains($l)) {
            $this->doAddInterest($l);

            if ($this->interestsScheduledForDeletion and $this->interestsScheduledForDeletion->contains($l)) {
                $this->interestsScheduledForDeletion->remove($this->interestsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInterest $interest The ChildInterest object to add.
     */
    protected function doAddInterest(ChildInterest $interest)
    {
        $this->collInterests[]= $interest;
        $interest->setQuestion($this);
    }

    /**
     * @param  ChildInterest $interest The ChildInterest object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeInterest(ChildInterest $interest)
    {
        if ($this->getInterests()->contains($interest)) {
            $pos = $this->collInterests->search($interest);
            $this->collInterests->remove($pos);
            if (null === $this->interestsScheduledForDeletion) {
                $this->interestsScheduledForDeletion = clone $this->collInterests;
                $this->interestsScheduledForDeletion->clear();
            }
            $this->interestsScheduledForDeletion[]= clone $interest;
            $interest->setQuestion(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Question is new, it will return
     * an empty collection; or if this Question has previously
     * been saved, it will retrieve related Interests from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Question.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInterest[] List of ChildInterest objects
     */
    public function getInterestsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInterestQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getInterests($query, $con);
    }

    /**
     * Clears out the collQuestionTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuestionTags()
     */
    public function clearQuestionTags()
    {
        $this->collQuestionTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collQuestionTags collection loaded partially.
     */
    public function resetPartialQuestionTags($v = true)
    {
        $this->collQuestionTagsPartial = $v;
    }

    /**
     * Initializes the collQuestionTags collection.
     *
     * By default this just sets the collQuestionTags collection to an empty array (like clearcollQuestionTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuestionTags($overrideExisting = true)
    {
        if (null !== $this->collQuestionTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = QuestionTagTableMap::getTableMap()->getCollectionClassName();

        $this->collQuestionTags = new $collectionClassName;
        $this->collQuestionTags->setModel('\App\Entity\QuestionTag');
    }

    /**
     * Gets an array of ChildQuestionTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildQuestionTag[] List of ChildQuestionTag objects
     * @throws PropelException
     */
    public function getQuestionTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionTagsPartial && !$this->isNew();
        if (null === $this->collQuestionTags || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collQuestionTags) {
                    $this->initQuestionTags();
                } else {
                    $collectionClassName = QuestionTagTableMap::getTableMap()->getCollectionClassName();

                    $collQuestionTags = new $collectionClassName;
                    $collQuestionTags->setModel('\App\Entity\QuestionTag');

                    return $collQuestionTags;
                }
            } else {
                $collQuestionTags = ChildQuestionTagQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collQuestionTagsPartial && count($collQuestionTags)) {
                        $this->initQuestionTags(false);

                        foreach ($collQuestionTags as $obj) {
                            if (false == $this->collQuestionTags->contains($obj)) {
                                $this->collQuestionTags->append($obj);
                            }
                        }

                        $this->collQuestionTagsPartial = true;
                    }

                    return $collQuestionTags;
                }

                if ($partial && $this->collQuestionTags) {
                    foreach ($this->collQuestionTags as $obj) {
                        if ($obj->isNew()) {
                            $collQuestionTags[] = $obj;
                        }
                    }
                }

                $this->collQuestionTags = $collQuestionTags;
                $this->collQuestionTagsPartial = false;
            }
        }

        return $this->collQuestionTags;
    }

    /**
     * Sets a collection of ChildQuestionTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $questionTags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setQuestionTags(Collection $questionTags, ConnectionInterface $con = null)
    {
        /** @var ChildQuestionTag[] $questionTagsToDelete */
        $questionTagsToDelete = $this->getQuestionTags(new Criteria(), $con)->diff($questionTags);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->questionTagsScheduledForDeletion = clone $questionTagsToDelete;

        foreach ($questionTagsToDelete as $questionTagRemoved) {
            $questionTagRemoved->setQuestion(null);
        }

        $this->collQuestionTags = null;
        foreach ($questionTags as $questionTag) {
            $this->addQuestionTag($questionTag);
        }

        $this->collQuestionTags = $questionTags;
        $this->collQuestionTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related QuestionTag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related QuestionTag objects.
     * @throws PropelException
     */
    public function countQuestionTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionTagsPartial && !$this->isNew();
        if (null === $this->collQuestionTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuestionTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQuestionTags());
            }

            $query = ChildQuestionTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collQuestionTags);
    }

    /**
     * Method called to associate a ChildQuestionTag object to this object
     * through the ChildQuestionTag foreign key attribute.
     *
     * @param  ChildQuestionTag $l ChildQuestionTag
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addQuestionTag(ChildQuestionTag $l)
    {
        if ($this->collQuestionTags === null) {
            $this->initQuestionTags();
            $this->collQuestionTagsPartial = true;
        }

        if (!$this->collQuestionTags->contains($l)) {
            $this->doAddQuestionTag($l);

            if ($this->questionTagsScheduledForDeletion and $this->questionTagsScheduledForDeletion->contains($l)) {
                $this->questionTagsScheduledForDeletion->remove($this->questionTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildQuestionTag $questionTag The ChildQuestionTag object to add.
     */
    protected function doAddQuestionTag(ChildQuestionTag $questionTag)
    {
        $this->collQuestionTags[]= $questionTag;
        $questionTag->setQuestion($this);
    }

    /**
     * @param  ChildQuestionTag $questionTag The ChildQuestionTag object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeQuestionTag(ChildQuestionTag $questionTag)
    {
        if ($this->getQuestionTags()->contains($questionTag)) {
            $pos = $this->collQuestionTags->search($questionTag);
            $this->collQuestionTags->remove($pos);
            if (null === $this->questionTagsScheduledForDeletion) {
                $this->questionTagsScheduledForDeletion = clone $this->collQuestionTags;
                $this->questionTagsScheduledForDeletion->clear();
            }
            $this->questionTagsScheduledForDeletion[]= clone $questionTag;
            $questionTag->setQuestion(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Question is new, it will return
     * an empty collection; or if this Question has previously
     * been saved, it will retrieve related QuestionTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Question.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildQuestionTag[] List of ChildQuestionTag objects
     */
    public function getQuestionTagsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildQuestionTagQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getQuestionTags($query, $con);
    }

    /**
     * Clears out the collReportQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addReportQuestions()
     */
    public function clearReportQuestions()
    {
        $this->collReportQuestions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collReportQuestions collection loaded partially.
     */
    public function resetPartialReportQuestions($v = true)
    {
        $this->collReportQuestionsPartial = $v;
    }

    /**
     * Initializes the collReportQuestions collection.
     *
     * By default this just sets the collReportQuestions collection to an empty array (like clearcollReportQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReportQuestions($overrideExisting = true)
    {
        if (null !== $this->collReportQuestions && !$overrideExisting) {
            return;
        }

        $collectionClassName = ReportQuestionTableMap::getTableMap()->getCollectionClassName();

        $this->collReportQuestions = new $collectionClassName;
        $this->collReportQuestions->setModel('\App\Entity\ReportQuestion');
    }

    /**
     * Gets an array of ChildReportQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildReportQuestion[] List of ChildReportQuestion objects
     * @throws PropelException
     */
    public function getReportQuestions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collReportQuestionsPartial && !$this->isNew();
        if (null === $this->collReportQuestions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collReportQuestions) {
                    $this->initReportQuestions();
                } else {
                    $collectionClassName = ReportQuestionTableMap::getTableMap()->getCollectionClassName();

                    $collReportQuestions = new $collectionClassName;
                    $collReportQuestions->setModel('\App\Entity\ReportQuestion');

                    return $collReportQuestions;
                }
            } else {
                $collReportQuestions = ChildReportQuestionQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collReportQuestionsPartial && count($collReportQuestions)) {
                        $this->initReportQuestions(false);

                        foreach ($collReportQuestions as $obj) {
                            if (false == $this->collReportQuestions->contains($obj)) {
                                $this->collReportQuestions->append($obj);
                            }
                        }

                        $this->collReportQuestionsPartial = true;
                    }

                    return $collReportQuestions;
                }

                if ($partial && $this->collReportQuestions) {
                    foreach ($this->collReportQuestions as $obj) {
                        if ($obj->isNew()) {
                            $collReportQuestions[] = $obj;
                        }
                    }
                }

                $this->collReportQuestions = $collReportQuestions;
                $this->collReportQuestionsPartial = false;
            }
        }

        return $this->collReportQuestions;
    }

    /**
     * Sets a collection of ChildReportQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $reportQuestions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setReportQuestions(Collection $reportQuestions, ConnectionInterface $con = null)
    {
        /** @var ChildReportQuestion[] $reportQuestionsToDelete */
        $reportQuestionsToDelete = $this->getReportQuestions(new Criteria(), $con)->diff($reportQuestions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->reportQuestionsScheduledForDeletion = clone $reportQuestionsToDelete;

        foreach ($reportQuestionsToDelete as $reportQuestionRemoved) {
            $reportQuestionRemoved->setQuestion(null);
        }

        $this->collReportQuestions = null;
        foreach ($reportQuestions as $reportQuestion) {
            $this->addReportQuestion($reportQuestion);
        }

        $this->collReportQuestions = $reportQuestions;
        $this->collReportQuestionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ReportQuestion objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ReportQuestion objects.
     * @throws PropelException
     */
    public function countReportQuestions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collReportQuestionsPartial && !$this->isNew();
        if (null === $this->collReportQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReportQuestions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReportQuestions());
            }

            $query = ChildReportQuestionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collReportQuestions);
    }

    /**
     * Method called to associate a ChildReportQuestion object to this object
     * through the ChildReportQuestion foreign key attribute.
     *
     * @param  ChildReportQuestion $l ChildReportQuestion
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addReportQuestion(ChildReportQuestion $l)
    {
        if ($this->collReportQuestions === null) {
            $this->initReportQuestions();
            $this->collReportQuestionsPartial = true;
        }

        if (!$this->collReportQuestions->contains($l)) {
            $this->doAddReportQuestion($l);

            if ($this->reportQuestionsScheduledForDeletion and $this->reportQuestionsScheduledForDeletion->contains($l)) {
                $this->reportQuestionsScheduledForDeletion->remove($this->reportQuestionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildReportQuestion $reportQuestion The ChildReportQuestion object to add.
     */
    protected function doAddReportQuestion(ChildReportQuestion $reportQuestion)
    {
        $this->collReportQuestions[]= $reportQuestion;
        $reportQuestion->setQuestion($this);
    }

    /**
     * @param  ChildReportQuestion $reportQuestion The ChildReportQuestion object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeReportQuestion(ChildReportQuestion $reportQuestion)
    {
        if ($this->getReportQuestions()->contains($reportQuestion)) {
            $pos = $this->collReportQuestions->search($reportQuestion);
            $this->collReportQuestions->remove($pos);
            if (null === $this->reportQuestionsScheduledForDeletion) {
                $this->reportQuestionsScheduledForDeletion = clone $this->collReportQuestions;
                $this->reportQuestionsScheduledForDeletion->clear();
            }
            $this->reportQuestionsScheduledForDeletion[]= clone $reportQuestion;
            $reportQuestion->setQuestion(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Question is new, it will return
     * an empty collection; or if this Question has previously
     * been saved, it will retrieve related ReportQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Question.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildReportQuestion[] List of ChildReportQuestion objects
     */
    public function getReportQuestionsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildReportQuestionQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getReportQuestions($query, $con);
    }

    /**
     * Clears out the collSearchIndices collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSearchIndices()
     */
    public function clearSearchIndices()
    {
        $this->collSearchIndices = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSearchIndices collection loaded partially.
     */
    public function resetPartialSearchIndices($v = true)
    {
        $this->collSearchIndicesPartial = $v;
    }

    /**
     * Initializes the collSearchIndices collection.
     *
     * By default this just sets the collSearchIndices collection to an empty array (like clearcollSearchIndices());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSearchIndices($overrideExisting = true)
    {
        if (null !== $this->collSearchIndices && !$overrideExisting) {
            return;
        }

        $collectionClassName = SearchIndexTableMap::getTableMap()->getCollectionClassName();

        $this->collSearchIndices = new $collectionClassName;
        $this->collSearchIndices->setModel('\App\Entity\SearchIndex');
    }

    /**
     * Gets an array of ChildSearchIndex objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSearchIndex[] List of ChildSearchIndex objects
     * @throws PropelException
     */
    public function getSearchIndices(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSearchIndicesPartial && !$this->isNew();
        if (null === $this->collSearchIndices || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSearchIndices) {
                    $this->initSearchIndices();
                } else {
                    $collectionClassName = SearchIndexTableMap::getTableMap()->getCollectionClassName();

                    $collSearchIndices = new $collectionClassName;
                    $collSearchIndices->setModel('\App\Entity\SearchIndex');

                    return $collSearchIndices;
                }
            } else {
                $collSearchIndices = ChildSearchIndexQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSearchIndicesPartial && count($collSearchIndices)) {
                        $this->initSearchIndices(false);

                        foreach ($collSearchIndices as $obj) {
                            if (false == $this->collSearchIndices->contains($obj)) {
                                $this->collSearchIndices->append($obj);
                            }
                        }

                        $this->collSearchIndicesPartial = true;
                    }

                    return $collSearchIndices;
                }

                if ($partial && $this->collSearchIndices) {
                    foreach ($this->collSearchIndices as $obj) {
                        if ($obj->isNew()) {
                            $collSearchIndices[] = $obj;
                        }
                    }
                }

                $this->collSearchIndices = $collSearchIndices;
                $this->collSearchIndicesPartial = false;
            }
        }

        return $this->collSearchIndices;
    }

    /**
     * Sets a collection of ChildSearchIndex objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $searchIndices A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setSearchIndices(Collection $searchIndices, ConnectionInterface $con = null)
    {
        /** @var ChildSearchIndex[] $searchIndicesToDelete */
        $searchIndicesToDelete = $this->getSearchIndices(new Criteria(), $con)->diff($searchIndices);


        $this->searchIndicesScheduledForDeletion = $searchIndicesToDelete;

        foreach ($searchIndicesToDelete as $searchIndexRemoved) {
            $searchIndexRemoved->setQuestion(null);
        }

        $this->collSearchIndices = null;
        foreach ($searchIndices as $searchIndex) {
            $this->addSearchIndex($searchIndex);
        }

        $this->collSearchIndices = $searchIndices;
        $this->collSearchIndicesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SearchIndex objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SearchIndex objects.
     * @throws PropelException
     */
    public function countSearchIndices(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSearchIndicesPartial && !$this->isNew();
        if (null === $this->collSearchIndices || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSearchIndices) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSearchIndices());
            }

            $query = ChildSearchIndexQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collSearchIndices);
    }

    /**
     * Method called to associate a ChildSearchIndex object to this object
     * through the ChildSearchIndex foreign key attribute.
     *
     * @param  ChildSearchIndex $l ChildSearchIndex
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addSearchIndex(ChildSearchIndex $l)
    {
        if ($this->collSearchIndices === null) {
            $this->initSearchIndices();
            $this->collSearchIndicesPartial = true;
        }

        if (!$this->collSearchIndices->contains($l)) {
            $this->doAddSearchIndex($l);

            if ($this->searchIndicesScheduledForDeletion and $this->searchIndicesScheduledForDeletion->contains($l)) {
                $this->searchIndicesScheduledForDeletion->remove($this->searchIndicesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSearchIndex $searchIndex The ChildSearchIndex object to add.
     */
    protected function doAddSearchIndex(ChildSearchIndex $searchIndex)
    {
        $this->collSearchIndices[]= $searchIndex;
        $searchIndex->setQuestion($this);
    }

    /**
     * @param  ChildSearchIndex $searchIndex The ChildSearchIndex object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeSearchIndex(ChildSearchIndex $searchIndex)
    {
        if ($this->getSearchIndices()->contains($searchIndex)) {
            $pos = $this->collSearchIndices->search($searchIndex);
            $this->collSearchIndices->remove($pos);
            if (null === $this->searchIndicesScheduledForDeletion) {
                $this->searchIndicesScheduledForDeletion = clone $this->collSearchIndices;
                $this->searchIndicesScheduledForDeletion->clear();
            }
            $this->searchIndicesScheduledForDeletion[]= $searchIndex;
            $searchIndex->setQuestion(null);
        }

        return $this;
    }

    /**
     * Clears out the collQuestionI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuestionI18ns()
     */
    public function clearQuestionI18ns()
    {
        $this->collQuestionI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collQuestionI18ns collection loaded partially.
     */
    public function resetPartialQuestionI18ns($v = true)
    {
        $this->collQuestionI18nsPartial = $v;
    }

    /**
     * Initializes the collQuestionI18ns collection.
     *
     * By default this just sets the collQuestionI18ns collection to an empty array (like clearcollQuestionI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuestionI18ns($overrideExisting = true)
    {
        if (null !== $this->collQuestionI18ns && !$overrideExisting) {
            return;
        }

        $collectionClassName = QuestionI18nTableMap::getTableMap()->getCollectionClassName();

        $this->collQuestionI18ns = new $collectionClassName;
        $this->collQuestionI18ns->setModel('\App\Entity\QuestionI18n');
    }

    /**
     * Gets an array of ChildQuestionI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildQuestion is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildQuestionI18n[] List of ChildQuestionI18n objects
     * @throws PropelException
     */
    public function getQuestionI18ns(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionI18nsPartial && !$this->isNew();
        if (null === $this->collQuestionI18ns || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collQuestionI18ns) {
                    $this->initQuestionI18ns();
                } else {
                    $collectionClassName = QuestionI18nTableMap::getTableMap()->getCollectionClassName();

                    $collQuestionI18ns = new $collectionClassName;
                    $collQuestionI18ns->setModel('\App\Entity\QuestionI18n');

                    return $collQuestionI18ns;
                }
            } else {
                $collQuestionI18ns = ChildQuestionI18nQuery::create(null, $criteria)
                    ->filterByQuestion($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collQuestionI18nsPartial && count($collQuestionI18ns)) {
                        $this->initQuestionI18ns(false);

                        foreach ($collQuestionI18ns as $obj) {
                            if (false == $this->collQuestionI18ns->contains($obj)) {
                                $this->collQuestionI18ns->append($obj);
                            }
                        }

                        $this->collQuestionI18nsPartial = true;
                    }

                    return $collQuestionI18ns;
                }

                if ($partial && $this->collQuestionI18ns) {
                    foreach ($this->collQuestionI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collQuestionI18ns[] = $obj;
                        }
                    }
                }

                $this->collQuestionI18ns = $collQuestionI18ns;
                $this->collQuestionI18nsPartial = false;
            }
        }

        return $this->collQuestionI18ns;
    }

    /**
     * Sets a collection of ChildQuestionI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $questionI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function setQuestionI18ns(Collection $questionI18ns, ConnectionInterface $con = null)
    {
        /** @var ChildQuestionI18n[] $questionI18nsToDelete */
        $questionI18nsToDelete = $this->getQuestionI18ns(new Criteria(), $con)->diff($questionI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->questionI18nsScheduledForDeletion = clone $questionI18nsToDelete;

        foreach ($questionI18nsToDelete as $questionI18nRemoved) {
            $questionI18nRemoved->setQuestion(null);
        }

        $this->collQuestionI18ns = null;
        foreach ($questionI18ns as $questionI18n) {
            $this->addQuestionI18n($questionI18n);
        }

        $this->collQuestionI18ns = $questionI18ns;
        $this->collQuestionI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related QuestionI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related QuestionI18n objects.
     * @throws PropelException
     */
    public function countQuestionI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionI18nsPartial && !$this->isNew();
        if (null === $this->collQuestionI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuestionI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQuestionI18ns());
            }

            $query = ChildQuestionI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByQuestion($this)
                ->count($con);
        }

        return count($this->collQuestionI18ns);
    }

    /**
     * Method called to associate a ChildQuestionI18n object to this object
     * through the ChildQuestionI18n foreign key attribute.
     *
     * @param  ChildQuestionI18n $l ChildQuestionI18n
     * @return $this|\App\Entity\Question The current object (for fluent API support)
     */
    public function addQuestionI18n(ChildQuestionI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collQuestionI18ns === null) {
            $this->initQuestionI18ns();
            $this->collQuestionI18nsPartial = true;
        }

        if (!$this->collQuestionI18ns->contains($l)) {
            $this->doAddQuestionI18n($l);

            if ($this->questionI18nsScheduledForDeletion and $this->questionI18nsScheduledForDeletion->contains($l)) {
                $this->questionI18nsScheduledForDeletion->remove($this->questionI18nsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildQuestionI18n $questionI18n The ChildQuestionI18n object to add.
     */
    protected function doAddQuestionI18n(ChildQuestionI18n $questionI18n)
    {
        $this->collQuestionI18ns[]= $questionI18n;
        $questionI18n->setQuestion($this);
    }

    /**
     * @param  ChildQuestionI18n $questionI18n The ChildQuestionI18n object to remove.
     * @return $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeQuestionI18n(ChildQuestionI18n $questionI18n)
    {
        if ($this->getQuestionI18ns()->contains($questionI18n)) {
            $pos = $this->collQuestionI18ns->search($questionI18n);
            $this->collQuestionI18ns->remove($pos);
            if (null === $this->questionI18nsScheduledForDeletion) {
                $this->questionI18nsScheduledForDeletion = clone $this->collQuestionI18ns;
                $this->questionI18nsScheduledForDeletion->clear();
            }
            $this->questionI18nsScheduledForDeletion[]= clone $questionI18n;
            $questionI18n->setQuestion(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeQuestion($this);
        }
        $this->id = null;
        $this->user_id = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->interested_users = null;
        $this->stripped_title = null;
        $this->reports = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collAnswers) {
                foreach ($this->collAnswers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInterests) {
                foreach ($this->collInterests as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQuestionTags) {
                foreach ($this->collQuestionTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collReportQuestions) {
                foreach ($this->collReportQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSearchIndices) {
                foreach ($this->collSearchIndices as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQuestionI18ns) {
                foreach ($this->collQuestionI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // l10n behavior
        $this->currentLocale = null;
        $this->currentTranslations = null;

        $this->collAnswers = null;
        $this->collInterests = null;
        $this->collQuestionTags = null;
        $this->collReportQuestions = null;
        $this->collSearchIndices = null;
        $this->collQuestionI18ns = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(QuestionTableMap::DEFAULT_STRING_FORMAT);
    }

    // l10n behavior

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
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'de-DE'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildQuestionI18n */
    public function getTranslation($locale = null, ConnectionInterface $con = null)
    {
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collQuestionI18ns) {
                foreach ($this->collQuestionI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildQuestionI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildQuestionI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addQuestionI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'de-DE'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    $this|ChildQuestion The current object (for fluent API support)
     */
    public function removeTranslation($locale = null, ConnectionInterface $con = null)
    {
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        if (!$this->isNew()) {
            ChildQuestionI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collQuestionI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collQuestionI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildQuestionI18n */
    public function getCurrentTranslation($locale = null, ConnectionInterface $con = null)
    {
        $locale = $this->getLocale();
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        return $this->getTranslation($locale, $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return string
         */
    public function getTitle($locale = null)
    {
        $getTranslatedLocale = function($locale)  {
            $trans = $this->getTranslation($locale);
            return $trans->getTitle();
        };
        $workDownLanguageTag = function($locale) use($getTranslatedLocale) {
            // check if the locale has more than one subtag to work down
            if (strpos($locale, '-') === false) {
                return null;
            }

            // drop the last subtag
            $locale = implode('-', array_slice(explode('-', $locale), 0, -1));
            $value = $getTranslatedLocale($locale);
            if ($value === null) {
                $value = $workDownLanguageTag($locale);
            }
            return $value;
        };
        $value = null;
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        // try default locale
        $value = $getTranslatedLocale($locale);

        if ($value === null) {
            // try dependency chain
            while (PropelL10n::hasDependency($locale) && $value === null) {
                $newLocale = PropelL10n::getDependency($locale);

                // if primary language of dependency is different than current, work down language-tag-chain
                if (\Locale::getPrimaryLanguage($newLocale) != \Locale::getPrimaryLanguage($locale)) {
                    $value = $workDownLanguageTag($locale);
                }

                // proceed with dependency if still nothing is found
                if ($value === null) {
                    $locale = $newLocale;
                    $value = $getTranslatedLocale($locale);
                }
            }

            // work down language-tag-chain
            if ($value === null) {
                $value = $workDownLanguageTag($locale);

                // try fallback language
                if ($value === null) {
                    $locale = PropelL10n::getFallback();
                    $value = $getTranslatedLocale($locale);
                }
            }
        }
        return $value;
    }


        /**
         * Set the value of [title] column.
         *
         * @param string|null $v New value
         * @return $this|\App\Entity\QuestionI18n The current object (for fluent API support)
         */
    public function setTitle($v, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        $this->getTranslation($locale)->setTitle($v);

        return $this;
    }


        /**
         * Get the [body] column value.
         *
         * @return string
         */
    public function getBody($locale = null)
    {
        $getTranslatedLocale = function($locale)  {
            $trans = $this->getTranslation($locale);
            return $trans->getBody();
        };
        $workDownLanguageTag = function($locale) use($getTranslatedLocale) {
            // check if the locale has more than one subtag to work down
            if (strpos($locale, '-') === false) {
                return null;
            }

            // drop the last subtag
            $locale = implode('-', array_slice(explode('-', $locale), 0, -1));
            $value = $getTranslatedLocale($locale);
            if ($value === null) {
                $value = $workDownLanguageTag($locale);
            }
            return $value;
        };
        $value = null;
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        // try default locale
        $value = $getTranslatedLocale($locale);

        if ($value === null) {
            // try dependency chain
            while (PropelL10n::hasDependency($locale) && $value === null) {
                $newLocale = PropelL10n::getDependency($locale);

                // if primary language of dependency is different than current, work down language-tag-chain
                if (\Locale::getPrimaryLanguage($newLocale) != \Locale::getPrimaryLanguage($locale)) {
                    $value = $workDownLanguageTag($locale);
                }

                // proceed with dependency if still nothing is found
                if ($value === null) {
                    $locale = $newLocale;
                    $value = $getTranslatedLocale($locale);
                }
            }

            // work down language-tag-chain
            if ($value === null) {
                $value = $workDownLanguageTag($locale);

                // try fallback language
                if ($value === null) {
                    $locale = PropelL10n::getFallback();
                    $value = $getTranslatedLocale($locale);
                }
            }
        }
        return $value;
    }


        /**
         * Set the value of [body] column.
         *
         * @param string|null $v New value
         * @return $this|\App\Entity\QuestionI18n The current object (for fluent API support)
         */
    public function setBody($v, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        $this->getTranslation($locale)->setBody($v);

        return $this;
    }


        /**
         * Get the [html_body] column value.
         *
         * @return string
         */
    public function getHtmlBody($locale = null)
    {
        $getTranslatedLocale = function($locale)  {
            $trans = $this->getTranslation($locale);
            return $trans->getHtmlBody();
        };
        $workDownLanguageTag = function($locale) use($getTranslatedLocale) {
            // check if the locale has more than one subtag to work down
            if (strpos($locale, '-') === false) {
                return null;
            }

            // drop the last subtag
            $locale = implode('-', array_slice(explode('-', $locale), 0, -1));
            $value = $getTranslatedLocale($locale);
            if ($value === null) {
                $value = $workDownLanguageTag($locale);
            }
            return $value;
        };
        $value = null;
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }

        // try default locale
        $value = $getTranslatedLocale($locale);

        if ($value === null) {
            // try dependency chain
            while (PropelL10n::hasDependency($locale) && $value === null) {
                $newLocale = PropelL10n::getDependency($locale);

                // if primary language of dependency is different than current, work down language-tag-chain
                if (\Locale::getPrimaryLanguage($newLocale) != \Locale::getPrimaryLanguage($locale)) {
                    $value = $workDownLanguageTag($locale);
                }

                // proceed with dependency if still nothing is found
                if ($value === null) {
                    $locale = $newLocale;
                    $value = $getTranslatedLocale($locale);
                }
            }

            // work down language-tag-chain
            if ($value === null) {
                $value = $workDownLanguageTag($locale);

                // try fallback language
                if ($value === null) {
                    $locale = PropelL10n::getFallback();
                    $value = $getTranslatedLocale($locale);
                }
            }
        }
        return $value;
    }


        /**
         * Set the value of [html_body] column.
         *
         * @param string|null $v New value
         * @return $this|\App\Entity\QuestionI18n The current object (for fluent API support)
         */
    public function setHtmlBody($v, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->getLocale();
        }
        if ($locale === null) {
            $locale = PropelL10n::getLocale();
        }
        $this->getTranslation($locale)->setHtmlBody($v);

        return $this;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildQuestion The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[QuestionTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
