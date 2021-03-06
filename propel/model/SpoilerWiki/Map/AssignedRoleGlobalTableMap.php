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
use SpoilerWiki\AssignedRoleGlobal;
use SpoilerWiki\AssignedRoleGlobalQuery;


/**
 * This class defines the structure of the 'assigned_role_global' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AssignedRoleGlobalTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SpoilerWiki.Map.AssignedRoleGlobalTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'spoilerwiki-remote';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'assigned_role_global';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SpoilerWiki\\AssignedRoleGlobal';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SpoilerWiki.AssignedRoleGlobal';

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
    const COL_ID = 'assigned_role_global.id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'assigned_role_global.user_id';

    /**
     * the column name for the role_id field
     */
    const COL_ROLE_ID = 'assigned_role_global.role_id';

    /**
     * the column name for the assigned_by field
     */
    const COL_ASSIGNED_BY = 'assigned_role_global.assigned_by';

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
        self::TYPE_PHPNAME       => array('Id', 'UserId', 'RoleId', 'AssignedBy', ),
        self::TYPE_CAMELNAME     => array('id', 'userId', 'roleId', 'assignedBy', ),
        self::TYPE_COLNAME       => array(AssignedRoleGlobalTableMap::COL_ID, AssignedRoleGlobalTableMap::COL_USER_ID, AssignedRoleGlobalTableMap::COL_ROLE_ID, AssignedRoleGlobalTableMap::COL_ASSIGNED_BY, ),
        self::TYPE_FIELDNAME     => array('id', 'user_id', 'role_id', 'assigned_by', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserId' => 1, 'RoleId' => 2, 'AssignedBy' => 3, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userId' => 1, 'roleId' => 2, 'assignedBy' => 3, ),
        self::TYPE_COLNAME       => array(AssignedRoleGlobalTableMap::COL_ID => 0, AssignedRoleGlobalTableMap::COL_USER_ID => 1, AssignedRoleGlobalTableMap::COL_ROLE_ID => 2, AssignedRoleGlobalTableMap::COL_ASSIGNED_BY => 3, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_id' => 1, 'role_id' => 2, 'assigned_by' => 3, ),
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
        $this->setName('assigned_role_global');
        $this->setPhpName('AssignedRoleGlobal');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\SpoilerWiki\\AssignedRoleGlobal');
        $this->setPackage('SpoilerWiki');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'role', 'id', true, null, null);
        $this->addColumn('role_id', 'RoleId', 'INTEGER', true, null, null);
        $this->addForeignKey('assigned_by', 'AssignedBy', 'INTEGER', 'user', 'id', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('user', '\\SpoilerWiki\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('assignedBy', '\\SpoilerWiki\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':assigned_by',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('role', '\\SpoilerWiki\\Role', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? AssignedRoleGlobalTableMap::CLASS_DEFAULT : AssignedRoleGlobalTableMap::OM_CLASS;
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
     * @return array           (AssignedRoleGlobal object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AssignedRoleGlobalTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AssignedRoleGlobalTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AssignedRoleGlobalTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AssignedRoleGlobalTableMap::OM_CLASS;
            /** @var AssignedRoleGlobal $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AssignedRoleGlobalTableMap::addInstanceToPool($obj, $key);
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
            $key = AssignedRoleGlobalTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AssignedRoleGlobalTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AssignedRoleGlobal $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AssignedRoleGlobalTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AssignedRoleGlobalTableMap::COL_ID);
            $criteria->addSelectColumn(AssignedRoleGlobalTableMap::COL_USER_ID);
            $criteria->addSelectColumn(AssignedRoleGlobalTableMap::COL_ROLE_ID);
            $criteria->addSelectColumn(AssignedRoleGlobalTableMap::COL_ASSIGNED_BY);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.role_id');
            $criteria->addSelectColumn($alias . '.assigned_by');
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
        return Propel::getServiceContainer()->getDatabaseMap(AssignedRoleGlobalTableMap::DATABASE_NAME)->getTable(AssignedRoleGlobalTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AssignedRoleGlobalTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AssignedRoleGlobalTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AssignedRoleGlobalTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AssignedRoleGlobal or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AssignedRoleGlobal object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AssignedRoleGlobalTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SpoilerWiki\AssignedRoleGlobal) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AssignedRoleGlobalTableMap::DATABASE_NAME);
            $criteria->add(AssignedRoleGlobalTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AssignedRoleGlobalQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AssignedRoleGlobalTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AssignedRoleGlobalTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the assigned_role_global table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AssignedRoleGlobalQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AssignedRoleGlobal or Criteria object.
     *
     * @param mixed               $criteria Criteria or AssignedRoleGlobal object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AssignedRoleGlobalTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AssignedRoleGlobal object
        }

        if ($criteria->containsKey(AssignedRoleGlobalTableMap::COL_ID) && $criteria->keyContainsValue(AssignedRoleGlobalTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AssignedRoleGlobalTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AssignedRoleGlobalQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AssignedRoleGlobalTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AssignedRoleGlobalTableMap::buildTableMap();
