<?php

/**
 * Base class that represents a row from the 'workorder_expense' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorderExpense extends BaseObject  implements Persistent {


  const PEER = 'WorkorderExpensePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkorderExpensePeer
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
	 * The value for the workorder_invoice_id field.
	 * @var        int
	 */
	protected $workorder_invoice_id;

	/**
	 * The value for the label field.
	 * @var        string
	 */
	protected $label;

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
	 * The value for the cost field.
	 * @var        string
	 */
	protected $cost;

	/**
	 * The value for the estimate field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $estimate;

	/**
	 * The value for the invoice field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $invoice;

	/**
	 * The value for the price field.
	 * @var        string
	 */
	protected $price;

	/**
	 * The value for the origin field.
	 * @var        string
	 */
	protected $origin;

	/**
	 * The value for the sub_contractor_flg field.
	 * @var        string
	 */
	protected $sub_contractor_flg;

	/**
	 * The value for the pst_override_flg field.
	 * @var        string
	 */
	protected $pst_override_flg;

	/**
	 * The value for the gst_override_flg field.
	 * @var        string
	 */
	protected $gst_override_flg;


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
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * @var        WorkorderItem
	 */
	protected $aWorkorderItem;

	/**
	 * @var        Invoice
	 */
	protected $aInvoiceRelatedByWorkorderInvoiceId;

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
	 * Initializes internal state of BaseWorkorderExpense object.
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
		$this->estimate = false;
		$this->invoice = true;
		$this->taxable_hst = '0';
		$this->taxable_gst = '0';
		$this->taxable_pst = '0';
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
	 * Get the [workorder_invoice_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderInvoiceId()
	{
		return $this->workorder_invoice_id;
	}

	/**
	 * Get the [label] column value.
	 * 
	 * @return     string
	 */
	public function getLabel()
	{
		return $this->label;
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
	 * Get the [cost] column value.
	 * 
	 * @return     string
	 */
	public function getCost()
	{
		return $this->cost;
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
	 * Get the [invoice] column value.
	 * 
	 * @return     boolean
	 */
	public function getInvoice()
	{
		return $this->invoice;
	}

	/**
	 * Get the [price] column value.
	 * 
	 * @return     string
	 */
	public function getPrice()
	{
		return $this->price;
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
	 * Get the [sub_contractor_flg] column value.
	 * 
	 * @return     string
	 */
	public function getSubContractorFlg()
	{
		return $this->sub_contractor_flg;
	}

     /**
	 * Get the [pst_override_flg] column value.
	 * 
	 * @return     string
	 */
	public function getPstOverrideFlg()
	{
		return $this->pst_override_flg;
	}

     /**
	 * Get the [gst_override_flg] column value.
	 * 
	 * @return     string
	 */
	public function getGstOverrideFlg()
	{
		return $this->gst_override_flg;
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
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [workorder_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setWorkorderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_item_id !== $v) {
			$this->workorder_item_id = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::WORKORDER_ITEM_ID;
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
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setWorkorderInvoiceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_invoice_id !== $v) {
			$this->workorder_invoice_id = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::WORKORDER_INVOICE_ID;
		}

		if ($this->aInvoiceRelatedByWorkorderInvoiceId !== null && $this->aInvoiceRelatedByWorkorderInvoiceId->getId() !== $v) {
			$this->aInvoiceRelatedByWorkorderInvoiceId = null;
		}

		return $this;
	} // setWorkorderInvoiceId()

	/**
	 * Set the value of [label] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setLabel($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->label !== $v) {
			$this->label = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::LABEL;
		}

		return $this;
	} // setLabel()

	/**
	 * Set the value of [customer_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setCustomerNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_notes !== $v) {
			$this->customer_notes = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::CUSTOMER_NOTES;
		}

		return $this;
	} // setCustomerNotes()

	/**
	 * Set the value of [internal_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setInternalNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->internal_notes !== $v) {
			$this->internal_notes = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::INTERNAL_NOTES;
		}

		return $this;
	} // setInternalNotes()

	/**
	 * Set the value of [cost] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setCost($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->cost !== $v) {
			$this->cost = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::COST;
		}

		return $this;
	} // setCost()

	/**
	 * Set the value of [estimate] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setEstimate($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->estimate !== $v || $v === false) {
			$this->estimate = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::ESTIMATE;
		}

		return $this;
	} // setEstimate()

	/**
	 * Set the value of [invoice] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setInvoice($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->invoice !== $v || $v === true) {
			$this->invoice = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::INVOICE;
		}

		return $this;
	} // setInvoice()

	/**
	 * Set the value of [price] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setPrice($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->price !== $v) {
			$this->price = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::PRICE;
		}

		return $this;
	} // setPrice()

	/**
	 * Set the value of [origin] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setOrigin($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->origin !== $v) {
			$this->origin = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::ORIGIN;
		}

		return $this;
	} // setOrigin()

	/**
	 * Set the value of [sub_contractor_flg] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setSubContractorFlg($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->sub_contractor_flg !== $v) {
			$this->sub_contractor_flg = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::SUB_CONTRACTOR_FLG;
		}

		return $this;
	} // setSubContractorFlg()

	/**
	 * Set the value of [pst_override_flg] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setPstOverrideFlg($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->pst_override_flg !== $v) {
			$this->pst_override_flg = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::PST_OVERRIDE_FLG;
		}

		return $this;
	} // setPstOverrideFlg()

	/**
	 * Set the value of [gst_override_flg] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setGstOverrideFlg($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->gst_override_flg !== $v) {
			$this->gst_override_flg = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::GST_OVERRIDE_FLG;
		}

		return $this;
	} // setGstOverrideFlg()
	

	/**
	 * Set the value of [taxable_hst] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setTaxableHst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_hst !== $v || $v === '0') {
			$this->taxable_hst = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::TAXABLE_HST;
		}

		return $this;
	} // setTaxableHst()

	/**
	 * Set the value of [taxable_gst] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setTaxableGst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_gst !== $v || $v === '0') {
			$this->taxable_gst = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::TAXABLE_GST;
		}

		return $this;
	} // setTaxableGst()

	/**
	 * Set the value of [taxable_pst] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderExpense The current object (for fluent API support)
	 */
	public function setTaxablePst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_pst !== $v || $v === '0') {
			$this->taxable_pst = $v;
			$this->modifiedColumns[] = WorkorderExpensePeer::TAXABLE_PST;
		}

		return $this;
	} // setTaxablePst()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WorkorderExpense The current object (for fluent API support)
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
				$this->modifiedColumns[] = WorkorderExpensePeer::CREATED_AT;
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
			if (array_diff($this->modifiedColumns, array(WorkorderExpensePeer::ESTIMATE,WorkorderExpensePeer::INVOICE,WorkorderExpensePeer::TAXABLE_HST,WorkorderExpensePeer::TAXABLE_GST,WorkorderExpensePeer::TAXABLE_PST,WorkorderExpensePeer::SUB_CONTRACTOR_FLG))) {
				return false;
			}

			if ($this->estimate !== false) {
				return false;
			}

			if ($this->invoice !== true) {
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

			if ($this->sub_contractor_flg !== 'N') {
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
			$this->workorder_invoice_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->label = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->customer_notes = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->internal_notes = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->cost = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->estimate = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->invoice = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->price = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->origin = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->taxable_hst = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->taxable_gst = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->taxable_pst = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->created_at = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->sub_contractor_flg = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->pst_override_flg = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->gst_override_flg = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + WorkorderExpensePeer::NUM_COLUMNS - WorkorderExpensePeer::NUM_LAZY_LOAD_COLUMNS;

		} catch (Exception $e) {
			throw new PropelException("Error populating WorkorderExpense object", $e);
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
		if ($this->aInvoiceRelatedByWorkorderInvoiceId !== null && $this->workorder_invoice_id !== $this->aInvoiceRelatedByWorkorderInvoiceId->getId()) {
			$this->aInvoiceRelatedByWorkorderInvoiceId = null;
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
			$con = Propel::getConnection(WorkorderExpensePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkorderExpensePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWorkorderItem = null;
			$this->aInvoiceRelatedByWorkorderInvoiceId = null;
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

    foreach (sfMixer::getCallables('BaseWorkorderExpense:delete:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderExpensePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			WorkorderExpensePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseWorkorderExpense:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseWorkorderExpense:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(WorkorderExpensePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(WorkorderExpensePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseWorkorderExpense:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			WorkorderExpensePeer::addInstanceToPool($this);
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

			if ($this->aInvoiceRelatedByWorkorderInvoiceId !== null) {
				if ($this->aInvoiceRelatedByWorkorderInvoiceId->isModified() || $this->aInvoiceRelatedByWorkorderInvoiceId->isNew()) {
					$affectedRows += $this->aInvoiceRelatedByWorkorderInvoiceId->save($con);
				}
				$this->setInvoiceRelatedByWorkorderInvoiceId($this->aInvoiceRelatedByWorkorderInvoiceId);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WorkorderExpensePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WorkorderExpensePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WorkorderExpensePeer::doUpdate($this, $con);
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

			if ($this->aInvoiceRelatedByWorkorderInvoiceId !== null) {
				if (!$this->aInvoiceRelatedByWorkorderInvoiceId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aInvoiceRelatedByWorkorderInvoiceId->getValidationFailures());
				}
			}


			if (($retval = WorkorderExpensePeer::doValidate($this, $columns)) !== true) {
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
		$pos = WorkorderExpensePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getWorkorderInvoiceId();
				break;
			case 3:
				return $this->getLabel();
				break;
			case 4:
				return $this->getCustomerNotes();
				break;
			case 5:
				return $this->getInternalNotes();
				break;
			case 6:
				return $this->getCost();
				break;
			case 7:
				return $this->getEstimate();
				break;
			case 8:
				return $this->getInvoice();
				break;
			case 9:
				return $this->getPrice();
				break;
			case 10:
				return $this->getOrigin();
				break;
			case 11:
				return $this->getTaxableHst();
				break;
			case 12:
				return $this->getTaxableGst();
				break;
			case 13:
				return $this->getTaxablePst();
				break;
			case 14:
				return $this->getCreatedAt();
				break;
			case 15:
				return $this->getSubContractorFlg();
				break;
			case 16:
				return $this->getPstOverrideFlg();
				break;
			case 17:
				return $this->getGstOverrideFlg();
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
		$keys = WorkorderExpensePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWorkorderItemId(),
			$keys[2] => $this->getWorkorderInvoiceId(),
			$keys[3] => $this->getLabel(),
			$keys[4] => $this->getCustomerNotes(),
			$keys[5] => $this->getInternalNotes(),
			$keys[6] => $this->getCost(),
			$keys[7] => $this->getEstimate(),
			$keys[8] => $this->getInvoice(),
			$keys[9] => $this->getPrice(),
			$keys[10] => $this->getOrigin(),
			$keys[11] => $this->getTaxableHst(),
			$keys[12] => $this->getTaxableGst(),
			$keys[13] => $this->getTaxablePst(),
			$keys[14] => $this->getCreatedAt(),
			$keys[15] => $this->getSubContractorFlg(),
			$keys[16] => $this->getPstOverrideFlg(),
			$keys[17] => $this->getGstOverrideFlg(),
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
		$pos = WorkorderExpensePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setWorkorderInvoiceId($value);
				break;
			case 3:
				$this->setLabel($value);
				break;
			case 4:
				$this->setCustomerNotes($value);
				break;
			case 5:
				$this->setInternalNotes($value);
				break;
			case 6:
				$this->setCost($value);
				break;
			case 7:
				$this->setEstimate($value);
				break;
			case 8:
				$this->setInvoice($value);
				break;
			case 9:
				$this->setPrice($value);
				break;
			case 10:
				$this->setOrigin($value);
				break;
			case 11:
				$this->setTaxableHst($value);
				break;
			case 12:
				$this->setTaxableGst($value);
				break;
			case 13:
				$this->setTaxablePst($value);
				break;
			case 14:
				$this->setCreatedAt($value);
				break;
			case 15:
				$this->setSubContractorFlg($value);
				break;
			case 16:
				$this->setPstOverrideFlg($value);
				break;
			case 17:
				$this->setGstOverrideFlg($value);
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
		$keys = WorkorderExpensePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWorkorderItemId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setWorkorderInvoiceId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLabel($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCustomerNotes($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setInternalNotes($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCost($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEstimate($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setInvoice($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setPrice($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setOrigin($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setTaxableHst($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setTaxableGst($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setTaxablePst($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setCreatedAt($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setSubContractorFlg($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setPstOverrideFlg($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setGstOverrideFlg($arr[$keys[17]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WorkorderExpensePeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkorderExpensePeer::ID)) $criteria->add(WorkorderExpensePeer::ID, $this->id);
		if ($this->isColumnModified(WorkorderExpensePeer::WORKORDER_ITEM_ID)) $criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->workorder_item_id);
		if ($this->isColumnModified(WorkorderExpensePeer::WORKORDER_INVOICE_ID)) $criteria->add(WorkorderExpensePeer::WORKORDER_INVOICE_ID, $this->workorder_invoice_id);
		if ($this->isColumnModified(WorkorderExpensePeer::LABEL)) $criteria->add(WorkorderExpensePeer::LABEL, $this->label);
		if ($this->isColumnModified(WorkorderExpensePeer::CUSTOMER_NOTES)) $criteria->add(WorkorderExpensePeer::CUSTOMER_NOTES, $this->customer_notes);
		if ($this->isColumnModified(WorkorderExpensePeer::INTERNAL_NOTES)) $criteria->add(WorkorderExpensePeer::INTERNAL_NOTES, $this->internal_notes);
		if ($this->isColumnModified(WorkorderExpensePeer::COST)) $criteria->add(WorkorderExpensePeer::COST, $this->cost);
		if ($this->isColumnModified(WorkorderExpensePeer::ESTIMATE)) $criteria->add(WorkorderExpensePeer::ESTIMATE, $this->estimate);
		if ($this->isColumnModified(WorkorderExpensePeer::INVOICE)) $criteria->add(WorkorderExpensePeer::INVOICE, $this->invoice);
		if ($this->isColumnModified(WorkorderExpensePeer::PRICE)) $criteria->add(WorkorderExpensePeer::PRICE, $this->price);
		if ($this->isColumnModified(WorkorderExpensePeer::ORIGIN)) $criteria->add(WorkorderExpensePeer::ORIGIN, $this->origin);
		if ($this->isColumnModified(WorkorderExpensePeer::TAXABLE_HST)) $criteria->add(WorkorderExpensePeer::TAXABLE_HST, $this->taxable_hst);
		if ($this->isColumnModified(WorkorderExpensePeer::TAXABLE_GST)) $criteria->add(WorkorderExpensePeer::TAXABLE_GST, $this->taxable_gst);
		if ($this->isColumnModified(WorkorderExpensePeer::TAXABLE_PST)) $criteria->add(WorkorderExpensePeer::TAXABLE_PST, $this->taxable_pst);
		if ($this->isColumnModified(WorkorderExpensePeer::CREATED_AT)) $criteria->add(WorkorderExpensePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(WorkorderExpensePeer::SUB_CONTRACTOR_FLG)) $criteria->add(WorkorderExpensePeer::SUB_CONTRACTOR_FLG, $this->sub_contractor_flg);
		if ($this->isColumnModified(WorkorderExpensePeer::PST_OVERRIDE_FLG)) $criteria->add(WorkorderExpensePeer::PST_OVERRIDE_FLG, $this->pst_override_flg);
		if ($this->isColumnModified(WorkorderExpensePeer::GST_OVERRIDE_FLG)) $criteria->add(WorkorderExpensePeer::GST_OVERRIDE_FLG, $this->gst_override_flg);

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
		$criteria = new Criteria(WorkorderExpensePeer::DATABASE_NAME);

		$criteria->add(WorkorderExpensePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of WorkorderExpense (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setWorkorderItemId($this->workorder_item_id);

		$copyObj->setWorkorderInvoiceId($this->workorder_invoice_id);

		$copyObj->setLabel($this->label);

		$copyObj->setCustomerNotes($this->customer_notes);

		$copyObj->setInternalNotes($this->internal_notes);

		$copyObj->setCost($this->cost);

		$copyObj->setEstimate($this->estimate);

		$copyObj->setInvoice($this->invoice);

		$copyObj->setPrice($this->price);

		$copyObj->setOrigin($this->origin);

		$copyObj->setTaxableHst($this->taxable_hst);

		$copyObj->setTaxableGst($this->taxable_gst);

		$copyObj->setTaxablePst($this->taxable_pst);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setSubContractorFlg($this->sub_contractor_flg);

		$copyObj->setPstOverrideFlg($this->pst_override_flg);

		$copyObj->setGstOverrideFlg($this->gst_override_flg);

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
	 * @return     WorkorderExpense Clone of current object.
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
	 * @return     WorkorderExpensePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkorderExpensePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WorkorderItem object.
	 *
	 * @param      WorkorderItem $v
	 * @return     WorkorderExpense The current object (for fluent API support)
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
			$v->addWorkorderExpense($this);
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
			   $this->aWorkorderItem->addWorkorderExpenses($this);
			 */
		}
		return $this->aWorkorderItem;
	}

	/**
	 * Declares an association between this object and a Invoice object.
	 *
	 * @param      Invoice $v
	 * @return     WorkorderExpense The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setInvoiceRelatedByWorkorderInvoiceId(Invoice $v = null)
	{
		if ($v === null) {
			$this->setWorkorderInvoiceId(NULL);
		} else {
			$this->setWorkorderInvoiceId($v->getId());
		}

		$this->aInvoiceRelatedByWorkorderInvoiceId = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Invoice object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderExpense($this);
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
	public function getInvoiceRelatedByWorkorderInvoiceId(PropelPDO $con = null)
	{
		if ($this->aInvoiceRelatedByWorkorderInvoiceId === null && ($this->workorder_invoice_id !== null)) {
			$c = new Criteria(InvoicePeer::DATABASE_NAME);
			$c->add(InvoicePeer::ID, $this->workorder_invoice_id);
			$this->aInvoiceRelatedByWorkorderInvoiceId = InvoicePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aInvoiceRelatedByWorkorderInvoiceId->addWorkorderExpenses($this);
			 */
		}
		return $this->aInvoiceRelatedByWorkorderInvoiceId;
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
			$this->aInvoiceRelatedByWorkorderInvoiceId = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseWorkorderExpense:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseWorkorderExpense::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseWorkorderExpense
