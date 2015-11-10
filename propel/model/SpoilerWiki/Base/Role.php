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
use SpoilerWiki\Role as ChildRole;
use SpoilerWiki\RoleQuery as ChildRoleQuery;
use SpoilerWiki\Map\RoleTableMap;

/**
 * Base class that represents a row from the 'role' table.
 *
 * 
 *
* @package    propel.generator.SpoilerWiki.Base
*/
abstract class Role implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SpoilerWiki\\Map\\RoleTableMap';


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
     * The value for the name field.
     * 
     * @var        string
     */
    protected $name;

    /**
     * The value for the description field.
     * 
     * @var        string
     */
    protected $description;

    /**
     * @var        ObjectCollection|ChildAssignedRoleGlobal[] Collection to store aggregation of ChildAssignedRoleGlobal objects.
     */
    protected $collAssignedRoleGlobals;
    protected $collAssignedRoleGlobalsPartial;

    /**
     * @var        ObjectCollection|ChildAssignedRole[] Collection to store aggregation of ChildAssignedRole objects.
     */
    protected $collAssignedRoles;
    protected $collAssignedRolesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRoleGlobal[]
     */
    protected $assignedRoleGlobalsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAssignedRole[]
     */
    protected $assignedRolesScheduledForDeletion = null;

    /**
     * Initializes internal state of SpoilerWiki\Base\Role object.
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
     * Compares this with another <code>Role</code> instance.  If
     * <code>obj</code> is an instance of <code>Role</code>, delegates to
     * <code>equals(Role)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Role The current object, for fluid interface
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
     * Get the [name] column value.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [description] column value.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Role The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[RoleTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\Role The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[RoleTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\Role The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[RoleTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RoleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RoleTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RoleTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = RoleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SpoilerWiki\\Role'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(RoleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildRoleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collAssignedRoleGlobals = null;

            $this->collAssignedRoles = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Role::setDeleted()
     * @see Role::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildRoleQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
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
                RoleTableMap::addInstanceToPool($this);
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

            if ($this->assignedRoleGlobalsScheduledForDeletion !== null) {
                if (!$this->assignedRoleGlobalsScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleGlobalQuery::create()
                        ->filterByPrimaryKeys($this->assignedRoleGlobalsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRoleGlobalsScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRoleGlobals !== null) {
                foreach ($this->collAssignedRoleGlobals as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->assignedRolesScheduledForDeletion !== null) {
                if (!$this->assignedRolesScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\AssignedRoleQuery::create()
                        ->filterByPrimaryKeys($this->assignedRolesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->assignedRolesScheduledForDeletion = null;
                }
            }

            if ($this->collAssignedRoles !== null) {
                foreach ($this->collAssignedRoles as $referrerFK) {
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

        $this->modifiedColumns[RoleTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RoleTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(RoleTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(RoleTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }

        $sql = sprintf(
            'INSERT INTO `role` (%s) VALUES (%s)',
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
                    case '`name`':                        
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`description`':                        
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
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
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getDescription();
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

        if (isset($alreadyDumpedObjects['Role'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Role'][$this->hashCode()] = true;
        $keys = RoleTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collAssignedRoleGlobals) {
                
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
        
                $result[$key] = $this->collAssignedRoleGlobals->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAssignedRoles) {
                
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
        
                $result[$key] = $this->collAssignedRoles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\SpoilerWiki\Role
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SpoilerWiki\Role
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setDescription($value);
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
        $keys = RoleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
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
     * @return $this|\SpoilerWiki\Role The current object, for fluid interface
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
        $criteria = new Criteria(RoleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $criteria->add(RoleTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_NAME)) {
            $criteria->add(RoleTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(RoleTableMap::COL_DESCRIPTION)) {
            $criteria->add(RoleTableMap::COL_DESCRIPTION, $this->description);
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
        $criteria = ChildRoleQuery::create();
        $criteria->add(RoleTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \SpoilerWiki\Role (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAssignedRoleGlobals() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRoleGlobal($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAssignedRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAssignedRole($relObj->copy($deepCopy));
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
     * @return \SpoilerWiki\Role Clone of current object.
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
        if ('AssignedRoleGlobal' == $relationName) {
            return $this->initAssignedRoleGlobals();
        }
        if ('AssignedRole' == $relationName) {
            return $this->initAssignedRoles();
        }
    }

    /**
     * Clears out the collAssignedRoleGlobals collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRoleGlobals()
     */
    public function clearAssignedRoleGlobals()
    {
        $this->collAssignedRoleGlobals = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRoleGlobals collection loaded partially.
     */
    public function resetPartialAssignedRoleGlobals($v = true)
    {
        $this->collAssignedRoleGlobalsPartial = $v;
    }

    /**
     * Initializes the collAssignedRoleGlobals collection.
     *
     * By default this just sets the collAssignedRoleGlobals collection to an empty array (like clearcollAssignedRoleGlobals());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRoleGlobals($overrideExisting = true)
    {
        if (null !== $this->collAssignedRoleGlobals && !$overrideExisting) {
            return;
        }
        $this->collAssignedRoleGlobals = new ObjectCollection();
        $this->collAssignedRoleGlobals->setModel('\SpoilerWiki\AssignedRoleGlobal');
    }

    /**
     * Gets an array of ChildAssignedRoleGlobal objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     * @throws PropelException
     */
    public function getAssignedRoleGlobals(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobals || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobals) {
                // return empty collection
                $this->initAssignedRoleGlobals();
            } else {
                $collAssignedRoleGlobals = ChildAssignedRoleGlobalQuery::create(null, $criteria)
                    ->filterByrole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRoleGlobalsPartial && count($collAssignedRoleGlobals)) {
                        $this->initAssignedRoleGlobals(false);

                        foreach ($collAssignedRoleGlobals as $obj) {
                            if (false == $this->collAssignedRoleGlobals->contains($obj)) {
                                $this->collAssignedRoleGlobals->append($obj);
                            }
                        }

                        $this->collAssignedRoleGlobalsPartial = true;
                    }

                    return $collAssignedRoleGlobals;
                }

                if ($partial && $this->collAssignedRoleGlobals) {
                    foreach ($this->collAssignedRoleGlobals as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRoleGlobals[] = $obj;
                        }
                    }
                }

                $this->collAssignedRoleGlobals = $collAssignedRoleGlobals;
                $this->collAssignedRoleGlobalsPartial = false;
            }
        }

        return $this->collAssignedRoleGlobals;
    }

    /**
     * Sets a collection of ChildAssignedRoleGlobal objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRoleGlobals A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setAssignedRoleGlobals(Collection $assignedRoleGlobals, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRoleGlobal[] $assignedRoleGlobalsToDelete */
        $assignedRoleGlobalsToDelete = $this->getAssignedRoleGlobals(new Criteria(), $con)->diff($assignedRoleGlobals);

        
        $this->assignedRoleGlobalsScheduledForDeletion = $assignedRoleGlobalsToDelete;

        foreach ($assignedRoleGlobalsToDelete as $assignedRoleGlobalRemoved) {
            $assignedRoleGlobalRemoved->setrole(null);
        }

        $this->collAssignedRoleGlobals = null;
        foreach ($assignedRoleGlobals as $assignedRoleGlobal) {
            $this->addAssignedRoleGlobal($assignedRoleGlobal);
        }

        $this->collAssignedRoleGlobals = $assignedRoleGlobals;
        $this->collAssignedRoleGlobalsPartial = false;

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
    public function countAssignedRoleGlobals(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRoleGlobalsPartial && !$this->isNew();
        if (null === $this->collAssignedRoleGlobals || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoleGlobals) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRoleGlobals());
            }

            $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByrole($this)
                ->count($con);
        }

        return count($this->collAssignedRoleGlobals);
    }

    /**
     * Method called to associate a ChildAssignedRoleGlobal object to this object
     * through the ChildAssignedRoleGlobal foreign key attribute.
     *
     * @param  ChildAssignedRoleGlobal $l ChildAssignedRoleGlobal
     * @return $this|\SpoilerWiki\Role The current object (for fluent API support)
     */
    public function addAssignedRoleGlobal(ChildAssignedRoleGlobal $l)
    {
        if ($this->collAssignedRoleGlobals === null) {
            $this->initAssignedRoleGlobals();
            $this->collAssignedRoleGlobalsPartial = true;
        }

        if (!$this->collAssignedRoleGlobals->contains($l)) {
            $this->doAddAssignedRoleGlobal($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRoleGlobal $assignedRoleGlobal The ChildAssignedRoleGlobal object to add.
     */
    protected function doAddAssignedRoleGlobal(ChildAssignedRoleGlobal $assignedRoleGlobal)
    {
        $this->collAssignedRoleGlobals[]= $assignedRoleGlobal;
        $assignedRoleGlobal->setrole($this);
    }

    /**
     * @param  ChildAssignedRoleGlobal $assignedRoleGlobal The ChildAssignedRoleGlobal object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeAssignedRoleGlobal(ChildAssignedRoleGlobal $assignedRoleGlobal)
    {
        if ($this->getAssignedRoleGlobals()->contains($assignedRoleGlobal)) {
            $pos = $this->collAssignedRoleGlobals->search($assignedRoleGlobal);
            $this->collAssignedRoleGlobals->remove($pos);
            if (null === $this->assignedRoleGlobalsScheduledForDeletion) {
                $this->assignedRoleGlobalsScheduledForDeletion = clone $this->collAssignedRoleGlobals;
                $this->assignedRoleGlobalsScheduledForDeletion->clear();
            }
            $this->assignedRoleGlobalsScheduledForDeletion[]= clone $assignedRoleGlobal;
            $assignedRoleGlobal->setrole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related AssignedRoleGlobals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     */
    public function getAssignedRoleGlobalsJoinuser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
        $query->joinWith('user', $joinBehavior);

        return $this->getAssignedRoleGlobals($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related AssignedRoleGlobals from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRoleGlobal[] List of ChildAssignedRoleGlobal objects
     */
    public function getAssignedRoleGlobalsJoinassignedBy(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleGlobalQuery::create(null, $criteria);
        $query->joinWith('assignedBy', $joinBehavior);

        return $this->getAssignedRoleGlobals($query, $con);
    }

    /**
     * Clears out the collAssignedRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAssignedRoles()
     */
    public function clearAssignedRoles()
    {
        $this->collAssignedRoles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAssignedRoles collection loaded partially.
     */
    public function resetPartialAssignedRoles($v = true)
    {
        $this->collAssignedRolesPartial = $v;
    }

    /**
     * Initializes the collAssignedRoles collection.
     *
     * By default this just sets the collAssignedRoles collection to an empty array (like clearcollAssignedRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAssignedRoles($overrideExisting = true)
    {
        if (null !== $this->collAssignedRoles && !$overrideExisting) {
            return;
        }
        $this->collAssignedRoles = new ObjectCollection();
        $this->collAssignedRoles->setModel('\SpoilerWiki\AssignedRole');
    }

    /**
     * Gets an array of ChildAssignedRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     * @throws PropelException
     */
    public function getAssignedRoles(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesPartial && !$this->isNew();
        if (null === $this->collAssignedRoles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoles) {
                // return empty collection
                $this->initAssignedRoles();
            } else {
                $collAssignedRoles = ChildAssignedRoleQuery::create(null, $criteria)
                    ->filterByrole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAssignedRolesPartial && count($collAssignedRoles)) {
                        $this->initAssignedRoles(false);

                        foreach ($collAssignedRoles as $obj) {
                            if (false == $this->collAssignedRoles->contains($obj)) {
                                $this->collAssignedRoles->append($obj);
                            }
                        }

                        $this->collAssignedRolesPartial = true;
                    }

                    return $collAssignedRoles;
                }

                if ($partial && $this->collAssignedRoles) {
                    foreach ($this->collAssignedRoles as $obj) {
                        if ($obj->isNew()) {
                            $collAssignedRoles[] = $obj;
                        }
                    }
                }

                $this->collAssignedRoles = $collAssignedRoles;
                $this->collAssignedRolesPartial = false;
            }
        }

        return $this->collAssignedRoles;
    }

    /**
     * Sets a collection of ChildAssignedRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $assignedRoles A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setAssignedRoles(Collection $assignedRoles, ConnectionInterface $con = null)
    {
        /** @var ChildAssignedRole[] $assignedRolesToDelete */
        $assignedRolesToDelete = $this->getAssignedRoles(new Criteria(), $con)->diff($assignedRoles);

        
        $this->assignedRolesScheduledForDeletion = $assignedRolesToDelete;

        foreach ($assignedRolesToDelete as $assignedRoleRemoved) {
            $assignedRoleRemoved->setrole(null);
        }

        $this->collAssignedRoles = null;
        foreach ($assignedRoles as $assignedRole) {
            $this->addAssignedRole($assignedRole);
        }

        $this->collAssignedRoles = $assignedRoles;
        $this->collAssignedRolesPartial = false;

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
    public function countAssignedRoles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAssignedRolesPartial && !$this->isNew();
        if (null === $this->collAssignedRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAssignedRoles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAssignedRoles());
            }

            $query = ChildAssignedRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByrole($this)
                ->count($con);
        }

        return count($this->collAssignedRoles);
    }

    /**
     * Method called to associate a ChildAssignedRole object to this object
     * through the ChildAssignedRole foreign key attribute.
     *
     * @param  ChildAssignedRole $l ChildAssignedRole
     * @return $this|\SpoilerWiki\Role The current object (for fluent API support)
     */
    public function addAssignedRole(ChildAssignedRole $l)
    {
        if ($this->collAssignedRoles === null) {
            $this->initAssignedRoles();
            $this->collAssignedRolesPartial = true;
        }

        if (!$this->collAssignedRoles->contains($l)) {
            $this->doAddAssignedRole($l);
        }

        return $this;
    }

    /**
     * @param ChildAssignedRole $assignedRole The ChildAssignedRole object to add.
     */
    protected function doAddAssignedRole(ChildAssignedRole $assignedRole)
    {
        $this->collAssignedRoles[]= $assignedRole;
        $assignedRole->setrole($this);
    }

    /**
     * @param  ChildAssignedRole $assignedRole The ChildAssignedRole object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeAssignedRole(ChildAssignedRole $assignedRole)
    {
        if ($this->getAssignedRoles()->contains($assignedRole)) {
            $pos = $this->collAssignedRoles->search($assignedRole);
            $this->collAssignedRoles->remove($pos);
            if (null === $this->assignedRolesScheduledForDeletion) {
                $this->assignedRolesScheduledForDeletion = clone $this->collAssignedRoles;
                $this->assignedRolesScheduledForDeletion->clear();
            }
            $this->assignedRolesScheduledForDeletion[]= clone $assignedRole;
            $assignedRole->setrole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related AssignedRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesJoinuser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('user', $joinBehavior);

        return $this->getAssignedRoles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related AssignedRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesJoinassignedBy(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('assignedBy', $joinBehavior);

        return $this->getAssignedRoles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related AssignedRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAssignedRole[] List of ChildAssignedRole objects
     */
    public function getAssignedRolesJoincanon(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAssignedRoleQuery::create(null, $criteria);
        $query->joinWith('canon', $joinBehavior);

        return $this->getAssignedRoles($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->description = null;
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
            if ($this->collAssignedRoleGlobals) {
                foreach ($this->collAssignedRoleGlobals as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAssignedRoles) {
                foreach ($this->collAssignedRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAssignedRoleGlobals = null;
        $this->collAssignedRoles = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RoleTableMap::DEFAULT_STRING_FORMAT);
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
