<?php

/**
 * Base class that represents a row from the 'part' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePart extends BaseObject  implements Persistent {


  const PEER = 'PartPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PartPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the part_category_id field.
	 * @var        int
	 */
	protected $part_category_id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the description field.
	 * @var        string
	 */
	protected $description;

	/**
	 * The value for the has_serial_number field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $has_serial_number;

	/**
	 * The value for the is_multisku field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_multisku;

	/**
	 * The value for the manufacturer_id field.
	 * @var        int
	 */
	protected $manufacturer_id;

	/**
	 * The value for the active field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $active;

	/**
	 * The value for the origin field.
	 * @var        string
	 */
	protected $origin;

	/**
	 * @var        PartCategory
	 */
	protected $aPartCategory;

	/**
	 * @var        Manufacturer
	 */
	protected $aManufacturer;

	/**
	 * @var        array PartOption[] Collection to store aggregation of PartOption objects.
	 */
	protected $collPartOptions;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartOptions.
	 */
	private $lastPartOptionCriteria = null;

	/**
	 * @var        array PartVariant[] Collection to store aggregation of PartVariant objects.
	 */
	protected $collPartVariants;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartVariants.
	 */
	private $lastPartVariantCriteria = null;

	/**
	 * @var        array PartPhoto[] Collection to store aggregation of PartPhoto objects.
	 */
	protected $collPartPhotos;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartPhotos.
	 */
	private $lastPartPhotoCriteria = null;

	/**
	 * @var        array PartFile[] Collection to store aggregation of PartFile objects.
	 */
	protected $collPartFiles;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartFiles.
	 */
	private $lastPartFileCriteria = null;

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
	 * Initializes internal state of BasePart object.
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
		$this->has_serial_number = false;
		$this->is_multisku = false;
		$this->active = true;
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
	 * Get the [part_category_id] column value.
	 * 
	 * @return     int
	 */
	public function getPartCategoryId()
	{
		return $this->part_category_id;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return     string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the [description] column value.
	 * 
	 * @return     string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get the [has_serial_number] column value.
	 * 
	 * @return     boolean
	 */
	public function getHasSerialNumber()
	{
		return $this->has_serial_number;
	}

	/**
	 * Get the [is_multisku] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsMultisku()
	{
		return $this->is_multisku;
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
	 * Get the [active] column value.
	 * 
	 * @return     boolean
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Get the [origin] column value.
	 * 
	 * @return     string
	 */
	public function getOrigin()
	{
		return $this->origin;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PartPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [part_category_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setPartCategoryId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_category_id !== $v) {
			$this->part_category_id = $v;
			$this->modifiedColumns[] = PartPeer::PART_CATEGORY_ID;
		}

		if ($this->aPartCategory !== null && $this->aPartCategory->getId() !== $v) {
			$this->aPartCategory = null;
		}

		return $this;
	} // setPartCategoryId()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = PartPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Set the value of [description] column.
	 * 
	 * @param      string $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = PartPeer::DESCRIPTION;
		}

		return $this;
	} // setDescription()

	/**
	 * Set the value of [has_serial_number] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setHasSerialNumber($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->has_serial_number !== $v || $v === false) {
			$this->has_serial_number = $v;
			$this->modifiedColumns[] = PartPeer::HAS_SERIAL_NUMBER;
		}

		return $this;
	} // setHasSerialNumber()

	/**
	 * Set the value of [is_multisku] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setIsMultisku($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_multisku !== $v || $v === false) {
			$this->is_multisku = $v;
			$this->modifiedColumns[] = PartPeer::IS_MULTISKU;
		}

		return $this;
	} // setIsMultisku()

	/**
	 * Set the value of [manufacturer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setManufacturerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->manufacturer_id !== $v) {
			$this->manufacturer_id = $v;
			$this->modifiedColumns[] = PartPeer::MANUFACTURER_ID;
		}

		if ($this->aManufacturer !== null && $this->aManufacturer->getId() !== $v) {
			$this->aManufacturer = null;
		}

		return $this;
	} // setManufacturerId()

	/**
	 * Set the value of [active] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setActive($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->active !== $v || $v === true) {
			$this->active = $v;
			$this->modifiedColumns[] = PartPeer::ACTIVE;
		}

		return $this;
	} // setActive()

	/**
	 * Set the value of [origin] column.
	 * 
	 * @param      string $v new value
	 * @return     Part The current object (for fluent API support)
	 */
	public function setOrigin($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->origin !== $v) {
			$this->origin = $v;
			$this->modifiedColumns[] = PartPeer::ORIGIN;
		}

		return $this;
	} // setOrigin()

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
			if (array_diff($this->modifiedColumns, array(PartPeer::HAS_SERIAL_NUMBER,PartPeer::IS_MULTISKU,PartPeer::ACTIVE))) {
				return false;
			}

			if ($this->has_serial_number !== false) {
				return false;
			}

			if ($this->is_multisku !== false) {
				return false;
			}

			if ($this->active !== true) {
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
			$this->part_category_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->description = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->has_serial_number = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->is_multisku = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->manufacturer_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->active = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->origin = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 9; // 9 = PartPeer::NUM_COLUMNS - PartPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Part object", $e);
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

		if ($this->aPartCategory !== null && $this->part_category_id !== $this->aPartCategory->getId()) {
			$this->aPartCategory = null;
		}
		if ($this->aManufacturer !== null && $this->manufacturer_id !== $this->aManufacturer->getId()) {
			$this->aManufacturer = null;
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
			$con = Propel::getConnection(PartPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PartPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aPartCategory = null;
			$this->aManufacturer = null;
			$this->collPartOptions = null;
			$this->lastPartOptionCriteria = null;

			$this->collPartVariants = null;
			$this->lastPartVariantCriteria = null;

			$this->collPartPhotos = null;
			$this->lastPartPhotoCriteria = null;

			$this->collPartFiles = null;
			$this->lastPartFileCriteria = null;

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

    foreach (sfMixer::getCallables('BasePart:delete:pre') as $callable)
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
			$con = Propel::getConnection(PartPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PartPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePart:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePart:save:pre') as $callable)
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
			$con = Propel::getConnection(PartPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePart:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PartPeer::addInstanceToPool($this);
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

			if ($this->aPartCategory !== null) {
				if ($this->aPartCategory->isModified() || $this->aPartCategory->isNew()) {
					$affectedRows += $this->aPartCategory->save($con);
				}
				$this->setPartCategory($this->aPartCategory);
			}

			if ($this->aManufacturer !== null) {
				if ($this->aManufacturer->isModified() || $this->aManufacturer->isNew()) {
					$affectedRows += $this->aManufacturer->save($con);
				}
				$this->setManufacturer($this->aManufacturer);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PartPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PartPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PartPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPartOptions !== null) {
				foreach ($this->collPartOptions as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartVariants !== null) {
				foreach ($this->collPartVariants as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartPhotos !== null) {
				foreach ($this->collPartPhotos as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartFiles !== null) {
				foreach ($this->collPartFiles as $referrerFK) {
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

			if ($this->aPartCategory !== null) {
				if (!$this->aPartCategory->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPartCategory->getValidationFailures());
				}
			}

			if ($this->aManufacturer !== null) {
				if (!$this->aManufacturer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aManufacturer->getValidationFailures());
				}
			}


			if (($retval = PartPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPartOptions !== null) {
					foreach ($this->collPartOptions as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartVariants !== null) {
					foreach ($this->collPartVariants as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartPhotos !== null) {
					foreach ($this->collPartPhotos as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartFiles !== null) {
					foreach ($this->collPartFiles as $referrerFK) {
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
		$pos = PartPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPartCategoryId();
				break;
			case 2:
				return $this->getName();
				break;
			case 3:
				return $this->getDescription();
				break;
			case 4:
				return $this->getHasSerialNumber();
				break;
			case 5:
				return $this->getIsMultisku();
				break;
			case 6:
				return $this->getManufacturerId();
				break;
			case 7:
				return $this->getActive();
				break;
			case 8:
				return $this->getOrigin();
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
		$keys = PartPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPartCategoryId(),
			$keys[2] => $this->getName(),
			$keys[3] => $this->getDescription(),
			$keys[4] => $this->getHasSerialNumber(),
			$keys[5] => $this->getIsMultisku(),
			$keys[6] => $this->getManufacturerId(),
			$keys[7] => $this->getActive(),
			$keys[8] => $this->getOrigin(),
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
		$pos = PartPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPartCategoryId($value);
				break;
			case 2:
				$this->setName($value);
				break;
			case 3:
				$this->setDescription($value);
				break;
			case 4:
				$this->setHasSerialNumber($value);
				break;
			case 5:
				$this->setIsMultisku($value);
				break;
			case 6:
				$this->setManufacturerId($value);
				break;
			case 7:
				$this->setActive($value);
				break;
			case 8:
				$this->setOrigin($value);
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
		$keys = PartPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPartCategoryId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDescription($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setHasSerialNumber($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setIsMultisku($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setManufacturerId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setActive($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setOrigin($arr[$keys[8]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PartPeer::DATABASE_NAME);

		if ($this->isColumnModified(PartPeer::ID)) $criteria->add(PartPeer::ID, $this->id);
		if ($this->isColumnModified(PartPeer::PART_CATEGORY_ID)) $criteria->add(PartPeer::PART_CATEGORY_ID, $this->part_category_id);
		if ($this->isColumnModified(PartPeer::NAME)) $criteria->add(PartPeer::NAME, $this->name);
		if ($this->isColumnModified(PartPeer::DESCRIPTION)) $criteria->add(PartPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(PartPeer::HAS_SERIAL_NUMBER)) $criteria->add(PartPeer::HAS_SERIAL_NUMBER, $this->has_serial_number);
		if ($this->isColumnModified(PartPeer::IS_MULTISKU)) $criteria->add(PartPeer::IS_MULTISKU, $this->is_multisku);
		if ($this->isColumnModified(PartPeer::MANUFACTURER_ID)) $criteria->add(PartPeer::MANUFACTURER_ID, $this->manufacturer_id);
		if ($this->isColumnModified(PartPeer::ACTIVE)) $criteria->add(PartPeer::ACTIVE, $this->active);
		if ($this->isColumnModified(PartPeer::ORIGIN)) $criteria->add(PartPeer::ORIGIN, $this->origin);

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
		$criteria = new Criteria(PartPeer::DATABASE_NAME);

		$criteria->add(PartPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Part (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPartCategoryId($this->part_category_id);

		$copyObj->setName($this->name);

		$copyObj->setDescription($this->description);

		$copyObj->setHasSerialNumber($this->has_serial_number);

		$copyObj->setIsMultisku($this->is_multisku);

		$copyObj->setManufacturerId($this->manufacturer_id);

		$copyObj->setActive($this->active);

		$copyObj->setOrigin($this->origin);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPartOptions() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartOption($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartVariants() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartVariant($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartPhotos() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartPhoto($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartFiles() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartFile($relObj->copy($deepCopy));
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
	 * @return     Part Clone of current object.
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
	 * @return     PartPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PartPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a PartCategory object.
	 *
	 * @param      PartCategory $v
	 * @return     Part The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setPartCategory(PartCategory $v = null)
	{
		if ($v === null) {
			$this->setPartCategoryId(NULL);
		} else {
			$this->setPartCategoryId($v->getId());
		}

		$this->aPartCategory = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the PartCategory object, it will not be re-added.
		if ($v !== null) {
			$v->addPart($this);
		}

		return $this;
	}


	/**
	 * Get the associated PartCategory object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     PartCategory The associated PartCategory object.
	 * @throws     PropelException
	 */
	public function getPartCategory(PropelPDO $con = null)
	{
		if ($this->aPartCategory === null && ($this->part_category_id !== null)) {
			$c = new Criteria(PartCategoryPeer::DATABASE_NAME);
			$c->add(PartCategoryPeer::ID, $this->part_category_id);
			$this->aPartCategory = PartCategoryPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aPartCategory->addParts($this);
			 */
		}
		return $this->aPartCategory;
	}

	/**
	 * Declares an association between this object and a Manufacturer object.
	 *
	 * @param      Manufacturer $v
	 * @return     Part The current object (for fluent API support)
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
			$v->addPart($this);
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
			   $this->aManufacturer->addParts($this);
			 */
		}
		return $this->aManufacturer;
	}

	/**
	 * Clears out the collPartOptions collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartOptions()
	 */
	public function clearPartOptions()
	{
		$this->collPartOptions = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartOptions collection (array).
	 *
	 * By default this just sets the collPartOptions collection to an empty array (like clearcollPartOptions());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartOptions()
	{
		$this->collPartOptions = array();
	}

	/**
	 * Gets an array of PartOption objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Part has previously been saved, it will retrieve
	 * related PartOptions from storage. If this Part is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartOption[]
	 * @throws     PropelException
	 */
	public function getPartOptions($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartOptions === null) {
			if ($this->isNew()) {
			   $this->collPartOptions = array();
			} else {

				$criteria->add(PartOptionPeer::PART_ID, $this->id);

				PartOptionPeer::addSelectColumns($criteria);
				$this->collPartOptions = PartOptionPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartOptionPeer::PART_ID, $this->id);

				PartOptionPeer::addSelectColumns($criteria);
				if (!isset($this->lastPartOptionCriteria) || !$this->lastPartOptionCriteria->equals($criteria)) {
					$this->collPartOptions = PartOptionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartOptionCriteria = $criteria;
		return $this->collPartOptions;
	}

	/**
	 * Returns the number of related PartOption objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartOption objects.
	 * @throws     PropelException
	 */
	public function countPartOptions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartOptions === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartOptionPeer::PART_ID, $this->id);

				$count = PartOptionPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartOptionPeer::PART_ID, $this->id);

				if (!isset($this->lastPartOptionCriteria) || !$this->lastPartOptionCriteria->equals($criteria)) {
					$count = PartOptionPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartOptions);
				}
			} else {
				$count = count($this->collPartOptions);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartOption object to this object
	 * through the PartOption foreign key attribute.
	 *
	 * @param      PartOption $l PartOption
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartOption(PartOption $l)
	{
		if ($this->collPartOptions === null) {
			$this->initPartOptions();
		}
		if (!in_array($l, $this->collPartOptions, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartOptions, $l);
			$l->setPart($this);
		}
	}

	/**
	 * Clears out the collPartVariants collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartVariants()
	 */
	public function clearPartVariants()
	{
		$this->collPartVariants = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartVariants collection (array).
	 *
	 * By default this just sets the collPartVariants collection to an empty array (like clearcollPartVariants());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartVariants()
	{
		$this->collPartVariants = array();
	}

	/**
	 * Gets an array of PartVariant objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Part has previously been saved, it will retrieve
	 * related PartVariants from storage. If this Part is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartVariant[]
	 * @throws     PropelException
	 */
	public function getPartVariants($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartVariants === null) {
			if ($this->isNew()) {
			   $this->collPartVariants = array();
			} else {

				$criteria->add(PartVariantPeer::PART_ID, $this->id);

				PartVariantPeer::addSelectColumns($criteria);
				$this->collPartVariants = PartVariantPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartVariantPeer::PART_ID, $this->id);

				PartVariantPeer::addSelectColumns($criteria);
				if (!isset($this->lastPartVariantCriteria) || !$this->lastPartVariantCriteria->equals($criteria)) {
					$this->collPartVariants = PartVariantPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartVariantCriteria = $criteria;
		return $this->collPartVariants;
	}

	/**
	 * Returns the number of related PartVariant objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartVariant objects.
	 * @throws     PropelException
	 */
	public function countPartVariants(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartVariants === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartVariantPeer::PART_ID, $this->id);

				$count = PartVariantPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartVariantPeer::PART_ID, $this->id);

				if (!isset($this->lastPartVariantCriteria) || !$this->lastPartVariantCriteria->equals($criteria)) {
					$count = PartVariantPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartVariants);
				}
			} else {
				$count = count($this->collPartVariants);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartVariant object to this object
	 * through the PartVariant foreign key attribute.
	 *
	 * @param      PartVariant $l PartVariant
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartVariant(PartVariant $l)
	{
		if ($this->collPartVariants === null) {
			$this->initPartVariants();
		}
		if (!in_array($l, $this->collPartVariants, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartVariants, $l);
			$l->setPart($this);
		}
	}

	/**
	 * Clears out the collPartPhotos collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartPhotos()
	 */
	public function clearPartPhotos()
	{
		$this->collPartPhotos = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartPhotos collection (array).
	 *
	 * By default this just sets the collPartPhotos collection to an empty array (like clearcollPartPhotos());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartPhotos()
	{
		$this->collPartPhotos = array();
	}

	/**
	 * Gets an array of PartPhoto objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Part has previously been saved, it will retrieve
	 * related PartPhotos from storage. If this Part is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartPhoto[]
	 * @throws     PropelException
	 */
	public function getPartPhotos($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
			   $this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				PartPhotoPeer::addSelectColumns($criteria);
				$this->collPartPhotos = PartPhotoPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				PartPhotoPeer::addSelectColumns($criteria);
				if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
					$this->collPartPhotos = PartPhotoPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartPhotoCriteria = $criteria;
		return $this->collPartPhotos;
	}

	/**
	 * Returns the number of related PartPhoto objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartPhoto objects.
	 * @throws     PropelException
	 */
	public function countPartPhotos(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				$count = PartPhotoPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
					$count = PartPhotoPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartPhotos);
				}
			} else {
				$count = count($this->collPartPhotos);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartPhoto object to this object
	 * through the PartPhoto foreign key attribute.
	 *
	 * @param      PartPhoto $l PartPhoto
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartPhoto(PartPhoto $l)
	{
		if ($this->collPartPhotos === null) {
			$this->initPartPhotos();
		}
		if (!in_array($l, $this->collPartPhotos, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartPhotos, $l);
			$l->setPart($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Part is new, it will return
	 * an empty collection; or if this Part has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Part.
	 */
	public function getPartPhotosJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PART_ID, $this->id);

			if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartPhotoCriteria = $criteria;

		return $this->collPartPhotos;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Part is new, it will return
	 * an empty collection; or if this Part has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Part.
	 */
	public function getPartPhotosJoinPhoto($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PART_ID, $this->id);

			if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartPhotoCriteria = $criteria;

		return $this->collPartPhotos;
	}

	/**
	 * Clears out the collPartFiles collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartFiles()
	 */
	public function clearPartFiles()
	{
		$this->collPartFiles = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartFiles collection (array).
	 *
	 * By default this just sets the collPartFiles collection to an empty array (like clearcollPartFiles());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartFiles()
	{
		$this->collPartFiles = array();
	}

	/**
	 * Gets an array of PartFile objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Part has previously been saved, it will retrieve
	 * related PartFiles from storage. If this Part is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartFile[]
	 * @throws     PropelException
	 */
	public function getPartFiles($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
			   $this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_ID, $this->id);

				PartFilePeer::addSelectColumns($criteria);
				$this->collPartFiles = PartFilePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartFilePeer::PART_ID, $this->id);

				PartFilePeer::addSelectColumns($criteria);
				if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
					$this->collPartFiles = PartFilePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartFileCriteria = $criteria;
		return $this->collPartFiles;
	}

	/**
	 * Returns the number of related PartFile objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartFile objects.
	 * @throws     PropelException
	 */
	public function countPartFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartFilePeer::PART_ID, $this->id);

				$count = PartFilePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartFilePeer::PART_ID, $this->id);

				if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
					$count = PartFilePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartFiles);
				}
			} else {
				$count = count($this->collPartFiles);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartFile object to this object
	 * through the PartFile foreign key attribute.
	 *
	 * @param      PartFile $l PartFile
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartFile(PartFile $l)
	{
		if ($this->collPartFiles === null) {
			$this->initPartFiles();
		}
		if (!in_array($l, $this->collPartFiles, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartFiles, $l);
			$l->setPart($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Part is new, it will return
	 * an empty collection; or if this Part has previously
	 * been saved, it will retrieve related PartFiles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Part.
	 */
	public function getPartFilesJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_ID, $this->id);

				$this->collPartFiles = PartFilePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartFilePeer::PART_ID, $this->id);

			if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
				$this->collPartFiles = PartFilePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartFileCriteria = $criteria;

		return $this->collPartFiles;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Part is new, it will return
	 * an empty collection; or if this Part has previously
	 * been saved, it will retrieve related PartFiles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Part.
	 */
	public function getPartFilesJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_ID, $this->id);

				$this->collPartFiles = PartFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartFilePeer::PART_ID, $this->id);

			if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
				$this->collPartFiles = PartFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartFileCriteria = $criteria;

		return $this->collPartFiles;
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
			if ($this->collPartOptions) {
				foreach ((array) $this->collPartOptions as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartVariants) {
				foreach ((array) $this->collPartVariants as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartPhotos) {
				foreach ((array) $this->collPartPhotos as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartFiles) {
				foreach ((array) $this->collPartFiles as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPartOptions = null;
		$this->collPartVariants = null;
		$this->collPartPhotos = null;
		$this->collPartFiles = null;
			$this->aPartCategory = null;
			$this->aManufacturer = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePart:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePart::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePart
