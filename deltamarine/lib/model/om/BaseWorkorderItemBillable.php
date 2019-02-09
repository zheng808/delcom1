<?php

/**
 * Base class that represents a row from the 'workorder_item_billable' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorderItemBillable extends BaseObject  implements Persistent {


  const PEER = 'WorkorderItemBillablePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkorderItemBillablePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the workorder_item_id field.
	 * @var        int
	 */
	protected $workorder_item_id;

	/**
	 * The value for the manufacturer_id field.
	 * @var        int
	 */
	protected $manufacturer_id;

	/**
	 * The value for the supplier_id field.
	 * @var        int
	 */
	protected $supplier_id;

	/**
	 * The value for the manufacturer_parts_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $manufacturer_parts_percent;

	/**
	 * The value for the manufacturer_labour_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $manufacturer_labour_percent;

	/**
	 * The value for the supplier_parts_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $supplier_parts_percent;

	/**
	 * The value for the supplier_labour_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $supplier_labour_percent;

	/**
	 * The value for the in_house_parts_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $in_house_parts_percent;

	/**
	 * The value for the in_house_labour_percent field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $in_house_labour_percent;

	/**
	 * The value for the customer_parts_percent field.
	 * Note: this column has a database default value of: 100
	 * @var        int
	 */
	protected $customer_parts_percent;

	/**
	 * The value for the customer_labour_percent field.
	 * Note: this column has a database default value of: 100
	 * @var        int
	 */
	protected $customer_labour_percent;

	/**
	 * The value for the recurse field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $recurse;

	/**
	 * @var        WorkorderItem
	 */
	protected $aWorkorderItem;

	/**
	 * @var        Manufacturer
	 */
	protected $aManufacturer;

	/**
	 * @var        Supplier
	 */
	protected $aSupplier;

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
	 * Initializes internal state of BaseWorkorderItemBillable object.
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
		$this->manufacturer_parts_percent = 0;
		$this->manufacturer_labour_percent = 0;
		$this->supplier_parts_percent = 0;
		$this->supplier_labour_percent = 0;
		$this->in_house_parts_percent = 0;
		$this->in_house_labour_percent = 0;
		$this->customer_parts_percent = 100;
		$this->customer_labour_percent = 100;
		$this->recurse = true;
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
	 * Get the [workorder_item_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderItemId()
	{
		return $this->workorder_item_id;
	}

	/**
	 * Get the [manufacturer_id] column value.
	 * 
	 * @return     int
	 */
	public function getManufacturerId()
	{
		return $this->manufacturer_id;
	}

	/**
	 * Get the [supplier_id] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierId()
	{
		return $this->supplier_id;
	}

	/**
	 * Get the [manufacturer_parts_percent] column value.
	 * 
	 * @return     int
	 */
	public function getManufacturerPartsPercent()
	{
		return $this->manufacturer_parts_percent;
	}

	/**
	 * Get the [manufacturer_labour_percent] column value.
	 * 
	 * @return     int
	 */
	public function getManufacturerLabourPercent()
	{
		return $this->manufacturer_labour_percent;
	}

	/**
	 * Get the [supplier_parts_percent] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierPartsPercent()
	{
		return $this->supplier_parts_percent;
	}

	/**
	 * Get the [supplier_labour_percent] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierLabourPercent()
	{
		return $this->supplier_labour_percent;
	}

	/**
	 * Get the [in_house_parts_percent] column value.
	 * 
	 * @return     int
	 */
	public function getInHousePartsPercent()
	{
		return $this->in_house_parts_percent;
	}

	/**
	 * Get the [in_house_labour_percent] column value.
	 * 
	 * @return     int
	 */
	public function getInHouseLabourPercent()
	{
		return $this->in_house_labour_percent;
	}

	/**
	 * Get the [customer_parts_percent] column value.
	 * 
	 * @return     int
	 */
	public function getCustomerPartsPercent()
	{
		return $this->customer_parts_percent;
	}

	/**
	 * Get the [customer_labour_percent] column value.
	 * 
	 * @return     int
	 */
	public function getCustomerLabourPercent()
	{
		return $this->customer_labour_percent;
	}

	/**
	 * Get the [recurse] column value.
	 * 
	 * @return     boolean
	 */
	public function getRecurse()
	{
		return $this->recurse;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [workorder_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setWorkorderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_item_id !== $v) {
			$this->workorder_item_id = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::WORKORDER_ITEM_ID;
		}

		if ($this->aWorkorderItem !== null && $this->aWorkorderItem->getId() !== $v) {
			$this->aWorkorderItem = null;
		}

		return $this;
	} // setWorkorderItemId()

	/**
	 * Set the value of [manufacturer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setManufacturerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->manufacturer_id !== $v) {
			$this->manufacturer_id = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::MANUFACTURER_ID;
		}

		if ($this->aManufacturer !== null && $this->aManufacturer->getId() !== $v) {
			$this->aManufacturer = null;
		}

		return $this;
	} // setManufacturerId()

	/**
	 * Set the value of [supplier_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setSupplierId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_id !== $v) {
			$this->supplier_id = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::SUPPLIER_ID;
		}

		if ($this->aSupplier !== null && $this->aSupplier->getId() !== $v) {
			$this->aSupplier = null;
		}

		return $this;
	} // setSupplierId()

	/**
	 * Set the value of [manufacturer_parts_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setManufacturerPartsPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->manufacturer_parts_percent !== $v || $v === 0) {
			$this->manufacturer_parts_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::MANUFACTURER_PARTS_PERCENT;
		}

		return $this;
	} // setManufacturerPartsPercent()

	/**
	 * Set the value of [manufacturer_labour_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setManufacturerLabourPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->manufacturer_labour_percent !== $v || $v === 0) {
			$this->manufacturer_labour_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::MANUFACTURER_LABOUR_PERCENT;
		}

		return $this;
	} // setManufacturerLabourPercent()

	/**
	 * Set the value of [supplier_parts_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setSupplierPartsPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_parts_percent !== $v || $v === 0) {
			$this->supplier_parts_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::SUPPLIER_PARTS_PERCENT;
		}

		return $this;
	} // setSupplierPartsPercent()

	/**
	 * Set the value of [supplier_labour_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setSupplierLabourPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_labour_percent !== $v || $v === 0) {
			$this->supplier_labour_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::SUPPLIER_LABOUR_PERCENT;
		}

		return $this;
	} // setSupplierLabourPercent()

	/**
	 * Set the value of [in_house_parts_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setInHousePartsPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->in_house_parts_percent !== $v || $v === 0) {
			$this->in_house_parts_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::IN_HOUSE_PARTS_PERCENT;
		}

		return $this;
	} // setInHousePartsPercent()

	/**
	 * Set the value of [in_house_labour_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setInHouseLabourPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->in_house_labour_percent !== $v || $v === 0) {
			$this->in_house_labour_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::IN_HOUSE_LABOUR_PERCENT;
		}

		return $this;
	} // setInHouseLabourPercent()

	/**
	 * Set the value of [customer_parts_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setCustomerPartsPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_parts_percent !== $v || $v === 100) {
			$this->customer_parts_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT;
		}

		return $this;
	} // setCustomerPartsPercent()

	/**
	 * Set the value of [customer_labour_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setCustomerLabourPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_labour_percent !== $v || $v === 100) {
			$this->customer_labour_percent = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT;
		}

		return $this;
	} // setCustomerLabourPercent()

	/**
	 * Set the value of [recurse] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 */
	public function setRecurse($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->recurse !== $v || $v === true) {
			$this->recurse = $v;
			$this->modifiedColumns[] = WorkorderItemBillablePeer::RECURSE;
		}

		return $this;
	} // setRecurse()

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
			if (array_diff($this->modifiedColumns, array(WorkorderItemBillablePeer::MANUFACTURER_PARTS_PERCENT,WorkorderItemBillablePeer::MANUFACTURER_LABOUR_PERCENT,WorkorderItemBillablePeer::SUPPLIER_PARTS_PERCENT,WorkorderItemBillablePeer::SUPPLIER_LABOUR_PERCENT,WorkorderItemBillablePeer::IN_HOUSE_PARTS_PERCENT,WorkorderItemBillablePeer::IN_HOUSE_LABOUR_PERCENT,WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT,WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT,WorkorderItemBillablePeer::RECURSE))) {
				return false;
			}

			if ($this->manufacturer_parts_percent !== 0) {
				return false;
			}

			if ($this->manufacturer_labour_percent !== 0) {
				return false;
			}

			if ($this->supplier_parts_percent !== 0) {
				return false;
			}

			if ($this->supplier_labour_percent !== 0) {
				return false;
			}

			if ($this->in_house_parts_percent !== 0) {
				return false;
			}

			if ($this->in_house_labour_percent !== 0) {
				return false;
			}

			if ($this->customer_parts_percent !== 100) {
				return false;
			}

			if ($this->customer_labour_percent !== 100) {
				return false;
			}

			if ($this->recurse !== true) {
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
			$this->workorder_item_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->manufacturer_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->supplier_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->manufacturer_parts_percent = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->manufacturer_labour_percent = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->supplier_parts_percent = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->supplier_labour_percent = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->in_house_parts_percent = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->in_house_labour_percent = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->customer_parts_percent = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->customer_labour_percent = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->recurse = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = WorkorderItemBillablePeer::NUM_COLUMNS - WorkorderItemBillablePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WorkorderItemBillable object", $e);
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

		if ($this->aWorkorderItem !== null && $this->workorder_item_id !== $this->aWorkorderItem->getId()) {
			$this->aWorkorderItem = null;
		}
		if ($this->aManufacturer !== null && $this->manufacturer_id !== $this->aManufacturer->getId()) {
			$this->aManufacturer = null;
		}
		if ($this->aSupplier !== null && $this->supplier_id !== $this->aSupplier->getId()) {
			$this->aSupplier = null;
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
			$con = Propel::getConnection(WorkorderItemBillablePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkorderItemBillablePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWorkorderItem = null;
			$this->aManufacturer = null;
			$this->aSupplier = null;
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

    foreach (sfMixer::getCallables('BaseWorkorderItemBillable:delete:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderItemBillablePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			WorkorderItemBillablePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseWorkorderItemBillable:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseWorkorderItemBillable:save:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderItemBillablePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseWorkorderItemBillable:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			WorkorderItemBillablePeer::addInstanceToPool($this);
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

			if ($this->aWorkorderItem !== null) {
				if ($this->aWorkorderItem->isModified() || $this->aWorkorderItem->isNew()) {
					$affectedRows += $this->aWorkorderItem->save($con);
				}
				$this->setWorkorderItem($this->aWorkorderItem);
			}

			if ($this->aManufacturer !== null) {
				if ($this->aManufacturer->isModified() || $this->aManufacturer->isNew()) {
					$affectedRows += $this->aManufacturer->save($con);
				}
				$this->setManufacturer($this->aManufacturer);
			}

			if ($this->aSupplier !== null) {
				if ($this->aSupplier->isModified() || $this->aSupplier->isNew()) {
					$affectedRows += $this->aSupplier->save($con);
				}
				$this->setSupplier($this->aSupplier);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WorkorderItemBillablePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WorkorderItemBillablePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WorkorderItemBillablePeer::doUpdate($this, $con);
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

			if ($this->aWorkorderItem !== null) {
				if (!$this->aWorkorderItem->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkorderItem->getValidationFailures());
				}
			}

			if ($this->aManufacturer !== null) {
				if (!$this->aManufacturer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aManufacturer->getValidationFailures());
				}
			}

			if ($this->aSupplier !== null) {
				if (!$this->aSupplier->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSupplier->getValidationFailures());
				}
			}


			if (($retval = WorkorderItemBillablePeer::doValidate($this, $columns)) !== true) {
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
		$pos = WorkorderItemBillablePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getWorkorderItemId();
				break;
			case 2:
				return $this->getManufacturerId();
				break;
			case 3:
				return $this->getSupplierId();
				break;
			case 4:
				return $this->getManufacturerPartsPercent();
				break;
			case 5:
				return $this->getManufacturerLabourPercent();
				break;
			case 6:
				return $this->getSupplierPartsPercent();
				break;
			case 7:
				return $this->getSupplierLabourPercent();
				break;
			case 8:
				return $this->getInHousePartsPercent();
				break;
			case 9:
				return $this->getInHouseLabourPercent();
				break;
			case 10:
				return $this->getCustomerPartsPercent();
				break;
			case 11:
				return $this->getCustomerLabourPercent();
				break;
			case 12:
				return $this->getRecurse();
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
		$keys = WorkorderItemBillablePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWorkorderItemId(),
			$keys[2] => $this->getManufacturerId(),
			$keys[3] => $this->getSupplierId(),
			$keys[4] => $this->getManufacturerPartsPercent(),
			$keys[5] => $this->getManufacturerLabourPercent(),
			$keys[6] => $this->getSupplierPartsPercent(),
			$keys[7] => $this->getSupplierLabourPercent(),
			$keys[8] => $this->getInHousePartsPercent(),
			$keys[9] => $this->getInHouseLabourPercent(),
			$keys[10] => $this->getCustomerPartsPercent(),
			$keys[11] => $this->getCustomerLabourPercent(),
			$keys[12] => $this->getRecurse(),
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
		$pos = WorkorderItemBillablePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setWorkorderItemId($value);
				break;
			case 2:
				$this->setManufacturerId($value);
				break;
			case 3:
				$this->setSupplierId($value);
				break;
			case 4:
				$this->setManufacturerPartsPercent($value);
				break;
			case 5:
				$this->setManufacturerLabourPercent($value);
				break;
			case 6:
				$this->setSupplierPartsPercent($value);
				break;
			case 7:
				$this->setSupplierLabourPercent($value);
				break;
			case 8:
				$this->setInHousePartsPercent($value);
				break;
			case 9:
				$this->setInHouseLabourPercent($value);
				break;
			case 10:
				$this->setCustomerPartsPercent($value);
				break;
			case 11:
				$this->setCustomerLabourPercent($value);
				break;
			case 12:
				$this->setRecurse($value);
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
		$keys = WorkorderItemBillablePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWorkorderItemId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setManufacturerId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setSupplierId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setManufacturerPartsPercent($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setManufacturerLabourPercent($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setSupplierPartsPercent($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setSupplierLabourPercent($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setInHousePartsPercent($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setInHouseLabourPercent($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCustomerPartsPercent($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setCustomerLabourPercent($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setRecurse($arr[$keys[12]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WorkorderItemBillablePeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkorderItemBillablePeer::ID)) $criteria->add(WorkorderItemBillablePeer::ID, $this->id);
		if ($this->isColumnModified(WorkorderItemBillablePeer::WORKORDER_ITEM_ID)) $criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->workorder_item_id);
		if ($this->isColumnModified(WorkorderItemBillablePeer::MANUFACTURER_ID)) $criteria->add(WorkorderItemBillablePeer::MANUFACTURER_ID, $this->manufacturer_id);
		if ($this->isColumnModified(WorkorderItemBillablePeer::SUPPLIER_ID)) $criteria->add(WorkorderItemBillablePeer::SUPPLIER_ID, $this->supplier_id);
		if ($this->isColumnModified(WorkorderItemBillablePeer::MANUFACTURER_PARTS_PERCENT)) $criteria->add(WorkorderItemBillablePeer::MANUFACTURER_PARTS_PERCENT, $this->manufacturer_parts_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::MANUFACTURER_LABOUR_PERCENT)) $criteria->add(WorkorderItemBillablePeer::MANUFACTURER_LABOUR_PERCENT, $this->manufacturer_labour_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::SUPPLIER_PARTS_PERCENT)) $criteria->add(WorkorderItemBillablePeer::SUPPLIER_PARTS_PERCENT, $this->supplier_parts_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::SUPPLIER_LABOUR_PERCENT)) $criteria->add(WorkorderItemBillablePeer::SUPPLIER_LABOUR_PERCENT, $this->supplier_labour_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::IN_HOUSE_PARTS_PERCENT)) $criteria->add(WorkorderItemBillablePeer::IN_HOUSE_PARTS_PERCENT, $this->in_house_parts_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::IN_HOUSE_LABOUR_PERCENT)) $criteria->add(WorkorderItemBillablePeer::IN_HOUSE_LABOUR_PERCENT, $this->in_house_labour_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT)) $criteria->add(WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT, $this->customer_parts_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT)) $criteria->add(WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT, $this->customer_labour_percent);
		if ($this->isColumnModified(WorkorderItemBillablePeer::RECURSE)) $criteria->add(WorkorderItemBillablePeer::RECURSE, $this->recurse);

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
		$criteria = new Criteria(WorkorderItemBillablePeer::DATABASE_NAME);

		$criteria->add(WorkorderItemBillablePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of WorkorderItemBillable (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setWorkorderItemId($this->workorder_item_id);

		$copyObj->setManufacturerId($this->manufacturer_id);

		$copyObj->setSupplierId($this->supplier_id);

		$copyObj->setManufacturerPartsPercent($this->manufacturer_parts_percent);

		$copyObj->setManufacturerLabourPercent($this->manufacturer_labour_percent);

		$copyObj->setSupplierPartsPercent($this->supplier_parts_percent);

		$copyObj->setSupplierLabourPercent($this->supplier_labour_percent);

		$copyObj->setInHousePartsPercent($this->in_house_parts_percent);

		$copyObj->setInHouseLabourPercent($this->in_house_labour_percent);

		$copyObj->setCustomerPartsPercent($this->customer_parts_percent);

		$copyObj->setCustomerLabourPercent($this->customer_labour_percent);

		$copyObj->setRecurse($this->recurse);


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
	 * @return     WorkorderItemBillable Clone of current object.
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
	 * @return     WorkorderItemBillablePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkorderItemBillablePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WorkorderItem object.
	 *
	 * @param      WorkorderItem $v
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWorkorderItem(WorkorderItem $v = null)
	{
		if ($v === null) {
			$this->setWorkorderItemId(NULL);
		} else {
			$this->setWorkorderItemId($v->getId());
		}

		$this->aWorkorderItem = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WorkorderItem object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderItemBillable($this);
		}

		return $this;
	}


	/**
	 * Get the associated WorkorderItem object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     WorkorderItem The associated WorkorderItem object.
	 * @throws     PropelException
	 */
	public function getWorkorderItem(PropelPDO $con = null)
	{
		if ($this->aWorkorderItem === null && ($this->workorder_item_id !== null)) {
			$c = new Criteria(WorkorderItemPeer::DATABASE_NAME);
			$c->add(WorkorderItemPeer::ID, $this->workorder_item_id);
			$this->aWorkorderItem = WorkorderItemPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aWorkorderItem->addWorkorderItemBillables($this);
			 */
		}
		return $this->aWorkorderItem;
	}

	/**
	 * Declares an association between this object and a Manufacturer object.
	 *
	 * @param      Manufacturer $v
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setManufacturer(Manufacturer $v = null)
	{
		if ($v === null) {
			$this->setManufacturerId(NULL);
		} else {
			$this->setManufacturerId($v->getId());
		}

		$this->aManufacturer = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Manufacturer object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderItemBillable($this);
		}

		return $this;
	}


	/**
	 * Get the associated Manufacturer object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Manufacturer The associated Manufacturer object.
	 * @throws     PropelException
	 */
	public function getManufacturer(PropelPDO $con = null)
	{
		if ($this->aManufacturer === null && ($this->manufacturer_id !== null)) {
			$c = new Criteria(ManufacturerPeer::DATABASE_NAME);
			$c->add(ManufacturerPeer::ID, $this->manufacturer_id);
			$this->aManufacturer = ManufacturerPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aManufacturer->addWorkorderItemBillables($this);
			 */
		}
		return $this->aManufacturer;
	}

	/**
	 * Declares an association between this object and a Supplier object.
	 *
	 * @param      Supplier $v
	 * @return     WorkorderItemBillable The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setSupplier(Supplier $v = null)
	{
		if ($v === null) {
			$this->setSupplierId(NULL);
		} else {
			$this->setSupplierId($v->getId());
		}

		$this->aSupplier = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Supplier object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderItemBillable($this);
		}

		return $this;
	}


	/**
	 * Get the associated Supplier object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Supplier The associated Supplier object.
	 * @throws     PropelException
	 */
	public function getSupplier(PropelPDO $con = null)
	{
		if ($this->aSupplier === null && ($this->supplier_id !== null)) {
			$c = new Criteria(SupplierPeer::DATABASE_NAME);
			$c->add(SupplierPeer::ID, $this->supplier_id);
			$this->aSupplier = SupplierPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aSupplier->addWorkorderItemBillables($this);
			 */
		}
		return $this->aSupplier;
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

			$this->aWorkorderItem = null;
			$this->aManufacturer = null;
			$this->aSupplier = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseWorkorderItemBillable:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseWorkorderItemBillable::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseWorkorderItemBillable