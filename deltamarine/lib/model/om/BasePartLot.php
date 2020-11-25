<?php

/**
 * Base class that represents a row from the 'part_lot' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartLot extends BaseObject  implements Persistent {


  const PEER = 'PartLotPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PartLotPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the part_variant_id field.
	 * @var        int
	 */
	protected $part_variant_id;

	/**
	 * The value for the supplier_order_item_id field.
	 * @var        int
	 */
	protected $supplier_order_item_id;

	/**
	 * The value for the quantity_received field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $quantity_received;

	/**
	 * The value for the quantity_remaining field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $quantity_remaining;

	/**
	 * The value for the received_date field.
	 * @var        string
	 */
	protected $received_date;

	/**
	 * The value for the landed_cost field.
	 * @var        string
	 */
	protected $landed_cost;

	/**
	 * @var        PartVariant
	 */
	protected $aPartVariant;

	/**
	 * @var        SupplierOrderItem
	 */
	protected $aSupplierOrderItem;

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
	 * Initializes internal state of BasePartLot object.
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
		$this->quantity_received = '0';
		$this->quantity_remaining = '0';
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
	 * Get the [part_variant_id] column value.
	 * 
	 * @return     int
	 */
	public function getPartVariantId()
	{
		return $this->part_variant_id;
	}

	/**
	 * Get the [supplier_order_item_id] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierOrderItemId()
	{
		return $this->supplier_order_item_id;
	}

	/**
	 * Get the [quantity_received] column value.
	 * 
	 * @return     string
	 */
	public function getQuantityReceived()
	{
		return $this->quantity_received;
	}

	/**
	 * Get the [quantity_remaining] column value.
	 * 
	 * @return     string
	 */
	public function getQuantityRemaining()
	{
		return $this->quantity_remaining;
	}

	/**
	 * Get the [optionally formatted] temporal [received_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getReceivedDate($format = 'Y-m-d H:i:s')
	{
		if ($this->received_date === null) {
			return null;
		}


		if ($this->received_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->received_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->received_date, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [landed_cost] column value.
	 * 
	 * @return     string
	 */
	public function getLandedCost()
	{
		return $this->landed_cost;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PartLotPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [part_variant_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setPartVariantId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_variant_id !== $v) {
			$this->part_variant_id = $v;
			$this->modifiedColumns[] = PartLotPeer::PART_VARIANT_ID;
		}

		if ($this->aPartVariant !== null && $this->aPartVariant->getId() !== $v) {
			$this->aPartVariant = null;
		}

		return $this;
	} // setPartVariantId()

	/**
	 * Set the value of [supplier_order_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setSupplierOrderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_order_item_id !== $v) {
			$this->supplier_order_item_id = $v;
			$this->modifiedColumns[] = PartLotPeer::SUPPLIER_ORDER_ITEM_ID;
		}

		if ($this->aSupplierOrderItem !== null && $this->aSupplierOrderItem->getId() !== $v) {
			$this->aSupplierOrderItem = null;
		}

		return $this;
	} // setSupplierOrderItemId()

	/**
	 * Set the value of [quantity_received] column.
	 * 
	 * @param      string $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setQuantityReceived($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->quantity_received !== $v || $v === '0') {
			$this->quantity_received = $v;
			$this->modifiedColumns[] = PartLotPeer::QUANTITY_RECEIVED;
		}

		return $this;
	} // setQuantityReceived()

	/**
	 * Set the value of [quantity_remaining] column.
	 * 
	 * @param      string $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setQuantityRemaining($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->quantity_remaining !== $v || $v === '0') {
			$this->quantity_remaining = $v;
			$this->modifiedColumns[] = PartLotPeer::QUANTITY_REMAINING;
		}

		return $this;
	} // setQuantityRemaining()

	/**
	 * Sets the value of [received_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setReceivedDate($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->received_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->received_date !== null && $tmpDt = new DateTime($this->received_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->received_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = PartLotPeer::RECEIVED_DATE;
			}
		} // if either are not null

		return $this;
	} // setReceivedDate()

	/**
	 * Set the value of [landed_cost] column.
	 * 
	 * @param      string $v new value
	 * @return     PartLot The current object (for fluent API support)
	 */
	public function setLandedCost($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->landed_cost !== $v) {
			$this->landed_cost = $v;
			$this->modifiedColumns[] = PartLotPeer::LANDED_COST;
		}

		return $this;
	} // setLandedCost()

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
			if (array_diff($this->modifiedColumns, array(PartLotPeer::QUANTITY_RECEIVED,PartLotPeer::QUANTITY_REMAINING))) {
				return false;
			}

			if ($this->quantity_received !== '0') {
				return false;
			}

			if ($this->quantity_remaining !== '0') {
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
			$this->part_variant_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->supplier_order_item_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->quantity_received = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->quantity_remaining = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->received_date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->landed_cost = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = PartLotPeer::NUM_COLUMNS - PartLotPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating PartLot object", $e);
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

		if ($this->aPartVariant !== null && $this->part_variant_id !== $this->aPartVariant->getId()) {
			$this->aPartVariant = null;
		}
		if ($this->aSupplierOrderItem !== null && $this->supplier_order_item_id !== $this->aSupplierOrderItem->getId()) {
			$this->aSupplierOrderItem = null;
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
			$con = Propel::getConnection(PartLotPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PartLotPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aPartVariant = null;
			$this->aSupplierOrderItem = null;
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

    foreach (sfMixer::getCallables('BasePartLot:delete:pre') as $callable)
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
			$con = Propel::getConnection(PartLotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PartLotPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePartLot:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePartLot:save:pre') as $callable)
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
			$con = Propel::getConnection(PartLotPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePartLot:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PartLotPeer::addInstanceToPool($this);
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

			if ($this->aPartVariant !== null) {
				if ($this->aPartVariant->isModified() || $this->aPartVariant->isNew()) {
					$affectedRows += $this->aPartVariant->save($con);
				}
				$this->setPartVariant($this->aPartVariant);
			}

			if ($this->aSupplierOrderItem !== null) {
				if ($this->aSupplierOrderItem->isModified() || $this->aSupplierOrderItem->isNew()) {
					$affectedRows += $this->aSupplierOrderItem->save($con);
				}
				$this->setSupplierOrderItem($this->aSupplierOrderItem);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PartLotPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PartLotPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PartLotPeer::doUpdate($this, $con);
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

			if ($this->aPartVariant !== null) {
				if (!$this->aPartVariant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPartVariant->getValidationFailures());
				}
			}

			if ($this->aSupplierOrderItem !== null) {
				if (!$this->aSupplierOrderItem->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSupplierOrderItem->getValidationFailures());
				}
			}


			if (($retval = PartLotPeer::doValidate($this, $columns)) !== true) {
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
		$pos = PartLotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPartVariantId();
				break;
			case 2:
				return $this->getSupplierOrderItemId();
				break;
			case 3:
				return $this->getQuantityReceived();
				break;
			case 4:
				return $this->getQuantityRemaining();
				break;
			case 5:
				return $this->getReceivedDate();
				break;
			case 6:
				return $this->getLandedCost();
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
		$keys = PartLotPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPartVariantId(),
			$keys[2] => $this->getSupplierOrderItemId(),
			$keys[3] => $this->getQuantityReceived(),
			$keys[4] => $this->getQuantityRemaining(),
			$keys[5] => $this->getReceivedDate(),
			$keys[6] => $this->getLandedCost(),
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
		$pos = PartLotPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPartVariantId($value);
				break;
			case 2:
				$this->setSupplierOrderItemId($value);
				break;
			case 3:
				$this->setQuantityReceived($value);
				break;
			case 4:
				$this->setQuantityRemaining($value);
				break;
			case 5:
				$this->setReceivedDate($value);
				break;
			case 6:
				$this->setLandedCost($value);
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
		$keys = PartLotPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPartVariantId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSupplierOrderItemId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setQuantityReceived($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setQuantityRemaining($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setReceivedDate($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setLandedCost($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PartLotPeer::DATABASE_NAME);

		if ($this->isColumnModified(PartLotPeer::ID)) $criteria->add(PartLotPeer::ID, $this->id);
		if ($this->isColumnModified(PartLotPeer::PART_VARIANT_ID)) $criteria->add(PartLotPeer::PART_VARIANT_ID, $this->part_variant_id);
		if ($this->isColumnModified(PartLotPeer::SUPPLIER_ORDER_ITEM_ID)) $criteria->add(PartLotPeer::SUPPLIER_ORDER_ITEM_ID, $this->supplier_order_item_id);
		if ($this->isColumnModified(PartLotPeer::QUANTITY_RECEIVED)) $criteria->add(PartLotPeer::QUANTITY_RECEIVED, $this->quantity_received);
		if ($this->isColumnModified(PartLotPeer::QUANTITY_REMAINING)) $criteria->add(PartLotPeer::QUANTITY_REMAINING, $this->quantity_remaining);
		if ($this->isColumnModified(PartLotPeer::RECEIVED_DATE)) $criteria->add(PartLotPeer::RECEIVED_DATE, $this->received_date);
		if ($this->isColumnModified(PartLotPeer::LANDED_COST)) $criteria->add(PartLotPeer::LANDED_COST, $this->landed_cost);

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
		$criteria = new Criteria(PartLotPeer::DATABASE_NAME);

		$criteria->add(PartLotPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of PartLot (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPartVariantId($this->part_variant_id);

		$copyObj->setSupplierOrderItemId($this->supplier_order_item_id);

		$copyObj->setQuantityReceived($this->quantity_received);

		$copyObj->setQuantityRemaining($this->quantity_remaining);

		$copyObj->setReceivedDate($this->received_date);

		$copyObj->setLandedCost($this->landed_cost);


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
	 * @return     PartLot Clone of current object.
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
	 * @return     PartLotPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PartLotPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a PartVariant object.
	 *
	 * @param      PartVariant $v
	 * @return     PartLot The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setPartVariant(PartVariant $v = null)
	{
		if ($v === null) {
			$this->setPartVariantId(NULL);
		} else {
			$this->setPartVariantId($v->getId());
		}

		$this->aPartVariant = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the PartVariant object, it will not be re-added.
		if ($v !== null) {
			$v->addPartLot($this);
		}

		return $this;
	}


	/**
	 * Get the associated PartVariant object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     PartVariant The associated PartVariant object.
	 * @throws     PropelException
	 */
	public function getPartVariant(PropelPDO $con = null)
	{
		if ($this->aPartVariant === null && ($this->part_variant_id !== null)) {
			$c = new Criteria(PartVariantPeer::DATABASE_NAME);
			$c->add(PartVariantPeer::ID, $this->part_variant_id);
			$this->aPartVariant = PartVariantPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aPartVariant->addPartLots($this);
			 */
		}
		return $this->aPartVariant;
	}

	/**
	 * Declares an association between this object and a SupplierOrderItem object.
	 *
	 * @param      SupplierOrderItem $v
	 * @return     PartLot The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setSupplierOrderItem(SupplierOrderItem $v = null)
	{
		if ($v === null) {
			$this->setSupplierOrderItemId(NULL);
		} else {
			$this->setSupplierOrderItemId($v->getId());
		}

		$this->aSupplierOrderItem = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the SupplierOrderItem object, it will not be re-added.
		if ($v !== null) {
			$v->addPartLot($this);
		}

		return $this;
	}


	/**
	 * Get the associated SupplierOrderItem object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     SupplierOrderItem The associated SupplierOrderItem object.
	 * @throws     PropelException
	 */
	public function getSupplierOrderItem(PropelPDO $con = null)
	{
		if ($this->aSupplierOrderItem === null && ($this->supplier_order_item_id !== null)) {
			$c = new Criteria(SupplierOrderItemPeer::DATABASE_NAME);
			$c->add(SupplierOrderItemPeer::ID, $this->supplier_order_item_id);
			$this->aSupplierOrderItem = SupplierOrderItemPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aSupplierOrderItem->addPartLots($this);
			 */
		}
		return $this->aSupplierOrderItem;
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

			$this->aPartVariant = null;
			$this->aSupplierOrderItem = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePartLot:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePartLot::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePartLot
