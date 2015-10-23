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
use SpoilerWiki\Work;
use SpoilerWiki\WorkQuery;


/**
 * This class defines the structure of the 'work' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class WorkTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SpoilerWiki.Map.WorkTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'spoilerwiki';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'work';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SpoilerWiki\\Work';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SpoilerWiki.Work';

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
    const COL_ID = 'work.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'work.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'work.description';

    /**
     * the column name for the order field
     */
    const COL_ORDER = 'work.order';

    /**
     * the column name for the primary_artist_id field
     */
    const COL_PRIMARY_ARTIST_ID = 'work.primary_artist_id';

    /**
     * the column name for the canon_id field
     */
    const COL_CANON_ID = 'work.canon_id';

    /**
     * the column name for the work_type_id field
     */
    const COL_WORK_TYPE_ID = 'work.work_type_id';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'Order', 'PrimaryArtistId', 'CanonId', 'WorkTypeId', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'order', 'primaryArtistId', 'canonId', 'workTypeId', ),
        self::TYPE_COLNAME       => array(WorkTableMap::COL_ID, WorkTableMap::COL_NAME, WorkTableMap::COL_DESCRIPTION, WorkTableMap::COL_ORDER, WorkTableMap::COL_PRIMARY_ARTIST_ID, WorkTableMap::COL_CANON_ID, WorkTableMap::COL_WORK_TYPE_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'order', 'primary_artist_id', 'canon_id', 'work_type_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'Order' => 3, 'PrimaryArtistId' => 4, 'CanonId' => 5, 'WorkTypeId' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'order' => 3, 'primaryArtistId' => 4, 'canonId' => 5, 'workTypeId' => 6, ),
        self::TYPE_COLNAME       => array(WorkTableMap::COL_ID => 0, WorkTableMap::COL_NAME => 1, WorkTableMap::COL_DESCRIPTION => 2, WorkTableMap::COL_ORDER => 3, WorkTableMap::COL_PRIMARY_ARTIST_ID => 4, WorkTableMap::COL_CANON_ID => 5, WorkTableMap::COL_WORK_TYPE_ID => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'order' => 3, 'primary_artist_id' => 4, 'canon_id' => 5, 'work_type_id' => 6, ),
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
        $this->setName('work');
        $this->setPhpName('Work');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\SpoilerWiki\\Work');
        $this->setPackage('SpoilerWiki');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order', 'Order', 'INTEGER', true, null, 0);
        $this->addForeignKey('primary_artist_id', 'PrimaryArtistId', 'INTEGER', 'artist', 'id', true, null, null);
        $this->addForeignKey('canon_id', 'CanonId', 'INTEGER', 'canon', 'id', true, null, null);
        $this->addForeignKey('work_type_id', 'WorkTypeId', 'INTEGER', 'work_type', 'id', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('primaryArtist', '\\SpoilerWiki\\Artist', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':primary_artist_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('canon', '\\SpoilerWiki\\Canon', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':canon_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('workType', '\\SpoilerWiki\\WorkType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':work_type_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Milestone', '\\SpoilerWiki\\Milestone', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':work_id',
    1 => ':id',
  ),
), null, null, 'Milestones', false);
    } // buildRelations()

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
        return $withPrefix ? WorkTableMap::CLASS_DEFAULT : WorkTableMap::OM_CLASS;
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
     * @return array           (Work object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = WorkTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = WorkTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + WorkTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WorkTableMap::OM_CLASS;
            /** @var Work $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            WorkTableMap::addInstanceToPool($obj, $key);
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
            $key = WorkTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = WorkTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Work $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WorkTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(WorkTableMap::COL_ID);
            $criteria->addSelectColumn(WorkTableMap::COL_NAME);
            $criteria->addSelectColumn(WorkTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(WorkTableMap::COL_ORDER);
            $criteria->addSelectColumn(WorkTableMap::COL_PRIMARY_ARTIST_ID);
            $criteria->addSelectColumn(WorkTableMap::COL_CANON_ID);
            $criteria->addSelectColumn(WorkTableMap::COL_WORK_TYPE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.order');
            $criteria->addSelectColumn($alias . '.primary_artist_id');
            $criteria->addSelectColumn($alias . '.canon_id');
            $criteria->addSelectColumn($alias . '.work_type_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(WorkTableMap::DATABASE_NAME)->getTable(WorkTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(WorkTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(WorkTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new WorkTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Work or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Work object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SpoilerWiki\Work) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WorkTableMap::DATABASE_NAME);
            $criteria->add(WorkTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = WorkQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            WorkTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                WorkTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the work table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return WorkQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Work or Criteria object.
     *
     * @param mixed               $criteria Criteria or Work object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Work object
        }

        if ($criteria->containsKey(WorkTableMap::COL_ID) && $criteria->keyContainsValue(WorkTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WorkTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = WorkQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // WorkTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
WorkTableMap::buildTableMap();
