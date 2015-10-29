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
use SpoilerWiki\Milestone as ChildMilestone;
use SpoilerWiki\MilestoneQuery as ChildMilestoneQuery;
use SpoilerWiki\Snippet as ChildSnippet;
use SpoilerWiki\SnippetQuery as ChildSnippetQuery;
use SpoilerWiki\Summary as ChildSummary;
use SpoilerWiki\SummaryQuery as ChildSummaryQuery;
use SpoilerWiki\Topic as ChildTopic;
use SpoilerWiki\TopicQuery as ChildTopicQuery;
use SpoilerWiki\Work as ChildWork;
use SpoilerWiki\WorkQuery as ChildWorkQuery;
use SpoilerWiki\Map\MilestoneTableMap;

/**
 * Base class that represents a row from the 'milestone' table.
 *
 * 
 *
* @package    propel.generator.SpoilerWiki.Base
*/
abstract class Milestone implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SpoilerWiki\\Map\\MilestoneTableMap';


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
     * The value for the label field.
     * 
     * @var        string
     */
    protected $label;

    /**
     * The value for the work_id field.
     * 
     * @var        int
     */
    protected $work_id;

    /**
     * The value for the sortable_rank field.
     * 
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        ChildWork
     */
    protected $awork;

    /**
     * @var        ObjectCollection|ChildTopic[] Collection to store aggregation of ChildTopic objects.
     */
    protected $collTopics;
    protected $collTopicsPartial;

    /**
     * @var        ObjectCollection|ChildSnippet[] Collection to store aggregation of ChildSnippet objects.
     */
    protected $collSnippets;
    protected $collSnippetsPartial;

    /**
     * @var        ObjectCollection|ChildSummary[] Collection to store aggregation of ChildSummary objects.
     */
    protected $collSummaries;
    protected $collSummariesPartial;

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
     * @var ObjectCollection|ChildTopic[]
     */
    protected $topicsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSnippet[]
     */
    protected $snippetsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSummary[]
     */
    protected $summariesScheduledForDeletion = null;

    /**
     * Initializes internal state of SpoilerWiki\Base\Milestone object.
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
     * Compares this with another <code>Milestone</code> instance.  If
     * <code>obj</code> is an instance of <code>Milestone</code>, delegates to
     * <code>equals(Milestone)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Milestone The current object, for fluid interface
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
     * Get the [label] column value.
     * 
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the [work_id] column value.
     * 
     * @return int
     */
    public function getWorkId()
    {
        return $this->work_id;
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
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MilestoneTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [label] column.
     * 
     * @param string $v new value
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function setLabel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->label !== $v) {
            $this->label = $v;
            $this->modifiedColumns[MilestoneTableMap::COL_LABEL] = true;
        }

        return $this;
    } // setLabel()

    /**
     * Set the value of [work_id] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function setWorkId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->work_id !== $v) {
            $this->work_id = $v;
            $this->modifiedColumns[MilestoneTableMap::COL_WORK_ID] = true;
        }

        if ($this->awork !== null && $this->awork->getId() !== $v) {
            $this->awork = null;
        }

        return $this;
    } // setWorkId()

    /**
     * Set the value of [sortable_rank] column.
     * 
     * @param int $v new value
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[MilestoneTableMap::COL_SORTABLE_RANK] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MilestoneTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MilestoneTableMap::translateFieldName('Label', TableMap::TYPE_PHPNAME, $indexType)];
            $this->label = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MilestoneTableMap::translateFieldName('WorkId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->work_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MilestoneTableMap::translateFieldName('SortableRank', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sortable_rank = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = MilestoneTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SpoilerWiki\\Milestone'), 0, $e);
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
        if ($this->awork !== null && $this->work_id !== $this->awork->getId()) {
            $this->awork = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMilestoneQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->awork = null;
            $this->collTopics = null;

            $this->collSnippets = null;

            $this->collSummaries = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Milestone::setDeleted()
     * @see Milestone::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMilestoneQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior
            
            ChildMilestoneQuery::sortableShiftRank(-1, $this->getSortableRank() + 1, null, $con);
            MilestoneTableMap::clearInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(MilestoneTableMap::RANK_COL)) {
                    $this->setSortableRank(ChildMilestoneQuery::create()->getMaxRankArray($con) + 1);
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
                MilestoneTableMap::addInstanceToPool($this);
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

            if ($this->awork !== null) {
                if ($this->awork->isModified() || $this->awork->isNew()) {
                    $affectedRows += $this->awork->save($con);
                }
                $this->setwork($this->awork);
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

            if ($this->topicsScheduledForDeletion !== null) {
                if (!$this->topicsScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\TopicQuery::create()
                        ->filterByPrimaryKeys($this->topicsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->topicsScheduledForDeletion = null;
                }
            }

            if ($this->collTopics !== null) {
                foreach ($this->collTopics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->snippetsScheduledForDeletion !== null) {
                if (!$this->snippetsScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\SnippetQuery::create()
                        ->filterByPrimaryKeys($this->snippetsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->snippetsScheduledForDeletion = null;
                }
            }

            if ($this->collSnippets !== null) {
                foreach ($this->collSnippets as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->summariesScheduledForDeletion !== null) {
                if (!$this->summariesScheduledForDeletion->isEmpty()) {
                    \SpoilerWiki\SummaryQuery::create()
                        ->filterByPrimaryKeys($this->summariesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->summariesScheduledForDeletion = null;
                }
            }

            if ($this->collSummaries !== null) {
                foreach ($this->collSummaries as $referrerFK) {
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

        $this->modifiedColumns[MilestoneTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MilestoneTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MilestoneTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_LABEL)) {
            $modifiedColumns[':p' . $index++]  = '`label`';
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_WORK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`work_id`';
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `milestone` (%s) VALUES (%s)',
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
                    case '`label`':                        
                        $stmt->bindValue($identifier, $this->label, PDO::PARAM_STR);
                        break;
                    case '`work_id`':                        
                        $stmt->bindValue($identifier, $this->work_id, PDO::PARAM_INT);
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
        $pos = MilestoneTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getLabel();
                break;
            case 2:
                return $this->getWorkId();
                break;
            case 3:
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

        if (isset($alreadyDumpedObjects['Milestone'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Milestone'][$this->hashCode()] = true;
        $keys = MilestoneTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLabel(),
            $keys[2] => $this->getWorkId(),
            $keys[3] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->awork) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'work';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'work';
                        break;
                    default:
                        $key = 'Work';
                }
        
                $result[$key] = $this->awork->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collTopics) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'topics';
                        break;
                    default:
                        $key = 'Topics';
                }
        
                $result[$key] = $this->collTopics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSnippets) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'snippets';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'snippets';
                        break;
                    default:
                        $key = 'Snippets';
                }
        
                $result[$key] = $this->collSnippets->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSummaries) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'summaries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'summaries';
                        break;
                    default:
                        $key = 'Summaries';
                }
        
                $result[$key] = $this->collSummaries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\SpoilerWiki\Milestone
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MilestoneTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SpoilerWiki\Milestone
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setLabel($value);
                break;
            case 2:
                $this->setWorkId($value);
                break;
            case 3:
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
        $keys = MilestoneTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLabel($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setWorkId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSortableRank($arr[$keys[3]]);
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
     * @return $this|\SpoilerWiki\Milestone The current object, for fluid interface
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
        $criteria = new Criteria(MilestoneTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MilestoneTableMap::COL_ID)) {
            $criteria->add(MilestoneTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_LABEL)) {
            $criteria->add(MilestoneTableMap::COL_LABEL, $this->label);
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_WORK_ID)) {
            $criteria->add(MilestoneTableMap::COL_WORK_ID, $this->work_id);
        }
        if ($this->isColumnModified(MilestoneTableMap::COL_SORTABLE_RANK)) {
            $criteria->add(MilestoneTableMap::COL_SORTABLE_RANK, $this->sortable_rank);
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
        $criteria = ChildMilestoneQuery::create();
        $criteria->add(MilestoneTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \SpoilerWiki\Milestone (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLabel($this->getLabel());
        $copyObj->setWorkId($this->getWorkId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTopics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTopic($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSnippets() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSnippet($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSummaries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSummary($relObj->copy($deepCopy));
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
     * @return \SpoilerWiki\Milestone Clone of current object.
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
     * Declares an association between this object and a ChildWork object.
     *
     * @param  ChildWork $v
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     * @throws PropelException
     */
    public function setwork(ChildWork $v = null)
    {
        if ($v === null) {
            $this->setWorkId(NULL);
        } else {
            $this->setWorkId($v->getId());
        }

        $this->awork = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildWork object, it will not be re-added.
        if ($v !== null) {
            $v->addMilestone($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildWork object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildWork The associated ChildWork object.
     * @throws PropelException
     */
    public function getwork(ConnectionInterface $con = null)
    {
        if ($this->awork === null && ($this->work_id !== null)) {
            $this->awork = ChildWorkQuery::create()->findPk($this->work_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->awork->addMilestones($this);
             */
        }

        return $this->awork;
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
        if ('Topic' == $relationName) {
            return $this->initTopics();
        }
        if ('Snippet' == $relationName) {
            return $this->initSnippets();
        }
        if ('Summary' == $relationName) {
            return $this->initSummaries();
        }
    }

    /**
     * Clears out the collTopics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTopics()
     */
    public function clearTopics()
    {
        $this->collTopics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTopics collection loaded partially.
     */
    public function resetPartialTopics($v = true)
    {
        $this->collTopicsPartial = $v;
    }

    /**
     * Initializes the collTopics collection.
     *
     * By default this just sets the collTopics collection to an empty array (like clearcollTopics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTopics($overrideExisting = true)
    {
        if (null !== $this->collTopics && !$overrideExisting) {
            return;
        }
        $this->collTopics = new ObjectCollection();
        $this->collTopics->setModel('\SpoilerWiki\Topic');
    }

    /**
     * Gets an array of ChildTopic objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMilestone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTopic[] List of ChildTopic objects
     * @throws PropelException
     */
    public function getTopics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicsPartial && !$this->isNew();
        if (null === $this->collTopics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTopics) {
                // return empty collection
                $this->initTopics();
            } else {
                $collTopics = ChildTopicQuery::create(null, $criteria)
                    ->filterByintroducedAt($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTopicsPartial && count($collTopics)) {
                        $this->initTopics(false);

                        foreach ($collTopics as $obj) {
                            if (false == $this->collTopics->contains($obj)) {
                                $this->collTopics->append($obj);
                            }
                        }

                        $this->collTopicsPartial = true;
                    }

                    return $collTopics;
                }

                if ($partial && $this->collTopics) {
                    foreach ($this->collTopics as $obj) {
                        if ($obj->isNew()) {
                            $collTopics[] = $obj;
                        }
                    }
                }

                $this->collTopics = $collTopics;
                $this->collTopicsPartial = false;
            }
        }

        return $this->collTopics;
    }

    /**
     * Sets a collection of ChildTopic objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $topics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function setTopics(Collection $topics, ConnectionInterface $con = null)
    {
        /** @var ChildTopic[] $topicsToDelete */
        $topicsToDelete = $this->getTopics(new Criteria(), $con)->diff($topics);

        
        $this->topicsScheduledForDeletion = $topicsToDelete;

        foreach ($topicsToDelete as $topicRemoved) {
            $topicRemoved->setintroducedAt(null);
        }

        $this->collTopics = null;
        foreach ($topics as $topic) {
            $this->addTopic($topic);
        }

        $this->collTopics = $topics;
        $this->collTopicsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Topic objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Topic objects.
     * @throws PropelException
     */
    public function countTopics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicsPartial && !$this->isNew();
        if (null === $this->collTopics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTopics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTopics());
            }

            $query = ChildTopicQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByintroducedAt($this)
                ->count($con);
        }

        return count($this->collTopics);
    }

    /**
     * Method called to associate a ChildTopic object to this object
     * through the ChildTopic foreign key attribute.
     *
     * @param  ChildTopic $l ChildTopic
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function addTopic(ChildTopic $l)
    {
        if ($this->collTopics === null) {
            $this->initTopics();
            $this->collTopicsPartial = true;
        }

        if (!$this->collTopics->contains($l)) {
            $this->doAddTopic($l);
        }

        return $this;
    }

    /**
     * @param ChildTopic $topic The ChildTopic object to add.
     */
    protected function doAddTopic(ChildTopic $topic)
    {
        $this->collTopics[]= $topic;
        $topic->setintroducedAt($this);
    }

    /**
     * @param  ChildTopic $topic The ChildTopic object to remove.
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function removeTopic(ChildTopic $topic)
    {
        if ($this->getTopics()->contains($topic)) {
            $pos = $this->collTopics->search($topic);
            $this->collTopics->remove($pos);
            if (null === $this->topicsScheduledForDeletion) {
                $this->topicsScheduledForDeletion = clone $this->collTopics;
                $this->topicsScheduledForDeletion->clear();
            }
            $this->topicsScheduledForDeletion[]= clone $topic;
            $topic->setintroducedAt(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Milestone is new, it will return
     * an empty collection; or if this Milestone has previously
     * been saved, it will retrieve related Topics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Milestone.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTopic[] List of ChildTopic objects
     */
    public function getTopicsJoincanon(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTopicQuery::create(null, $criteria);
        $query->joinWith('canon', $joinBehavior);

        return $this->getTopics($query, $con);
    }

    /**
     * Clears out the collSnippets collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSnippets()
     */
    public function clearSnippets()
    {
        $this->collSnippets = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSnippets collection loaded partially.
     */
    public function resetPartialSnippets($v = true)
    {
        $this->collSnippetsPartial = $v;
    }

    /**
     * Initializes the collSnippets collection.
     *
     * By default this just sets the collSnippets collection to an empty array (like clearcollSnippets());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSnippets($overrideExisting = true)
    {
        if (null !== $this->collSnippets && !$overrideExisting) {
            return;
        }
        $this->collSnippets = new ObjectCollection();
        $this->collSnippets->setModel('\SpoilerWiki\Snippet');
    }

    /**
     * Gets an array of ChildSnippet objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMilestone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSnippet[] List of ChildSnippet objects
     * @throws PropelException
     */
    public function getSnippets(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSnippetsPartial && !$this->isNew();
        if (null === $this->collSnippets || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSnippets) {
                // return empty collection
                $this->initSnippets();
            } else {
                $collSnippets = ChildSnippetQuery::create(null, $criteria)
                    ->filterByintroducedAt($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSnippetsPartial && count($collSnippets)) {
                        $this->initSnippets(false);

                        foreach ($collSnippets as $obj) {
                            if (false == $this->collSnippets->contains($obj)) {
                                $this->collSnippets->append($obj);
                            }
                        }

                        $this->collSnippetsPartial = true;
                    }

                    return $collSnippets;
                }

                if ($partial && $this->collSnippets) {
                    foreach ($this->collSnippets as $obj) {
                        if ($obj->isNew()) {
                            $collSnippets[] = $obj;
                        }
                    }
                }

                $this->collSnippets = $collSnippets;
                $this->collSnippetsPartial = false;
            }
        }

        return $this->collSnippets;
    }

    /**
     * Sets a collection of ChildSnippet objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $snippets A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function setSnippets(Collection $snippets, ConnectionInterface $con = null)
    {
        /** @var ChildSnippet[] $snippetsToDelete */
        $snippetsToDelete = $this->getSnippets(new Criteria(), $con)->diff($snippets);

        
        $this->snippetsScheduledForDeletion = $snippetsToDelete;

        foreach ($snippetsToDelete as $snippetRemoved) {
            $snippetRemoved->setintroducedAt(null);
        }

        $this->collSnippets = null;
        foreach ($snippets as $snippet) {
            $this->addSnippet($snippet);
        }

        $this->collSnippets = $snippets;
        $this->collSnippetsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Snippet objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Snippet objects.
     * @throws PropelException
     */
    public function countSnippets(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSnippetsPartial && !$this->isNew();
        if (null === $this->collSnippets || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSnippets) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSnippets());
            }

            $query = ChildSnippetQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByintroducedAt($this)
                ->count($con);
        }

        return count($this->collSnippets);
    }

    /**
     * Method called to associate a ChildSnippet object to this object
     * through the ChildSnippet foreign key attribute.
     *
     * @param  ChildSnippet $l ChildSnippet
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function addSnippet(ChildSnippet $l)
    {
        if ($this->collSnippets === null) {
            $this->initSnippets();
            $this->collSnippetsPartial = true;
        }

        if (!$this->collSnippets->contains($l)) {
            $this->doAddSnippet($l);
        }

        return $this;
    }

    /**
     * @param ChildSnippet $snippet The ChildSnippet object to add.
     */
    protected function doAddSnippet(ChildSnippet $snippet)
    {
        $this->collSnippets[]= $snippet;
        $snippet->setintroducedAt($this);
    }

    /**
     * @param  ChildSnippet $snippet The ChildSnippet object to remove.
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function removeSnippet(ChildSnippet $snippet)
    {
        if ($this->getSnippets()->contains($snippet)) {
            $pos = $this->collSnippets->search($snippet);
            $this->collSnippets->remove($pos);
            if (null === $this->snippetsScheduledForDeletion) {
                $this->snippetsScheduledForDeletion = clone $this->collSnippets;
                $this->snippetsScheduledForDeletion->clear();
            }
            $this->snippetsScheduledForDeletion[]= clone $snippet;
            $snippet->setintroducedAt(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Milestone is new, it will return
     * an empty collection; or if this Milestone has previously
     * been saved, it will retrieve related Snippets from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Milestone.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSnippet[] List of ChildSnippet objects
     */
    public function getSnippetsJointopic(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSnippetQuery::create(null, $criteria);
        $query->joinWith('topic', $joinBehavior);

        return $this->getSnippets($query, $con);
    }

    /**
     * Clears out the collSummaries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSummaries()
     */
    public function clearSummaries()
    {
        $this->collSummaries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSummaries collection loaded partially.
     */
    public function resetPartialSummaries($v = true)
    {
        $this->collSummariesPartial = $v;
    }

    /**
     * Initializes the collSummaries collection.
     *
     * By default this just sets the collSummaries collection to an empty array (like clearcollSummaries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSummaries($overrideExisting = true)
    {
        if (null !== $this->collSummaries && !$overrideExisting) {
            return;
        }
        $this->collSummaries = new ObjectCollection();
        $this->collSummaries->setModel('\SpoilerWiki\Summary');
    }

    /**
     * Gets an array of ChildSummary objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMilestone is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSummary[] List of ChildSummary objects
     * @throws PropelException
     */
    public function getSummaries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSummariesPartial && !$this->isNew();
        if (null === $this->collSummaries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSummaries) {
                // return empty collection
                $this->initSummaries();
            } else {
                $collSummaries = ChildSummaryQuery::create(null, $criteria)
                    ->filterByupdatedAt($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSummariesPartial && count($collSummaries)) {
                        $this->initSummaries(false);

                        foreach ($collSummaries as $obj) {
                            if (false == $this->collSummaries->contains($obj)) {
                                $this->collSummaries->append($obj);
                            }
                        }

                        $this->collSummariesPartial = true;
                    }

                    return $collSummaries;
                }

                if ($partial && $this->collSummaries) {
                    foreach ($this->collSummaries as $obj) {
                        if ($obj->isNew()) {
                            $collSummaries[] = $obj;
                        }
                    }
                }

                $this->collSummaries = $collSummaries;
                $this->collSummariesPartial = false;
            }
        }

        return $this->collSummaries;
    }

    /**
     * Sets a collection of ChildSummary objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $summaries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function setSummaries(Collection $summaries, ConnectionInterface $con = null)
    {
        /** @var ChildSummary[] $summariesToDelete */
        $summariesToDelete = $this->getSummaries(new Criteria(), $con)->diff($summaries);

        
        $this->summariesScheduledForDeletion = $summariesToDelete;

        foreach ($summariesToDelete as $summaryRemoved) {
            $summaryRemoved->setupdatedAt(null);
        }

        $this->collSummaries = null;
        foreach ($summaries as $summary) {
            $this->addSummary($summary);
        }

        $this->collSummaries = $summaries;
        $this->collSummariesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Summary objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Summary objects.
     * @throws PropelException
     */
    public function countSummaries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSummariesPartial && !$this->isNew();
        if (null === $this->collSummaries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSummaries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSummaries());
            }

            $query = ChildSummaryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByupdatedAt($this)
                ->count($con);
        }

        return count($this->collSummaries);
    }

    /**
     * Method called to associate a ChildSummary object to this object
     * through the ChildSummary foreign key attribute.
     *
     * @param  ChildSummary $l ChildSummary
     * @return $this|\SpoilerWiki\Milestone The current object (for fluent API support)
     */
    public function addSummary(ChildSummary $l)
    {
        if ($this->collSummaries === null) {
            $this->initSummaries();
            $this->collSummariesPartial = true;
        }

        if (!$this->collSummaries->contains($l)) {
            $this->doAddSummary($l);
        }

        return $this;
    }

    /**
     * @param ChildSummary $summary The ChildSummary object to add.
     */
    protected function doAddSummary(ChildSummary $summary)
    {
        $this->collSummaries[]= $summary;
        $summary->setupdatedAt($this);
    }

    /**
     * @param  ChildSummary $summary The ChildSummary object to remove.
     * @return $this|ChildMilestone The current object (for fluent API support)
     */
    public function removeSummary(ChildSummary $summary)
    {
        if ($this->getSummaries()->contains($summary)) {
            $pos = $this->collSummaries->search($summary);
            $this->collSummaries->remove($pos);
            if (null === $this->summariesScheduledForDeletion) {
                $this->summariesScheduledForDeletion = clone $this->collSummaries;
                $this->summariesScheduledForDeletion->clear();
            }
            $this->summariesScheduledForDeletion[]= clone $summary;
            $summary->setupdatedAt(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Milestone is new, it will return
     * an empty collection; or if this Milestone has previously
     * been saved, it will retrieve related Summaries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Milestone.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSummary[] List of ChildSummary objects
     */
    public function getSummariesJointopic(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSummaryQuery::create(null, $criteria);
        $query->joinWith('topic', $joinBehavior);

        return $this->getSummaries($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->awork) {
            $this->awork->removeMilestone($this);
        }
        $this->id = null;
        $this->label = null;
        $this->work_id = null;
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
            if ($this->collTopics) {
                foreach ($this->collTopics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSnippets) {
                foreach ($this->collSnippets as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSummaries) {
                foreach ($this->collSummaries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTopics = null;
        $this->collSnippets = null;
        $this->collSummaries = null;
        $this->awork = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MilestoneTableMap::DEFAULT_STRING_FORMAT);
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
     * @return    $this|ChildMilestone
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
        return $this->getSortableRank() == ChildMilestoneQuery::create()->getMaxRankArray($con);
    }
    
    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildMilestone
     */
    public function getNext(ConnectionInterface $con = null)
    {
    
        $query = ChildMilestoneQuery::create();
    
        $query->filterByRank($this->getSortableRank() + 1);
    
    
        return $query->findOne($con);
    }
    
    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     ConnectionInterface  $con      optional connection
     *
     * @return    ChildMilestone
     */
    public function getPrevious(ConnectionInterface $con = null)
    {
    
        $query = ChildMilestoneQuery::create();
    
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
     * @return    $this|ChildMilestone the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, ConnectionInterface $con = null)
    {
        $maxRank = ChildMilestoneQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array('\SpoilerWiki\MilestoneQuery', 'sortableShiftRank'),
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
     * @return    $this|ChildMilestone the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(ConnectionInterface $con = null)
    {
        $this->setSortableRank(ChildMilestoneQuery::create()->getMaxRankArray($con) + 1);
    
        return $this;
    }
    
    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildMilestone the current object
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
     * @return    $this|ChildMilestone the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, ConnectionInterface $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ChildMilestoneQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }
    
        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }
    
        $con->transaction(function () use ($con, $oldRank, $newRank) {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            ChildMilestoneQuery::sortableShiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);
    
            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);
        });
    
        return $this;
    }
    
    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     ChildMilestone $object
     * @param     ConnectionInterface $con optional connection
     *
     * @return    $this|ChildMilestone the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
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
     * @return    $this|ChildMilestone the current object
     */
    public function moveUp(ConnectionInterface $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
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
     * @return    $this|ChildMilestone the current object
     */
    public function moveDown(ConnectionInterface $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
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
     * @return    $this|ChildMilestone the current object
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
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }
    
        return $con->transaction(function () use ($con) {
            $bottom = ChildMilestoneQuery::create()->getMaxRankArray($con);
    
            return $this->moveToRank($bottom, $con);
        });
    }
    
    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @return    $this|ChildMilestone the current object
     */
    public function removeFromList()
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries[] = array(
            'callable'  => array('\SpoilerWiki\MilestoneQuery', 'sortableShiftRank'),
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
