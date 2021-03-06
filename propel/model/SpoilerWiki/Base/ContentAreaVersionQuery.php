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
use SpoilerWiki\ContentAreaVersion as ChildContentAreaVersion;
use SpoilerWiki\ContentAreaVersionQuery as ChildContentAreaVersionQuery;
use SpoilerWiki\Map\ContentAreaVersionTableMap;

/**
 * Base class that represents a query for the 'content_area_version' table.
 *
 * 
 *
 * @method     ChildContentAreaVersionQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildContentAreaVersionQuery orderByactiveVersion($order = Criteria::ASC) Order by the active_version column
 * @method     ChildContentAreaVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildContentAreaVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildContentAreaVersionQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildContentAreaVersionQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 * @method     ChildContentAreaVersionQuery orderByVersionComment($order = Criteria::ASC) Order by the version_comment column
 *
 * @method     ChildContentAreaVersionQuery groupByContent() Group by the content column
 * @method     ChildContentAreaVersionQuery groupByactiveVersion() Group by the active_version column
 * @method     ChildContentAreaVersionQuery groupById() Group by the id column
 * @method     ChildContentAreaVersionQuery groupByVersion() Group by the version column
 * @method     ChildContentAreaVersionQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildContentAreaVersionQuery groupByVersionCreatedBy() Group by the version_created_by column
 * @method     ChildContentAreaVersionQuery groupByVersionComment() Group by the version_comment column
 *
 * @method     ChildContentAreaVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildContentAreaVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildContentAreaVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildContentAreaVersionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildContentAreaVersionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildContentAreaVersionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildContentAreaVersionQuery leftJoinContentArea($relationAlias = null) Adds a LEFT JOIN clause to the query using the ContentArea relation
 * @method     ChildContentAreaVersionQuery rightJoinContentArea($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ContentArea relation
 * @method     ChildContentAreaVersionQuery innerJoinContentArea($relationAlias = null) Adds a INNER JOIN clause to the query using the ContentArea relation
 *
 * @method     ChildContentAreaVersionQuery joinWithContentArea($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ContentArea relation
 *
 * @method     ChildContentAreaVersionQuery leftJoinWithContentArea() Adds a LEFT JOIN clause and with to the query using the ContentArea relation
 * @method     ChildContentAreaVersionQuery rightJoinWithContentArea() Adds a RIGHT JOIN clause and with to the query using the ContentArea relation
 * @method     ChildContentAreaVersionQuery innerJoinWithContentArea() Adds a INNER JOIN clause and with to the query using the ContentArea relation
 *
 * @method     \SpoilerWiki\ContentAreaQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildContentAreaVersion findOne(ConnectionInterface $con = null) Return the first ChildContentAreaVersion matching the query
 * @method     ChildContentAreaVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildContentAreaVersion matching the query, or a new ChildContentAreaVersion object populated from the query conditions when no match is found
 *
 * @method     ChildContentAreaVersion findOneByContent(string $content) Return the first ChildContentAreaVersion filtered by the content column
 * @method     ChildContentAreaVersion findOneByactiveVersion(int $active_version) Return the first ChildContentAreaVersion filtered by the active_version column
 * @method     ChildContentAreaVersion findOneById(int $id) Return the first ChildContentAreaVersion filtered by the id column
 * @method     ChildContentAreaVersion findOneByVersion(int $version) Return the first ChildContentAreaVersion filtered by the version column
 * @method     ChildContentAreaVersion findOneByVersionCreatedAt(string $version_created_at) Return the first ChildContentAreaVersion filtered by the version_created_at column
 * @method     ChildContentAreaVersion findOneByVersionCreatedBy(string $version_created_by) Return the first ChildContentAreaVersion filtered by the version_created_by column
 * @method     ChildContentAreaVersion findOneByVersionComment(string $version_comment) Return the first ChildContentAreaVersion filtered by the version_comment column *

 * @method     ChildContentAreaVersion requirePk($key, ConnectionInterface $con = null) Return the ChildContentAreaVersion by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOne(ConnectionInterface $con = null) Return the first ChildContentAreaVersion matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContentAreaVersion requireOneByContent(string $content) Return the first ChildContentAreaVersion filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneByactiveVersion(int $active_version) Return the first ChildContentAreaVersion filtered by the active_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneById(int $id) Return the first ChildContentAreaVersion filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneByVersion(int $version) Return the first ChildContentAreaVersion filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneByVersionCreatedAt(string $version_created_at) Return the first ChildContentAreaVersion filtered by the version_created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneByVersionCreatedBy(string $version_created_by) Return the first ChildContentAreaVersion filtered by the version_created_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentAreaVersion requireOneByVersionComment(string $version_comment) Return the first ChildContentAreaVersion filtered by the version_comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContentAreaVersion[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildContentAreaVersion objects based on current ModelCriteria
 * @method     ChildContentAreaVersion[]|ObjectCollection findByContent(string $content) Return ChildContentAreaVersion objects filtered by the content column
 * @method     ChildContentAreaVersion[]|ObjectCollection findByactiveVersion(int $active_version) Return ChildContentAreaVersion objects filtered by the active_version column
 * @method     ChildContentAreaVersion[]|ObjectCollection findById(int $id) Return ChildContentAreaVersion objects filtered by the id column
 * @method     ChildContentAreaVersion[]|ObjectCollection findByVersion(int $version) Return ChildContentAreaVersion objects filtered by the version column
 * @method     ChildContentAreaVersion[]|ObjectCollection findByVersionCreatedAt(string $version_created_at) Return ChildContentAreaVersion objects filtered by the version_created_at column
 * @method     ChildContentAreaVersion[]|ObjectCollection findByVersionCreatedBy(string $version_created_by) Return ChildContentAreaVersion objects filtered by the version_created_by column
 * @method     ChildContentAreaVersion[]|ObjectCollection findByVersionComment(string $version_comment) Return ChildContentAreaVersion objects filtered by the version_comment column
 * @method     ChildContentAreaVersion[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ContentAreaVersionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\ContentAreaVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\ContentAreaVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildContentAreaVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildContentAreaVersionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildContentAreaVersionQuery) {
            return $criteria;
        }
        $query = new ChildContentAreaVersionQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id, $version] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildContentAreaVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ContentAreaVersionTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ContentAreaVersionTableMap::DATABASE_NAME);
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
     * @return ChildContentAreaVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `content`, `active_version`, `id`, `version`, `version_created_at`, `version_created_by`, `version_comment` FROM `content_area_version` WHERE `id` = :p0 AND `version` = :p1';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);            
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildContentAreaVersion $obj */
            $obj = new ChildContentAreaVersion();
            $obj->hydrate($row);
            ContentAreaVersionTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildContentAreaVersion|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ContentAreaVersionTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ContentAreaVersionTableMap::COL_VERSION, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the active_version column
     *
     * Example usage:
     * <code>
     * $query->filterByactiveVersion(1234); // WHERE active_version = 1234
     * $query->filterByactiveVersion(array(12, 34)); // WHERE active_version IN (12, 34)
     * $query->filterByactiveVersion(array('min' => 12)); // WHERE active_version > 12
     * </code>
     *
     * @param     mixed $activeVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByactiveVersion($activeVersion = null, $comparison = null)
    {
        if (is_array($activeVersion)) {
            $useMinMax = false;
            if (isset($activeVersion['min'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_ACTIVE_VERSION, $activeVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activeVersion['max'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_ACTIVE_VERSION, $activeVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_ACTIVE_VERSION, $activeVersion, $comparison);
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
     * @see       filterByContentArea()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version > 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the version_created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedAt('2011-03-14'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt('now'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt(array('max' => 'yesterday')); // WHERE version_created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $versionCreatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt, $comparison);
    }

    /**
     * Filter the query on the version_created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedBy('fooValue');   // WHERE version_created_by = 'fooValue'
     * $query->filterByVersionCreatedBy('%fooValue%'); // WHERE version_created_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $versionCreatedBy The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedBy($versionCreatedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($versionCreatedBy)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $versionCreatedBy)) {
                $versionCreatedBy = str_replace('*', '%', $versionCreatedBy);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION_CREATED_BY, $versionCreatedBy, $comparison);
    }

    /**
     * Filter the query on the version_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionComment('fooValue');   // WHERE version_comment = 'fooValue'
     * $query->filterByVersionComment('%fooValue%'); // WHERE version_comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $versionComment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByVersionComment($versionComment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($versionComment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $versionComment)) {
                $versionComment = str_replace('*', '%', $versionComment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContentAreaVersionTableMap::COL_VERSION_COMMENT, $versionComment, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\ContentArea object
     *
     * @param \SpoilerWiki\ContentArea|ObjectCollection $contentArea The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function filterByContentArea($contentArea, $comparison = null)
    {
        if ($contentArea instanceof \SpoilerWiki\ContentArea) {
            return $this
                ->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $contentArea->getId(), $comparison);
        } elseif ($contentArea instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContentAreaVersionTableMap::COL_ID, $contentArea->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByContentArea() only accepts arguments of type \SpoilerWiki\ContentArea or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ContentArea relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function joinContentArea($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ContentArea');

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
            $this->addJoinObject($join, 'ContentArea');
        }

        return $this;
    }

    /**
     * Use the ContentArea relation ContentArea object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\ContentAreaQuery A secondary query class using the current class as primary query
     */
    public function useContentAreaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContentArea($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ContentArea', '\SpoilerWiki\ContentAreaQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildContentAreaVersion $contentAreaVersion Object to remove from the list of results
     *
     * @return $this|ChildContentAreaVersionQuery The current query, for fluid interface
     */
    public function prune($contentAreaVersion = null)
    {
        if ($contentAreaVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ContentAreaVersionTableMap::COL_ID), $contentAreaVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ContentAreaVersionTableMap::COL_VERSION), $contentAreaVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the content_area_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaVersionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContentAreaVersionTableMap::clearInstancePool();
            ContentAreaVersionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ContentAreaVersionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            ContentAreaVersionTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            ContentAreaVersionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ContentAreaVersionQuery
