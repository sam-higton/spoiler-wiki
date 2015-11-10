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
use SpoilerWiki\User as ChildUser;
use SpoilerWiki\UserQuery as ChildUserQuery;
use SpoilerWiki\Map\UserTableMap;

/**
 * Base class that represents a query for the 'user' table.
 *
 * 
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinAuthToken($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuthToken relation
 * @method     ChildUserQuery rightJoinAuthToken($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuthToken relation
 * @method     ChildUserQuery innerJoinAuthToken($relationAlias = null) Adds a INNER JOIN clause to the query using the AuthToken relation
 *
 * @method     ChildUserQuery joinWithAuthToken($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AuthToken relation
 *
 * @method     ChildUserQuery leftJoinWithAuthToken() Adds a LEFT JOIN clause and with to the query using the AuthToken relation
 * @method     ChildUserQuery rightJoinWithAuthToken() Adds a RIGHT JOIN clause and with to the query using the AuthToken relation
 * @method     ChildUserQuery innerJoinWithAuthToken() Adds a INNER JOIN clause and with to the query using the AuthToken relation
 *
 * @method     ChildUserQuery leftJoinAssignedRoleGlobalRelatedByUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssignedRoleGlobalRelatedByUserId relation
 * @method     ChildUserQuery rightJoinAssignedRoleGlobalRelatedByUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssignedRoleGlobalRelatedByUserId relation
 * @method     ChildUserQuery innerJoinAssignedRoleGlobalRelatedByUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AssignedRoleGlobalRelatedByUserId relation
 *
 * @method     ChildUserQuery joinWithAssignedRoleGlobalRelatedByUserId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssignedRoleGlobalRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinWithAssignedRoleGlobalRelatedByUserId() Adds a LEFT JOIN clause and with to the query using the AssignedRoleGlobalRelatedByUserId relation
 * @method     ChildUserQuery rightJoinWithAssignedRoleGlobalRelatedByUserId() Adds a RIGHT JOIN clause and with to the query using the AssignedRoleGlobalRelatedByUserId relation
 * @method     ChildUserQuery innerJoinWithAssignedRoleGlobalRelatedByUserId() Adds a INNER JOIN clause and with to the query using the AssignedRoleGlobalRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinAssignedRoleGlobalRelatedByAssignedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 * @method     ChildUserQuery rightJoinAssignedRoleGlobalRelatedByAssignedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 * @method     ChildUserQuery innerJoinAssignedRoleGlobalRelatedByAssignedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 *
 * @method     ChildUserQuery joinWithAssignedRoleGlobalRelatedByAssignedBy($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 *
 * @method     ChildUserQuery leftJoinWithAssignedRoleGlobalRelatedByAssignedBy() Adds a LEFT JOIN clause and with to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 * @method     ChildUserQuery rightJoinWithAssignedRoleGlobalRelatedByAssignedBy() Adds a RIGHT JOIN clause and with to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 * @method     ChildUserQuery innerJoinWithAssignedRoleGlobalRelatedByAssignedBy() Adds a INNER JOIN clause and with to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
 *
 * @method     ChildUserQuery leftJoinAssignedRoleRelatedByUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssignedRoleRelatedByUserId relation
 * @method     ChildUserQuery rightJoinAssignedRoleRelatedByUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssignedRoleRelatedByUserId relation
 * @method     ChildUserQuery innerJoinAssignedRoleRelatedByUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the AssignedRoleRelatedByUserId relation
 *
 * @method     ChildUserQuery joinWithAssignedRoleRelatedByUserId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssignedRoleRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinWithAssignedRoleRelatedByUserId() Adds a LEFT JOIN clause and with to the query using the AssignedRoleRelatedByUserId relation
 * @method     ChildUserQuery rightJoinWithAssignedRoleRelatedByUserId() Adds a RIGHT JOIN clause and with to the query using the AssignedRoleRelatedByUserId relation
 * @method     ChildUserQuery innerJoinWithAssignedRoleRelatedByUserId() Adds a INNER JOIN clause and with to the query using the AssignedRoleRelatedByUserId relation
 *
 * @method     ChildUserQuery leftJoinAssignedRoleRelatedByAssignedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the AssignedRoleRelatedByAssignedBy relation
 * @method     ChildUserQuery rightJoinAssignedRoleRelatedByAssignedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AssignedRoleRelatedByAssignedBy relation
 * @method     ChildUserQuery innerJoinAssignedRoleRelatedByAssignedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the AssignedRoleRelatedByAssignedBy relation
 *
 * @method     ChildUserQuery joinWithAssignedRoleRelatedByAssignedBy($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AssignedRoleRelatedByAssignedBy relation
 *
 * @method     ChildUserQuery leftJoinWithAssignedRoleRelatedByAssignedBy() Adds a LEFT JOIN clause and with to the query using the AssignedRoleRelatedByAssignedBy relation
 * @method     ChildUserQuery rightJoinWithAssignedRoleRelatedByAssignedBy() Adds a RIGHT JOIN clause and with to the query using the AssignedRoleRelatedByAssignedBy relation
 * @method     ChildUserQuery innerJoinWithAssignedRoleRelatedByAssignedBy() Adds a INNER JOIN clause and with to the query using the AssignedRoleRelatedByAssignedBy relation
 *
 * @method     \SpoilerWiki\AuthTokenQuery|\SpoilerWiki\AssignedRoleGlobalQuery|\SpoilerWiki\AssignedRoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `username`, `password`, `email` FROM `user` WHERE `id` = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\AuthToken object
     *
     * @param \SpoilerWiki\AuthToken|ObjectCollection $authToken the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAuthToken($authToken, $comparison = null)
    {
        if ($authToken instanceof \SpoilerWiki\AuthToken) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $authToken->getUserId(), $comparison);
        } elseif ($authToken instanceof ObjectCollection) {
            return $this
                ->useAuthTokenQuery()
                ->filterByPrimaryKeys($authToken->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAuthToken() only accepts arguments of type \SpoilerWiki\AuthToken or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuthToken relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAuthToken($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuthToken');

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
            $this->addJoinObject($join, 'AuthToken');
        }

        return $this;
    }

    /**
     * Use the AuthToken relation AuthToken object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\AuthTokenQuery A secondary query class using the current class as primary query
     */
    public function useAuthTokenQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthToken($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuthToken', '\SpoilerWiki\AuthTokenQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\AssignedRoleGlobal object
     *
     * @param \SpoilerWiki\AssignedRoleGlobal|ObjectCollection $assignedRoleGlobal the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAssignedRoleGlobalRelatedByUserId($assignedRoleGlobal, $comparison = null)
    {
        if ($assignedRoleGlobal instanceof \SpoilerWiki\AssignedRoleGlobal) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $assignedRoleGlobal->getUserId(), $comparison);
        } elseif ($assignedRoleGlobal instanceof ObjectCollection) {
            return $this
                ->useAssignedRoleGlobalRelatedByUserIdQuery()
                ->filterByPrimaryKeys($assignedRoleGlobal->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAssignedRoleGlobalRelatedByUserId() only accepts arguments of type \SpoilerWiki\AssignedRoleGlobal or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssignedRoleGlobalRelatedByUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAssignedRoleGlobalRelatedByUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssignedRoleGlobalRelatedByUserId');

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
            $this->addJoinObject($join, 'AssignedRoleGlobalRelatedByUserId');
        }

        return $this;
    }

    /**
     * Use the AssignedRoleGlobalRelatedByUserId relation AssignedRoleGlobal object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\AssignedRoleGlobalQuery A secondary query class using the current class as primary query
     */
    public function useAssignedRoleGlobalRelatedByUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssignedRoleGlobalRelatedByUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssignedRoleGlobalRelatedByUserId', '\SpoilerWiki\AssignedRoleGlobalQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\AssignedRoleGlobal object
     *
     * @param \SpoilerWiki\AssignedRoleGlobal|ObjectCollection $assignedRoleGlobal the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAssignedRoleGlobalRelatedByAssignedBy($assignedRoleGlobal, $comparison = null)
    {
        if ($assignedRoleGlobal instanceof \SpoilerWiki\AssignedRoleGlobal) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $assignedRoleGlobal->getAssignedBy(), $comparison);
        } elseif ($assignedRoleGlobal instanceof ObjectCollection) {
            return $this
                ->useAssignedRoleGlobalRelatedByAssignedByQuery()
                ->filterByPrimaryKeys($assignedRoleGlobal->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAssignedRoleGlobalRelatedByAssignedBy() only accepts arguments of type \SpoilerWiki\AssignedRoleGlobal or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssignedRoleGlobalRelatedByAssignedBy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAssignedRoleGlobalRelatedByAssignedBy($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssignedRoleGlobalRelatedByAssignedBy');

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
            $this->addJoinObject($join, 'AssignedRoleGlobalRelatedByAssignedBy');
        }

        return $this;
    }

    /**
     * Use the AssignedRoleGlobalRelatedByAssignedBy relation AssignedRoleGlobal object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\AssignedRoleGlobalQuery A secondary query class using the current class as primary query
     */
    public function useAssignedRoleGlobalRelatedByAssignedByQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssignedRoleGlobalRelatedByAssignedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssignedRoleGlobalRelatedByAssignedBy', '\SpoilerWiki\AssignedRoleGlobalQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\AssignedRole object
     *
     * @param \SpoilerWiki\AssignedRole|ObjectCollection $assignedRole the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAssignedRoleRelatedByUserId($assignedRole, $comparison = null)
    {
        if ($assignedRole instanceof \SpoilerWiki\AssignedRole) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $assignedRole->getUserId(), $comparison);
        } elseif ($assignedRole instanceof ObjectCollection) {
            return $this
                ->useAssignedRoleRelatedByUserIdQuery()
                ->filterByPrimaryKeys($assignedRole->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAssignedRoleRelatedByUserId() only accepts arguments of type \SpoilerWiki\AssignedRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssignedRoleRelatedByUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAssignedRoleRelatedByUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssignedRoleRelatedByUserId');

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
            $this->addJoinObject($join, 'AssignedRoleRelatedByUserId');
        }

        return $this;
    }

    /**
     * Use the AssignedRoleRelatedByUserId relation AssignedRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\AssignedRoleQuery A secondary query class using the current class as primary query
     */
    public function useAssignedRoleRelatedByUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssignedRoleRelatedByUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssignedRoleRelatedByUserId', '\SpoilerWiki\AssignedRoleQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\AssignedRole object
     *
     * @param \SpoilerWiki\AssignedRole|ObjectCollection $assignedRole the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAssignedRoleRelatedByAssignedBy($assignedRole, $comparison = null)
    {
        if ($assignedRole instanceof \SpoilerWiki\AssignedRole) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $assignedRole->getAssignedBy(), $comparison);
        } elseif ($assignedRole instanceof ObjectCollection) {
            return $this
                ->useAssignedRoleRelatedByAssignedByQuery()
                ->filterByPrimaryKeys($assignedRole->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAssignedRoleRelatedByAssignedBy() only accepts arguments of type \SpoilerWiki\AssignedRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AssignedRoleRelatedByAssignedBy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAssignedRoleRelatedByAssignedBy($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AssignedRoleRelatedByAssignedBy');

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
            $this->addJoinObject($join, 'AssignedRoleRelatedByAssignedBy');
        }

        return $this;
    }

    /**
     * Use the AssignedRoleRelatedByAssignedBy relation AssignedRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\AssignedRoleQuery A secondary query class using the current class as primary query
     */
    public function useAssignedRoleRelatedByAssignedByQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAssignedRoleRelatedByAssignedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AssignedRoleRelatedByAssignedBy', '\SpoilerWiki\AssignedRoleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            UserTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
