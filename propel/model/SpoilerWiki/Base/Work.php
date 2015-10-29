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
use SpoilerWiki\Artist as ChildArtist;
use SpoilerWiki\ArtistQuery as ChildArtistQuery;
use SpoilerWiki\Canon as ChildCanon;
use SpoilerWiki\CanonQuery as ChildCanonQuery;
use SpoilerWiki\Milestone as ChildMilestone;
use SpoilerWiki\MilestoneQuery as ChildMilestoneQuery;
use SpoilerWiki\Work as ChildWork;
use SpoilerWiki\WorkQuery as ChildWorkQuery;
use SpoilerWiki\Map\WorkTableMap;

/**
 * Base class that represents a row from the 'work' table.
 *
 * 
 *
* @package    propel.generator.SpoilerWiki.Base
*/
abstract class Work implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SpoilerWiki\\Map\\WorkTableMap';


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
     * The value for the primary_artist_id field.
     * 
     * @var        int
     */
    protected $primary_artist_id;

    /**
     * The value for the canon_id field.
     * 
     * @var        int
     */
    protected $canon_id;

    /**
     * The value for the sortable_rank field.
     * 
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        ChildArtist
     */
    protected $aprimaryArtist;

    /**
     * @var        ChildCanon
     */
    protected $acanon;

    /**
     * @var        ObjectCollection|ChildMilestone[] Collection to store aggregation of ChildMilestone objects.
     */
    protected $collMilestones;
    protected $collMilestonesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // sortable behavior
    
    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMilestone[]
     */
    protected $milestonesScheduledForDeletion = null;

    /**
     * Initializes internal state of SpoilerWiki\Base\Work object.
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
     * Compares this with another <code>Work</code> instance.  If
     * <code>obj</code> is an instance of <code>Work</code>, delegates to
     * <code>equals(Work)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Work The current object, for fluid interface
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
     * Get the [primary_artist_id] column value.
     * 
     * @return int
     */
    public function getPrimaryArtistId()
    {
        return $this->primary_artist_id;
    }

    /**
     * Get the [canon_id] column value.
     * 
     * @return int
     */
    public function getCanonId()
    {
        return $this->canon_id;
    }

    /**
     * Get the [sortable_rank] column value.
     * 
     * @return int
     */
    public function getSortableRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[WorkTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[WorkTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [description] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[WorkTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [primary_artist_id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setPrimaryArtistId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->primary_artist_id !== $v) {
            $this->primary_artist_id = $v;
            $this->modifiedColumns[WorkTableMap::COL_PRIMARY_ARTIST_ID] = true;
        }

        if ($this->aprimaryArtist !== null && $this->aprimaryArtist->getId() !== $v) {
            $this->aprimaryArtist = null;
        }

        return $this;
    } // setPrimaryArtistId()

    /**
     * Set the value of [canon_id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setCanonId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->canon_id !== $v) {
            $this->canon_id = $v;
            $this->modifiedColumns[WorkTableMap::COL_CANON_ID] = true;
        }

        if ($this->acanon !== null && $this->acanon->getId() !== $v) {
            $this->acanon = null;
        }

        return $this;
    } // setCanonId()

    /**
     * Set the value of [sortable_rank] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[WorkTableMap::COL_SORTABLE_RANK] = true;
        }

        return $this;
    } // setSortableRank()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : WorkTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : WorkTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : WorkTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : WorkTableMap::translateFieldName('PrimaryArtistId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primary_artist_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : WorkTableMap::translateFieldName('CanonId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->canon_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : WorkTableMap::translateFieldName('SortableRank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sortable_rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = WorkTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SpoilerWiki\\Work'), 0, $e);
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
        if ($this->aprimaryArtist !== null && $this->primary_artist_id !== $this->aprimaryArtist->getId()) {
            $this->aprimaryArtist = null;
        }
        if ($this->acanon !== null && $this->canon_id !== $this->acanon->getId()) {
            $this->acanon = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(WorkTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildWorkQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aprimaryArtist = null;
            $this->acanon = null;
            $this->collMilestones = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Work::setDeleted()
     * @see Work::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildWorkQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior
            
            ChildWorkQuery::sortableShiftRank(-1, $this->getSortableRank() + 1, null, $con);
            WorkTableMap::clearInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(WorkTableMap::RANK_COL)) {
                    $this->setSortableRank(ChildWorkQuery::create()->getMaxRankArray($con) + 1);
                }

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
                WorkTableMap::addInstanceToPool($this);
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

            if ($this->aprimaryArtist !== null) {
                if ($this->aprimaryArtist->isModified() || $this->aprimaryArtist->isNew()) {
                    $affectedRows += $this->aprimaryArtist->save($con);
                }
                $this->setprimaryArtist($this->aprimaryArtist);
            }

            if ($this->acanon !== null) {
                if ($this->acanon->isModified() || $this->acanon->isNew()) {
                    $affectedRows += $this->acanon->save($con);
                }
                $this->setcanon($this->acanon);
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

            if ($this->milestonesScheduledForDeletion !== null) {
                if (!$this->milestonesScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\MilestoneQuery::create()
                        ->filterByPrimaryKeys($this->milestonesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->milestonesScheduledForDeletion = null;
                }
            }

            if ($this->collMilestones !== null) {
                foreach ($this->collMilestones as $referrerFK) {
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

        $this->modifiedColumns[WorkTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WorkTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WorkTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(WorkTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(WorkTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`description`';
        }
        if ($this->isColumnModified(WorkTableMap::COL_PRIMARY_ARTIST_ID)) {
            $modifiedColumns[':p' . $index++]  = '`primary_artist_id`';
        }
        if ($this->isColumnModified(WorkTableMap::COL_CANON_ID)) {
            $modifiedColumns[':p' . $index++]  = '`canon_id`';
        }
        if ($this->isColumnModified(WorkTableMap::COL_SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `work` (%s) VALUES (%s)',
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
                    case '`primary_artist_id`':                        
                        $stmt->bindValue($identifier, $this->primary_artist_id, PDO::PARAM_INT);
                        break;
                    case '`canon_id`':                        
                        $stmt->bindValue($identifier, $this->canon_id, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':                        
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
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
        $pos = WorkTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
            case 3:
                return $this->getPrimaryArtistId();
                break;
            case 4:
                return $this->getCanonId();
                break;
            case 5:
                return $this->getSortableRank();
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

        if (isset($alreadyDumpedObjects['Work'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Work'][$this->hashCode()] = true;
        $keys = WorkTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getPrimaryArtistId(),
            $keys[4] => $this->getCanonId(),
            $keys[5] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aprimaryArtist) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'artist';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'artist';
                        break;
                    default:
                        $key = 'Artist';
                }
        
                $result[$key] = $this->aprimaryArtist->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->acanon) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'canon';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'canon';
                        break;
                    default:
                        $key = 'Canon';
                }
        
                $result[$key] = $this->acanon->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMilestones) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'milestones';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'milestones';
                        break;
                    default:
                        $key = 'Milestones';
                }
        
                $result[$key] = $this->collMilestones->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\SpoilerWiki\Work
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = WorkTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SpoilerWiki\Work
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
            case 3:
                $this->setPrimaryArtistId($value);
                break;
            case 4:
                $this->setCanonId($value);
                break;
            case 5:
                $this->setSortableRank($value);
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
        $keys = WorkTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPrimaryArtistId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCanonId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSortableRank($arr[$keys[5]]);
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
     * @return $this|\SpoilerWiki\Work The current object, for fluid interface
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
        $criteria = new Criteria(WorkTableMap::DATABASE_NAME);

        if ($this->isColumnModified(WorkTableMap::COL_ID)) {
            $criteria->add(WorkTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(WorkTableMap::COL_NAME)) {
            $criteria->add(WorkTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(WorkTableMap::COL_DESCRIPTION)) {
            $criteria->add(WorkTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(WorkTableMap::COL_PRIMARY_ARTIST_ID)) {
            $criteria->add(WorkTableMap::COL_PRIMARY_ARTIST_ID, $this->primary_artist_id);
        }
        if ($this->isColumnModified(WorkTableMap::COL_CANON_ID)) {
            $criteria->add(WorkTableMap::COL_CANON_ID, $this->canon_id);
        }
        if ($this->isColumnModified(WorkTableMap::COL_SORTABLE_RANK)) {
            $criteria->add(WorkTableMap::COL_SORTABLE_RANK, $this->sortable_rank);
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
        $criteria = ChildWorkQuery::create();
        $criteria->add(WorkTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \SpoilerWiki\Work (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setPrimaryArtistId($this->getPrimaryArtistId());
        $copyObj->setCanonId($this->getCanonId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMilestones() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMilestone($relObj->copy($deepCopy));
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
     * @return \SpoilerWiki\Work Clone of current object.
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
     * Declares an association between this object and a ChildArtist object.
     *
     * @param  ChildArtist $v
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setprimaryArtist(ChildArtist $v = null)
    {
        if ($v === null) {
            $this->setPrimaryArtistId(NULL);
        } else {
            $this->setPrimaryArtistId($v->getId());
        }

        $this->aprimaryArtist = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildArtist object, it will not be re-added.
        if ($v !== null) {
            $v->addWork($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildArtist object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildArtist The associated ChildArtist object.
     * @throws PropelException
     */
    public function getprimaryArtist(ConnectionInterface $con = null)
    {
        if ($this->aprimaryArtist === null && ($this->primary_artist_id !== null)) {
            $this->aprimaryArtist = ChildArtistQuery::create()->findPk($this->primary_artist_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aprimaryArtist->addWorks($this);
             */
        }

        return $this->aprimaryArtist;
    }

    /**
     * Declares an association between this object and a ChildCanon object.
     *
     * @param  ChildCanon $v
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     * @throws PropelException
     */
    public function setcanon(ChildCanon $v = null)
    {
        if ($v === null) {
            $this->setCanonId(NULL);
        } else {
            $this->setCanonId($v->getId());
        }

        $this->acanon = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCanon object, it will not be re-added.
        if ($v !== null) {
            $v->addWork($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCanon object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCanon The associated ChildCanon object.
     * @throws PropelException
     */
    public function getcanon(ConnectionInterface $con = null)
    {
        if ($this->acanon === null && ($this->canon_id !== null)) {
            $this->acanon = ChildCanonQuery::create()->findPk($this->canon_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->acanon->addWorks($this);
             */
        }

        return $this->acanon;
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
        if ('Milestone' == $relationName) {
            return $this->initMilestones();
        }
    }

    /**
     * Clears out the collMilestones collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMilestones()
     */
    public function clearMilestones()
    {
        $this->collMilestones = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMilestones collection loaded partially.
     */
    public function resetPartialMilestones($v = true)
    {
        $this->collMilestonesPartial = $v;
    }

    /**
     * Initializes the collMilestones collection.
     *
     * By default this just sets the collMilestones collection to an empty array (like clearcollMilestones());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMilestones($overrideExisting = true)
    {
        if (null !== $this->collMilestones && !$overrideExisting) {
            return;
        }
        $this->collMilestones = new ObjectCollection();
        $this->collMilestones->setModel('\SpoilerWiki\Milestone');
    }

    /**
     * Gets an array of ChildMilestone objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWork is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMilestone[] List of ChildMilestone objects
     * @throws PropelException
     */
    public function getMilestones(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMilestonesPartial && !$this->isNew();
        if (null === $this->collMilestones || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMilestones) {
                // return empty collection
                $this->initMilestones();
            } else {
                $collMilestones = ChildMilestoneQuery::create(null, $criteria)
                    ->filterBywork($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMilestonesPartial && count($collMilestones)) {
                        $this->initMilestones(false);

                        foreach ($collMilestones as $obj) {
                            if (false == $this->collMilestones->contains($obj)) {
                                $this->collMilestones->append($obj);
                            }
                        }

                        $this->collMilestonesPartial = true;
                    }

                    return $collMilestones;
                }

                if ($partial && $this->collMilestones) {
                    foreach ($this->collMilestones as $obj) {
                        if ($obj->isNew()) {
                            $collMilestones[] = $obj;
                        }
                    }
                }

                $this->collMilestones = $collMilestones;
                $this->collMilestonesPartial = false;
            }
        }

        return $this->collMilestones;
    }

    /**
     * Sets a collection of ChildMilestone objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $milestones A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWork The current object (for fluent API support)
     */
    public function setMilestones(Collection $milestones, ConnectionInterface $con = null)
    {
        /** @var ChildMilestone[] $milestonesToDelete */
        $milestonesToDelete = $this->getMilestones(new Criteria(), $con)->diff($milestones);

        
        $this->milestonesScheduledForDeletion = $milestonesToDelete;

        foreach ($milestonesToDelete as $milestoneRemoved) {
            $milestoneRemoved->setwork(null);
        }

        $this->collMilestones = null;
        foreach ($milestones as $milestone) {
            $this->addMilestone($milestone);
        }

        $this->collMilestones = $milestones;
        $this->collMilestonesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Milestone objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Milestone objects.
     * @throws PropelException
     */
    public function countMilestones(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMilestonesPartial && !$this->isNew();
        if (null === $this->collMilestones || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMilestones) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMilestones());
            }

            $query = ChildMilestoneQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBywork($this)
                ->count($con);
        }

        return count($this->collMilestones);
    }

    /**
     * Method called to associate a ChildMilestone object to this object
     * through the ChildMilestone foreign key attribute.
     *
     * @param  ChildMilestone $l ChildMilestone
     * @return $this|\SpoilerWiki\Work The current object (for fluent API support)
     */
    public function addMilestone(ChildMilestone $l)
    {
        if ($this->collMilestones === null) {
            $this->initMilestones();
            $this->collMilestonesPartial = true;
        }

        if (!$this->collMilestones->contains($l)) {
            $this->doAddMilestone($l);
        }

        return $this;
    }

    /**
     * @param ChildMilestone $milestone The ChildMilestone object to add.
     */
    protected function doAddMilestone(ChildMilestone $milestone)
    {
        $this->collMilestones[]= $milestone;
        $milestone->setwork($this);
    }

    /**
     * @param  ChildMilestone $milestone The ChildMilestone object to remove.
     * @return $this|ChildWork The current object (for fluent API support)
     */
    public function removeMilestone(ChildMilestone $milestone)
    {
        if ($this->getMilestones()->contains($milestone)) {
            $pos = $this->collMilestones->search($milestone);
            $this->collMilestones->remove($pos);
            if (null === $this->milestonesScheduledForDeletion) {
                $this->milestonesScheduledForDeletion = clone $this->collMilestones;
                $this->milestonesScheduledForDeletion->clear();
            }
            $this->milestonesScheduledForDeletion[]= clone $milestone;
            $milestone->setwork(null);
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
        if (null !== $this->aprimaryArtist) {
            $this->aprimaryArtist->removeWork($this);
        }
        if (null !== $this->acanon) {
            $this->acanon->removeWork($this);
        }
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->primary_artist_id = null;
        $this->canon_id = null;
        $this->sortable_rank = null;
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
            if ($this->collMilestones) {
                foreach ($this->collMilestones as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMilestones = null;
        $this->aprimaryArtist = null;
        $this->acanon = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WorkTableMap::DEFAULT_STRING_FORMAT);
    }

    // sortable behavior
    
    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }
    
    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    $this|ChildWork
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }
    
    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }
    
    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(ConnectionInterface $con = null)
    {
        return $this->getSortableRank() == ChildWorkQuery::create()->getMaxRankArray($con);
    }
    
    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildWork
     */
    public function getNext(ConnectionInterface $con = null)
    {
    
        $query = ChildWorkQuery::create();
    
        $query->filterByRank($this->getSortableRank() + 1);
    
    
        return $query->findOne($con);
    }
    
    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildWork
     */
    public function getPrevious(ConnectionInterface $con = null)
    {
    
        $query = ChildWorkQuery::create();
    
        $query->filterByRank($this->getSortableRank() - 1);
    
    
        return $query->findOne($con);
    }
    
    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    $this|ChildWork the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, ConnectionInterface $con = null)
    {
        $maxRank = ChildWorkQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array('\SpoilerWiki\WorkQuery', 'sortableShiftRank'),
                'arguments' => array(1, $rank, null, )
            );
        }
    
        return $this;
    }
    
    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(ConnectionInterface $con = null)
    {
        $this->setSortableRank(ChildWorkQuery::create()->getMaxRankArray($con) + 1);
    
        return $this;
    }
    
    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildWork the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }
    
    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, ConnectionInterface $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ChildWorkQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }
    
        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }
    
        $con->transaction(function () use ($con, $oldRank, $newRank) {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            ChildWorkQuery::sortableShiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);
    
            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);
        });
    
        return $this;
    }
    
    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     ChildWork $object
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con, $object) {
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
    
            $this->setSortableRank($newRank);
            $object->setSortableRank($oldRank);
    
            $this->save($con);
            $object->save($con);
        });
    
        return $this;
    }
    
    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     */
    public function moveUp(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con) {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
        });
    
        return $this;
    }
    
    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     */
    public function moveDown(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }
        $con->transaction(function () use ($con) {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
        });
    
        return $this;
    }
    
    /**
     * Move the object to the top of the list
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildWork the current object
     */
    public function moveToTop(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
    
        return $this->moveToRank(1, $con);
    }
    
    /**
     * Move the object to the bottom of the list
     *
     * @param     ConnectionInterface $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }
    
        return $con->transaction(function () use ($con) {
            $bottom = ChildWorkQuery::create()->getMaxRankArray($con);
    
            return $this->moveToRank($bottom, $con);
        });
    }
    
    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildWork the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries[] = array(
            'callable'  => array('\SpoilerWiki\WorkQuery', 'sortableShiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null)
        );
        // remove the object from the list
        $this->setSortableRank(null);
        
    
        return $this;
    }
    
    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
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
