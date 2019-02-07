<?php

/**
 * Base class that represents a row from the 'timelog' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseTimelog extends BaseObject  implements Persistent {


  const PEER = 'TimelogPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        TimelogPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the employee_id field.
	 * @var        int
	 */
	protected $employee_id;

	/**
	 * The value for the workorder_item_id field.
	 * @var        int
	 */
	protected $workorder_item_id;

	/**
	 * The value for the workorder_invoice_id field.
	 * @var        int
	 */
	protected $workorder_invoice_id;

	/**
	 * The value for the labour_type_id field.
	 * @var        int
	 */
	protected $labour_type_id;

	/**
	 * The value for the nonbill_type_id field.
	 * @var        int
	 */
	protected $nonbill_type_id;

	/**
	 * The value for the custom_label field.
	 * @var        string
	 */
	protected $custom_label;

	/**
	 * The value for the rate field.
	 * @var        string
	 */
	protected $rate;

	/**
	 * The value for the start_time field.
	 * @var        string
	 */
	protected $start_time;

	/**
	 * The value for the end_time field.
	 * @var        string
	 */
	protected $end_time;

	/**
	 * The value for the payroll_hours field.
	 * @var        string
	 */
	protected $payroll_hours;

	/**
	 * The value for the billable_hours field.
	 * @var        string
	 */
	protected $billable_hours;

	/**
	 * The value for the cost field.
	 * @var        string
	 */
	protected $cost;

	/**
	 * The value for the taxable_hst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $taxable_hst;

	/**
	 * The value for the taxable_gst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $taxable_gst;

	/**
	 * The value for the taxable_pst field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $taxable_pst;

	/**
	 * The value for the employee_notes field.
	 * @var        string
	 */
	protected $employee_notes;

	/**
	 * The value for the admin_notes field.
	 * @var        string
	 */
	protected $admin_notes;

	/**
	 * The value for the admin_flagged field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $admin_flagged;

	/**
	 * The value for the estimate field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $estimate;

	/**
	 * The value for the approved field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $approved;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the updated_at field.
	 * @var        string
	 */
	protected $updated_at;

	/**
	 * @var        Employee
	 */
	protected $aEmployee;

	/**
	 * @var        WorkorderItem
	 */
	protected $aWorkorderItem;

	/**
	 * @var        Invoice
	 */
	protected $aInvoice;

	/**
	 * @var        LabourType
	 */
	protected $aLabourType;

	/**
	 * @var        NonbillType
	 */
	protected $aNonbillType;

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
	 * Initializes internal state of BaseTimelog object.
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
		$this->taxable_hst = '0';
		$this->taxable_gst = '0';
		$this->taxable_pst = '0';
		$this->admin_flagged = false;
		$this->estimate = false;
		$this->approved = false;
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
	 * Get the [employee_id] column value.
	 * 
	 * @return     int
	 */
	public function getEmployeeId()
	{
		return $this->employee_id;
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
	 * Get the [workorder_invoice_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderInvoiceId()
	{
		return $this->workorder_invoice_id;
	}

	/**
	 * Get the [labour_type_id] column value.
	 * 
	 * @return     int
	 */
	public function getLabourTypeId()
	{
		return $this->labour_type_id;
	}

	/**
	 * Get the [nonbill_type_id] column value.
	 * 
	 * @return     int
	 */
	public function getNonbillTypeId()
	{
		return $this->nonbill_type_id;
	}

	/**
	 * Get the [custom_label] column value.
	 * 
	 * @return     string
	 */
	public function getCustomLabel()
	{
		return $this->custom_label;
	}

	/**
	 * Get the [rate] column value.
	 * 
	 * @return     string
	 */
	public function getRate()
	{
		return $this->rate;
	}

	/**
	 * Get the [optionally formatted] temporal [start_time] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getStartTime($format = 'Y-m-d H:i:s')
	{
		if ($this->start_time === null) {
			return null;
		}


		if ($this->start_time === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->start_time);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->start_time, true), $x);
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
	 * Get the [optionally formatted] temporal [end_time] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getEndTime($format = 'Y-m-d H:i:s')
	{
		if ($this->end_time === null) {
			return null;
		}


		if ($this->end_time === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->end_time);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->end_time, true), $x);
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
	 * Get the [payroll_hours] column value.
	 * 
	 * @return     string
	 */
	public function getPayrollHours()
	{
		return $this->payroll_hours;
	}

	/**
	 * Get the [billable_hours] column value.
	 * 
	 * @return     string
	 */
	public function getBillableHours()
	{
		return $this->billable_hours;
	}

	/**
	 * Get the [cost] column value.
	 * 
	 * @return     string
	 */
	public function getCost()
	{
		return $this->cost;
	}

	/**
	 * Get the [taxable_hst] column value.
	 * 
	 * @return     string
	 */
	public function getTaxableHst()
	{
		return $this->taxable_hst;
	}

	/**
	 * Get the [taxable_gst] column value.
	 * 
	 * @return     string
	 */
	public function getTaxableGst()
	{
		return $this->taxable_gst;
	}

	/**
	 * Get the [taxable_pst] column value.
	 * 
	 * @return     string
	 */
	public function getTaxablePst()
	{
		return $this->taxable_pst;
	}

	/**
	 * Get the [employee_notes] column value.
	 * 
	 * @return     string
	 */
	public function getEmployeeNotes()
	{
		return $this->employee_notes;
	}

	/**
	 * Get the [admin_notes] column value.
	 * 
	 * @return     string
	 */
	public function getAdminNotes()
	{
		return $this->admin_notes;
	}

	/**
	 * Get the [admin_flagged] column value.
	 * 
	 * @return     boolean
	 */
	public function getAdminFlagged()
	{
		return $this->admin_flagged;
	}

	/**
	 * Get the [estimate] column value.
	 * 
	 * @return     boolean
	 */
	public function getEstimate()
	{
		return $this->estimate;
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
	 * Get the [optionally formatted] temporal [updated_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->updated_at === null) {
			return null;
		}


		if ($this->updated_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->updated_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
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
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = TimelogPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [employee_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setEmployeeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->employee_id !== $v) {
			$this->employee_id = $v;
			$this->modifiedColumns[] = TimelogPeer::EMPLOYEE_ID;
		}

		if ($this->aEmployee !== null && $this->aEmployee->getId() !== $v) {
			$this->aEmployee = null;
		}

		return $this;
	} // setEmployeeId()

	/**
	 * Set the value of [workorder_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setWorkorderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_item_id !== $v) {
			$this->workorder_item_id = $v;
			$this->modifiedColumns[] = TimelogPeer::WORKORDER_ITEM_ID;
		}

		if ($this->aWorkorderItem !== null && $this->aWorkorderItem->getId() !== $v) {
			$this->aWorkorderItem = null;
		}

		return $this;
	} // setWorkorderItemId()

	/**
	 * Set the value of [workorder_invoice_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setWorkorderInvoiceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_invoice_id !== $v) {
			$this->workorder_invoice_id = $v;
			$this->modifiedColumns[] = TimelogPeer::WORKORDER_INVOICE_ID;
		}

		if ($this->aInvoice !== null && $this->aInvoice->getId() !== $v) {
			$this->aInvoice = null;
		}

		return $this;
	} // setWorkorderInvoiceId()

	/**
	 * Set the value of [labour_type_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setLabourTypeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->labour_type_id !== $v) {
			$this->labour_type_id = $v;
			$this->modifiedColumns[] = TimelogPeer::LABOUR_TYPE_ID;
		}

		if ($this->aLabourType !== null && $this->aLabourType->getId() !== $v) {
			$this->aLabourType = null;
		}

		return $this;
	} // setLabourTypeId()

	/**
	 * Set the value of [nonbill_type_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setNonbillTypeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->nonbill_type_id !== $v) {
			$this->nonbill_type_id = $v;
			$this->modifiedColumns[] = TimelogPeer::NONBILL_TYPE_ID;
		}

		if ($this->aNonbillType !== null && $this->aNonbillType->getId() !== $v) {
			$this->aNonbillType = null;
		}

		return $this;
	} // setNonbillTypeId()

	/**
	 * Set the value of [custom_label] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setCustomLabel($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->custom_label !== $v) {
			$this->custom_label = $v;
			$this->modifiedColumns[] = TimelogPeer::CUSTOM_LABEL;
		}

		return $this;
	} // setCustomLabel()

	/**
	 * Set the value of [rate] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setRate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->rate !== $v) {
			$this->rate = $v;
			$this->modifiedColumns[] = TimelogPeer::RATE;
		}

		return $this;
	} // setRate()

	/**
	 * Sets the value of [start_time] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setStartTime($v)
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

		if ( $this->start_time !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->start_time !== null && $tmpDt = new DateTime($this->start_time)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->start_time = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = TimelogPeer::START_TIME;
			}
		} // if either are not null

		return $this;
	} // setStartTime()

	/**
	 * Sets the value of [end_time] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setEndTime($v)
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

		if ( $this->end_time !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->end_time !== null && $tmpDt = new DateTime($this->end_time)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->end_time = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = TimelogPeer::END_TIME;
			}
		} // if either are not null

		return $this;
	} // setEndTime()

	/**
	 * Set the value of [payroll_hours] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setPayrollHours($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->payroll_hours !== $v) {
			$this->payroll_hours = $v;
			$this->modifiedColumns[] = TimelogPeer::PAYROLL_HOURS;
		}

		return $this;
	} // setPayrollHours()

	/**
	 * Set the value of [billable_hours] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setBillableHours($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->billable_hours !== $v) {
			$this->billable_hours = $v;
			$this->modifiedColumns[] = TimelogPeer::BILLABLE_HOURS;
		}

		return $this;
	} // setBillableHours()

	/**
	 * Set the value of [cost] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setCost($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->cost !== $v) {
			$this->cost = $v;
			$this->modifiedColumns[] = TimelogPeer::COST;
		}

		return $this;
	} // setCost()

	/**
	 * Set the value of [taxable_hst] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setTaxableHst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_hst !== $v || $v === '0') {
			$this->taxable_hst = $v;
			$this->modifiedColumns[] = TimelogPeer::TAXABLE_HST;
		}

		return $this;
	} // setTaxableHst()

	/**
	 * Set the value of [taxable_gst] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setTaxableGst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_gst !== $v || $v === '0') {
			$this->taxable_gst = $v;
			$this->modifiedColumns[] = TimelogPeer::TAXABLE_GST;
		}

		return $this;
	} // setTaxableGst()

	/**
	 * Set the value of [taxable_pst] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setTaxablePst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_pst !== $v || $v === '0') {
			$this->taxable_pst = $v;
			$this->modifiedColumns[] = TimelogPeer::TAXABLE_PST;
		}

		return $this;
	} // setTaxablePst()

	/**
	 * Set the value of [employee_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setEmployeeNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->employee_notes !== $v) {
			$this->employee_notes = $v;
			$this->modifiedColumns[] = TimelogPeer::EMPLOYEE_NOTES;
		}

		return $this;
	} // setEmployeeNotes()

	/**
	 * Set the value of [admin_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setAdminNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->admin_notes !== $v) {
			$this->admin_notes = $v;
			$this->modifiedColumns[] = TimelogPeer::ADMIN_NOTES;
		}

		return $this;
	} // setAdminNotes()

	/**
	 * Set the value of [admin_flagged] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setAdminFlagged($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->admin_flagged !== $v || $v === false) {
			$this->admin_flagged = $v;
			$this->modifiedColumns[] = TimelogPeer::ADMIN_FLAGGED;
		}

		return $this;
	} // setAdminFlagged()

	/**
	 * Set the value of [estimate] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setEstimate($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->estimate !== $v || $v === false) {
			$this->estimate = $v;
			$this->modifiedColumns[] = TimelogPeer::ESTIMATE;
		}

		return $this;
	} // setEstimate()

	/**
	 * Set the value of [approved] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setApproved($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->approved !== $v || $v === false) {
			$this->approved = $v;
			$this->modifiedColumns[] = TimelogPeer::APPROVED;
		}

		return $this;
	} // setApproved()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Timelog The current object (for fluent API support)
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
				$this->modifiedColumns[] = TimelogPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Timelog The current object (for fluent API support)
	 */
	public function setUpdatedAt($v)
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

		if ( $this->updated_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->updated_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = TimelogPeer::UPDATED_AT;
			}
		} // if either are not null

		return $this;
	} // setUpdatedAt()

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
			if (array_diff($this->modifiedColumns, array(TimelogPeer::TAXABLE_HST,TimelogPeer::TAXABLE_GST,TimelogPeer::TAXABLE_PST,TimelogPeer::ADMIN_FLAGGED,TimelogPeer::ESTIMATE,TimelogPeer::APPROVED))) {
				return false;
			}

			if ($this->taxable_hst !== '0') {
				return false;
			}

			if ($this->taxable_gst !== '0') {
				return false;
			}

			if ($this->taxable_pst !== '0') {
				return false;
			}

			if ($this->admin_flagged !== false) {
				return false;
			}

			if ($this->estimate !== false) {
				return false;
			}

			if ($this->approved !== false) {
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
			$this->employee_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->workorder_item_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->workorder_invoice_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->labour_type_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->nonbill_type_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->custom_label = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->rate = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->start_time = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->end_time = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->payroll_hours = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->billable_hours = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->cost = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->taxable_hst = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->taxable_gst = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->taxable_pst = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->employee_notes = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->admin_notes = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
			$this->admin_flagged = ($row[$startcol + 18] !== null) ? (boolean) $row[$startcol + 18] : null;
			$this->estimate = ($row[$startcol + 19] !== null) ? (boolean) $row[$startcol + 19] : null;
			$this->approved = ($row[$startcol + 20] !== null) ? (boolean) $row[$startcol + 20] : null;
			$this->created_at = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
			$this->updated_at = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 23; // 23 = TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Timelog object", $e);
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

		if ($this->aEmployee !== null && $this->employee_id !== $this->aEmployee->getId()) {
			$this->aEmployee = null;
		}
		if ($this->aWorkorderItem !== null && $this->workorder_item_id !== $this->aWorkorderItem->getId()) {
			$this->aWorkorderItem = null;
		}
		if ($this->aInvoice !== null && $this->workorder_invoice_id !== $this->aInvoice->getId()) {
			$this->aInvoice = null;
		}
		if ($this->aLabourType !== null && $this->labour_type_id !== $this->aLabourType->getId()) {
			$this->aLabourType = null;
		}
		if ($this->aNonbillType !== null && $this->nonbill_type_id !== $this->aNonbillType->getId()) {
			$this->aNonbillType = null;
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
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = TimelogPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aEmployee = null;
			$this->aWorkorderItem = null;
			$this->aInvoice = null;
			$this->aLabourType = null;
			$this->aNonbillType = null;
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

    foreach (sfMixer::getCallables('BaseTimelog:delete:pre') as $callable)
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
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			TimelogPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseTimelog:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseTimelog:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(TimelogPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(TimelogPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseTimelog:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			TimelogPeer::addInstanceToPool($this);
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

			if ($this->aEmployee !== null) {
				if ($this->aEmployee->isModified() || $this->aEmployee->isNew()) {
					$affectedRows += $this->aEmployee->save($con);
				}
				$this->setEmployee($this->aEmployee);
			}

			if ($this->aWorkorderItem !== null) {
				if ($this->aWorkorderItem->isModified() || $this->aWorkorderItem->isNew()) {
					$affectedRows += $this->aWorkorderItem->save($con);
				}
				$this->setWorkorderItem($this->aWorkorderItem);
			}

			if ($this->aInvoice !== null) {
				if ($this->aInvoice->isModified() || $this->aInvoice->isNew()) {
					$affectedRows += $this->aInvoice->save($con);
				}
				$this->setInvoice($this->aInvoice);
			}

			if ($this->aLabourType !== null) {
				if ($this->aLabourType->isModified() || $this->aLabourType->isNew()) {
					$affectedRows += $this->aLabourType->save($con);
				}
				$this->setLabourType($this->aLabourType);
			}

			if ($this->aNonbillType !== null) {
				if ($this->aNonbillType->isModified() || $this->aNonbillType->isNew()) {
					$affectedRows += $this->aNonbillType->save($con);
				}
				$this->setNonbillType($this->aNonbillType);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = TimelogPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = TimelogPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += TimelogPeer::doUpdate($this, $con);
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

			if ($this->aEmployee !== null) {
				if (!$this->aEmployee->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEmployee->getValidationFailures());
				}
			}

			if ($this->aWorkorderItem !== null) {
				if (!$this->aWorkorderItem->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkorderItem->getValidationFailures());
				}
			}

			if ($this->aInvoice !== null) {
				if (!$this->aInvoice->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aInvoice->getValidationFailures());
				}
			}

			if ($this->aLabourType !== null) {
				if (!$this->aLabourType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aLabourType->getValidationFailures());
				}
			}

			if ($this->aNonbillType !== null) {
				if (!$this->aNonbillType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aNonbillType->getValidationFailures());
				}
			}


			if (($retval = TimelogPeer::doValidate($this, $columns)) !== true) {
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
		$pos = TimelogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getEmployeeId();
				break;
			case 2:
				return $this->getWorkorderItemId();
				break;
			case 3:
				return $this->getWorkorderInvoiceId();
				break;
			case 4:
				return $this->getLabourTypeId();
				break;
			case 5:
				return $this->getNonbillTypeId();
				break;
			case 6:
				return $this->getCustomLabel();
				break;
			case 7:
				return $this->getRate();
				break;
			case 8:
				return $this->getStartTime();
				break;
			case 9:
				return $this->getEndTime();
				break;
			case 10:
				return $this->getPayrollHours();
				break;
			case 11:
				return $this->getBillableHours();
				break;
			case 12:
				return $this->getCost();
				break;
			case 13:
				return $this->getTaxableHst();
				break;
			case 14:
				return $this->getTaxableGst();
				break;
			case 15:
				return $this->getTaxablePst();
				break;
			case 16:
				return $this->getEmployeeNotes();
				break;
			case 17:
				return $this->getAdminNotes();
				break;
			case 18:
				return $this->getAdminFlagged();
				break;
			case 19:
				return $this->getEstimate();
				break;
			case 20:
				return $this->getApproved();
				break;
			case 21:
				return $this->getCreatedAt();
				break;
			case 22:
				return $this->getUpdatedAt();
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
		$keys = TimelogPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getEmployeeId(),
			$keys[2] => $this->getWorkorderItemId(),
			$keys[3] => $this->getWorkorderInvoiceId(),
			$keys[4] => $this->getLabourTypeId(),
			$keys[5] => $this->getNonbillTypeId(),
			$keys[6] => $this->getCustomLabel(),
			$keys[7] => $this->getRate(),
			$keys[8] => $this->getStartTime(),
			$keys[9] => $this->getEndTime(),
			$keys[10] => $this->getPayrollHours(),
			$keys[11] => $this->getBillableHours(),
			$keys[12] => $this->getCost(),
			$keys[13] => $this->getTaxableHst(),
			$keys[14] => $this->getTaxableGst(),
			$keys[15] => $this->getTaxablePst(),
			$keys[16] => $this->getEmployeeNotes(),
			$keys[17] => $this->getAdminNotes(),
			$keys[18] => $this->getAdminFlagged(),
			$keys[19] => $this->getEstimate(),
			$keys[20] => $this->getApproved(),
			$keys[21] => $this->getCreatedAt(),
			$keys[22] => $this->getUpdatedAt(),
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
		$pos = TimelogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setEmployeeId($value);
				break;
			case 2:
				$this->setWorkorderItemId($value);
				break;
			case 3:
				$this->setWorkorderInvoiceId($value);
				break;
			case 4:
				$this->setLabourTypeId($value);
				break;
			case 5:
				$this->setNonbillTypeId($value);
				break;
			case 6:
				$this->setCustomLabel($value);
				break;
			case 7:
				$this->setRate($value);
				break;
			case 8:
				$this->setStartTime($value);
				break;
			case 9:
				$this->setEndTime($value);
				break;
			case 10:
				$this->setPayrollHours($value);
				break;
			case 11:
				$this->setBillableHours($value);
				break;
			case 12:
				$this->setCost($value);
				break;
			case 13:
				$this->setTaxableHst($value);
				break;
			case 14:
				$this->setTaxableGst($value);
				break;
			case 15:
				$this->setTaxablePst($value);
				break;
			case 16:
				$this->setEmployeeNotes($value);
				break;
			case 17:
				$this->setAdminNotes($value);
				break;
			case 18:
				$this->setAdminFlagged($value);
				break;
			case 19:
				$this->setEstimate($value);
				break;
			case 20:
				$this->setApproved($value);
				break;
			case 21:
				$this->setCreatedAt($value);
				break;
			case 22:
				$this->setUpdatedAt($value);
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
		$keys = TimelogPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setEmployeeId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setWorkorderItemId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setWorkorderInvoiceId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setLabourTypeId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setNonbillTypeId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCustomLabel($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setRate($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setStartTime($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setEndTime($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setPayrollHours($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setBillableHours($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCost($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setTaxableHst($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setTaxableGst($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setTaxablePst($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setEmployeeNotes($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setAdminNotes($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setAdminFlagged($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setEstimate($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setApproved($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setCreatedAt($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setUpdatedAt($arr[$keys[22]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(TimelogPeer::DATABASE_NAME);

		if ($this->isColumnModified(TimelogPeer::ID)) $criteria->add(TimelogPeer::ID, $this->id);
		if ($this->isColumnModified(TimelogPeer::EMPLOYEE_ID)) $criteria->add(TimelogPeer::EMPLOYEE_ID, $this->employee_id);
		if ($this->isColumnModified(TimelogPeer::WORKORDER_ITEM_ID)) $criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->workorder_item_id);
		if ($this->isColumnModified(TimelogPeer::WORKORDER_INVOICE_ID)) $criteria->add(TimelogPeer::WORKORDER_INVOICE_ID, $this->workorder_invoice_id);
		if ($this->isColumnModified(TimelogPeer::LABOUR_TYPE_ID)) $criteria->add(TimelogPeer::LABOUR_TYPE_ID, $this->labour_type_id);
		if ($this->isColumnModified(TimelogPeer::NONBILL_TYPE_ID)) $criteria->add(TimelogPeer::NONBILL_TYPE_ID, $this->nonbill_type_id);
		if ($this->isColumnModified(TimelogPeer::CUSTOM_LABEL)) $criteria->add(TimelogPeer::CUSTOM_LABEL, $this->custom_label);
		if ($this->isColumnModified(TimelogPeer::RATE)) $criteria->add(TimelogPeer::RATE, $this->rate);
		if ($this->isColumnModified(TimelogPeer::START_TIME)) $criteria->add(TimelogPeer::START_TIME, $this->start_time);
		if ($this->isColumnModified(TimelogPeer::END_TIME)) $criteria->add(TimelogPeer::END_TIME, $this->end_time);
		if ($this->isColumnModified(TimelogPeer::PAYROLL_HOURS)) $criteria->add(TimelogPeer::PAYROLL_HOURS, $this->payroll_hours);
		if ($this->isColumnModified(TimelogPeer::BILLABLE_HOURS)) $criteria->add(TimelogPeer::BILLABLE_HOURS, $this->billable_hours);
		if ($this->isColumnModified(TimelogPeer::COST)) $criteria->add(TimelogPeer::COST, $this->cost);
		if ($this->isColumnModified(TimelogPeer::TAXABLE_HST)) $criteria->add(TimelogPeer::TAXABLE_HST, $this->taxable_hst);
		if ($this->isColumnModified(TimelogPeer::TAXABLE_GST)) $criteria->add(TimelogPeer::TAXABLE_GST, $this->taxable_gst);
		if ($this->isColumnModified(TimelogPeer::TAXABLE_PST)) $criteria->add(TimelogPeer::TAXABLE_PST, $this->taxable_pst);
		if ($this->isColumnModified(TimelogPeer::EMPLOYEE_NOTES)) $criteria->add(TimelogPeer::EMPLOYEE_NOTES, $this->employee_notes);
		if ($this->isColumnModified(TimelogPeer::ADMIN_NOTES)) $criteria->add(TimelogPeer::ADMIN_NOTES, $this->admin_notes);
		if ($this->isColumnModified(TimelogPeer::ADMIN_FLAGGED)) $criteria->add(TimelogPeer::ADMIN_FLAGGED, $this->admin_flagged);
		if ($this->isColumnModified(TimelogPeer::ESTIMATE)) $criteria->add(TimelogPeer::ESTIMATE, $this->estimate);
		if ($this->isColumnModified(TimelogPeer::APPROVED)) $criteria->add(TimelogPeer::APPROVED, $this->approved);
		if ($this->isColumnModified(TimelogPeer::CREATED_AT)) $criteria->add(TimelogPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(TimelogPeer::UPDATED_AT)) $criteria->add(TimelogPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(TimelogPeer::DATABASE_NAME);

		$criteria->add(TimelogPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Timelog (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setEmployeeId($this->employee_id);

		$copyObj->setWorkorderItemId($this->workorder_item_id);

		$copyObj->setWorkorderInvoiceId($this->workorder_invoice_id);

		$copyObj->setLabourTypeId($this->labour_type_id);

		$copyObj->setNonbillTypeId($this->nonbill_type_id);

		$copyObj->setCustomLabel($this->custom_label);

		$copyObj->setRate($this->rate);

		$copyObj->setStartTime($this->start_time);

		$copyObj->setEndTime($this->end_time);

		$copyObj->setPayrollHours($this->payroll_hours);

		$copyObj->setBillableHours($this->billable_hours);

		$copyObj->setCost($this->cost);

		$copyObj->setTaxableHst($this->taxable_hst);

		$copyObj->setTaxableGst($this->taxable_gst);

		$copyObj->setTaxablePst($this->taxable_pst);

		$copyObj->setEmployeeNotes($this->employee_notes);

		$copyObj->setAdminNotes($this->admin_notes);

		$copyObj->setAdminFlagged($this->admin_flagged);

		$copyObj->setEstimate($this->estimate);

		$copyObj->setApproved($this->approved);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


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
	 * @return     Timelog Clone of current object.
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
	 * @return     TimelogPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new TimelogPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Employee object.
	 *
	 * @param      Employee $v
	 * @return     Timelog The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEmployee(Employee $v = null)
	{
		if ($v === null) {
			$this->setEmployeeId(NULL);
		} else {
			$this->setEmployeeId($v->getId());
		}

		$this->aEmployee = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Employee object, it will not be re-added.
		if ($v !== null) {
			$v->addTimelog($this);
		}

		return $this;
	}


	/**
	 * Get the associated Employee object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Employee The associated Employee object.
	 * @throws     PropelException
	 */
	public function getEmployee(PropelPDO $con = null)
	{
		if ($this->aEmployee === null && ($this->employee_id !== null)) {
			$c = new Criteria(EmployeePeer::DATABASE_NAME);
			$c->add(EmployeePeer::ID, $this->employee_id);
			$this->aEmployee = EmployeePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEmployee->addTimelogs($this);
			 */
		}
		return $this->aEmployee;
	}

	/**
	 * Declares an association between this object and a WorkorderItem object.
	 *
	 * @param      WorkorderItem $v
	 * @return     Timelog The current object (for fluent API support)
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
			$v->addTimelog($this);
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
			   $this->aWorkorderItem->addTimelogs($this);
			 */
		}
		return $this->aWorkorderItem;
	}

	/**
	 * Declares an association between this object and a Invoice object.
	 *
	 * @param      Invoice $v
	 * @return     Timelog The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setInvoice(Invoice $v = null)
	{
		if ($v === null) {
			$this->setWorkorderInvoiceId(NULL);
		} else {
			$this->setWorkorderInvoiceId($v->getId());
		}

		$this->aInvoice = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Invoice object, it will not be re-added.
		if ($v !== null) {
			$v->addTimelog($this);
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
		if ($this->aInvoice === null && ($this->workorder_invoice_id !== null)) {
			$c = new Criteria(InvoicePeer::DATABASE_NAME);
			$c->add(InvoicePeer::ID, $this->workorder_invoice_id);
			$this->aInvoice = InvoicePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aInvoice->addTimelogs($this);
			 */
		}
		return $this->aInvoice;
	}

	/**
	 * Declares an association between this object and a LabourType object.
	 *
	 * @param      LabourType $v
	 * @return     Timelog The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setLabourType(LabourType $v = null)
	{
		if ($v === null) {
			$this->setLabourTypeId(NULL);
		} else {
			$this->setLabourTypeId($v->getId());
		}

		$this->aLabourType = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the LabourType object, it will not be re-added.
		if ($v !== null) {
			$v->addTimelog($this);
		}

		return $this;
	}


	/**
	 * Get the associated LabourType object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     LabourType The associated LabourType object.
	 * @throws     PropelException
	 */
	public function getLabourType(PropelPDO $con = null)
	{
		if ($this->aLabourType === null && ($this->labour_type_id !== null)) {
			$c = new Criteria(LabourTypePeer::DATABASE_NAME);
			$c->add(LabourTypePeer::ID, $this->labour_type_id);
			$this->aLabourType = LabourTypePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aLabourType->addTimelogs($this);
			 */
		}
		return $this->aLabourType;
	}

	/**
	 * Declares an association between this object and a NonbillType object.
	 *
	 * @param      NonbillType $v
	 * @return     Timelog The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setNonbillType(NonbillType $v = null)
	{
		if ($v === null) {
			$this->setNonbillTypeId(NULL);
		} else {
			$this->setNonbillTypeId($v->getId());
		}

		$this->aNonbillType = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the NonbillType object, it will not be re-added.
		if ($v !== null) {
			$v->addTimelog($this);
		}

		return $this;
	}


	/**
	 * Get the associated NonbillType object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     NonbillType The associated NonbillType object.
	 * @throws     PropelException
	 */
	public function getNonbillType(PropelPDO $con = null)
	{
		if ($this->aNonbillType === null && ($this->nonbill_type_id !== null)) {
			$c = new Criteria(NonbillTypePeer::DATABASE_NAME);
			$c->add(NonbillTypePeer::ID, $this->nonbill_type_id);
			$this->aNonbillType = NonbillTypePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aNonbillType->addTimelogs($this);
			 */
		}
		return $this->aNonbillType;
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

			$this->aEmployee = null;
			$this->aWorkorderItem = null;
			$this->aInvoice = null;
			$this->aLabourType = null;
			$this->aNonbillType = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseTimelog:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseTimelog::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseTimelog
