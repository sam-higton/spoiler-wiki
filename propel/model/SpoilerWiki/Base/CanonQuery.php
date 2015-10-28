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
use SpoilerWiki\Canon as ChildCanon;
use SpoilerWiki\CanonQuery as ChildCanonQuery;
use SpoilerWiki\Map\CanonTableMap;

/**
 * Base class that represents a query for the 'canon' table.
 *
 * 
 *
 * @method     ChildCanonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCanonQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCanonQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildCanonQuery orderByPrimaryArtistId($order = Criteria::ASC) Order by the primary_artist_id column
 *
 * @method     ChildCanonQuery groupById() Group by the id column
 * @method     ChildCanonQuery groupByName() Group by the name column
 * @method     ChildCanonQuery groupByDescription() Group by the description column
 * @method     ChildCanonQuery groupByPrimaryArtistId() Group by the primary_artist_id column
 *
 * @method     ChildCanonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCanonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCanonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCanonQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCanonQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCanonQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCanonQuery leftJoinprimaryArtist($relationAlias = null) Adds a LEFT JOIN clause to the query using the primaryArtist relation
 * @method     ChildCanonQuery rightJoinprimaryArtist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the primaryArtist relation
 * @method     ChildCanonQuery innerJoinprimaryArtist($relationAlias = null) Adds a INNER JOIN clause to the query using the primaryArtist relation
 *
 * @method     ChildCanonQuery joinWithprimaryArtist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the primaryArtist relation
 *
 * @method     ChildCanonQuery leftJoinWithprimaryArtist() Adds a LEFT JOIN clause and with to the query using the primaryArtist relation
 * @method     ChildCanonQuery rightJoinWithprimaryArtist() Adds a RIGHT JOIN clause and with to the query using the primaryArtist relation
 * @method     ChildCanonQuery innerJoinWithprimaryArtist() Adds a INNER JOIN clause and with to the query using the primaryArtist relation
 *
 * @method     ChildCanonQuery leftJoinWork($relationAlias = null) Adds a LEFT JOIN clause to the query using the Work relation
 * @method     ChildCanonQuery rightJoinWork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Work relation
 * @method     ChildCanonQuery innerJoinWork($relationAlias = null) Adds a INNER JOIN clause to the query using the Work relation
 *
 * @method     ChildCanonQuery joinWithWork($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Work relation
 *
 * @method     ChildCanonQuery leftJoinWithWork() Adds a LEFT JOIN clause and with to the query using the Work relation
 * @method     ChildCanonQuery rightJoinWithWork() Adds a RIGHT JOIN clause and with to the query using the Work relation
 * @method     ChildCanonQuery innerJoinWithWork() Adds a INNER JOIN clause and with to the query using the Work relation
 *
 * @method     ChildCanonQuery leftJoinTopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the Topic relation
 * @method     ChildCanonQuery rightJoinTopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Topic relation
 * @method     ChildCanonQuery innerJoinTopic($relationAlias = null) Adds a INNER JOIN clause to the query using the Topic relation
 *
 * @method     ChildCanonQuery joinWithTopic($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Topic relation
 *
 * @method     ChildCanonQuery leftJoinWithTopic() Adds a LEFT JOIN clause and with to the query using the Topic relation
 * @method     ChildCanonQuery rightJoinWithTopic() Adds a RIGHT JOIN clause and with to the query using the Topic relation
 * @method     ChildCanonQuery innerJoinWithTopic() Adds a INNER JOIN clause and with to the query using the Topic relation
 *
 * @method     \SpoilerWiki\ArtistQuery|\SpoilerWiki\WorkQuery|\SpoilerWiki\TopicQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCanon findOne(ConnectionInterface $con = null) Return the first ChildCanon matching the query
 * @method     ChildCanon findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCanon matching the query, or a new ChildCanon object populated from the query conditions when no match is found
 *
 * @method     ChildCanon findOneById(int $id) Return the first ChildCanon filtered by the id column
 * @method     ChildCanon findOneByName(string $name) Return the first ChildCanon filtered by the name column
 * @method     ChildCanon findOneByDescription(string $description) Return the first ChildCanon filtered by the description column
 * @method     ChildCanon findOneByPrimaryArtistId(int $primary_artist_id) Return the first ChildCanon filtered by the primary_artist_id column *

 * @method     ChildCanon requirePk($key, ConnectionInterface $con = null) Return the ChildCanon by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanon requireOne(ConnectionInterface $con = null) Return the first ChildCanon matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCanon requireOneById(int $id) Return the first ChildCanon filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanon requireOneByName(string $name) Return the first ChildCanon filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanon requireOneByDescription(string $description) Return the first ChildCanon filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanon requireOneByPrimaryArtistId(int $primary_artist_id) Return the first ChildCanon filtered by the primary_artist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCanon[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCanon objects based on current ModelCriteria
 * @method     ChildCanon[]|ObjectCollection findById(int $id) Return ChildCanon objects filtered by the id column
 * @method     ChildCanon[]|ObjectCollection findByName(string $name) Return ChildCanon objects filtered by the name column
 * @method     ChildCanon[]|ObjectCollection findByDescription(string $description) Return ChildCanon objects filtered by the description column
 * @method     ChildCanon[]|ObjectCollection findByPrimaryArtistId(int $primary_artist_id) Return ChildCanon objects filtered by the primary_artist_id column
 * @method     ChildCanon[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CanonQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\CanonQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-local', $modelName = '\\SpoilerWiki\\Canon', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCanonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCanonQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCanonQuery) {
            return $criteria;
        }
        $query = new ChildCanonQuery();
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
     * @return ChildCanon|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CanonTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CanonTableMap::DATABASE_NAME);
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
     * @return ChildCanon A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `description`, `primary_artist_id` FROM `canon` WHERE `id` = :p0';
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
            /** @var ChildCanon $obj */
            $obj = new ChildCanon();
            $obj->hydrate($row);
            CanonTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildCanon|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CanonTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CanonTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CanonTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CanonTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CanonTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CanonTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
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

        return $this->addUsingAlias(CanonTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function filterByPrimaryArtistId($primaryArtistId = null, $comparison = null)
    {
        if (is_array($primaryArtistId)) {
            $useMinMax = false;
            if (isset($primaryArtistId['min'])) {
                $this->addUsingAlias(CanonTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($primaryArtistId['max'])) {
                $this->addUsingAlias(CanonTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CanonTableMap::COL_PRIMARY_ARTIST_ID, $primaryArtistId, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Artist object
     *
     * @param \SpoilerWiki\Artist|ObjectCollection $artist The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCanonQuery The current query, for fluid interface
     */
    public function filterByprimaryArtist($artist, $comparison = null)
    {
        if ($artist instanceof \SpoilerWiki\Artist) {
            return $this
                ->addUsingAlias(CanonTableMap::COL_PRIMARY_ARTIST_ID, $artist->getId(), $comparison);
        } elseif ($artist instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CanonTableMap::COL_PRIMARY_ARTIST_ID, $artist->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildCanonQuery The current query, for fluid interface
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
     * Filter the query by a related \SpoilerWiki\Work object
     *
     * @param \SpoilerWiki\Work|ObjectCollection $work the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCanonQuery The current query, for fluid interface
     */
    public function filterByWork($work, $comparison = null)
    {
        if ($work instanceof \SpoilerWiki\Work) {
            return $this
                ->addUsingAlias(CanonTableMap::COL_ID, $work->getCanonId(), $comparison);
        } elseif ($work instanceof ObjectCollection) {
            return $this
                ->useWorkQuery()
                ->filterByPrimaryKeys($work->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWork() only accepts arguments of type \SpoilerWiki\Work or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Work relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function joinWork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Work');

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
            $this->addJoinObject($join, 'Work');
        }

        return $this;
    }

    /**
     * Use the Work relation Work object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\WorkQuery A secondary query class using the current class as primary query
     */
    public function useWorkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Work', '\SpoilerWiki\WorkQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Topic object
     *
     * @param \SpoilerWiki\Topic|ObjectCollection $topic the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCanonQuery The current query, for fluid interface
     */
    public function filterByTopic($topic, $comparison = null)
    {
        if ($topic instanceof \SpoilerWiki\Topic) {
            return $this
                ->addUsingAlias(CanonTableMap::COL_ID, $topic->getCanonId(), $comparison);
        } elseif ($topic instanceof ObjectCollection) {
            return $this
                ->useTopicQuery()
                ->filterByPrimaryKeys($topic->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopic() only accepts arguments of type \SpoilerWiki\Topic or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Topic relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function joinTopic($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Topic');

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
            $this->addJoinObject($join, 'Topic');
        }

        return $this;
    }

    /**
     * Use the Topic relation Topic object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\TopicQuery A secondary query class using the current class as primary query
     */
    public function useTopicQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTopic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Topic', '\SpoilerWiki\TopicQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCanon $canon Object to remove from the list of results
     *
     * @return $this|ChildCanonQuery The current query, for fluid interface
     */
    public function prune($canon = null)
    {
        if ($canon) {
            $this->addUsingAlias(CanonTableMap::COL_ID, $canon->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the canon table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanonTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CanonTableMap::clearInstancePool();
            CanonTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CanonTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CanonTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CanonTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CanonTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CanonQuery
