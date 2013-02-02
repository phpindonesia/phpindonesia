<?php

namespace app\Models\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use app\Models\User;
use app\Models\UserPeer;
use app\Models\UserQuery;

/**
 * Base class that represents a query for the 'phpid_users' table.
 *
 * User Table
 *
 * @method UserQuery orderByUid($order = Criteria::ASC) Order by the uid column
 * @method UserQuery orderByNames($order = Criteria::ASC) Order by the names column
 * @method UserQuery orderByPass($order = Criteria::ASC) Order by the pass column
 * @method UserQuery orderByMail($order = Criteria::ASC) Order by the mail column
 * @method UserQuery orderByTheme($order = Criteria::ASC) Order by the theme column
 * @method UserQuery orderBySignature($order = Criteria::ASC) Order by the signature column
 * @method UserQuery orderBySignatureFormat($order = Criteria::ASC) Order by the signature_format column
 * @method UserQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method UserQuery orderByAccess($order = Criteria::ASC) Order by the access column
 * @method UserQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method UserQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method UserQuery orderByTimezone($order = Criteria::ASC) Order by the timezone column
 * @method UserQuery orderByLanguage($order = Criteria::ASC) Order by the language column
 * @method UserQuery orderByPicture($order = Criteria::ASC) Order by the picture column
 * @method UserQuery orderByInit($order = Criteria::ASC) Order by the init column
 * @method UserQuery orderByData($order = Criteria::ASC) Order by the data column
 *
 * @method UserQuery groupByUid() Group by the uid column
 * @method UserQuery groupByNames() Group by the names column
 * @method UserQuery groupByPass() Group by the pass column
 * @method UserQuery groupByMail() Group by the mail column
 * @method UserQuery groupByTheme() Group by the theme column
 * @method UserQuery groupBySignature() Group by the signature column
 * @method UserQuery groupBySignatureFormat() Group by the signature_format column
 * @method UserQuery groupByCreated() Group by the created column
 * @method UserQuery groupByAccess() Group by the access column
 * @method UserQuery groupByLogin() Group by the login column
 * @method UserQuery groupByStatus() Group by the status column
 * @method UserQuery groupByTimezone() Group by the timezone column
 * @method UserQuery groupByLanguage() Group by the language column
 * @method UserQuery groupByPicture() Group by the picture column
 * @method UserQuery groupByInit() Group by the init column
 * @method UserQuery groupByData() Group by the data column
 *
 * @method UserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method User findOne(PropelPDO $con = null) Return the first User matching the query
 * @method User findOneOrCreate(PropelPDO $con = null) Return the first User matching the query, or a new User object populated from the query conditions when no match is found
 *
 * @method User findOneByUid(int $uid) Return the first User filtered by the uid column
 * @method User findOneByNames(string $names) Return the first User filtered by the names column
 * @method User findOneByPass(string $pass) Return the first User filtered by the pass column
 * @method User findOneByMail(string $mail) Return the first User filtered by the mail column
 * @method User findOneByTheme(string $theme) Return the first User filtered by the theme column
 * @method User findOneBySignature(string $signature) Return the first User filtered by the signature column
 * @method User findOneBySignatureFormat(string $signature_format) Return the first User filtered by the signature_format column
 * @method User findOneByCreated(int $created) Return the first User filtered by the created column
 * @method User findOneByAccess(int $access) Return the first User filtered by the access column
 * @method User findOneByLogin(int $login) Return the first User filtered by the login column
 * @method User findOneByStatus(int $status) Return the first User filtered by the status column
 * @method User findOneByTimezone(string $timezone) Return the first User filtered by the timezone column
 * @method User findOneByLanguage(string $language) Return the first User filtered by the language column
 * @method User findOneByPicture(int $picture) Return the first User filtered by the picture column
 * @method User findOneByInit(string $init) Return the first User filtered by the init column
 * @method User findOneByData(string $data) Return the first User filtered by the data column
 *
 * @method array findByUid(int $uid) Return User objects filtered by the uid column
 * @method array findByNames(string $names) Return User objects filtered by the names column
 * @method array findByPass(string $pass) Return User objects filtered by the pass column
 * @method array findByMail(string $mail) Return User objects filtered by the mail column
 * @method array findByTheme(string $theme) Return User objects filtered by the theme column
 * @method array findBySignature(string $signature) Return User objects filtered by the signature column
 * @method array findBySignatureFormat(string $signature_format) Return User objects filtered by the signature_format column
 * @method array findByCreated(int $created) Return User objects filtered by the created column
 * @method array findByAccess(int $access) Return User objects filtered by the access column
 * @method array findByLogin(int $login) Return User objects filtered by the login column
 * @method array findByStatus(int $status) Return User objects filtered by the status column
 * @method array findByTimezone(string $timezone) Return User objects filtered by the timezone column
 * @method array findByLanguage(string $language) Return User objects filtered by the language column
 * @method array findByPicture(int $picture) Return User objects filtered by the picture column
 * @method array findByInit(string $init) Return User objects filtered by the init column
 * @method array findByData(string $data) Return User objects filtered by the data column
 *
 * @package    propel.generator.app.Models.om
 */
abstract class BaseUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'dev_phpindonesia', $modelName = 'app\\Models\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuery) {
            return $criteria;
        }
        $query = new UserQuery();
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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   User|User[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @param     PropelPDO $con A connection object
     *
     * @return   User A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `UID`, `NAMES`, `PASS`, `MAIL`, `THEME`, `SIGNATURE`, `SIGNATURE_FORMAT`, `CREATED`, `ACCESS`, `LOGIN`, `STATUS`, `TIMEZONE`, `LANGUAGE`, `PICTURE`, `INIT`, `DATA` FROM `phpid_users` WHERE `UID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new User();
            $obj->hydrate($row);
            UserPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return User|User[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|User[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserPeer::UID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserPeer::UID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the uid column
     *
     * Example usage:
     * <code>
     * $query->filterByUid(1234); // WHERE uid = 1234
     * $query->filterByUid(array(12, 34)); // WHERE uid IN (12, 34)
     * $query->filterByUid(array('min' => 12)); // WHERE uid > 12
     * </code>
     *
     * @param     mixed $uid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByUid($uid = null, $comparison = null)
    {
        if (is_array($uid) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserPeer::UID, $uid, $comparison);
    }

    /**
     * Filter the query on the names column
     *
     * Example usage:
     * <code>
     * $query->filterByNames('fooValue');   // WHERE names = 'fooValue'
     * $query->filterByNames('%fooValue%'); // WHERE names LIKE '%fooValue%'
     * </code>
     *
     * @param     string $names The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByNames($names = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($names)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $names)) {
                $names = str_replace('*', '%', $names);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::NAMES, $names, $comparison);
    }

    /**
     * Filter the query on the pass column
     *
     * Example usage:
     * <code>
     * $query->filterByPass('fooValue');   // WHERE pass = 'fooValue'
     * $query->filterByPass('%fooValue%'); // WHERE pass LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pass The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPass($pass = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pass)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pass)) {
                $pass = str_replace('*', '%', $pass);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::PASS, $pass, $comparison);
    }

    /**
     * Filter the query on the mail column
     *
     * Example usage:
     * <code>
     * $query->filterByMail('fooValue');   // WHERE mail = 'fooValue'
     * $query->filterByMail('%fooValue%'); // WHERE mail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByMail($mail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mail)) {
                $mail = str_replace('*', '%', $mail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::MAIL, $mail, $comparison);
    }

    /**
     * Filter the query on the theme column
     *
     * Example usage:
     * <code>
     * $query->filterByTheme('fooValue');   // WHERE theme = 'fooValue'
     * $query->filterByTheme('%fooValue%'); // WHERE theme LIKE '%fooValue%'
     * </code>
     *
     * @param     string $theme The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByTheme($theme = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($theme)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $theme)) {
                $theme = str_replace('*', '%', $theme);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::THEME, $theme, $comparison);
    }

    /**
     * Filter the query on the signature column
     *
     * Example usage:
     * <code>
     * $query->filterBySignature('fooValue');   // WHERE signature = 'fooValue'
     * $query->filterBySignature('%fooValue%'); // WHERE signature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $signature The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterBySignature($signature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($signature)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $signature)) {
                $signature = str_replace('*', '%', $signature);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::SIGNATURE, $signature, $comparison);
    }

    /**
     * Filter the query on the signature_format column
     *
     * Example usage:
     * <code>
     * $query->filterBySignatureFormat('fooValue');   // WHERE signature_format = 'fooValue'
     * $query->filterBySignatureFormat('%fooValue%'); // WHERE signature_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $signatureFormat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterBySignatureFormat($signatureFormat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($signatureFormat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $signatureFormat)) {
                $signatureFormat = str_replace('*', '%', $signatureFormat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::SIGNATURE_FORMAT, $signatureFormat, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated(1234); // WHERE created = 1234
     * $query->filterByCreated(array(12, 34)); // WHERE created IN (12, 34)
     * $query->filterByCreated(array('min' => 12)); // WHERE created > 12
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(UserPeer::CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(UserPeer::CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the access column
     *
     * Example usage:
     * <code>
     * $query->filterByAccess(1234); // WHERE access = 1234
     * $query->filterByAccess(array(12, 34)); // WHERE access IN (12, 34)
     * $query->filterByAccess(array('min' => 12)); // WHERE access > 12
     * </code>
     *
     * @param     mixed $access The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByAccess($access = null, $comparison = null)
    {
        if (is_array($access)) {
            $useMinMax = false;
            if (isset($access['min'])) {
                $this->addUsingAlias(UserPeer::ACCESS, $access['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($access['max'])) {
                $this->addUsingAlias(UserPeer::ACCESS, $access['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::ACCESS, $access, $comparison);
    }

    /**
     * Filter the query on the login column
     *
     * Example usage:
     * <code>
     * $query->filterByLogin(1234); // WHERE login = 1234
     * $query->filterByLogin(array(12, 34)); // WHERE login IN (12, 34)
     * $query->filterByLogin(array('min' => 12)); // WHERE login > 12
     * </code>
     *
     * @param     mixed $login The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLogin($login = null, $comparison = null)
    {
        if (is_array($login)) {
            $useMinMax = false;
            if (isset($login['min'])) {
                $this->addUsingAlias(UserPeer::LOGIN, $login['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($login['max'])) {
                $this->addUsingAlias(UserPeer::LOGIN, $login['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::LOGIN, $login, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(UserPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(UserPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the timezone column
     *
     * Example usage:
     * <code>
     * $query->filterByTimezone('fooValue');   // WHERE timezone = 'fooValue'
     * $query->filterByTimezone('%fooValue%'); // WHERE timezone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $timezone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByTimezone($timezone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($timezone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $timezone)) {
                $timezone = str_replace('*', '%', $timezone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::TIMEZONE, $timezone, $comparison);
    }

    /**
     * Filter the query on the language column
     *
     * Example usage:
     * <code>
     * $query->filterByLanguage('fooValue');   // WHERE language = 'fooValue'
     * $query->filterByLanguage('%fooValue%'); // WHERE language LIKE '%fooValue%'
     * </code>
     *
     * @param     string $language The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLanguage($language = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($language)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $language)) {
                $language = str_replace('*', '%', $language);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::LANGUAGE, $language, $comparison);
    }

    /**
     * Filter the query on the picture column
     *
     * Example usage:
     * <code>
     * $query->filterByPicture(1234); // WHERE picture = 1234
     * $query->filterByPicture(array(12, 34)); // WHERE picture IN (12, 34)
     * $query->filterByPicture(array('min' => 12)); // WHERE picture > 12
     * </code>
     *
     * @param     mixed $picture The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPicture($picture = null, $comparison = null)
    {
        if (is_array($picture)) {
            $useMinMax = false;
            if (isset($picture['min'])) {
                $this->addUsingAlias(UserPeer::PICTURE, $picture['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($picture['max'])) {
                $this->addUsingAlias(UserPeer::PICTURE, $picture['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::PICTURE, $picture, $comparison);
    }

    /**
     * Filter the query on the init column
     *
     * Example usage:
     * <code>
     * $query->filterByInit('fooValue');   // WHERE init = 'fooValue'
     * $query->filterByInit('%fooValue%'); // WHERE init LIKE '%fooValue%'
     * </code>
     *
     * @param     string $init The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByInit($init = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($init)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $init)) {
                $init = str_replace('*', '%', $init);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::INIT, $init, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * @param     mixed $data The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {

        return $this->addUsingAlias(UserPeer::DATA, $data, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   User $user Object to remove from the list of results
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserPeer::UID, $user->getUid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
