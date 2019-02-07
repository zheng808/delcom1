<?php

/**
 * Base class that represents a row from the 'supplier_order' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseSupplierOrder extends BaseObject  implements Persistent {


  const PEER = 'SupplierOrderPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        SupplierOrderPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the supplier_id field.
	 * @var        int
	 */
	protected $supplier_id;

	/**
	 * The value for the purchase_order field.
	 * @var        string
	 */
	protected $purchase_order;

	/**
	 * The value for the notes field.
	 * @var        string
	 */
	protected $notes;

	/**
	 * The value for the date_ordered field.
	 * @var        string
	 */
	protected $date_ordered;

	/**
	 * The value for the date_expected field.
	 * @var        string
	 */
	protected $date_expected;

	/**
	 * The value for the date_received field.
	 * @var        string
	 */
	protected $date_received;

	/**
	 * The value for the finalized field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $finalized;

	/**
	 * The value for the approved field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $approved;

	/**
	 * The value for the sent field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $sent;

	/**
	 * The value for the received_some field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $received_some;

	/**
	 * The value for the received_all field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $received_all;

	/**
	 * The value for the invoice_id field.
	 * @var        int
	 */
	protected $invoice_id;

	/**
	 * @var        Supplier
	 */
	protected $aSupplier;

	/**
	 * @var        Invoice
	 */
	protected $aInvoice;

	/**
	 * @var        array SupplierOrderItem[] Collection to store aggregation of SupplierOrderItem objects.
	 */
	protected $collSupplierOrderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSupplierOrderItems.
	 */
	private $lastSupplierOrderItemCriteria = null;

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
	 * Initializes internal state of BaseSupplierOrder object.
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
		$this->finalized = false;
		$this->approved = false;
		$this->sent = false;
		$this->received_some = false;
		$this->received_all = false;
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
	 * Get the [supplier_id] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierId()
	{
		return $this->supplier_id;
	}

	/**
	 * Get the [purchase_order] column value.
	 * 
	 * @return     string
	 */
	public function getPurchaseOrder()
	{
		return $this->purchase_order;
	}

	/**
	 * Get the [notes] column value.
	 * 
	 * @return     string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * Get the [optionally formatted] temporal [date_ordered] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDateOrdered($format = 'Y-m-d H:i:s')
	{
		if ($this->date_ordered === null) {
			return null;
		}


		if ($this->date_ordered === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->date_ordered);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_ordered, true), $x);
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
	 * Get the [optionally formatted] temporal [date_expected] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDateExpected($format = 'Y-m-d H:i:s')
	{
		if ($this->date_expected === null) {
			return null;
		}


		if ($this->date_expected === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->date_expected);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_expected, true), $x);
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
	 * Get the [optionally formatted] temporal [date_received] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDateReceived($format = 'Y-m-d H:i:s')
	{
		if ($this->date_received === null) {
			return null;
		}


		if ($this->date_received === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->date_received);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_received, true), $x);
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
	 * Get the [finalized] column value.
	 * 
	 * @return     boolean
	 */
	public function getFinalized()
	{
		return $this->finalized;
	}

	/**
	 * Get the [approved] column value.
	 * 
	 * @return     boolean
	 */
	public function getApproved()
	{
		return $this->approved;
	}

	/**
	 * Get the [sent] column value.
	 * 
	 * @return     boolean
	 */
	public function getSent()
	{
		return $this->sent;
	}

	/**
	 * Get the [received_some] column value.
	 * 
	 * @return     boolean
	 */
	public function getReceivedSome()
	{
		return $this->received_some;
	}

	/**
	 * Get the [received_all] column value.
	 * 
	 * @return     boolean
	 */
	public function getReceivedAll()
	{
		return $this->received_all;
	}

	/**
	 * Get the [invoice_id] column value.
	 * 
	 * @return     int
	 */
	public function getInvoiceId()
	{
		return $this->invoice_id;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [supplier_id] column.
	 * 
	 * @param      int $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setSupplierId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_id !== $v) {
			$this->supplier_id = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::SUPPLIER_ID;
		}

		if ($this->aSupplier !== null && $this->aSupplier->getId() !== $v) {
			$this->aSupplier = null;
		}

		return $this;
	} // setSupplierId()

	/**
	 * Set the value of [purchase_order] column.
	 * 
	 * @param      string $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setPurchaseOrder($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->purchase_order !== $v) {
			$this->purchase_order = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::PURCHASE_ORDER;
		}

		return $this;
	} // setPurchaseOrder()

	/**
	 * Set the value of [notes] column.
	 * 
	 * @param      string $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->notes !== $v) {
			$this->notes = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::NOTES;
		}

		return $this;
	} // setNotes()

	/**
	 * Sets the value of [date_ordered] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setDateOrdered($v)
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

		if ( $this->date_ordered !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->date_ordered !== null && $tmpDt = new DateTime($this->date_ordered)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->date_ordered = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = SupplierOrderPeer::DATE_ORDERED;
			}
		} // if either are not null

		return $this;
	} // setDateOrdered()

	/**
	 * Sets the value of [date_expected] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setDateExpected($v)
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

		if ( $this->date_expected !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->date_expected !== null && $tmpDt = new DateTime($this->date_expected)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->date_expected = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = SupplierOrderPeer::DATE_EXPECTED;
			}
		} // if either are not null

		return $this;
	} // setDateExpected()

	/**
	 * Sets the value of [date_received] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setDateReceived($v)
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

		if ( $this->date_received !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->date_received !== null && $tmpDt = new DateTime($this->date_received)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->date_received = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = SupplierOrderPeer::DATE_RECEIVED;
			}
		} // if either are not null

		return $this;
	} // setDateReceived()

	/**
	 * Set the value of [finalized] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setFinalized($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->finalized !== $v || $v === false) {
			$this->finalized = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::FINALIZED;
		}

		return $this;
	} // setFinalized()

	/**
	 * Set the value of [approved] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setApproved($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->approved !== $v || $v === false) {
			$this->approved = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::APPROVED;
		}

		return $this;
	} // setApproved()

	/**
	 * Set the value of [sent] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setSent($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->sent !== $v || $v === false) {
			$this->sent = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::SENT;
		}

		return $this;
	} // setSent()

	/**
	 * Set the value of [received_some] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setReceivedSome($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->received_some !== $v || $v === false) {
			$this->received_some = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::RECEIVED_SOME;
		}

		return $this;
	} // setReceivedSome()

	/**
	 * Set the value of [received_all] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setReceivedAll($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->received_all !== $v || $v === false) {
			$this->received_all = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::RECEIVED_ALL;
		}

		return $this;
	} // setReceivedAll()

	/**
	 * Set the value of [invoice_id] column.
	 * 
	 * @param      int $v new value
	 * @return     SupplierOrder The current object (for fluent API support)
	 */
	public function setInvoiceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->invoice_id !== $v) {
			$this->invoice_id = $v;
			$this->modifiedColumns[] = SupplierOrderPeer::INVOICE_ID;
		}

		if ($this->aInvoice !== null && $this->aInvoice->getId() !== $v) {
			$this->aInvoice = null;
		}

		return $this;
	} // setInvoiceId()

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
			if (array_diff($this->modifiedColumns, array(SupplierOrderPeer::FINALIZED,SupplierOrderPeer::APPROVED,SupplierOrderPeer::SENT,SupplierOrderPeer::RECEIVED_SOME,SupplierOrderPeer::RECEIVED_ALL))) {
				return false;
			}

			if ($this->finalized !== false) {
				return false;
			}

			if ($this->approved !== false) {
				return false;
			}

			if ($this->sent !== false) {
				return false;
			}

			if ($this->received_some !== false) {
				return false;
			}

			if ($this->received_all !== false) {
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
			$this->supplier_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->purchase_order = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->notes = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->date_ordered = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->date_expected = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->date_received = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->finalized = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->approved = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->sent = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
			$this->received_some = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
			$this->received_all = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
			$this->invoice_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = SupplierOrderPeer::NUM_COLUMNS - SupplierOrderPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating SupplierOrder object", $e);
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

		if ($this->aSupplier !== null && $this->supplier_id !== $this->aSupplier->getId()) {
			$this->aSupplier = null;
		}
		if ($this->aInvoice !== null && $this->invoice_id !== $this->aInvoice->getId()) {
			$this->aInvoice = null;
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
			$con = Propel::getConnection(SupplierOrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = SupplierOrderPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aSupplier = null;
			$this->aInvoice = null;
			$this->collSupplierOrderItems = null;
			$this->lastSupplierOrderItemCriteria = null;

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

    foreach (sfMixer::getCallables('BaseSupplierOrder:delete:pre') as $callable)
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
			$con = Propel::getConnection(SupplierOrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			SupplierOrderPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseSupplierOrder:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseSupplierOrder:save:pre') as $callable)
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
			$con = Propel::getConnection(SupplierOrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseSupplierOrder:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			SupplierOrderPeer::addInstanceToPool($this);
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

			if ($this->aSupplier !== null) {
				if ($this->aSupplier->isModified() || $this->aSupplier->isNew()) {
					$affectedRows += $this->aSupplier->save($con);
				}
				$this->setSupplier($this->aSupplier);
			}

			if ($this->aInvoice !== null) {
				if ($this->aInvoice->isModified() || $this->aInvoice->isNew()) {
					$affectedRows += $this->aInvoice->save($con);
				}
				$this->setInvoice($this->aInvoice);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = SupplierOrderPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = SupplierOrderPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += SupplierOrderPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collSupplierOrderItems !== null) {
				foreach ($this->collSupplierOrderItems as $referrerFK) {
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

			if ($this->aSupplier !== null) {
				if (!$this->aSupplier->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSupplier->getValidationFailures());
				}
			}

			if ($this->aInvoice !== null) {
				if (!$this->aInvoice->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aInvoice->getValidationFailures());
				}
			}


			if (($retval = SupplierOrderPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collSupplierOrderItems !== null) {
					foreach ($this->collSupplierOrderItems as $referrerFK) {
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
		$pos = SupplierOrderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getSupplierId();
				break;
			case 2:
				return $this->getPurchaseOrder();
				break;
			case 3:
				return $this->getNotes();
				break;
			case 4:
				return $this->getDateOrdered();
				break;
			case 5:
				return $this->getDateExpected();
				break;
			case 6:
				return $this->getDateReceived();
				break;
			case 7:
				return $this->getFinalized();
				break;
			case 8:
				return $this->getApproved();
				break;
			case 9:
				return $this->getSent();
				break;
			case 10:
				return $this->getReceivedSome();
				break;
			case 11:
				return $this->getReceivedAll();
				break;
			case 12:
				return $this->getInvoiceId();
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
		$keys = SupplierOrderPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getSupplierId(),
			$keys[2] => $this->getPurchaseOrder(),
			$keys[3] => $this->getNotes(),
			$keys[4] => $this->getDateOrdered(),
			$keys[5] => $this->getDateExpected(),
			$keys[6] => $this->getDateReceived(),
			$keys[7] => $this->getFinalized(),
			$keys[8] => $this->getApproved(),
			$keys[9] => $this->getSent(),
			$keys[10] => $this->getReceivedSome(),
			$keys[11] => $this->getReceivedAll(),
			$keys[12] => $this->getInvoiceId(),
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
		$pos = SupplierOrderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setSupplierId($value);
				break;
			case 2:
				$this->setPurchaseOrder($value);
				break;
			case 3:
				$this->setNotes($value);
				break;
			case 4:
				$this->setDateOrdered($value);
				break;
			case 5:
				$this->setDateExpected($value);
				break;
			case 6:
				$this->setDateReceived($value);
				break;
			case 7:
				$this->setFinalized($value);
				break;
			case 8:
				$this->setApproved($value);
				break;
			case 9:
				$this->setSent($value);
				break;
			case 10:
				$this->setReceivedSome($value);
				break;
			case 11:
				$this->setReceivedAll($value);
				break;
			case 12:
				$this->setInvoiceId($value);
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
		$keys = SupplierOrderPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setSupplierId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPurchaseOrder($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setNotes($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDateOrdered($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setDateExpected($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDateReceived($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setFinalized($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setApproved($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setSent($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setReceivedSome($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setReceivedAll($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setInvoiceId($arr[$keys[12]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(SupplierOrderPeer::DATABASE_NAME);

		if ($this->isColumnModified(SupplierOrderPeer::ID)) $criteria->add(SupplierOrderPeer::ID, $this->id);
		if ($this->isColumnModified(SupplierOrderPeer::SUPPLIER_ID)) $criteria->add(SupplierOrderPeer::SUPPLIER_ID, $this->supplier_id);
		if ($this->isColumnModified(SupplierOrderPeer::PURCHASE_ORDER)) $criteria->add(SupplierOrderPeer::PURCHASE_ORDER, $this->purchase_order);
		if ($this->isColumnModified(SupplierOrderPeer::NOTES)) $criteria->add(SupplierOrderPeer::NOTES, $this->notes);
		if ($this->isColumnModified(SupplierOrderPeer::DATE_ORDERED)) $criteria->add(SupplierOrderPeer::DATE_ORDERED, $this->date_ordered);
		if ($this->isColumnModified(SupplierOrderPeer::DATE_EXPECTED)) $criteria->add(SupplierOrderPeer::DATE_EXPECTED, $this->date_expected);
		if ($this->isColumnModified(SupplierOrderPeer::DATE_RECEIVED)) $criteria->add(SupplierOrderPeer::DATE_RECEIVED, $this->date_received);
		if ($this->isColumnModified(SupplierOrderPeer::FINALIZED)) $criteria->add(SupplierOrderPeer::FINALIZED, $this->finalized);
		if ($this->isColumnModified(SupplierOrderPeer::APPROVED)) $criteria->add(SupplierOrderPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(SupplierOrderPeer::SENT)) $criteria->add(SupplierOrderPeer::SENT, $this->sent);
		if ($this->isColumnModified(SupplierOrderPeer::RECEIVED_SOME)) $criteria->add(SupplierOrderPeer::RECEIVED_SOME, $this->received_some);
		if ($this->isColumnModified(SupplierOrderPeer::RECEIVED_ALL)) $criteria->add(SupplierOrderPeer::RECEIVED_ALL, $this->received_all);
		if ($this->isColumnModified(SupplierOrderPeer::INVOICE_ID)) $criteria->add(SupplierOrderPeer::INVOICE_ID, $this->invoice_id);

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
		$criteria = new Criteria(SupplierOrderPeer::DATABASE_NAME);

		$criteria->add(SupplierOrderPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of SupplierOrder (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setSupplierId($this->supplier_id);

		$copyObj->setPurchaseOrder($this->purchase_order);

		$copyObj->setNotes($this->notes);

		$copyObj->setDateOrdered($this->date_ordered);

		$copyObj->setDateExpected($this->date_expected);

		$copyObj->setDateReceived($this->date_received);

		$copyObj->setFinalized($this->finalized);

		$copyObj->setApproved($this->approved);

		$copyObj->setSent($this->sent);

		$copyObj->setReceivedSome($this->received_some);

		$copyObj->setReceivedAll($this->received_all);

		$copyObj->setInvoiceId($this->invoice_id);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getSupplierOrderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSupplierOrderItem($relObj->copy($deepCopy));
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
	 * @return     SupplierOrder Clone of current object.
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
	 * @return     SupplierOrderPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new SupplierOrderPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Supplier object.
	 *
	 * @param      Supplier $v
	 * @return     SupplierOrder The current object (for fluent API support)
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
			$v->addSupplierOrder($this);
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
			   $this->aSupplier->addSupplierOrders($this);
			 */
		}
		return $this->aSupplier;
	}

	/**
	 * Declares an association between this object and a Invoice object.
	 *
	 * @param      Invoice $v
	 * @return     SupplierOrder The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setInvoice(Invoice $v = null)
	{
		if ($v === null) {
			$this->setInvoiceId(NULL);
		} else {
			$this->setInvoiceId($v->getId());
		}

		$this->aInvoice = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Invoice object, it will not be re-added.
		if ($v !== null) {
			$v->addSupplierOrder($this);
		}

		return $this;
	}


	/**
	 * Get the associated Invoice object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Invoice The associated Invoice object.
	 * @throws     PropelException
	 */
	public function getInvoice(PropelPDO $con = null)
	{
		if ($this->aInvoice === null && ($this->invoice_id !== null)) {
			$c = new Criteria(InvoicePeer::DATABASE_NAME);
			$c->add(InvoicePeer::ID, $this->invoice_id);
			$this->aInvoice = InvoicePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aInvoice->addSupplierOrders($this);
			 */
		}
		return $this->aInvoice;
	}

	/**
	 * Clears out the collSupplierOrderItems collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSupplierOrderItems()
	 */
	public function clearSupplierOrderItems()
	{
		$this->collSupplierOrderItems = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSupplierOrderItems collection (array).
	 *
	 * By default this just sets the collSupplierOrderItems collection to an empty array (like clearcollSupplierOrderItems());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSupplierOrderItems()
	{
		$this->collSupplierOrderItems = array();
	}

	/**
	 * Gets an array of SupplierOrderItem objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SupplierOrder has previously been saved, it will retrieve
	 * related SupplierOrderItems from storage. If this SupplierOrder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SupplierOrderItem[]
	 * @throws     PropelException
	 */
	public function getSupplierOrderItems($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SupplierOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrderItems === null) {
			if ($this->isNew()) {
			   $this->collSupplierOrderItems = array();
			} else {

				$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

				SupplierOrderItemPeer::addSelectColumns($criteria);
				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

				SupplierOrderItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastSupplierOrderItemCriteria) || !$this->lastSupplierOrderItemCriteria->equals($criteria)) {
					$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSupplierOrderItemCriteria = $criteria;
		return $this->collSupplierOrderItems;
	}

	/**
	 * Returns the number of related SupplierOrderItem objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SupplierOrderItem objects.
	 * @throws     PropelException
	 */
	public function countSupplierOrderItems(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SupplierOrderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSupplierOrderItems === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

				$count = SupplierOrderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

				if (!isset($this->lastSupplierOrderItemCriteria) || !$this->lastSupplierOrderItemCriteria->equals($criteria)) {
					$count = SupplierOrderItemPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSupplierOrderItems);
				}
			} else {
				$count = count($this->collSupplierOrderItems);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a SupplierOrderItem object to this object
	 * through the SupplierOrderItem foreign key attribute.
	 *
	 * @param      SupplierOrderItem $l SupplierOrderItem
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSupplierOrderItem(SupplierOrderItem $l)
	{
		if ($this->collSupplierOrderItems === null) {
			$this->initSupplierOrderItems();
		}
		if (!in_array($l, $this->collSupplierOrderItems, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSupplierOrderItems, $l);
			$l->setSupplierOrder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SupplierOrder is new, it will return
	 * an empty collection; or if this SupplierOrder has previously
	 * been saved, it will retrieve related SupplierOrderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SupplierOrder.
	 */
	public function getSupplierOrderItemsJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SupplierOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrderItems === null) {
			if ($this->isNew()) {
				$this->collSupplierOrderItems = array();
			} else {

				$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->id);

			if (!isset($this->lastSupplierOrderItemCriteria) || !$this->lastSupplierOrderItemCriteria->equals($criteria)) {
				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastSupplierOrderItemCriteria = $criteria;

		return $this->collSupplierOrderItems;
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
			if ($this->collSupplierOrderItems) {
				foreach ((array) $this->collSupplierOrderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collSupplierOrderItems = null;
			$this->aSupplier = null;
			$this->aInvoice = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseSupplierOrder:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseSupplierOrder::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseSupplierOrder
