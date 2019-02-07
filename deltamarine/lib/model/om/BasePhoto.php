<?php

/**
 * Base class that represents a row from the 'photo' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePhoto extends BaseObject  implements Persistent {


  const PEER = 'PhotoPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PhotoPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the filename field.
	 * @var        string
	 */
	protected $filename;

	/**
	 * The value for the caption field.
	 * @var        string
	 */
	protected $caption;

	/**
	 * @var        array PartPhoto[] Collection to store aggregation of PartPhoto objects.
	 */
	protected $collPartPhotos;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartPhotos.
	 */
	private $lastPartPhotoCriteria = null;

	/**
	 * @var        array WorkorderItemPhoto[] Collection to store aggregation of WorkorderItemPhoto objects.
	 */
	protected $collWorkorderItemPhotos;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItemPhotos.
	 */
	private $lastWorkorderItemPhotoCriteria = null;

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
	 * Initializes internal state of BasePhoto object.
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
	 * Get the [filename] column value.
	 * 
	 * @return     string
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * Get the [caption] column value.
	 * 
	 * @return     string
	 */
	public function getCaption()
	{
		return $this->caption;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Photo The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PhotoPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [filename] column.
	 * 
	 * @param      string $v new value
	 * @return     Photo The current object (for fluent API support)
	 */
	public function setFilename($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->filename !== $v) {
			$this->filename = $v;
			$this->modifiedColumns[] = PhotoPeer::FILENAME;
		}

		return $this;
	} // setFilename()

	/**
	 * Set the value of [caption] column.
	 * 
	 * @param      string $v new value
	 * @return     Photo The current object (for fluent API support)
	 */
	public function setCaption($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->caption !== $v) {
			$this->caption = $v;
			$this->modifiedColumns[] = PhotoPeer::CAPTION;
		}

		return $this;
	} // setCaption()

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
			$this->filename = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->caption = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = PhotoPeer::NUM_COLUMNS - PhotoPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Photo object", $e);
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
			$con = Propel::getConnection(PhotoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PhotoPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collPartPhotos = null;
			$this->lastPartPhotoCriteria = null;

			$this->collWorkorderItemPhotos = null;
			$this->lastWorkorderItemPhotoCriteria = null;

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

    foreach (sfMixer::getCallables('BasePhoto:delete:pre') as $callable)
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
			$con = Propel::getConnection(PhotoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PhotoPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePhoto:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePhoto:save:pre') as $callable)
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
			$con = Propel::getConnection(PhotoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePhoto:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PhotoPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PhotoPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PhotoPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PhotoPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPartPhotos !== null) {
				foreach ($this->collPartPhotos as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderItemPhotos !== null) {
				foreach ($this->collWorkorderItemPhotos as $referrerFK) {
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


			if (($retval = PhotoPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPartPhotos !== null) {
					foreach ($this->collPartPhotos as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderItemPhotos !== null) {
					foreach ($this->collWorkorderItemPhotos as $referrerFK) {
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
		$pos = PhotoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getFilename();
				break;
			case 2:
				return $this->getCaption();
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
		$keys = PhotoPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getFilename(),
			$keys[2] => $this->getCaption(),
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
		$pos = PhotoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setFilename($value);
				break;
			case 2:
				$this->setCaption($value);
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
		$keys = PhotoPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFilename($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCaption($arr[$keys[2]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PhotoPeer::DATABASE_NAME);

		if ($this->isColumnModified(PhotoPeer::ID)) $criteria->add(PhotoPeer::ID, $this->id);
		if ($this->isColumnModified(PhotoPeer::FILENAME)) $criteria->add(PhotoPeer::FILENAME, $this->filename);
		if ($this->isColumnModified(PhotoPeer::CAPTION)) $criteria->add(PhotoPeer::CAPTION, $this->caption);

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
		$criteria = new Criteria(PhotoPeer::DATABASE_NAME);

		$criteria->add(PhotoPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Photo (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setFilename($this->filename);

		$copyObj->setCaption($this->caption);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPartPhotos() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartPhoto($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderItemPhotos() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItemPhoto($relObj->copy($deepCopy));
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
	 * @return     Photo Clone of current object.
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
	 * @return     PhotoPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PhotoPeer();
		}
		return self::$peer;
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
	 * Otherwise if this Photo has previously been saved, it will retrieve
	 * related PartPhotos from storage. If this Photo is new, it will return
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
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
			   $this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

				PartPhotoPeer::addSelectColumns($criteria);
				$this->collPartPhotos = PartPhotoPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

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
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
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

				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

				$count = PartPhotoPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

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
			$l->setPhoto($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Photo is new, it will return
	 * an empty collection; or if this Photo has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Photo.
	 */
	public function getPartPhotosJoinPart($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPart($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

			if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPart($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartPhotoCriteria = $criteria;

		return $this->collPartPhotos;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Photo is new, it will return
	 * an empty collection; or if this Photo has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Photo.
	 */
	public function getPartPhotosJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PHOTO_ID, $this->id);

			if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartPhotoCriteria = $criteria;

		return $this->collPartPhotos;
	}

	/**
	 * Clears out the collWorkorderItemPhotos collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderItemPhotos()
	 */
	public function clearWorkorderItemPhotos()
	{
		$this->collWorkorderItemPhotos = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderItemPhotos collection (array).
	 *
	 * By default this just sets the collWorkorderItemPhotos collection to an empty array (like clearcollWorkorderItemPhotos());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderItemPhotos()
	{
		$this->collWorkorderItemPhotos = array();
	}

	/**
	 * Gets an array of WorkorderItemPhoto objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Photo has previously been saved, it will retrieve
	 * related WorkorderItemPhotos from storage. If this Photo is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderItemPhoto[]
	 * @throws     PropelException
	 */
	public function getWorkorderItemPhotos($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemPhotos === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItemPhotos = array();
			} else {

				$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

				WorkorderItemPhotoPeer::addSelectColumns($criteria);
				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

				WorkorderItemPhotoPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderItemPhotoCriteria) || !$this->lastWorkorderItemPhotoCriteria->equals($criteria)) {
					$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderItemPhotoCriteria = $criteria;
		return $this->collWorkorderItemPhotos;
	}

	/**
	 * Returns the number of related WorkorderItemPhoto objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderItemPhoto objects.
	 * @throws     PropelException
	 */
	public function countWorkorderItemPhotos(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderItemPhotos === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

				$count = WorkorderItemPhotoPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

				if (!isset($this->lastWorkorderItemPhotoCriteria) || !$this->lastWorkorderItemPhotoCriteria->equals($criteria)) {
					$count = WorkorderItemPhotoPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderItemPhotos);
				}
			} else {
				$count = count($this->collWorkorderItemPhotos);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderItemPhoto object to this object
	 * through the WorkorderItemPhoto foreign key attribute.
	 *
	 * @param      WorkorderItemPhoto $l WorkorderItemPhoto
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderItemPhoto(WorkorderItemPhoto $l)
	{
		if ($this->collWorkorderItemPhotos === null) {
			$this->initWorkorderItemPhotos();
		}
		if (!in_array($l, $this->collWorkorderItemPhotos, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderItemPhotos, $l);
			$l->setPhoto($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Photo is new, it will return
	 * an empty collection; or if this Photo has previously
	 * been saved, it will retrieve related WorkorderItemPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Photo.
	 */
	public function getWorkorderItemPhotosJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PhotoPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemPhotos === null) {
			if ($this->isNew()) {
				$this->collWorkorderItemPhotos = array();
			} else {

				$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemPhotoPeer::PHOTO_ID, $this->id);

			if (!isset($this->lastWorkorderItemPhotoCriteria) || !$this->lastWorkorderItemPhotoCriteria->equals($criteria)) {
				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemPhotoCriteria = $criteria;

		return $this->collWorkorderItemPhotos;
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
			if ($this->collPartPhotos) {
				foreach ((array) $this->collPartPhotos as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderItemPhotos) {
				foreach ((array) $this->collWorkorderItemPhotos as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPartPhotos = null;
		$this->collWorkorderItemPhotos = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePhoto:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePhoto::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePhoto
