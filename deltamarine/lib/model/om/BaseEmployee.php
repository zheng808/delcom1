<?php

/**
 * Base class that represents a row from the 'employee' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseEmployee extends BaseObject  implements Persistent {


  const PEER = 'EmployeePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        EmployeePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the wf_crm_id field.
	 * @var        int
	 */
	protected $wf_crm_id;

	/**
	 * The value for the guard_user_id field.
	 * @var        int
	 */
	protected $guard_user_id;

	/**
	 * The value for the payrate field.
	 * @var        string
	 */
	protected $payrate;

	/**
	 * The value for the hidden field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $hidden;

	/**
	 * @var        wfCRM
	 */
	protected $awfCRM;

	/**
	 * @var        sfGuardUser
	 */
	protected $asfGuardUser;

	/**
	 * @var        array PartInstance[] Collection to store aggregation of PartInstance objects.
	 */
	protected $collPartInstances;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartInstances.
	 */
	private $lastPartInstanceCriteria = null;

	/**
	 * @var        array WorkorderItem[] Collection to store aggregation of WorkorderItem objects.
	 */
	protected $collWorkorderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItems.
	 */
	private $lastWorkorderItemCriteria = null;

	/**
	 * @var        array Timelog[] Collection to store aggregation of Timelog objects.
	 */
	protected $collTimelogs;

	/**
	 * @var        Criteria The criteria used to select the current contents of collTimelogs.
	 */
	private $lastTimelogCriteria = null;

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
	 * Initializes internal state of BaseEmployee object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->hidden = false;
	}

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [wf_crm_id] column value.
	 * 
	 * @return     int
	 */
	public function getWfCrmId()
	{
		return $this->wf_crm_id;
	}

	/**
	 * Get the [guard_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getGuardUserId()
	{
		return $this->guard_user_id;
	}

	/**
	 * Get the [payrate] column value.
	 * 
	 * @return     string
	 */
	public function getPayrate()
	{
		return $this->payrate;
	}

	/**
	 * Get the [hidden] column value.
	 * 
	 * @return     boolean
	 */
	public function getHidden()
	{
		return $this->hidden;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Employee The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = EmployeePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [wf_crm_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Employee The current object (for fluent API support)
	 */
	public function setWfCrmId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->wf_crm_id !== $v) {
			$this->wf_crm_id = $v;
			$this->modifiedColumns[] = EmployeePeer::WF_CRM_ID;
		}

		if ($this->awfCRM !== null && $this->awfCRM->getId() !== $v) {
			$this->awfCRM = null;
		}

		return $this;
	} // setWfCrmId()

	/**
	 * Set the value of [guard_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Employee The current object (for fluent API support)
	 */
	public function setGuardUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->guard_user_id !== $v) {
			$this->guard_user_id = $v;
			$this->modifiedColumns[] = EmployeePeer::GUARD_USER_ID;
		}

		if ($this->asfGuardUser !== null && $this->asfGuardUser->getId() !== $v) {
			$this->asfGuardUser = null;
		}

		return $this;
	} // setGuardUserId()

	/**
	 * Set the value of [payrate] column.
	 * 
	 * @param      string $v new value
	 * @return     Employee The current object (for fluent API support)
	 */
	public function setPayrate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->payrate !== $v) {
			$this->payrate = $v;
			$this->modifiedColumns[] = EmployeePeer::PAYRATE;
		}

		return $this;
	} // setPayrate()

	/**
	 * Set the value of [hidden] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Employee The current object (for fluent API support)
	 */
	public function setHidden($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->hidden !== $v || $v === false) {
			$this->hidden = $v;
			$this->modifiedColumns[] = EmployeePeer::HIDDEN;
		}

		return $this;
	} // setHidden()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			// First, ensure that we don't have any columns that have been modified which aren't default columns.
			if (array_diff($this->modifiedColumns, array(EmployeePeer::HIDDEN))) {
				return false;
			}

			if ($this->hidden !== false) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
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
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->wf_crm_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->guard_user_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->payrate = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->hidden = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Employee object", $e);
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
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->awfCRM !== null && $this->wf_crm_id !== $this->awfCRM->getId()) {
			$this->awfCRM = null;
		}
		if ($this->asfGuardUser !== null && $this->guard_user_id !== $this->asfGuardUser->getId()) {
			$this->asfGuardUser = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
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
			$con = Propel::getConnection(EmployeePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = EmployeePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->awfCRM = null;
			$this->asfGuardUser = null;
			$this->collPartInstances = null;
			$this->lastPartInstanceCriteria = null;

			$this->collWorkorderItems = null;
			$this->lastWorkorderItemCriteria = null;

			$this->collTimelogs = null;
			$this->lastTimelogCriteria = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseEmployee:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(EmployeePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			EmployeePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseEmployee:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
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
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseEmployee:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(EmployeePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseEmployee:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			EmployeePeer::addInstanceToPool($this);
			return $affectedRows;
		} catch (PropelException $e) {
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
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->awfCRM !== null) {
				if ($this->awfCRM->isModified() || $this->awfCRM->isNew()) {
					$affectedRows += $this->awfCRM->save($con);
				}
				$this->setwfCRM($this->awfCRM);
			}

			if ($this->asfGuardUser !== null) {
				if ($this->asfGuardUser->isModified() || $this->asfGuardUser->isNew()) {
					$affectedRows += $this->asfGuardUser->save($con);
				}
				$this->setsfGuardUser($this->asfGuardUser);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = EmployeePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = EmployeePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += EmployeePeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPartInstances !== null) {
				foreach ($this->collPartInstances as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderItems !== null) {
				foreach ($this->collWorkorderItems as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collTimelogs !== null) {
				foreach ($this->collTimelogs as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
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
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
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
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->awfCRM !== null) {
				if (!$this->awfCRM->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->awfCRM->getValidationFailures());
				}
			}

			if ($this->asfGuardUser !== null) {
				if (!$this->asfGuardUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->asfGuardUser->getValidationFailures());
				}
			}


			if (($retval = EmployeePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPartInstances !== null) {
					foreach ($this->collPartInstances as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderItems !== null) {
					foreach ($this->collWorkorderItems as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collTimelogs !== null) {
					foreach ($this->collTimelogs as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = EmployeePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getWfCrmId();
				break;
			case 2:
				return $this->getGuardUserId();
				break;
			case 3:
				return $this->getPayrate();
				break;
			case 4:
				return $this->getHidden();
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
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = EmployeePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWfCrmId(),
			$keys[2] => $this->getGuardUserId(),
			$keys[3] => $this->getPayrate(),
			$keys[4] => $this->getHidden(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = EmployeePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setWfCrmId($value);
				break;
			case 2:
				$this->setGuardUserId($value);
				break;
			case 3:
				$this->setPayrate($value);
				break;
			case 4:
				$this->setHidden($value);
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
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = EmployeePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWfCrmId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setGuardUserId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPayrate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setHidden($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(EmployeePeer::DATABASE_NAME);

		if ($this->isColumnModified(EmployeePeer::ID)) $criteria->add(EmployeePeer::ID, $this->id);
		if ($this->isColumnModified(EmployeePeer::WF_CRM_ID)) $criteria->add(EmployeePeer::WF_CRM_ID, $this->wf_crm_id);
		if ($this->isColumnModified(EmployeePeer::GUARD_USER_ID)) $criteria->add(EmployeePeer::GUARD_USER_ID, $this->guard_user_id);
		if ($this->isColumnModified(EmployeePeer::PAYRATE)) $criteria->add(EmployeePeer::PAYRATE, $this->payrate);
		if ($this->isColumnModified(EmployeePeer::HIDDEN)) $criteria->add(EmployeePeer::HIDDEN, $this->hidden);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(EmployeePeer::DATABASE_NAME);

		$criteria->add(EmployeePeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Employee (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setWfCrmId($this->wf_crm_id);

		$copyObj->setGuardUserId($this->guard_user_id);

		$copyObj->setPayrate($this->payrate);

		$copyObj->setHidden($this->hidden);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPartInstances() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartInstance($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItem($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getTimelogs() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTimelog($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Employee Clone of current object.
	 * @throws     PropelException
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
	 * @return     EmployeePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new EmployeePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a wfCRM object.
	 *
	 * @param      wfCRM $v
	 * @return     Employee The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setwfCRM(wfCRM $v = null)
	{
		if ($v === null) {
			$this->setWfCrmId(NULL);
		} else {
			$this->setWfCrmId($v->getId());
		}

		$this->awfCRM = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the wfCRM object, it will not be re-added.
		if ($v !== null) {
			$v->addEmployee($this);
		}

		return $this;
	}


	/**
	 * Get the associated wfCRM object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     wfCRM The associated wfCRM object.
	 * @throws     PropelException
	 */
	public function getwfCRM(PropelPDO $con = null)
	{
		if ($this->awfCRM === null && ($this->wf_crm_id !== null)) {
			$c = new Criteria(wfCRMPeer::DATABASE_NAME);
			$c->add(wfCRMPeer::ID, $this->wf_crm_id);
			$this->awfCRM = wfCRMPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->awfCRM->addEmployees($this);
			 */
		}
		return $this->awfCRM;
	}

	/**
	 * Declares an association between this object and a sfGuardUser object.
	 *
	 * @param      sfGuardUser $v
	 * @return     Employee The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setsfGuardUser(sfGuardUser $v = null)
	{
		if ($v === null) {
			$this->setGuardUserId(NULL);
		} else {
			$this->setGuardUserId($v->getId());
		}

		$this->asfGuardUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the sfGuardUser object, it will not be re-added.
		if ($v !== null) {
			$v->addEmployee($this);
		}

		return $this;
	}


	/**
	 * Get the associated sfGuardUser object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     sfGuardUser The associated sfGuardUser object.
	 * @throws     PropelException
	 */
	public function getsfGuardUser(PropelPDO $con = null)
	{
		if ($this->asfGuardUser === null && ($this->guard_user_id !== null)) {
			$c = new Criteria(sfGuardUserPeer::DATABASE_NAME);
			$c->add(sfGuardUserPeer::ID, $this->guard_user_id);
			$this->asfGuardUser = sfGuardUserPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->asfGuardUser->addEmployees($this);
			 */
		}
		return $this->asfGuardUser;
	}

	/**
	 * Clears out the collPartInstances collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartInstances()
	 */
	public function clearPartInstances()
	{
		$this->collPartInstances = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartInstances collection (array).
	 *
	 * By default this just sets the collPartInstances collection to an empty array (like clearcollPartInstances());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartInstances()
	{
		$this->collPartInstances = array();
	}

	/**
	 * Gets an array of PartInstance objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Employee has previously been saved, it will retrieve
	 * related PartInstances from storage. If this Employee is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartInstance[]
	 * @throws     PropelException
	 */
	public function getPartInstances($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
			   $this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
					$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartInstanceCriteria = $criteria;
		return $this->collPartInstances;
	}

	/**
	 * Returns the number of related PartInstance objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartInstance objects.
	 * @throws     PropelException
	 */
	public function countPartInstances(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				$count = PartInstancePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
					$count = PartInstancePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartInstances);
				}
			} else {
				$count = count($this->collPartInstances);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartInstance object to this object
	 * through the PartInstance foreign key attribute.
	 *
	 * @param      PartInstance $l PartInstance
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartInstance(PartInstance $l)
	{
		if ($this->collPartInstances === null) {
			$this->initPartInstances();
		}
		if (!in_array($l, $this->collPartInstances, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartInstances, $l);
			$l->setEmployee($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getPartInstancesJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getPartInstancesJoinSupplierOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getPartInstancesJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getPartInstancesJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::ADDED_BY, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}

	/**
	 * Clears out the collWorkorderItems collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderItems()
	 */
	public function clearWorkorderItems()
	{
		$this->collWorkorderItems = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderItems collection (array).
	 *
	 * By default this just sets the collWorkorderItems collection to an empty array (like clearcollWorkorderItems());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderItems()
	{
		$this->collWorkorderItems = array();
	}

	/**
	 * Gets an array of WorkorderItem objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Employee has previously been saved, it will retrieve
	 * related WorkorderItems from storage. If this Employee is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderItem[]
	 * @throws     PropelException
	 */
	public function getWorkorderItems($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItems = array();
			} else {

				$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

				WorkorderItemPeer::addSelectColumns($criteria);
				$this->collWorkorderItems = WorkorderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

				WorkorderItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
					$this->collWorkorderItems = WorkorderItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderItemCriteria = $criteria;
		return $this->collWorkorderItems;
	}

	/**
	 * Returns the number of related WorkorderItem objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderItem objects.
	 * @throws     PropelException
	 */
	public function countWorkorderItems(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

				$count = WorkorderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

				if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
					$count = WorkorderItemPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderItems);
				}
			} else {
				$count = count($this->collWorkorderItems);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderItem object to this object
	 * through the WorkorderItem foreign key attribute.
	 *
	 * @param      WorkorderItem $l WorkorderItem
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderItem(WorkorderItem $l)
	{
		if ($this->collWorkorderItems === null) {
			$this->initWorkorderItems();
		}
		if (!in_array($l, $this->collWorkorderItems, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderItems, $l);
			$l->setEmployee($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related WorkorderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getWorkorderItemsJoinWorkorder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
				$this->collWorkorderItems = array();
			} else {

				$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

				$this->collWorkorderItems = WorkorderItemPeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->id);

			if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
				$this->collWorkorderItems = WorkorderItemPeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemCriteria = $criteria;

		return $this->collWorkorderItems;
	}

	/**
	 * Clears out the collTimelogs collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTimelogs()
	 */
	public function clearTimelogs()
	{
		$this->collTimelogs = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTimelogs collection (array).
	 *
	 * By default this just sets the collTimelogs collection to an empty array (like clearcollTimelogs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initTimelogs()
	{
		$this->collTimelogs = array();
	}

	/**
	 * Gets an array of Timelog objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Employee has previously been saved, it will retrieve
	 * related Timelogs from storage. If this Employee is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Timelog[]
	 * @throws     PropelException
	 */
	public function getTimelogs($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
			   $this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				TimelogPeer::addSelectColumns($criteria);
				$this->collTimelogs = TimelogPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				TimelogPeer::addSelectColumns($criteria);
				if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
					$this->collTimelogs = TimelogPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastTimelogCriteria = $criteria;
		return $this->collTimelogs;
	}

	/**
	 * Returns the number of related Timelog objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Timelog objects.
	 * @throws     PropelException
	 */
	public function countTimelogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				$count = TimelogPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
					$count = TimelogPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collTimelogs);
				}
			} else {
				$count = count($this->collTimelogs);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Timelog object to this object
	 * through the Timelog foreign key attribute.
	 *
	 * @param      Timelog $l Timelog
	 * @return     void
	 * @throws     PropelException
	 */
	public function addTimelog(Timelog $l)
	{
		if ($this->collTimelogs === null) {
			$this->initTimelogs();
		}
		if (!in_array($l, $this->collTimelogs, true)) { // only add it if the **same** object is not already associated
			array_push($this->collTimelogs, $l);
			$l->setEmployee($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getTimelogsJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getTimelogsJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getTimelogsJoinLabourType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinLabourType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinLabourType($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Employee is new, it will return
	 * an empty collection; or if this Employee has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Employee.
	 */
	public function getTimelogsJoinNonbillType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EmployeePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinNonbillType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::EMPLOYEE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinNonbillType($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collPartInstances) {
				foreach ((array) $this->collPartInstances as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderItems) {
				foreach ((array) $this->collWorkorderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTimelogs) {
				foreach ((array) $this->collTimelogs as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPartInstances = null;
		$this->collWorkorderItems = null;
		$this->collTimelogs = null;
			$this->awfCRM = null;
			$this->asfGuardUser = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseEmployee:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseEmployee::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseEmployee
