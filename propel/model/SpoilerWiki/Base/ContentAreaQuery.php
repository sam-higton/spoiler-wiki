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
use SpoilerWiki\ContentArea as ChildContentArea;
use SpoilerWiki\ContentAreaQuery as ChildContentAreaQuery;
use SpoilerWiki\Map\ContentAreaTableMap;

/**
 * Base class that represents a query for the 'content_area' table.
 *
 * 
 *
 * @method     ChildContentAreaQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildContentAreaQuery orderByactiveVersion($order = Criteria::ASC) Order by the active_version column
 * @method     ChildContentAreaQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildContentAreaQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildContentAreaQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildContentAreaQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 * @method     ChildContentAreaQuery orderByVersionComment($order = Criteria::ASC) Order by the version_comment column
 *
 * @method     ChildContentAreaQuery groupByContent() Group by the content column
 * @method     ChildContentAreaQuery groupByactiveVersion() Group by the active_version column
 * @method     ChildContentAreaQuery groupById() Group by the id column
 * @method     ChildContentAreaQuery groupByVersion() Group by the version column
 * @method     ChildContentAreaQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildContentAreaQuery groupByVersionCreatedBy() Group by the version_created_by column
 * @method     ChildContentAreaQuery groupByVersionComment() Group by the version_comment column
 *
 * @method     ChildContentAreaQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildContentAreaQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildContentAreaQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildContentAreaQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildContentAreaQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildContentAreaQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildContentAreaQuery leftJoinSnippet($relationAlias = null) Adds a LEFT JOIN clause to the query using the Snippet relation
 * @method     ChildContentAreaQuery rightJoinSnippet($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Snippet relation
 * @method     ChildContentAreaQuery innerJoinSnippet($relationAlias = null) Adds a INNER JOIN clause to the query using the Snippet relation
 *
 * @method     ChildContentAreaQuery joinWithSnippet($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Snippet relation
 *
 * @method     ChildContentAreaQuery leftJoinWithSnippet() Adds a LEFT JOIN clause and with to the query using the Snippet relation
 * @method     ChildContentAreaQuery rightJoinWithSnippet() Adds a RIGHT JOIN clause and with to the query using the Snippet relation
 * @method     ChildContentAreaQuery innerJoinWithSnippet() Adds a INNER JOIN clause and with to the query using the Snippet relation
 *
 * @method     ChildContentAreaQuery leftJoinSummary($relationAlias = null) Adds a LEFT JOIN clause to the query using the Summary relation
 * @method     ChildContentAreaQuery rightJoinSummary($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Summary relation
 * @method     ChildContentAreaQuery innerJoinSummary($relationAlias = null) Adds a INNER JOIN clause to the query using the Summary relation
 *
 * @method     ChildContentAreaQuery joinWithSummary($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Summary relation
 *
 * @method     ChildContentAreaQuery leftJoinWithSummary() Adds a LEFT JOIN clause and with to the query using the Summary relation
 * @method     ChildContentAreaQuery rightJoinWithSummary() Adds a RIGHT JOIN clause and with to the query using the Summary relation
 * @method     ChildContentAreaQuery innerJoinWithSummary() Adds a INNER JOIN clause and with to the query using the Summary relation
 *
 * @method     ChildContentAreaQuery leftJoinContentAreaVersion($relationAlias = null) Adds a LEFT JOIN clause to the query using the ContentAreaVersion relation
 * @method     ChildContentAreaQuery rightJoinContentAreaVersion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ContentAreaVersion relation
 * @method     ChildContentAreaQuery innerJoinContentAreaVersion($relationAlias = null) Adds a INNER JOIN clause to the query using the ContentAreaVersion relation
 *
 * @method     ChildContentAreaQuery joinWithContentAreaVersion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ContentAreaVersion relation
 *
 * @method     ChildContentAreaQuery leftJoinWithContentAreaVersion() Adds a LEFT JOIN clause and with to the query using the ContentAreaVersion relation
 * @method     ChildContentAreaQuery rightJoinWithContentAreaVersion() Adds a RIGHT JOIN clause and with to the query using the ContentAreaVersion relation
 * @method     ChildContentAreaQuery innerJoinWithContentAreaVersion() Adds a INNER JOIN clause and with to the query using the ContentAreaVersion relation
 *
 * @method     \SpoilerWiki\SnippetQuery|\SpoilerWiki\SummaryQuery|\SpoilerWiki\ContentAreaVersionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildContentArea findOne(ConnectionInterface $con = null) Return the first ChildContentArea matching the query
 * @method     ChildContentArea findOneOrCreate(ConnectionInterface $con = null) Return the first ChildContentArea matching the query, or a new ChildContentArea object populated from the query conditions when no match is found
 *
 * @method     ChildContentArea findOneByContent(string $content) Return the first ChildContentArea filtered by the content column
 * @method     ChildContentArea findOneByactiveVersion(int $active_version) Return the first ChildContentArea filtered by the active_version column
 * @method     ChildContentArea findOneById(int $id) Return the first ChildContentArea filtered by the id column
 * @method     ChildContentArea findOneByVersion(int $version) Return the first ChildContentArea filtered by the version column
 * @method     ChildContentArea findOneByVersionCreatedAt(string $version_created_at) Return the first ChildContentArea filtered by the version_created_at column
 * @method     ChildContentArea findOneByVersionCreatedBy(string $version_created_by) Return the first ChildContentArea filtered by the version_created_by column
 * @method     ChildContentArea findOneByVersionComment(string $version_comment) Return the first ChildContentArea filtered by the version_comment column *

 * @method     ChildContentArea requirePk($key, ConnectionInterface $con = null) Return the ChildContentArea by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOne(ConnectionInterface $con = null) Return the first ChildContentArea matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContentArea requireOneByContent(string $content) Return the first ChildContentArea filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneByactiveVersion(int $active_version) Return the first ChildContentArea filtered by the active_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneById(int $id) Return the first ChildContentArea filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneByVersion(int $version) Return the first ChildContentArea filtered by the version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneByVersionCreatedAt(string $version_created_at) Return the first ChildContentArea filtered by the version_created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneByVersionCreatedBy(string $version_created_by) Return the first ChildContentArea filtered by the version_created_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContentArea requireOneByVersionComment(string $version_comment) Return the first ChildContentArea filtered by the version_comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContentArea[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildContentArea objects based on current ModelCriteria
 * @method     ChildContentArea[]|ObjectCollection findByContent(string $content) Return ChildContentArea objects filtered by the content column
 * @method     ChildContentArea[]|ObjectCollection findByactiveVersion(int $active_version) Return ChildContentArea objects filtered by the active_version column
 * @method     ChildContentArea[]|ObjectCollection findById(int $id) Return ChildContentArea objects filtered by the id column
 * @method     ChildContentArea[]|ObjectCollection findByVersion(int $version) Return ChildContentArea objects filtered by the version column
 * @method     ChildContentArea[]|ObjectCollection findByVersionCreatedAt(string $version_created_at) Return ChildContentArea objects filtered by the version_created_at column
 * @method     ChildContentArea[]|ObjectCollection findByVersionCreatedBy(string $version_created_by) Return ChildContentArea objects filtered by the version_created_by column
 * @method     ChildContentArea[]|ObjectCollection findByVersionComment(string $version_comment) Return ChildContentArea objects filtered by the version_comment column
 * @method     ChildContentArea[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ContentAreaQuery extends ModelCriteria
{
    
    // versionable behavior
    
    /**
     * Whether the versioning is enabled
     */
    static $isVersioningEnabled = true;
protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SpoilerWiki\Base\ContentAreaQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'spoilerwiki-remote', $modelName = '\\SpoilerWiki\\ContentArea', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildContentAreaQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildContentAreaQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildContentAreaQuery) {
            return $criteria;
        }
        $query = new ChildContentAreaQuery();
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
     * @return ChildContentArea|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ContentAreaTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ContentAreaTableMap::DATABASE_NAME);
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
     * @return ChildContentArea A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `content`, `active_version`, `id`, `version`, `version_created_at`, `version_created_by`, `version_comment` FROM `content_area` WHERE `id` = :p0';
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
            /** @var ChildContentArea $obj */
            $obj = new ChildContentArea();
            $obj->hydrate($row);
            ContentAreaTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildContentArea|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ContentAreaTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ContentAreaTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentAreaTableMap::COL_CONTENT, $content, $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByactiveVersion($activeVersion = null, $comparison = null)
    {
        if (is_array($activeVersion)) {
            $useMinMax = false;
            if (isset($activeVersion['min'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_ACTIVE_VERSION, $activeVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($activeVersion['max'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_ACTIVE_VERSION, $activeVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaTableMap::COL_ACTIVE_VERSION, $activeVersion, $comparison);
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
     * @see       filterBySnippet()
     *
     * @see       filterBySummary()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaTableMap::COL_VERSION, $version, $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(ContentAreaTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContentAreaTableMap::COL_VERSION_CREATED_AT, $versionCreatedAt, $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentAreaTableMap::COL_VERSION_CREATED_BY, $versionCreatedBy, $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContentAreaTableMap::COL_VERSION_COMMENT, $versionComment, $comparison);
    }

    /**
     * Filter the query by a related \SpoilerWiki\Snippet object
     *
     * @param \SpoilerWiki\Snippet|ObjectCollection $snippet The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterBySnippet($snippet, $comparison = null)
    {
        if ($snippet instanceof \SpoilerWiki\Snippet) {
            return $this
                ->addUsingAlias(ContentAreaTableMap::COL_ID, $snippet->getId(), $comparison);
        } elseif ($snippet instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContentAreaTableMap::COL_ID, $snippet->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function joinSnippet($relationAlias = null, $joinType = 'LEFT JOIN')
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
    public function useSnippetQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSnippet($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Snippet', '\SpoilerWiki\SnippetQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\Summary object
     *
     * @param \SpoilerWiki\Summary|ObjectCollection $summary The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterBySummary($summary, $comparison = null)
    {
        if ($summary instanceof \SpoilerWiki\Summary) {
            return $this
                ->addUsingAlias(ContentAreaTableMap::COL_ID, $summary->getId(), $comparison);
        } elseif ($summary instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContentAreaTableMap::COL_ID, $summary->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function joinSummary($relationAlias = null, $joinType = 'LEFT JOIN')
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
    public function useSummaryQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSummary($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Summary', '\SpoilerWiki\SummaryQuery');
    }

    /**
     * Filter the query by a related \SpoilerWiki\ContentAreaVersion object
     *
     * @param \SpoilerWiki\ContentAreaVersion|ObjectCollection $contentAreaVersion the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildContentAreaQuery The current query, for fluid interface
     */
    public function filterByContentAreaVersion($contentAreaVersion, $comparison = null)
    {
        if ($contentAreaVersion instanceof \SpoilerWiki\ContentAreaVersion) {
            return $this
                ->addUsingAlias(ContentAreaTableMap::COL_ID, $contentAreaVersion->getId(), $comparison);
        } elseif ($contentAreaVersion instanceof ObjectCollection) {
            return $this
                ->useContentAreaVersionQuery()
                ->filterByPrimaryKeys($contentAreaVersion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContentAreaVersion() only accepts arguments of type \SpoilerWiki\ContentAreaVersion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ContentAreaVersion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function joinContentAreaVersion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ContentAreaVersion');

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
            $this->addJoinObject($join, 'ContentAreaVersion');
        }

        return $this;
    }

    /**
     * Use the ContentAreaVersion relation ContentAreaVersion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SpoilerWiki\ContentAreaVersionQuery A secondary query class using the current class as primary query
     */
    public function useContentAreaVersionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContentAreaVersion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ContentAreaVersion', '\SpoilerWiki\ContentAreaVersionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildContentArea $contentArea Object to remove from the list of results
     *
     * @return $this|ChildContentAreaQuery The current query, for fluid interface
     */
    public function prune($contentArea = null)
    {
        if ($contentArea) {
            $this->addUsingAlias(ContentAreaTableMap::COL_ID, $contentArea->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the content_area table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContentAreaTableMap::clearInstancePool();
            ContentAreaTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ContentAreaTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ContentAreaTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            ContentAreaTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            ContentAreaTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // versionable behavior
    
    /**
     * Checks whether versioning is enabled
     *
     * @return boolean
     */
    static public function isVersioningEnabled()
    {
        return self::$isVersioningEnabled;
    }
    
    /**
     * Enables versioning
     */
    static public function enableVersioning()
    {
        self::$isVersioningEnabled = true;
    }
    
    /**
     * Disables versioning
     */
    static public function disableVersioning()
    {
        self::$isVersioningEnabled = false;
    }

} // ContentAreaQuery
