<?php

/**
 * Base class that represents a row from the 'wf_crm_address' table.
 *
 * 
 *
 * @package    plugins.wfCRMPlugin.lib.model.om
 */
abstract class BasewfCRMAddress extends BaseObject  implements Persistent {


  const PEER = 'wfCRMAddressPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        wfCRMAddressPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the crm_id field.
	 * @var        int
	 */
	protected $crm_id;

	/**
	 * The value for the type field.
	 * @var        string
	 */
	protected $type;

	/**
	 * The value for the line1 field.
	 * @var        string
	 */
	protected $line1;

	/**
	 * The value for the line2 field.
	 * @var        string
	 */
	protected $line2;

	/**
	 * The value for the city field.
	 * @var        string
	 */
	protected $city;

	/**
	 * The value for the region field.
	 * @var        string
	 */
	protected $region;

	/**
	 * The value for the postal field.
	 * @var        string
	 */
	protected $postal;

	/**
	 * The value for the country field.
	 * @var        string
	 */
	protected $country;

	/**
	 * @var        wfCRM
	 */
	protected $awfCRM;

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
	 * Initializes internal state of BasewfCRMAddress object.
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
	 * Get the [crm_id] column value.
	 * 
	 * @return     int
	 */
	public function getCrmId()
	{
		return $this->crm_id;
	}

	/**
	 * Get the [type] column value.
	 * 
	 * @return     string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the [line1] column value.
	 * 
	 * @return     string
	 */
	public function getLine1()
	{
		return $this->line1;
	}

	/**
	 * Get the [line2] column value.
	 * 
	 * @return     string
	 */
	public function getLine2()
	{
		return $this->line2;
	}

	/**
	 * Get the [city] column value.
	 * 
	 * @return     string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Get the [region] column value.
	 * 
	 * @return     string
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * Get the [postal] column value.
	 * 
	 * @return     string
	 */
	public function getPostal()
	{
		return $this->postal;
	}

	/**
	 * Get the [country] column value.
	 * 
	 * @return     string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [crm_id] column.
	 * 
	 * @param      int $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setCrmId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->crm_id !== $v) {
			$this->crm_id = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::CRM_ID;
		}

		if ($this->awfCRM !== null && $this->awfCRM->getId() !== $v) {
			$this->awfCRM = null;
		}

		return $this;
	} // setCrmId()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::TYPE;
		}

		return $this;
	} // setType()

	/**
	 * Set the value of [line1] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setLine1($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->line1 !== $v) {
			$this->line1 = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::LINE1;
		}

		return $this;
	} // setLine1()

	/**
	 * Set the value of [line2] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setLine2($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->line2 !== $v) {
			$this->line2 = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::LINE2;
		}

		return $this;
	} // setLine2()

	/**
	 * Set the value of [city] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setCity($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->city !== $v) {
			$this->city = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::CITY;
		}

		return $this;
	} // setCity()

	/**
	 * Set the value of [region] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setRegion($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->region !== $v) {
			$this->region = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::REGION;
		}

		return $this;
	} // setRegion()

	/**
	 * Set the value of [postal] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setPostal($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->postal !== $v) {
			$this->postal = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::POSTAL;
		}

		return $this;
	} // setPostal()

	/**
	 * Set the value of [country] column.
	 * 
	 * @param      string $v new value
	 * @return     wfCRMAddress The current object (for fluent API support)
	 */
	public function setCountry($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->country !== $v) {
			$this->country = $v;
			$this->modifiedColumns[] = wfCRMAddressPeer::COUNTRY;
		}

		return $this;
	} // setCountry()

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
			if (array_diff($this->modifiedColumns, array())) {
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
			$this->crm_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->type = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->line1 = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->line2 = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->city = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->region = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->postal = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->country = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = wfCRMAddressPeer::NUM_COLUMNS - wfCRMAddressPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating wfCRMAddress object", $e);
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

		if ($this->awfCRM !== null && $this->crm_id !== $this->awfCRM->getId()) {
			$this->awfCRM = null;
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
			$con = Propel::getConnection(wfCRMAddressPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = wfCRMAddressPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->awfCRM = null;
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

    foreach (sfMixer::getCallables('BasewfCRMAddress:delete:pre') as $callable)
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
			$con = Propel::getConnection(wfCRMAddressPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			wfCRMAddressPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasewfCRMAddress:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasewfCRMAddress:save:pre') as $callable)
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
			$con = Propel::getConnection(wfCRMAddressPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasewfCRMAddress:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			wfCRMAddressPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = wfCRMAddressPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = wfCRMAddressPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += wfCRMAddressPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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


			if (($retval = wfCRMAddressPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = wfCRMAddressPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCrmId();
				break;
			case 2:
				return $this->getType();
				break;
			case 3:
				return $this->getLine1();
				break;
			case 4:
				return $this->getLine2();
				break;
			case 5:
				return $this->getCity();
				break;
			case 6:
				return $this->getRegion();
				break;
			case 7:
				return $this->getPostal();
				break;
			case 8:
				return $this->getCountry();
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
		$keys = wfCRMAddressPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCrmId(),
			$keys[2] => $this->getType(),
			$keys[3] => $this->getLine1(),
			$keys[4] => $this->getLine2(),
			$keys[5] => $this->getCity(),
			$keys[6] => $this->getRegion(),
			$keys[7] => $this->getPostal(),
			$keys[8] => $this->getCountry(),
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
		$pos = wfCRMAddressPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCrmId($value);
				break;
			case 2:
				$this->setType($value);
				break;
			case 3:
				$this->setLine1($value);
				break;
			case 4:
				$this->setLine2($value);
				break;
			case 5:
				$this->setCity($value);
				break;
			case 6:
				$this->setRegion($value);
				break;
			case 7:
				$this->setPostal($value);
				break;
			case 8:
				$this->setCountry($value);
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
		$keys = wfCRMAddressPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCrmId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setType($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLine1($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setLine2($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCity($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setRegion($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setPostal($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCountry($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(wfCRMAddressPeer::DATABASE_NAME);

		if ($this->isColumnModified(wfCRMAddressPeer::ID)) $criteria->add(wfCRMAddressPeer::ID, $this->id);
		if ($this->isColumnModified(wfCRMAddressPeer::CRM_ID)) $criteria->add(wfCRMAddressPeer::CRM_ID, $this->crm_id);
		if ($this->isColumnModified(wfCRMAddressPeer::TYPE)) $criteria->add(wfCRMAddressPeer::TYPE, $this->type);
		if ($this->isColumnModified(wfCRMAddressPeer::LINE1)) $criteria->add(wfCRMAddressPeer::LINE1, $this->line1);
		if ($this->isColumnModified(wfCRMAddressPeer::LINE2)) $criteria->add(wfCRMAddressPeer::LINE2, $this->line2);
		if ($this->isColumnModified(wfCRMAddressPeer::CITY)) $criteria->add(wfCRMAddressPeer::CITY, $this->city);
		if ($this->isColumnModified(wfCRMAddressPeer::REGION)) $criteria->add(wfCRMAddressPeer::REGION, $this->region);
		if ($this->isColumnModified(wfCRMAddressPeer::POSTAL)) $criteria->add(wfCRMAddressPeer::POSTAL, $this->postal);
		if ($this->isColumnModified(wfCRMAddressPeer::COUNTRY)) $criteria->add(wfCRMAddressPeer::COUNTRY, $this->country);

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
		$criteria = new Criteria(wfCRMAddressPeer::DATABASE_NAME);

		$criteria->add(wfCRMAddressPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of wfCRMAddress (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCrmId($this->crm_id);

		$copyObj->setType($this->type);

		$copyObj->setLine1($this->line1);

		$copyObj->setLine2($this->line2);

		$copyObj->setCity($this->city);

		$copyObj->setRegion($this->region);

		$copyObj->setPostal($this->postal);

		$copyObj->setCountry($this->country);


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
	 * @return     wfCRMAddress Clone of current object.
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
	 * @return     wfCRMAddressPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new wfCRMAddressPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a wfCRM object.
	 *
	 * @param      wfCRM $v
	 * @return     wfCRMAddress The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setwfCRM(wfCRM $v = null)
	{
		if ($v === null) {
			$this->setCrmId(NULL);
		} else {
			$this->setCrmId($v->getId());
		}

		$this->awfCRM = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the wfCRM object, it will not be re-added.
		if ($v !== null) {
			$v->addwfCRMAddress($this);
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
		if ($this->awfCRM === null && ($this->crm_id !== null)) {
			$c = new Criteria(wfCRMPeer::DATABASE_NAME);
			$c->add(wfCRMPeer::ID, $this->crm_id);
			$this->awfCRM = wfCRMPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->awfCRM->addwfCRMAddresss($this);
			 */
		}
		return $this->awfCRM;
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
		} // if ($deep)

			$this->awfCRM = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasewfCRMAddress:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasewfCRMAddress::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasewfCRMAddress
