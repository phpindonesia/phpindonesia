<?php

namespace app\Models\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use app\Models\User;
use app\Models\UserPeer;
use app\Models\UserQuery;

/**
 * Base class that represents a row from the 'phpid_users' table.
 *
 * User Table
 *
 * @package    propel.generator.app.Models.om
 */
abstract class BaseUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'app\\Models\\UserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the uid field.
     * @var        int
     */
    protected $uid;

    /**
     * The value for the names field.
     * @var        string
     */
    protected $names;

    /**
     * The value for the pass field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $pass;

    /**
     * The value for the mail field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $mail;

    /**
     * The value for the theme field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $theme;

    /**
     * The value for the signature field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $signature;

    /**
     * The value for the signature_format field.
     * @var        string
     */
    protected $signature_format;

    /**
     * The value for the created field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $created;

    /**
     * The value for the access field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $access;

    /**
     * The value for the login field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $login;

    /**
     * The value for the status field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $status;

    /**
     * The value for the timezone field.
     * @var        string
     */
    protected $timezone;

    /**
     * The value for the language field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $language;

    /**
     * The value for the picture field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $picture;

    /**
     * The value for the init field.
     * Note: this column has a database default value of: ''
     * @var        string
     */
    protected $init;

    /**
     * The value for the data field.
     * @var        string
     */
    protected $data;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->pass = '';
        $this->mail = '';
        $this->theme = '';
        $this->signature = '';
        $this->created = 0;
        $this->access = 0;
        $this->login = 0;
        $this->status = 0;
        $this->language = '';
        $this->picture = 0;
        $this->init = '';
    }

    /**
     * Initializes internal state of BaseUser object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [uid] column value.
     * Unique user ID.
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Get the [names] column value.
     * Unique user name.
     * @return string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Get the [pass] column value.
     * User’s password (hashed).
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Get the [mail] column value.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get the [theme] column value.
     * User’s default theme.
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Get the [signature] column value.
     * User’s signature.
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Get the [signature_format] column value.
     * The phpid_filter_format.format of the signature.
     * @return string
     */
    public function getSignatureFormat()
    {
        return $this->signature_format;
    }

    /**
     * Get the [created] column value.
     * Timestamp for when user was created.
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get the [access] column value.
     * Timestamp for previous time user accessed the site.
     * @return int
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Get the [login] column value.
     * Timestamp for user’s last login.
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Get the [status] column value.
     * Whether the user is active(1) or blocked(0).
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [timezone] column value.
     * User’s time zone.
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Get the [language] column value.
     * User’s default language.
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get the [picture] column value.
     * Foreign key: phpid_file_managed.fid of user’s picture.
     * @return int
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Get the [init] column value.
     * E-mail address used for initial account creation.
     * @return string
     */
    public function getInit()
    {
        return $this->init;
    }

    /**
     * Get the [data] column value.
     * A serialized array of name value pairs that are related to the user. Any form values posted during user edit are stored and are loaded into the $user object during user_load(). Use of this field is discouraged and it will likely disappear in a future...
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of [uid] column.
     * Unique user ID.
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setUid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->uid !== $v) {
            $this->uid = $v;
            $this->modifiedColumns[] = UserPeer::UID;
        }


        return $this;
    } // setUid()

    /**
     * Set the value of [names] column.
     * Unique user name.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setNames($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->names !== $v) {
            $this->names = $v;
            $this->modifiedColumns[] = UserPeer::NAMES;
        }


        return $this;
    } // setNames()

    /**
     * Set the value of [pass] column.
     * User’s password (hashed).
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPass($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->pass !== $v) {
            $this->pass = $v;
            $this->modifiedColumns[] = UserPeer::PASS;
        }


        return $this;
    } // setPass()

    /**
     * Set the value of [mail] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mail !== $v) {
            $this->mail = $v;
            $this->modifiedColumns[] = UserPeer::MAIL;
        }


        return $this;
    } // setMail()

    /**
     * Set the value of [theme] column.
     * User’s default theme.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setTheme($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->theme !== $v) {
            $this->theme = $v;
            $this->modifiedColumns[] = UserPeer::THEME;
        }


        return $this;
    } // setTheme()

    /**
     * Set the value of [signature] column.
     * User’s signature.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setSignature($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->signature !== $v) {
            $this->signature = $v;
            $this->modifiedColumns[] = UserPeer::SIGNATURE;
        }


        return $this;
    } // setSignature()

    /**
     * Set the value of [signature_format] column.
     * The phpid_filter_format.format of the signature.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setSignatureFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->signature_format !== $v) {
            $this->signature_format = $v;
            $this->modifiedColumns[] = UserPeer::SIGNATURE_FORMAT;
        }


        return $this;
    } // setSignatureFormat()

    /**
     * Set the value of [created] column.
     * Timestamp for when user was created.
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->created !== $v) {
            $this->created = $v;
            $this->modifiedColumns[] = UserPeer::CREATED;
        }


        return $this;
    } // setCreated()

    /**
     * Set the value of [access] column.
     * Timestamp for previous time user accessed the site.
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setAccess($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->access !== $v) {
            $this->access = $v;
            $this->modifiedColumns[] = UserPeer::ACCESS;
        }


        return $this;
    } // setAccess()

    /**
     * Set the value of [login] column.
     * Timestamp for user’s last login.
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLogin($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->login !== $v) {
            $this->login = $v;
            $this->modifiedColumns[] = UserPeer::LOGIN;
        }


        return $this;
    } // setLogin()

    /**
     * Set the value of [status] column.
     * Whether the user is active(1) or blocked(0).
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = UserPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [timezone] column.
     * User’s time zone.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setTimezone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->timezone !== $v) {
            $this->timezone = $v;
            $this->modifiedColumns[] = UserPeer::TIMEZONE;
        }


        return $this;
    } // setTimezone()

    /**
     * Set the value of [language] column.
     * User’s default language.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLanguage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->language !== $v) {
            $this->language = $v;
            $this->modifiedColumns[] = UserPeer::LANGUAGE;
        }


        return $this;
    } // setLanguage()

    /**
     * Set the value of [picture] column.
     * Foreign key: phpid_file_managed.fid of user’s picture.
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPicture($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->picture !== $v) {
            $this->picture = $v;
            $this->modifiedColumns[] = UserPeer::PICTURE;
        }


        return $this;
    } // setPicture()

    /**
     * Set the value of [init] column.
     * E-mail address used for initial account creation.
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setInit($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->init !== $v) {
            $this->init = $v;
            $this->modifiedColumns[] = UserPeer::INIT;
        }


        return $this;
    } // setInit()

    /**
     * Set the value of [data] column.
     * A serialized array of name value pairs that are related to the user. Any form values posted during user edit are stored and are loaded into the $user object during user_load(). Use of this field is discouraged and it will likely disappear in a future...
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setData($v)
    {
        // Because BLOB columns are streams in PDO we have to assume that they are
        // always modified when a new value is passed in.  For example, the contents
        // of the stream itself may have changed externally.
        if (!is_resource($v) && $v !== null) {
            $this->data = fopen('php://memory', 'r+');
            fwrite($this->data, $v);
            rewind($this->data);
        } else { // it's already a stream
            $this->data = $v;
        }
        $this->modifiedColumns[] = UserPeer::DATA;


        return $this;
    } // setData()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->pass !== '') {
                return false;
            }

            if ($this->mail !== '') {
                return false;
            }

            if ($this->theme !== '') {
                return false;
            }

            if ($this->signature !== '') {
                return false;
            }

            if ($this->created !== 0) {
                return false;
            }

            if ($this->access !== 0) {
                return false;
            }

            if ($this->login !== 0) {
                return false;
            }

            if ($this->status !== 0) {
                return false;
            }

            if ($this->language !== '') {
                return false;
            }

            if ($this->picture !== 0) {
                return false;
            }

            if ($this->init !== '') {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->uid = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->names = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->pass = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->mail = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->theme = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->signature = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->signature_format = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->created = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->access = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->login = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->status = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->timezone = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->language = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->picture = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->init = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            if ($row[$startcol + 15] !== null) {
                $this->data = fopen('php://memory', 'r+');
                fwrite($this->data, $row[$startcol + 15]);
                rewind($this->data);
            } else {
                $this->data = null;
            }
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = UserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating User object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                // Rewind the data LOB column, since PDO does not rewind after inserting value.
                if ($this->data !== null && is_resource($this->data)) {
                    rewind($this->data);
                }

                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = UserPeer::UID;
        if (null !== $this->uid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::UID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserPeer::UID)) {
            $modifiedColumns[':p' . $index++]  = '`UID`';
        }
        if ($this->isColumnModified(UserPeer::NAMES)) {
            $modifiedColumns[':p' . $index++]  = '`NAMES`';
        }
        if ($this->isColumnModified(UserPeer::PASS)) {
            $modifiedColumns[':p' . $index++]  = '`PASS`';
        }
        if ($this->isColumnModified(UserPeer::MAIL)) {
            $modifiedColumns[':p' . $index++]  = '`MAIL`';
        }
        if ($this->isColumnModified(UserPeer::THEME)) {
            $modifiedColumns[':p' . $index++]  = '`THEME`';
        }
        if ($this->isColumnModified(UserPeer::SIGNATURE)) {
            $modifiedColumns[':p' . $index++]  = '`SIGNATURE`';
        }
        if ($this->isColumnModified(UserPeer::SIGNATURE_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = '`SIGNATURE_FORMAT`';
        }
        if ($this->isColumnModified(UserPeer::CREATED)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED`';
        }
        if ($this->isColumnModified(UserPeer::ACCESS)) {
            $modifiedColumns[':p' . $index++]  = '`ACCESS`';
        }
        if ($this->isColumnModified(UserPeer::LOGIN)) {
            $modifiedColumns[':p' . $index++]  = '`LOGIN`';
        }
        if ($this->isColumnModified(UserPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`STATUS`';
        }
        if ($this->isColumnModified(UserPeer::TIMEZONE)) {
            $modifiedColumns[':p' . $index++]  = '`TIMEZONE`';
        }
        if ($this->isColumnModified(UserPeer::LANGUAGE)) {
            $modifiedColumns[':p' . $index++]  = '`LANGUAGE`';
        }
        if ($this->isColumnModified(UserPeer::PICTURE)) {
            $modifiedColumns[':p' . $index++]  = '`PICTURE`';
        }
        if ($this->isColumnModified(UserPeer::INIT)) {
            $modifiedColumns[':p' . $index++]  = '`INIT`';
        }
        if ($this->isColumnModified(UserPeer::DATA)) {
            $modifiedColumns[':p' . $index++]  = '`DATA`';
        }

        $sql = sprintf(
            'INSERT INTO `phpid_users` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`UID`':
                        $stmt->bindValue($identifier, $this->uid, PDO::PARAM_INT);
                        break;
                    case '`NAMES`':
                        $stmt->bindValue($identifier, $this->names, PDO::PARAM_STR);
                        break;
                    case '`PASS`':
                        $stmt->bindValue($identifier, $this->pass, PDO::PARAM_STR);
                        break;
                    case '`MAIL`':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '`THEME`':
                        $stmt->bindValue($identifier, $this->theme, PDO::PARAM_STR);
                        break;
                    case '`SIGNATURE`':
                        $stmt->bindValue($identifier, $this->signature, PDO::PARAM_STR);
                        break;
                    case '`SIGNATURE_FORMAT`':
                        $stmt->bindValue($identifier, $this->signature_format, PDO::PARAM_STR);
                        break;
                    case '`CREATED`':
                        $stmt->bindValue($identifier, $this->created, PDO::PARAM_INT);
                        break;
                    case '`ACCESS`':
                        $stmt->bindValue($identifier, $this->access, PDO::PARAM_INT);
                        break;
                    case '`LOGIN`':
                        $stmt->bindValue($identifier, $this->login, PDO::PARAM_INT);
                        break;
                    case '`STATUS`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`TIMEZONE`':
                        $stmt->bindValue($identifier, $this->timezone, PDO::PARAM_STR);
                        break;
                    case '`LANGUAGE`':
                        $stmt->bindValue($identifier, $this->language, PDO::PARAM_STR);
                        break;
                    case '`PICTURE`':
                        $stmt->bindValue($identifier, $this->picture, PDO::PARAM_INT);
                        break;
                    case '`INIT`':
                        $stmt->bindValue($identifier, $this->init, PDO::PARAM_STR);
                        break;
                    case '`DATA`':
                        if (is_resource($this->data)) {
                            rewind($this->data);
                        }
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_LOB);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setUid($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        } else {
            $this->validationFailures = $res;

            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getUid();
                break;
            case 1:
                return $this->getNames();
                break;
            case 2:
                return $this->getPass();
                break;
            case 3:
                return $this->getMail();
                break;
            case 4:
                return $this->getTheme();
                break;
            case 5:
                return $this->getSignature();
                break;
            case 6:
                return $this->getSignatureFormat();
                break;
            case 7:
                return $this->getCreated();
                break;
            case 8:
                return $this->getAccess();
                break;
            case 9:
                return $this->getLogin();
                break;
            case 10:
                return $this->getStatus();
                break;
            case 11:
                return $this->getTimezone();
                break;
            case 12:
                return $this->getLanguage();
                break;
            case 13:
                return $this->getPicture();
                break;
            case 14:
                return $this->getInit();
                break;
            case 15:
                return $this->getData();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUid(),
            $keys[1] => $this->getNames(),
            $keys[2] => $this->getPass(),
            $keys[3] => $this->getMail(),
            $keys[4] => $this->getTheme(),
            $keys[5] => $this->getSignature(),
            $keys[6] => $this->getSignatureFormat(),
            $keys[7] => $this->getCreated(),
            $keys[8] => $this->getAccess(),
            $keys[9] => $this->getLogin(),
            $keys[10] => $this->getStatus(),
            $keys[11] => $this->getTimezone(),
            $keys[12] => $this->getLanguage(),
            $keys[13] => $this->getPicture(),
            $keys[14] => $this->getInit(),
            $keys[15] => $this->getData(),
        );

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUid($value);
                break;
            case 1:
                $this->setNames($value);
                break;
            case 2:
                $this->setPass($value);
                break;
            case 3:
                $this->setMail($value);
                break;
            case 4:
                $this->setTheme($value);
                break;
            case 5:
                $this->setSignature($value);
                break;
            case 6:
                $this->setSignatureFormat($value);
                break;
            case 7:
                $this->setCreated($value);
                break;
            case 8:
                $this->setAccess($value);
                break;
            case 9:
                $this->setLogin($value);
                break;
            case 10:
                $this->setStatus($value);
                break;
            case 11:
                $this->setTimezone($value);
                break;
            case 12:
                $this->setLanguage($value);
                break;
            case 13:
                $this->setPicture($value);
                break;
            case 14:
                $this->setInit($value);
                break;
            case 15:
                $this->setData($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = UserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setUid($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setNames($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setPass($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setMail($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setTheme($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setSignature($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSignatureFormat($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreated($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setAccess($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setLogin($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setStatus($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setTimezone($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setLanguage($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPicture($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setInit($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setData($arr[$keys[15]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserPeer::UID)) $criteria->add(UserPeer::UID, $this->uid);
        if ($this->isColumnModified(UserPeer::NAMES)) $criteria->add(UserPeer::NAMES, $this->names);
        if ($this->isColumnModified(UserPeer::PASS)) $criteria->add(UserPeer::PASS, $this->pass);
        if ($this->isColumnModified(UserPeer::MAIL)) $criteria->add(UserPeer::MAIL, $this->mail);
        if ($this->isColumnModified(UserPeer::THEME)) $criteria->add(UserPeer::THEME, $this->theme);
        if ($this->isColumnModified(UserPeer::SIGNATURE)) $criteria->add(UserPeer::SIGNATURE, $this->signature);
        if ($this->isColumnModified(UserPeer::SIGNATURE_FORMAT)) $criteria->add(UserPeer::SIGNATURE_FORMAT, $this->signature_format);
        if ($this->isColumnModified(UserPeer::CREATED)) $criteria->add(UserPeer::CREATED, $this->created);
        if ($this->isColumnModified(UserPeer::ACCESS)) $criteria->add(UserPeer::ACCESS, $this->access);
        if ($this->isColumnModified(UserPeer::LOGIN)) $criteria->add(UserPeer::LOGIN, $this->login);
        if ($this->isColumnModified(UserPeer::STATUS)) $criteria->add(UserPeer::STATUS, $this->status);
        if ($this->isColumnModified(UserPeer::TIMEZONE)) $criteria->add(UserPeer::TIMEZONE, $this->timezone);
        if ($this->isColumnModified(UserPeer::LANGUAGE)) $criteria->add(UserPeer::LANGUAGE, $this->language);
        if ($this->isColumnModified(UserPeer::PICTURE)) $criteria->add(UserPeer::PICTURE, $this->picture);
        if ($this->isColumnModified(UserPeer::INIT)) $criteria->add(UserPeer::INIT, $this->init);
        if ($this->isColumnModified(UserPeer::DATA)) $criteria->add(UserPeer::DATA, $this->data);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::UID, $this->uid);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getUid();
    }

    /**
     * Generic method to set the primary key (uid column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getUid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of User (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNames($this->getNames());
        $copyObj->setPass($this->getPass());
        $copyObj->setMail($this->getMail());
        $copyObj->setTheme($this->getTheme());
        $copyObj->setSignature($this->getSignature());
        $copyObj->setSignatureFormat($this->getSignatureFormat());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setAccess($this->getAccess());
        $copyObj->setLogin($this->getLogin());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setTimezone($this->getTimezone());
        $copyObj->setLanguage($this->getLanguage());
        $copyObj->setPicture($this->getPicture());
        $copyObj->setInit($this->getInit());
        $copyObj->setData($this->getData());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setUid(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return User Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return UserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserPeer();
        }

        return self::$peer;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->uid = null;
        $this->names = null;
        $this->pass = null;
        $this->mail = null;
        $this->theme = null;
        $this->signature = null;
        $this->signature_format = null;
        $this->created = null;
        $this->access = null;
        $this->login = null;
        $this->status = null;
        $this->timezone = null;
        $this->language = null;
        $this->picture = null;
        $this->init = null;
        $this->data = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
