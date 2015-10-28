<?php

namespace SpoilerWiki\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SpoilerWiki\Work as ChildWork;
use SpoilerWiki\WorkQuery as ChildWorkQuery;
use SpoilerWiki\Map\WorkTableMap;

/**
 * Base class that represents a query for the 'work' table.
 *
 * 
 *
 * @method     ChildWorkQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildWorkQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildWorkQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildWorkQuery orderByOrder($order = Criteria::ASC) Order by the order column
 * @method     ChildWorkQuery orderByPrimaryArtistId($order = Criteria::ASC) Order by the primary_artist_id column
 * @method     ChildWorkQuery orderByCanonId($order = Criteria::ASC) Order by the canon_id column
 * @method     ChildWorkQuery orderByWorkTypeId($order = Criteria::ASC) Order by the work_type_id column
 *
 * @method     ChildWorkQuery groupById() Group by the id column
 * @method     ChildWorkQuery groupByName() Group by the name column
 * @method     ChildWorkQuery groupByDescription() Group by the description column
 * @method     ChildWorkQuery groupByOrder() Group by the order column
 * @method     ChildWorkQuery groupByPrimaryArtistId() Group by the primary_artist_id column
 * @method     ChildWorkQuery groupByCanonId() Group by the canon_id column
 * @method     ChildWorkQuery groupByWorkTypeId() Group by the work_type_id column
 *
 * @method     ChildWorkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWorkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWorkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWorkQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWorkQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWorkQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWorkQuery leftJoinprimaryArtist($relationAlias = null) Adds a LEFT JOIN clause to the query using the primaryArtist relation
 * @method     ChildWorkQuery rightJoinprimaryArtist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the primaryArtist relation
 * @method     ChildWorkQuery innerJoinprimaryArtist($relationAlias = null) Adds a INNER JOIN clause to the query using the primaryArtist relation
 *
 * @method     ChildWorkQuery joinWithprimaryArtist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the primaryArtist relation
 *
 * @method     ChildWorkQuery leftJoinWithprimaryArtist() Adds a LEFT JOIN clause and with to the query using the primaryArtist relation
 * @method     ChildWorkQuery rightJoinWithprimaryArtist() Adds a RIGHT JOIN clause and with to the query using the primaryArtist relation
 * @method     ChildWorkQuery innerJoinWithprimaryArtist() Adds a INNER JOIN clause and with to the query using the primaryArtist relation
 *
 * @method     ChildWorkQuery leftJoincanon($relationAlias = null) Adds a LEFT JOIN clause to the query using the canon relation
 * @method     ChildWorkQuery rightJoincanon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the canon relation
 * @method     ChildWorkQuery innerJoincanon($relationAlias = null) Adds a INNER JOIN clause to the query using the canon relation
 *
 * @method     ChildWorkQuery joinWithcanon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the canon relation
 *
 * @method     ChildWorkQuery leftJoinWithcanon() Adds a LEFT JOIN clause and with to the query using the canon relation
 * @method     ChildWorkQuery rightJoinWithcanon() Adds a RIGHT JOIN clause and with to the query using the canon relation
 * @method     ChildWorkQuery innerJoinWithcanon() Adds a INNER JOIN clause and with to the query using the canon relation
 *
 * @method     ChildWorkQuery leftJoinworkType($relationAlias = null) Adds a LEFT JOIN clause to the query using the workType relation
 * @method     ChildWorkQuery rightJoinworkType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the workType relation
 * @method     ChildWorkQuery innerJoinworkType($relationAlias = null) Adds a INNER JOIN clause to the query using the workType relation
 *
 * @method     ChildWorkQuery joinWithworkType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the workType relation
 *
 * @method     ChildWorkQuery leftJoinWithworkType() Adds a LEFT JOIN clause and with to the query using the workType relation
 * @method     ChildWorkQuery rightJoinWithworkType() Adds a RIGHT JOIN clause and with to the query using the workType relation
 * @method     ChildWorkQuery innerJoinWithworkType() Adds a INNER JOIN clause and with to the query using the workType relation
 *
 * @method     ChildWorkQuery leftJoinMilestone($relationAlias = null) Adds a LEFT JOIN clause to the query using the Milestone relation
 * @method     ChildWorkQuery rightJoinMilestone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Milestone relation
 * @method     ChildWorkQuery innerJoinMilestone($relationAlias = null) Adds a INNER JOIN clause to the query using the Milestone relation
 *
 * @method     ChildWorkQuery joinWithMilestone($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Milestone relation
 *
 * @method     ChildWorkQuery leftJoinWithMilestone() Adds a LEFT JOIN clause and with to the query using the Milestone relation
 * @method     ChildWorkQuery rightJoinWithMilestone() Adds a RIGHT JOIN clause and with to the query using the Milestone relation
 * @method     ChildWorkQuery innerJoinWithMilestone() Adds a INNER JOIN clause and with to the query using the Milestone relation
 *
 * @method     \SpoilerWiki\ArtistQuery|\SpoilerWiki\CanonQuery|\SpoilerWiki\WorkTypeQuery|\SpoilerWiki\MilestoneQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWork findOne(ConnectionInterface $con = null) Return the first ChildWork matching the query
 * @method     ChildWork findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWork matching the query, or a new ChildWork object populated from the query conditions when no match is found
 *
 * @method     ChildWork findOneById(int $id) Return the first ChildWork filtered by the id column
 * @method     ChildWork findOneByName(string $name) Return the first ChildWork filtered by the name column
 * @method     ChildWork findOneByDescription(string $description) Return the first ChildWork filtered by the description column
 * @method     ChildWork findOneByOrder(int $order) Return the first ChildWork filtered by the order column
 * @method     ChildWork findOneByPrimaryArtistId(int $primary_artist_id) Return the first ChildWork filtered by the primary_artist_id column
 * @method     ChildWork findOneByCanonId(int $canon_id) Return the first ChildWork filtered by the canon_id column
 * @method     ChildWork findOneByWorkTypeId(int $work_type_id) Return the first ChildWork filtered by the work_type_id column *

 * @method     ChildWork requirePk($key, ConnectionInterface $con = null) Return the ChildWork by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOne(ConnectionInterface $con = null) Return the first ChildWork matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWork requireOneById(int $id) Return the first ChildWork filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByName(string $name) Return the first ChildWork filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByDescription(string $description) Return the first ChildWork filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByOrder(int $order) Return the first ChildWork filtered by the order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByPrimaryArtistId(int $primary_artist_id) Return the first ChildWork filtered by the primary_artist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByCanonId(int $canon_id) Return the first ChildWork filtered by the canon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWork requireOneByWorkTypeId(int $work_type_id) Return the first ChildWork filtered by the work_type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWork[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWork objects based on current ModelCriteria
 * @method     ChildWork[]|ObjectCollection findById(int $id) Return ChildWork objects filtered by the id column
 * @method     ChildWork[]|ObjectCollection findByName(string $name) Return ChildWork objects filtered by the name column
 * @method     ChildWork[]|ObjectCollection findByDescription(string $description) Return ChildWork objects filtered by the description column
 * @method     ChildWork[]|ObjectCollection findByOrder(int $order) Return ChildWork objects filtered by the order column
 * @method     ChildWork[]|ObjectCollection findByPrimaryArtistId(int $primary_artist_id) Return ChildWork objects filtered by the primary_artist_id column
 * @method     ChildWork[]|ObjectCollection findByCanonId(int $canon_id) Return ChildWork objects filtered by the canon_id column
 * @method     ChildWork[]|ObjectCollection findByWorkTypeId(int $work_type_id) Return ChildWork objects filtered by the work_type_id column
 * @method     ChildWork[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WorkQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\WorkQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-local', $modelName = '\\SpoilerWiki\\Work', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWorkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWorkQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWorkQuery) {
            return $criteria;
        }
        $query = new ChildWorkQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildWork|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WorkTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WorkTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWork A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `order`, `primary_artist_id`, `canon_id`, `work_type_id` FROM `work` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildWork $obj */
            $obj = new ChildWork();
            $obj->hydrate($row);
            WorkTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildWork|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WorkTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WorkTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WorkTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WorkTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE order > 12
     * </code>
     *
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(WorkTableMap::COL_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(WorkTableMap::COL_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_ORDER, $order, $comparison);
    }

    /**
     * Filter the query on the primary_artist_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimaryArtistId(1234); // WHERE primary_artist_id = 1234
     * $query->filterByPrimaryArtistId(array(12, 34)); // WHERE primary_artist_id IN (12, 34)
     * $query->filterByPrimaryArtistId(array('min' => 12)); // WHERE primary_artist_id > 12
     * </code>
     *
     * @see       filterByprimaryArtist()
     *
     * @param     mixed $primaryArtistId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByPrimaryArtistId($primaryArtistId = null, $comparison = null)
    {
        if (is_array($primaryArtistId)) {
            $useMinMax = false;
            if (isset($primaryArtistId['min'])) {
                $this->addUsingAlias(WorkTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primaryArtistId['max'])) {
                $this->addUsingAlias(WorkTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId, $comparison);
    }

    /**
     * Filter the query on the canon_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCanonId(1234); // WHERE canon_id = 1234
     * $query->filterByCanonId(array(12, 34)); // WHERE canon_id IN (12, 34)
     * $query->filterByCanonId(array('min' => 12)); // WHERE canon_id > 12
     * </code>
     *
     * @see       filterBycanon()
     *
     * @param     mixed $canonId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByCanonId($canonId = null, $comparison = null)
    {
        if (is_array($canonId)) {
            $useMinMax = false;
            if (isset($canonId['min'])) {
                $this->addUsingAlias(WorkTableMap::COL_CANON_ID, $canonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canonId['max'])) {
                $this->addUsingAlias(WorkTableMap::COL_CANON_ID, $canonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_CANON_ID, $canonId, $comparison);
    }

    /**
     * Filter the query on the work_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkTypeId(1234); // WHERE work_type_id = 1234
     * $query->filterByWorkTypeId(array(12, 34)); // WHERE work_type_id IN (12, 34)
     * $query->filterByWorkTypeId(array('min' => 12)); // WHERE work_type_id > 12
     * </code>
     *
     * @see       filterByworkType()
     *
     * @param     mixed $workTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function filterByWorkTypeId($workTypeId = null, $comparison = null)
    {
        if (is_array($workTypeId)) {
            $useMinMax = false;
            if (isset($workTypeId['min'])) {
                $this->addUsingAlias(WorkTableMap::COL_WORK_TYPE_ID, $workTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workTypeId['max'])) {
                $this->addUsingAlias(WorkTableMap::COL_WORK_TYPE_ID, $workTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WorkTableMap::COL_WORK_TYPE_ID, $workTypeId, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Artist object
     *
     * @param \SpoilerWiki\Artist|ObjectCollection $artist The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWorkQuery The current query, for fluid interface
     */
    public function filterByprimaryArtist($artist, $comparison = null)
    {
        if ($artist instanceof \SpoilerWiki\Artist) {
            return $this
                ->addUsingAlias(WorkTableMap::COL_PRIMARY_ARTIST_ID, $artist->getId(), $comparison);
        } elseif ($artist instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkTableMap::COL_PRIMARY_ARTIST_ID, $artist->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByprimaryArtist() only accepts arguments of type \SpoilerWiki\Artist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the primaryArtist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function joinprimaryArtist($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('primaryArtist');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'primaryArtist');
        }

        return $this;
    }

    /**
     * Use the primaryArtist relation Artist object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\ArtistQuery A secondary query class using the current class as primary query
     */
    public function useprimaryArtistQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinprimaryArtist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'primaryArtist', '\SpoilerWiki\ArtistQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Canon object
     *
     * @param \SpoilerWiki\Canon|ObjectCollection $canon The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWorkQuery The current query, for fluid interface
     */
    public function filterBycanon($canon, $comparison = null)
    {
        if ($canon instanceof \SpoilerWiki\Canon) {
            return $this
                ->addUsingAlias(WorkTableMap::COL_CANON_ID, $canon->getId(), $comparison);
        } elseif ($canon instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkTableMap::COL_CANON_ID, $canon->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBycanon() only accepts arguments of type \SpoilerWiki\Canon or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the canon relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function joincanon($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('canon');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'canon');
        }

        return $this;
    }

    /**
     * Use the canon relation Canon object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\CanonQuery A secondary query class using the current class as primary query
     */
    public function usecanonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joincanon($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'canon', '\SpoilerWiki\CanonQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\WorkType object
     *
     * @param \SpoilerWiki\WorkType|ObjectCollection $workType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWorkQuery The current query, for fluid interface
     */
    public function filterByworkType($workType, $comparison = null)
    {
        if ($workType instanceof \SpoilerWiki\WorkType) {
            return $this
                ->addUsingAlias(WorkTableMap::COL_WORK_TYPE_ID, $workType->getId(), $comparison);
        } elseif ($workType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WorkTableMap::COL_WORK_TYPE_ID, $workType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByworkType() only accepts arguments of type \SpoilerWiki\WorkType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the workType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function joinworkType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('workType');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'workType');
        }

        return $this;
    }

    /**
     * Use the workType relation WorkType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\WorkTypeQuery A secondary query class using the current class as primary query
     */
    public function useworkTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinworkType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'workType', '\SpoilerWiki\WorkTypeQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Milestone object
     *
     * @param \SpoilerWiki\Milestone|ObjectCollection $milestone the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWorkQuery The current query, for fluid interface
     */
    public function filterByMilestone($milestone, $comparison = null)
    {
        if ($milestone instanceof \SpoilerWiki\Milestone) {
            return $this
                ->addUsingAlias(WorkTableMap::COL_ID, $milestone->getWorkId(), $comparison);
        } elseif ($milestone instanceof ObjectCollection) {
            return $this
                ->useMilestoneQuery()
                ->filterByPrimaryKeys($milestone->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMilestone() only accepts arguments of type \SpoilerWiki\Milestone or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Milestone relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function joinMilestone($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Milestone');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Milestone');
        }

        return $this;
    }

    /**
     * Use the Milestone relation Milestone object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\MilestoneQuery A secondary query class using the current class as primary query
     */
    public function useMilestoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMilestone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Milestone', '\SpoilerWiki\MilestoneQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWork $work Object to remove from the list of results
     *
     * @return $this|ChildWorkQuery The current query, for fluid interface
     */
    public function prune($work = null)
    {
        if ($work) {
            $this->addUsingAlias(WorkTableMap::COL_ID, $work->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the work table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WorkTableMap::clearInstancePool();
            WorkTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WorkTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WorkTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            WorkTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            WorkTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // WorkQuery
