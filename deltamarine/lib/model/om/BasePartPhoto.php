<?php

/**
 * Base class that represents a row from the 'part_photo' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartPhoto extends BaseObject  implements Persistent {


  const PEER = 'PartPhotoPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PartPhotoPeer
	 */
	protected static $peer;

	/**
	 * The value for the part_id field.
	 * @var        int
	 */
	protected $part_id;

	/**
	 * The value for the part_variant_id field.
	 * @var        int
	 */
	protected $part_variant_id;

	/**
	 * The value for the photo_id field.
	 * @var        int
	 */
	protected $photo_id;

	/**
	 * The value for the is_primary field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $is_primary;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * @var        Part
	 */
	protected $aPart;

	/**
	 * @var        PartVariant
	 */
	protected $aPartVariant;

	/**
	 * @var        Photo
	 */
	protected $aPhoto;

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
	 * Initializes internal state of BasePartPhoto object.
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
		$this->is_primary = true;
	}

	/**
	 * Get the [part_id] column value.
	 * 
	 * @return     int
	 */
	public function getPartId()
	{
		return $this->part_id;
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
	 * Get the [photo_id] column value.
	 * 
	 * @return     int
	 */
	public function getPhotoId()
	{
		return $this->photo_id;
	}

	/**
	 * Get the [is_primary] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsPrimary()
	{
		return $this->is_primary;
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
	 * Set the value of [part_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartPhoto The current object (for fluent API support)
	 */
	public function setPartId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_id !== $v) {
			$this->part_id = $v;
			$this->modifiedColumns[] = PartPhotoPeer::PART_ID;
		}

		if ($this->aPart !== null && $this->aPart->getId() !== $v) {
			$this->aPart = null;
		}

		return $this;
	} // setPartId()

	/**
	 * Set the value of [part_variant_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartPhoto The current object (for fluent API support)
	 */
	public function setPartVariantId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_variant_id !== $v) {
			$this->part_variant_id = $v;
			$this->modifiedColumns[] = PartPhotoPeer::PART_VARIANT_ID;
		}

		if ($this->aPartVariant !== null && $this->aPartVariant->getId() !== $v) {
			$this->aPartVariant = null;
		}

		return $this;
	} // setPartVariantId()

	/**
	 * Set the value of [photo_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartPhoto The current object (for fluent API support)
	 */
	public function setPhotoId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->photo_id !== $v) {
			$this->photo_id = $v;
			$this->modifiedColumns[] = PartPhotoPeer::PHOTO_ID;
		}

		if ($this->aPhoto !== null && $this->aPhoto->getId() !== $v) {
			$this->aPhoto = null;
		}

		return $this;
	} // setPhotoId()

	/**
	 * Set the value of [is_primary] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartPhoto The current object (for fluent API support)
	 */
	public function setIsPrimary($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_primary !== $v || $v === true) {
			$this->is_primary = $v;
			$this->modifiedColumns[] = PartPhotoPeer::IS_PRIMARY;
		}

		return $this;
	} // setIsPrimary()

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartPhoto The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PartPhotoPeer::ID;
		}

		return $this;
	} // setId()

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
			if (array_diff($this->modifiedColumns, array(PartPhotoPeer::IS_PRIMARY))) {
				return false;
			}

			if ($this->is_primary !== true) {
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

			$this->part_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->part_variant_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->photo_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->is_primary = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = PartPhotoPeer::NUM_COLUMNS - PartPhotoPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating PartPhoto object", $e);
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

		if ($this->aPart !== null && $this->part_id !== $this->aPart->getId()) {
			$this->aPart = null;
		}
		if ($this->aPartVariant !== null && $this->part_variant_id !== $this->aPartVariant->getId()) {
			$this->aPartVariant = null;
		}
		if ($this->aPhoto !== null && $this->photo_id !== $this->aPhoto->getId()) {
			$this->aPhoto = null;
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
			$con = Propel::getConnection(PartPhotoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PartPhotoPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aPart = null;
			$this->aPartVariant = null;
			$this->aPhoto = null;
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

    foreach (sfMixer::getCallables('BasePartPhoto:delete:pre') as $callable)
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
			$con = Propel::getConnection(PartPhotoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PartPhotoPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePartPhoto:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePartPhoto:save:pre') as $callable)
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
			$con = Propel::getConnection(PartPhotoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePartPhoto:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PartPhotoPeer::addInstanceToPool($this);
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

			if ($this->aPart !== null) {
				if ($this->aPart->isModified() || $this->aPart->isNew()) {
					$affectedRows += $this->aPart->save($con);
				}
				$this->setPart($this->aPart);
			}

			if ($this->aPartVariant !== null) {
				if ($this->aPartVariant->isModified() || $this->aPartVariant->isNew()) {
					$affectedRows += $this->aPartVariant->save($con);
				}
				$this->setPartVariant($this->aPartVariant);
			}

			if ($this->aPhoto !== null) {
				if ($this->aPhoto->isModified() || $this->aPhoto->isNew()) {
					$affectedRows += $this->aPhoto->save($con);
				}
				$this->setPhoto($this->aPhoto);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PartPhotoPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PartPhotoPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PartPhotoPeer::doUpdate($this, $con);
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

			if ($this->aPart !== null) {
				if (!$this->aPart->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPart->getValidationFailures());
				}
			}

			if ($this->aPartVariant !== null) {
				if (!$this->aPartVariant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPartVariant->getValidationFailures());
				}
			}

			if ($this->aPhoto !== null) {
				if (!$this->aPhoto->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPhoto->getValidationFailures());
				}
			}


			if (($retval = PartPhotoPeer::doValidate($this, $columns)) !== true) {
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
		$pos = PartPhotoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPartId();
				break;
			case 1:
				return $this->getPartVariantId();
				break;
			case 2:
				return $this->getPhotoId();
				break;
			case 3:
				return $this->getIsPrimary();
				break;
			case 4:
				return $this->getId();
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
		$keys = PartPhotoPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPartId(),
			$keys[1] => $this->getPartVariantId(),
			$keys[2] => $this->getPhotoId(),
			$keys[3] => $this->getIsPrimary(),
			$keys[4] => $this->getId(),
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
		$pos = PartPhotoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPartId($value);
				break;
			case 1:
				$this->setPartVariantId($value);
				break;
			case 2:
				$this->setPhotoId($value);
				break;
			case 3:
				$this->setIsPrimary($value);
				break;
			case 4:
				$this->setId($value);
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
		$keys = PartPhotoPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPartId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPartVariantId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPhotoId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIsPrimary($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setId($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PartPhotoPeer::DATABASE_NAME);

		if ($this->isColumnModified(PartPhotoPeer::PART_ID)) $criteria->add(PartPhotoPeer::PART_ID, $this->part_id);
		if ($this->isColumnModified(PartPhotoPeer::PART_VARIANT_ID)) $criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->part_variant_id);
		if ($this->isColumnModified(PartPhotoPeer::PHOTO_ID)) $criteria->add(PartPhotoPeer::PHOTO_ID, $this->photo_id);
		if ($this->isColumnModified(PartPhotoPeer::IS_PRIMARY)) $criteria->add(PartPhotoPeer::IS_PRIMARY, $this->is_primary);
		if ($this->isColumnModified(PartPhotoPeer::ID)) $criteria->add(PartPhotoPeer::ID, $this->id);

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
		$criteria = new Criteria(PartPhotoPeer::DATABASE_NAME);

		$criteria->add(PartPhotoPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of PartPhoto (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPartId($this->part_id);

		$copyObj->setPartVariantId($this->part_variant_id);

		$copyObj->setPhotoId($this->photo_id);

		$copyObj->setIsPrimary($this->is_primary);


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
	 * @return     PartPhoto Clone of current object.
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
	 * @return     PartPhotoPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PartPhotoPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Part object.
	 *
	 * @param      Part $v
	 * @return     PartPhoto The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setPart(Part $v = null)
	{
		if ($v === null) {
			$this->setPartId(NULL);
		} else {
			$this->setPartId($v->getId());
		}

		$this->aPart = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Part object, it will not be re-added.
		if ($v !== null) {
			$v->addPartPhoto($this);
		}

		return $this;
	}


	/**
	 * Get the associated Part object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Part The associated Part object.
	 * @throws     PropelException
	 */
	public function getPart(PropelPDO $con = null)
	{
		if ($this->aPart === null && ($this->part_id !== null)) {
			$c = new Criteria(PartPeer::DATABASE_NAME);
			$c->add(PartPeer::ID, $this->part_id);
			$this->aPart = PartPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aPart->addPartPhotos($this);
			 */
		}
		return $this->aPart;
	}

	/**
	 * Declares an association between this object and a PartVariant object.
	 *
	 * @param      PartVariant $v
	 * @return     PartPhoto The current object (for fluent API support)
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
			$v->addPartPhoto($this);
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
			   $this->aPartVariant->addPartPhotos($this);
			 */
		}
		return $this->aPartVariant;
	}

	/**
	 * Declares an association between this object and a Photo object.
	 *
	 * @param      Photo $v
	 * @return     PartPhoto The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setPhoto(Photo $v = null)
	{
		if ($v === null) {
			$this->setPhotoId(NULL);
		} else {
			$this->setPhotoId($v->getId());
		}

		$this->aPhoto = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Photo object, it will not be re-added.
		if ($v !== null) {
			$v->addPartPhoto($this);
		}

		return $this;
	}


	/**
	 * Get the associated Photo object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Photo The associated Photo object.
	 * @throws     PropelException
	 */
	public function getPhoto(PropelPDO $con = null)
	{
		if ($this->aPhoto === null && ($this->photo_id !== null)) {
			$c = new Criteria(PhotoPeer::DATABASE_NAME);
			$c->add(PhotoPeer::ID, $this->photo_id);
			$this->aPhoto = PhotoPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aPhoto->addPartPhotos($this);
			 */
		}
		return $this->aPhoto;
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

			$this->aPart = null;
			$this->aPartVariant = null;
			$this->aPhoto = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePartPhoto:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePartPhoto::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePartPhoto