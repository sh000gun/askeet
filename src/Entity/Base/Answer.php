<?php

namespace App\Entity\Base;

use \DateTime;
use \Exception;
use \PDO;
use App\Entity\Answer as ChildAnswer;
use App\Entity\AnswerQuery as ChildAnswerQuery;
use App\Entity\Question as ChildQuestion;
use App\Entity\QuestionQuery as ChildQuestionQuery;
use App\Entity\Relevancy as ChildRelevancy;
use App\Entity\RelevancyQuery as ChildRelevancyQuery;
use App\Entity\ReportAnswer as ChildReportAnswer;
use App\Entity\ReportAnswerQuery as ChildReportAnswerQuery;
use App\Entity\User as ChildUser;
use App\Entity\UserQuery as ChildUserQuery;
use App\Entity\Map\AnswerTableMap;
use App\Entity\Map\RelevancyTableMap;
use App\Entity\Map\ReportAnswerTableMap;
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

/**
 * Base class that represents a row from the 'ask_answer' table.
 *
 *
 *
 * @package    propel.generator.App.Entity.Base
 */
abstract class Answer implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\App\\Entity\\Map\\AnswerTableMap';


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
     * The value for the question_id field.
     *
     * @var        int
     */
    protected $question_id;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the body field.
     *
     * @var        string
     */
    protected $body;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the relevancy_up field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $relevancy_up;

    /**
     * The value for the relevancy_down field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $relevancy_down;

    /**
     * The value for the reports field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $reports;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildQuestion
     */
    protected $aQuestion;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildRelevancy[] Collection to store aggregation of ChildRelevancy objects.
     */
    protected $collRelevancies;
    protected $collRelevanciesPartial;

    /**
     * @var        ObjectCollection|ChildReportAnswer[] Collection to store aggregation of ChildReportAnswer objects.
     */
    protected $collReportAnswers;
    protected $collReportAnswersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRelevancy[]
     */
    protected $relevanciesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildReportAnswer[]
     */
    protected $reportAnswersScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->relevancy_up = 0;
        $this->relevancy_down = 0;
        $this->reports = 0;
    }

    /**
     * Initializes internal state of App\Entity\Base\Answer object.
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
     * Compares this with another <code>Answer</code> instance.  If
     * <code>obj</code> is an instance of <code>Answer</code>, delegates to
     * <code>equals(Answer)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Answer The current object, for fluid interface
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
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
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
     * Get the [question_id] column value.
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->question_id;
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
     * Get the [body] column value.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
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
     * Get the [relevancy_up] column value.
     *
     * @return int
     */
    public function getRelevancyUp()
    {
        return $this->relevancy_up;
    }

    /**
     * Get the [relevancy_down] column value.
     *
     * @return int
     */
    public function getRelevancyDown()
    {
        return $this->relevancy_down;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AnswerTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [question_id] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setQuestionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->question_id !== $v) {
            $this->question_id = $v;
            $this->modifiedColumns[AnswerTableMap::COL_QUESTION_ID] = true;
        }

        if ($this->aQuestion !== null && $this->aQuestion->getId() !== $v) {
            $this->aQuestion = null;
        }

        return $this;
    } // setQuestionId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[AnswerTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [body] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setBody($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->body !== $v) {
            $this->body = $v;
            $this->modifiedColumns[AnswerTableMap::COL_BODY] = true;
        }

        return $this;
    } // setBody()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AnswerTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Set the value of [relevancy_up] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setRelevancyUp($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->relevancy_up !== $v) {
            $this->relevancy_up = $v;
            $this->modifiedColumns[AnswerTableMap::COL_RELEVANCY_UP] = true;
        }

        return $this;
    } // setRelevancyUp()

    /**
     * Set the value of [relevancy_down] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setRelevancyDown($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->relevancy_down !== $v) {
            $this->relevancy_down = $v;
            $this->modifiedColumns[AnswerTableMap::COL_RELEVANCY_DOWN] = true;
        }

        return $this;
    } // setRelevancyDown()

    /**
     * Set the value of [reports] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setReports($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reports !== $v) {
            $this->reports = $v;
            $this->modifiedColumns[AnswerTableMap::COL_REPORTS] = true;
        }

        return $this;
    } // setReports()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AnswerTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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
            if ($this->relevancy_up !== 0) {
                return false;
            }

            if ($this->relevancy_down !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AnswerTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AnswerTableMap::translateFieldName('QuestionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->question_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AnswerTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AnswerTableMap::translateFieldName('Body', TableMap::TYPE_PHPNAME, $indexType)];
            $this->body = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AnswerTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AnswerTableMap::translateFieldName('RelevancyUp', TableMap::TYPE_PHPNAME, $indexType)];
            $this->relevancy_up = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AnswerTableMap::translateFieldName('RelevancyDown', TableMap::TYPE_PHPNAME, $indexType)];
            $this->relevancy_down = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AnswerTableMap::translateFieldName('Reports', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reports = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AnswerTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = AnswerTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\App\\Entity\\Answer'), 0, $e);
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
        if ($this->aQuestion !== null && $this->question_id !== $this->aQuestion->getId()) {
            $this->aQuestion = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(AnswerTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAnswerQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aQuestion = null;
            $this->aUser = null;
            $this->collRelevancies = null;

            $this->collReportAnswers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Answer::setDeleted()
     * @see Answer::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAnswerQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AnswerTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(AnswerTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(AnswerTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(AnswerTableMap::COL_UPDATED_AT)) {
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
                AnswerTableMap::addInstanceToPool($this);
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

            if ($this->aQuestion !== null) {
                if ($this->aQuestion->isModified() || $this->aQuestion->isNew()) {
                    $affectedRows += $this->aQuestion->save($con);
                }
                $this->setQuestion($this->aQuestion);
            }

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

            if ($this->relevanciesScheduledForDeletion !== null) {
                if (!$this->relevanciesScheduledForDeletion->isEmpty()) {
                    \App\Entity\RelevancyQuery::create()
                        ->filterByPrimaryKeys($this->relevanciesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->relevanciesScheduledForDeletion = null;
                }
            }

            if ($this->collRelevancies !== null) {
                foreach ($this->collRelevancies as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->reportAnswersScheduledForDeletion !== null) {
                if (!$this->reportAnswersScheduledForDeletion->isEmpty()) {
                    \App\Entity\ReportAnswerQuery::create()
                        ->filterByPrimaryKeys($this->reportAnswersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->reportAnswersScheduledForDeletion = null;
                }
            }

            if ($this->collReportAnswers !== null) {
                foreach ($this->collReportAnswers as $referrerFK) {
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

        $this->modifiedColumns[AnswerTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AnswerTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AnswerTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_QUESTION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'question_id';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_BODY)) {
            $modifiedColumns[':p' . $index++]  = 'body';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_RELEVANCY_UP)) {
            $modifiedColumns[':p' . $index++]  = 'relevancy_up';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_RELEVANCY_DOWN)) {
            $modifiedColumns[':p' . $index++]  = 'relevancy_down';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_REPORTS)) {
            $modifiedColumns[':p' . $index++]  = 'reports';
        }
        if ($this->isColumnModified(AnswerTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO ask_answer (%s) VALUES (%s)',
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
                    case 'question_id':
                        $stmt->bindValue($identifier, $this->question_id, PDO::PARAM_INT);
                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'body':
                        $stmt->bindValue($identifier, $this->body, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'relevancy_up':
                        $stmt->bindValue($identifier, $this->relevancy_up, PDO::PARAM_INT);
                        break;
                    case 'relevancy_down':
                        $stmt->bindValue($identifier, $this->relevancy_down, PDO::PARAM_INT);
                        break;
                    case 'reports':
                        $stmt->bindValue($identifier, $this->reports, PDO::PARAM_INT);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = AnswerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getQuestionId();
                break;
            case 2:
                return $this->getUserId();
                break;
            case 3:
                return $this->getBody();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
                return $this->getRelevancyUp();
                break;
            case 6:
                return $this->getRelevancyDown();
                break;
            case 7:
                return $this->getReports();
                break;
            case 8:
                return $this->getUpdatedAt();
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

        if (isset($alreadyDumpedObjects['Answer'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Answer'][$this->hashCode()] = true;
        $keys = AnswerTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getQuestionId(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getBody(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getRelevancyUp(),
            $keys[6] => $this->getRelevancyDown(),
            $keys[7] => $this->getReports(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aQuestion) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'question';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_question';
                        break;
                    default:
                        $key = 'Question';
                }

                $result[$key] = $this->aQuestion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->collRelevancies) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'relevancies';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_relevancies';
                        break;
                    default:
                        $key = 'Relevancies';
                }

                $result[$key] = $this->collRelevancies->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collReportAnswers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'reportAnswers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_report_answers';
                        break;
                    default:
                        $key = 'ReportAnswers';
                }

                $result[$key] = $this->collReportAnswers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\App\Entity\Answer
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AnswerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\App\Entity\Answer
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setQuestionId($value);
                break;
            case 2:
                $this->setUserId($value);
                break;
            case 3:
                $this->setBody($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
                $this->setRelevancyUp($value);
                break;
            case 6:
                $this->setRelevancyDown($value);
                break;
            case 7:
                $this->setReports($value);
                break;
            case 8:
                $this->setUpdatedAt($value);
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
        $keys = AnswerTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setQuestionId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setBody($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCreatedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setRelevancyUp($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setRelevancyDown($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setReports($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUpdatedAt($arr[$keys[8]]);
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
     * @return $this|\App\Entity\Answer The current object, for fluid interface
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
        $criteria = new Criteria(AnswerTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AnswerTableMap::COL_ID)) {
            $criteria->add(AnswerTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_QUESTION_ID)) {
            $criteria->add(AnswerTableMap::COL_QUESTION_ID, $this->question_id);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_USER_ID)) {
            $criteria->add(AnswerTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_BODY)) {
            $criteria->add(AnswerTableMap::COL_BODY, $this->body);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_CREATED_AT)) {
            $criteria->add(AnswerTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_RELEVANCY_UP)) {
            $criteria->add(AnswerTableMap::COL_RELEVANCY_UP, $this->relevancy_up);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_RELEVANCY_DOWN)) {
            $criteria->add(AnswerTableMap::COL_RELEVANCY_DOWN, $this->relevancy_down);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_REPORTS)) {
            $criteria->add(AnswerTableMap::COL_REPORTS, $this->reports);
        }
        if ($this->isColumnModified(AnswerTableMap::COL_UPDATED_AT)) {
            $criteria->add(AnswerTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildAnswerQuery::create();
        $criteria->add(AnswerTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \App\Entity\Answer (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setQuestionId($this->getQuestionId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setBody($this->getBody());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setRelevancyUp($this->getRelevancyUp());
        $copyObj->setRelevancyDown($this->getRelevancyDown());
        $copyObj->setReports($this->getReports());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getRelevancies() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRelevancy($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getReportAnswers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReportAnswer($relObj->copy($deepCopy));
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
     * @return \App\Entity\Answer Clone of current object.
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
     * Declares an association between this object and a ChildQuestion object.
     *
     * @param  ChildQuestion $v
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     * @throws PropelException
     */
    public function setQuestion(ChildQuestion $v = null)
    {
        if ($v === null) {
            $this->setQuestionId(NULL);
        } else {
            $this->setQuestionId($v->getId());
        }

        $this->aQuestion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildQuestion object, it will not be re-added.
        if ($v !== null) {
            $v->addAnswer($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildQuestion object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildQuestion The associated ChildQuestion object.
     * @throws PropelException
     */
    public function getQuestion(ConnectionInterface $con = null)
    {
        if ($this->aQuestion === null && ($this->question_id != 0)) {
            $this->aQuestion = ChildQuestionQuery::create()->findPk($this->question_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aQuestion->addAnswers($this);
             */
        }

        return $this->aQuestion;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
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
            $v->addAnswer($this);
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
                $this->aUser->addAnswers($this);
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
        if ('Relevancy' == $relationName) {
            $this->initRelevancies();
            return;
        }
        if ('ReportAnswer' == $relationName) {
            $this->initReportAnswers();
            return;
        }
    }

    /**
     * Clears out the collRelevancies collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRelevancies()
     */
    public function clearRelevancies()
    {
        $this->collRelevancies = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRelevancies collection loaded partially.
     */
    public function resetPartialRelevancies($v = true)
    {
        $this->collRelevanciesPartial = $v;
    }

    /**
     * Initializes the collRelevancies collection.
     *
     * By default this just sets the collRelevancies collection to an empty array (like clearcollRelevancies());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRelevancies($overrideExisting = true)
    {
        if (null !== $this->collRelevancies && !$overrideExisting) {
            return;
        }

        $collectionClassName = RelevancyTableMap::getTableMap()->getCollectionClassName();

        $this->collRelevancies = new $collectionClassName;
        $this->collRelevancies->setModel('\App\Entity\Relevancy');
    }

    /**
     * Gets an array of ChildRelevancy objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAnswer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRelevancy[] List of ChildRelevancy objects
     * @throws PropelException
     */
    public function getRelevancies(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRelevanciesPartial && !$this->isNew();
        if (null === $this->collRelevancies || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRelevancies) {
                // return empty collection
                $this->initRelevancies();
            } else {
                $collRelevancies = ChildRelevancyQuery::create(null, $criteria)
                    ->filterByAnswer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRelevanciesPartial && count($collRelevancies)) {
                        $this->initRelevancies(false);

                        foreach ($collRelevancies as $obj) {
                            if (false == $this->collRelevancies->contains($obj)) {
                                $this->collRelevancies->append($obj);
                            }
                        }

                        $this->collRelevanciesPartial = true;
                    }

                    return $collRelevancies;
                }

                if ($partial && $this->collRelevancies) {
                    foreach ($this->collRelevancies as $obj) {
                        if ($obj->isNew()) {
                            $collRelevancies[] = $obj;
                        }
                    }
                }

                $this->collRelevancies = $collRelevancies;
                $this->collRelevanciesPartial = false;
            }
        }

        return $this->collRelevancies;
    }

    /**
     * Sets a collection of ChildRelevancy objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $relevancies A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAnswer The current object (for fluent API support)
     */
    public function setRelevancies(Collection $relevancies, ConnectionInterface $con = null)
    {
        /** @var ChildRelevancy[] $relevanciesToDelete */
        $relevanciesToDelete = $this->getRelevancies(new Criteria(), $con)->diff($relevancies);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->relevanciesScheduledForDeletion = clone $relevanciesToDelete;

        foreach ($relevanciesToDelete as $relevancyRemoved) {
            $relevancyRemoved->setAnswer(null);
        }

        $this->collRelevancies = null;
        foreach ($relevancies as $relevancy) {
            $this->addRelevancy($relevancy);
        }

        $this->collRelevancies = $relevancies;
        $this->collRelevanciesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Relevancy objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Relevancy objects.
     * @throws PropelException
     */
    public function countRelevancies(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRelevanciesPartial && !$this->isNew();
        if (null === $this->collRelevancies || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRelevancies) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRelevancies());
            }

            $query = ChildRelevancyQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAnswer($this)
                ->count($con);
        }

        return count($this->collRelevancies);
    }

    /**
     * Method called to associate a ChildRelevancy object to this object
     * through the ChildRelevancy foreign key attribute.
     *
     * @param  ChildRelevancy $l ChildRelevancy
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function addRelevancy(ChildRelevancy $l)
    {
        if ($this->collRelevancies === null) {
            $this->initRelevancies();
            $this->collRelevanciesPartial = true;
        }

        if (!$this->collRelevancies->contains($l)) {
            $this->doAddRelevancy($l);

            if ($this->relevanciesScheduledForDeletion and $this->relevanciesScheduledForDeletion->contains($l)) {
                $this->relevanciesScheduledForDeletion->remove($this->relevanciesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildRelevancy $relevancy The ChildRelevancy object to add.
     */
    protected function doAddRelevancy(ChildRelevancy $relevancy)
    {
        $this->collRelevancies[]= $relevancy;
        $relevancy->setAnswer($this);
    }

    /**
     * @param  ChildRelevancy $relevancy The ChildRelevancy object to remove.
     * @return $this|ChildAnswer The current object (for fluent API support)
     */
    public function removeRelevancy(ChildRelevancy $relevancy)
    {
        if ($this->getRelevancies()->contains($relevancy)) {
            $pos = $this->collRelevancies->search($relevancy);
            $this->collRelevancies->remove($pos);
            if (null === $this->relevanciesScheduledForDeletion) {
                $this->relevanciesScheduledForDeletion = clone $this->collRelevancies;
                $this->relevanciesScheduledForDeletion->clear();
            }
            $this->relevanciesScheduledForDeletion[]= clone $relevancy;
            $relevancy->setAnswer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Answer is new, it will return
     * an empty collection; or if this Answer has previously
     * been saved, it will retrieve related Relevancies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Answer.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRelevancy[] List of ChildRelevancy objects
     */
    public function getRelevanciesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRelevancyQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getRelevancies($query, $con);
    }

    /**
     * Clears out the collReportAnswers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addReportAnswers()
     */
    public function clearReportAnswers()
    {
        $this->collReportAnswers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collReportAnswers collection loaded partially.
     */
    public function resetPartialReportAnswers($v = true)
    {
        $this->collReportAnswersPartial = $v;
    }

    /**
     * Initializes the collReportAnswers collection.
     *
     * By default this just sets the collReportAnswers collection to an empty array (like clearcollReportAnswers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReportAnswers($overrideExisting = true)
    {
        if (null !== $this->collReportAnswers && !$overrideExisting) {
            return;
        }

        $collectionClassName = ReportAnswerTableMap::getTableMap()->getCollectionClassName();

        $this->collReportAnswers = new $collectionClassName;
        $this->collReportAnswers->setModel('\App\Entity\ReportAnswer');
    }

    /**
     * Gets an array of ChildReportAnswer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAnswer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildReportAnswer[] List of ChildReportAnswer objects
     * @throws PropelException
     */
    public function getReportAnswers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collReportAnswersPartial && !$this->isNew();
        if (null === $this->collReportAnswers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReportAnswers) {
                // return empty collection
                $this->initReportAnswers();
            } else {
                $collReportAnswers = ChildReportAnswerQuery::create(null, $criteria)
                    ->filterByAnswer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collReportAnswersPartial && count($collReportAnswers)) {
                        $this->initReportAnswers(false);

                        foreach ($collReportAnswers as $obj) {
                            if (false == $this->collReportAnswers->contains($obj)) {
                                $this->collReportAnswers->append($obj);
                            }
                        }

                        $this->collReportAnswersPartial = true;
                    }

                    return $collReportAnswers;
                }

                if ($partial && $this->collReportAnswers) {
                    foreach ($this->collReportAnswers as $obj) {
                        if ($obj->isNew()) {
                            $collReportAnswers[] = $obj;
                        }
                    }
                }

                $this->collReportAnswers = $collReportAnswers;
                $this->collReportAnswersPartial = false;
            }
        }

        return $this->collReportAnswers;
    }

    /**
     * Sets a collection of ChildReportAnswer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $reportAnswers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAnswer The current object (for fluent API support)
     */
    public function setReportAnswers(Collection $reportAnswers, ConnectionInterface $con = null)
    {
        /** @var ChildReportAnswer[] $reportAnswersToDelete */
        $reportAnswersToDelete = $this->getReportAnswers(new Criteria(), $con)->diff($reportAnswers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->reportAnswersScheduledForDeletion = clone $reportAnswersToDelete;

        foreach ($reportAnswersToDelete as $reportAnswerRemoved) {
            $reportAnswerRemoved->setAnswer(null);
        }

        $this->collReportAnswers = null;
        foreach ($reportAnswers as $reportAnswer) {
            $this->addReportAnswer($reportAnswer);
        }

        $this->collReportAnswers = $reportAnswers;
        $this->collReportAnswersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ReportAnswer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ReportAnswer objects.
     * @throws PropelException
     */
    public function countReportAnswers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collReportAnswersPartial && !$this->isNew();
        if (null === $this->collReportAnswers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReportAnswers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReportAnswers());
            }

            $query = ChildReportAnswerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAnswer($this)
                ->count($con);
        }

        return count($this->collReportAnswers);
    }

    /**
     * Method called to associate a ChildReportAnswer object to this object
     * through the ChildReportAnswer foreign key attribute.
     *
     * @param  ChildReportAnswer $l ChildReportAnswer
     * @return $this|\App\Entity\Answer The current object (for fluent API support)
     */
    public function addReportAnswer(ChildReportAnswer $l)
    {
        if ($this->collReportAnswers === null) {
            $this->initReportAnswers();
            $this->collReportAnswersPartial = true;
        }

        if (!$this->collReportAnswers->contains($l)) {
            $this->doAddReportAnswer($l);

            if ($this->reportAnswersScheduledForDeletion and $this->reportAnswersScheduledForDeletion->contains($l)) {
                $this->reportAnswersScheduledForDeletion->remove($this->reportAnswersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildReportAnswer $reportAnswer The ChildReportAnswer object to add.
     */
    protected function doAddReportAnswer(ChildReportAnswer $reportAnswer)
    {
        $this->collReportAnswers[]= $reportAnswer;
        $reportAnswer->setAnswer($this);
    }

    /**
     * @param  ChildReportAnswer $reportAnswer The ChildReportAnswer object to remove.
     * @return $this|ChildAnswer The current object (for fluent API support)
     */
    public function removeReportAnswer(ChildReportAnswer $reportAnswer)
    {
        if ($this->getReportAnswers()->contains($reportAnswer)) {
            $pos = $this->collReportAnswers->search($reportAnswer);
            $this->collReportAnswers->remove($pos);
            if (null === $this->reportAnswersScheduledForDeletion) {
                $this->reportAnswersScheduledForDeletion = clone $this->collReportAnswers;
                $this->reportAnswersScheduledForDeletion->clear();
            }
            $this->reportAnswersScheduledForDeletion[]= clone $reportAnswer;
            $reportAnswer->setAnswer(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Answer is new, it will return
     * an empty collection; or if this Answer has previously
     * been saved, it will retrieve related ReportAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Answer.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildReportAnswer[] List of ChildReportAnswer objects
     */
    public function getReportAnswersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildReportAnswerQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getReportAnswers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aQuestion) {
            $this->aQuestion->removeAnswer($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeAnswer($this);
        }
        $this->id = null;
        $this->question_id = null;
        $this->user_id = null;
        $this->body = null;
        $this->created_at = null;
        $this->relevancy_up = null;
        $this->relevancy_down = null;
        $this->reports = null;
        $this->updated_at = null;
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
            if ($this->collRelevancies) {
                foreach ($this->collRelevancies as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collReportAnswers) {
                foreach ($this->collReportAnswers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collRelevancies = null;
        $this->collReportAnswers = null;
        $this->aQuestion = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AnswerTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildAnswer The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[AnswerTableMap::COL_UPDATED_AT] = true;

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
