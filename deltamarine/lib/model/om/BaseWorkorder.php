<?php

/**
 * Base class that represents a row from the 'workorder' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorder extends BaseObject  implements Persistent {


  const PEER = 'WorkorderPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkorderPeer
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
	 * The value for the customer_boat_id field.
	 * @var        int
	 */
	protected $customer_boat_id;

	/**
	 * The value for the workorder_category_id field.
	 * @var        int
	 */
	protected $workorder_category_id;

	/**
	 * The value for the status field.
	 * @var        string
	 */
	protected $status;

	/**
	 * The value for the summary_color field.
	 * Note: this column has a database default value of: 'FFFFFF'
	 * @var        string
	 */
	protected $summary_color;

	/**
	 * The value for the summary_notes field.
	 * @var        string
	 */
	protected $summary_notes;

	/**
	 * The value for the haulout_date field.
	 * @var        string
	 */
	protected $haulout_date;

	/**
	 * The value for the haulin_date field.
	 * @var        string
	 */
	protected $haulin_date;

	/**
	 * The value for the created_on field.
	 * @var        string
	 */
	protected $created_on;

	/**
	 * The value for the started_on field.
	 * @var        string
	 */
	protected $started_on;

	/**
	 * The value for the completed_on field.
	 * @var        string
	 */
	protected $completed_on;

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
	 * The value for the customer_notes field.
	 * @var        string
	 */
	protected $customer_notes;

	/**
	 * The value for the internal_notes field.
	 * @var        string
	 */
	protected $internal_notes;

	/**
	 * The value for the for_rigging field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $for_rigging;

	/**
	 * The value for the shop_supplies_surcharge field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $shop_supplies_surcharge;

	/**
	 * The value for the moorage_surcharge field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $moorage_surcharge;

	/**
	 * The value for the moorage_surcharge_amt field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $moorage_surcharge_amt;

	protected $exemption_file;

	protected $canada_entry_num;
	protected $canada_entry_date;
	protected $usa_entry_num;
	protected $usa_entry_date;


	/**
	 * @var        Customer
	 */
	protected $aCustomer;

	/**
	 * @var        CustomerBoat
	 */
	protected $aCustomerBoat;

	/**
	 * @var        WorkorderCategory
	 */
	protected $aWorkorderCategory;

	/**
	 * @var        array WorkorderItem[] Collection to store aggregation of WorkorderItem objects.
	 */
	protected $collWorkorderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItems.
	 */
	private $lastWorkorderItemCriteria = null;

	/**
	 * @var        array WorkorderInvoice[] Collection to store aggregation of WorkorderInvoice objects.
	 */
	protected $collWorkorderInvoices;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderInvoices.
	 */
	private $lastWorkorderInvoiceCriteria = null;

	/**
	 * @var        array WorkorderPayment[] Collection to store aggregation of WorkorderPayment objects.
	 */
	protected $collWorkorderPayments;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderPayments.
	 */
	private $lastWorkorderPaymentCriteria = null;

	/**
	 * @var        array Payment[] Collection to store aggregation of Payment objects.
	 */
	protected $collPayments;

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
	 * Initializes internal state of BaseWorkorder object.
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
		$this->summary_color = 'FFFFFF';
		$this->hst_exempt = false;
		$this->gst_exempt = false;
		$this->pst_exempt = false;
		$this->for_rigging = false;
		$this->shop_supplies_surcharge = '0';
		$this->moorage_surcharge = '0';
		$this->moorage_surcharge_amt = '0';
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
	 * Get the [customer_boat_id] column value.
	 * 
	 * @return     int
	 */
	public function getCustomerBoatId()
	{
		return $this->customer_boat_id;
	}

	/**
	 * Get the [workorder_category_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderCategoryId()
	{
		return $this->workorder_category_id;
	}

	/**
	 * Get the [status] column value.
	 * 
	 * @return     string
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Get the [summary_color] column value.
	 * 
	 * @return     string
	 */
	public function getSummaryColor()
	{
		return $this->summary_color;
	}

	/**
	 * Get the [summary_notes] column value.
	 * 
	 * @return     string
	 */
	public function getSummaryNotes()
	{
		return $this->summary_notes;
	}

	/**
	 * Get the [optionally formatted] temporal [haulout_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getHauloutDate($format = 'Y-m-d H:i:s')
	{
		if ($this->haulout_date === null) {
			return null;
		}


		if ($this->haulout_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->haulout_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->haulout_date, true), $x);
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
	 * Get the [optionally formatted] temporal [haulin_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getHaulinDate($format = 'Y-m-d H:i:s')
	{
		if ($this->haulin_date === null) {
			return null;
		}


		if ($this->haulin_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->haulin_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->haulin_date, true), $x);
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
	 * Get the [optionally formatted] temporal [created_on] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedOn($format = 'Y-m-d H:i:s')
	{
		if ($this->created_on === null) {
			return null;
		}


		if ($this->created_on === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->created_on);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_on, true), $x);
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
	 * Get the [optionally formatted] temporal [started_on] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getStartedOn($format = 'Y-m-d H:i:s')
	{
		if ($this->started_on === null) {
			return null;
		}


		if ($this->started_on === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->started_on);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->started_on, true), $x);
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
	 * Get the [optionally formatted] temporal [completed_on] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCompletedOn($format = 'Y-m-d H:i:s')
	{
		if ($this->completed_on === null) {
			return null;
		}


		if ($this->completed_on === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->completed_on);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->completed_on, true), $x);
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
	 * Get the [customer_notes] column value.
	 * 
	 * @return     string
	 */
	public function getCustomerNotes()
	{
		return $this->customer_notes;
	}

	/**
	 * Get the [internal_notes] column value.
	 * 
	 * @return     string
	 */
	public function getInternalNotes()
	{
		return $this->internal_notes;
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
	 * Get the [shop_supplies_surcharge] column value.
	 * 
	 * @return     string
	 */
	public function getShopSuppliesSurcharge()
	{
		return $this->shop_supplies_surcharge;
	}

	/**
	 * Get the [moorage_surcharge] column value.
	 * 
	 * @return     string
	 */
	public function getMoorageSurcharge()
	{
		return $this->moorage_surcharge;
	}

	/**
	 * Get the [moorage_surcharge_amt] column value.
	 * 
	 * @return     string
	 */
	public function getMoorageSurchargeAmt()
	{
		return $this->moorage_surcharge_amt;
	}

	/**
	 * Get the [exemption_file] column value.
	 * 
	 * @return     string
	 */
	public function getExemptionFile()
	{
		return $this->exemption_file;
	}


	public function getCanadaEntryNum()
	{
		return $this->canada_entry_num;
	}

	public function getCanadaEntryDate($format = 'Y-m-d')
	{
		if ($this->canada_entry_date === null) {
			return null;
		}


		if ($this->canada_entry_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->canada_entry_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->canada_entry_date, true), $x);
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

	public function getUsaEntryNum()
	{
		return $this->usa_entry_num;
	}
	
	public function getUsaEntryDate($format = 'Y-m-d')
	{
		if ($this->usa_entry_date === null) {
			return null;
		}


		if ($this->usa_entry_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->usa_entry_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->usa_entry_date, true), $x);
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
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = WorkorderPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [customer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setCustomerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_id !== $v) {
			$this->customer_id = $v;
			$this->modifiedColumns[] = WorkorderPeer::CUSTOMER_ID;
		}

		if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
			$this->aCustomer = null;
		}

		return $this;
	} // setCustomerId()

	/**
	 * Set the value of [customer_boat_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setCustomerBoatId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->customer_boat_id !== $v) {
			$this->customer_boat_id = $v;
			$this->modifiedColumns[] = WorkorderPeer::CUSTOMER_BOAT_ID;
		}

		if ($this->aCustomerBoat !== null && $this->aCustomerBoat->getId() !== $v) {
			$this->aCustomerBoat = null;
		}

		return $this;
	} // setCustomerBoatId()

	/**
	 * Set the value of [workorder_category_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setWorkorderCategoryId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_category_id !== $v) {
			$this->workorder_category_id = $v;
			$this->modifiedColumns[] = WorkorderPeer::WORKORDER_CATEGORY_ID;
		}

		if ($this->aWorkorderCategory !== null && $this->aWorkorderCategory->getId() !== $v) {
			$this->aWorkorderCategory = null;
		}

		return $this;
	} // setWorkorderCategoryId()

	/**
	 * Set the value of [status] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setStatus($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->status !== $v) {
			$this->status = $v;
			$this->modifiedColumns[] = WorkorderPeer::STATUS;
		}

		return $this;
	} // setStatus()

	/**
	 * Set the value of [summary_color] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setSummaryColor($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->summary_color !== $v || $v === 'FFFFFF') {
			$this->summary_color = $v;
			$this->modifiedColumns[] = WorkorderPeer::SUMMARY_COLOR;
		}

		return $this;
	} // setSummaryColor()

	/**
	 * Set the value of [summary_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setSummaryNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->summary_notes !== $v) {
			$this->summary_notes = $v;
			$this->modifiedColumns[] = WorkorderPeer::SUMMARY_NOTES;
		}

		return $this;
	} // setSummaryNotes()

	/**
	 * Sets the value of [haulout_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setHauloutDate($v)
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

		if ( $this->haulout_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->haulout_date !== null && $tmpDt = new DateTime($this->haulout_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->haulout_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::HAULOUT_DATE;
			}
		} // if either are not null

		return $this;
	} // setHauloutDate()

	/**
	 * Sets the value of [haulin_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setHaulinDate($v)
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

		if ( $this->haulin_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->haulin_date !== null && $tmpDt = new DateTime($this->haulin_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->haulin_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::HAULIN_DATE;
			}
		} // if either are not null

		return $this;
	} // setHaulinDate()

	/**
	 * Sets the value of [created_on] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setCreatedOn($v)
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

		if ( $this->created_on !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_on !== null && $tmpDt = new DateTime($this->created_on)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_on = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::CREATED_ON;
			}
		} // if either are not null

		return $this;
	} // setCreatedOn()

	/**
	 * Sets the value of [started_on] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setStartedOn($v)
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

		if ( $this->started_on !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->started_on !== null && $tmpDt = new DateTime($this->started_on)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->started_on = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::STARTED_ON;
			}
		} // if either are not null

		return $this;
	} // setStartedOn()

	/**
	 * Sets the value of [completed_on] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setCompletedOn($v)
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

		if ( $this->completed_on !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->completed_on !== null && $tmpDt = new DateTime($this->completed_on)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->completed_on = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::COMPLETED_ON;
			}
		} // if either are not null

		return $this;
	} // setCompletedOn()

	/**
	 * Set the value of [hst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setHstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->hst_exempt !== $v || $v === false) {
			$this->hst_exempt = $v;
			$this->modifiedColumns[] = WorkorderPeer::HST_EXEMPT;
		}

		return $this;
	} // setHstExempt()

	/**
	 * Set the value of [gst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setGstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->gst_exempt !== $v || $v === false) {
			$this->gst_exempt = $v;
			$this->modifiedColumns[] = WorkorderPeer::GST_EXEMPT;
		}

		return $this;
	} // setGstExempt()

	/**
	 * Set the value of [pst_exempt] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setPstExempt($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->pst_exempt !== $v || $v === false) {
			$this->pst_exempt = $v;
			$this->modifiedColumns[] = WorkorderPeer::PST_EXEMPT;
		}

		return $this;
	} // setPstExempt()

	/**
	 * Set the value of [customer_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setCustomerNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_notes !== $v) {
			$this->customer_notes = $v;
			$this->modifiedColumns[] = WorkorderPeer::CUSTOMER_NOTES;
		}

		return $this;
	} // setCustomerNotes()

	/**
	 * Set the value of [internal_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setInternalNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->internal_notes !== $v) {
			$this->internal_notes = $v;
			$this->modifiedColumns[] = WorkorderPeer::INTERNAL_NOTES;
		}

		return $this;
	} // setInternalNotes()

	/**
	 * Set the value of [for_rigging] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setForRigging($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->for_rigging !== $v || $v === false) {
			$this->for_rigging = $v;
			$this->modifiedColumns[] = WorkorderPeer::FOR_RIGGING;
		}

		return $this;
	} // setForRigging()

	/**
	 * Set the value of [shop_supplies_surcharge] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setShopSuppliesSurcharge($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shop_supplies_surcharge !== $v || $v === '0') {
			$this->shop_supplies_surcharge = $v;
			$this->modifiedColumns[] = WorkorderPeer::SHOP_SUPPLIES_SURCHARGE;
		}

		return $this;
	} // setShopSuppliesSurcharge()

	/**
	 * Set the value of [moorage_surcharge] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setMoorageSurcharge($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->moorage_surcharge !== $v || $v === '0') {
			$this->moorage_surcharge = $v;
			$this->modifiedColumns[] = WorkorderPeer::MOORAGE_SURCHARGE;
		}

		return $this;
	} // setMoorageSurcharge()


	/**
	 * Set the value of [moorage_surcharge_amt] column.
	 * 
	 * @param      string $v new value
	 * @return     Workorder The current object (for fluent API support)
	 */
	public function setMoorageSurchargeAmt($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->moorage_surcharge_amt !== $v || $v === '0') {
			$this->moorage_surcharge_amt = $v;
			$this->modifiedColumns[] = WorkorderPeer::MOORAGE_SURCHARGE_AMT;
		}

		return $this;
	} // setMoorageSurchargeAmt()


	public function setExemptionFile($v)
	{

		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->exemption_file !== $v) {
			$this->exemption_file = $v;
			$this->modifiedColumns[] = WorkorderPeer::EXEMPTION_FILE;
		}

		return $this;
	}//setExemptionFile()

	public function setCanadaEntryNum($v)
	{
    if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->canada_entry_num !== $v) {
			$this->canada_entry_num = $v;
			$this->modifiedColumns[] = WorkorderPeer::CANADA_ENTRY_NUM;
		}

		return $this;
	}
	public function setCanadaEntryDate($v)
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

		if ( $this->canada_entry_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->canada_entry_date !== null && $tmpDt = new DateTime($this->canada_entry_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->canada_entry_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::CANADA_ENTRY_DATE;
			}
		} // if either are not null

		return $this;
	}
	public function setUsaEntryNum($v)
	{
    if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->usa_entry_num !== $v) {
			$this->usa_entry_num = $v;
			$this->modifiedColumns[] = WorkorderPeer::USA_ENTRY_NUM;
		}

		return $this;
	}
	public function setUsaEntryDate($v)
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

		if ( $this->canada_entusa_entry_datery_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->usa_entry_date !== null && $tmpDt = new DateTime($this->usa_entry_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->usa_entry_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderPeer::USA_ENTRY_DATE;
			}
		} // if either are not null

		return $this;
	}



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
			if (array_diff($this->modifiedColumns, array(WorkorderPeer::SUMMARY_COLOR,WorkorderPeer::HST_EXEMPT,WorkorderPeer::GST_EXEMPT,WorkorderPeer::PST_EXEMPT,WorkorderPeer::FOR_RIGGING,WorkorderPeer::SHOP_SUPPLIES_SURCHARGE,WorkorderPeer::MOORAGE_SURCHARGE,WorkorderPeer::MOORAGE_SURCHARGE_AMT))) {
				return false;
			}

			if ($this->summary_color !== 'FFFFFF') {
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

			if ($this->shop_supplies_surcharge !== '0') {
				return false;
			}

			if ($this->moorage_surcharge !== '0') {
				return false;
			}

			if ($this->moorage_surcharge_amt !== '0') {
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
			$this->customer_boat_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->workorder_category_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->status = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->summary_color = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->summary_notes = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->haulout_date = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->haulin_date = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->created_on = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->started_on = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->completed_on = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->hst_exempt = ($row[$startcol + 12] !== null) ? (boolean) $row[$startcol + 12] : null;
			$this->gst_exempt = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
			$this->pst_exempt = ($row[$startcol + 14] !== null) ? (boolean) $row[$startcol + 14] : null;
			$this->customer_notes = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->internal_notes = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->for_rigging = ($row[$startcol + 17] !== null) ? (boolean) $row[$startcol + 17] : null;
			$this->shop_supplies_surcharge = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
			$this->moorage_surcharge = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
			$this->moorage_surcharge_amt = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
			$this->exemption_file = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
			$this->canada_entry_num = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
			$this->canada_entry_date = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
			$this->usa_entry_num = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
			$this->usa_entry_date = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 22; // 22 = WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Workorder object", $e);
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
		if ($this->aCustomerBoat !== null && $this->customer_boat_id !== $this->aCustomerBoat->getId()) {
			$this->aCustomerBoat = null;
		}
		if ($this->aWorkorderCategory !== null && $this->workorder_category_id !== $this->aWorkorderCategory->getId()) {
			$this->aWorkorderCategory = null;
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
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkorderPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aCustomer = null;
			$this->aCustomerBoat = null;
			$this->aWorkorderCategory = null;
			$this->collWorkorderItems = null;
			$this->lastWorkorderItemCriteria = null;

			$this->collWorkorderInvoices = null;
			$this->lastWorkorderInvoiceCriteria = null;

			$this->collWorkorderPayments = null;
			$this->lastWorkorderPaymentCriteria = null;

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

    foreach (sfMixer::getCallables('BaseWorkorder:delete:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			WorkorderPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseWorkorder:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseWorkorder:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(WorkorderPeer::CREATED_ON))
    {
      $this->setCreatedOn(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseWorkorder:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			WorkorderPeer::addInstanceToPool($this);
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

			if ($this->aCustomerBoat !== null) {
				if ($this->aCustomerBoat->isModified() || $this->aCustomerBoat->isNew()) {
					$affectedRows += $this->aCustomerBoat->save($con);
				}
				$this->setCustomerBoat($this->aCustomerBoat);
			}

			if ($this->aWorkorderCategory !== null) {
				if ($this->aWorkorderCategory->isModified() || $this->aWorkorderCategory->isNew()) {
					$affectedRows += $this->aWorkorderCategory->save($con);
				}
				$this->setWorkorderCategory($this->aWorkorderCategory);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WorkorderPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WorkorderPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WorkorderPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collWorkorderItems !== null) {
				foreach ($this->collWorkorderItems as $referrerFK) {
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

			if ($this->collWorkorderPayments !== null) {
				foreach ($this->collWorkorderPayments as $referrerFK) {
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

			if ($this->aCustomerBoat !== null) {
				if (!$this->aCustomerBoat->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCustomerBoat->getValidationFailures());
				}
			}

			if ($this->aWorkorderCategory !== null) {
				if (!$this->aWorkorderCategory->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkorderCategory->getValidationFailures());
				}
			}


			if (($retval = WorkorderPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collWorkorderItems !== null) {
					foreach ($this->collWorkorderItems as $referrerFK) {
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

				if ($this->collWorkorderPayments !== null) {
					foreach ($this->collWorkorderPayments as $referrerFK) {
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
		$pos = WorkorderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCustomerBoatId();
				break;
			case 3:
				return $this->getWorkorderCategoryId();
				break;
			case 4:
				return $this->getStatus();
				break;
			case 5:
				return $this->getSummaryColor();
				break;
			case 6:
				return $this->getSummaryNotes();
				break;
			case 7:
				return $this->getHauloutDate();
				break;
			case 8:
				return $this->getHaulinDate();
				break;
			case 9:
				return $this->getCreatedOn();
				break;
			case 10:
				return $this->getStartedOn();
				break;
			case 11:
				return $this->getCompletedOn();
				break;
			case 12:
				return $this->getHstExempt();
				break;
			case 13:
				return $this->getGstExempt();
				break;
			case 14:
				return $this->getPstExempt();
				break;
			case 15:
				return $this->getCustomerNotes();
				break;
			case 16:
				return $this->getInternalNotes();
				break;
			case 17:
				return $this->getForRigging();
				break;
			case 18:
				return $this->getShopSuppliesSurcharge();
				break;
			case 19:
				return $this->getMoorageSurcharge();
				break;
			case 20:
				return $this->getMoorageSurchargeAmt();
				break;
			case 21:
				return $this->getExemptionFile();
				break;
			case 22:
				return $this->getCanadaEntryNum();
				break;
			case 23:
				return $this->getCanadaEntryDate();
				break;
			case 24:
			return $this->getUsaEntryNum();
			break;
			case 25:
				return $this->getUsaEntryDate();
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
		$keys = WorkorderPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCustomerId(),
			$keys[2] => $this->getCustomerBoatId(),
			$keys[3] => $this->getWorkorderCategoryId(),
			$keys[4] => $this->getStatus(),
			$keys[5] => $this->getSummaryColor(),
			$keys[6] => $this->getSummaryNotes(),
			$keys[7] => $this->getHauloutDate(),
			$keys[8] => $this->getHaulinDate(),
			$keys[9] => $this->getCreatedOn(),
			$keys[10] => $this->getStartedOn(),
			$keys[11] => $this->getCompletedOn(),
			$keys[12] => $this->getHstExempt(),
			$keys[13] => $this->getGstExempt(),
			$keys[14] => $this->getPstExempt(),
			$keys[15] => $this->getCustomerNotes(),
			$keys[16] => $this->getInternalNotes(),
			$keys[17] => $this->getForRigging(),
			$keys[18] => $this->getShopSuppliesSurcharge(),
			$keys[19] => $this->getMoorageSurcharge(),
			$keys[20] => $this->getMoorageSurchargeAmt(),
			$keys[21] => $this->getExemptionFile(),
			$keys[22] => $this->getCanadaEntryNum(),
			$keys[23] => $this->getCanadaEntryDate(),
			$keys[24] => $this->getUsaEntryNum(),
			$keys[25] => $this->getUsaEntryDate(),
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
		$pos = WorkorderPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCustomerBoatId($value);
				break;
			case 3:
				$this->setWorkorderCategoryId($value);
				break;
			case 4:
				$this->setStatus($value);
				break;
			case 5:
				$this->setSummaryColor($value);
				break;
			case 6:
				$this->setSummaryNotes($value);
				break;
			case 7:
				$this->setHauloutDate($value);
				break;
			case 8:
				$this->setHaulinDate($value);
				break;
			case 9:
				$this->setCreatedOn($value);
				break;
			case 10:
				$this->setStartedOn($value);
				break;
			case 11:
				$this->setCompletedOn($value);
				break;
			case 12:
				$this->setHstExempt($value);
				break;
			case 13:
				$this->setGstExempt($value);
				break;
			case 14:
				$this->setPstExempt($value);
				break;
			case 15:
				$this->setCustomerNotes($value);
				break;
			case 16:
				$this->setInternalNotes($value);
				break;
			case 17:
				$this->setForRigging($value);
				break;
			case 18:
				$this->setShopSuppliesSurcharge($value);
				break;
			case 19:
				$this->setMoorageSurcharge($value);
				break;
			case 20:
				$this->setMoorageSurchargeAmt($value);
				break;
			case 21:
				$this->setExemptionFile($value);
				break;
			case 22:
			$this->setCanadaEntryNum($value);
			break;
			case 23:
				$this->setCanadaEntryDate($value);
				break;
			case 24:
			$this->setUsaEntryNum($value);
			break;
			case 25:
				$this->setUsaEntryDate($value);
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
		$keys = WorkorderPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCustomerId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCustomerBoatId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setWorkorderCategoryId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setStatus($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSummaryColor($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setSummaryNotes($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHauloutDate($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setHaulinDate($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCreatedOn($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setStartedOn($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setCompletedOn($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setHstExempt($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setGstExempt($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setPstExempt($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setCustomerNotes($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setInternalNotes($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setForRigging($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setShopSuppliesSurcharge($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setMoorageSurcharge($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setMoorageSurchargeAmt($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setExemptionFile($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setCanadaEntryNum($arr[$keys[22]]);
		if (array_key_exists($keys[23], $arr)) $this->setCanadaEntryDate($arr[$keys[23]]);
		if (array_key_exists($keys[24], $arr)) $this->setUsaEntryNum($arr[$keys[24]]);
		if (array_key_exists($keys[25], $arr)) $this->setUsaEntryDate($arr[$keys[25]]);
	}
	
	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkorderPeer::ID)) $criteria->add(WorkorderPeer::ID, $this->id);
		if ($this->isColumnModified(WorkorderPeer::CUSTOMER_ID)) $criteria->add(WorkorderPeer::CUSTOMER_ID, $this->customer_id);
		if ($this->isColumnModified(WorkorderPeer::CUSTOMER_BOAT_ID)) $criteria->add(WorkorderPeer::CUSTOMER_BOAT_ID, $this->customer_boat_id);
		if ($this->isColumnModified(WorkorderPeer::WORKORDER_CATEGORY_ID)) $criteria->add(WorkorderPeer::WORKORDER_CATEGORY_ID, $this->workorder_category_id);
		if ($this->isColumnModified(WorkorderPeer::STATUS)) $criteria->add(WorkorderPeer::STATUS, $this->status);
		if ($this->isColumnModified(WorkorderPeer::SUMMARY_COLOR)) $criteria->add(WorkorderPeer::SUMMARY_COLOR, $this->summary_color);
		if ($this->isColumnModified(WorkorderPeer::SUMMARY_NOTES)) $criteria->add(WorkorderPeer::SUMMARY_NOTES, $this->summary_notes);
		if ($this->isColumnModified(WorkorderPeer::HAULOUT_DATE)) $criteria->add(WorkorderPeer::HAULOUT_DATE, $this->haulout_date);
		if ($this->isColumnModified(WorkorderPeer::HAULIN_DATE)) $criteria->add(WorkorderPeer::HAULIN_DATE, $this->haulin_date);
		if ($this->isColumnModified(WorkorderPeer::CREATED_ON)) $criteria->add(WorkorderPeer::CREATED_ON, $this->created_on);
		if ($this->isColumnModified(WorkorderPeer::STARTED_ON)) $criteria->add(WorkorderPeer::STARTED_ON, $this->started_on);
		if ($this->isColumnModified(WorkorderPeer::COMPLETED_ON)) $criteria->add(WorkorderPeer::COMPLETED_ON, $this->completed_on);
		if ($this->isColumnModified(WorkorderPeer::HST_EXEMPT)) $criteria->add(WorkorderPeer::HST_EXEMPT, $this->hst_exempt);
		if ($this->isColumnModified(WorkorderPeer::GST_EXEMPT)) $criteria->add(WorkorderPeer::GST_EXEMPT, $this->gst_exempt);
		if ($this->isColumnModified(WorkorderPeer::PST_EXEMPT)) $criteria->add(WorkorderPeer::PST_EXEMPT, $this->pst_exempt);
		if ($this->isColumnModified(WorkorderPeer::CUSTOMER_NOTES)) $criteria->add(WorkorderPeer::CUSTOMER_NOTES, $this->customer_notes);
		if ($this->isColumnModified(WorkorderPeer::INTERNAL_NOTES)) $criteria->add(WorkorderPeer::INTERNAL_NOTES, $this->internal_notes);
		if ($this->isColumnModified(WorkorderPeer::FOR_RIGGING)) $criteria->add(WorkorderPeer::FOR_RIGGING, $this->for_rigging);
		if ($this->isColumnModified(WorkorderPeer::SHOP_SUPPLIES_SURCHARGE)) $criteria->add(WorkorderPeer::SHOP_SUPPLIES_SURCHARGE, $this->shop_supplies_surcharge);
		if ($this->isColumnModified(WorkorderPeer::MOORAGE_SURCHARGE)) $criteria->add(WorkorderPeer::MOORAGE_SURCHARGE, $this->moorage_surcharge);
		if ($this->isColumnModified(WorkorderPeer::MOORAGE_SURCHARGE_AMT)) $criteria->add(WorkorderPeer::MOORAGE_SURCHARGE_AMT, $this->moorage_surcharge_amt);
		if ($this->isColumnModified(WorkorderPeer::EXEMPTION_FILE)) $criteria->add(WorkorderPeer::EXEMPTION_FILE, $this->exemption_file);
		if ($this->isColumnModified(WorkorderPeer::CANADA_ENTRY_NUM)) $criteria->add(WorkorderPeer::CANADA_ENTRY_NUM, $this->canada_entry_num);
		if ($this->isColumnModified(WorkorderPeer::CANADA_ENTRY_DATE)) $criteria->add(WorkorderPeer::CANADA_ENTRY_DATE, $this->canada_entry_date);
		if ($this->isColumnModified(WorkorderPeer::USA_ENTRY_NUM)) $criteria->add(WorkorderPeer::USA_ENTRY_NUM, $this->usa_entry_num);
		if ($this->isColumnModified(WorkorderPeer::USA_ENTRY_DATE)) $criteria->add(WorkorderPeer::USA_ENTRY_DATE, $this->usa_entry_date);

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
		$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);

		$criteria->add(WorkorderPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Workorder (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCustomerId($this->customer_id);

		$copyObj->setCustomerBoatId($this->customer_boat_id);

		$copyObj->setWorkorderCategoryId($this->workorder_category_id);

		$copyObj->setStatus($this->status);

		$copyObj->setSummaryColor($this->summary_color);

		$copyObj->setSummaryNotes($this->summary_notes);

		$copyObj->setHauloutDate($this->haulout_date);

		$copyObj->setHaulinDate($this->haulin_date);

		$copyObj->setCreatedOn($this->created_on);

		$copyObj->setStartedOn($this->started_on);

		$copyObj->setCompletedOn($this->completed_on);

		$copyObj->setHstExempt($this->hst_exempt);

		$copyObj->setGstExempt($this->gst_exempt);

		$copyObj->setPstExempt($this->pst_exempt);

		$copyObj->setCustomerNotes($this->customer_notes);

		$copyObj->setInternalNotes($this->internal_notes);

		$copyObj->setForRigging($this->for_rigging);

		$copyObj->setShopSuppliesSurcharge($this->shop_supplies_surcharge);

		$copyObj->setMoorageSurcharge($this->moorage_surcharge);

		$copyObj->setMoorageSurchargeAmt($this->moorage_surcharge_amt);

		$copyObj->setExemptionFile($this->exemption_file);

		$copyObj->setCanadaEntryNum($this->canada_entry_num);

		$copyObj->setCanadaEntryDate($this->canada_entry_date);

		$copyObj->setUsaEntryNum($this->usa_entry_num);

		$copyObj->setUsaEntryDate($this->usa_entry_date);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getWorkorderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItem($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderInvoices() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderInvoice($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderPayments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderPayment($relObj->copy($deepCopy));
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
	 * @return     Workorder Clone of current object.
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
	 * @return     WorkorderPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkorderPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Customer object.
	 *
	 * @param      Customer $v
	 * @return     Workorder The current object (for fluent API support)
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
			$v->addWorkorder($this);
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
			   $this->aCustomer->addWorkorders($this);
			 */
		}
		return $this->aCustomer;
	}

	/**
	 * Declares an association between this object and a CustomerBoat object.
	 *
	 * @param      CustomerBoat $v
	 * @return     Workorder The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setCustomerBoat(CustomerBoat $v = null)
	{
		if ($v === null) {
			$this->setCustomerBoatId(NULL);
		} else {
			$this->setCustomerBoatId($v->getId());
		}

		$this->aCustomerBoat = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the CustomerBoat object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorder($this);
		}

		return $this;
	}


	/**
	 * Get the associated CustomerBoat object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     CustomerBoat The associated CustomerBoat object.
	 * @throws     PropelException
	 */
	public function getCustomerBoat(PropelPDO $con = null)
	{
		if ($this->aCustomerBoat === null && ($this->customer_boat_id !== null)) {
			$c = new Criteria(CustomerBoatPeer::DATABASE_NAME);
			$c->add(CustomerBoatPeer::ID, $this->customer_boat_id);
			$this->aCustomerBoat = CustomerBoatPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aCustomerBoat->addWorkorders($this);
			 */
		}
		return $this->aCustomerBoat;
	}

	/**
	 * Declares an association between this object and a WorkorderCategory object.
	 *
	 * @param      WorkorderCategory $v
	 * @return     Workorder The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWorkorderCategory(WorkorderCategory $v = null)
	{
		if ($v === null) {
			$this->setWorkorderCategoryId(NULL);
		} else {
			$this->setWorkorderCategoryId($v->getId());
		}

		$this->aWorkorderCategory = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WorkorderCategory object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorder($this);
		}

		return $this;
	}


	/**
	 * Get the associated WorkorderCategory object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     WorkorderCategory The associated WorkorderCategory object.
	 * @throws     PropelException
	 */
	public function getWorkorderCategory(PropelPDO $con = null)
	{
		if ($this->aWorkorderCategory === null && ($this->workorder_category_id !== null)) {
			$c = new Criteria(WorkorderCategoryPeer::DATABASE_NAME);
			$c->add(WorkorderCategoryPeer::ID, $this->workorder_category_id);
			$this->aWorkorderCategory = WorkorderCategoryPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aWorkorderCategory->addWorkorders($this);
			 */
		}
		return $this->aWorkorderCategory;
	}

	/**
	 * Clears out the collWorkorderItems collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderItems()
	 */
	public function clearWorkorderItems()
	{
		$this->collWorkorderItems = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderItems collection (array).
	 *
	 * By default this just sets the collWorkorderItems collection to an empty array (like clearcollWorkorderItems());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderItems()
	{
		$this->collWorkorderItems = array();
	}

	/**
	 * Gets an array of WorkorderItem objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Workorder has previously been saved, it will retrieve
	 * related WorkorderItems from storage. If this Workorder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderItem[]
	 * @throws     PropelException
	 */
	public function getWorkorderItems($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItems = array();
			} else {

				$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

				WorkorderItemPeer::addSelectColumns($criteria);
				$this->collWorkorderItems = WorkorderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

				WorkorderItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
					$this->collWorkorderItems = WorkorderItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderItemCriteria = $criteria;
		return $this->collWorkorderItems;
	}

	/**
	 * Returns the number of related WorkorderItem objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderItem objects.
	 * @throws     PropelException
	 */
	public function countWorkorderItems(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

				$count = WorkorderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

				if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
					$count = WorkorderItemPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderItems);
				}
			} else {
				$count = count($this->collWorkorderItems);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderItem object to this object
	 * through the WorkorderItem foreign key attribute.
	 *
	 * @param      WorkorderItem $l WorkorderItem
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderItem(WorkorderItem $l)
	{
		if ($this->collWorkorderItems === null) {
			$this->initWorkorderItems();
		}
		if (!in_array($l, $this->collWorkorderItems, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderItems, $l);
			$l->setWorkorder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workorder is new, it will return
	 * an empty collection; or if this Workorder has previously
	 * been saved, it will retrieve related WorkorderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workorder.
	 */
	public function getWorkorderItemsJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItems === null) {
			if ($this->isNew()) {
				$this->collWorkorderItems = array();
			} else {

				$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

				$this->collWorkorderItems = WorkorderItemPeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->id);

			if (!isset($this->lastWorkorderItemCriteria) || !$this->lastWorkorderItemCriteria->equals($criteria)) {
				$this->collWorkorderItems = WorkorderItemPeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemCriteria = $criteria;

		return $this->collWorkorderItems;
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
	 * Otherwise if this Workorder has previously been saved, it will retrieve
	 * related WorkorderInvoices from storage. If this Workorder is new, it will return
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
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderInvoices === null) {
			if ($this->isNew()) {
			   $this->collWorkorderInvoices = array();
			} else {

				$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

				WorkorderInvoicePeer::addSelectColumns($criteria);
				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

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
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
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

				$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

				$count = WorkorderInvoicePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

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
			$l->setWorkorder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workorder is new, it will return
	 * an empty collection; or if this Workorder has previously
	 * been saved, it will retrieve related WorkorderInvoices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workorder.
	 */
	public function getWorkorderInvoicesJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderInvoices === null) {
			if ($this->isNew()) {
				$this->collWorkorderInvoices = array();
			} else {

				$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderInvoicePeer::WORKORDER_ID, $this->id);

			if (!isset($this->lastWorkorderInvoiceCriteria) || !$this->lastWorkorderInvoiceCriteria->equals($criteria)) {
				$this->collWorkorderInvoices = WorkorderInvoicePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderInvoiceCriteria = $criteria;

		return $this->collWorkorderInvoices;
	}

	/**
	 * Clears out the collWorkorderPayments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderPayments()
	 */
	public function clearWorkorderPayments()
	{
		$this->collWorkorderPayments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderPayments collection (array).
	 *
	 * By default this just sets the collWorkorderPayments collection to an empty array (like clearcollWorkorderPayments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderPayments()
	{
		$this->collWorkorderPayments = array();
	}

	/**
	 * Gets an array of WorkorderPayment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Workorder has previously been saved, it will retrieve
	 * related WorkorderPayments from storage. If this Workorder is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderPayment[]
	 * @throws     PropelException
	 */
	public function getWorkorderPayments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderPayments === null) {
			if ($this->isNew()) {
			   $this->collWorkorderPayments = array();
			} else {

				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				WorkorderPaymentPeer::addSelectColumns($criteria);
				$this->collWorkorderPayments = WorkorderPaymentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				WorkorderPaymentPeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderPaymentCriteria) || !$this->lastWorkorderPaymentCriteria->equals($criteria)) {
					$this->collWorkorderPayments = WorkorderPaymentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderPaymentCriteria = $criteria;
		return $this->collWorkorderPayments;
	}

	/**
	 * Returns the number of related WorkorderPayment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderPayment objects.
	 * @throws     PropelException
	 */
	public function countWorkorderPayments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderPayments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				$count = WorkorderPaymentPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				if (!isset($this->lastWorkorderPaymentCriteria) || !$this->lastWorkorderPaymentCriteria->equals($criteria)) {
					$count = WorkorderPaymentPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderPayments);
				}
			} else {
				$count = count($this->collWorkorderPayments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderPayment object to this object
	 * through the WorkorderPayment foreign key attribute.
	 *
	 * @param      WorkorderPayment $l WorkorderPayment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderPayment(WorkorderPayment $l)
	{
		if ($this->collWorkorderPayments === null) {
			$this->initWorkorderPayments();
		}
		if (!in_array($l, $this->collWorkorderPayments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderPayments, $l);
			$l->setWorkorder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workorder is new, it will return
	 * an empty collection; or if this Workorder has previously
	 * been saved, it will retrieve related WorkorderPayments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workorder.
	 */
	public function getWorkorderPaymentsJoinSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderPayments === null) {
			if ($this->isNew()) {
				$this->collWorkorderPayments = array();
			} else {

				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				$this->collWorkorderPayments = WorkorderPaymentPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

			if (!isset($this->lastWorkorderPaymentCriteria) || !$this->lastWorkorderPaymentCriteria->equals($criteria)) {
				$this->collWorkorderPayments = WorkorderPaymentPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderPaymentCriteria = $criteria;

		return $this->collWorkorderPayments;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workorder is new, it will return
	 * an empty collection; or if this Workorder has previously
	 * been saved, it will retrieve related WorkorderPayments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workorder.
	 */
	public function getWorkorderPaymentsJoinManufacturer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderPayments === null) {
			if ($this->isNew()) {
				$this->collWorkorderPayments = array();
			} else {

				$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

				$this->collWorkorderPayments = WorkorderPaymentPeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderPaymentPeer::WORKORDER_ID, $this->id);

			if (!isset($this->lastWorkorderPaymentCriteria) || !$this->lastWorkorderPaymentCriteria->equals($criteria)) {
				$this->collWorkorderPayments = WorkorderPaymentPeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderPaymentCriteria = $criteria;

		return $this->collWorkorderPayments;
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
	 * Otherwise if this Workorder has previously been saved, it will retrieve
	 * related Payments from storage. If this Workorder is new, it will return
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
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPayments === null) {
			if ($this->isNew()) {
			   $this->collPayments = array();
			} else {

				$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

				PaymentPeer::addSelectColumns($criteria);
				$this->collPayments = PaymentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

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
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
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

				$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

				$count = PaymentPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

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
			$l->setWorkorder($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Workorder is new, it will return
	 * an empty collection; or if this Workorder has previously
	 * been saved, it will retrieve related Payments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Workorder.
	 */
	public function getPaymentsJoinCustomerOrder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPayments === null) {
			if ($this->isNew()) {
				$this->collPayments = array();
			} else {

				$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

				$this->collPayments = PaymentPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PaymentPeer::WORKORDER_ID, $this->id);

			if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
				$this->collPayments = PaymentPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
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
			if ($this->collWorkorderItems) {
				foreach ((array) $this->collWorkorderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderInvoices) {
				foreach ((array) $this->collWorkorderInvoices as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderPayments) {
				foreach ((array) $this->collWorkorderPayments as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPayments) {
				foreach ((array) $this->collPayments as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collWorkorderItems = null;
		$this->collWorkorderInvoices = null;
		$this->collWorkorderPayments = null;
		$this->collPayments = null;
			$this->aCustomer = null;
			$this->aCustomerBoat = null;
			$this->aWorkorderCategory = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseWorkorder:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseWorkorder::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseWorkorder
