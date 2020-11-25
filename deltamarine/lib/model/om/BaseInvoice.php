<?php

/**
 * Base class that represents a row from the 'invoice' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseInvoice extends BaseObject  implements Persistent {


  const PEER = 'InvoicePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        InvoicePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the receivable field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $receivable;

	/**
	 * The value for the customer_id field.
	 * @var        int
	 */
	protected $customer_id;

	/**
	 * The value for the supplier_id field.
	 * @var        int
	 */
	protected $supplier_id;

	/**
	 * The value for the manufacturer_id field.
	 * @var        int
	 */
	protected $manufacturer_id;

	/**
	 * The value for the subtotal field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $subtotal;

	/**
	 * The value for the shipping field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $shipping;

	/**
	 * The value for the hst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $hst;

	/**
	 * The value for the gst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $gst;

	/**
	 * The value for the pst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $pst;

	/**
	 * The value for the enviro_levy field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $enviro_levy;

	/**
	 * The value for the battery_levy field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $battery_levy;

	/**
	 * The value for the duties field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $duties;

	/**
	 * The value for the total field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $total;

	/**
	 * The value for the issued_date field.
	 * @var        string
	 */
	protected $issued_date;

	/**
	 * The value for the payable_date field.
	 * @var        string
	 */
	protected $payable_date;

	/**
	 * The value for the archived field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $archived;

	/**
	 * @var        Customer
	 */
	protected $aCustomer;

	/**
	 * @var        Supplier
	 */
	protected $aSupplier;

	/**
	 * @var        Manufacturer
	 */
	protected $aManufacturer;

	/**
	 * @var        array SupplierOrder[] Collection to store aggregation of SupplierOrder objects.
	 */
	protected $collSupplierOrders;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSupplierOrders.
	 */
	private $lastSupplierOrderCriteria = null;

	/**
	 * @var        array CustomerOrder[] Collection to store aggregation of CustomerOrder objects.
	 */
	protected $collCustomerOrders;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerOrders.
	 */
	private $lastCustomerOrderCriteria = null;

	/**
	 * @var        array CustomerReturn[] Collection to store aggregation of CustomerReturn objects.
	 */
	protected $collCustomerReturns;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerReturns.
	 */
	private $lastCustomerReturnCriteria = null;

	/**
	 * @var        array PartInstance[] Collection to store aggregation of PartInstance objects.
	 */
	protected $collPartInstances;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartInstances.
	 */
	private $lastPartInstanceCriteria = null;

	/**
	 * @var        array Shipment[] Collection to store aggregation of Shipment objects.
	 */
	protected $collShipments;

	/**
	 * @var        Criteria The criteria used to select the current contents of collShipments.
	 */
	private $lastShipmentCriteria = null;

	/**
	 * @var        array WorkorderExpense[] Collection to store aggregation of WorkorderExpense objects.
	 */
	protected $collWorkorderExpenses;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderExpenses.
	 */
	private $lastWorkorderExpenseCriteria = null;

	/**
	 * @var        array WorkorderInvoice[] Collection to store aggregation of WorkorderInvoice objects.
	 */
	protected $collWorkorderInvoices;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderInvoices.
	 */
	private $lastWorkorderInvoiceCriteria = null;

	/**
	 * @var        array Timelog[] Collection to store aggregation of Timelog objects.
	 */
	protected $collTimelogs;

	/**
	 * @var        Criteria The criteria used to select the current contents of collTimelogs.
	 */
	private $lastTimelogCriteria = null;

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
	 * Initializes internal state of BaseInvoice object.
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
		$this->receivable = true;
		$this->subtotal = '0';
		$this->shipping = '0';
		$this->hst = '0';
		$this->gst = '0';
		$this->pst = '0';
		$this->enviro_levy = '0';
		$this->battery_levy = '0';
		$this->duties = '0';
		$this->total = '0';
		$this->archived = false;
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
	 * Get the [receivable] column value.
	 * 
	 * @return     boolean
	 */
	public function getReceivable()
	{
		return $this->receivable;
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
	 * Get the [supplier_id] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierId()
	{
		return $this->supplier_id;
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
	 * Get the [subtotal] column value.
	 * 
	 * @return     string
	 */
	public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	 * Get the [shipping] column value.
	 * 
	 * @return     string
	 */
	public function getShipping()
	{
		return $this->shipping;
	}

	/**
	 * Get the [hst] column value.
	 * 
	 * @return     string
	 */
	public function getHst()
	{
		return $this->hst;
	}

	/**
	 * Get the [gst] column value.
	 * 
	 * @return     string
	 */
	public function getGst()
	{
		return $this->gst;
	}

	/**
	 * Get the [pst] column value.
	 * 
	 * @return     string
	 */
	public function getPst()
	{
		return $this->pst;
	}

	/**
	 * Get the [enviro_levy] column value.
	 * 
	 * @return     string
	 */
	public function getEnviroLevy()
	{
		return $this->enviro_levy;
	}

	/**
	 * Get the [battery_levy] column value.
	 * 
	 * @return     string
	 */
	public function getBatteryLevy()
	{
		return $this->battery_levy;
	}

	/**
	 * Get the [duties] column value.
	 * 
	 * @return     string
	 */
	public function getDuties()
	{
		return $this->duties;
	}

	/**
	 * Get the [total] column value.
	 * 
	 * @return     string
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * Get the [optionally formatted] temporal [issued_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getIssuedDate($format = 'Y-m-d H:i:s')
	{
		if ($this->issued_date === null) {
			return null;
		}


		if ($this->issued_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->issued_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->issued_date, true), $x);
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
	 * Get the [optionally formatted] temporal [payable_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getPayableDate($format = 'Y-m-d H:i:s')
	{
		if ($this->payable_date === null) {
			return null;
		}


		if ($this->payable_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->payable_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->payable_date, true), $x);
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
	 * Get the [archived] column value.
	 * 
	 * @return     boolean
	 */
	public function getArchived()
	{
		return $this->archived;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = InvoicePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [receivable] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setReceivable($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->receivable !== $v || $v === true) {
			$this->receivable = $v;
			$this->modifiedColumns[] = InvoicePeer::RECEIVABLE;
		}

		return $this;
	} // setReceivable()

	/**
	 * Set the value of [customer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setCustomerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_id !== $v) {
			$this->customer_id = $v;
			$this->modifiedColumns[] = InvoicePeer::CUSTOMER_ID;
		}

		if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
			$this->aCustomer = null;
		}

		return $this;
	} // setCustomerId()

	/**
	 * Set the value of [supplier_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setSupplierId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_id !== $v) {
			$this->supplier_id = $v;
			$this->modifiedColumns[] = InvoicePeer::SUPPLIER_ID;
		}

		if ($this->aSupplier !== null && $this->aSupplier->getId() !== $v) {
			$this->aSupplier = null;
		}

		return $this;
	} // setSupplierId()

	/**
	 * Set the value of [manufacturer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setManufacturerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->manufacturer_id !== $v) {
			$this->manufacturer_id = $v;
			$this->modifiedColumns[] = InvoicePeer::MANUFACTURER_ID;
		}

		if ($this->aManufacturer !== null && $this->aManufacturer->getId() !== $v) {
			$this->aManufacturer = null;
		}

		return $this;
	} // setManufacturerId()

	/**
	 * Set the value of [subtotal] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setSubtotal($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->subtotal !== $v || $v === '0') {
			$this->subtotal = $v;
			$this->modifiedColumns[] = InvoicePeer::SUBTOTAL;
		}

		return $this;
	} // setSubtotal()

	/**
	 * Set the value of [shipping] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setShipping($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping !== $v || $v === '0') {
			$this->shipping = $v;
			$this->modifiedColumns[] = InvoicePeer::SHIPPING;
		}

		return $this;
	} // setShipping()

	/**
	 * Set the value of [hst] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setHst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->hst !== $v || $v === '0') {
			$this->hst = $v;
			$this->modifiedColumns[] = InvoicePeer::HST;
		}

		return $this;
	} // setHst()

	/**
	 * Set the value of [gst] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setGst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->gst !== $v || $v === '0') {
			$this->gst = $v;
			$this->modifiedColumns[] = InvoicePeer::GST;
		}

		return $this;
	} // setGst()

	/**
	 * Set the value of [pst] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setPst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->pst !== $v || $v === '0') {
			$this->pst = $v;
			$this->modifiedColumns[] = InvoicePeer::PST;
		}

		return $this;
	} // setPst()

	/**
	 * Set the value of [enviro_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setEnviroLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->enviro_levy !== $v || $v === '0') {
			$this->enviro_levy = $v;
			$this->modifiedColumns[] = InvoicePeer::ENVIRO_LEVY;
		}

		return $this;
	} // setEnviroLevy()

	/**
	 * Set the value of [battery_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setBatteryLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->battery_levy !== $v || $v === '0') {
			$this->battery_levy = $v;
			$this->modifiedColumns[] = InvoicePeer::BATTERY_LEVY;
		}

		return $this;
	} // setBatteryLevy()

	/**
	 * Set the value of [duties] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setDuties($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->duties !== $v || $v === '0') {
			$this->duties = $v;
			$this->modifiedColumns[] = InvoicePeer::DUTIES;
		}

		return $this;
	} // setDuties()

	/**
	 * Set the value of [total] column.
	 * 
	 * @param      string $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setTotal($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->total !== $v || $v === '0') {
			$this->total = $v;
			$this->modifiedColumns[] = InvoicePeer::TOTAL;
		}

		return $this;
	} // setTotal()

	/**
	 * Sets the value of [issued_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setIssuedDate($v)
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

		if ( $this->issued_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->issued_date !== null && $tmpDt = new DateTime($this->issued_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->issued_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = InvoicePeer::ISSUED_DATE;
			}
		} // if either are not null

		return $this;
	} // setIssuedDate()

	/**
	 * Sets the value of [payable_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setPayableDate($v)
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

		if ( $this->payable_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->payable_date !== null && $tmpDt = new DateTime($this->payable_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->payable_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = InvoicePeer::PAYABLE_DATE;
			}
		} // if either are not null

		return $this;
	} // setPayableDate()

	/**
	 * Set the value of [archived] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Invoice The current object (for fluent API support)
	 */
	public function setArchived($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->archived !== $v || $v === false) {
			$this->archived = $v;
			$this->modifiedColumns[] = InvoicePeer::ARCHIVED;
		}

		return $this;
	} // setArchived()

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
			if (array_diff($this->modifiedColumns, array(InvoicePeer::RECEIVABLE,InvoicePeer::SUBTOTAL,InvoicePeer::SHIPPING,InvoicePeer::HST,InvoicePeer::GST,InvoicePeer::PST,InvoicePeer::ENVIRO_LEVY,InvoicePeer::BATTERY_LEVY,InvoicePeer::DUTIES,InvoicePeer::TOTAL,InvoicePeer::ARCHIVED))) {
				return false;
			}

			if ($this->receivable !== true) {
				return false;
			}

			if ($this->subtotal !== '0') {
				return false;
			}

			if ($this->shipping !== '0') {
				return false;
			}

			if ($this->hst !== '0') {
				return false;
			}

			if ($this->gst !== '0') {
				return false;
			}

			if ($this->pst !== '0') {
				return false;
			}

			if ($this->enviro_levy !== '0') {
				return false;
			}

			if ($this->battery_levy !== '0') {
				return false;
			}

			if ($this->duties !== '0') {
				return false;
			}

			if ($this->total !== '0') {
				return false;
			}

			if ($this->archived !== false) {
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
			$this->receivable = ($row[$startcol + 1] !== null) ? (boolean) $row[$startcol + 1] : null;
			$this->customer_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->supplier_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->manufacturer_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->subtotal = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->shipping = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->hst = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->gst = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->pst = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->enviro_levy = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->battery_levy = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->duties = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->total = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->issued_date = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->payable_date = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->archived = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 17; // 17 = InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Invoice object", $e);
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
		if ($this->aSupplier !== null && $this->supplier_id !== $this->aSupplier->getId()) {
			$this->aSupplier = null;
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
			$con = Propel::getConnection(InvoicePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = InvoicePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aCustomer = null;
			$this->aSupplier = null;
			$this->aManufacturer = null;
			$this->collSupplierOrders = null;
			$this->lastSupplierOrderCriteria = null;

			$this->collCustomerOrders = null;
			$this->lastCustomerOrderCriteria = null;

			$this->collCustomerReturns = null;
			$this->lastCustomerReturnCriteria = null;

			$this->collPartInstances = null;
			$this->lastPartInstanceCriteria = null;

			$this->collShipments = null;
			$this->lastShipmentCriteria = null;

			$this->collWorkorderExpenses = null;
			$this->lastWorkorderExpenseCriteria = null;

			$this->collWorkorderInvoices = null;
			$this->lastWorkorderInvoiceCriteria = null;

			$this->collTimelogs = null;
			$this->lastTimelogCriteria = null;

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

    foreach (sfMixer::getCallables('BaseInvoice:delete:pre') as $callable)
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
			$con = Propel::getConnection(InvoicePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			InvoicePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseInvoice:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseInvoice:save:pre') as $callable)
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
			$con = Propel::getConnection(InvoicePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseInvoice:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			InvoicePeer::addInstanceToPool($this);
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

			if ($this->aSupplier !== null) {
				if ($this->aSupplier->isModified() || $this->aSupplier->isNew()) {
					$affectedRows += $this->aSupplier->save($con);
				}
				$this->setSupplier($this->aSupplier);
			}

			if ($this->aManufacturer !== null) {
				if ($this->aManufacturer->isModified() || $this->aManufacturer->isNew()) {
					$affectedRows += $this->aManufacturer->save($con);
				}
				$this->setManufacturer($this->aManufacturer);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = InvoicePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = InvoicePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += InvoicePeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collSupplierOrders !== null) {
				foreach ($this->collSupplierOrders as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCustomerOrders !== null) {
				foreach ($this->collCustomerOrders as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCustomerReturns !== null) {
				foreach ($this->collCustomerReturns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartInstances !== null) {
				foreach ($this->collPartInstances as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collShipments !== null) {
				foreach ($this->collShipments as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderExpenses !== null) {
				foreach ($this->collWorkorderExpenses as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderInvoices !== null) {
				foreach ($this->collWorkorderInvoices as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collTimelogs !== null) {
				foreach ($this->collTimelogs as $referrerFK) {
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

			if ($this->aSupplier !== null) {
				if (!$this->aSupplier->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSupplier->getValidationFailures());
				}
			}

			if ($this->aManufacturer !== null) {
				if (!$this->aManufacturer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aManufacturer->getValidationFailures());
				}
			}


			if (($retval = InvoicePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collSupplierOrders !== null) {
					foreach ($this->collSupplierOrders as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCustomerOrders !== null) {
					foreach ($this->collCustomerOrders as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCustomerReturns !== null) {
					foreach ($this->collCustomerReturns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartInstances !== null) {
					foreach ($this->collPartInstances as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collShipments !== null) {
					foreach ($this->collShipments as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderExpenses !== null) {
					foreach ($this->collWorkorderExpenses as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderInvoices !== null) {
					foreach ($this->collWorkorderInvoices as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collTimelogs !== null) {
					foreach ($this->collTimelogs as $referrerFK) {
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
		$pos = InvoicePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getReceivable();
				break;
			case 2:
				return $this->getCustomerId();
				break;
			case 3:
				return $this->getSupplierId();
				break;
			case 4:
				return $this->getManufacturerId();
				break;
			case 5:
				return $this->getSubtotal();
				break;
			case 6:
				return $this->getShipping();
				break;
			case 7:
				return $this->getHst();
				break;
			case 8:
				return $this->getGst();
				break;
			case 9:
				return $this->getPst();
				break;
			case 10:
				return $this->getEnviroLevy();
				break;
			case 11:
				return $this->getBatteryLevy();
				break;
			case 12:
				return $this->getDuties();
				break;
			case 13:
				return $this->getTotal();
				break;
			case 14:
				return $this->getIssuedDate();
				break;
			case 15:
				return $this->getPayableDate();
				break;
			case 16:
				return $this->getArchived();
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
		$keys = InvoicePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getReceivable(),
			$keys[2] => $this->getCustomerId(),
			$keys[3] => $this->getSupplierId(),
			$keys[4] => $this->getManufacturerId(),
			$keys[5] => $this->getSubtotal(),
			$keys[6] => $this->getShipping(),
			$keys[7] => $this->getHst(),
			$keys[8] => $this->getGst(),
			$keys[9] => $this->getPst(),
			$keys[10] => $this->getEnviroLevy(),
			$keys[11] => $this->getBatteryLevy(),
			$keys[12] => $this->getDuties(),
			$keys[13] => $this->getTotal(),
			$keys[14] => $this->getIssuedDate(),
			$keys[15] => $this->getPayableDate(),
			$keys[16] => $this->getArchived(),
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
		$pos = InvoicePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setReceivable($value);
				break;
			case 2:
				$this->setCustomerId($value);
				break;
			case 3:
				$this->setSupplierId($value);
				break;
			case 4:
				$this->setManufacturerId($value);
				break;
			case 5:
				$this->setSubtotal($value);
				break;
			case 6:
				$this->setShipping($value);
				break;
			case 7:
				$this->setHst($value);
				break;
			case 8:
				$this->setGst($value);
				break;
			case 9:
				$this->setPst($value);
				break;
			case 10:
				$this->setEnviroLevy($value);
				break;
			case 11:
				$this->setBatteryLevy($value);
				break;
			case 12:
				$this->setDuties($value);
				break;
			case 13:
				$this->setTotal($value);
				break;
			case 14:
				$this->setIssuedDate($value);
				break;
			case 15:
				$this->setPayableDate($value);
				break;
			case 16:
				$this->setArchived($value);
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
		$keys = InvoicePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setReceivable($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCustomerId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setSupplierId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setManufacturerId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSubtotal($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setShipping($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHst($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setGst($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setPst($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setEnviroLevy($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setBatteryLevy($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setDuties($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setTotal($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setIssuedDate($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setPayableDate($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setArchived($arr[$keys[16]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(InvoicePeer::DATABASE_NAME);

		if ($this->isColumnModified(InvoicePeer::ID)) $criteria->add(InvoicePeer::ID, $this->id);
		if ($this->isColumnModified(InvoicePeer::RECEIVABLE)) $criteria->add(InvoicePeer::RECEIVABLE, $this->receivable);
		if ($this->isColumnModified(InvoicePeer::CUSTOMER_ID)) $criteria->add(InvoicePeer::CUSTOMER_ID, $this->customer_id);
		if ($this->isColumnModified(InvoicePeer::SUPPLIER_ID)) $criteria->add(InvoicePeer::SUPPLIER_ID, $this->supplier_id);
		if ($this->isColumnModified(InvoicePeer::MANUFACTURER_ID)) $criteria->add(InvoicePeer::MANUFACTURER_ID, $this->manufacturer_id);
		if ($this->isColumnModified(InvoicePeer::SUBTOTAL)) $criteria->add(InvoicePeer::SUBTOTAL, $this->subtotal);
		if ($this->isColumnModified(InvoicePeer::SHIPPING)) $criteria->add(InvoicePeer::SHIPPING, $this->shipping);
		if ($this->isColumnModified(InvoicePeer::HST)) $criteria->add(InvoicePeer::HST, $this->hst);
		if ($this->isColumnModified(InvoicePeer::GST)) $criteria->add(InvoicePeer::GST, $this->gst);
		if ($this->isColumnModified(InvoicePeer::PST)) $criteria->add(InvoicePeer::PST, $this->pst);
		if ($this->isColumnModified(InvoicePeer::ENVIRO_LEVY)) $criteria->add(InvoicePeer::ENVIRO_LEVY, $this->enviro_levy);
		if ($this->isColumnModified(InvoicePeer::BATTERY_LEVY)) $criteria->add(InvoicePeer::BATTERY_LEVY, $this->battery_levy);
		if ($this->isColumnModified(InvoicePeer::DUTIES)) $criteria->add(InvoicePeer::DUTIES, $this->duties);
		if ($this->isColumnModified(InvoicePeer::TOTAL)) $criteria->add(InvoicePeer::TOTAL, $this->total);
		if ($this->isColumnModified(InvoicePeer::ISSUED_DATE)) $criteria->add(InvoicePeer::ISSUED_DATE, $this->issued_date);
		if ($this->isColumnModified(InvoicePeer::PAYABLE_DATE)) $criteria->add(InvoicePeer::PAYABLE_DATE, $this->payable_date);
		if ($this->isColumnModified(InvoicePeer::ARCHIVED)) $criteria->add(InvoicePeer::ARCHIVED, $this->archived);

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
		$criteria = new Criteria(InvoicePeer::DATABASE_NAME);

		$criteria->add(InvoicePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Invoice (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setReceivable($this->receivable);

		$copyObj->setCustomerId($this->customer_id);

		$copyObj->setSupplierId($this->supplier_id);

		$copyObj->setManufacturerId($this->manufacturer_id);

		$copyObj->setSubtotal($this->subtotal);

		$copyObj->setShipping($this->shipping);

		$copyObj->setHst($this->hst);

		$copyObj->setGst($this->gst);

		$copyObj->setPst($this->pst);

		$copyObj->setEnviroLevy($this->enviro_levy);

		$copyObj->setBatteryLevy($this->battery_levy);

		$copyObj->setDuties($this->duties);

		$copyObj->setTotal($this->total);

		$copyObj->setIssuedDate($this->issued_date);

		$copyObj->setPayableDate($this->payable_date);

		$copyObj->setArchived($this->archived);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getSupplierOrders() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSupplierOrder($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomerOrders() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerOrder($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomerReturns() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerReturn($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartInstances() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartInstance($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getShipments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addShipment($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderExpenses() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderExpense($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderInvoices() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderInvoice($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getTimelogs() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addTimelog($relObj->copy($deepCopy));
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
	 * @return     Invoice Clone of current object.
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
	 * @return     InvoicePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new InvoicePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Customer object.
	 *
	 * @param      Customer $v
	 * @return     Invoice The current object (for fluent API support)
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
			$v->addInvoice($this);
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
			   $this->aCustomer->addInvoices($this);
			 */
		}
		return $this->aCustomer;
	}

	/**
	 * Declares an association between this object and a Supplier object.
	 *
	 * @param      Supplier $v
	 * @return     Invoice The current object (for fluent API support)
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
			$v->addInvoice($this);
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
			   $this->aSupplier->addInvoices($this);
			 */
		}
		return $this->aSupplier;
	}

	/**
	 * Declares an association between this object and a Manufacturer object.
	 *
	 * @param      Manufacturer $v
	 * @return     Invoice The current object (for fluent API support)
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
			$v->addInvoice($this);
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
			   $this->aManufacturer->addInvoices($this);
			 */
		}
		return $this->aManufacturer;
	}

	/**
	 * Clears out the collSupplierOrders collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSupplierOrders()
	 */
	public function clearSupplierOrders()
	{
		$this->collSupplierOrders = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSupplierOrders collection (array).
	 *
	 * By default this just sets the collSupplierOrders collection to an empty array (like clearcollSupplierOrders());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSupplierOrders()
	{
		$this->collSupplierOrders = array();
	}

	/**
	 * Gets an array of SupplierOrder objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related SupplierOrders from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SupplierOrder[]
	 * @throws     PropelException
	 */
	public function getSupplierOrders($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrders === null) {
			if ($this->isNew()) {
			   $this->collSupplierOrders = array();
			} else {

				$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

				SupplierOrderPeer::addSelectColumns($criteria);
				$this->collSupplierOrders = SupplierOrderPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

				SupplierOrderPeer::addSelectColumns($criteria);
				if (!isset($this->lastSupplierOrderCriteria) || !$this->lastSupplierOrderCriteria->equals($criteria)) {
					$this->collSupplierOrders = SupplierOrderPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSupplierOrderCriteria = $criteria;
		return $this->collSupplierOrders;
	}

	/**
	 * Returns the number of related SupplierOrder objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SupplierOrder objects.
	 * @throws     PropelException
	 */
	public function countSupplierOrders(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSupplierOrders === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

				$count = SupplierOrderPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

				if (!isset($this->lastSupplierOrderCriteria) || !$this->lastSupplierOrderCriteria->equals($criteria)) {
					$count = SupplierOrderPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSupplierOrders);
				}
			} else {
				$count = count($this->collSupplierOrders);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a SupplierOrder object to this object
	 * through the SupplierOrder foreign key attribute.
	 *
	 * @param      SupplierOrder $l SupplierOrder
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSupplierOrder(SupplierOrder $l)
	{
		if ($this->collSupplierOrders === null) {
			$this->initSupplierOrders();
		}
		if (!in_array($l, $this->collSupplierOrders, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSupplierOrders, $l);
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related SupplierOrders from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getSupplierOrdersJoinSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrders === null) {
			if ($this->isNew()) {
				$this->collSupplierOrders = array();
			} else {

				$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

				$this->collSupplierOrders = SupplierOrderPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SupplierOrderPeer::INVOICE_ID, $this->id);

			if (!isset($this->lastSupplierOrderCriteria) || !$this->lastSupplierOrderCriteria->equals($criteria)) {
				$this->collSupplierOrders = SupplierOrderPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastSupplierOrderCriteria = $criteria;

		return $this->collSupplierOrders;
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
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related CustomerOrders from storage. If this Invoice is new, it will return
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
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrders === null) {
			if ($this->isNew()) {
			   $this->collCustomerOrders = array();
			} else {

				$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

				CustomerOrderPeer::addSelectColumns($criteria);
				$this->collCustomerOrders = CustomerOrderPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

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
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
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

				$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

				$count = CustomerOrderPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

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
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related CustomerOrders from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getCustomerOrdersJoinCustomer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrders === null) {
			if ($this->isNew()) {
				$this->collCustomerOrders = array();
			} else {

				$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

				$this->collCustomerOrders = CustomerOrderPeer::doSelectJoinCustomer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerOrderPeer::INVOICE_ID, $this->id);

			if (!isset($this->lastCustomerOrderCriteria) || !$this->lastCustomerOrderCriteria->equals($criteria)) {
				$this->collCustomerOrders = CustomerOrderPeer::doSelectJoinCustomer($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerOrderCriteria = $criteria;

		return $this->collCustomerOrders;
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
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related CustomerReturns from storage. If this Invoice is new, it will return
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
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturns === null) {
			if ($this->isNew()) {
			   $this->collCustomerReturns = array();
			} else {

				$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

				CustomerReturnPeer::addSelectColumns($criteria);
				$this->collCustomerReturns = CustomerReturnPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

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
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
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

				$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

				$count = CustomerReturnPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

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
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related CustomerReturns from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getCustomerReturnsJoinCustomerOrder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturns === null) {
			if ($this->isNew()) {
				$this->collCustomerReturns = array();
			} else {

				$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

				$this->collCustomerReturns = CustomerReturnPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerReturnPeer::INVOICE_ID, $this->id);

			if (!isset($this->lastCustomerReturnCriteria) || !$this->lastCustomerReturnCriteria->equals($criteria)) {
				$this->collCustomerReturns = CustomerReturnPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerReturnCriteria = $criteria;

		return $this->collCustomerReturns;
	}

	/**
	 * Clears out the collPartInstances collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartInstances()
	 */
	public function clearPartInstances()
	{
		$this->collPartInstances = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartInstances collection (array).
	 *
	 * By default this just sets the collPartInstances collection to an empty array (like clearcollPartInstances());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartInstances()
	{
		$this->collPartInstances = array();
	}

	/**
	 * Gets an array of PartInstance objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related PartInstances from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartInstance[]
	 * @throws     PropelException
	 */
	public function getPartInstances($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
			   $this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
					$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartInstanceCriteria = $criteria;
		return $this->collPartInstances;
	}

	/**
	 * Returns the number of related PartInstance objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartInstance objects.
	 * @throws     PropelException
	 */
	public function countPartInstances(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				$count = PartInstancePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
					$count = PartInstancePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartInstances);
				}
			} else {
				$count = count($this->collPartInstances);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartInstance object to this object
	 * through the PartInstance foreign key attribute.
	 *
	 * @param      PartInstance $l PartInstance
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartInstance(PartInstance $l)
	{
		if ($this->collPartInstances === null) {
			$this->initPartInstances();
		}
		if (!in_array($l, $this->collPartInstances, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartInstances, $l);
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getPartInstancesJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getPartInstancesJoinSupplierOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getPartInstancesJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getPartInstancesJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}

	/**
	 * Clears out the collShipments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addShipments()
	 */
	public function clearShipments()
	{
		$this->collShipments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collShipments collection (array).
	 *
	 * By default this just sets the collShipments collection to an empty array (like clearcollShipments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initShipments()
	{
		$this->collShipments = array();
	}

	/**
	 * Gets an array of Shipment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related Shipments from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Shipment[]
	 * @throws     PropelException
	 */
	public function getShipments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collShipments === null) {
			if ($this->isNew()) {
			   $this->collShipments = array();
			} else {

				$criteria->add(ShipmentPeer::INVOICE_ID, $this->id);

				ShipmentPeer::addSelectColumns($criteria);
				$this->collShipments = ShipmentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ShipmentPeer::INVOICE_ID, $this->id);

				ShipmentPeer::addSelectColumns($criteria);
				if (!isset($this->lastShipmentCriteria) || !$this->lastShipmentCriteria->equals($criteria)) {
					$this->collShipments = ShipmentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastShipmentCriteria = $criteria;
		return $this->collShipments;
	}

	/**
	 * Returns the number of related Shipment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Shipment objects.
	 * @throws     PropelException
	 */
	public function countShipments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collShipments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ShipmentPeer::INVOICE_ID, $this->id);

				$count = ShipmentPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ShipmentPeer::INVOICE_ID, $this->id);

				if (!isset($this->lastShipmentCriteria) || !$this->lastShipmentCriteria->equals($criteria)) {
					$count = ShipmentPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collShipments);
				}
			} else {
				$count = count($this->collShipments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Shipment object to this object
	 * through the Shipment foreign key attribute.
	 *
	 * @param      Shipment $l Shipment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addShipment(Shipment $l)
	{
		if ($this->collShipments === null) {
			$this->initShipments();
		}
		if (!in_array($l, $this->collShipments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collShipments, $l);
			$l->setInvoice($this);
		}
	}

	/**
	 * Clears out the collWorkorderExpenses collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderExpenses()
	 */
	public function clearWorkorderExpenses()
	{
		$this->collWorkorderExpenses = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderExpenses collection (array).
	 *
	 * By default this just sets the collWorkorderExpenses collection to an empty array (like clearcollWorkorderExpenses());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderExpenses()
	{
		$this->collWorkorderExpenses = array();
	}

	/**
	 * Gets an array of WorkorderExpense objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related WorkorderExpenses from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderExpense[]
	 * @throws     PropelException
	 */
	public function getWorkorderExpenses($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderExpenses === null) {
			if ($this->isNew()) {
			   $this->collWorkorderExpenses = array();
			} else {

				$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

				WorkorderExpensePeer::addSelectColumns($criteria);
				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

				WorkorderExpensePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderExpenseCriteria) || !$this->lastWorkorderExpenseCriteria->equals($criteria)) {
					$this->collWorkorderExpenses = WorkorderExpensePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderExpenseCriteria = $criteria;
		return $this->collWorkorderExpenses;
	}

	/**
	 * Returns the number of related WorkorderExpense objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderExpense objects.
	 * @throws     PropelException
	 */
	public function countWorkorderExpenses(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderExpenses === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

				$count = WorkorderExpensePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

				if (!isset($this->lastWorkorderExpenseCriteria) || !$this->lastWorkorderExpenseCriteria->equals($criteria)) {
					$count = WorkorderExpensePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderExpenses);
				}
			} else {
				$count = count($this->collWorkorderExpenses);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderExpense object to this object
	 * through the WorkorderExpense foreign key attribute.
	 *
	 * @param      WorkorderExpense $l WorkorderExpense
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderExpense(WorkorderExpense $l)
	{
		if ($this->collWorkorderExpenses === null) {
			$this->initWorkorderExpenses();
		}
		if (!in_array($l, $this->collWorkorderExpenses, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderExpenses, $l);
			$l->setInvoiceRelatedByWorkorderInvoiceId($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related WorkorderExpenses from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getWorkorderExpensesJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderExpenses === null) {
			if ($this->isNew()) {
				$this->collWorkorderExpenses = array();
			} else {

				$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastWorkorderExpenseCriteria) || !$this->lastWorkorderExpenseCriteria->equals($criteria)) {
				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderExpenseCriteria = $criteria;

		return $this->collWorkorderExpenses;
	}

	/**
	 * Clears out the collWorkorderInvoices collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderInvoices()
	 */
	public function clearWorkorderInvoices()
	{
		$this->collWorkorderInvoices = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderInvoices collection (array).
	 *
	 * By default this just sets the collWorkorderInvoices collection to an empty array (like clearcollWorkorderInvoices());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderInvoices()
	{
		$this->collWorkorderInvoices = array();
	}

	/**
	 * Gets an array of WorkorderInvoice objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related WorkorderInvoices from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderInvoice[]
	 * @throws     PropelException
	 */
	public function getWorkorderInvoices($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderInvoices === null) {
			if ($this->isNew()) {
			   $this->collWorkorderInvoices = array();
			} else {

				$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

				WorkorderInvoicePeer::addSelectColumns($criteria);
				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

				WorkorderInvoicePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderInvoiceCriteria) || !$this->lastWorkorderInvoiceCriteria->equals($criteria)) {
					$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderInvoiceCriteria = $criteria;
		return $this->collWorkorderInvoices;
	}

	/**
	 * Returns the number of related WorkorderInvoice objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderInvoice objects.
	 * @throws     PropelException
	 */
	public function countWorkorderInvoices(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderInvoices === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

				$count = WorkorderInvoicePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

				if (!isset($this->lastWorkorderInvoiceCriteria) || !$this->lastWorkorderInvoiceCriteria->equals($criteria)) {
					$count = WorkorderInvoicePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderInvoices);
				}
			} else {
				$count = count($this->collWorkorderInvoices);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderInvoice object to this object
	 * through the WorkorderInvoice foreign key attribute.
	 *
	 * @param      WorkorderInvoice $l WorkorderInvoice
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderInvoice(WorkorderInvoice $l)
	{
		if ($this->collWorkorderInvoices === null) {
			$this->initWorkorderInvoices();
		}
		if (!in_array($l, $this->collWorkorderInvoices, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderInvoices, $l);
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related WorkorderInvoices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getWorkorderInvoicesJoinWorkorder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderInvoices === null) {
			if ($this->isNew()) {
				$this->collWorkorderInvoices = array();
			} else {

				$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderInvoicePeer::INVOICE_ID, $this->id);

			if (!isset($this->lastWorkorderInvoiceCriteria) || !$this->lastWorkorderInvoiceCriteria->equals($criteria)) {
				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelectJoinWorkorder($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderInvoiceCriteria = $criteria;

		return $this->collWorkorderInvoices;
	}

	/**
	 * Clears out the collTimelogs collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addTimelogs()
	 */
	public function clearTimelogs()
	{
		$this->collTimelogs = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collTimelogs collection (array).
	 *
	 * By default this just sets the collTimelogs collection to an empty array (like clearcollTimelogs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initTimelogs()
	{
		$this->collTimelogs = array();
	}

	/**
	 * Gets an array of Timelog objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Invoice has previously been saved, it will retrieve
	 * related Timelogs from storage. If this Invoice is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Timelog[]
	 * @throws     PropelException
	 */
	public function getTimelogs($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
			   $this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				TimelogPeer::addSelectColumns($criteria);
				$this->collTimelogs = TimelogPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				TimelogPeer::addSelectColumns($criteria);
				if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
					$this->collTimelogs = TimelogPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastTimelogCriteria = $criteria;
		return $this->collTimelogs;
	}

	/**
	 * Returns the number of related Timelog objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Timelog objects.
	 * @throws     PropelException
	 */
	public function countTimelogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				$count = TimelogPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
					$count = TimelogPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collTimelogs);
				}
			} else {
				$count = count($this->collTimelogs);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Timelog object to this object
	 * through the Timelog foreign key attribute.
	 *
	 * @param      Timelog $l Timelog
	 * @return     void
	 * @throws     PropelException
	 */
	public function addTimelog(Timelog $l)
	{
		if ($this->collTimelogs === null) {
			$this->initTimelogs();
		}
		if (!in_array($l, $this->collTimelogs, true)) { // only add it if the **same** object is not already associated
			array_push($this->collTimelogs, $l);
			$l->setInvoice($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getTimelogsJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getTimelogsJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getTimelogsJoinLabourType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinLabourType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinLabourType($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Invoice is new, it will return
	 * an empty collection; or if this Invoice has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Invoice.
	 */
	public function getTimelogsJoinNonbillType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(InvoicePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinNonbillType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinNonbillType($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
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
			if ($this->collSupplierOrders) {
				foreach ((array) $this->collSupplierOrders as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomerOrders) {
				foreach ((array) $this->collCustomerOrders as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomerReturns) {
				foreach ((array) $this->collCustomerReturns as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartInstances) {
				foreach ((array) $this->collPartInstances as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collShipments) {
				foreach ((array) $this->collShipments as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderExpenses) {
				foreach ((array) $this->collWorkorderExpenses as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderInvoices) {
				foreach ((array) $this->collWorkorderInvoices as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTimelogs) {
				foreach ((array) $this->collTimelogs as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collSupplierOrders = null;
		$this->collCustomerOrders = null;
		$this->collCustomerReturns = null;
		$this->collPartInstances = null;
		$this->collShipments = null;
		$this->collWorkorderExpenses = null;
		$this->collWorkorderInvoices = null;
		$this->collTimelogs = null;
			$this->aCustomer = null;
			$this->aSupplier = null;
			$this->aManufacturer = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseInvoice:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseInvoice::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseInvoice
