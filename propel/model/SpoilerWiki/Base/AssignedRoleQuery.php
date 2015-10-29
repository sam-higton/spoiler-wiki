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
use SpoilerWiki\AssignedRole as ChildAssignedRole;
use SpoilerWiki\AssignedRoleQuery as ChildAssignedRoleQuery;
use SpoilerWiki\Map\AssignedRoleTableMap;

/**
 * Base class that represents a query for the 'assigned_role' table.
 *
 * 
 *
 * @method     ChildAssignedRoleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAssignedRoleQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildAssignedRoleQuery orderByCanonId($order = Criteria::ASC) Order by the canon_id column
 * @method     ChildAssignedRoleQuery orderByRoleId($order = Criteria::ASC) Order by the role_id column
 * @method     ChildAssignedRoleQuery orderByAssignedBy($order = Criteria::ASC) Order by the assigned_by column
 *
 * @method     ChildAssignedRoleQuery groupById() Group by the id column
 * @method     ChildAssignedRoleQuery groupByUserId() Group by the user_id column
 * @method     ChildAssignedRoleQuery groupByCanonId() Group by the canon_id column
 * @method     ChildAssignedRoleQuery groupByRoleId() Group by the role_id column
 * @method     ChildAssignedRoleQuery groupByAssignedBy() Group by the assigned_by column
 *
 * @method     ChildAssignedRoleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAssignedRoleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAssignedRoleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAssignedRoleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAssignedRoleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAssignedRoleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAssignedRoleQuery leftJoinuser($relationAlias = null) Adds a LEFT JOIN clause to the query using the user relation
 * @method     ChildAssignedRoleQuery rightJoinuser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the user relation
 * @method     ChildAssignedRoleQuery innerJoinuser($relationAlias = null) Adds a INNER JOIN clause to the query using the user relation
 *
 * @method     ChildAssignedRoleQuery joinWithuser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the user relation
 *
 * @method     ChildAssignedRoleQuery leftJoinWithuser() Adds a LEFT JOIN clause and with to the query using the user relation
 * @method     ChildAssignedRoleQuery rightJoinWithuser() Adds a RIGHT JOIN clause and with to the query using the user relation
 * @method     ChildAssignedRoleQuery innerJoinWithuser() Adds a INNER JOIN clause and with to the query using the user relation
 *
 * @method     ChildAssignedRoleQuery leftJoinassignedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the assignedBy relation
 * @method     ChildAssignedRoleQuery rightJoinassignedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the assignedBy relation
 * @method     ChildAssignedRoleQuery innerJoinassignedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the assignedBy relation
 *
 * @method     ChildAssignedRoleQuery joinWithassignedBy($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the assignedBy relation
 *
 * @method     ChildAssignedRoleQuery leftJoinWithassignedBy() Adds a LEFT JOIN clause and with to the query using the assignedBy relation
 * @method     ChildAssignedRoleQuery rightJoinWithassignedBy() Adds a RIGHT JOIN clause and with to the query using the assignedBy relation
 * @method     ChildAssignedRoleQuery innerJoinWithassignedBy() Adds a INNER JOIN clause and with to the query using the assignedBy relation
 *
 * @method     ChildAssignedRoleQuery leftJoincanon($relationAlias = null) Adds a LEFT JOIN clause to the query using the canon relation
 * @method     ChildAssignedRoleQuery rightJoincanon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the canon relation
 * @method     ChildAssignedRoleQuery innerJoincanon($relationAlias = null) Adds a INNER JOIN clause to the query using the canon relation
 *
 * @method     ChildAssignedRoleQuery joinWithcanon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the canon relation
 *
 * @method     ChildAssignedRoleQuery leftJoinWithcanon() Adds a LEFT JOIN clause and with to the query using the canon relation
 * @method     ChildAssignedRoleQuery rightJoinWithcanon() Adds a RIGHT JOIN clause and with to the query using the canon relation
 * @method     ChildAssignedRoleQuery innerJoinWithcanon() Adds a INNER JOIN clause and with to the query using the canon relation
 *
 * @method     ChildAssignedRoleQuery leftJoinrole($relationAlias = null) Adds a LEFT JOIN clause to the query using the role relation
 * @method     ChildAssignedRoleQuery rightJoinrole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the role relation
 * @method     ChildAssignedRoleQuery innerJoinrole($relationAlias = null) Adds a INNER JOIN clause to the query using the role relation
 *
 * @method     ChildAssignedRoleQuery joinWithrole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the role relation
 *
 * @method     ChildAssignedRoleQuery leftJoinWithrole() Adds a LEFT JOIN clause and with to the query using the role relation
 * @method     ChildAssignedRoleQuery rightJoinWithrole() Adds a RIGHT JOIN clause and with to the query using the role relation
 * @method     ChildAssignedRoleQuery innerJoinWithrole() Adds a INNER JOIN clause and with to the query using the role relation
 *
 * @method     \SpoilerWiki\UserQuery|\SpoilerWiki\CanonQuery|\SpoilerWiki\RoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAssignedRole findOne(ConnectionInterface $con = null) Return the first ChildAssignedRole matching the query
 * @method     ChildAssignedRole findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAssignedRole matching the query, or a new ChildAssignedRole object populated from the query conditions when no match is found
 *
 * @method     ChildAssignedRole findOneById(int $id) Return the first ChildAssignedRole filtered by the id column
 * @method     ChildAssignedRole findOneByUserId(int $user_id) Return the first ChildAssignedRole filtered by the user_id column
 * @method     ChildAssignedRole findOneByCanonId(int $canon_id) Return the first ChildAssignedRole filtered by the canon_id column
 * @method     ChildAssignedRole findOneByRoleId(int $role_id) Return the first ChildAssignedRole filtered by the role_id column
 * @method     ChildAssignedRole findOneByAssignedBy(int $assigned_by) Return the first ChildAssignedRole filtered by the assigned_by column *

 * @method     ChildAssignedRole requirePk($key, ConnectionInterface $con = null) Return the ChildAssignedRole by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssignedRole requireOne(ConnectionInterface $con = null) Return the first ChildAssignedRole matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssignedRole requireOneById(int $id) Return the first ChildAssignedRole filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssignedRole requireOneByUserId(int $user_id) Return the first ChildAssignedRole filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssignedRole requireOneByCanonId(int $canon_id) Return the first ChildAssignedRole filtered by the canon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssignedRole requireOneByRoleId(int $role_id) Return the first ChildAssignedRole filtered by the role_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAssignedRole requireOneByAssignedBy(int $assigned_by) Return the first ChildAssignedRole filtered by the assigned_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAssignedRole[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAssignedRole objects based on current ModelCriteria
 * @method     ChildAssignedRole[]|ObjectCollection findById(int $id) Return ChildAssignedRole objects filtered by the id column
 * @method     ChildAssignedRole[]|ObjectCollection findByUserId(int $user_id) Return ChildAssignedRole objects filtered by the user_id column
 * @method     ChildAssignedRole[]|ObjectCollection findByCanonId(int $canon_id) Return ChildAssignedRole objects filtered by the canon_id column
 * @method     ChildAssignedRole[]|ObjectCollection findByRoleId(int $role_id) Return ChildAssignedRole objects filtered by the role_id column
 * @method     ChildAssignedRole[]|ObjectCollection findByAssignedBy(int $assigned_by) Return ChildAssignedRole objects filtered by the assigned_by column
 * @method     ChildAssignedRole[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AssignedRoleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\AssignedRoleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-local', $modelName = '\\SpoilerWiki\\AssignedRole', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAssignedRoleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAssignedRoleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAssignedRoleQuery) {
            return $criteria;
        }
        $query = new ChildAssignedRoleQuery();
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
     * @return ChildAssignedRole|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AssignedRoleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AssignedRoleTableMap::DATABASE_NAME);
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
     * @return ChildAssignedRole A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `user_id`, `canon_id`, `role_id`, `assigned_by` FROM `assigned_role` WHERE `id` = :p0';
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
            /** @var ChildAssignedRole $obj */
            $obj = new ChildAssignedRole();
            $obj->hydrate($row);
            AssignedRoleTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildAssignedRole|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByuser()
     *
     * @see       filterByrole()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByCanonId($canonId = null, $comparison = null)
    {
        if (is_array($canonId)) {
            $useMinMax = false;
            if (isset($canonId['min'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_CANON_ID, $canonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canonId['max'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_CANON_ID, $canonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssignedRoleTableMap::COL_CANON_ID, $canonId, $comparison);
    }

    /**
     * Filter the query on the role_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleId(1234); // WHERE role_id = 1234
     * $query->filterByRoleId(array(12, 34)); // WHERE role_id IN (12, 34)
     * $query->filterByRoleId(array('min' => 12)); // WHERE role_id > 12
     * </code>
     *
     * @param     mixed $roleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByRoleId($roleId = null, $comparison = null)
    {
        if (is_array($roleId)) {
            $useMinMax = false;
            if (isset($roleId['min'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ROLE_ID, $roleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleId['max'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ROLE_ID, $roleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssignedRoleTableMap::COL_ROLE_ID, $roleId, $comparison);
    }

    /**
     * Filter the query on the assigned_by column
     *
     * Example usage:
     * <code>
     * $query->filterByAssignedBy(1234); // WHERE assigned_by = 1234
     * $query->filterByAssignedBy(array(12, 34)); // WHERE assigned_by IN (12, 34)
     * $query->filterByAssignedBy(array('min' => 12)); // WHERE assigned_by > 12
     * </code>
     *
     * @see       filterByassignedBy()
     *
     * @param     mixed $assignedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByAssignedBy($assignedBy = null, $comparison = null)
    {
        if (is_array($assignedBy)) {
            $useMinMax = false;
            if (isset($assignedBy['min'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ASSIGNED_BY, $assignedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($assignedBy['max'])) {
                $this->addUsingAlias(AssignedRoleTableMap::COL_ASSIGNED_BY, $assignedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AssignedRoleTableMap::COL_ASSIGNED_BY, $assignedBy, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\User object
     *
     * @param \SpoilerWiki\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByuser($user, $comparison = null)
    {
        if ($user instanceof \SpoilerWiki\User) {
            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByuser() only accepts arguments of type \SpoilerWiki\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the user relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function joinuser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('user');

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
            $this->addJoinObject($join, 'user');
        }

        return $this;
    }

    /**
     * Use the user relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\UserQuery A secondary query class using the current class as primary query
     */
    public function useuserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinuser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'user', '\SpoilerWiki\UserQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\User object
     *
     * @param \SpoilerWiki\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByassignedBy($user, $comparison = null)
    {
        if ($user instanceof \SpoilerWiki\User) {
            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_ASSIGNED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_ASSIGNED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByassignedBy() only accepts arguments of type \SpoilerWiki\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the assignedBy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function joinassignedBy($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('assignedBy');

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
            $this->addJoinObject($join, 'assignedBy');
        }

        return $this;
    }

    /**
     * Use the assignedBy relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\UserQuery A secondary query class using the current class as primary query
     */
    public function useassignedByQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinassignedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'assignedBy', '\SpoilerWiki\UserQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Canon object
     *
     * @param \SpoilerWiki\Canon|ObjectCollection $canon The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterBycanon($canon, $comparison = null)
    {
        if ($canon instanceof \SpoilerWiki\Canon) {
            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_CANON_ID, $canon->getId(), $comparison);
        } elseif ($canon instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_CANON_ID, $canon->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
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
     * Filter the query by a related \SpoilerWiki\Role object
     *
     * @param \SpoilerWiki\Role|ObjectCollection $role The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function filterByrole($role, $comparison = null)
    {
        if ($role instanceof \SpoilerWiki\Role) {
            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $role->getId(), $comparison);
        } elseif ($role instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AssignedRoleTableMap::COL_USER_ID, $role->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByrole() only accepts arguments of type \SpoilerWiki\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function joinrole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('role');

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
            $this->addJoinObject($join, 'role');
        }

        return $this;
    }

    /**
     * Use the role relation Role object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\RoleQuery A secondary query class using the current class as primary query
     */
    public function useroleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinrole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'role', '\SpoilerWiki\RoleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAssignedRole $assignedRole Object to remove from the list of results
     *
     * @return $this|ChildAssignedRoleQuery The current query, for fluid interface
     */
    public function prune($assignedRole = null)
    {
        if ($assignedRole) {
            $this->addUsingAlias(AssignedRoleTableMap::COL_ID, $assignedRole->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the assigned_role table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AssignedRoleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AssignedRoleTableMap::clearInstancePool();
            AssignedRoleTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AssignedRoleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AssignedRoleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            AssignedRoleTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            AssignedRoleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AssignedRoleQuery
