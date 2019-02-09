<?php

/**
 * Base class that represents a row from the 'customer' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseCustomer extends BaseObject  implements Persistent {


  const PEER = 'CustomerPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        CustomerPeer
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
	 * The value for the pst_number field.
	 * @var        string
	 */
	protected $pst_number;

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
	 * @var        array CustomerOrder[] Collection to store aggregation of CustomerOrder objects.
	 */
	protected $collCustomerOrders;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerOrders.
	 */
	private $lastCustomerOrderCriteria = null;

	/**
	 * @var        array Workorder[] Collection to store aggregation of Workorder objects.
	 */
	protected $collWorkorders;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorders.
	 */
	private $lastWorkorderCriteria = null;

	/**
	 * @var        array CustomerBoat[] Collection to store aggregation of CustomerBoat objects.
	 */
	protected $collCustomerBoats;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerBoats.
	 */
	private $lastCustomerBoatCriteria = null;

	/**
	 * @var        array Invoice[] Collection to store aggregation of Invoice objects.
	 */
	protected $collInvoices;

	/**
	 * @var        Criteria The criteria used to select the current contents of collInvoices.
	 */
	private $lastInvoiceCriteria = null;

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
	 * Initializes internal state of BaseCustomer object.
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
	 * Get the [pst_number] column value.
	 * 
	 * @return     string
	 */
	public function getPstNumber()
	{
		return $this->pst_number;
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
	 * @return     Customer The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = CustomerPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [wf_crm_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Customer The current object (for fluent API support)
	 */
	public function setWfCrmId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->wf_crm_id !== $v) {
			$this->wf_crm_id = $v;
			$this->modifiedColumns[] = CustomerPeer::WF_CRM_ID;
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
	 * @return     Customer The current object (for fluent API support)
	 */
	public function setGuardUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->guard_user_id !== $v) {
			$this->guard_user_id = $v;
			$this->modifiedColumns[] = CustomerPeer::GUARD_USER_ID;
		}

		if ($this->asfGuardUser !== null && $this->asfGuardUser->getId() !== $v) {
			$this->asfGuardUser = null;
		}

		return $this;
	} // setGuardUserId()

	/**
	 * Set the value of [pst_number] column.
	 * 
	 * @param      string $v new value
	 * @return     Customer The current object (for fluent API support)
	 */
	public function setPstNumber($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->pst_number !== $v) {
			$this->pst_number = $v;
			$this->modifiedColumns[] = CustomerPeer::PST_NUMBER;
		}

		return $this;
	} // setPstNumber()

	/**
	 * Set the value of [hidden] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Customer The current object (for fluent API support)
	 */
	public function setHidden($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->hidden !== $v || $v === false) {
			$this->hidden = $v;
			$this->modifiedColumns[] = CustomerPeer::HIDDEN;
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
			if (array_diff($this->modifiedColumns, array(CustomerPeer::HIDDEN))) {
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
			$this->pst_number = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->hidden = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Customer object", $e);
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
			$con = Propel::getConnection(CustomerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = CustomerPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->awfCRM = null;
			$this->asfGuardUser = null;
			$this->collCustomerOrders = null;
			$this->lastCustomerOrderCriteria = null;

			$this->collWorkorders = null;
			$this->lastWorkorderCriteria = null;

			$this->collCustomerBoats = null;
			$this->lastCustomerBoatCriteria = null;

			$this->collInvoices = null;
			$this->lastInvoiceCriteria = null;

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

    foreach (sfMixer::getCallables('BaseCustomer:delete:pre') as $callable)
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
			$con = Propel::getConnection(CustomerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			CustomerPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseCustomer:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseCustomer:save:pre') as $callable)
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
			$con = Propel::getConnection(CustomerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseCustomer:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			CustomerPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = CustomerPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CustomerPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += CustomerPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collCustomerOrders !== null) {
				foreach ($this->collCustomerOrders as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorders !== null) {
				foreach ($this->collWorkorders as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCustomerBoats !== null) {
				foreach ($this->collCustomerBoats as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collInvoices !== null) {
				foreach ($this->collInvoices as $referrerFK) {
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


			if (($retval = CustomerPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collCustomerOrders !== null) {
					foreach ($this->collCustomerOrders as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorders !== null) {
					foreach ($this->collWorkorders as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCustomerBoats !== null) {
					foreach ($this->collCustomerBoats as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collInvoices !== null) {
					foreach ($this->collInvoices as $referrerFK) {
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
		$pos = CustomerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPstNumber();
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
		$keys = CustomerPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWfCrmId(),
			$keys[2] => $this->getGuardUserId(),
			$keys[3] => $this->getPstNumber(),
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
		$pos = CustomerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPstNumber($value);
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
		$keys = CustomerPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWfCrmId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setGuardUserId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPstNumber($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setHidden($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(CustomerPeer::DATABASE_NAME);

		if ($this->isColumnModified(CustomerPeer::ID)) $criteria->add(CustomerPeer::ID, $this->id);
		if ($this->isColumnModified(CustomerPeer::WF_CRM_ID)) $criteria->add(CustomerPeer::WF_CRM_ID, $this->wf_crm_id);
		if ($this->isColumnModified(CustomerPeer::GUARD_USER_ID)) $criteria->add(CustomerPeer::GUARD_USER_ID, $this->guard_user_id);
		if ($this->isColumnModified(CustomerPeer::PST_NUMBER)) $criteria->add(CustomerPeer::PST_NUMBER, $this->pst_number);
		if ($this->isColumnModified(CustomerPeer::HIDDEN)) $criteria->add(CustomerPeer::HIDDEN, $this->hidden);

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
		$criteria = new Criteria(CustomerPeer::DATABASE_NAME);

		$criteria->add(CustomerPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Customer (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setWfCrmId($this->wf_crm_id);

		$copyObj->setGuardUserId($this->guard_user_id);

		$copyObj->setPstNumber($this->pst_number);

		$copyObj->setHidden($this->hidden);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getCustomerOrders() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerOrder($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorders() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorder($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomerBoats() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerBoat($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getInvoices() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addInvoice($relObj->copy($deepCopy));
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
	 * @return     Customer Clone of current object.
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
	 * @return     CustomerPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new CustomerPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a wfCRM object.
	 *
	 * @param      wfCRM $v
	 * @return     Customer The current object (for fluent API support)
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
			$v->addCustomer($this);
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
			   $this->awfCRM->addCustomers($this);
			 */
		}
		return $this->awfCRM;
	}

	/**
	 * Declares an association between this object and a sfGuardUser object.
	 *
	 * @param      sfGuardUser $v
	 * @return     Customer The current object (for fluent API support)
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
			$v->addCustomer($this);
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
			   $this->asfGuardUser->addCustomers($this);
			 */
		}
		return $this->asfGuardUser;
	}

	/**
	 * Clears out the collCustomerOrders collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomerOrders()
	 */
	public function clearCustomerOrders()
	{
		$this->collCustomerOrders = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomerOrders collection (array).
	 *
	 * By default this just sets the collCustomerOrders collection to an empty array (like clearcollCustomerOrders());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomerOrders()
	{
		$this->collCustomerOrders = array();
	}

	/**
	 * Gets an array of CustomerOrder objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Customer has previously been saved, it will retrieve
	 * related CustomerOrders from storage. If this Customer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array CustomerOrder[]
	 * @throws     PropelException
	 */
	public function getCustomerOrders($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrders === null) {
			if ($this->isNew()) {
			   $this->collCustomerOrders = array();
			} else {

				$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

				CustomerOrderPeer::addSelectColumns($criteria);
				$this->collCustomerOrders = CustomerOrderPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

				CustomerOrderPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerOrderCriteria) || !$this->lastCustomerOrderCriteria->equals($criteria)) {
					$this->collCustomerOrders = CustomerOrderPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerOrderCriteria = $criteria;
		return $this->collCustomerOrders;
	}

	/**
	 * Returns the number of related CustomerOrder objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CustomerOrder objects.
	 * @throws     PropelException
	 */
	public function countCustomerOrders(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomerOrders === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

				$count = CustomerOrderPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

				if (!isset($this->lastCustomerOrderCriteria) || !$this->lastCustomerOrderCriteria->equals($criteria)) {
					$count = CustomerOrderPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomerOrders);
				}
			} else {
				$count = count($this->collCustomerOrders);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a CustomerOrder object to this object
	 * through the CustomerOrder foreign key attribute.
	 *
	 * @param      CustomerOrder $l CustomerOrder
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomerOrder(CustomerOrder $l)
	{
		if ($this->collCustomerOrders === null) {
			$this->initCustomerOrders();
		}
		if (!in_array($l, $this->collCustomerOrders, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomerOrders, $l);
			$l->setCustomer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Customer is new, it will return
	 * an empty collection; or if this Customer has previously
	 * been saved, it will retrieve related CustomerOrders from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Customer.
	 */
	public function getCustomerOrdersJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrders === null) {
			if ($this->isNew()) {
				$this->collCustomerOrders = array();
			} else {

				$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

				$this->collCustomerOrders = CustomerOrderPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->id);

			if (!isset($this->lastCustomerOrderCriteria) || !$this->lastCustomerOrderCriteria->equals($criteria)) {
				$this->collCustomerOrders = CustomerOrderPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerOrderCriteria = $criteria;

		return $this->collCustomerOrders;
	}

	/**
	 * Clears out the collWorkorders collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorders()
	 */
	public function clearWorkorders()
	{
		$this->collWorkorders = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorders collection (array).
	 *
	 * By default this just sets the collWorkorders collection to an empty array (like clearcollWorkorders());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorders()
	{
		$this->collWorkorders = array();
	}

	/**
	 * Gets an array of Workorder objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Customer has previously been saved, it will retrieve
	 * related Workorders from storage. If this Customer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Workorder[]
	 * @throws     PropelException
	 */
	public function getWorkorders($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorders === null) {
			if ($this->isNew()) {
			   $this->collWorkorders = array();
			} else {

				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				WorkorderPeer::addSelectColumns($criteria);
				$this->collWorkorders = WorkorderPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				WorkorderPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderCriteria) || !$this->lastWorkorderCriteria->equals($criteria)) {
					$this->collWorkorders = WorkorderPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderCriteria = $criteria;
		return $this->collWorkorders;
	}

	/**
	 * Returns the number of related Workorder objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Workorder objects.
	 * @throws     PropelException
	 */
	public function countWorkorders(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorders === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				$count = WorkorderPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				if (!isset($this->lastWorkorderCriteria) || !$this->lastWorkorderCriteria->equals($criteria)) {
					$count = WorkorderPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorders);
				}
			} else {
				$count = count($this->collWorkorders);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Workorder object to this object
	 * through the Workorder foreign key attribute.
	 *
	 * @param      Workorder $l Workorder
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorder(Workorder $l)
	{
		if ($this->collWorkorders === null) {
			$this->initWorkorders();
		}
		if (!in_array($l, $this->collWorkorders, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorders, $l);
			$l->setCustomer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Customer is new, it will return
	 * an empty collection; or if this Customer has previously
	 * been saved, it will retrieve related Workorders from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Customer.
	 */
	public function getWorkordersJoinCustomerBoat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorders === null) {
			if ($this->isNew()) {
				$this->collWorkorders = array();
			} else {

				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				$this->collWorkorders = WorkorderPeer::doSelectJoinCustomerBoat($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

			if (!isset($this->lastWorkorderCriteria) || !$this->lastWorkorderCriteria->equals($criteria)) {
				$this->collWorkorders = WorkorderPeer::doSelectJoinCustomerBoat($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderCriteria = $criteria;

		return $this->collWorkorders;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Customer is new, it will return
	 * an empty collection; or if this Customer has previously
	 * been saved, it will retrieve related Workorders from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Customer.
	 */
	public function getWorkordersJoinWorkorderCategory($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorders === null) {
			if ($this->isNew()) {
				$this->collWorkorders = array();
			} else {

				$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

				$this->collWorkorders = WorkorderPeer::doSelectJoinWorkorderCategory($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderPeer::CUSTOMER_ID, $this->id);

			if (!isset($this->lastWorkorderCriteria) || !$this->lastWorkorderCriteria->equals($criteria)) {
				$this->collWorkorders = WorkorderPeer::doSelectJoinWorkorderCategory($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderCriteria = $criteria;

		return $this->collWorkorders;
	}

	/**
	 * Clears out the collCustomerBoats collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomerBoats()
	 */
	public function clearCustomerBoats()
	{
		$this->collCustomerBoats = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomerBoats collection (array).
	 *
	 * By default this just sets the collCustomerBoats collection to an empty array (like clearcollCustomerBoats());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomerBoats()
	{
		$this->collCustomerBoats = array();
	}

	/**
	 * Gets an array of CustomerBoat objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Customer has previously been saved, it will retrieve
	 * related CustomerBoats from storage. If this Customer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array CustomerBoat[]
	 * @throws     PropelException
	 */
	public function getCustomerBoats($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerBoats === null) {
			if ($this->isNew()) {
			   $this->collCustomerBoats = array();
			} else {

				$criteria->add(CustomerBoatPeer::CUSTOMER_ID, $this->id);

				CustomerBoatPeer::addSelectColumns($criteria);
				$this->collCustomerBoats = CustomerBoatPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerBoatPeer::CUSTOMER_ID, $this->id);

				CustomerBoatPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerBoatCriteria) || !$this->lastCustomerBoatCriteria->equals($criteria)) {
					$this->collCustomerBoats = CustomerBoatPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerBoatCriteria = $criteria;
		return $this->collCustomerBoats;
	}

	/**
	 * Returns the number of related CustomerBoat objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CustomerBoat objects.
	 * @throws     PropelException
	 */
	public function countCustomerBoats(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomerBoats === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerBoatPeer::CUSTOMER_ID, $this->id);

				$count = CustomerBoatPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerBoatPeer::CUSTOMER_ID, $this->id);

				if (!isset($this->lastCustomerBoatCriteria) || !$this->lastCustomerBoatCriteria->equals($criteria)) {
					$count = CustomerBoatPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomerBoats);
				}
			} else {
				$count = count($this->collCustomerBoats);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a CustomerBoat object to this object
	 * through the CustomerBoat foreign key attribute.
	 *
	 * @param      CustomerBoat $l CustomerBoat
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomerBoat(CustomerBoat $l)
	{
		if ($this->collCustomerBoats === null) {
			$this->initCustomerBoats();
		}
		if (!in_array($l, $this->collCustomerBoats, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomerBoats, $l);
			$l->setCustomer($this);
		}
	}

	/**
	 * Clears out the collInvoices collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addInvoices()
	 */
	public function clearInvoices()
	{
		$this->collInvoices = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collInvoices collection (array).
	 *
	 * By default this just sets the collInvoices collection to an empty array (like clearcollInvoices());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initInvoices()
	{
		$this->collInvoices = array();
	}

	/**
	 * Gets an array of Invoice objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Customer has previously been saved, it will retrieve
	 * related Invoices from storage. If this Customer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Invoice[]
	 * @throws     PropelException
	 */
	public function getInvoices($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collInvoices === null) {
			if ($this->isNew()) {
			   $this->collInvoices = array();
			} else {

				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				InvoicePeer::addSelectColumns($criteria);
				$this->collInvoices = InvoicePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				InvoicePeer::addSelectColumns($criteria);
				if (!isset($this->lastInvoiceCriteria) || !$this->lastInvoiceCriteria->equals($criteria)) {
					$this->collInvoices = InvoicePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastInvoiceCriteria = $criteria;
		return $this->collInvoices;
	}

	/**
	 * Returns the number of related Invoice objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Invoice objects.
	 * @throws     PropelException
	 */
	public function countInvoices(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collInvoices === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				$count = InvoicePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				if (!isset($this->lastInvoiceCriteria) || !$this->lastInvoiceCriteria->equals($criteria)) {
					$count = InvoicePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collInvoices);
				}
			} else {
				$count = count($this->collInvoices);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Invoice object to this object
	 * through the Invoice foreign key attribute.
	 *
	 * @param      Invoice $l Invoice
	 * @return     void
	 * @throws     PropelException
	 */
	public function addInvoice(Invoice $l)
	{
		if ($this->collInvoices === null) {
			$this->initInvoices();
		}
		if (!in_array($l, $this->collInvoices, true)) { // only add it if the **same** object is not already associated
			array_push($this->collInvoices, $l);
			$l->setCustomer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Customer is new, it will return
	 * an empty collection; or if this Customer has previously
	 * been saved, it will retrieve related Invoices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Customer.
	 */
	public function getInvoicesJoinSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collInvoices === null) {
			if ($this->isNew()) {
				$this->collInvoices = array();
			} else {

				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				$this->collInvoices = InvoicePeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

			if (!isset($this->lastInvoiceCriteria) || !$this->lastInvoiceCriteria->equals($criteria)) {
				$this->collInvoices = InvoicePeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastInvoiceCriteria = $criteria;

		return $this->collInvoices;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Customer is new, it will return
	 * an empty collection; or if this Customer has previously
	 * been saved, it will retrieve related Invoices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Customer.
	 */
	public function getInvoicesJoinManufacturer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collInvoices === null) {
			if ($this->isNew()) {
				$this->collInvoices = array();
			} else {

				$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

				$this->collInvoices = InvoicePeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(InvoicePeer::CUSTOMER_ID, $this->id);

			if (!isset($this->lastInvoiceCriteria) || !$this->lastInvoiceCriteria->equals($criteria)) {
				$this->collInvoices = InvoicePeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		}
		$this->lastInvoiceCriteria = $criteria;

		return $this->collInvoices;
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
			if ($this->collCustomerOrders) {
				foreach ((array) $this->collCustomerOrders as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorders) {
				foreach ((array) $this->collWorkorders as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomerBoats) {
				foreach ((array) $this->collCustomerBoats as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collInvoices) {
				foreach ((array) $this->collInvoices as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collCustomerOrders = null;
		$this->collWorkorders = null;
		$this->collCustomerBoats = null;
		$this->collInvoices = null;
			$this->awfCRM = null;
			$this->asfGuardUser = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseCustomer:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseCustomer::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseCustomer