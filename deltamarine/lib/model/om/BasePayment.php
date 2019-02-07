<?php

/**
 * Base class that represents a row from the 'payment' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePayment extends BaseObject  implements Persistent {


  const PEER = 'PaymentPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PaymentPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the customer_order_id field.
	 * @var        int
	 */
	protected $customer_order_id;

	/**
	 * The value for the workorder_id field.
	 * @var        int
	 */
	protected $workorder_id;

	/**
	 * The value for the amount field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $amount;

	/**
	 * The value for the tendered field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $tendered;

	/**
	 * The value for the change field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $change;

	/**
	 * The value for the payment_method field.
	 * @var        string
	 */
	protected $payment_method;

	/**
	 * The value for the payment_details field.
	 * @var        string
	 */
	protected $payment_details;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * @var        CustomerOrder
	 */
	protected $aCustomerOrder;

	/**
	 * @var        Workorder
	 */
	protected $aWorkorder;

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
	 * Initializes internal state of BasePayment object.
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
		$this->amount = '0';
		$this->tendered = '0';
		$this->change = '0';
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
	 * Get the [customer_order_id] column value.
	 * 
	 * @return     int
	 */
	public function getCustomerOrderId()
	{
		return $this->customer_order_id;
	}

	/**
	 * Get the [workorder_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderId()
	{
		return $this->workorder_id;
	}

	/**
	 * Get the [amount] column value.
	 * 
	 * @return     string
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Get the [tendered] column value.
	 * 
	 * @return     string
	 */
	public function getTendered()
	{
		return $this->tendered;
	}

	/**
	 * Get the [change] column value.
	 * 
	 * @return     string
	 */
	public function getChange()
	{
		return $this->change;
	}

	/**
	 * Get the [payment_method] column value.
	 * 
	 * @return     string
	 */
	public function getPaymentMethod()
	{
		return $this->payment_method;
	}

	/**
	 * Get the [payment_details] column value.
	 * 
	 * @return     string
	 */
	public function getPaymentDetails()
	{
		return $this->payment_details;
	}

	/**
	 * Get the [optionally formatted] temporal [created_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->created_at === null) {
			return null;
		}


		if ($this->created_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->created_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
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
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PaymentPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [customer_order_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setCustomerOrderId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_order_id !== $v) {
			$this->customer_order_id = $v;
			$this->modifiedColumns[] = PaymentPeer::CUSTOMER_ORDER_ID;
		}

		if ($this->aCustomerOrder !== null && $this->aCustomerOrder->getId() !== $v) {
			$this->aCustomerOrder = null;
		}

		return $this;
	} // setCustomerOrderId()

	/**
	 * Set the value of [workorder_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setWorkorderId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_id !== $v) {
			$this->workorder_id = $v;
			$this->modifiedColumns[] = PaymentPeer::WORKORDER_ID;
		}

		if ($this->aWorkorder !== null && $this->aWorkorder->getId() !== $v) {
			$this->aWorkorder = null;
		}

		return $this;
	} // setWorkorderId()

	/**
	 * Set the value of [amount] column.
	 * 
	 * @param      string $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount !== $v || $v === '0') {
			$this->amount = $v;
			$this->modifiedColumns[] = PaymentPeer::AMOUNT;
		}

		return $this;
	} // setAmount()

	/**
	 * Set the value of [tendered] column.
	 * 
	 * @param      string $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setTendered($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->tendered !== $v || $v === '0') {
			$this->tendered = $v;
			$this->modifiedColumns[] = PaymentPeer::TENDERED;
		}

		return $this;
	} // setTendered()

	/**
	 * Set the value of [change] column.
	 * 
	 * @param      string $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setChange($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->change !== $v || $v === '0') {
			$this->change = $v;
			$this->modifiedColumns[] = PaymentPeer::CHANGE;
		}

		return $this;
	} // setChange()

	/**
	 * Set the value of [payment_method] column.
	 * 
	 * @param      string $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setPaymentMethod($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->payment_method !== $v) {
			$this->payment_method = $v;
			$this->modifiedColumns[] = PaymentPeer::PAYMENT_METHOD;
		}

		return $this;
	} // setPaymentMethod()

	/**
	 * Set the value of [payment_details] column.
	 * 
	 * @param      string $v new value
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setPaymentDetails($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->payment_details !== $v) {
			$this->payment_details = $v;
			$this->modifiedColumns[] = PaymentPeer::PAYMENT_DETAILS;
		}

		return $this;
	} // setPaymentDetails()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Payment The current object (for fluent API support)
	 */
	public function setCreatedAt($v)
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

		if ( $this->created_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = PaymentPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

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
			if (array_diff($this->modifiedColumns, array(PaymentPeer::AMOUNT,PaymentPeer::TENDERED,PaymentPeer::CHANGE))) {
				return false;
			}

			if ($this->amount !== '0') {
				return false;
			}

			if ($this->tendered !== '0') {
				return false;
			}

			if ($this->change !== '0') {
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
			$this->customer_order_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->workorder_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->amount = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->tendered = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->change = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->payment_method = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->payment_details = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->created_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = PaymentPeer::NUM_COLUMNS - PaymentPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Payment object", $e);
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

		if ($this->aCustomerOrder !== null && $this->customer_order_id !== $this->aCustomerOrder->getId()) {
			$this->aCustomerOrder = null;
		}
		if ($this->aWorkorder !== null && $this->workorder_id !== $this->aWorkorder->getId()) {
			$this->aWorkorder = null;
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
			$con = Propel::getConnection(PaymentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PaymentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aCustomerOrder = null;
			$this->aWorkorder = null;
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

    foreach (sfMixer::getCallables('BasePayment:delete:pre') as $callable)
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
			$con = Propel::getConnection(PaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PaymentPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePayment:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePayment:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(PaymentPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(PaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePayment:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PaymentPeer::addInstanceToPool($this);
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

			if ($this->aCustomerOrder !== null) {
				if ($this->aCustomerOrder->isModified() || $this->aCustomerOrder->isNew()) {
					$affectedRows += $this->aCustomerOrder->save($con);
				}
				$this->setCustomerOrder($this->aCustomerOrder);
			}

			if ($this->aWorkorder !== null) {
				if ($this->aWorkorder->isModified() || $this->aWorkorder->isNew()) {
					$affectedRows += $this->aWorkorder->save($con);
				}
				$this->setWorkorder($this->aWorkorder);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PaymentPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PaymentPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PaymentPeer::doUpdate($this, $con);
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

			if ($this->aCustomerOrder !== null) {
				if (!$this->aCustomerOrder->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCustomerOrder->getValidationFailures());
				}
			}

			if ($this->aWorkorder !== null) {
				if (!$this->aWorkorder->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkorder->getValidationFailures());
				}
			}


			if (($retval = PaymentPeer::doValidate($this, $columns)) !== true) {
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
		$pos = PaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCustomerOrderId();
				break;
			case 2:
				return $this->getWorkorderId();
				break;
			case 3:
				return $this->getAmount();
				break;
			case 4:
				return $this->getTendered();
				break;
			case 5:
				return $this->getChange();
				break;
			case 6:
				return $this->getPaymentMethod();
				break;
			case 7:
				return $this->getPaymentDetails();
				break;
			case 8:
				return $this->getCreatedAt();
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
		$keys = PaymentPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCustomerOrderId(),
			$keys[2] => $this->getWorkorderId(),
			$keys[3] => $this->getAmount(),
			$keys[4] => $this->getTendered(),
			$keys[5] => $this->getChange(),
			$keys[6] => $this->getPaymentMethod(),
			$keys[7] => $this->getPaymentDetails(),
			$keys[8] => $this->getCreatedAt(),
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
		$pos = PaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCustomerOrderId($value);
				break;
			case 2:
				$this->setWorkorderId($value);
				break;
			case 3:
				$this->setAmount($value);
				break;
			case 4:
				$this->setTendered($value);
				break;
			case 5:
				$this->setChange($value);
				break;
			case 6:
				$this->setPaymentMethod($value);
				break;
			case 7:
				$this->setPaymentDetails($value);
				break;
			case 8:
				$this->setCreatedAt($value);
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
		$keys = PaymentPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCustomerOrderId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setWorkorderId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setAmount($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setTendered($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setChange($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPaymentMethod($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setPaymentDetails($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PaymentPeer::DATABASE_NAME);

		if ($this->isColumnModified(PaymentPeer::ID)) $criteria->add(PaymentPeer::ID, $this->id);
		if ($this->isColumnModified(PaymentPeer::CUSTOMER_ORDER_ID)) $criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->customer_order_id);
		if ($this->isColumnModified(PaymentPeer::WORKORDER_ID)) $criteria->add(PaymentPeer::WORKORDER_ID, $this->workorder_id);
		if ($this->isColumnModified(PaymentPeer::AMOUNT)) $criteria->add(PaymentPeer::AMOUNT, $this->amount);
		if ($this->isColumnModified(PaymentPeer::TENDERED)) $criteria->add(PaymentPeer::TENDERED, $this->tendered);
		if ($this->isColumnModified(PaymentPeer::CHANGE)) $criteria->add(PaymentPeer::CHANGE, $this->change);
		if ($this->isColumnModified(PaymentPeer::PAYMENT_METHOD)) $criteria->add(PaymentPeer::PAYMENT_METHOD, $this->payment_method);
		if ($this->isColumnModified(PaymentPeer::PAYMENT_DETAILS)) $criteria->add(PaymentPeer::PAYMENT_DETAILS, $this->payment_details);
		if ($this->isColumnModified(PaymentPeer::CREATED_AT)) $criteria->add(PaymentPeer::CREATED_AT, $this->created_at);

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
		$criteria = new Criteria(PaymentPeer::DATABASE_NAME);

		$criteria->add(PaymentPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Payment (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCustomerOrderId($this->customer_order_id);

		$copyObj->setWorkorderId($this->workorder_id);

		$copyObj->setAmount($this->amount);

		$copyObj->setTendered($this->tendered);

		$copyObj->setChange($this->change);

		$copyObj->setPaymentMethod($this->payment_method);

		$copyObj->setPaymentDetails($this->payment_details);

		$copyObj->setCreatedAt($this->created_at);


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
	 * @return     Payment Clone of current object.
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
	 * @return     PaymentPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PaymentPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a CustomerOrder object.
	 *
	 * @param      CustomerOrder $v
	 * @return     Payment The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setCustomerOrder(CustomerOrder $v = null)
	{
		if ($v === null) {
			$this->setCustomerOrderId(NULL);
		} else {
			$this->setCustomerOrderId($v->getId());
		}

		$this->aCustomerOrder = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the CustomerOrder object, it will not be re-added.
		if ($v !== null) {
			$v->addPayment($this);
		}

		return $this;
	}


	/**
	 * Get the associated CustomerOrder object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     CustomerOrder The associated CustomerOrder object.
	 * @throws     PropelException
	 */
	public function getCustomerOrder(PropelPDO $con = null)
	{
		if ($this->aCustomerOrder === null && ($this->customer_order_id !== null)) {
			$c = new Criteria(CustomerOrderPeer::DATABASE_NAME);
			$c->add(CustomerOrderPeer::ID, $this->customer_order_id);
			$this->aCustomerOrder = CustomerOrderPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aCustomerOrder->addPayments($this);
			 */
		}
		return $this->aCustomerOrder;
	}

	/**
	 * Declares an association between this object and a Workorder object.
	 *
	 * @param      Workorder $v
	 * @return     Payment The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWorkorder(Workorder $v = null)
	{
		if ($v === null) {
			$this->setWorkorderId(NULL);
		} else {
			$this->setWorkorderId($v->getId());
		}

		$this->aWorkorder = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Workorder object, it will not be re-added.
		if ($v !== null) {
			$v->addPayment($this);
		}

		return $this;
	}


	/**
	 * Get the associated Workorder object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Workorder The associated Workorder object.
	 * @throws     PropelException
	 */
	public function getWorkorder(PropelPDO $con = null)
	{
		if ($this->aWorkorder === null && ($this->workorder_id !== null)) {
			$c = new Criteria(WorkorderPeer::DATABASE_NAME);
			$c->add(WorkorderPeer::ID, $this->workorder_id);
			$this->aWorkorder = WorkorderPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aWorkorder->addPayments($this);
			 */
		}
		return $this->aWorkorder;
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

			$this->aCustomerOrder = null;
			$this->aWorkorder = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePayment:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePayment::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePayment
