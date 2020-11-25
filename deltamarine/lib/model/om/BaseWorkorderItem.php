<?php

/**
 * Base class that represents a row from the 'workorder_item' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorderItem extends BaseObject  implements Persistent {


  const PEER = 'WorkorderItemPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WorkorderItemPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the workorder_id field.
	 * @var        int
	 */
	protected $workorder_id;

	/**
	 * The value for the label field.
	 * @var        string
	 */
	protected $label;

	/**
	 * The value for the lft field.
	 * @var        int
	 */
	protected $lft;

	/**
	 * The value for the rgt field.
	 * @var        int
	 */
	protected $rgt;

	/**
	 * The value for the owner_company field.
	 * @var        int
	 */
	protected $owner_company;

	/**
	 * The value for the labour_estimate field.
	 * @var        string
	 */
	protected $labour_estimate;

	/**
	 * The value for the labour_actual field.
	 * @var        string
	 */
	protected $labour_actual;

	/**
	 * The value for the other_estimate field.
	 * @var        string
	 */
	protected $other_estimate;

	/**
	 * The value for the other_actual field.
	 * @var        string
	 */
	protected $other_actual;

	/**
	 * The value for the part_estimate field.
	 * @var        string
	 */
	protected $part_estimate;

	/**
	 * The value for the part_actual field.
	 * @var        string
	 */
	protected $part_actual;

	/**
	 * The value for the amount_paid field.
	 * @var        string
	 */
	protected $amount_paid;

	/**
	 * The value for the completed field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $completed;

	/**
	 * The value for the completed_by field.
	 * @var        int
	 */
	protected $completed_by;

	/**
	 * The value for the completed_date field.
	 * @var        string
	 */
	protected $completed_date;

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
	 * The value for the color_code field.
	 * Note: this column has a database default value of: 'FFFFFF'
	 * @var        string
	 */
	protected $color_code;

	/**
	 * @var        Workorder
	 */
	protected $aWorkorder;

	/**
	 * @var        Employee
	 */
	protected $aEmployee;

	/**
	 * @var        array PartInstance[] Collection to store aggregation of PartInstance objects.
	 */
	protected $collPartInstances;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartInstances.
	 */
	private $lastPartInstanceCriteria = null;

	/**
	 * @var        array WorkorderItemBillable[] Collection to store aggregation of WorkorderItemBillable objects.
	 */
	protected $collWorkorderItemBillables;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItemBillables.
	 */
	private $lastWorkorderItemBillableCriteria = null;

	/**
	 * @var        array WorkorderExpense[] Collection to store aggregation of WorkorderExpense objects.
	 */
	protected $collWorkorderExpenses;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderExpenses.
	 */
	private $lastWorkorderExpenseCriteria = null;

	/**
	 * @var        array WorkorderItemPhoto[] Collection to store aggregation of WorkorderItemPhoto objects.
	 */
	protected $collWorkorderItemPhotos;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItemPhotos.
	 */
	private $lastWorkorderItemPhotoCriteria = null;

	/**
	 * @var        array WorkorderItemFile[] Collection to store aggregation of WorkorderItemFile objects.
	 */
	protected $collWorkorderItemFiles;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWorkorderItemFiles.
	 */
	private $lastWorkorderItemFileCriteria = null;

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

	protected $task_code;
	/**
	 * Initializes internal state of BaseWorkorderItem object.
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
		$this->completed = false;
		$this->color_code = 'FFFFFF';
		$this->task_code = 'FFFFFF';
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
	 * Get the [workorder_id] column value.
	 * 
	 * @return     int
	 */
	public function getWorkorderId()
	{
		return $this->workorder_id;
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
	 * Get the [lft] column value.
	 * 
	 * @return     int
	 */
	public function getLeft()
	{
		return $this->lft;
	}

	/**
	 * Get the [rgt] column value.
	 * 
	 * @return     int
	 */
	public function getRight()
	{
		return $this->rgt;
	}

	/**
	 * Get the [owner_company] column value.
	 * 
	 * @return     int
	 */
	public function getOwnerCompany()
	{
		return $this->owner_company;
	}

	/**
	 * Get the [labour_estimate] column value.
	 * 
	 * @return     string
	 */
	public function getLabourEstimate()
	{
		return $this->labour_estimate;
	}

	/**
	 * Get the [labour_actual] column value.
	 * 
	 * @return     string
	 */
	public function getLabourActual()
	{
		return $this->labour_actual;
	}

	/**
	 * Get the [other_estimate] column value.
	 * 
	 * @return     string
	 */
	public function getOtherEstimate()
	{
		return $this->other_estimate;
	}

	/**
	 * Get the [other_actual] column value.
	 * 
	 * @return     string
	 */
	public function getOtherActual()
	{
		return $this->other_actual;
	}

	/**
	 * Get the [part_estimate] column value.
	 * 
	 * @return     string
	 */
	public function getPartEstimate()
	{
		return $this->part_estimate;
	}

	/**
	 * Get the [part_actual] column value.
	 * 
	 * @return     string
	 */
	public function getPartActual()
	{
		return $this->part_actual;
	}

	/**
	 * Get the [amount_paid] column value.
	 * 
	 * @return     string
	 */
	public function getAmountPaid()
	{
		return $this->amount_paid;
	}

	/**
	 * Get the [completed] column value.
	 * 
	 * @return     boolean
	 */
	public function getCompleted()
	{
		return $this->completed;
	}

	/**
	 * Get the [completed_by] column value.
	 * 
	 * @return     int
	 */
	public function getCompletedBy()
	{
		return $this->completed_by;
	}

	/**
	 * Get the [optionally formatted] temporal [completed_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCompletedDate($format = 'Y-m-d H:i:s')
	{
		if ($this->completed_date === null) {
			return null;
		}


		if ($this->completed_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->completed_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->completed_date, true), $x);
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
	 * Get the [color_code] column value.
	 * 
	 * @return     string
	 */
	public function getColorCode()
	{
		return $this->color_code;
	}

	public function getTaskColorCode(){
		return $this->task_code;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [workorder_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setWorkorderId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_id !== $v) {
			$this->workorder_id = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::WORKORDER_ID;
		}

		if ($this->aWorkorder !== null && $this->aWorkorder->getId() !== $v) {
			$this->aWorkorder = null;
		}

		return $this;
	} // setWorkorderId()

	/**
	 * Set the value of [label] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLabel($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->label !== $v) {
			$this->label = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::LABEL;
		}

		return $this;
	} // setLabel()

	/**
	 * Set the value of [lft] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLeft($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->lft !== $v) {
			$this->lft = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::LFT;
		}

		return $this;
	} // setLeft()

	/**
	 * Set the value of [rgt] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setRight($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->rgt !== $v) {
			$this->rgt = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::RGT;
		}

		return $this;
	} // setRight()

	/**
	 * Set the value of [owner_company] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setOwnerCompany($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->owner_company !== $v) {
			$this->owner_company = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::OWNER_COMPANY;
		}

		return $this;
	} // setOwnerCompany()

	/**
	 * Set the value of [labour_estimate] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLabourEstimate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->labour_estimate !== $v) {
			$this->labour_estimate = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::LABOUR_ESTIMATE;
		}

		return $this;
	} // setLabourEstimate()

	/**
	 * Set the value of [labour_actual] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setLabourActual($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->labour_actual !== $v) {
			$this->labour_actual = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::LABOUR_ACTUAL;
		}

		return $this;
	} // setLabourActual()

	/**
	 * Set the value of [other_estimate] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setOtherEstimate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->other_estimate !== $v) {
			$this->other_estimate = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::OTHER_ESTIMATE;
		}

		return $this;
	} // setOtherEstimate()

	/**
	 * Set the value of [other_actual] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setOtherActual($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->other_actual !== $v) {
			$this->other_actual = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::OTHER_ACTUAL;
		}

		return $this;
	} // setOtherActual()

	/**
	 * Set the value of [part_estimate] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setPartEstimate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->part_estimate !== $v) {
			$this->part_estimate = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::PART_ESTIMATE;
		}

		return $this;
	} // setPartEstimate()

	/**
	 * Set the value of [part_actual] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setPartActual($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->part_actual !== $v) {
			$this->part_actual = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::PART_ACTUAL;
		}

		return $this;
	} // setPartActual()

	/**
	 * Set the value of [amount_paid] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setAmountPaid($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount_paid !== $v) {
			$this->amount_paid = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::AMOUNT_PAID;
		}

		return $this;
	} // setAmountPaid()

	/**
	 * Set the value of [completed] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setCompleted($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->completed !== $v || $v === false) {
			$this->completed = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::COMPLETED;
		}

		return $this;
	} // setCompleted()

	/**
	 * Set the value of [completed_by] column.
	 * 
	 * @param      int $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setCompletedBy($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->completed_by !== $v) {
			$this->completed_by = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::COMPLETED_BY;
		}

		if ($this->aEmployee !== null && $this->aEmployee->getId() !== $v) {
			$this->aEmployee = null;
		}

		return $this;
	} // setCompletedBy()

	/**
	 * Sets the value of [completed_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setCompletedDate($v)
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

		if ( $this->completed_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->completed_date !== null && $tmpDt = new DateTime($this->completed_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->completed_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WorkorderItemPeer::COMPLETED_DATE;
			}
		} // if either are not null

		return $this;
	} // setCompletedDate()

	/**
	 * Set the value of [customer_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setCustomerNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->customer_notes !== $v) {
			$this->customer_notes = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::CUSTOMER_NOTES;
		}

		return $this;
	} // setCustomerNotes()

	/**
	 * Set the value of [internal_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setInternalNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->internal_notes !== $v) {
			$this->internal_notes = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::INTERNAL_NOTES;
		}

		return $this;
	} // setInternalNotes()

	/**
	 * Set the value of [color_code] column.
	 * 
	 * @param      string $v new value
	 * @return     WorkorderItem The current object (for fluent API support)
	 */
	public function setColorCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->color_code !== $v || $v === 'FFFFFF') {
			$this->color_code = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::COLOR_CODE;
		}

		return $this;
	} // setColorCode()

	public function setTaskColorCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->task_code !== $v || $v === 'FFFFFF') {
			$this->task_code = $v;
			$this->modifiedColumns[] = WorkorderItemPeer::TASK_CODE;
		}

		return $this;
	} // setColorCode()

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
			if (array_diff($this->modifiedColumns, array(WorkorderItemPeer::COMPLETED,WorkorderItemPeer::COLOR_CODE, WorkorderItemPeer::TASK_CODE))) {
				return false;
			}

			if ($this->completed !== false) {
				return false;
			}

			if ($this->color_code !== 'FFFFFF') {
				return false;
			}

			if ($this->task_code !== 'FFFFFF') {
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
			$this->workorder_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->label = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->lft = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->rgt = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->owner_company = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->labour_estimate = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->labour_actual = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->other_estimate = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->other_actual = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->part_estimate = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->part_actual = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->amount_paid = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->completed = ($row[$startcol + 13] !== null) ? (boolean) $row[$startcol + 13] : null;
			$this->completed_by = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
			$this->completed_date = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->customer_notes = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->internal_notes = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
			$this->color_code = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
			$this->task_code = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 19; // 19 = WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WorkorderItem object", $e);
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

		if ($this->aWorkorder !== null && $this->workorder_id !== $this->aWorkorder->getId()) {
			$this->aWorkorder = null;
		}
		if ($this->aEmployee !== null && $this->completed_by !== $this->aEmployee->getId()) {
			$this->aEmployee = null;
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
			$con = Propel::getConnection(WorkorderItemPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WorkorderItemPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWorkorder = null;
			$this->aEmployee = null;
			$this->collPartInstances = null;
			$this->lastPartInstanceCriteria = null;

			$this->collWorkorderItemBillables = null;
			$this->lastWorkorderItemBillableCriteria = null;

			$this->collWorkorderExpenses = null;
			$this->lastWorkorderExpenseCriteria = null;

			$this->collWorkorderItemPhotos = null;
			$this->lastWorkorderItemPhotoCriteria = null;

			$this->collWorkorderItemFiles = null;
			$this->lastWorkorderItemFileCriteria = null;

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

    foreach (sfMixer::getCallables('BaseWorkorderItem:delete:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderItemPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			WorkorderItemPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseWorkorderItem:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseWorkorderItem:save:pre') as $callable)
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
			$con = Propel::getConnection(WorkorderItemPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseWorkorderItem:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			WorkorderItemPeer::addInstanceToPool($this);
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

			if ($this->aWorkorder !== null) {
				if ($this->aWorkorder->isModified() || $this->aWorkorder->isNew()) {
					$affectedRows += $this->aWorkorder->save($con);
				}
				$this->setWorkorder($this->aWorkorder);
			}

			if ($this->aEmployee !== null) {
				if ($this->aEmployee->isModified() || $this->aEmployee->isNew()) {
					$affectedRows += $this->aEmployee->save($con);
				}
				$this->setEmployee($this->aEmployee);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WorkorderItemPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WorkorderItemPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WorkorderItemPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPartInstances !== null) {
				foreach ($this->collPartInstances as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderItemBillables !== null) {
				foreach ($this->collWorkorderItemBillables as $referrerFK) {
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

			if ($this->collWorkorderItemPhotos !== null) {
				foreach ($this->collWorkorderItemPhotos as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWorkorderItemFiles !== null) {
				foreach ($this->collWorkorderItemFiles as $referrerFK) {
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

			if ($this->aWorkorder !== null) {
				if (!$this->aWorkorder->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWorkorder->getValidationFailures());
				}
			}

			if ($this->aEmployee !== null) {
				if (!$this->aEmployee->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEmployee->getValidationFailures());
				}
			}


			if (($retval = WorkorderItemPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPartInstances !== null) {
					foreach ($this->collPartInstances as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderItemBillables !== null) {
					foreach ($this->collWorkorderItemBillables as $referrerFK) {
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

				if ($this->collWorkorderItemPhotos !== null) {
					foreach ($this->collWorkorderItemPhotos as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWorkorderItemFiles !== null) {
					foreach ($this->collWorkorderItemFiles as $referrerFK) {
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
		$pos = WorkorderItemPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getWorkorderId();
				break;
			case 2:
				return $this->getLabel();
				break;
			case 3:
				return $this->getLeft();
				break;
			case 4:
				return $this->getRight();
				break;
			case 5:
				return $this->getOwnerCompany();
				break;
			case 6:
				return $this->getLabourEstimate();
				break;
			case 7:
				return $this->getLabourActual();
				break;
			case 8:
				return $this->getOtherEstimate();
				break;
			case 9:
				return $this->getOtherActual();
				break;
			case 10:
				return $this->getPartEstimate();
				break;
			case 11:
				return $this->getPartActual();
				break;
			case 12:
				return $this->getAmountPaid();
				break;
			case 13:
				return $this->getCompleted();
				break;
			case 14:
				return $this->getCompletedBy();
				break;
			case 15:
				return $this->getCompletedDate();
				break;
			case 16:
				return $this->getCustomerNotes();
				break;
			case 17:
				return $this->getInternalNotes();
				break;
			case 18:
				return $this->getColorCode();
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
		$keys = WorkorderItemPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getWorkorderId(),
			$keys[2] => $this->getLabel(),
			$keys[3] => $this->getLeft(),
			$keys[4] => $this->getRight(),
			$keys[5] => $this->getOwnerCompany(),
			$keys[6] => $this->getLabourEstimate(),
			$keys[7] => $this->getLabourActual(),
			$keys[8] => $this->getOtherEstimate(),
			$keys[9] => $this->getOtherActual(),
			$keys[10] => $this->getPartEstimate(),
			$keys[11] => $this->getPartActual(),
			$keys[12] => $this->getAmountPaid(),
			$keys[13] => $this->getCompleted(),
			$keys[14] => $this->getCompletedBy(),
			$keys[15] => $this->getCompletedDate(),
			$keys[16] => $this->getCustomerNotes(),
			$keys[17] => $this->getInternalNotes(),
			$keys[18] => $this->getColorCode(),
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
		$pos = WorkorderItemPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setWorkorderId($value);
				break;
			case 2:
				$this->setLabel($value);
				break;
			case 3:
				$this->setLeft($value);
				break;
			case 4:
				$this->setRight($value);
				break;
			case 5:
				$this->setOwnerCompany($value);
				break;
			case 6:
				$this->setLabourEstimate($value);
				break;
			case 7:
				$this->setLabourActual($value);
				break;
			case 8:
				$this->setOtherEstimate($value);
				break;
			case 9:
				$this->setOtherActual($value);
				break;
			case 10:
				$this->setPartEstimate($value);
				break;
			case 11:
				$this->setPartActual($value);
				break;
			case 12:
				$this->setAmountPaid($value);
				break;
			case 13:
				$this->setCompleted($value);
				break;
			case 14:
				$this->setCompletedBy($value);
				break;
			case 15:
				$this->setCompletedDate($value);
				break;
			case 16:
				$this->setCustomerNotes($value);
				break;
			case 17:
				$this->setInternalNotes($value);
				break;
			case 18:
				$this->setColorCode($value);
				break;
			case 19:
				$this->setTaskColorCode($value);
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
		$keys = WorkorderItemPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setWorkorderId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setLabel($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLeft($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setRight($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setOwnerCompany($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setLabourEstimate($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setLabourActual($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setOtherEstimate($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setOtherActual($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setPartEstimate($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setPartActual($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setAmountPaid($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setCompleted($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setCompletedBy($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setCompletedDate($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setCustomerNotes($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setInternalNotes($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setColorCode($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setTaskColorCode($arr[$keys[19]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);

		if ($this->isColumnModified(WorkorderItemPeer::ID)) $criteria->add(WorkorderItemPeer::ID, $this->id);
		if ($this->isColumnModified(WorkorderItemPeer::WORKORDER_ID)) $criteria->add(WorkorderItemPeer::WORKORDER_ID, $this->workorder_id);
		if ($this->isColumnModified(WorkorderItemPeer::LABEL)) $criteria->add(WorkorderItemPeer::LABEL, $this->label);
		if ($this->isColumnModified(WorkorderItemPeer::LFT)) $criteria->add(WorkorderItemPeer::LFT, $this->lft);
		if ($this->isColumnModified(WorkorderItemPeer::RGT)) $criteria->add(WorkorderItemPeer::RGT, $this->rgt);
		if ($this->isColumnModified(WorkorderItemPeer::OWNER_COMPANY)) $criteria->add(WorkorderItemPeer::OWNER_COMPANY, $this->owner_company);
		if ($this->isColumnModified(WorkorderItemPeer::LABOUR_ESTIMATE)) $criteria->add(WorkorderItemPeer::LABOUR_ESTIMATE, $this->labour_estimate);
		if ($this->isColumnModified(WorkorderItemPeer::LABOUR_ACTUAL)) $criteria->add(WorkorderItemPeer::LABOUR_ACTUAL, $this->labour_actual);
		if ($this->isColumnModified(WorkorderItemPeer::OTHER_ESTIMATE)) $criteria->add(WorkorderItemPeer::OTHER_ESTIMATE, $this->other_estimate);
		if ($this->isColumnModified(WorkorderItemPeer::OTHER_ACTUAL)) $criteria->add(WorkorderItemPeer::OTHER_ACTUAL, $this->other_actual);
		if ($this->isColumnModified(WorkorderItemPeer::PART_ESTIMATE)) $criteria->add(WorkorderItemPeer::PART_ESTIMATE, $this->part_estimate);
		if ($this->isColumnModified(WorkorderItemPeer::PART_ACTUAL)) $criteria->add(WorkorderItemPeer::PART_ACTUAL, $this->part_actual);
		if ($this->isColumnModified(WorkorderItemPeer::AMOUNT_PAID)) $criteria->add(WorkorderItemPeer::AMOUNT_PAID, $this->amount_paid);
		if ($this->isColumnModified(WorkorderItemPeer::COMPLETED)) $criteria->add(WorkorderItemPeer::COMPLETED, $this->completed);
		if ($this->isColumnModified(WorkorderItemPeer::COMPLETED_BY)) $criteria->add(WorkorderItemPeer::COMPLETED_BY, $this->completed_by);
		if ($this->isColumnModified(WorkorderItemPeer::COMPLETED_DATE)) $criteria->add(WorkorderItemPeer::COMPLETED_DATE, $this->completed_date);
		if ($this->isColumnModified(WorkorderItemPeer::CUSTOMER_NOTES)) $criteria->add(WorkorderItemPeer::CUSTOMER_NOTES, $this->customer_notes);
		if ($this->isColumnModified(WorkorderItemPeer::INTERNAL_NOTES)) $criteria->add(WorkorderItemPeer::INTERNAL_NOTES, $this->internal_notes);
		if ($this->isColumnModified(WorkorderItemPeer::COLOR_CODE)) $criteria->add(WorkorderItemPeer::COLOR_CODE, $this->color_code);
		if ($this->isColumnModified(WorkorderItemPeer::TASK_CODE)) $criteria->add(WorkorderItemPeer::TASK_CODE, $this->task_code);
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
		$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);

		$criteria->add(WorkorderItemPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of WorkorderItem (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setWorkorderId($this->workorder_id);

		$copyObj->setLabel($this->label);

		$copyObj->setLeft($this->lft);

		$copyObj->setRight($this->rgt);

		$copyObj->setOwnerCompany($this->owner_company);

		$copyObj->setLabourEstimate($this->labour_estimate);

		$copyObj->setLabourActual($this->labour_actual);

		$copyObj->setOtherEstimate($this->other_estimate);

		$copyObj->setOtherActual($this->other_actual);

		$copyObj->setPartEstimate($this->part_estimate);

		$copyObj->setPartActual($this->part_actual);

		$copyObj->setAmountPaid($this->amount_paid);

		$copyObj->setCompleted($this->completed);

		$copyObj->setCompletedBy($this->completed_by);

		$copyObj->setCompletedDate($this->completed_date);

		$copyObj->setCustomerNotes($this->customer_notes);

		$copyObj->setInternalNotes($this->internal_notes);

		$copyObj->setColorCode($this->color_code);

		$copyObj->setTaskColorCode($this->task_code);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPartInstances() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartInstance($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderItemBillables() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItemBillable($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderExpenses() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderExpense($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderItemPhotos() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItemPhoto($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWorkorderItemFiles() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWorkorderItemFile($relObj->copy($deepCopy));
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
	 * @return     WorkorderItem Clone of current object.
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
	 * @return     WorkorderItemPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WorkorderItemPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Workorder object.
	 *
	 * @param      Workorder $v
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWorkorder(Workorder $v = null)
	{
		if ($v === null) {
			$this->setWorkorderId(NULL);
		} else {
			$this->setWorkorderId($v->getId());
		}

		$this->aWorkorder = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Workorder object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderItem($this);
		}

		return $this;
	}


	/**
	 * Get the associated Workorder object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Workorder The associated Workorder object.
	 * @throws     PropelException
	 */
	public function getWorkorder(PropelPDO $con = null)
	{
		if ($this->aWorkorder === null && ($this->workorder_id !== null)) {
			$c = new Criteria(WorkorderPeer::DATABASE_NAME);
			$c->add(WorkorderPeer::ID, $this->workorder_id);
			$this->aWorkorder = WorkorderPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aWorkorder->addWorkorderItems($this);
			 */
		}
		return $this->aWorkorder;
	}

	/**
	 * Declares an association between this object and a Employee object.
	 *
	 * @param      Employee $v
	 * @return     WorkorderItem The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEmployee(Employee $v = null)
	{
		if ($v === null) {
			$this->setCompletedBy(NULL);
		} else {
			$this->setCompletedBy($v->getId());
		}

		$this->aEmployee = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Employee object, it will not be re-added.
		if ($v !== null) {
			$v->addWorkorderItem($this);
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
		if ($this->aEmployee === null && ($this->completed_by !== null)) {
			$c = new Criteria(EmployeePeer::DATABASE_NAME);
			$c->add(EmployeePeer::ID, $this->completed_by);
			$this->aEmployee = EmployeePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEmployee->addWorkorderItems($this);
			 */
		}
		return $this->aEmployee;
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
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related PartInstances from storage. If this WorkorderItem is new, it will return
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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
			   $this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
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

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				$count = PartInstancePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

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
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getPartInstancesJoinPartVariant($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinPartVariant($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

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
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getPartInstancesJoinSupplierOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

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
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getPartInstancesJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getPartInstancesJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
	}

	/**
	 * Clears out the collWorkorderItemBillables collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderItemBillables()
	 */
	public function clearWorkorderItemBillables()
	{
		$this->collWorkorderItemBillables = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderItemBillables collection (array).
	 *
	 * By default this just sets the collWorkorderItemBillables collection to an empty array (like clearcollWorkorderItemBillables());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderItemBillables()
	{
		$this->collWorkorderItemBillables = array();
	}

	/**
	 * Gets an array of WorkorderItemBillable objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related WorkorderItemBillables from storage. If this WorkorderItem is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderItemBillable[]
	 * @throws     PropelException
	 */
	public function getWorkorderItemBillables($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemBillables === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItemBillables = array();
			} else {

				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderItemBillablePeer::addSelectColumns($criteria);
				$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderItemBillablePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderItemBillableCriteria) || !$this->lastWorkorderItemBillableCriteria->equals($criteria)) {
					$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderItemBillableCriteria = $criteria;
		return $this->collWorkorderItemBillables;
	}

	/**
	 * Returns the number of related WorkorderItemBillable objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderItemBillable objects.
	 * @throws     PropelException
	 */
	public function countWorkorderItemBillables(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderItemBillables === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				$count = WorkorderItemBillablePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				if (!isset($this->lastWorkorderItemBillableCriteria) || !$this->lastWorkorderItemBillableCriteria->equals($criteria)) {
					$count = WorkorderItemBillablePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderItemBillables);
				}
			} else {
				$count = count($this->collWorkorderItemBillables);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderItemBillable object to this object
	 * through the WorkorderItemBillable foreign key attribute.
	 *
	 * @param      WorkorderItemBillable $l WorkorderItemBillable
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderItemBillable(WorkorderItemBillable $l)
	{
		if ($this->collWorkorderItemBillables === null) {
			$this->initWorkorderItemBillables();
		}
		if (!in_array($l, $this->collWorkorderItemBillables, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderItemBillables, $l);
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related WorkorderItemBillables from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getWorkorderItemBillablesJoinManufacturer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemBillables === null) {
			if ($this->isNew()) {
				$this->collWorkorderItemBillables = array();
			} else {

				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastWorkorderItemBillableCriteria) || !$this->lastWorkorderItemBillableCriteria->equals($criteria)) {
				$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelectJoinManufacturer($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemBillableCriteria = $criteria;

		return $this->collWorkorderItemBillables;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related WorkorderItemBillables from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getWorkorderItemBillablesJoinSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemBillables === null) {
			if ($this->isNew()) {
				$this->collWorkorderItemBillables = array();
			} else {

				$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastWorkorderItemBillableCriteria) || !$this->lastWorkorderItemBillableCriteria->equals($criteria)) {
				$this->collWorkorderItemBillables = WorkorderItemBillablePeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemBillableCriteria = $criteria;

		return $this->collWorkorderItemBillables;
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
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related WorkorderExpenses from storage. If this WorkorderItem is new, it will return
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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderExpenses === null) {
			if ($this->isNew()) {
			   $this->collWorkorderExpenses = array();
			} else {

				$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderExpensePeer::addSelectColumns($criteria);
				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
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

				$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

				$count = WorkorderExpensePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

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
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related WorkorderExpenses from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getWorkorderExpensesJoinInvoiceRelatedByWorkorderInvoiceId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderExpenses === null) {
			if ($this->isNew()) {
				$this->collWorkorderExpenses = array();
			} else {

				$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelectJoinInvoiceRelatedByWorkorderInvoiceId($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastWorkorderExpenseCriteria) || !$this->lastWorkorderExpenseCriteria->equals($criteria)) {
				$this->collWorkorderExpenses = WorkorderExpensePeer::doSelectJoinInvoiceRelatedByWorkorderInvoiceId($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderExpenseCriteria = $criteria;

		return $this->collWorkorderExpenses;
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
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related WorkorderItemPhotos from storage. If this WorkorderItem is new, it will return
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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemPhotos === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItemPhotos = array();
			} else {

				$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderItemPhotoPeer::addSelectColumns($criteria);
				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
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

				$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

				$count = WorkorderItemPhotoPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

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
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related WorkorderItemPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getWorkorderItemPhotosJoinPhoto($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemPhotos === null) {
			if ($this->isNew()) {
				$this->collWorkorderItemPhotos = array();
			} else {

				$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastWorkorderItemPhotoCriteria) || !$this->lastWorkorderItemPhotoCriteria->equals($criteria)) {
				$this->collWorkorderItemPhotos = WorkorderItemPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemPhotoCriteria = $criteria;

		return $this->collWorkorderItemPhotos;
	}

	/**
	 * Clears out the collWorkorderItemFiles collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWorkorderItemFiles()
	 */
	public function clearWorkorderItemFiles()
	{
		$this->collWorkorderItemFiles = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWorkorderItemFiles collection (array).
	 *
	 * By default this just sets the collWorkorderItemFiles collection to an empty array (like clearcollWorkorderItemFiles());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWorkorderItemFiles()
	{
		$this->collWorkorderItemFiles = array();
	}

	/**
	 * Gets an array of WorkorderItemFile objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related WorkorderItemFiles from storage. If this WorkorderItem is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WorkorderItemFile[]
	 * @throws     PropelException
	 */
	public function getWorkorderItemFiles($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemFiles === null) {
			if ($this->isNew()) {
			   $this->collWorkorderItemFiles = array();
			} else {

				$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderItemFilePeer::addSelectColumns($criteria);
				$this->collWorkorderItemFiles = WorkorderItemFilePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

				WorkorderItemFilePeer::addSelectColumns($criteria);
				if (!isset($this->lastWorkorderItemFileCriteria) || !$this->lastWorkorderItemFileCriteria->equals($criteria)) {
					$this->collWorkorderItemFiles = WorkorderItemFilePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWorkorderItemFileCriteria = $criteria;
		return $this->collWorkorderItemFiles;
	}

	/**
	 * Returns the number of related WorkorderItemFile objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WorkorderItemFile objects.
	 * @throws     PropelException
	 */
	public function countWorkorderItemFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWorkorderItemFiles === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

				$count = WorkorderItemFilePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

				if (!isset($this->lastWorkorderItemFileCriteria) || !$this->lastWorkorderItemFileCriteria->equals($criteria)) {
					$count = WorkorderItemFilePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collWorkorderItemFiles);
				}
			} else {
				$count = count($this->collWorkorderItemFiles);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a WorkorderItemFile object to this object
	 * through the WorkorderItemFile foreign key attribute.
	 *
	 * @param      WorkorderItemFile $l WorkorderItemFile
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWorkorderItemFile(WorkorderItemFile $l)
	{
		if ($this->collWorkorderItemFiles === null) {
			$this->initWorkorderItemFiles();
		}
		if (!in_array($l, $this->collWorkorderItemFiles, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWorkorderItemFiles, $l);
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related WorkorderItemFiles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getWorkorderItemFilesJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWorkorderItemFiles === null) {
			if ($this->isNew()) {
				$this->collWorkorderItemFiles = array();
			} else {

				$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

				$this->collWorkorderItemFiles = WorkorderItemFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastWorkorderItemFileCriteria) || !$this->lastWorkorderItemFileCriteria->equals($criteria)) {
				$this->collWorkorderItemFiles = WorkorderItemFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		}
		$this->lastWorkorderItemFileCriteria = $criteria;

		return $this->collWorkorderItemFiles;
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
	 * Otherwise if this WorkorderItem has previously been saved, it will retrieve
	 * related Timelogs from storage. If this WorkorderItem is new, it will return
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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
			   $this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				TimelogPeer::addSelectColumns($criteria);
				$this->collTimelogs = TimelogPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

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
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
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

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				$count = TimelogPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

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
			$l->setWorkorderItem($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getTimelogsJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

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
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getTimelogsJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

			if (!isset($this->lastTimelogCriteria) || !$this->lastTimelogCriteria->equals($criteria)) {
				$this->collTimelogs = TimelogPeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		}
		$this->lastTimelogCriteria = $criteria;

		return $this->collTimelogs;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getTimelogsJoinLabourType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinLabourType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

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
	 * Otherwise if this WorkorderItem is new, it will return
	 * an empty collection; or if this WorkorderItem has previously
	 * been saved, it will retrieve related Timelogs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WorkorderItem.
	 */
	public function getTimelogsJoinNonbillType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collTimelogs === null) {
			if ($this->isNew()) {
				$this->collTimelogs = array();
			} else {

				$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

				$this->collTimelogs = TimelogPeer::doSelectJoinNonbillType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(TimelogPeer::WORKORDER_ITEM_ID, $this->id);

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
			if ($this->collPartInstances) {
				foreach ((array) $this->collPartInstances as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderItemBillables) {
				foreach ((array) $this->collWorkorderItemBillables as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderExpenses) {
				foreach ((array) $this->collWorkorderExpenses as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderItemPhotos) {
				foreach ((array) $this->collWorkorderItemPhotos as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWorkorderItemFiles) {
				foreach ((array) $this->collWorkorderItemFiles as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collTimelogs) {
				foreach ((array) $this->collTimelogs as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPartInstances = null;
		$this->collWorkorderItemBillables = null;
		$this->collWorkorderExpenses = null;
		$this->collWorkorderItemPhotos = null;
		$this->collWorkorderItemFiles = null;
		$this->collTimelogs = null;
			$this->aWorkorder = null;
			$this->aEmployee = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseWorkorderItem:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseWorkorderItem::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseWorkorderItem
