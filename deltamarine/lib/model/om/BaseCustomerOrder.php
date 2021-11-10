<?php

/**
 * Base class that represents a row from the 'customer_order' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseCustomerOrder extends BaseObject  implements Persistent {


  const PEER = 'CustomerOrderPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        CustomerOrderPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the customer_id field.
	 * @var        int
	 */
	protected $customer_id;

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
	 * The value for the sent_some field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $sent_some;

	/**
	 * The value for the sent_all field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $sent_all;

	/**
	 * The value for the invoice_per_shipment field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $invoice_per_shipment;

	/**
	 * The value for the invoice_id field.
	 * @var        int
	 */
	protected $invoice_id;

	/**
	 * The value for the date_ordered field.
	 * @var        string
	 */
	protected $date_ordered;

	/**
	 * The value for the hst_exempt field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $hst_exempt;

	/**
	 * The value for the gst_exempt field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $gst_exempt;

	/**
	 * The value for the pst_exempt field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $pst_exempt;

	/**
	 * The value for the for_rigging field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $for_rigging;

	/**
	 * The value for the discount_pct field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $discount_pct;

	/**
	 * The value for the po_num field.
	 * @var        string
	 */
	protected $po_num;

	/**
	 * The value for the boat_name field.
	 * @var        string
	 */
	protected $boat_name;

	/**
	 * @var        Customer
	 */
	protected $aCustomer;

	/**
	 * @var        Invoice
	 */
	protected $aInvoice;

	/**
	 * @var        array CustomerReturn[] Collection to store aggregation of CustomerReturn objects.
	 */
	protected $collCustomerReturns;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerReturns.
	 */
	private $lastCustomerReturnCriteria = null;

	/**
	 * @var        array CustomerOrderItem[] Collection to store aggregation of CustomerOrderItem objects.
	 */
	protected $collCustomerOrderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerOrderItems.
	 */
	private $lastCustomerOrderItemCriteria = null;

	/**
	 * @var        array Payment[] Collection to store aggregation of Payment objects.
	 */
	protected $collPayments;

	protected $division;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPayments.
	 */
	private $lastPaymentCriteria = null;


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
	 * Initializes internal state of BaseCustomerOrder object.
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
		$this->sent_some = false;
		$this->sent_all = false;
		$this->invoice_per_shipment = false;
		$this->hst_exempt = false;
		$this->gst_exempt = false;
		$this->pst_exempt = false;
		$this->for_rigging = false;
		$this->discount_pct = 0;
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
	 * Get the [customer_id] column value.
	 * 
	 * @return     int
	 */
	public function getCustomerId()
	{
		return $this->customer_id;
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
	 * Get the [sent_some] column value.
	 * 
	 * @return     boolean
	 */
	public function getSentSome()
	{
		return $this->sent_some;
	}

	/**
	 * Get the [sent_all] column value.
	 * 
	 * @return     boolean
	 */
	public function getSentAll()
	{
		return $this->sent_all;
	}

	/**
	 * Get the [invoice_per_shipment] column value.
	 * 
	 * @return     boolean
	 */
	public function getInvoicePerShipment()
	{
		return $this->invoice_per_shipment;
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
	 * Get the [hst_exempt] column value.
	 * 
	 * @return     boolean
	 */
	public function getHstExempt()
	{
		return $this->hst_exempt;
	}

	/**
	 * Get the [gst_exempt] column value.
	 * 
	 * @return     boolean
	 */
	public function getGstExempt()
	{
		return $this->gst_exempt;
	}

	/**
	 * Get the [pst_exempt] column value.
	 * 
	 * @return     boolean
	 */
	public function getPstExempt()
	{
		return $this->pst_exempt;
	}

	/**
	 * Get the [for_rigging] column value.
	 * 
	 * @return     boolean
	 */
	public function getForRigging()
	{
		return $this->for_rigging;
	}

	/**
	 * Get the [discount_pct] column value.
	 * 
	 * @return     int
	 */
	public function getDiscountPct()
	{
		return $this->discount_pct;
	}

	/**
	 * Get the [po_num] column value.
	 * 
	 * @return     string
	 */
	public function getPoNum()
	{
		return $this->po_num;
	}

	public function getDivision()
	{
		return $this->division;
	}

	/**
	 * Get the [boat_name] column value.
	 * 
	 * @return     string
	 */
	public function getBoatName()
	{
		return $this->boat_name;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [customer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setCustomerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_id !== $v) {
			$this->customer_id = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::CUSTOMER_ID;
		}

		if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
			$this->aCustomer = null;
		}

		return $this;
	} // setCustomerId()

	/**
	 * Set the value of [finalized] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setFinalized($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->finalized !== $v || $v === false) {
			$this->finalized = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::FINALIZED;
		}

		return $this;
	} // setFinalized()

	/**
	 * Set the value of [approved] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setApproved($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->approved !== $v || $v === false) {
			$this->approved = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::APPROVED;
		}

		return $this;
	} // setApproved()

	/**
	 * Set the value of [sent_some] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setSentSome($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->sent_some !== $v || $v === false) {
			$this->sent_some = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::SENT_SOME;
		}

		return $this;
	} // setSentSome()

	/**
	 * Set the value of [sent_all] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setSentAll($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->sent_all !== $v || $v === false) {
			$this->sent_all = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::SENT_ALL;
		}

		return $this;
	} // setSentAll()

	/**
	 * Set the value of [invoice_per_shipment] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setInvoicePerShipment($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->invoice_per_shipment !== $v || $v === false) {
			$this->invoice_per_shipment = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::INVOICE_PER_SHIPMENT;
		}

		return $this;
	} // setInvoicePerShipment()

	/**
	 * Set the value of [invoice_id] column.
	 * 
	 * @param      int $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setInvoiceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->invoice_id !== $v) {
			$this->invoice_id = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::INVOICE_ID;
		}

		if ($this->aInvoice !== null && $this->aInvoice->getId() !== $v) {
			$this->aInvoice = null;
		}

		return $this;
	} // setInvoiceId()

	/**
	 * Sets the value of [date_ordered] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     CustomerOrder The current object (for fluent API support)
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
				$this->modifiedColumns[] = CustomerOrderPeer::DATE_ORDERED;
			}
		} // if either are not null

		return $this;
	} // setDateOrdered()

	/**
	 * Set the value of [hst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setHstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->hst_exempt !== $v || $v === false) {
			$this->hst_exempt = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::HST_EXEMPT;
		}

		return $this;
	} // setHstExempt()

	/**
	 * Set the value of [gst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setGstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->gst_exempt !== $v || $v === false) {
			$this->gst_exempt = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::GST_EXEMPT;
		}

		return $this;
	} // setGstExempt()

	/**
	 * Set the value of [pst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setPstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->pst_exempt !== $v || $v === false) {
			$this->pst_exempt = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::PST_EXEMPT;
		}

		return $this;
	} // setPstExempt()

	/**
	 * Set the value of [for_rigging] column.
	 * 
	 * @param      boolean $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setForRigging($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->for_rigging !== $v || $v === false) {
			$this->for_rigging = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::FOR_RIGGING;
		}

		return $this;
	} // setForRigging()

	/**
	 * Set the value of [discount_pct] column.
	 * 
	 * @param      int $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setDiscountPct($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->discount_pct !== $v || $v === 0) {
			$this->discount_pct = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::DISCOUNT_PCT;
		}

		return $this;
	} // setDiscountPct()

	/**
	 * Set the value of [po_num] column.
	 * 
	 * @param      string $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setPoNum($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->po_num !== $v) {
			$this->po_num = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::PO_NUM;
		}

		return $this;
	} // setPoNum()

	public function setDivision($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->division !== $v) {
			$this->division = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::DIVISION;
		}

		return $this;
	} 

	/**
	 * Set the value of [boat_name] column.
	 * 
	 * @param      string $v new value
	 * @return     CustomerOrder The current object (for fluent API support)
	 */
	public function setBoatName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->boat_name !== $v) {
			$this->boat_name = $v;
			$this->modifiedColumns[] = CustomerOrderPeer::BOAT_NAME;
		}

		return $this;
	} // setBoatName()

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
			if (array_diff($this->modifiedColumns, array(CustomerOrderPeer::FINALIZED,CustomerOrderPeer::APPROVED,CustomerOrderPeer::SENT_SOME,CustomerOrderPeer::SENT_ALL,CustomerOrderPeer::INVOICE_PER_SHIPMENT,CustomerOrderPeer::HST_EXEMPT,CustomerOrderPeer::GST_EXEMPT,CustomerOrderPeer::PST_EXEMPT,CustomerOrderPeer::FOR_RIGGING,CustomerOrderPeer::DISCOUNT_PCT))) {
				return false;
			}

			if ($this->finalized !== false) {
				return false;
			}

			if ($this->approved !== false) {
				return false;
			}

			if ($this->sent_some !== false) {
				return false;
			}

			if ($this->sent_all !== false) {
				return false;
			}

			if ($this->invoice_per_shipment !== false) {
				return false;
			}

			if ($this->hst_exempt !== false) {
				return false;
			}

			if ($this->gst_exempt !== false) {
				return false;
			}

			if ($this->pst_exempt !== false) {
				return false;
			}

			if ($this->for_rigging !== false) {
				return false;
			}

			if ($this->discount_pct !== 0) {
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
			$this->customer_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->finalized = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
			$this->approved = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->sent_some = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->sent_all = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->invoice_per_shipment = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->invoice_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->date_ordered = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->hst_exempt = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
			$this->gst_exempt = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
			$this->pst_exempt = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
			$this->for_rigging = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
			$this->discount_pct = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
			$this->po_num = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->boat_name = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->division = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 16; // 16 = CustomerOrderPeer::NUM_COLUMNS - CustomerOrderPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating CustomerOrder object", $e);
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

		if ($this->aCustomer !== null && $this->customer_id !== $this->aCustomer->getId()) {
			$this->aCustomer = null;
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
			$con = Propel::getConnection(CustomerOrderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = CustomerOrderPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aCustomer = null;
			$this->aInvoice = null;
			$this->collCustomerReturns = null;
			$this->lastCustomerReturnCriteria = null;

			$this->collCustomerOrderItems = null;
			$this->lastCustomerOrderItemCriteria = null;

			$this->collPayments = null;
			$this->lastPaymentCriteria = null;

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

    foreach (sfMixer::getCallables('BaseCustomerOrder:delete:pre') as $callable)
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
			$con = Propel::getConnection(CustomerOrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			CustomerOrderPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseCustomerOrder:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseCustomerOrder:save:pre') as $callable)
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
			$con = Propel::getConnection(CustomerOrderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseCustomerOrder:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			CustomerOrderPeer::addInstanceToPool($this);
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

			if ($this->aCustomer !== null) {
				if ($this->aCustomer->isModified() || $this->aCustomer->isNew()) {
					$affectedRows += $this->aCustomer->save($con);
				}
				$this->setCustomer($this->aCustomer);
			}

			if ($this->aInvoice !== null) {
				if ($this->aInvoice->isModified() || $this->aInvoice->isNew()) {
					$affectedRows += $this->aInvoice->save($con);
				}
				$this->setInvoice($this->aInvoice);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = CustomerOrderPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CustomerOrderPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += CustomerOrderPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collCustomerReturns !== null) {
				foreach ($this->collCustomerReturns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCustomerOrderItems !== null) {
				foreach ($this->collCustomerOrderItems as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPayments !== null) {
				foreach ($this->collPayments as $referrerFK) {
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

			if ($this->aCustomer !== null) {
				if (!$this->aCustomer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCustomer->getValidationFailures());
				}
			}

			if ($this->aInvoice !== null) {
				if (!$this->aInvoice->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aInvoice->getValidationFailures());
				}
			}


			if (($retval = CustomerOrderPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collCustomerReturns !== null) {
					foreach ($this->collCustomerReturns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCustomerOrderItems !== null) {
					foreach ($this->collCustomerOrderItems as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPayments !== null) {
					foreach ($this->collPayments as $referrerFK) {
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
		$pos = CustomerOrderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCustomerId();
				break;
			case 2:
				return $this->getFinalized();
				break;
			case 3:
				return $this->getApproved();
				break;
			case 4:
				return $this->getSentSome();
				break;
			case 5:
				return $this->getSentAll();
				break;
			case 6:
				return $this->getInvoicePerShipment();
				break;
			case 7:
				return $this->getInvoiceId();
				break;
			case 8:
				return $this->getDateOrdered();
				break;
			case 9:
				return $this->getHstExempt();
				break;
			case 10:
				return $this->getGstExempt();
				break;
			case 11:
				return $this->getPstExempt();
				break;
			case 12:
				return $this->getForRigging();
				break;
			case 13:
				return $this->getDiscountPct();
				break;
			case 14:
				return $this->getPoNum();
				break;
			case 15:
				return $this->getBoatName();
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
		$keys = CustomerOrderPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCustomerId(),
			$keys[2] => $this->getFinalized(),
			$keys[3] => $this->getApproved(),
			$keys[4] => $this->getSentSome(),
			$keys[5] => $this->getSentAll(),
			$keys[6] => $this->getInvoicePerShipment(),
			$keys[7] => $this->getInvoiceId(),
			$keys[8] => $this->getDateOrdered(),
			$keys[9] => $this->getHstExempt(),
			$keys[10] => $this->getGstExempt(),
			$keys[11] => $this->getPstExempt(),
			$keys[12] => $this->getForRigging(),
			$keys[13] => $this->getDiscountPct(),
			$keys[14] => $this->getPoNum(),
			$keys[15] => $this->getBoatName(),
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
		$pos = CustomerOrderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCustomerId($value);
				break;
			case 2:
				$this->setFinalized($value);
				break;
			case 3:
				$this->setApproved($value);
				break;
			case 4:
				$this->setSentSome($value);
				break;
			case 5:
				$this->setSentAll($value);
				break;
			case 6:
				$this->setInvoicePerShipment($value);
				break;
			case 7:
				$this->setInvoiceId($value);
				break;
			case 8:
				$this->setDateOrdered($value);
				break;
			case 9:
				$this->setHstExempt($value);
				break;
			case 10:
				$this->setGstExempt($value);
				break;
			case 11:
				$this->setPstExempt($value);
				break;
			case 12:
				$this->setForRigging($value);
				break;
			case 13:
				$this->setDiscountPct($value);
				break;
			case 14:
				$this->setPoNum($value);
				break;
			case 15:
				$this->setBoatName($value);
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
		$keys = CustomerOrderPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCustomerId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setFinalized($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setApproved($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setSentSome($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSentAll($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setInvoicePerShipment($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setInvoiceId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setDateOrdered($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setHstExempt($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setGstExempt($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setPstExempt($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setForRigging($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setDiscountPct($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setPoNum($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setBoatName($arr[$keys[15]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);

		if ($this->isColumnModified(CustomerOrderPeer::ID)) $criteria->add(CustomerOrderPeer::ID, $this->id);
		if ($this->isColumnModified(CustomerOrderPeer::CUSTOMER_ID)) $criteria->add(CustomerOrderPeer::CUSTOMER_ID, $this->customer_id);
		if ($this->isColumnModified(CustomerOrderPeer::FINALIZED)) $criteria->add(CustomerOrderPeer::FINALIZED, $this->finalized);
		if ($this->isColumnModified(CustomerOrderPeer::APPROVED)) $criteria->add(CustomerOrderPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(CustomerOrderPeer::SENT_SOME)) $criteria->add(CustomerOrderPeer::SENT_SOME, $this->sent_some);
		if ($this->isColumnModified(CustomerOrderPeer::SENT_ALL)) $criteria->add(CustomerOrderPeer::SENT_ALL, $this->sent_all);
		if ($this->isColumnModified(CustomerOrderPeer::INVOICE_PER_SHIPMENT)) $criteria->add(CustomerOrderPeer::INVOICE_PER_SHIPMENT, $this->invoice_per_shipment);
		if ($this->isColumnModified(CustomerOrderPeer::INVOICE_ID)) $criteria->add(CustomerOrderPeer::INVOICE_ID, $this->invoice_id);
		if ($this->isColumnModified(CustomerOrderPeer::DATE_ORDERED)) $criteria->add(CustomerOrderPeer::DATE_ORDERED, $this->date_ordered);
		if ($this->isColumnModified(CustomerOrderPeer::HST_EXEMPT)) $criteria->add(CustomerOrderPeer::HST_EXEMPT, $this->hst_exempt);
		if ($this->isColumnModified(CustomerOrderPeer::GST_EXEMPT)) $criteria->add(CustomerOrderPeer::GST_EXEMPT, $this->gst_exempt);
		if ($this->isColumnModified(CustomerOrderPeer::PST_EXEMPT)) $criteria->add(CustomerOrderPeer::PST_EXEMPT, $this->pst_exempt);
		if ($this->isColumnModified(CustomerOrderPeer::FOR_RIGGING)) $criteria->add(CustomerOrderPeer::FOR_RIGGING, $this->for_rigging);
		if ($this->isColumnModified(CustomerOrderPeer::DISCOUNT_PCT)) $criteria->add(CustomerOrderPeer::DISCOUNT_PCT, $this->discount_pct);
		if ($this->isColumnModified(CustomerOrderPeer::PO_NUM)) $criteria->add(CustomerOrderPeer::PO_NUM, $this->po_num);
		if ($this->isColumnModified(CustomerOrderPeer::BOAT_NAME)) $criteria->add(CustomerOrderPeer::BOAT_NAME, $this->boat_name);
		if ($this->isColumnModified(CustomerOrderPeer::DIVISION)) $criteria->add(CustomerOrderPeer::DIVISION, $this->division);
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
		$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);

		$criteria->add(CustomerOrderPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of CustomerOrder (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCustomerId($this->customer_id);

		$copyObj->setFinalized($this->finalized);

		$copyObj->setApproved($this->approved);

		$copyObj->setSentSome($this->sent_some);

		$copyObj->setSentAll($this->sent_all);

		$copyObj->setInvoicePerShipment($this->invoice_per_shipment);

		$copyObj->setInvoiceId($this->invoice_id);

		$copyObj->setDateOrdered($this->date_ordered);

		$copyObj->setHstExempt($this->hst_exempt);

		$copyObj->setGstExempt($this->gst_exempt);

		$copyObj->setPstExempt($this->pst_exempt);

		$copyObj->setForRigging($this->for_rigging);

		$copyObj->setDiscountPct($this->discount_pct);

		$copyObj->setPoNum($this->po_num);

		$copyObj->setBoatName($this->boat_name);

		$copyObj->setBoatName($this->division);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getCustomerReturns() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerReturn($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomerOrderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerOrderItem($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPayments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPayment($relObj->copy($deepCopy));
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
	 * @return     CustomerOrder Clone of current object.
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
	 * @return     CustomerOrderPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new CustomerOrderPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Customer object.
	 *
	 * @param      Customer $v
	 * @return     CustomerOrder The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setCustomer(Customer $v = null)
	{
		if ($v === null) {
			$this->setCustomerId(NULL);
		} else {
			$this->setCustomerId($v->getId());
		}

		$this->aCustomer = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Customer object, it will not be re-added.
		if ($v !== null) {
			$v->addCustomerOrder($this);
		}

		return $this;
	}


	/**
	 * Get the associated Customer object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Customer The associated Customer object.
	 * @throws     PropelException
	 */
	public function getCustomer(PropelPDO $con = null)
	{
		if ($this->aCustomer === null && ($this->customer_id !== null)) {
			$c = new Criteria(CustomerPeer::DATABASE_NAME);
			$c->add(CustomerPeer::ID, $this->customer_id);
			$this->aCustomer = CustomerPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aCustomer->addCustomerOrders($this);
			 */
		}
		return $this->aCustomer;
	}

	/**
	 * Declares an association between this object and a Invoice object.
	 *
	 * @param      Invoice $v
	 * @return     CustomerOrder The current object (for fluent API support)
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
			$v->addCustomerOrder($this);
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
			   $this->aInvoice->addCustomerOrders($this);
			 */
		}
		return $this->aInvoice;
	}

	/**
	 * Clears out the collCustomerReturns collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomerReturns()
	 */
	public function clearCustomerReturns()
	{
		$this->collCustomerReturns = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomerReturns collection (array).
	 *
	 * By default this just sets the collCustomerReturns collection to an empty array (like clearcollCustomerReturns());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomerReturns()
	{
		$this->collCustomerReturns = array();
	}

	/**
	 * Gets an array of CustomerReturn objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this CustomerOrder has previously been saved, it will retrieve
	 * related CustomerReturns from storage. If this CustomerOrder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array CustomerReturn[]
	 * @throws     PropelException
	 */
	public function getCustomerReturns($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturns === null) {
			if ($this->isNew()) {
			   $this->collCustomerReturns = array();
			} else {

				$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

				CustomerReturnPeer::addSelectColumns($criteria);
				$this->collCustomerReturns = CustomerReturnPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

				CustomerReturnPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerReturnCriteria) || !$this->lastCustomerReturnCriteria->equals($criteria)) {
					$this->collCustomerReturns = CustomerReturnPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerReturnCriteria = $criteria;
		return $this->collCustomerReturns;
	}

	/**
	 * Returns the number of related CustomerReturn objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CustomerReturn objects.
	 * @throws     PropelException
	 */
	public function countCustomerReturns(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomerReturns === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

				$count = CustomerReturnPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

				if (!isset($this->lastCustomerReturnCriteria) || !$this->lastCustomerReturnCriteria->equals($criteria)) {
					$count = CustomerReturnPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomerReturns);
				}
			} else {
				$count = count($this->collCustomerReturns);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a CustomerReturn object to this object
	 * through the CustomerReturn foreign key attribute.
	 *
	 * @param      CustomerReturn $l CustomerReturn
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomerReturn(CustomerReturn $l)
	{
		if ($this->collCustomerReturns === null) {
			$this->initCustomerReturns();
		}
		if (!in_array($l, $this->collCustomerReturns, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomerReturns, $l);
			$l->setCustomerOrder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this CustomerOrder is new, it will return
	 * an empty collection; or if this CustomerOrder has previously
	 * been saved, it will retrieve related CustomerReturns from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in CustomerOrder.
	 */
	public function getCustomerReturnsJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturns === null) {
			if ($this->isNew()) {
				$this->collCustomerReturns = array();
			} else {

				$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

				$this->collCustomerReturns = CustomerReturnPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->id);

			if (!isset($this->lastCustomerReturnCriteria) || !$this->lastCustomerReturnCriteria->equals($criteria)) {
				$this->collCustomerReturns = CustomerReturnPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerReturnCriteria = $criteria;

		return $this->collCustomerReturns;
	}

	/**
	 * Clears out the collCustomerOrderItems collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomerOrderItems()
	 */
	public function clearCustomerOrderItems()
	{
		$this->collCustomerOrderItems = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomerOrderItems collection (array).
	 *
	 * By default this just sets the collCustomerOrderItems collection to an empty array (like clearcollCustomerOrderItems());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomerOrderItems()
	{
		$this->collCustomerOrderItems = array();
	}

	/**
	 * Gets an array of CustomerOrderItem objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this CustomerOrder has previously been saved, it will retrieve
	 * related CustomerOrderItems from storage. If this CustomerOrder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array CustomerOrderItem[]
	 * @throws     PropelException
	 */
	public function getCustomerOrderItems($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrderItems === null) {
			if ($this->isNew()) {
			   $this->collCustomerOrderItems = array();
			} else {

				$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

				CustomerOrderItemPeer::addSelectColumns($criteria);
				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

				CustomerOrderItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerOrderItemCriteria) || !$this->lastCustomerOrderItemCriteria->equals($criteria)) {
					$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerOrderItemCriteria = $criteria;
		return $this->collCustomerOrderItems;
	}

	/**
	 * Returns the number of related CustomerOrderItem objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CustomerOrderItem objects.
	 * @throws     PropelException
	 */
	public function countCustomerOrderItems(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomerOrderItems === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

				$count = CustomerOrderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

				if (!isset($this->lastCustomerOrderItemCriteria) || !$this->lastCustomerOrderItemCriteria->equals($criteria)) {
					$count = CustomerOrderItemPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomerOrderItems);
				}
			} else {
				$count = count($this->collCustomerOrderItems);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a CustomerOrderItem object to this object
	 * through the CustomerOrderItem foreign key attribute.
	 *
	 * @param      CustomerOrderItem $l CustomerOrderItem
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomerOrderItem(CustomerOrderItem $l)
	{
		if ($this->collCustomerOrderItems === null) {
			$this->initCustomerOrderItems();
		}
		if (!in_array($l, $this->collCustomerOrderItems, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomerOrderItems, $l);
			$l->setCustomerOrder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this CustomerOrder is new, it will return
	 * an empty collection; or if this CustomerOrder has previously
	 * been saved, it will retrieve related CustomerOrderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in CustomerOrder.
	 */
	public function getCustomerOrderItemsJoinPartInstance($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrderItems === null) {
			if ($this->isNew()) {
				$this->collCustomerOrderItems = array();
			} else {

				$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelectJoinPartInstance($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->id);

			if (!isset($this->lastCustomerOrderItemCriteria) || !$this->lastCustomerOrderItemCriteria->equals($criteria)) {
				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelectJoinPartInstance($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerOrderItemCriteria = $criteria;

		return $this->collCustomerOrderItems;
	}

	/**
	 * Clears out the collPayments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPayments()
	 */
	public function clearPayments()
	{
		$this->collPayments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPayments collection (array).
	 *
	 * By default this just sets the collPayments collection to an empty array (like clearcollPayments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPayments()
	{
		$this->collPayments = array();
	}

	/**
	 * Gets an array of Payment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this CustomerOrder has previously been saved, it will retrieve
	 * related Payments from storage. If this CustomerOrder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Payment[]
	 * @throws     PropelException
	 */
	public function getPayments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPayments === null) {
			if ($this->isNew()) {
			   $this->collPayments = array();
			} else {

				$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

				PaymentPeer::addSelectColumns($criteria);
				$this->collPayments = PaymentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

				PaymentPeer::addSelectColumns($criteria);
				if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
					$this->collPayments = PaymentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPaymentCriteria = $criteria;
		return $this->collPayments;
	}

	/**
	 * Returns the number of related Payment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Payment objects.
	 * @throws     PropelException
	 */
	public function countPayments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPayments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

				$count = PaymentPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

				if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
					$count = PaymentPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPayments);
				}
			} else {
				$count = count($this->collPayments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Payment object to this object
	 * through the Payment foreign key attribute.
	 *
	 * @param      Payment $l Payment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPayment(Payment $l)
	{
		if ($this->collPayments === null) {
			$this->initPayments();
		}
		if (!in_array($l, $this->collPayments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPayments, $l);
			$l->setCustomerOrder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this CustomerOrder is new, it will return
	 * an empty collection; or if this CustomerOrder has previously
	 * been saved, it will retrieve related Payments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in CustomerOrder.
	 */
	public function getPaymentsJoinWorkorder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CustomerOrderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPayments === null) {
			if ($this->isNew()) {
				$this->collPayments = array();
			} else {

				$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

				$this->collPayments = PaymentPeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PaymentPeer::CUSTOMER_ORDER_ID, $this->id);

			if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
				$this->collPayments = PaymentPeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		}
		$this->lastPaymentCriteria = $criteria;

		return $this->collPayments;
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
			if ($this->collCustomerReturns) {
				foreach ((array) $this->collCustomerReturns as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomerOrderItems) {
				foreach ((array) $this->collCustomerOrderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPayments) {
				foreach ((array) $this->collPayments as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collCustomerReturns = null;
		$this->collCustomerOrderItems = null;
		$this->collPayments = null;
			$this->aCustomer = null;
			$this->aInvoice = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseCustomerOrder:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseCustomerOrder::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseCustomerOrder
