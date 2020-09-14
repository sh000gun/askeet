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
use App\Entity\QuestionQuery as ChildQuestionQuery;
use App\Entity\QuestionTag as ChildQuestionTag;
use App\Entity\QuestionTagQuery as ChildQuestionTagQuery;
use App\Entity\Relevancy as ChildRelevancy;
use App\Entity\RelevancyQuery as ChildRelevancyQuery;
use App\Entity\ReportAnswer as ChildReportAnswer;
use App\Entity\ReportAnswerQuery as ChildReportAnswerQuery;
use App\Entity\ReportQuestion as ChildReportQuestion;
use App\Entity\ReportQuestionQuery as ChildReportQuestionQuery;
use App\Entity\User as ChildUser;
use App\Entity\UserQuery as ChildUserQuery;
use App\Entity\Map\AnswerTableMap;
use App\Entity\Map\InterestTableMap;
use App\Entity\Map\QuestionTableMap;
use App\Entity\Map\QuestionTagTableMap;
use App\Entity\Map\RelevancyTableMap;
use App\Entity\Map\ReportAnswerTableMap;
use App\Entity\Map\ReportQuestionTableMap;
use App\Entity\Map\UserTableMap;
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
 * Base class that represents a row from the 'ask_user' table.
 *
 *
 *
 * @package    propel.generator.App.Entity.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\App\\Entity\\Map\\UserTableMap';


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
     * The value for the nickname field.
     *
     * @var        string
     */
    protected $nickname;

    /**
     * The value for the first_name field.
     *
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     *
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the sha1_password field.
     *
     * @var        string
     */
    protected $sha1_password;

    /**
     * The value for the salt field.
     *
     * @var        string
     */
    protected $salt;

    /**
     * The value for the has_paypal field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $has_paypal;

    /**
     * The value for the is_administrator field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_administrator;

    /**
     * The value for the is_moderator field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $is_moderator;

    /**
     * The value for the deletions field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $deletions;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildQuestion[] Collection to store aggregation of ChildQuestion objects.
     */
    protected $collQuestions;
    protected $collQuestionsPartial;

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
     * @var        ObjectCollection|ChildRelevancy[] Collection to store aggregation of ChildRelevancy objects.
     */
    protected $collRelevancies;
    protected $collRelevanciesPartial;

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
     * @var ObjectCollection|ChildQuestion[]
     */
    protected $questionsScheduledForDeletion = null;

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
     * @var ObjectCollection|ChildRelevancy[]
     */
    protected $relevanciesScheduledForDeletion = null;

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
        $this->has_paypal = false;
        $this->is_administrator = false;
        $this->is_moderator = 0;
        $this->deletions = 0;
    }

    /**
     * Initializes internal state of App\Entity\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [nickname] column value.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
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
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [sha1_password] column value.
     *
     * @return string
     */
    public function getSha1Password()
    {
        return $this->sha1_password;
    }

    /**
     * Get the [salt] column value.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get the [has_paypal] column value.
     *
     * @return boolean
     */
    public function getHasPaypal()
    {
        return $this->has_paypal;
    }

    /**
     * Get the [has_paypal] column value.
     *
     * @return boolean
     */
    public function hasPaypal()
    {
        return $this->getHasPaypal();
    }

    /**
     * Get the [is_administrator] column value.
     *
     * @return boolean
     */
    public function getIsAdministrator()
    {
        return $this->is_administrator;
    }

    /**
     * Get the [is_administrator] column value.
     *
     * @return boolean
     */
    public function isAdministrator()
    {
        return $this->getIsAdministrator();
    }

    /**
     * Get the [is_moderator] column value.
     *
     * @return int
     */
    public function getIsModerator()
    {
        return $this->is_moderator;
    }

    /**
     * Get the [deletions] column value.
     *
     * @return int
     */
    public function getDeletions()
    {
        return $this->deletions;
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
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [nickname] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nickname !== $v) {
            $this->nickname = $v;
            $this->modifiedColumns[UserTableMap::COL_NICKNAME] = true;
        }

        return $this;
    } // setNickname()

    /**
     * Set the value of [first_name] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[UserTableMap::COL_FIRST_NAME] = true;
        }

        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[UserTableMap::COL_LAST_NAME] = true;
        }

        return $this;
    } // setLastName()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [sha1_password] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setSha1Password($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sha1_password !== $v) {
            $this->sha1_password = $v;
            $this->modifiedColumns[UserTableMap::COL_SHA1_PASSWORD] = true;
        }

        return $this;
    } // setSha1Password()

    /**
     * Set the value of [salt] column.
     *
     * @param string $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setSalt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->salt !== $v) {
            $this->salt = $v;
            $this->modifiedColumns[UserTableMap::COL_SALT] = true;
        }

        return $this;
    } // setSalt()

    /**
     * Sets the value of the [has_paypal] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setHasPaypal($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->has_paypal !== $v) {
            $this->has_paypal = $v;
            $this->modifiedColumns[UserTableMap::COL_HAS_PAYPAL] = true;
        }

        return $this;
    } // setHasPaypal()

    /**
     * Sets the value of the [is_administrator] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setIsAdministrator($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_administrator !== $v) {
            $this->is_administrator = $v;
            $this->modifiedColumns[UserTableMap::COL_IS_ADMINISTRATOR] = true;
        }

        return $this;
    } // setIsAdministrator()

    /**
     * Set the value of [is_moderator] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setIsModerator($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->is_moderator !== $v) {
            $this->is_moderator = $v;
            $this->modifiedColumns[UserTableMap::COL_IS_MODERATOR] = true;
        }

        return $this;
    } // setIsModerator()

    /**
     * Set the value of [deletions] column.
     *
     * @param int $v new value
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setDeletions($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->deletions !== $v) {
            $this->deletions = $v;
            $this->modifiedColumns[UserTableMap::COL_DELETIONS] = true;
        }

        return $this;
    } // setDeletions()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
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
            if ($this->has_paypal !== false) {
                return false;
            }

            if ($this->is_administrator !== false) {
                return false;
            }

            if ($this->is_moderator !== 0) {
                return false;
            }

            if ($this->deletions !== 0) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Nickname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nickname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Sha1Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sha1_password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Salt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->salt = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('HasPaypal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->has_paypal = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('IsAdministrator', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_administrator = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('IsModerator', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_moderator = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('Deletions', TableMap::TYPE_PHPNAME, $indexType)];
            $this->deletions = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\App\\Entity\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collQuestions = null;

            $this->collAnswers = null;

            $this->collInterests = null;

            $this->collRelevancies = null;

            $this->collQuestionTags = null;

            $this->collReportQuestions = null;

            $this->collReportAnswers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->questionsScheduledForDeletion !== null) {
                if (!$this->questionsScheduledForDeletion->isEmpty()) {
                    \App\Entity\QuestionQuery::create()
                        ->filterByPrimaryKeys($this->questionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->questionsScheduledForDeletion = null;
                }
            }

            if ($this->collQuestions !== null) {
                foreach ($this->collQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_NICKNAME)) {
            $modifiedColumns[':p' . $index++]  = 'nickname';
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'first_name';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'last_name';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_SHA1_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'sha1_password';
        }
        if ($this->isColumnModified(UserTableMap::COL_SALT)) {
            $modifiedColumns[':p' . $index++]  = 'salt';
        }
        if ($this->isColumnModified(UserTableMap::COL_HAS_PAYPAL)) {
            $modifiedColumns[':p' . $index++]  = 'has_paypal';
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ADMINISTRATOR)) {
            $modifiedColumns[':p' . $index++]  = 'is_administrator';
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_MODERATOR)) {
            $modifiedColumns[':p' . $index++]  = 'is_moderator';
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETIONS)) {
            $modifiedColumns[':p' . $index++]  = 'deletions';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO ask_user (%s) VALUES (%s)',
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
                    case 'nickname':
                        $stmt->bindValue($identifier, $this->nickname, PDO::PARAM_STR);
                        break;
                    case 'first_name':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case 'last_name':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'sha1_password':
                        $stmt->bindValue($identifier, $this->sha1_password, PDO::PARAM_STR);
                        break;
                    case 'salt':
                        $stmt->bindValue($identifier, $this->salt, PDO::PARAM_STR);
                        break;
                    case 'has_paypal':
                        $stmt->bindValue($identifier, (int) $this->has_paypal, PDO::PARAM_INT);
                        break;
                    case 'is_administrator':
                        $stmt->bindValue($identifier, (int) $this->is_administrator, PDO::PARAM_INT);
                        break;
                    case 'is_moderator':
                        $stmt->bindValue($identifier, $this->is_moderator, PDO::PARAM_INT);
                        break;
                    case 'deletions':
                        $stmt->bindValue($identifier, $this->deletions, PDO::PARAM_INT);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getNickname();
                break;
            case 2:
                return $this->getFirstName();
                break;
            case 3:
                return $this->getLastName();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
                return $this->getEmail();
                break;
            case 6:
                return $this->getSha1Password();
                break;
            case 7:
                return $this->getSalt();
                break;
            case 8:
                return $this->getHasPaypal();
                break;
            case 9:
                return $this->getIsAdministrator();
                break;
            case 10:
                return $this->getIsModerator();
                break;
            case 11:
                return $this->getDeletions();
                break;
            case 12:
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNickname(),
            $keys[2] => $this->getFirstName(),
            $keys[3] => $this->getLastName(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getEmail(),
            $keys[6] => $this->getSha1Password(),
            $keys[7] => $this->getSalt(),
            $keys[8] => $this->getHasPaypal(),
            $keys[9] => $this->getIsAdministrator(),
            $keys[10] => $this->getIsModerator(),
            $keys[11] => $this->getDeletions(),
            $keys[12] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collQuestions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'questions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'ask_questions';
                        break;
                    default:
                        $key = 'Questions';
                }

                $result[$key] = $this->collQuestions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\App\Entity\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\App\Entity\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setNickname($value);
                break;
            case 2:
                $this->setFirstName($value);
                break;
            case 3:
                $this->setLastName($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
                $this->setEmail($value);
                break;
            case 6:
                $this->setSha1Password($value);
                break;
            case 7:
                $this->setSalt($value);
                break;
            case 8:
                $this->setHasPaypal($value);
                break;
            case 9:
                $this->setIsAdministrator($value);
                break;
            case 10:
                $this->setIsModerator($value);
                break;
            case 11:
                $this->setDeletions($value);
                break;
            case 12:
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNickname($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFirstName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLastName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCreatedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEmail($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSha1Password($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSalt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setHasPaypal($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setIsAdministrator($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setIsModerator($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setDeletions($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
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
     * @return $this|\App\Entity\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_NICKNAME)) {
            $criteria->add(UserTableMap::COL_NICKNAME, $this->nickname);
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRST_NAME)) {
            $criteria->add(UserTableMap::COL_FIRST_NAME, $this->first_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_NAME)) {
            $criteria->add(UserTableMap::COL_LAST_NAME, $this->last_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_SHA1_PASSWORD)) {
            $criteria->add(UserTableMap::COL_SHA1_PASSWORD, $this->sha1_password);
        }
        if ($this->isColumnModified(UserTableMap::COL_SALT)) {
            $criteria->add(UserTableMap::COL_SALT, $this->salt);
        }
        if ($this->isColumnModified(UserTableMap::COL_HAS_PAYPAL)) {
            $criteria->add(UserTableMap::COL_HAS_PAYPAL, $this->has_paypal);
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ADMINISTRATOR)) {
            $criteria->add(UserTableMap::COL_IS_ADMINISTRATOR, $this->is_administrator);
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_MODERATOR)) {
            $criteria->add(UserTableMap::COL_IS_MODERATOR, $this->is_moderator);
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETIONS)) {
            $criteria->add(UserTableMap::COL_DELETIONS, $this->deletions);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \App\Entity\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNickname($this->getNickname());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setSha1Password($this->getSha1Password());
        $copyObj->setSalt($this->getSalt());
        $copyObj->setHasPaypal($this->getHasPaypal());
        $copyObj->setIsAdministrator($this->getIsAdministrator());
        $copyObj->setIsModerator($this->getIsModerator());
        $copyObj->setDeletions($this->getDeletions());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuestion($relObj->copy($deepCopy));
                }
            }

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

            foreach ($this->getRelevancies() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRelevancy($relObj->copy($deepCopy));
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
     * @return \App\Entity\User Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Question' == $relationName) {
            $this->initQuestions();
            return;
        }
        if ('Answer' == $relationName) {
            $this->initAnswers();
            return;
        }
        if ('Interest' == $relationName) {
            $this->initInterests();
            return;
        }
        if ('Relevancy' == $relationName) {
            $this->initRelevancies();
            return;
        }
        if ('QuestionTag' == $relationName) {
            $this->initQuestionTags();
            return;
        }
        if ('ReportQuestion' == $relationName) {
            $this->initReportQuestions();
            return;
        }
        if ('ReportAnswer' == $relationName) {
            $this->initReportAnswers();
            return;
        }
    }

    /**
     * Clears out the collQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuestions()
     */
    public function clearQuestions()
    {
        $this->collQuestions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collQuestions collection loaded partially.
     */
    public function resetPartialQuestions($v = true)
    {
        $this->collQuestionsPartial = $v;
    }

    /**
     * Initializes the collQuestions collection.
     *
     * By default this just sets the collQuestions collection to an empty array (like clearcollQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuestions($overrideExisting = true)
    {
        if (null !== $this->collQuestions && !$overrideExisting) {
            return;
        }

        $collectionClassName = QuestionTableMap::getTableMap()->getCollectionClassName();

        $this->collQuestions = new $collectionClassName;
        $this->collQuestions->setModel('\App\Entity\Question');
    }

    /**
     * Gets an array of ChildQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildQuestion[] List of ChildQuestion objects
     * @throws PropelException
     */
    public function getQuestions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionsPartial && !$this->isNew();
        if (null === $this->collQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQuestions) {
                // return empty collection
                $this->initQuestions();
            } else {
                $collQuestions = ChildQuestionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collQuestionsPartial && count($collQuestions)) {
                        $this->initQuestions(false);

                        foreach ($collQuestions as $obj) {
                            if (false == $this->collQuestions->contains($obj)) {
                                $this->collQuestions->append($obj);
                            }
                        }

                        $this->collQuestionsPartial = true;
                    }

                    return $collQuestions;
                }

                if ($partial && $this->collQuestions) {
                    foreach ($this->collQuestions as $obj) {
                        if ($obj->isNew()) {
                            $collQuestions[] = $obj;
                        }
                    }
                }

                $this->collQuestions = $collQuestions;
                $this->collQuestionsPartial = false;
            }
        }

        return $this->collQuestions;
    }

    /**
     * Sets a collection of ChildQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $questions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setQuestions(Collection $questions, ConnectionInterface $con = null)
    {
        /** @var ChildQuestion[] $questionsToDelete */
        $questionsToDelete = $this->getQuestions(new Criteria(), $con)->diff($questions);


        $this->questionsScheduledForDeletion = $questionsToDelete;

        foreach ($questionsToDelete as $questionRemoved) {
            $questionRemoved->setUser(null);
        }

        $this->collQuestions = null;
        foreach ($questions as $question) {
            $this->addQuestion($question);
        }

        $this->collQuestions = $questions;
        $this->collQuestionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Question objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Question objects.
     * @throws PropelException
     */
    public function countQuestions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collQuestionsPartial && !$this->isNew();
        if (null === $this->collQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuestions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getQuestions());
            }

            $query = ChildQuestionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collQuestions);
    }

    /**
     * Method called to associate a ChildQuestion object to this object
     * through the ChildQuestion foreign key attribute.
     *
     * @param  ChildQuestion $l ChildQuestion
     * @return $this|\App\Entity\User The current object (for fluent API support)
     */
    public function addQuestion(ChildQuestion $l)
    {
        if ($this->collQuestions === null) {
            $this->initQuestions();
            $this->collQuestionsPartial = true;
        }

        if (!$this->collQuestions->contains($l)) {
            $this->doAddQuestion($l);

            if ($this->questionsScheduledForDeletion and $this->questionsScheduledForDeletion->contains($l)) {
                $this->questionsScheduledForDeletion->remove($this->questionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildQuestion $question The ChildQuestion object to add.
     */
    protected function doAddQuestion(ChildQuestion $question)
    {
        $this->collQuestions[]= $question;
        $question->setUser($this);
    }

    /**
     * @param  ChildQuestion $question The ChildQuestion object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeQuestion(ChildQuestion $question)
    {
        if ($this->getQuestions()->contains($question)) {
            $pos = $this->collQuestions->search($question);
            $this->collQuestions->remove($pos);
            if (null === $this->questionsScheduledForDeletion) {
                $this->questionsScheduledForDeletion = clone $this->collQuestions;
                $this->questionsScheduledForDeletion->clear();
            }
            $this->questionsScheduledForDeletion[]= $question;
            $question->setUser(null);
        }

        return $this;
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
     * If this ChildUser is new, it will return
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
        if (null === $this->collAnswers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAnswers) {
                // return empty collection
                $this->initAnswers();
            } else {
                $collAnswers = ChildAnswerQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAnswers(Collection $answers, ConnectionInterface $con = null)
    {
        /** @var ChildAnswer[] $answersToDelete */
        $answersToDelete = $this->getAnswers(new Criteria(), $con)->diff($answers);


        $this->answersScheduledForDeletion = $answersToDelete;

        foreach ($answersToDelete as $answerRemoved) {
            $answerRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAnswers);
    }

    /**
     * Method called to associate a ChildAnswer object to this object
     * through the ChildAnswer foreign key attribute.
     *
     * @param  ChildAnswer $l ChildAnswer
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $answer->setUser($this);
    }

    /**
     * @param  ChildAnswer $answer The ChildAnswer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $answer->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Answers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAnswer[] List of ChildAnswer objects
     */
    public function getAnswersJoinQuestion(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAnswerQuery::create(null, $criteria);
        $query->joinWith('Question', $joinBehavior);

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
     * If this ChildUser is new, it will return
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
        if (null === $this->collInterests || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInterests) {
                // return empty collection
                $this->initInterests();
            } else {
                $collInterests = ChildInterestQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $interestRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collInterests);
    }

    /**
     * Method called to associate a ChildInterest object to this object
     * through the ChildInterest foreign key attribute.
     *
     * @param  ChildInterest $l ChildInterest
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $interest->setUser($this);
    }

    /**
     * @param  ChildInterest $interest The ChildInterest object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $interest->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Interests from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInterest[] List of ChildInterest objects
     */
    public function getInterestsJoinQuestion(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInterestQuery::create(null, $criteria);
        $query->joinWith('Question', $joinBehavior);

        return $this->getInterests($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $relevancyRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collRelevancies);
    }

    /**
     * Method called to associate a ChildRelevancy object to this object
     * through the ChildRelevancy foreign key attribute.
     *
     * @param  ChildRelevancy $l ChildRelevancy
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $relevancy->setUser($this);
    }

    /**
     * @param  ChildRelevancy $relevancy The ChildRelevancy object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $relevancy->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Relevancies from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRelevancy[] List of ChildRelevancy objects
     */
    public function getRelevanciesJoinAnswer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRelevancyQuery::create(null, $criteria);
        $query->joinWith('Answer', $joinBehavior);

        return $this->getRelevancies($query, $con);
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
     * If this ChildUser is new, it will return
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
        if (null === $this->collQuestionTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQuestionTags) {
                // return empty collection
                $this->initQuestionTags();
            } else {
                $collQuestionTags = ChildQuestionTagQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $questionTagRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collQuestionTags);
    }

    /**
     * Method called to associate a ChildQuestionTag object to this object
     * through the ChildQuestionTag foreign key attribute.
     *
     * @param  ChildQuestionTag $l ChildQuestionTag
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $questionTag->setUser($this);
    }

    /**
     * @param  ChildQuestionTag $questionTag The ChildQuestionTag object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $questionTag->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related QuestionTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildQuestionTag[] List of ChildQuestionTag objects
     */
    public function getQuestionTagsJoinQuestion(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildQuestionTagQuery::create(null, $criteria);
        $query->joinWith('Question', $joinBehavior);

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
     * If this ChildUser is new, it will return
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
        if (null === $this->collReportQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReportQuestions) {
                // return empty collection
                $this->initReportQuestions();
            } else {
                $collReportQuestions = ChildReportQuestionQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $reportQuestionRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collReportQuestions);
    }

    /**
     * Method called to associate a ChildReportQuestion object to this object
     * through the ChildReportQuestion foreign key attribute.
     *
     * @param  ChildReportQuestion $l ChildReportQuestion
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $reportQuestion->setUser($this);
    }

    /**
     * @param  ChildReportQuestion $reportQuestion The ChildReportQuestion object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $reportQuestion->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ReportQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildReportQuestion[] List of ChildReportQuestion objects
     */
    public function getReportQuestionsJoinQuestion(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildReportQuestionQuery::create(null, $criteria);
        $query->joinWith('Question', $joinBehavior);

        return $this->getReportQuestions($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $reportAnswerRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collReportAnswers);
    }

    /**
     * Method called to associate a ChildReportAnswer object to this object
     * through the ChildReportAnswer foreign key attribute.
     *
     * @param  ChildReportAnswer $l ChildReportAnswer
     * @return $this|\App\Entity\User The current object (for fluent API support)
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
        $reportAnswer->setUser($this);
    }

    /**
     * @param  ChildReportAnswer $reportAnswer The ChildReportAnswer object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $reportAnswer->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ReportAnswers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildReportAnswer[] List of ChildReportAnswer objects
     */
    public function getReportAnswersJoinAnswer(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildReportAnswerQuery::create(null, $criteria);
        $query->joinWith('Answer', $joinBehavior);

        return $this->getReportAnswers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->nickname = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->created_at = null;
        $this->email = null;
        $this->sha1_password = null;
        $this->salt = null;
        $this->has_paypal = null;
        $this->is_administrator = null;
        $this->is_moderator = null;
        $this->deletions = null;
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
            if ($this->collQuestions) {
                foreach ($this->collQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
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
            if ($this->collRelevancies) {
                foreach ($this->collRelevancies as $o) {
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
            if ($this->collReportAnswers) {
                foreach ($this->collReportAnswers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collQuestions = null;
        $this->collAnswers = null;
        $this->collInterests = null;
        $this->collRelevancies = null;
        $this->collQuestionTags = null;
        $this->collReportQuestions = null;
        $this->collReportAnswers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

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
