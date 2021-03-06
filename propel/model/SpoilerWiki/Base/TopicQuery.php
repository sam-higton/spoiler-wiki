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
use SpoilerWiki\Topic as ChildTopic;
use SpoilerWiki\TopicQuery as ChildTopicQuery;
use SpoilerWiki\Map\TopicTableMap;

/**
 * Base class that represents a query for the 'topic' table.
 *
 * 
 *
 * @method     ChildTopicQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildTopicQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildTopicQuery orderByCanonId($order = Criteria::ASC) Order by the canon_id column
 * @method     ChildTopicQuery orderByIntroducedAt($order = Criteria::ASC) Order by the introduced_at column
 *
 * @method     ChildTopicQuery groupById() Group by the id column
 * @method     ChildTopicQuery groupByName() Group by the name column
 * @method     ChildTopicQuery groupByCanonId() Group by the canon_id column
 * @method     ChildTopicQuery groupByIntroducedAt() Group by the introduced_at column
 *
 * @method     ChildTopicQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTopicQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTopicQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTopicQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTopicQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTopicQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTopicQuery leftJoincanon($relationAlias = null) Adds a LEFT JOIN clause to the query using the canon relation
 * @method     ChildTopicQuery rightJoincanon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the canon relation
 * @method     ChildTopicQuery innerJoincanon($relationAlias = null) Adds a INNER JOIN clause to the query using the canon relation
 *
 * @method     ChildTopicQuery joinWithcanon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the canon relation
 *
 * @method     ChildTopicQuery leftJoinWithcanon() Adds a LEFT JOIN clause and with to the query using the canon relation
 * @method     ChildTopicQuery rightJoinWithcanon() Adds a RIGHT JOIN clause and with to the query using the canon relation
 * @method     ChildTopicQuery innerJoinWithcanon() Adds a INNER JOIN clause and with to the query using the canon relation
 *
 * @method     ChildTopicQuery leftJoinintroducedAt($relationAlias = null) Adds a LEFT JOIN clause to the query using the introducedAt relation
 * @method     ChildTopicQuery rightJoinintroducedAt($relationAlias = null) Adds a RIGHT JOIN clause to the query using the introducedAt relation
 * @method     ChildTopicQuery innerJoinintroducedAt($relationAlias = null) Adds a INNER JOIN clause to the query using the introducedAt relation
 *
 * @method     ChildTopicQuery joinWithintroducedAt($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the introducedAt relation
 *
 * @method     ChildTopicQuery leftJoinWithintroducedAt() Adds a LEFT JOIN clause and with to the query using the introducedAt relation
 * @method     ChildTopicQuery rightJoinWithintroducedAt() Adds a RIGHT JOIN clause and with to the query using the introducedAt relation
 * @method     ChildTopicQuery innerJoinWithintroducedAt() Adds a INNER JOIN clause and with to the query using the introducedAt relation
 *
 * @method     ChildTopicQuery leftJoinSnippet($relationAlias = null) Adds a LEFT JOIN clause to the query using the Snippet relation
 * @method     ChildTopicQuery rightJoinSnippet($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Snippet relation
 * @method     ChildTopicQuery innerJoinSnippet($relationAlias = null) Adds a INNER JOIN clause to the query using the Snippet relation
 *
 * @method     ChildTopicQuery joinWithSnippet($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Snippet relation
 *
 * @method     ChildTopicQuery leftJoinWithSnippet() Adds a LEFT JOIN clause and with to the query using the Snippet relation
 * @method     ChildTopicQuery rightJoinWithSnippet() Adds a RIGHT JOIN clause and with to the query using the Snippet relation
 * @method     ChildTopicQuery innerJoinWithSnippet() Adds a INNER JOIN clause and with to the query using the Snippet relation
 *
 * @method     ChildTopicQuery leftJoinSummary($relationAlias = null) Adds a LEFT JOIN clause to the query using the Summary relation
 * @method     ChildTopicQuery rightJoinSummary($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Summary relation
 * @method     ChildTopicQuery innerJoinSummary($relationAlias = null) Adds a INNER JOIN clause to the query using the Summary relation
 *
 * @method     ChildTopicQuery joinWithSummary($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Summary relation
 *
 * @method     ChildTopicQuery leftJoinWithSummary() Adds a LEFT JOIN clause and with to the query using the Summary relation
 * @method     ChildTopicQuery rightJoinWithSummary() Adds a RIGHT JOIN clause and with to the query using the Summary relation
 * @method     ChildTopicQuery innerJoinWithSummary() Adds a INNER JOIN clause and with to the query using the Summary relation
 *
 * @method     \SpoilerWiki\CanonQuery|\SpoilerWiki\MilestoneQuery|\SpoilerWiki\SnippetQuery|\SpoilerWiki\SummaryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTopic findOne(ConnectionInterface $con = null) Return the first ChildTopic matching the query
 * @method     ChildTopic findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTopic matching the query, or a new ChildTopic object populated from the query conditions when no match is found
 *
 * @method     ChildTopic findOneById(int $id) Return the first ChildTopic filtered by the id column
 * @method     ChildTopic findOneByName(string $name) Return the first ChildTopic filtered by the name column
 * @method     ChildTopic findOneByCanonId(int $canon_id) Return the first ChildTopic filtered by the canon_id column
 * @method     ChildTopic findOneByIntroducedAt(int $introduced_at) Return the first ChildTopic filtered by the introduced_at column *

 * @method     ChildTopic requirePk($key, ConnectionInterface $con = null) Return the ChildTopic by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOne(ConnectionInterface $con = null) Return the first ChildTopic matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTopic requireOneById(int $id) Return the first ChildTopic filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneByName(string $name) Return the first ChildTopic filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneByCanonId(int $canon_id) Return the first ChildTopic filtered by the canon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTopic requireOneByIntroducedAt(int $introduced_at) Return the first ChildTopic filtered by the introduced_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTopic[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTopic objects based on current ModelCriteria
 * @method     ChildTopic[]|ObjectCollection findById(int $id) Return ChildTopic objects filtered by the id column
 * @method     ChildTopic[]|ObjectCollection findByName(string $name) Return ChildTopic objects filtered by the name column
 * @method     ChildTopic[]|ObjectCollection findByCanonId(int $canon_id) Return ChildTopic objects filtered by the canon_id column
 * @method     ChildTopic[]|ObjectCollection findByIntroducedAt(int $introduced_at) Return ChildTopic objects filtered by the introduced_at column
 * @method     ChildTopic[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TopicQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\TopicQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\Topic', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTopicQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTopicQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTopicQuery) {
            return $criteria;
        }
        $query = new ChildTopicQuery();
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
     * @return ChildTopic|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TopicTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TopicTableMap::DATABASE_NAME);
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
     * @return ChildTopic A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `canon_id`, `introduced_at` FROM `topic` WHERE `id` = :p0';
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
            /** @var ChildTopic $obj */
            $obj = new ChildTopic();
            $obj->hydrate($row);
            TopicTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildTopic|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TopicTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TopicTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TopicTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TopicTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TopicTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByCanonId($canonId = null, $comparison = null)
    {
        if (is_array($canonId)) {
            $useMinMax = false;
            if (isset($canonId['min'])) {
                $this->addUsingAlias(TopicTableMap::COL_CANON_ID, $canonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canonId['max'])) {
                $this->addUsingAlias(TopicTableMap::COL_CANON_ID, $canonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_CANON_ID, $canonId, $comparison);
    }

    /**
     * Filter the query on the introduced_at column
     *
     * Example usage:
     * <code>
     * $query->filterByIntroducedAt(1234); // WHERE introduced_at = 1234
     * $query->filterByIntroducedAt(array(12, 34)); // WHERE introduced_at IN (12, 34)
     * $query->filterByIntroducedAt(array('min' => 12)); // WHERE introduced_at > 12
     * </code>
     *
     * @see       filterByintroducedAt()
     *
     * @param     mixed $introducedAt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function filterByIntroducedAt($introducedAt = null, $comparison = null)
    {
        if (is_array($introducedAt)) {
            $useMinMax = false;
            if (isset($introducedAt['min'])) {
                $this->addUsingAlias(TopicTableMap::COL_INTRODUCED_AT, $introducedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($introducedAt['max'])) {
                $this->addUsingAlias(TopicTableMap::COL_INTRODUCED_AT, $introducedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TopicTableMap::COL_INTRODUCED_AT, $introducedAt, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Canon object
     *
     * @param \SpoilerWiki\Canon|ObjectCollection $canon The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterBycanon($canon, $comparison = null)
    {
        if ($canon instanceof \SpoilerWiki\Canon) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_CANON_ID, $canon->getId(), $comparison);
        } elseif ($canon instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TopicTableMap::COL_CANON_ID, $canon->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildTopicQuery The current query, for fluid interface
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
     * Filter the query by a related \SpoilerWiki\Milestone object
     *
     * @param \SpoilerWiki\Milestone|ObjectCollection $milestone The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterByintroducedAt($milestone, $comparison = null)
    {
        if ($milestone instanceof \SpoilerWiki\Milestone) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_INTRODUCED_AT, $milestone->getId(), $comparison);
        } elseif ($milestone instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TopicTableMap::COL_INTRODUCED_AT, $milestone->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByintroducedAt() only accepts arguments of type \SpoilerWiki\Milestone or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the introducedAt relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinintroducedAt($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('introducedAt');

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
            $this->addJoinObject($join, 'introducedAt');
        }

        return $this;
    }

    /**
     * Use the introducedAt relation Milestone object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\MilestoneQuery A secondary query class using the current class as primary query
     */
    public function useintroducedAtQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinintroducedAt($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'introducedAt', '\SpoilerWiki\MilestoneQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Snippet object
     *
     * @param \SpoilerWiki\Snippet|ObjectCollection $snippet the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterBySnippet($snippet, $comparison = null)
    {
        if ($snippet instanceof \SpoilerWiki\Snippet) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $snippet->getTopicId(), $comparison);
        } elseif ($snippet instanceof ObjectCollection) {
            return $this
                ->useSnippetQuery()
                ->filterByPrimaryKeys($snippet->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySnippet() only accepts arguments of type \SpoilerWiki\Snippet or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Snippet relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinSnippet($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Snippet');

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
            $this->addJoinObject($join, 'Snippet');
        }

        return $this;
    }

    /**
     * Use the Snippet relation Snippet object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\SnippetQuery A secondary query class using the current class as primary query
     */
    public function useSnippetQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSnippet($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Snippet', '\SpoilerWiki\SnippetQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Summary object
     *
     * @param \SpoilerWiki\Summary|ObjectCollection $summary the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTopicQuery The current query, for fluid interface
     */
    public function filterBySummary($summary, $comparison = null)
    {
        if ($summary instanceof \SpoilerWiki\Summary) {
            return $this
                ->addUsingAlias(TopicTableMap::COL_ID, $summary->getTopicId(), $comparison);
        } elseif ($summary instanceof ObjectCollection) {
            return $this
                ->useSummaryQuery()
                ->filterByPrimaryKeys($summary->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySummary() only accepts arguments of type \SpoilerWiki\Summary or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Summary relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function joinSummary($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Summary');

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
            $this->addJoinObject($join, 'Summary');
        }

        return $this;
    }

    /**
     * Use the Summary relation Summary object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\SummaryQuery A secondary query class using the current class as primary query
     */
    public function useSummaryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSummary($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Summary', '\SpoilerWiki\SummaryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTopic $topic Object to remove from the list of results
     *
     * @return $this|ChildTopicQuery The current query, for fluid interface
     */
    public function prune($topic = null)
    {
        if ($topic) {
            $this->addUsingAlias(TopicTableMap::COL_ID, $topic->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the topic table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TopicTableMap::clearInstancePool();
            TopicTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TopicTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TopicTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            TopicTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            TopicTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TopicQuery
