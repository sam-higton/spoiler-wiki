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
use SpoilerWiki\ContentArea;
use SpoilerWiki\ContentAreaQuery;


/**
 * This class defines the structure of the 'content_area' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ContentAreaTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SpoilerWiki.Map.ContentAreaTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'spoilerwiki-remote';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'content_area';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SpoilerWiki\\ContentArea';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SpoilerWiki.ContentArea';

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
     * the column name for the content field
     */
    const COL_CONTENT = 'content_area.content';

    /**
     * the column name for the active_version field
     */
    const COL_ACTIVE_VERSION = 'content_area.active_version';

    /**
     * the column name for the id field
     */
    const COL_ID = 'content_area.id';

    /**
     * the column name for the version field
     */
    const COL_VERSION = 'content_area.version';

    /**
     * the column name for the version_created_at field
     */
    const COL_VERSION_CREATED_AT = 'content_area.version_created_at';

    /**
     * the column name for the version_created_by field
     */
    const COL_VERSION_CREATED_BY = 'content_area.version_created_by';

    /**
     * the column name for the version_comment field
     */
    const COL_VERSION_COMMENT = 'content_area.version_comment';

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
        self::TYPE_PHPNAME       => array('Content', 'activeVersion', 'Id', 'Version', 'VersionCreatedAt', 'VersionCreatedBy', 'VersionComment', ),
        self::TYPE_CAMELNAME     => array('content', 'activeVersion', 'id', 'version', 'versionCreatedAt', 'versionCreatedBy', 'versionComment', ),
        self::TYPE_COLNAME       => array(ContentAreaTableMap::COL_CONTENT, ContentAreaTableMap::COL_ACTIVE_VERSION, ContentAreaTableMap::COL_ID, ContentAreaTableMap::COL_VERSION, ContentAreaTableMap::COL_VERSION_CREATED_AT, ContentAreaTableMap::COL_VERSION_CREATED_BY, ContentAreaTableMap::COL_VERSION_COMMENT, ),
        self::TYPE_FIELDNAME     => array('content', 'active_version', 'id', 'version', 'version_created_at', 'version_created_by', 'version_comment', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Content' => 0, 'activeVersion' => 1, 'Id' => 2, 'Version' => 3, 'VersionCreatedAt' => 4, 'VersionCreatedBy' => 5, 'VersionComment' => 6, ),
        self::TYPE_CAMELNAME     => array('content' => 0, 'activeVersion' => 1, 'id' => 2, 'version' => 3, 'versionCreatedAt' => 4, 'versionCreatedBy' => 5, 'versionComment' => 6, ),
        self::TYPE_COLNAME       => array(ContentAreaTableMap::COL_CONTENT => 0, ContentAreaTableMap::COL_ACTIVE_VERSION => 1, ContentAreaTableMap::COL_ID => 2, ContentAreaTableMap::COL_VERSION => 3, ContentAreaTableMap::COL_VERSION_CREATED_AT => 4, ContentAreaTableMap::COL_VERSION_CREATED_BY => 5, ContentAreaTableMap::COL_VERSION_COMMENT => 6, ),
        self::TYPE_FIELDNAME     => array('content' => 0, 'active_version' => 1, 'id' => 2, 'version' => 3, 'version_created_at' => 4, 'version_created_by' => 5, 'version_comment' => 6, ),
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
        $this->setName('content_area');
        $this->setPhpName('ContentArea');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\SpoilerWiki\\ContentArea');
        $this->setPackage('SpoilerWiki');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('content', 'Content', 'LONGVARCHAR', true, null, null);
        $this->addColumn('active_version', 'activeVersion', 'INTEGER', false, null, 1);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'snippet', 'id', true, null, null);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'summary', 'id', true, null, null);
        $this->addColumn('version', 'Version', 'INTEGER', false, null, 0);
        $this->addColumn('version_created_at', 'VersionCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('version_created_by', 'VersionCreatedBy', 'VARCHAR', false, 100, null);
        $this->addColumn('version_comment', 'VersionComment', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Snippet', '\\SpoilerWiki\\Snippet', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Summary', '\\SpoilerWiki\\Summary', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('ContentAreaVersion', '\\SpoilerWiki\\ContentAreaVersion', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, 'ContentAreaVersions', false);
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
            'versionable' => array('version_column' => 'version', 'version_table' => '', 'log_created_at' => 'true', 'log_created_by' => 'true', 'log_comment' => 'true', 'version_created_at_column' => 'version_created_at', 'version_created_by_column' => 'version_created_by', 'version_comment_column' => 'version_comment', 'indices' => 'false', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to content_area     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ContentAreaVersionTableMap::clearInstancePool();
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 2 + $offset
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
        return $withPrefix ? ContentAreaTableMap::CLASS_DEFAULT : ContentAreaTableMap::OM_CLASS;
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
     * @return array           (ContentArea object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ContentAreaTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ContentAreaTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ContentAreaTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ContentAreaTableMap::OM_CLASS;
            /** @var ContentArea $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ContentAreaTableMap::addInstanceToPool($obj, $key);
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
            $key = ContentAreaTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ContentAreaTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ContentArea $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ContentAreaTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ContentAreaTableMap::COL_CONTENT);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_ACTIVE_VERSION);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_ID);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_VERSION);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_VERSION_CREATED_AT);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_VERSION_CREATED_BY);
            $criteria->addSelectColumn(ContentAreaTableMap::COL_VERSION_COMMENT);
        } else {
            $criteria->addSelectColumn($alias . '.content');
            $criteria->addSelectColumn($alias . '.active_version');
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.version');
            $criteria->addSelectColumn($alias . '.version_created_at');
            $criteria->addSelectColumn($alias . '.version_created_by');
            $criteria->addSelectColumn($alias . '.version_comment');
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
        return Propel::getServiceContainer()->getDatabaseMap(ContentAreaTableMap::DATABASE_NAME)->getTable(ContentAreaTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ContentAreaTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ContentAreaTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ContentAreaTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ContentArea or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ContentArea object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SpoilerWiki\ContentArea) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ContentAreaTableMap::DATABASE_NAME);
            $criteria->add(ContentAreaTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ContentAreaQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ContentAreaTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ContentAreaTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the content_area table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ContentAreaQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ContentArea or Criteria object.
     *
     * @param mixed               $criteria Criteria or ContentArea object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ContentArea object
        }


        // Set the correct dbName
        $query = ContentAreaQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ContentAreaTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ContentAreaTableMap::buildTableMap();
