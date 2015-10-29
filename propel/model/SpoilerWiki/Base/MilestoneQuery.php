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
use SpoilerWiki\Milestone as ChildMilestone;
use SpoilerWiki\MilestoneQuery as ChildMilestoneQuery;
use SpoilerWiki\Map\MilestoneTableMap;

/**
 * Base class that represents a query for the 'milestone' table.
 *
 * 
 *
 * @method     ChildMilestoneQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMilestoneQuery orderByLabel($order = Criteria::ASC) Order by the label column
 * @method     ChildMilestoneQuery orderByWorkId($order = Criteria::ASC) Order by the work_id column
 * @method     ChildMilestoneQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method     ChildMilestoneQuery groupById() Group by the id column
 * @method     ChildMilestoneQuery groupByLabel() Group by the label column
 * @method     ChildMilestoneQuery groupByWorkId() Group by the work_id column
 * @method     ChildMilestoneQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method     ChildMilestoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMilestoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMilestoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMilestoneQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMilestoneQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMilestoneQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMilestoneQuery leftJoinwork($relationAlias = null) Adds a LEFT JOIN clause to the query using the work relation
 * @method     ChildMilestoneQuery rightJoinwork($relationAlias = null) Adds a RIGHT JOIN clause to the query using the work relation
 * @method     ChildMilestoneQuery innerJoinwork($relationAlias = null) Adds a INNER JOIN clause to the query using the work relation
 *
 * @method     ChildMilestoneQuery joinWithwork($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the work relation
 *
 * @method     ChildMilestoneQuery leftJoinWithwork() Adds a LEFT JOIN clause and with to the query using the work relation
 * @method     ChildMilestoneQuery rightJoinWithwork() Adds a RIGHT JOIN clause and with to the query using the work relation
 * @method     ChildMilestoneQuery innerJoinWithwork() Adds a INNER JOIN clause and with to the query using the work relation
 *
 * @method     ChildMilestoneQuery leftJoinTopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the Topic relation
 * @method     ChildMilestoneQuery rightJoinTopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Topic relation
 * @method     ChildMilestoneQuery innerJoinTopic($relationAlias = null) Adds a INNER JOIN clause to the query using the Topic relation
 *
 * @method     ChildMilestoneQuery joinWithTopic($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Topic relation
 *
 * @method     ChildMilestoneQuery leftJoinWithTopic() Adds a LEFT JOIN clause and with to the query using the Topic relation
 * @method     ChildMilestoneQuery rightJoinWithTopic() Adds a RIGHT JOIN clause and with to the query using the Topic relation
 * @method     ChildMilestoneQuery innerJoinWithTopic() Adds a INNER JOIN clause and with to the query using the Topic relation
 *
 * @method     ChildMilestoneQuery leftJoinSnippet($relationAlias = null) Adds a LEFT JOIN clause to the query using the Snippet relation
 * @method     ChildMilestoneQuery rightJoinSnippet($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Snippet relation
 * @method     ChildMilestoneQuery innerJoinSnippet($relationAlias = null) Adds a INNER JOIN clause to the query using the Snippet relation
 *
 * @method     ChildMilestoneQuery joinWithSnippet($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Snippet relation
 *
 * @method     ChildMilestoneQuery leftJoinWithSnippet() Adds a LEFT JOIN clause and with to the query using the Snippet relation
 * @method     ChildMilestoneQuery rightJoinWithSnippet() Adds a RIGHT JOIN clause and with to the query using the Snippet relation
 * @method     ChildMilestoneQuery innerJoinWithSnippet() Adds a INNER JOIN clause and with to the query using the Snippet relation
 *
 * @method     ChildMilestoneQuery leftJoinSummary($relationAlias = null) Adds a LEFT JOIN clause to the query using the Summary relation
 * @method     ChildMilestoneQuery rightJoinSummary($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Summary relation
 * @method     ChildMilestoneQuery innerJoinSummary($relationAlias = null) Adds a INNER JOIN clause to the query using the Summary relation
 *
 * @method     ChildMilestoneQuery joinWithSummary($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Summary relation
 *
 * @method     ChildMilestoneQuery leftJoinWithSummary() Adds a LEFT JOIN clause and with to the query using the Summary relation
 * @method     ChildMilestoneQuery rightJoinWithSummary() Adds a RIGHT JOIN clause and with to the query using the Summary relation
 * @method     ChildMilestoneQuery innerJoinWithSummary() Adds a INNER JOIN clause and with to the query using the Summary relation
 *
 * @method     \SpoilerWiki\WorkQuery|\SpoilerWiki\TopicQuery|\SpoilerWiki\SnippetQuery|\SpoilerWiki\SummaryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMilestone findOne(ConnectionInterface $con = null) Return the first ChildMilestone matching the query
 * @method     ChildMilestone findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMilestone matching the query, or a new ChildMilestone object populated from the query conditions when no match is found
 *
 * @method     ChildMilestone findOneById(int $id) Return the first ChildMilestone filtered by the id column
 * @method     ChildMilestone findOneByLabel(string $label) Return the first ChildMilestone filtered by the label column
 * @method     ChildMilestone findOneByWorkId(int $work_id) Return the first ChildMilestone filtered by the work_id column
 * @method     ChildMilestone findOneBySortableRank(int $sortable_rank) Return the first ChildMilestone filtered by the sortable_rank column *

 * @method     ChildMilestone requirePk($key, ConnectionInterface $con = null) Return the ChildMilestone by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMilestone requireOne(ConnectionInterface $con = null) Return the first ChildMilestone matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMilestone requireOneById(int $id) Return the first ChildMilestone filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMilestone requireOneByLabel(string $label) Return the first ChildMilestone filtered by the label column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMilestone requireOneByWorkId(int $work_id) Return the first ChildMilestone filtered by the work_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMilestone requireOneBySortableRank(int $sortable_rank) Return the first ChildMilestone filtered by the sortable_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMilestone[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMilestone objects based on current ModelCriteria
 * @method     ChildMilestone[]|ObjectCollection findById(int $id) Return ChildMilestone objects filtered by the id column
 * @method     ChildMilestone[]|ObjectCollection findByLabel(string $label) Return ChildMilestone objects filtered by the label column
 * @method     ChildMilestone[]|ObjectCollection findByWorkId(int $work_id) Return ChildMilestone objects filtered by the work_id column
 * @method     ChildMilestone[]|ObjectCollection findBySortableRank(int $sortable_rank) Return ChildMilestone objects filtered by the sortable_rank column
 * @method     ChildMilestone[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MilestoneQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\MilestoneQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\Milestone', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMilestoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMilestoneQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMilestoneQuery) {
            return $criteria;
        }
        $query = new ChildMilestoneQuery();
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
     * @return ChildMilestone|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MilestoneTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
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
     * @return ChildMilestone A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `label`, `work_id`, `sortable_rank` FROM `milestone` WHERE `id` = :p0';
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
            /** @var ChildMilestone $obj */
            $obj = new ChildMilestone();
            $obj->hydrate($row);
            MilestoneTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMilestone|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MilestoneTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MilestoneTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestoneTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the label column
     *
     * Example usage:
     * <code>
     * $query->filterByLabel('fooValue');   // WHERE label = 'fooValue'
     * $query->filterByLabel('%fooValue%'); // WHERE label LIKE '%fooValue%'
     * </code>
     *
     * @param     string $label The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByLabel($label = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($label)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $label)) {
                $label = str_replace('*', '%', $label);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MilestoneTableMap::COL_LABEL, $label, $comparison);
    }

    /**
     * Filter the query on the work_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWorkId(1234); // WHERE work_id = 1234
     * $query->filterByWorkId(array(12, 34)); // WHERE work_id IN (12, 34)
     * $query->filterByWorkId(array('min' => 12)); // WHERE work_id > 12
     * </code>
     *
     * @see       filterBywork()
     *
     * @param     mixed $workId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByWorkId($workId = null, $comparison = null)
    {
        if (is_array($workId)) {
            $useMinMax = false;
            if (isset($workId['min'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_WORK_ID, $workId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($workId['max'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_WORK_ID, $workId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestoneTableMap::COL_WORK_ID, $workId, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank > 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(MilestoneTableMap::COL_SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MilestoneTableMap::COL_SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Work object
     *
     * @param \SpoilerWiki\Work|ObjectCollection $work The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterBywork($work, $comparison = null)
    {
        if ($work instanceof \SpoilerWiki\Work) {
            return $this
                ->addUsingAlias(MilestoneTableMap::COL_WORK_ID, $work->getId(), $comparison);
        } elseif ($work instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MilestoneTableMap::COL_WORK_ID, $work->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBywork() only accepts arguments of type \SpoilerWiki\Work or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the work relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function joinwork($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('work');

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
            $this->addJoinObject($join, 'work');
        }

        return $this;
    }

    /**
     * Use the work relation Work object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\WorkQuery A secondary query class using the current class as primary query
     */
    public function useworkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinwork($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'work', '\SpoilerWiki\WorkQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Topic object
     *
     * @param \SpoilerWiki\Topic|ObjectCollection $topic the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByTopic($topic, $comparison = null)
    {
        if ($topic instanceof \SpoilerWiki\Topic) {
            return $this
                ->addUsingAlias(MilestoneTableMap::COL_ID, $topic->getIntroducedAt(), $comparison);
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
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
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
     * Filter the query by a related \SpoilerWiki\Snippet object
     *
     * @param \SpoilerWiki\Snippet|ObjectCollection $snippet the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterBySnippet($snippet, $comparison = null)
    {
        if ($snippet instanceof \SpoilerWiki\Snippet) {
            return $this
                ->addUsingAlias(MilestoneTableMap::COL_ID, $snippet->getIntroducedAt(), $comparison);
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
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
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
     * @return ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterBySummary($summary, $comparison = null)
    {
        if ($summary instanceof \SpoilerWiki\Summary) {
            return $this
                ->addUsingAlias(MilestoneTableMap::COL_ID, $summary->getIntroducedAt(), $comparison);
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
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
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
     * @param   ChildMilestone $milestone Object to remove from the list of results
     *
     * @return $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function prune($milestone = null)
    {
        if ($milestone) {
            $this->addUsingAlias(MilestoneTableMap::COL_ID, $milestone->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the milestone table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MilestoneTableMap::clearInstancePool();
            MilestoneTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MilestoneTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            MilestoneTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            MilestoneTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // sortable behavior
    
    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ChildMilestoneQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {
    
        return $this
            ->addUsingAlias(MilestoneTableMap::RANK_COL, $rank, Criteria::EQUAL);
    }
    
    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    $this|ChildMilestoneQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(MilestoneTableMap::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(MilestoneTableMap::RANK_COL));
                break;
            default:
                throw new \Propel\Runtime\Exception\PropelException('ChildMilestoneQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }
    
    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return    ChildMilestone
     */
    public function findOneByRank($rank, ConnectionInterface $con = null)
    {
    
        return $this
            ->filterByRank($rank)
            ->findOne($con);
    }
    
    /**
     * Returns the list of objects
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($con = null)
    {
    
        return $this
            ->orderByRank()
            ->find($con);
    }
    
    /**
     * Get the highest rank
     * 
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . MilestoneTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);
    
        return $stmt->fetchColumn();
    }
    
    /**
     * Get the highest rank by a scope with a array format.
     * 
     * @param     ConnectionInterface optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray(ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(MilestoneTableMap::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . MilestoneTableMap::RANK_COL . ')');
        $stmt = $this->doSelect($con);
    
        return $stmt->fetchColumn();
    }
    
    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     ConnectionInterface $con optional connection
     *
     * @return ChildMilestone
     */
    static public function retrieveByRank($rank, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
        }
    
        $c = new Criteria;
        $c->add(MilestoneTableMap::RANK_COL, $rank);
    
        return static::create(null, $c)->findOne($con);
    }
    
    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     mixed               $order id => rank pairs
     * @param     ConnectionInterface $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder($order, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
        }
    
        $con->transaction(function () use ($con, $order) {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
        });
    
        return true;
    }
    
    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     ConnectionInterface $con       optional connection
     *
     * @return    array list of sortable objects
     */
    static public function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(MilestoneTableMap::DATABASE_NAME);
        }
    
        if (null === $criteria) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }
    
        $criteria->clearOrderByColumns();
    
        if (Criteria::ASC == $order) {
            $criteria->addAscendingOrderByColumn(MilestoneTableMap::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(MilestoneTableMap::RANK_COL);
        }
    
        return ChildMilestoneQuery::create(null, $criteria)->find($con);
    }
    
    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      ConnectionInterface $con Connection to use.
     */
    static public function sortableShiftRank($delta, $first, $last = null, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MilestoneTableMap::DATABASE_NAME);
        }
    
        $whereCriteria = new Criteria(MilestoneTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(MilestoneTableMap::RANK_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(MilestoneTableMap::RANK_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
    
        $valuesCriteria = new Criteria(MilestoneTableMap::DATABASE_NAME);
        $valuesCriteria->add(MilestoneTableMap::RANK_COL, array('raw' => MilestoneTableMap::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);
    
        $whereCriteria->doUpdate($valuesCriteria, $con);
        MilestoneTableMap::clearInstancePool();
    }

} // MilestoneQuery
