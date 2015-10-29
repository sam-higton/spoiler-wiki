<?php

namespace SpoilerWiki\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SpoilerWiki\Milestone;
use SpoilerWiki\MilestoneQuery;


/**
 * This class defines the structure of the 'milestone' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MilestoneTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SpoilerWiki.Map.MilestoneTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'spoilerwiki-remote';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'milestone';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SpoilerWiki\\Milestone';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SpoilerWiki.Milestone';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the id field
     */
    const COL_ID = 'milestone.id';

    /**
     * the column name for the label field
     */
    const COL_LABEL = 'milestone.label';

    /**
     * the column name for the work_id field
     */
    const COL_WORK_ID = 'milestone.work_id';

    /**
     * the column name for the sortable_rank field
     */
    const COL_SORTABLE_RANK = 'milestone.sortable_rank';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // sortable behavior
    /**
     * rank column
     */
    const RANK_COL = "milestone.sortable_rank";
    

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Label', 'WorkId', 'SortableRank', ),
        self::TYPE_CAMELNAME     => array('id', 'label', 'workId', 'sortableRank', ),
        self::TYPE_COLNAME       => array(MilestoneTableMap::COL_ID, MilestoneTableMap::COL_LABEL, MilestoneTableMap::COL_WORK_ID, MilestoneTableMap::COL_SORTABLE_RANK, ),
        self::TYPE_FIELDNAME     => array('id', 'label', 'work_id', 'sortable_rank', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Label' => 1, 'WorkId' => 2, 'SortableRank' => 3, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'label' => 1, 'workId' => 2, 'sortableRank' => 3, ),
        self::TYPE_COLNAME       => array(MilestoneTableMap::COL_ID => 0, MilestoneTableMap::COL_LABEL => 1, MilestoneTableMap::COL_WORK_ID => 2, MilestoneTableMap::COL_SORTABLE_RANK => 3, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'label' => 1, 'work_id' => 2, 'sortable_rank' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('milestone');
        $this->setPhpName('Milestone');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\SpoilerWiki\\Milestone');
        $this->setPackage('SpoilerWiki');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('label', 'Label', 'VARCHAR', false, 255, null);
        $this->addForeignKey('work_id', 'WorkId', 'INTEGER', 'work', 'id', true, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('work', '\\SpoilerWiki\\Work', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':work_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Topic', '\\SpoilerWiki\\Topic', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':introduced_at',
    1 => ':id',
  ),
), null, null, 'Topics', false);
        $this->addRelation('Snippet', '\\SpoilerWiki\\Snippet', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':introduced_at',
    1 => ':id',
  ),
), null, null, 'Snippets', false);
        $this->addRelation('Summary', '\\SpoilerWiki\\Summary', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':introduced_at',
    1 => ':id',
  ),
), null, null, 'Summaries', false);
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
            'sortable' => array('rank_column' => 'sortable_rank', 'use_scope' => 'false', 'scope_column' => '', ),
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

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return $withPrefix ? MilestoneTableMap::CLASS_DEFAULT : MilestoneTableMap::OM_CLASS;
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
     * @return array           (Milestone object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MilestoneTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MilestoneTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MilestoneTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MilestoneTableMap::OM_CLASS;
            /** @var Milestone $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MilestoneTableMap::addInstanceToPool($obj, $key);
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
            $key = MilestoneTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MilestoneTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Milestone $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MilestoneTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MilestoneTableMap::COL_ID);
            $criteria->addSelectColumn(MilestoneTableMap::COL_LABEL);
            $criteria->addSelectColumn(MilestoneTableMap::COL_WORK_ID);
            $criteria->addSelectColumn(MilestoneTableMap::COL_SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.label');
            $criteria->addSelectColumn($alias . '.work_id');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        return Propel::getServiceContainer()->getDatabaseMap(MilestoneTableMap::DATABASE_NAME)->getTable(MilestoneTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MilestoneTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MilestoneTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MilestoneTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Milestone or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Milestone object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SpoilerWiki\Milestone) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MilestoneTableMap::DATABASE_NAME);
            $criteria->add(MilestoneTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MilestoneQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MilestoneTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MilestoneTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the milestone table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MilestoneQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Milestone or Criteria object.
     *
     * @param mixed               $criteria Criteria or Milestone object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Milestone object
        }

        if ($criteria->containsKey(MilestoneTableMap::COL_ID) && $criteria->keyContainsValue(MilestoneTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MilestoneTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MilestoneQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MilestoneTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MilestoneTableMap::buildTableMap();
