<?php

namespace SpoilerWiki\Base;

use \Exception;
use \PDO;
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
use SpoilerWiki\AssignedRole as ChildAssignedRole;
use SpoilerWiki\AssignedRoleGlobal as ChildAssignedRoleGlobal;
use SpoilerWiki\AssignedRoleGlobalQuery as ChildAssignedRoleGlobalQuery;
use SpoilerWiki\AssignedRoleQuery as ChildAssignedRoleQuery;
use SpoilerWiki\AuthToken as ChildAuthToken;
use SpoilerWiki\AuthTokenQuery as ChildAuthTokenQuery;
use SpoilerWiki\User as ChildUser;
use SpoilerWiki\UserQuery as ChildUserQuery;
use SpoilerWiki\Map\UserTableMap;

/**
 * Base class that represents a row from the 'user' table.
 *
 * 
 *
* @package    propel.generator.SpoilerWiki.Base
*/
abstract class User implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SpoilerWiki\\Map\\UserTableMap';


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
     * The value for the username field.
     * 
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     * 
     * @var        string
     */
    protected $password;

    /**
     * The value for the email field.
     * 
     * @var        string
     */
    protected $email;

    /**
     * @var        ObjectCollection|ChildAuthToken[] Collection to store aggregation of ChildAuthToken objects.
     */
    protected $collAuthTokens;
    protected $collAuthTokensPartial;

    /**
     * @var        ObjectCollection|ChildAssignedRoleGlobal[] Collection to store aggregation of ChildAssignedRoleGlobal objects.
     */
    protected $collAssignedRoleGlobalsRelatedByUserId;
    protected $collAssignedRoleGlobalsRelatedByUserIdPartial;

    /**
     * @var        ObjectCollection|ChildAssignedRoleGlobal[] Collection to store aggregation of ChildAssignedRoleGlobal objects.
     */
    protected $collAssignedRoleGlobalsRelatedByAssignedBy;
    protected $collAssignedRoleGlobalsRelatedByAssignedByPartial;

    /**
     * @var        ObjectCollection|ChildAssignedRole[] Collection to store aggregation of ChildAssignedRole objects.
     */
    protected $collAssignedRolesRelatedByUserId;
    protected $collAssignedRolesRelatedByUserIdPartial;

    /**
     * @var        ObjectCollection|ChildAssignedRole[] Collection to store aggregation of ChildAssignedRole objects.
     */
    protected $collAssignedRolesRelatedByAssignedBy;
    protected $collAssignedRolesRelatedByAssignedByPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAuthToken[]
     */
    protected $authTokensScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRoleGlobal[]
     */
    protected $assignedRoleGlobalsRelatedByUserIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRoleGlobal[]
     */
    protected $assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRole[]
     */
    protected $assignedRolesRelatedByUserIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRole[]
     */
    protected $assignedRolesRelatedByAssignedByScheduledForDeletion = null;

    /**
     * Initializes internal state of SpoilerWiki\Base\User object.
     */
    public function __construct()
    {
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
        foreach($cls->getProperties() as $property) {
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
     * Get the [username] column value.
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
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
     * Set the value of [username] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [email] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
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
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SpoilerWiki\\User'), 0, $e);
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

            $this->collAuthTokens = null;

            $this->collAssignedRoleGlobalsRelatedByUserId = null;

            $this->collAssignedRoleGlobalsRelatedByAssignedBy = null;

            $this->collAssignedRolesRelatedByUserId = null;

            $this->collAssignedRolesRelatedByAssignedBy = null;

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

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
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

            if ($this->authTokensScheduledForDeletion !== null) {
                if (!$this->authTokensScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AuthTokenQuery::create()
                        ->filterByPrimaryKeys($this->authTokensScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authTokensScheduledForDeletion = null;
                }
            }

            if ($this->collAuthTokens !== null) {
                foreach ($this->collAuthTokens as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion !== null) {
                if (!$this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleGlobalQuery::create()
                        ->filterByPrimaryKeys($this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRoleGlobalsRelatedByUserId !== null) {
                foreach ($this->collAssignedRoleGlobalsRelatedByUserId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion !== null) {
                if (!$this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleGlobalQuery::create()
                        ->filterByPrimaryKeys($this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRoleGlobalsRelatedByAssignedBy !== null) {
                foreach ($this->collAssignedRoleGlobalsRelatedByAssignedBy as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->assignedRolesRelatedByUserIdScheduledForDeletion !== null) {
                if (!$this->assignedRolesRelatedByUserIdScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleQuery::create()
                        ->filterByPrimaryKeys($this->assignedRolesRelatedByUserIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRolesRelatedByUserIdScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRolesRelatedByUserId !== null) {
                foreach ($this->collAssignedRolesRelatedByUserId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->assignedRolesRelatedByAssignedByScheduledForDeletion !== null) {
                if (!$this->assignedRolesRelatedByAssignedByScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleQuery::create()
                        ->filterByPrimaryKeys($this->assignedRolesRelatedByAssignedByScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRolesRelatedByAssignedByScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRolesRelatedByAssignedBy !== null) {
                foreach ($this->collAssignedRolesRelatedByAssignedBy as $referrerFK) {
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
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }

        $sql = sprintf(
            'INSERT INTO `user` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':                        
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`username`':                        
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`password`':                        
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`email`':                        
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
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
                return $this->getUsername();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getEmail();
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
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getEmail(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collAuthTokens) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'authTokens';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'auth_tokens';
                        break;
                    default:
                        $key = 'AuthTokens';
                }
        
                $result[$key] = $this->collAuthTokens->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAssignedRoleGlobalsRelatedByUserId) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assignedRoleGlobals';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assigned_role_globals';
                        break;
                    default:
                        $key = 'AssignedRoleGlobals';
                }
        
                $result[$key] = $this->collAssignedRoleGlobalsRelatedByUserId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAssignedRoleGlobalsRelatedByAssignedBy) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assignedRoleGlobals';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assigned_role_globals';
                        break;
                    default:
                        $key = 'AssignedRoleGlobals';
                }
        
                $result[$key] = $this->collAssignedRoleGlobalsRelatedByAssignedBy->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAssignedRolesRelatedByUserId) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assignedRoles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assigned_roles';
                        break;
                    default:
                        $key = 'AssignedRoles';
                }
        
                $result[$key] = $this->collAssignedRolesRelatedByUserId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAssignedRolesRelatedByAssignedBy) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'assignedRoles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'assigned_roles';
                        break;
                    default:
                        $key = 'AssignedRoles';
                }
        
                $result[$key] = $this->collAssignedRolesRelatedByAssignedBy->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\SpoilerWiki\User
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
     * @return $this|\SpoilerWiki\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setEmail($value);
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
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
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
     * @return $this|\SpoilerWiki\User The current object, for fluid interface
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
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
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
     * @param      object $copyObj An object of \SpoilerWiki\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setEmail($this->getEmail());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAuthTokens() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuthToken($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAssignedRoleGlobalsRelatedByUserId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRoleGlobalRelatedByUserId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAssignedRoleGlobalsRelatedByAssignedBy() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRoleGlobalRelatedByAssignedBy($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAssignedRolesRelatedByUserId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRoleRelatedByUserId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAssignedRolesRelatedByAssignedBy() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRoleRelatedByAssignedBy($relObj->copy($deepCopy));
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
     * @return \SpoilerWiki\User Clone of current object.
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
        if ('AuthToken' == $relationName) {
            return $this->initAuthTokens();
        }
        if ('AssignedRoleGlobalRelatedByUserId' == $relationName) {
            return $this->initAssignedRoleGlobalsRelatedByUserId();
        }
        if ('AssignedRoleGlobalRelatedByAssignedBy' == $relationName) {
            return $this->initAssignedRoleGlobalsRelatedByAssignedBy();
        }
        if ('AssignedRoleRelatedByUserId' == $relationName) {
            return $this->initAssignedRolesRelatedByUserId();
        }
        if ('AssignedRoleRelatedByAssignedBy' == $relationName) {
            return $this->initAssignedRolesRelatedByAssignedBy();
        }
    }

    /**
     * Clears out the collAuthTokens collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAuthTokens()
     */
    public function clearAuthTokens()
    {
        $this->collAuthTokens = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAuthTokens collection loaded partially.
     */
    public function resetPartialAuthTokens($v = true)
    {
        $this->collAuthTokensPartial = $v;
    }

    /**
     * Initializes the collAuthTokens collection.
     *
     * By default this just sets the collAuthTokens collection to an empty array (like clearcollAuthTokens());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuthTokens($overrideExisting = true)
    {
        if (null !== $this->collAuthTokens && !$overrideExisting) {
            return;
        }
        $this->collAuthTokens = new ObjectCollection();
        $this->collAuthTokens->setModel('\SpoilerWiki\AuthToken');
    }

    /**
     * Gets an array of ChildAuthToken objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAuthToken[] List of ChildAuthToken objects
     * @throws PropelException
     */
    public function getAuthTokens(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAuthTokensPartial && !$this->isNew();
        if (null === $this->collAuthTokens || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAuthTokens) {
                // return empty collection
                $this->initAuthTokens();
            } else {
                $collAuthTokens = ChildAuthTokenQuery::create(null, $criteria)
                    ->filterByuser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAuthTokensPartial && count($collAuthTokens)) {
                        $this->initAuthTokens(false);

                        foreach ($collAuthTokens as $obj) {
                            if (false == $this->collAuthTokens->contains($obj)) {
                                $this->collAuthTokens->append($obj);
                            }
                        }

                        $this->collAuthTokensPartial = true;
                    }

                    return $collAuthTokens;
                }

                if ($partial && $this->collAuthTokens) {
                    foreach ($this->collAuthTokens as $obj) {
                        if ($obj->isNew()) {
                            $collAuthTokens[] = $obj;
                        }
                    }
                }

                $this->collAuthTokens = $collAuthTokens;
                $this->collAuthTokensPartial = false;
            }
        }

        return $this->collAuthTokens;
    }

    /**
     * Sets a collection of ChildAuthToken objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $authTokens A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAuthTokens(Collection $authTokens, ConnectionInterface $con = null)
    {
        /** @var ChildAuthToken[] $authTokensToDelete */
        $authTokensToDelete = $this->getAuthTokens(new Criteria(), $con)->diff($authTokens);

        
        $this->authTokensScheduledForDeletion = $authTokensToDelete;

        foreach ($authTokensToDelete as $authTokenRemoved) {
            $authTokenRemoved->setuser(null);
        }

        $this->collAuthTokens = null;
        foreach ($authTokens as $authToken) {
            $this->addAuthToken($authToken);
        }

        $this->collAuthTokens = $authTokens;
        $this->collAuthTokensPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AuthToken objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AuthToken objects.
     * @throws PropelException
     */
    public function countAuthTokens(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAuthTokensPartial && !$this->isNew();
        if (null === $this->collAuthTokens || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuthTokens) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAuthTokens());
            }

            $query = ChildAuthTokenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByuser($this)
                ->count($con);
        }

        return count($this->collAuthTokens);
    }

    /**
     * Method called to associate a ChildAuthToken object to this object
     * through the ChildAuthToken foreign key attribute.
     *
     * @param  ChildAuthToken $l ChildAuthToken
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function addAuthToken(ChildAuthToken $l)
    {
        if ($this->collAuthTokens === null) {
            $this->initAuthTokens();
            $this->collAuthTokensPartial = true;
        }

        if (!$this->collAuthTokens->contains($l)) {
            $this->doAddAuthToken($l);
        }

        return $this;
    }

    /**
     * @param ChildAuthToken $authToken The ChildAuthToken object to add.
     */
    protected function doAddAuthToken(ChildAuthToken $authToken)
    {
        $this->collAuthTokens[]= $authToken;
        $authToken->setuser($this);
    }

    /**
     * @param  ChildAuthToken $authToken The ChildAuthToken object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAuthToken(ChildAuthToken $authToken)
    {
        if ($this->getAuthTokens()->contains($authToken)) {
            $pos = $this->collAuthTokens->search($authToken);
            $this->collAuthTokens->remove($pos);
            if (null === $this->authTokensScheduledForDeletion) {
                $this->authTokensScheduledForDeletion = clone $this->collAuthTokens;
                $this->authTokensScheduledForDeletion->clear();
            }
            $this->authTokensScheduledForDeletion[]= clone $authToken;
            $authToken->setuser(null);
        }

        return $this;
    }

    /**
     * Clears out the collAssignedRoleGlobalsRelatedByUserId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRoleGlobalsRelatedByUserId()
     */
    public function clearAssignedRoleGlobalsRelatedByUserId()
    {
        $this->collAssignedRoleGlobalsRelatedByUserId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRoleGlobalsRelatedByUserId collection loaded partially.
     */
    public function resetPartialAssignedRoleGlobalsRelatedByUserId($v = true)
    {
        $this->collAssignedRoleGlobalsRelatedByUserIdPartial = $v;
    }

    /**
     * Initializes the collAssignedRoleGlobalsRelatedByUserId collection.
     *
     * By default this just sets the collAssignedRoleGlobalsRelatedByUserId collection to an empty array (like clearcollAssignedRoleGlobalsRelatedByUserId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRoleGlobalsRelatedByUserId($overrideExisting = true)
    {
        if (null !== $this->collAssignedRoleGlobalsRelatedByUserId && !$overrideExisting) {
            return;
        }
        $this->collAssignedRoleGlobalsRelatedByUserId = new ObjectCollection();
        $this->collAssignedRoleGlobalsRelatedByUserId->setModel('\SpoilerWiki\AssignedRoleGlobal');
    }

    /**
     * Gets an array of ChildAssignedRoleGlobal objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     * @throws PropelException
     */
    public function getAssignedRoleGlobalsRelatedByUserId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobalsRelatedByUserId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobalsRelatedByUserId) {
                // return empty collection
                $this->initAssignedRoleGlobalsRelatedByUserId();
            } else {
                $collAssignedRoleGlobalsRelatedByUserId = ChildAssignedRoleGlobalQuery::create(null, $criteria)
                    ->filterByuser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRoleGlobalsRelatedByUserIdPartial && count($collAssignedRoleGlobalsRelatedByUserId)) {
                        $this->initAssignedRoleGlobalsRelatedByUserId(false);

                        foreach ($collAssignedRoleGlobalsRelatedByUserId as $obj) {
                            if (false == $this->collAssignedRoleGlobalsRelatedByUserId->contains($obj)) {
                                $this->collAssignedRoleGlobalsRelatedByUserId->append($obj);
                            }
                        }

                        $this->collAssignedRoleGlobalsRelatedByUserIdPartial = true;
                    }

                    return $collAssignedRoleGlobalsRelatedByUserId;
                }

                if ($partial && $this->collAssignedRoleGlobalsRelatedByUserId) {
                    foreach ($this->collAssignedRoleGlobalsRelatedByUserId as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRoleGlobalsRelatedByUserId[] = $obj;
                        }
                    }
                }

                $this->collAssignedRoleGlobalsRelatedByUserId = $collAssignedRoleGlobalsRelatedByUserId;
                $this->collAssignedRoleGlobalsRelatedByUserIdPartial = false;
            }
        }

        return $this->collAssignedRoleGlobalsRelatedByUserId;
    }

    /**
     * Sets a collection of ChildAssignedRoleGlobal objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRoleGlobalsRelatedByUserId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAssignedRoleGlobalsRelatedByUserId(Collection $assignedRoleGlobalsRelatedByUserId, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRoleGlobal[] $assignedRoleGlobalsRelatedByUserIdToDelete */
        $assignedRoleGlobalsRelatedByUserIdToDelete = $this->getAssignedRoleGlobalsRelatedByUserId(new Criteria(), $con)->diff($assignedRoleGlobalsRelatedByUserId);

        
        $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion = $assignedRoleGlobalsRelatedByUserIdToDelete;

        foreach ($assignedRoleGlobalsRelatedByUserIdToDelete as $assignedRoleGlobalRelatedByUserIdRemoved) {
            $assignedRoleGlobalRelatedByUserIdRemoved->setuser(null);
        }

        $this->collAssignedRoleGlobalsRelatedByUserId = null;
        foreach ($assignedRoleGlobalsRelatedByUserId as $assignedRoleGlobalRelatedByUserId) {
            $this->addAssignedRoleGlobalRelatedByUserId($assignedRoleGlobalRelatedByUserId);
        }

        $this->collAssignedRoleGlobalsRelatedByUserId = $assignedRoleGlobalsRelatedByUserId;
        $this->collAssignedRoleGlobalsRelatedByUserIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AssignedRoleGlobal objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AssignedRoleGlobal objects.
     * @throws PropelException
     */
    public function countAssignedRoleGlobalsRelatedByUserId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobalsRelatedByUserId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobalsRelatedByUserId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRoleGlobalsRelatedByUserId());
            }

            $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByuser($this)
                ->count($con);
        }

        return count($this->collAssignedRoleGlobalsRelatedByUserId);
    }

    /**
     * Method called to associate a ChildAssignedRoleGlobal object to this object
     * through the ChildAssignedRoleGlobal foreign key attribute.
     *
     * @param  ChildAssignedRoleGlobal $l ChildAssignedRoleGlobal
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function addAssignedRoleGlobalRelatedByUserId(ChildAssignedRoleGlobal $l)
    {
        if ($this->collAssignedRoleGlobalsRelatedByUserId === null) {
            $this->initAssignedRoleGlobalsRelatedByUserId();
            $this->collAssignedRoleGlobalsRelatedByUserIdPartial = true;
        }

        if (!$this->collAssignedRoleGlobalsRelatedByUserId->contains($l)) {
            $this->doAddAssignedRoleGlobalRelatedByUserId($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByUserId The ChildAssignedRoleGlobal object to add.
     */
    protected function doAddAssignedRoleGlobalRelatedByUserId(ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByUserId)
    {
        $this->collAssignedRoleGlobalsRelatedByUserId[]= $assignedRoleGlobalRelatedByUserId;
        $assignedRoleGlobalRelatedByUserId->setuser($this);
    }

    /**
     * @param  ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByUserId The ChildAssignedRoleGlobal object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAssignedRoleGlobalRelatedByUserId(ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByUserId)
    {
        if ($this->getAssignedRoleGlobalsRelatedByUserId()->contains($assignedRoleGlobalRelatedByUserId)) {
            $pos = $this->collAssignedRoleGlobalsRelatedByUserId->search($assignedRoleGlobalRelatedByUserId);
            $this->collAssignedRoleGlobalsRelatedByUserId->remove($pos);
            if (null === $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion) {
                $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion = clone $this->collAssignedRoleGlobalsRelatedByUserId;
                $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion->clear();
            }
            $this->assignedRoleGlobalsRelatedByUserIdScheduledForDeletion[]= clone $assignedRoleGlobalRelatedByUserId;
            $assignedRoleGlobalRelatedByUserId->setuser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRoleGlobalsRelatedByUserId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     */
    public function getAssignedRoleGlobalsRelatedByUserIdJoinrole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
        $query->joinWith('role', $joinBehavior);

        return $this->getAssignedRoleGlobalsRelatedByUserId($query, $con);
    }

    /**
     * Clears out the collAssignedRoleGlobalsRelatedByAssignedBy collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRoleGlobalsRelatedByAssignedBy()
     */
    public function clearAssignedRoleGlobalsRelatedByAssignedBy()
    {
        $this->collAssignedRoleGlobalsRelatedByAssignedBy = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRoleGlobalsRelatedByAssignedBy collection loaded partially.
     */
    public function resetPartialAssignedRoleGlobalsRelatedByAssignedBy($v = true)
    {
        $this->collAssignedRoleGlobalsRelatedByAssignedByPartial = $v;
    }

    /**
     * Initializes the collAssignedRoleGlobalsRelatedByAssignedBy collection.
     *
     * By default this just sets the collAssignedRoleGlobalsRelatedByAssignedBy collection to an empty array (like clearcollAssignedRoleGlobalsRelatedByAssignedBy());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRoleGlobalsRelatedByAssignedBy($overrideExisting = true)
    {
        if (null !== $this->collAssignedRoleGlobalsRelatedByAssignedBy && !$overrideExisting) {
            return;
        }
        $this->collAssignedRoleGlobalsRelatedByAssignedBy = new ObjectCollection();
        $this->collAssignedRoleGlobalsRelatedByAssignedBy->setModel('\SpoilerWiki\AssignedRoleGlobal');
    }

    /**
     * Gets an array of ChildAssignedRoleGlobal objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     * @throws PropelException
     */
    public function getAssignedRoleGlobalsRelatedByAssignedBy(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsRelatedByAssignedByPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobalsRelatedByAssignedBy || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobalsRelatedByAssignedBy) {
                // return empty collection
                $this->initAssignedRoleGlobalsRelatedByAssignedBy();
            } else {
                $collAssignedRoleGlobalsRelatedByAssignedBy = ChildAssignedRoleGlobalQuery::create(null, $criteria)
                    ->filterByassignedBy($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRoleGlobalsRelatedByAssignedByPartial && count($collAssignedRoleGlobalsRelatedByAssignedBy)) {
                        $this->initAssignedRoleGlobalsRelatedByAssignedBy(false);

                        foreach ($collAssignedRoleGlobalsRelatedByAssignedBy as $obj) {
                            if (false == $this->collAssignedRoleGlobalsRelatedByAssignedBy->contains($obj)) {
                                $this->collAssignedRoleGlobalsRelatedByAssignedBy->append($obj);
                            }
                        }

                        $this->collAssignedRoleGlobalsRelatedByAssignedByPartial = true;
                    }

                    return $collAssignedRoleGlobalsRelatedByAssignedBy;
                }

                if ($partial && $this->collAssignedRoleGlobalsRelatedByAssignedBy) {
                    foreach ($this->collAssignedRoleGlobalsRelatedByAssignedBy as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRoleGlobalsRelatedByAssignedBy[] = $obj;
                        }
                    }
                }

                $this->collAssignedRoleGlobalsRelatedByAssignedBy = $collAssignedRoleGlobalsRelatedByAssignedBy;
                $this->collAssignedRoleGlobalsRelatedByAssignedByPartial = false;
            }
        }

        return $this->collAssignedRoleGlobalsRelatedByAssignedBy;
    }

    /**
     * Sets a collection of ChildAssignedRoleGlobal objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRoleGlobalsRelatedByAssignedBy A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAssignedRoleGlobalsRelatedByAssignedBy(Collection $assignedRoleGlobalsRelatedByAssignedBy, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRoleGlobal[] $assignedRoleGlobalsRelatedByAssignedByToDelete */
        $assignedRoleGlobalsRelatedByAssignedByToDelete = $this->getAssignedRoleGlobalsRelatedByAssignedBy(new Criteria(), $con)->diff($assignedRoleGlobalsRelatedByAssignedBy);

        
        $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion = $assignedRoleGlobalsRelatedByAssignedByToDelete;

        foreach ($assignedRoleGlobalsRelatedByAssignedByToDelete as $assignedRoleGlobalRelatedByAssignedByRemoved) {
            $assignedRoleGlobalRelatedByAssignedByRemoved->setassignedBy(null);
        }

        $this->collAssignedRoleGlobalsRelatedByAssignedBy = null;
        foreach ($assignedRoleGlobalsRelatedByAssignedBy as $assignedRoleGlobalRelatedByAssignedBy) {
            $this->addAssignedRoleGlobalRelatedByAssignedBy($assignedRoleGlobalRelatedByAssignedBy);
        }

        $this->collAssignedRoleGlobalsRelatedByAssignedBy = $assignedRoleGlobalsRelatedByAssignedBy;
        $this->collAssignedRoleGlobalsRelatedByAssignedByPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AssignedRoleGlobal objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AssignedRoleGlobal objects.
     * @throws PropelException
     */
    public function countAssignedRoleGlobalsRelatedByAssignedBy(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsRelatedByAssignedByPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobalsRelatedByAssignedBy || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobalsRelatedByAssignedBy) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRoleGlobalsRelatedByAssignedBy());
            }

            $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByassignedBy($this)
                ->count($con);
        }

        return count($this->collAssignedRoleGlobalsRelatedByAssignedBy);
    }

    /**
     * Method called to associate a ChildAssignedRoleGlobal object to this object
     * through the ChildAssignedRoleGlobal foreign key attribute.
     *
     * @param  ChildAssignedRoleGlobal $l ChildAssignedRoleGlobal
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function addAssignedRoleGlobalRelatedByAssignedBy(ChildAssignedRoleGlobal $l)
    {
        if ($this->collAssignedRoleGlobalsRelatedByAssignedBy === null) {
            $this->initAssignedRoleGlobalsRelatedByAssignedBy();
            $this->collAssignedRoleGlobalsRelatedByAssignedByPartial = true;
        }

        if (!$this->collAssignedRoleGlobalsRelatedByAssignedBy->contains($l)) {
            $this->doAddAssignedRoleGlobalRelatedByAssignedBy($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByAssignedBy The ChildAssignedRoleGlobal object to add.
     */
    protected function doAddAssignedRoleGlobalRelatedByAssignedBy(ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByAssignedBy)
    {
        $this->collAssignedRoleGlobalsRelatedByAssignedBy[]= $assignedRoleGlobalRelatedByAssignedBy;
        $assignedRoleGlobalRelatedByAssignedBy->setassignedBy($this);
    }

    /**
     * @param  ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByAssignedBy The ChildAssignedRoleGlobal object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAssignedRoleGlobalRelatedByAssignedBy(ChildAssignedRoleGlobal $assignedRoleGlobalRelatedByAssignedBy)
    {
        if ($this->getAssignedRoleGlobalsRelatedByAssignedBy()->contains($assignedRoleGlobalRelatedByAssignedBy)) {
            $pos = $this->collAssignedRoleGlobalsRelatedByAssignedBy->search($assignedRoleGlobalRelatedByAssignedBy);
            $this->collAssignedRoleGlobalsRelatedByAssignedBy->remove($pos);
            if (null === $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion) {
                $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion = clone $this->collAssignedRoleGlobalsRelatedByAssignedBy;
                $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion->clear();
            }
            $this->assignedRoleGlobalsRelatedByAssignedByScheduledForDeletion[]= clone $assignedRoleGlobalRelatedByAssignedBy;
            $assignedRoleGlobalRelatedByAssignedBy->setassignedBy(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRoleGlobalsRelatedByAssignedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     */
    public function getAssignedRoleGlobalsRelatedByAssignedByJoinrole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
        $query->joinWith('role', $joinBehavior);

        return $this->getAssignedRoleGlobalsRelatedByAssignedBy($query, $con);
    }

    /**
     * Clears out the collAssignedRolesRelatedByUserId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRolesRelatedByUserId()
     */
    public function clearAssignedRolesRelatedByUserId()
    {
        $this->collAssignedRolesRelatedByUserId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRolesRelatedByUserId collection loaded partially.
     */
    public function resetPartialAssignedRolesRelatedByUserId($v = true)
    {
        $this->collAssignedRolesRelatedByUserIdPartial = $v;
    }

    /**
     * Initializes the collAssignedRolesRelatedByUserId collection.
     *
     * By default this just sets the collAssignedRolesRelatedByUserId collection to an empty array (like clearcollAssignedRolesRelatedByUserId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRolesRelatedByUserId($overrideExisting = true)
    {
        if (null !== $this->collAssignedRolesRelatedByUserId && !$overrideExisting) {
            return;
        }
        $this->collAssignedRolesRelatedByUserId = new ObjectCollection();
        $this->collAssignedRolesRelatedByUserId->setModel('\SpoilerWiki\AssignedRole');
    }

    /**
     * Gets an array of ChildAssignedRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     * @throws PropelException
     */
    public function getAssignedRolesRelatedByUserId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collAssignedRolesRelatedByUserId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRolesRelatedByUserId) {
                // return empty collection
                $this->initAssignedRolesRelatedByUserId();
            } else {
                $collAssignedRolesRelatedByUserId = ChildAssignedRoleQuery::create(null, $criteria)
                    ->filterByuser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRolesRelatedByUserIdPartial && count($collAssignedRolesRelatedByUserId)) {
                        $this->initAssignedRolesRelatedByUserId(false);

                        foreach ($collAssignedRolesRelatedByUserId as $obj) {
                            if (false == $this->collAssignedRolesRelatedByUserId->contains($obj)) {
                                $this->collAssignedRolesRelatedByUserId->append($obj);
                            }
                        }

                        $this->collAssignedRolesRelatedByUserIdPartial = true;
                    }

                    return $collAssignedRolesRelatedByUserId;
                }

                if ($partial && $this->collAssignedRolesRelatedByUserId) {
                    foreach ($this->collAssignedRolesRelatedByUserId as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRolesRelatedByUserId[] = $obj;
                        }
                    }
                }

                $this->collAssignedRolesRelatedByUserId = $collAssignedRolesRelatedByUserId;
                $this->collAssignedRolesRelatedByUserIdPartial = false;
            }
        }

        return $this->collAssignedRolesRelatedByUserId;
    }

    /**
     * Sets a collection of ChildAssignedRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRolesRelatedByUserId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAssignedRolesRelatedByUserId(Collection $assignedRolesRelatedByUserId, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRole[] $assignedRolesRelatedByUserIdToDelete */
        $assignedRolesRelatedByUserIdToDelete = $this->getAssignedRolesRelatedByUserId(new Criteria(), $con)->diff($assignedRolesRelatedByUserId);

        
        $this->assignedRolesRelatedByUserIdScheduledForDeletion = $assignedRolesRelatedByUserIdToDelete;

        foreach ($assignedRolesRelatedByUserIdToDelete as $assignedRoleRelatedByUserIdRemoved) {
            $assignedRoleRelatedByUserIdRemoved->setuser(null);
        }

        $this->collAssignedRolesRelatedByUserId = null;
        foreach ($assignedRolesRelatedByUserId as $assignedRoleRelatedByUserId) {
            $this->addAssignedRoleRelatedByUserId($assignedRoleRelatedByUserId);
        }

        $this->collAssignedRolesRelatedByUserId = $assignedRolesRelatedByUserId;
        $this->collAssignedRolesRelatedByUserIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AssignedRole objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AssignedRole objects.
     * @throws PropelException
     */
    public function countAssignedRolesRelatedByUserId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collAssignedRolesRelatedByUserId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRolesRelatedByUserId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRolesRelatedByUserId());
            }

            $query = ChildAssignedRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByuser($this)
                ->count($con);
        }

        return count($this->collAssignedRolesRelatedByUserId);
    }

    /**
     * Method called to associate a ChildAssignedRole object to this object
     * through the ChildAssignedRole foreign key attribute.
     *
     * @param  ChildAssignedRole $l ChildAssignedRole
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function addAssignedRoleRelatedByUserId(ChildAssignedRole $l)
    {
        if ($this->collAssignedRolesRelatedByUserId === null) {
            $this->initAssignedRolesRelatedByUserId();
            $this->collAssignedRolesRelatedByUserIdPartial = true;
        }

        if (!$this->collAssignedRolesRelatedByUserId->contains($l)) {
            $this->doAddAssignedRoleRelatedByUserId($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRole $assignedRoleRelatedByUserId The ChildAssignedRole object to add.
     */
    protected function doAddAssignedRoleRelatedByUserId(ChildAssignedRole $assignedRoleRelatedByUserId)
    {
        $this->collAssignedRolesRelatedByUserId[]= $assignedRoleRelatedByUserId;
        $assignedRoleRelatedByUserId->setuser($this);
    }

    /**
     * @param  ChildAssignedRole $assignedRoleRelatedByUserId The ChildAssignedRole object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAssignedRoleRelatedByUserId(ChildAssignedRole $assignedRoleRelatedByUserId)
    {
        if ($this->getAssignedRolesRelatedByUserId()->contains($assignedRoleRelatedByUserId)) {
            $pos = $this->collAssignedRolesRelatedByUserId->search($assignedRoleRelatedByUserId);
            $this->collAssignedRolesRelatedByUserId->remove($pos);
            if (null === $this->assignedRolesRelatedByUserIdScheduledForDeletion) {
                $this->assignedRolesRelatedByUserIdScheduledForDeletion = clone $this->collAssignedRolesRelatedByUserId;
                $this->assignedRolesRelatedByUserIdScheduledForDeletion->clear();
            }
            $this->assignedRolesRelatedByUserIdScheduledForDeletion[]= clone $assignedRoleRelatedByUserId;
            $assignedRoleRelatedByUserId->setuser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRolesRelatedByUserId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesRelatedByUserIdJoincanon(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('canon', $joinBehavior);

        return $this->getAssignedRolesRelatedByUserId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRolesRelatedByUserId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesRelatedByUserIdJoinrole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('role', $joinBehavior);

        return $this->getAssignedRolesRelatedByUserId($query, $con);
    }

    /**
     * Clears out the collAssignedRolesRelatedByAssignedBy collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRolesRelatedByAssignedBy()
     */
    public function clearAssignedRolesRelatedByAssignedBy()
    {
        $this->collAssignedRolesRelatedByAssignedBy = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRolesRelatedByAssignedBy collection loaded partially.
     */
    public function resetPartialAssignedRolesRelatedByAssignedBy($v = true)
    {
        $this->collAssignedRolesRelatedByAssignedByPartial = $v;
    }

    /**
     * Initializes the collAssignedRolesRelatedByAssignedBy collection.
     *
     * By default this just sets the collAssignedRolesRelatedByAssignedBy collection to an empty array (like clearcollAssignedRolesRelatedByAssignedBy());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRolesRelatedByAssignedBy($overrideExisting = true)
    {
        if (null !== $this->collAssignedRolesRelatedByAssignedBy && !$overrideExisting) {
            return;
        }
        $this->collAssignedRolesRelatedByAssignedBy = new ObjectCollection();
        $this->collAssignedRolesRelatedByAssignedBy->setModel('\SpoilerWiki\AssignedRole');
    }

    /**
     * Gets an array of ChildAssignedRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     * @throws PropelException
     */
    public function getAssignedRolesRelatedByAssignedBy(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesRelatedByAssignedByPartial && !$this->isNew();
        if (null === $this->collAssignedRolesRelatedByAssignedBy || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRolesRelatedByAssignedBy) {
                // return empty collection
                $this->initAssignedRolesRelatedByAssignedBy();
            } else {
                $collAssignedRolesRelatedByAssignedBy = ChildAssignedRoleQuery::create(null, $criteria)
                    ->filterByassignedBy($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRolesRelatedByAssignedByPartial && count($collAssignedRolesRelatedByAssignedBy)) {
                        $this->initAssignedRolesRelatedByAssignedBy(false);

                        foreach ($collAssignedRolesRelatedByAssignedBy as $obj) {
                            if (false == $this->collAssignedRolesRelatedByAssignedBy->contains($obj)) {
                                $this->collAssignedRolesRelatedByAssignedBy->append($obj);
                            }
                        }

                        $this->collAssignedRolesRelatedByAssignedByPartial = true;
                    }

                    return $collAssignedRolesRelatedByAssignedBy;
                }

                if ($partial && $this->collAssignedRolesRelatedByAssignedBy) {
                    foreach ($this->collAssignedRolesRelatedByAssignedBy as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRolesRelatedByAssignedBy[] = $obj;
                        }
                    }
                }

                $this->collAssignedRolesRelatedByAssignedBy = $collAssignedRolesRelatedByAssignedBy;
                $this->collAssignedRolesRelatedByAssignedByPartial = false;
            }
        }

        return $this->collAssignedRolesRelatedByAssignedBy;
    }

    /**
     * Sets a collection of ChildAssignedRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRolesRelatedByAssignedBy A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAssignedRolesRelatedByAssignedBy(Collection $assignedRolesRelatedByAssignedBy, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRole[] $assignedRolesRelatedByAssignedByToDelete */
        $assignedRolesRelatedByAssignedByToDelete = $this->getAssignedRolesRelatedByAssignedBy(new Criteria(), $con)->diff($assignedRolesRelatedByAssignedBy);

        
        $this->assignedRolesRelatedByAssignedByScheduledForDeletion = $assignedRolesRelatedByAssignedByToDelete;

        foreach ($assignedRolesRelatedByAssignedByToDelete as $assignedRoleRelatedByAssignedByRemoved) {
            $assignedRoleRelatedByAssignedByRemoved->setassignedBy(null);
        }

        $this->collAssignedRolesRelatedByAssignedBy = null;
        foreach ($assignedRolesRelatedByAssignedBy as $assignedRoleRelatedByAssignedBy) {
            $this->addAssignedRoleRelatedByAssignedBy($assignedRoleRelatedByAssignedBy);
        }

        $this->collAssignedRolesRelatedByAssignedBy = $assignedRolesRelatedByAssignedBy;
        $this->collAssignedRolesRelatedByAssignedByPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AssignedRole objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AssignedRole objects.
     * @throws PropelException
     */
    public function countAssignedRolesRelatedByAssignedBy(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesRelatedByAssignedByPartial && !$this->isNew();
        if (null === $this->collAssignedRolesRelatedByAssignedBy || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRolesRelatedByAssignedBy) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRolesRelatedByAssignedBy());
            }

            $query = ChildAssignedRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByassignedBy($this)
                ->count($con);
        }

        return count($this->collAssignedRolesRelatedByAssignedBy);
    }

    /**
     * Method called to associate a ChildAssignedRole object to this object
     * through the ChildAssignedRole foreign key attribute.
     *
     * @param  ChildAssignedRole $l ChildAssignedRole
     * @return $this|\SpoilerWiki\User The current object (for fluent API support)
     */
    public function addAssignedRoleRelatedByAssignedBy(ChildAssignedRole $l)
    {
        if ($this->collAssignedRolesRelatedByAssignedBy === null) {
            $this->initAssignedRolesRelatedByAssignedBy();
            $this->collAssignedRolesRelatedByAssignedByPartial = true;
        }

        if (!$this->collAssignedRolesRelatedByAssignedBy->contains($l)) {
            $this->doAddAssignedRoleRelatedByAssignedBy($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRole $assignedRoleRelatedByAssignedBy The ChildAssignedRole object to add.
     */
    protected function doAddAssignedRoleRelatedByAssignedBy(ChildAssignedRole $assignedRoleRelatedByAssignedBy)
    {
        $this->collAssignedRolesRelatedByAssignedBy[]= $assignedRoleRelatedByAssignedBy;
        $assignedRoleRelatedByAssignedBy->setassignedBy($this);
    }

    /**
     * @param  ChildAssignedRole $assignedRoleRelatedByAssignedBy The ChildAssignedRole object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAssignedRoleRelatedByAssignedBy(ChildAssignedRole $assignedRoleRelatedByAssignedBy)
    {
        if ($this->getAssignedRolesRelatedByAssignedBy()->contains($assignedRoleRelatedByAssignedBy)) {
            $pos = $this->collAssignedRolesRelatedByAssignedBy->search($assignedRoleRelatedByAssignedBy);
            $this->collAssignedRolesRelatedByAssignedBy->remove($pos);
            if (null === $this->assignedRolesRelatedByAssignedByScheduledForDeletion) {
                $this->assignedRolesRelatedByAssignedByScheduledForDeletion = clone $this->collAssignedRolesRelatedByAssignedBy;
                $this->assignedRolesRelatedByAssignedByScheduledForDeletion->clear();
            }
            $this->assignedRolesRelatedByAssignedByScheduledForDeletion[]= clone $assignedRoleRelatedByAssignedBy;
            $assignedRoleRelatedByAssignedBy->setassignedBy(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRolesRelatedByAssignedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesRelatedByAssignedByJoincanon(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('canon', $joinBehavior);

        return $this->getAssignedRolesRelatedByAssignedBy($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AssignedRolesRelatedByAssignedBy from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesRelatedByAssignedByJoinrole(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('role', $joinBehavior);

        return $this->getAssignedRolesRelatedByAssignedBy($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->password = null;
        $this->email = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collAuthTokens) {
                foreach ($this->collAuthTokens as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAssignedRoleGlobalsRelatedByUserId) {
                foreach ($this->collAssignedRoleGlobalsRelatedByUserId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAssignedRoleGlobalsRelatedByAssignedBy) {
                foreach ($this->collAssignedRoleGlobalsRelatedByAssignedBy as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAssignedRolesRelatedByUserId) {
                foreach ($this->collAssignedRolesRelatedByUserId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAssignedRolesRelatedByAssignedBy) {
                foreach ($this->collAssignedRolesRelatedByAssignedBy as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAuthTokens = null;
        $this->collAssignedRoleGlobalsRelatedByUserId = null;
        $this->collAssignedRoleGlobalsRelatedByAssignedBy = null;
        $this->collAssignedRolesRelatedByUserId = null;
        $this->collAssignedRolesRelatedByAssignedBy = null;
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
