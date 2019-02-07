<?php

/**
 * Base class that represents a row from the 'part_instance' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartInstance extends BaseObject  implements Persistent {


  const PEER = 'PartInstancePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PartInstancePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the part_variant_id field.
	 * @var        int
	 */
	protected $part_variant_id;

	/**
	 * The value for the custom_name field.
	 * @var        string
	 */
	protected $custom_name;

	/**
	 * The value for the custom_origin field.
	 * @var        string
	 */
	protected $custom_origin;

	/**
	 * The value for the quantity field.
	 * @var        string
	 */
	protected $quantity;

	/**
	 * The value for the unit_price field.
	 * @var        string
	 */
	protected $unit_price;

	/**
	 * The value for the unit_cost field.
	 * @var        string
	 */
	protected $unit_cost;

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
	 * The value for the supplier_order_item_id field.
	 * @var        int
	 */
	protected $supplier_order_item_id;

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
	 * The value for the added_by field.
	 * @var        int
	 */
	protected $added_by;

	/**
	 * The value for the estimate field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $estimate;

	/**
	 * The value for the allocated field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $allocated;

	/**
	 * The value for the delivered field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $delivered;

	/**
	 * The value for the serial_number field.
	 * @var        string
	 */
	protected $serial_number;

	/**
	 * The value for the date_used field.
	 * @var        string
	 */
	protected $date_used;

	/**
	 * The value for the is_inventory_adjustment field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_inventory_adjustment;

	/**
	 * The value for the internal_notes field.
	 * @var        string
	 */
	protected $internal_notes;

	/**
	 * @var        PartVariant
	 */
	protected $aPartVariant;

	/**
	 * @var        SupplierOrderItem
	 */
	protected $aSupplierOrderItem;

	/**
	 * @var        WorkorderItem
	 */
	protected $aWorkorderItem;

	/**
	 * @var        Invoice
	 */
	protected $aInvoice;

	/**
	 * @var        Employee
	 */
	protected $aEmployee;

	/**
	 * @var        array CustomerReturnItem[] Collection to store aggregation of CustomerReturnItem objects.
	 */
	protected $collCustomerReturnItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerReturnItems.
	 */
	private $lastCustomerReturnItemCriteria = null;

	/**
	 * @var        array CustomerOrderItem[] Collection to store aggregation of CustomerOrderItem objects.
	 */
	protected $collCustomerOrderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collCustomerOrderItems.
	 */
	private $lastCustomerOrderItemCriteria = null;

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
	 * Initializes internal state of BasePartInstance object.
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
		$this->enviro_levy = '0';
		$this->battery_levy = '0';
		$this->estimate = false;
		$this->allocated = false;
		$this->delivered = false;
		$this->is_inventory_adjustment = false;
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
	 * Get the [part_variant_id] column value.
	 * 
	 * @return     int
	 */
	public function getPartVariantId()
	{
		return $this->part_variant_id;
	}

	/**
	 * Get the [custom_name] column value.
	 * 
	 * @return     string
	 */
	public function getCustomName()
	{
		return $this->custom_name;
	}

	/**
	 * Get the [custom_origin] column value.
	 * 
	 * @return     string
	 */
	public function getCustomOrigin()
	{
		return $this->custom_origin;
	}

	/**
	 * Get the [quantity] column value.
	 * 
	 * @return     string
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * Get the [unit_price] column value.
	 * 
	 * @return     string
	 */
	public function getUnitPrice()
	{
		return $this->unit_price;
	}

	/**
	 * Get the [unit_cost] column value.
	 * 
	 * @return     string
	 */
	public function getUnitCost()
	{
		return $this->unit_cost;
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
	 * Get the [supplier_order_item_id] column value.
	 * 
	 * @return     int
	 */
	public function getSupplierOrderItemId()
	{
		return $this->supplier_order_item_id;
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
	 * Get the [added_by] column value.
	 * 
	 * @return     int
	 */
	public function getAddedBy()
	{
		return $this->added_by;
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
	 * Get the [allocated] column value.
	 * 
	 * @return     boolean
	 */
	public function getAllocated()
	{
		return $this->allocated;
	}

	/**
	 * Get the [delivered] column value.
	 * 
	 * @return     boolean
	 */
	public function getDelivered()
	{
		return $this->delivered;
	}

	/**
	 * Get the [serial_number] column value.
	 * 
	 * @return     string
	 */
	public function getSerialNumber()
	{
		return $this->serial_number;
	}

	/**
	 * Get the [optionally formatted] temporal [date_used] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDateUsed($format = 'Y-m-d H:i:s')
	{
		if ($this->date_used === null) {
			return null;
		}


		if ($this->date_used === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->date_used);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date_used, true), $x);
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
	 * Get the [is_inventory_adjustment] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsInventoryAdjustment()
	{
		return $this->is_inventory_adjustment;
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
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PartInstancePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [part_variant_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setPartVariantId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_variant_id !== $v) {
			$this->part_variant_id = $v;
			$this->modifiedColumns[] = PartInstancePeer::PART_VARIANT_ID;
		}

		if ($this->aPartVariant !== null && $this->aPartVariant->getId() !== $v) {
			$this->aPartVariant = null;
		}

		return $this;
	} // setPartVariantId()

	/**
	 * Set the value of [custom_name] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setCustomName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->custom_name !== $v) {
			$this->custom_name = $v;
			$this->modifiedColumns[] = PartInstancePeer::CUSTOM_NAME;
		}

		return $this;
	} // setCustomName()

	/**
	 * Set the value of [custom_origin] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setCustomOrigin($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->custom_origin !== $v) {
			$this->custom_origin = $v;
			$this->modifiedColumns[] = PartInstancePeer::CUSTOM_ORIGIN;
		}

		return $this;
	} // setCustomOrigin()

	/**
	 * Set the value of [quantity] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setQuantity($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->quantity !== $v) {
			$this->quantity = $v;
			$this->modifiedColumns[] = PartInstancePeer::QUANTITY;
		}

		return $this;
	} // setQuantity()

	/**
	 * Set the value of [unit_price] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setUnitPrice($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unit_price !== $v) {
			$this->unit_price = $v;
			$this->modifiedColumns[] = PartInstancePeer::UNIT_PRICE;
		}

		return $this;
	} // setUnitPrice()

	/**
	 * Set the value of [unit_cost] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setUnitCost($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unit_cost !== $v) {
			$this->unit_cost = $v;
			$this->modifiedColumns[] = PartInstancePeer::UNIT_COST;
		}

		return $this;
	} // setUnitCost()

	/**
	 * Set the value of [taxable_hst] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setTaxableHst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_hst !== $v || $v === '0') {
			$this->taxable_hst = $v;
			$this->modifiedColumns[] = PartInstancePeer::TAXABLE_HST;
		}

		return $this;
	} // setTaxableHst()

	/**
	 * Set the value of [taxable_gst] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setTaxableGst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_gst !== $v || $v === '0') {
			$this->taxable_gst = $v;
			$this->modifiedColumns[] = PartInstancePeer::TAXABLE_GST;
		}

		return $this;
	} // setTaxableGst()

	/**
	 * Set the value of [taxable_pst] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setTaxablePst($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->taxable_pst !== $v || $v === '0') {
			$this->taxable_pst = $v;
			$this->modifiedColumns[] = PartInstancePeer::TAXABLE_PST;
		}

		return $this;
	} // setTaxablePst()

	/**
	 * Set the value of [enviro_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setEnviroLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->enviro_levy !== $v || $v === '0') {
			$this->enviro_levy = $v;
			$this->modifiedColumns[] = PartInstancePeer::ENVIRO_LEVY;
		}

		return $this;
	} // setEnviroLevy()

	/**
	 * Set the value of [battery_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setBatteryLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->battery_levy !== $v || $v === '0') {
			$this->battery_levy = $v;
			$this->modifiedColumns[] = PartInstancePeer::BATTERY_LEVY;
		}

		return $this;
	} // setBatteryLevy()

	/**
	 * Set the value of [supplier_order_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setSupplierOrderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->supplier_order_item_id !== $v) {
			$this->supplier_order_item_id = $v;
			$this->modifiedColumns[] = PartInstancePeer::SUPPLIER_ORDER_ITEM_ID;
		}

		if ($this->aSupplierOrderItem !== null && $this->aSupplierOrderItem->getId() !== $v) {
			$this->aSupplierOrderItem = null;
		}

		return $this;
	} // setSupplierOrderItemId()

	/**
	 * Set the value of [workorder_item_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setWorkorderItemId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_item_id !== $v) {
			$this->workorder_item_id = $v;
			$this->modifiedColumns[] = PartInstancePeer::WORKORDER_ITEM_ID;
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
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setWorkorderInvoiceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->workorder_invoice_id !== $v) {
			$this->workorder_invoice_id = $v;
			$this->modifiedColumns[] = PartInstancePeer::WORKORDER_INVOICE_ID;
		}

		if ($this->aInvoice !== null && $this->aInvoice->getId() !== $v) {
			$this->aInvoice = null;
		}

		return $this;
	} // setWorkorderInvoiceId()

	/**
	 * Set the value of [added_by] column.
	 * 
	 * @param      int $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setAddedBy($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->added_by !== $v) {
			$this->added_by = $v;
			$this->modifiedColumns[] = PartInstancePeer::ADDED_BY;
		}

		if ($this->aEmployee !== null && $this->aEmployee->getId() !== $v) {
			$this->aEmployee = null;
		}

		return $this;
	} // setAddedBy()

	/**
	 * Set the value of [estimate] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setEstimate($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->estimate !== $v || $v === false) {
			$this->estimate = $v;
			$this->modifiedColumns[] = PartInstancePeer::ESTIMATE;
		}

		return $this;
	} // setEstimate()

	/**
	 * Set the value of [allocated] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setAllocated($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->allocated !== $v || $v === false) {
			$this->allocated = $v;
			$this->modifiedColumns[] = PartInstancePeer::ALLOCATED;
		}

		return $this;
	} // setAllocated()

	/**
	 * Set the value of [delivered] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setDelivered($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->delivered !== $v || $v === false) {
			$this->delivered = $v;
			$this->modifiedColumns[] = PartInstancePeer::DELIVERED;
		}

		return $this;
	} // setDelivered()

	/**
	 * Set the value of [serial_number] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setSerialNumber($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->serial_number !== $v) {
			$this->serial_number = $v;
			$this->modifiedColumns[] = PartInstancePeer::SERIAL_NUMBER;
		}

		return $this;
	} // setSerialNumber()

	/**
	 * Sets the value of [date_used] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setDateUsed($v)
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

		if ( $this->date_used !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->date_used !== null && $tmpDt = new DateTime($this->date_used)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->date_used = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = PartInstancePeer::DATE_USED;
			}
		} // if either are not null

		return $this;
	} // setDateUsed()

	/**
	 * Set the value of [is_inventory_adjustment] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setIsInventoryAdjustment($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_inventory_adjustment !== $v || $v === false) {
			$this->is_inventory_adjustment = $v;
			$this->modifiedColumns[] = PartInstancePeer::IS_INVENTORY_ADJUSTMENT;
		}

		return $this;
	} // setIsInventoryAdjustment()

	/**
	 * Set the value of [internal_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     PartInstance The current object (for fluent API support)
	 */
	public function setInternalNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->internal_notes !== $v) {
			$this->internal_notes = $v;
			$this->modifiedColumns[] = PartInstancePeer::INTERNAL_NOTES;
		}

		return $this;
	} // setInternalNotes()

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
			if (array_diff($this->modifiedColumns, array(PartInstancePeer::TAXABLE_HST,PartInstancePeer::TAXABLE_GST,PartInstancePeer::TAXABLE_PST,PartInstancePeer::ENVIRO_LEVY,PartInstancePeer::BATTERY_LEVY,PartInstancePeer::ESTIMATE,PartInstancePeer::ALLOCATED,PartInstancePeer::DELIVERED,PartInstancePeer::IS_INVENTORY_ADJUSTMENT))) {
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

			if ($this->enviro_levy !== '0') {
				return false;
			}

			if ($this->battery_levy !== '0') {
				return false;
			}

			if ($this->estimate !== false) {
				return false;
			}

			if ($this->allocated !== false) {
				return false;
			}

			if ($this->delivered !== false) {
				return false;
			}

			if ($this->is_inventory_adjustment !== false) {
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
			$this->part_variant_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->custom_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->custom_origin = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->quantity = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->unit_price = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->unit_cost = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->taxable_hst = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->taxable_gst = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->taxable_pst = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->enviro_levy = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->battery_levy = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->supplier_order_item_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
			$this->workorder_item_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
			$this->workorder_invoice_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
			$this->added_by = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
			$this->estimate = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
			$this->allocated = ($row[$startcol + 17] !== null) ? (boolean) $row[$startcol + 17] : null;
			$this->delivered = ($row[$startcol + 18] !== null) ? (boolean) $row[$startcol + 18] : null;
			$this->serial_number = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
			$this->date_used = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
			$this->is_inventory_adjustment = ($row[$startcol + 21] !== null) ? (boolean) $row[$startcol + 21] : null;
			$this->internal_notes = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 23; // 23 = PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating PartInstance object", $e);
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

		if ($this->aPartVariant !== null && $this->part_variant_id !== $this->aPartVariant->getId()) {
			$this->aPartVariant = null;
		}
		if ($this->aSupplierOrderItem !== null && $this->supplier_order_item_id !== $this->aSupplierOrderItem->getId()) {
			$this->aSupplierOrderItem = null;
		}
		if ($this->aWorkorderItem !== null && $this->workorder_item_id !== $this->aWorkorderItem->getId()) {
			$this->aWorkorderItem = null;
		}
		if ($this->aInvoice !== null && $this->workorder_invoice_id !== $this->aInvoice->getId()) {
			$this->aInvoice = null;
		}
		if ($this->aEmployee !== null && $this->added_by !== $this->aEmployee->getId()) {
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
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PartInstancePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aPartVariant = null;
			$this->aSupplierOrderItem = null;
			$this->aWorkorderItem = null;
			$this->aInvoice = null;
			$this->aEmployee = null;
			$this->collCustomerReturnItems = null;
			$this->lastCustomerReturnItemCriteria = null;

			$this->collCustomerOrderItems = null;
			$this->lastCustomerOrderItemCriteria = null;

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

    foreach (sfMixer::getCallables('BasePartInstance:delete:pre') as $callable)
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
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PartInstancePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePartInstance:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePartInstance:save:pre') as $callable)
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
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePartInstance:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PartInstancePeer::addInstanceToPool($this);
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

			if ($this->aPartVariant !== null) {
				if ($this->aPartVariant->isModified() || $this->aPartVariant->isNew()) {
					$affectedRows += $this->aPartVariant->save($con);
				}
				$this->setPartVariant($this->aPartVariant);
			}

			if ($this->aSupplierOrderItem !== null) {
				if ($this->aSupplierOrderItem->isModified() || $this->aSupplierOrderItem->isNew()) {
					$affectedRows += $this->aSupplierOrderItem->save($con);
				}
				$this->setSupplierOrderItem($this->aSupplierOrderItem);
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

			if ($this->aEmployee !== null) {
				if ($this->aEmployee->isModified() || $this->aEmployee->isNew()) {
					$affectedRows += $this->aEmployee->save($con);
				}
				$this->setEmployee($this->aEmployee);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PartInstancePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PartInstancePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PartInstancePeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collCustomerReturnItems !== null) {
				foreach ($this->collCustomerReturnItems as $referrerFK) {
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

			if ($this->aPartVariant !== null) {
				if (!$this->aPartVariant->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPartVariant->getValidationFailures());
				}
			}

			if ($this->aSupplierOrderItem !== null) {
				if (!$this->aSupplierOrderItem->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSupplierOrderItem->getValidationFailures());
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

			if ($this->aEmployee !== null) {
				if (!$this->aEmployee->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEmployee->getValidationFailures());
				}
			}


			if (($retval = PartInstancePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collCustomerReturnItems !== null) {
					foreach ($this->collCustomerReturnItems as $referrerFK) {
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
		$pos = PartInstancePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPartVariantId();
				break;
			case 2:
				return $this->getCustomName();
				break;
			case 3:
				return $this->getCustomOrigin();
				break;
			case 4:
				return $this->getQuantity();
				break;
			case 5:
				return $this->getUnitPrice();
				break;
			case 6:
				return $this->getUnitCost();
				break;
			case 7:
				return $this->getTaxableHst();
				break;
			case 8:
				return $this->getTaxableGst();
				break;
			case 9:
				return $this->getTaxablePst();
				break;
			case 10:
				return $this->getEnviroLevy();
				break;
			case 11:
				return $this->getBatteryLevy();
				break;
			case 12:
				return $this->getSupplierOrderItemId();
				break;
			case 13:
				return $this->getWorkorderItemId();
				break;
			case 14:
				return $this->getWorkorderInvoiceId();
				break;
			case 15:
				return $this->getAddedBy();
				break;
			case 16:
				return $this->getEstimate();
				break;
			case 17:
				return $this->getAllocated();
				break;
			case 18:
				return $this->getDelivered();
				break;
			case 19:
				return $this->getSerialNumber();
				break;
			case 20:
				return $this->getDateUsed();
				break;
			case 21:
				return $this->getIsInventoryAdjustment();
				break;
			case 22:
				return $this->getInternalNotes();
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
		$keys = PartInstancePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPartVariantId(),
			$keys[2] => $this->getCustomName(),
			$keys[3] => $this->getCustomOrigin(),
			$keys[4] => $this->getQuantity(),
			$keys[5] => $this->getUnitPrice(),
			$keys[6] => $this->getUnitCost(),
			$keys[7] => $this->getTaxableHst(),
			$keys[8] => $this->getTaxableGst(),
			$keys[9] => $this->getTaxablePst(),
			$keys[10] => $this->getEnviroLevy(),
			$keys[11] => $this->getBatteryLevy(),
			$keys[12] => $this->getSupplierOrderItemId(),
			$keys[13] => $this->getWorkorderItemId(),
			$keys[14] => $this->getWorkorderInvoiceId(),
			$keys[15] => $this->getAddedBy(),
			$keys[16] => $this->getEstimate(),
			$keys[17] => $this->getAllocated(),
			$keys[18] => $this->getDelivered(),
			$keys[19] => $this->getSerialNumber(),
			$keys[20] => $this->getDateUsed(),
			$keys[21] => $this->getIsInventoryAdjustment(),
			$keys[22] => $this->getInternalNotes(),
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
		$pos = PartInstancePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPartVariantId($value);
				break;
			case 2:
				$this->setCustomName($value);
				break;
			case 3:
				$this->setCustomOrigin($value);
				break;
			case 4:
				$this->setQuantity($value);
				break;
			case 5:
				$this->setUnitPrice($value);
				break;
			case 6:
				$this->setUnitCost($value);
				break;
			case 7:
				$this->setTaxableHst($value);
				break;
			case 8:
				$this->setTaxableGst($value);
				break;
			case 9:
				$this->setTaxablePst($value);
				break;
			case 10:
				$this->setEnviroLevy($value);
				break;
			case 11:
				$this->setBatteryLevy($value);
				break;
			case 12:
				$this->setSupplierOrderItemId($value);
				break;
			case 13:
				$this->setWorkorderItemId($value);
				break;
			case 14:
				$this->setWorkorderInvoiceId($value);
				break;
			case 15:
				$this->setAddedBy($value);
				break;
			case 16:
				$this->setEstimate($value);
				break;
			case 17:
				$this->setAllocated($value);
				break;
			case 18:
				$this->setDelivered($value);
				break;
			case 19:
				$this->setSerialNumber($value);
				break;
			case 20:
				$this->setDateUsed($value);
				break;
			case 21:
				$this->setIsInventoryAdjustment($value);
				break;
			case 22:
				$this->setInternalNotes($value);
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
		$keys = PartInstancePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPartVariantId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCustomName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCustomOrigin($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setQuantity($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUnitPrice($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUnitCost($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setTaxableHst($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setTaxableGst($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setTaxablePst($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setEnviroLevy($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setBatteryLevy($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setSupplierOrderItemId($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setWorkorderItemId($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setWorkorderInvoiceId($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setAddedBy($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setEstimate($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setAllocated($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setDelivered($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setSerialNumber($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setDateUsed($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setIsInventoryAdjustment($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setInternalNotes($arr[$keys[22]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);

		if ($this->isColumnModified(PartInstancePeer::ID)) $criteria->add(PartInstancePeer::ID, $this->id);
		if ($this->isColumnModified(PartInstancePeer::PART_VARIANT_ID)) $criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->part_variant_id);
		if ($this->isColumnModified(PartInstancePeer::CUSTOM_NAME)) $criteria->add(PartInstancePeer::CUSTOM_NAME, $this->custom_name);
		if ($this->isColumnModified(PartInstancePeer::CUSTOM_ORIGIN)) $criteria->add(PartInstancePeer::CUSTOM_ORIGIN, $this->custom_origin);
		if ($this->isColumnModified(PartInstancePeer::QUANTITY)) $criteria->add(PartInstancePeer::QUANTITY, $this->quantity);
		if ($this->isColumnModified(PartInstancePeer::UNIT_PRICE)) $criteria->add(PartInstancePeer::UNIT_PRICE, $this->unit_price);
		if ($this->isColumnModified(PartInstancePeer::UNIT_COST)) $criteria->add(PartInstancePeer::UNIT_COST, $this->unit_cost);
		if ($this->isColumnModified(PartInstancePeer::TAXABLE_HST)) $criteria->add(PartInstancePeer::TAXABLE_HST, $this->taxable_hst);
		if ($this->isColumnModified(PartInstancePeer::TAXABLE_GST)) $criteria->add(PartInstancePeer::TAXABLE_GST, $this->taxable_gst);
		if ($this->isColumnModified(PartInstancePeer::TAXABLE_PST)) $criteria->add(PartInstancePeer::TAXABLE_PST, $this->taxable_pst);
		if ($this->isColumnModified(PartInstancePeer::ENVIRO_LEVY)) $criteria->add(PartInstancePeer::ENVIRO_LEVY, $this->enviro_levy);
		if ($this->isColumnModified(PartInstancePeer::BATTERY_LEVY)) $criteria->add(PartInstancePeer::BATTERY_LEVY, $this->battery_levy);
		if ($this->isColumnModified(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID)) $criteria->add(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID, $this->supplier_order_item_id);
		if ($this->isColumnModified(PartInstancePeer::WORKORDER_ITEM_ID)) $criteria->add(PartInstancePeer::WORKORDER_ITEM_ID, $this->workorder_item_id);
		if ($this->isColumnModified(PartInstancePeer::WORKORDER_INVOICE_ID)) $criteria->add(PartInstancePeer::WORKORDER_INVOICE_ID, $this->workorder_invoice_id);
		if ($this->isColumnModified(PartInstancePeer::ADDED_BY)) $criteria->add(PartInstancePeer::ADDED_BY, $this->added_by);
		if ($this->isColumnModified(PartInstancePeer::ESTIMATE)) $criteria->add(PartInstancePeer::ESTIMATE, $this->estimate);
		if ($this->isColumnModified(PartInstancePeer::ALLOCATED)) $criteria->add(PartInstancePeer::ALLOCATED, $this->allocated);
		if ($this->isColumnModified(PartInstancePeer::DELIVERED)) $criteria->add(PartInstancePeer::DELIVERED, $this->delivered);
		if ($this->isColumnModified(PartInstancePeer::SERIAL_NUMBER)) $criteria->add(PartInstancePeer::SERIAL_NUMBER, $this->serial_number);
		if ($this->isColumnModified(PartInstancePeer::DATE_USED)) $criteria->add(PartInstancePeer::DATE_USED, $this->date_used);
		if ($this->isColumnModified(PartInstancePeer::IS_INVENTORY_ADJUSTMENT)) $criteria->add(PartInstancePeer::IS_INVENTORY_ADJUSTMENT, $this->is_inventory_adjustment);
		if ($this->isColumnModified(PartInstancePeer::INTERNAL_NOTES)) $criteria->add(PartInstancePeer::INTERNAL_NOTES, $this->internal_notes);

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
		$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);

		$criteria->add(PartInstancePeer::ID, $this->id);

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
	 * @param      object $copyObj An object of PartInstance (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPartVariantId($this->part_variant_id);

		$copyObj->setCustomName($this->custom_name);

		$copyObj->setCustomOrigin($this->custom_origin);

		$copyObj->setQuantity($this->quantity);

		$copyObj->setUnitPrice($this->unit_price);

		$copyObj->setUnitCost($this->unit_cost);

		$copyObj->setTaxableHst($this->taxable_hst);

		$copyObj->setTaxableGst($this->taxable_gst);

		$copyObj->setTaxablePst($this->taxable_pst);

		$copyObj->setEnviroLevy($this->enviro_levy);

		$copyObj->setBatteryLevy($this->battery_levy);

		$copyObj->setSupplierOrderItemId($this->supplier_order_item_id);

		$copyObj->setWorkorderItemId($this->workorder_item_id);

		$copyObj->setWorkorderInvoiceId($this->workorder_invoice_id);

		$copyObj->setAddedBy($this->added_by);

		$copyObj->setEstimate($this->estimate);

		$copyObj->setAllocated($this->allocated);

		$copyObj->setDelivered($this->delivered);

		$copyObj->setSerialNumber($this->serial_number);

		$copyObj->setDateUsed($this->date_used);

		$copyObj->setIsInventoryAdjustment($this->is_inventory_adjustment);

		$copyObj->setInternalNotes($this->internal_notes);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getCustomerReturnItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerReturnItem($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCustomerOrderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCustomerOrderItem($relObj->copy($deepCopy));
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
	 * @return     PartInstance Clone of current object.
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
	 * @return     PartInstancePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PartInstancePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a PartVariant object.
	 *
	 * @param      PartVariant $v
	 * @return     PartInstance The current object (for fluent API support)
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
			$v->addPartInstance($this);
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
			   $this->aPartVariant->addPartInstances($this);
			 */
		}
		return $this->aPartVariant;
	}

	/**
	 * Declares an association between this object and a SupplierOrderItem object.
	 *
	 * @param      SupplierOrderItem $v
	 * @return     PartInstance The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setSupplierOrderItem(SupplierOrderItem $v = null)
	{
		if ($v === null) {
			$this->setSupplierOrderItemId(NULL);
		} else {
			$this->setSupplierOrderItemId($v->getId());
		}

		$this->aSupplierOrderItem = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the SupplierOrderItem object, it will not be re-added.
		if ($v !== null) {
			$v->addPartInstance($this);
		}

		return $this;
	}


	/**
	 * Get the associated SupplierOrderItem object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     SupplierOrderItem The associated SupplierOrderItem object.
	 * @throws     PropelException
	 */
	public function getSupplierOrderItem(PropelPDO $con = null)
	{
		if ($this->aSupplierOrderItem === null && ($this->supplier_order_item_id !== null)) {
			$c = new Criteria(SupplierOrderItemPeer::DATABASE_NAME);
			$c->add(SupplierOrderItemPeer::ID, $this->supplier_order_item_id);
			$this->aSupplierOrderItem = SupplierOrderItemPeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aSupplierOrderItem->addPartInstances($this);
			 */
		}
		return $this->aSupplierOrderItem;
	}

	/**
	 * Declares an association between this object and a WorkorderItem object.
	 *
	 * @param      WorkorderItem $v
	 * @return     PartInstance The current object (for fluent API support)
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
			$v->addPartInstance($this);
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
			   $this->aWorkorderItem->addPartInstances($this);
			 */
		}
		return $this->aWorkorderItem;
	}

	/**
	 * Declares an association between this object and a Invoice object.
	 *
	 * @param      Invoice $v
	 * @return     PartInstance The current object (for fluent API support)
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
			$v->addPartInstance($this);
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
			   $this->aInvoice->addPartInstances($this);
			 */
		}
		return $this->aInvoice;
	}

	/**
	 * Declares an association between this object and a Employee object.
	 *
	 * @param      Employee $v
	 * @return     PartInstance The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEmployee(Employee $v = null)
	{
		if ($v === null) {
			$this->setAddedBy(NULL);
		} else {
			$this->setAddedBy($v->getId());
		}

		$this->aEmployee = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Employee object, it will not be re-added.
		if ($v !== null) {
			$v->addPartInstance($this);
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
		if ($this->aEmployee === null && ($this->added_by !== null)) {
			$c = new Criteria(EmployeePeer::DATABASE_NAME);
			$c->add(EmployeePeer::ID, $this->added_by);
			$this->aEmployee = EmployeePeer::doSelectOne($c, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEmployee->addPartInstances($this);
			 */
		}
		return $this->aEmployee;
	}

	/**
	 * Clears out the collCustomerReturnItems collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCustomerReturnItems()
	 */
	public function clearCustomerReturnItems()
	{
		$this->collCustomerReturnItems = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCustomerReturnItems collection (array).
	 *
	 * By default this just sets the collCustomerReturnItems collection to an empty array (like clearcollCustomerReturnItems());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCustomerReturnItems()
	{
		$this->collCustomerReturnItems = array();
	}

	/**
	 * Gets an array of CustomerReturnItem objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartInstance has previously been saved, it will retrieve
	 * related CustomerReturnItems from storage. If this PartInstance is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array CustomerReturnItem[]
	 * @throws     PropelException
	 */
	public function getCustomerReturnItems($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturnItems === null) {
			if ($this->isNew()) {
			   $this->collCustomerReturnItems = array();
			} else {

				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				CustomerReturnItemPeer::addSelectColumns($criteria);
				$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				CustomerReturnItemPeer::addSelectColumns($criteria);
				if (!isset($this->lastCustomerReturnItemCriteria) || !$this->lastCustomerReturnItemCriteria->equals($criteria)) {
					$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastCustomerReturnItemCriteria = $criteria;
		return $this->collCustomerReturnItems;
	}

	/**
	 * Returns the number of related CustomerReturnItem objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CustomerReturnItem objects.
	 * @throws     PropelException
	 */
	public function countCustomerReturnItems(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collCustomerReturnItems === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				$count = CustomerReturnItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				if (!isset($this->lastCustomerReturnItemCriteria) || !$this->lastCustomerReturnItemCriteria->equals($criteria)) {
					$count = CustomerReturnItemPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collCustomerReturnItems);
				}
			} else {
				$count = count($this->collCustomerReturnItems);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a CustomerReturnItem object to this object
	 * through the CustomerReturnItem foreign key attribute.
	 *
	 * @param      CustomerReturnItem $l CustomerReturnItem
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCustomerReturnItem(CustomerReturnItem $l)
	{
		if ($this->collCustomerReturnItems === null) {
			$this->initCustomerReturnItems();
		}
		if (!in_array($l, $this->collCustomerReturnItems, true)) { // only add it if the **same** object is not already associated
			array_push($this->collCustomerReturnItems, $l);
			$l->setPartInstance($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartInstance is new, it will return
	 * an empty collection; or if this PartInstance has previously
	 * been saved, it will retrieve related CustomerReturnItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartInstance.
	 */
	public function getCustomerReturnItemsJoinCustomerReturn($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturnItems === null) {
			if ($this->isNew()) {
				$this->collCustomerReturnItems = array();
			} else {

				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelectJoinCustomerReturn($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

			if (!isset($this->lastCustomerReturnItemCriteria) || !$this->lastCustomerReturnItemCriteria->equals($criteria)) {
				$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelectJoinCustomerReturn($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerReturnItemCriteria = $criteria;

		return $this->collCustomerReturnItems;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartInstance is new, it will return
	 * an empty collection; or if this PartInstance has previously
	 * been saved, it will retrieve related CustomerReturnItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartInstance.
	 */
	public function getCustomerReturnItemsJoinCustomerOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerReturnItems === null) {
			if ($this->isNew()) {
				$this->collCustomerReturnItems = array();
			} else {

				$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

				$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelectJoinCustomerOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerReturnItemPeer::PART_INSTANCE_ID, $this->id);

			if (!isset($this->lastCustomerReturnItemCriteria) || !$this->lastCustomerReturnItemCriteria->equals($criteria)) {
				$this->collCustomerReturnItems = CustomerReturnItemPeer::doSelectJoinCustomerOrderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerReturnItemCriteria = $criteria;

		return $this->collCustomerReturnItems;
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
	 * Otherwise if this PartInstance has previously been saved, it will retrieve
	 * related CustomerOrderItems from storage. If this PartInstance is new, it will return
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
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrderItems === null) {
			if ($this->isNew()) {
			   $this->collCustomerOrderItems = array();
			} else {

				$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

				CustomerOrderItemPeer::addSelectColumns($criteria);
				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

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
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
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

				$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

				$count = CustomerOrderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

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
			$l->setPartInstance($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartInstance is new, it will return
	 * an empty collection; or if this PartInstance has previously
	 * been saved, it will retrieve related CustomerOrderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartInstance.
	 */
	public function getCustomerOrderItemsJoinCustomerOrder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collCustomerOrderItems === null) {
			if ($this->isNew()) {
				$this->collCustomerOrderItems = array();
			} else {

				$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->id);

			if (!isset($this->lastCustomerOrderItemCriteria) || !$this->lastCustomerOrderItemCriteria->equals($criteria)) {
				$this->collCustomerOrderItems = CustomerOrderItemPeer::doSelectJoinCustomerOrder($criteria, $con, $join_behavior);
			}
		}
		$this->lastCustomerOrderItemCriteria = $criteria;

		return $this->collCustomerOrderItems;
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
			if ($this->collCustomerReturnItems) {
				foreach ((array) $this->collCustomerReturnItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCustomerOrderItems) {
				foreach ((array) $this->collCustomerOrderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collCustomerReturnItems = null;
		$this->collCustomerOrderItems = null;
			$this->aPartVariant = null;
			$this->aSupplierOrderItem = null;
			$this->aWorkorderItem = null;
			$this->aInvoice = null;
			$this->aEmployee = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePartInstance:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePartInstance::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePartInstance
