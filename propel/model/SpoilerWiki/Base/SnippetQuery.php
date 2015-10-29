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
use SpoilerWiki\Snippet as ChildSnippet;
use SpoilerWiki\SnippetQuery as ChildSnippetQuery;
use SpoilerWiki\Map\SnippetTableMap;

/**
 * Base class that represents a query for the 'snippet' table.
 *
 * 
 *
 * @method     ChildSnippetQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSnippetQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildSnippetQuery orderByTopicId($order = Criteria::ASC) Order by the topic_id column
 * @method     ChildSnippetQuery orderByIntroducedAt($order = Criteria::ASC) Order by the introduced_at column
 *
 * @method     ChildSnippetQuery groupById() Group by the id column
 * @method     ChildSnippetQuery groupByContent() Group by the content column
 * @method     ChildSnippetQuery groupByTopicId() Group by the topic_id column
 * @method     ChildSnippetQuery groupByIntroducedAt() Group by the introduced_at column
 *
 * @method     ChildSnippetQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSnippetQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSnippetQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSnippetQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSnippetQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSnippetQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSnippetQuery leftJointopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the topic relation
 * @method     ChildSnippetQuery rightJointopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the topic relation
 * @method     ChildSnippetQuery innerJointopic($relationAlias = null) Adds a INNER JOIN clause to the query using the topic relation
 *
 * @method     ChildSnippetQuery joinWithtopic($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the topic relation
 *
 * @method     ChildSnippetQuery leftJoinWithtopic() Adds a LEFT JOIN clause and with to the query using the topic relation
 * @method     ChildSnippetQuery rightJoinWithtopic() Adds a RIGHT JOIN clause and with to the query using the topic relation
 * @method     ChildSnippetQuery innerJoinWithtopic() Adds a INNER JOIN clause and with to the query using the topic relation
 *
 * @method     ChildSnippetQuery leftJoinintroducedAt($relationAlias = null) Adds a LEFT JOIN clause to the query using the introducedAt relation
 * @method     ChildSnippetQuery rightJoinintroducedAt($relationAlias = null) Adds a RIGHT JOIN clause to the query using the introducedAt relation
 * @method     ChildSnippetQuery innerJoinintroducedAt($relationAlias = null) Adds a INNER JOIN clause to the query using the introducedAt relation
 *
 * @method     ChildSnippetQuery joinWithintroducedAt($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the introducedAt relation
 *
 * @method     ChildSnippetQuery leftJoinWithintroducedAt() Adds a LEFT JOIN clause and with to the query using the introducedAt relation
 * @method     ChildSnippetQuery rightJoinWithintroducedAt() Adds a RIGHT JOIN clause and with to the query using the introducedAt relation
 * @method     ChildSnippetQuery innerJoinWithintroducedAt() Adds a INNER JOIN clause and with to the query using the introducedAt relation
 *
 * @method     \SpoilerWiki\TopicQuery|\SpoilerWiki\MilestoneQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSnippet findOne(ConnectionInterface $con = null) Return the first ChildSnippet matching the query
 * @method     ChildSnippet findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSnippet matching the query, or a new ChildSnippet object populated from the query conditions when no match is found
 *
 * @method     ChildSnippet findOneById(int $id) Return the first ChildSnippet filtered by the id column
 * @method     ChildSnippet findOneByContent(string $content) Return the first ChildSnippet filtered by the content column
 * @method     ChildSnippet findOneByTopicId(int $topic_id) Return the first ChildSnippet filtered by the topic_id column
 * @method     ChildSnippet findOneByIntroducedAt(int $introduced_at) Return the first ChildSnippet filtered by the introduced_at column *

 * @method     ChildSnippet requirePk($key, ConnectionInterface $con = null) Return the ChildSnippet by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSnippet requireOne(ConnectionInterface $con = null) Return the first ChildSnippet matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSnippet requireOneById(int $id) Return the first ChildSnippet filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSnippet requireOneByContent(string $content) Return the first ChildSnippet filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSnippet requireOneByTopicId(int $topic_id) Return the first ChildSnippet filtered by the topic_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSnippet requireOneByIntroducedAt(int $introduced_at) Return the first ChildSnippet filtered by the introduced_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSnippet[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSnippet objects based on current ModelCriteria
 * @method     ChildSnippet[]|ObjectCollection findById(int $id) Return ChildSnippet objects filtered by the id column
 * @method     ChildSnippet[]|ObjectCollection findByContent(string $content) Return ChildSnippet objects filtered by the content column
 * @method     ChildSnippet[]|ObjectCollection findByTopicId(int $topic_id) Return ChildSnippet objects filtered by the topic_id column
 * @method     ChildSnippet[]|ObjectCollection findByIntroducedAt(int $introduced_at) Return ChildSnippet objects filtered by the introduced_at column
 * @method     ChildSnippet[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SnippetQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\SnippetQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\Snippet', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSnippetQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSnippetQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSnippetQuery) {
            return $criteria;
        }
        $query = new ChildSnippetQuery();
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
     * @return ChildSnippet|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SnippetTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SnippetTableMap::DATABASE_NAME);
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
     * @return ChildSnippet A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `content`, `topic_id`, `introduced_at` FROM `snippet` WHERE `id` = :p0';
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
            /** @var ChildSnippet $obj */
            $obj = new ChildSnippet();
            $obj->hydrate($row);
            SnippetTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSnippet|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SnippetTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SnippetTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SnippetTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SnippetTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SnippetTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%'); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $content)) {
                $content = str_replace('*', '%', $content);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SnippetTableMap::COL_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the topic_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTopicId(1234); // WHERE topic_id = 1234
     * $query->filterByTopicId(array(12, 34)); // WHERE topic_id IN (12, 34)
     * $query->filterByTopicId(array('min' => 12)); // WHERE topic_id > 12
     * </code>
     *
     * @see       filterBytopic()
     *
     * @param     mixed $topicId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByTopicId($topicId = null, $comparison = null)
    {
        if (is_array($topicId)) {
            $useMinMax = false;
            if (isset($topicId['min'])) {
                $this->addUsingAlias(SnippetTableMap::COL_TOPIC_ID, $topicId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($topicId['max'])) {
                $this->addUsingAlias(SnippetTableMap::COL_TOPIC_ID, $topicId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SnippetTableMap::COL_TOPIC_ID, $topicId, $comparison);
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
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByIntroducedAt($introducedAt = null, $comparison = null)
    {
        if (is_array($introducedAt)) {
            $useMinMax = false;
            if (isset($introducedAt['min'])) {
                $this->addUsingAlias(SnippetTableMap::COL_INTRODUCED_AT, $introducedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($introducedAt['max'])) {
                $this->addUsingAlias(SnippetTableMap::COL_INTRODUCED_AT, $introducedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SnippetTableMap::COL_INTRODUCED_AT, $introducedAt, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Topic object
     *
     * @param \SpoilerWiki\Topic|ObjectCollection $topic The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSnippetQuery The current query, for fluid interface
     */
    public function filterBytopic($topic, $comparison = null)
    {
        if ($topic instanceof \SpoilerWiki\Topic) {
            return $this
                ->addUsingAlias(SnippetTableMap::COL_TOPIC_ID, $topic->getId(), $comparison);
        } elseif ($topic instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SnippetTableMap::COL_TOPIC_ID, $topic->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBytopic() only accepts arguments of type \SpoilerWiki\Topic or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the topic relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function jointopic($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('topic');

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
            $this->addJoinObject($join, 'topic');
        }

        return $this;
    }

    /**
     * Use the topic relation Topic object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\TopicQuery A secondary query class using the current class as primary query
     */
    public function usetopicQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->jointopic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'topic', '\SpoilerWiki\TopicQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Milestone object
     *
     * @param \SpoilerWiki\Milestone|ObjectCollection $milestone The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSnippetQuery The current query, for fluid interface
     */
    public function filterByintroducedAt($milestone, $comparison = null)
    {
        if ($milestone instanceof \SpoilerWiki\Milestone) {
            return $this
                ->addUsingAlias(SnippetTableMap::COL_INTRODUCED_AT, $milestone->getId(), $comparison);
        } elseif ($milestone instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SnippetTableMap::COL_INTRODUCED_AT, $milestone->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSnippetQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildSnippet $snippet Object to remove from the list of results
     *
     * @return $this|ChildSnippetQuery The current query, for fluid interface
     */
    public function prune($snippet = null)
    {
        if ($snippet) {
            $this->addUsingAlias(SnippetTableMap::COL_ID, $snippet->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the snippet table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SnippetTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SnippetTableMap::clearInstancePool();
            SnippetTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SnippetTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SnippetTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            SnippetTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            SnippetTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SnippetQuery
