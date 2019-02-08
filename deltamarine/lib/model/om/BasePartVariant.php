<?php

/**
 * Base class that represents a row from the 'part_variant' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartVariant extends BaseObject  implements Persistent {


  const PEER = 'PartVariantPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        PartVariantPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the part_id field.
	 * @var        int
	 */
	protected $part_id;

	/**
	 * The value for the is_default_variant field.
	 * @var        boolean
	 */
	protected $is_default_variant;

	/**
	 * The value for the manufacturer_sku field.
	 * @var        string
	 */
	protected $manufacturer_sku;

	/**
	 * The value for the internal_sku field.
	 * @var        string
	 */
	protected $internal_sku;

	/**
	 * The value for the use_default_units field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $use_default_units;

	/**
	 * The value for the units field.
	 * @var        string
	 */
	protected $units;

	/**
	 * The value for the use_default_costing field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $use_default_costing;

	/**
	 * The value for the cost_calculation_method field.
	 * Note: this column has a database default value of: 'lifo'
	 * @var        string
	 */
	protected $cost_calculation_method;

	/**
	 * The value for the unit_cost field.
	 * @var        string
	 */
	protected $unit_cost;

	/**
	 * The value for the use_default_pricing field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $use_default_pricing;

	/**
	 * The value for the broker_fees field.
	 * @var        string
	 */
	protected $broker_fees;

  /**
	 * The value for the shipping_fees field.
	 * @var        string
	 */
	protected $shipping_fees;

  /**
	 * The value for the unit_price field.
	 * @var        string
	 */
	protected $unit_price;

	/**
	 * The value for the markup_amount field.
	 * @var        string
	 */
	protected $markup_amount;

	/**
	 * The value for the markup_percent field.
	 * @var        int
	 */
	protected $markup_percent;

	/**
	 * The value for the taxable_hst field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $taxable_hst;

	/**
	 * The value for the taxable_gst field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $taxable_gst;

	/**
	 * The value for the taxable_pst field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $taxable_pst;

	/**
	 * The value for the enviro_levy field.
	 * @var        string
	 */
	protected $enviro_levy;

	/**
	 * The value for the battery_levy field.
	 * @var        string
	 */
	protected $battery_levy;

	/**
	 * The value for the use_default_dimensions field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $use_default_dimensions;

	/**
	 * The value for the shipping_weight field.
	 * @var        string
	 */
	protected $shipping_weight;

	/**
	 * The value for the shipping_width field.
	 * @var        string
	 */
	protected $shipping_width;

	/**
	 * The value for the shipping_height field.
	 * @var        string
	 */
	protected $shipping_height;

	/**
	 * The value for the shipping_depth field.
	 * @var        string
	 */
	protected $shipping_depth;

	/**
	 * The value for the shipping_volume field.
	 * @var        string
	 */
	protected $shipping_volume;

	/**
	 * The value for the use_default_inventory field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $use_default_inventory;

	/**
	 * The value for the track_inventory field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $track_inventory;

	/**
	 * The value for the minimum_on_hand field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $minimum_on_hand;

	/**
	 * The value for the maximum_on_hand field.
	 * @var        string
	 */
	protected $maximum_on_hand;

	/**
	 * The value for the current_on_hand field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $current_on_hand;

	/**
	 * The value for the current_on_hold field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $current_on_hold;

	/**
	 * The value for the current_on_order field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $current_on_order;

	/**
	 * The value for the location field.
	 * @var        string
	 */
	protected $location;

	/**
	 * The value for the last_inventory_update field.
	 * @var        string
	 */
	protected $last_inventory_update;

	/**
	 * The value for the standard_package_qty field.
	 * @var        string
	 */
	protected $standard_package_qty;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the stocking_notes field.
	 * @var        string
	 */
	protected $stocking_notes;

	/**
	 * @var        Part
	 */
	protected $aPart;

	/**
	 * @var        array PartOptionValue[] Collection to store aggregation of PartOptionValue objects.
	 */
	protected $collPartOptionValues;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartOptionValues.
	 */
	private $lastPartOptionValueCriteria = null;

	/**
	 * @var        array PartSupplier[] Collection to store aggregation of PartSupplier objects.
	 */
	protected $collPartSuppliers;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartSuppliers.
	 */
	private $lastPartSupplierCriteria = null;

	/**
	 * @var        array PartPhoto[] Collection to store aggregation of PartPhoto objects.
	 */
	protected $collPartPhotos;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartPhotos.
	 */
	private $lastPartPhotoCriteria = null;

	/**
	 * @var        array PartFile[] Collection to store aggregation of PartFile objects.
	 */
	protected $collPartFiles;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartFiles.
	 */
	private $lastPartFileCriteria = null;

	/**
	 * @var        array Barcode[] Collection to store aggregation of Barcode objects.
	 */
	protected $collBarcodes;

	/**
	 * @var        Criteria The criteria used to select the current contents of collBarcodes.
	 */
	private $lastBarcodeCriteria = null;

	/**
	 * @var        array Subpart[] Collection to store aggregation of Subpart objects.
	 */
	protected $collSubpartsRelatedByParentId;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSubpartsRelatedByParentId.
	 */
	private $lastSubpartRelatedByParentIdCriteria = null;

	/**
	 * @var        array Subpart[] Collection to store aggregation of Subpart objects.
	 */
	protected $collSubpartsRelatedByChildId;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSubpartsRelatedByChildId.
	 */
	private $lastSubpartRelatedByChildIdCriteria = null;

	/**
	 * @var        array SupplierOrderItem[] Collection to store aggregation of SupplierOrderItem objects.
	 */
	protected $collSupplierOrderItems;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSupplierOrderItems.
	 */
	private $lastSupplierOrderItemCriteria = null;

	/**
	 * @var        array PartLot[] Collection to store aggregation of PartLot objects.
	 */
	protected $collPartLots;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartLots.
	 */
	private $lastPartLotCriteria = null;

	/**
	 * @var        array PartInstance[] Collection to store aggregation of PartInstance objects.
	 */
	protected $collPartInstances;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPartInstances.
	 */
	private $lastPartInstanceCriteria = null;

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
	 * Initializes internal state of BasePartVariant object.
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
		$this->use_default_units = false;
		$this->use_default_costing = false;
		$this->cost_calculation_method = 'lifo';
		$this->use_default_pricing = false;
		$this->taxable_hst = true;
		$this->taxable_gst = true;
		$this->taxable_pst = true;
		$this->use_default_dimensions = false;
		$this->use_default_inventory = false;
		$this->track_inventory = true;
		$this->minimum_on_hand = '0';
		$this->current_on_hand = '0';
		$this->current_on_hold = '0';
		$this->current_on_order = '0';
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
	 * Get the [part_id] column value.
	 * 
	 * @return     int
	 */
	public function getPartId()
	{
		return $this->part_id;
	}

	/**
	 * Get the [is_default_variant] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsDefaultVariant()
	{
		return $this->is_default_variant;
	}

	/**
	 * Get the [manufacturer_sku] column value.
	 * 
	 * @return     string
	 */
	public function getManufacturerSku()
	{
		return $this->manufacturer_sku;
	}

	/**
	 * Get the [internal_sku] column value.
	 * 
	 * @return     string
	 */
	public function getInternalSku()
	{
		return $this->internal_sku;
	}

	/**
	 * Get the [use_default_units] column value.
	 * 
	 * @return     boolean
	 */
	public function getUseDefaultUnits()
	{
		return $this->use_default_units;
	}

	/**
	 * Get the [units] column value.
	 * 
	 * @return     string
	 */
	public function getUnits()
	{
		return $this->units;
	}

	/**
	 * Get the [use_default_costing] column value.
	 * 
	 * @return     boolean
	 */
	public function getUseDefaultCosting()
	{
		return $this->use_default_costing;
	}

	/**
	 * Get the [cost_calculation_method] column value.
	 * 
	 * @return     string
	 */
	public function getCostCalculationMethod()
	{
		return $this->cost_calculation_method;
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
	 * Get the [use_default_pricing] column value.
	 * 
	 * @return     boolean
	 */
	public function getUseDefaultPricing()
	{
		return $this->use_default_pricing;
	}

		/**
	 * Get the [broker_fees] column value.
	 *
	 * @return     string
	 */
	public function getBrokerFees()
	{
		return $this->broker_fees;
	}

  /**
	 * Get the [shipping_fees] column value.
	 *
	 * @return     string
	 */
	public function getShippingFees()
	{
		return $this->shipping_fees;
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
	 * Get the [markup_amount] column value.
	 * 
	 * @return     string
	 */
	public function getMarkupAmount()
	{
		return $this->markup_amount;
	}

	/**
	 * Get the [markup_percent] column value.
	 * 
	 * @return     int
	 */
	public function getMarkupPercent()
	{
		return $this->markup_percent;
	}

	/**
	 * Get the [taxable_hst] column value.
	 * 
	 * @return     boolean
	 */
	public function getTaxableHst()
	{
		return $this->taxable_hst;
	}

	/**
	 * Get the [taxable_gst] column value.
	 * 
	 * @return     boolean
	 */
	public function getTaxableGst()
	{
		return $this->taxable_gst;
	}

	/**
	 * Get the [taxable_pst] column value.
	 * 
	 * @return     boolean
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
	 * Get the [use_default_dimensions] column value.
	 * 
	 * @return     boolean
	 */
	public function getUseDefaultDimensions()
	{
		return $this->use_default_dimensions;
	}

	/**
	 * Get the [shipping_weight] column value.
	 * 
	 * @return     string
	 */
	public function getShippingWeight()
	{
		return $this->shipping_weight;
	}

	/**
	 * Get the [shipping_width] column value.
	 * 
	 * @return     string
	 */
	public function getShippingWidth()
	{
		return $this->shipping_width;
	}

	/**
	 * Get the [shipping_height] column value.
	 * 
	 * @return     string
	 */
	public function getShippingHeight()
	{
		return $this->shipping_height;
	}

	/**
	 * Get the [shipping_depth] column value.
	 * 
	 * @return     string
	 */
	public function getShippingDepth()
	{
		return $this->shipping_depth;
	}

	/**
	 * Get the [shipping_volume] column value.
	 * 
	 * @return     string
	 */
	public function getShippingVolume()
	{
		return $this->shipping_volume;
	}

	/**
	 * Get the [use_default_inventory] column value.
	 * 
	 * @return     boolean
	 */
	public function getUseDefaultInventory()
	{
		return $this->use_default_inventory;
	}

	/**
	 * Get the [track_inventory] column value.
	 * 
	 * @return     boolean
	 */
	public function getTrackInventory()
	{
		return $this->track_inventory;
	}

	/**
	 * Get the [minimum_on_hand] column value.
	 * 
	 * @return     string
	 */
	public function getMinimumOnHand()
	{
		return $this->minimum_on_hand;
	}

	/**
	 * Get the [maximum_on_hand] column value.
	 * 
	 * @return     string
	 */
	public function getMaximumOnHand()
	{
		return $this->maximum_on_hand;
	}

	/**
	 * Get the [current_on_hand] column value.
	 * 
	 * @return     string
	 */
	public function getCurrentOnHand()
	{
		return $this->current_on_hand;
	}

	/**
	 * Get the [current_on_hold] column value.
	 * 
	 * @return     string
	 */
	public function getCurrentOnHold()
	{
		return $this->current_on_hold;
	}

	/**
	 * Get the [current_on_order] column value.
	 * 
	 * @return     string
	 */
	public function getCurrentOnOrder()
	{
		return $this->current_on_order;
	}

	/**
	 * Get the [location] column value.
	 * 
	 * @return     string
	 */
	public function getLocation()
	{
		return $this->location;
	}

	/**
	 * Get the [optionally formatted] temporal [last_inventory_update] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getLastInventoryUpdate($format = 'Y-m-d H:i:s')
	{
		if ($this->last_inventory_update === null) {
			return null;
		}


		if ($this->last_inventory_update === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->last_inventory_update);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->last_inventory_update, true), $x);
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
	 * Get the [standard_package_qty] column value.
	 * 
	 * @return     string
	 */
	public function getStandardPackageQty()
	{
		return $this->standard_package_qty;
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
	 * Get the [stocking_notes] column value.
	 * 
	 * @return     string
	 */
	public function getStockingNotes()
	{
		return $this->stocking_notes;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = PartVariantPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [part_id] column.
	 * 
	 * @param      int $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setPartId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->part_id !== $v) {
			$this->part_id = $v;
			$this->modifiedColumns[] = PartVariantPeer::PART_ID;
		}

		if ($this->aPart !== null && $this->aPart->getId() !== $v) {
			$this->aPart = null;
		}

		return $this;
	} // setPartId()

	/**
	 * Set the value of [is_default_variant] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setIsDefaultVariant($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_default_variant !== $v) {
			$this->is_default_variant = $v;
			$this->modifiedColumns[] = PartVariantPeer::IS_DEFAULT_VARIANT;
		}

		return $this;
	} // setIsDefaultVariant()

	/**
	 * Set the value of [manufacturer_sku] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setManufacturerSku($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->manufacturer_sku !== $v) {
			$this->manufacturer_sku = $v;
			$this->modifiedColumns[] = PartVariantPeer::MANUFACTURER_SKU;
		}

		return $this;
	} // setManufacturerSku()

	/**
	 * Set the value of [internal_sku] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setInternalSku($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->internal_sku !== $v) {
			$this->internal_sku = $v;
			$this->modifiedColumns[] = PartVariantPeer::INTERNAL_SKU;
		}

		return $this;
	} // setInternalSku()

	/**
	 * Set the value of [use_default_units] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUseDefaultUnits($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->use_default_units !== $v || $v === false) {
			$this->use_default_units = $v;
			$this->modifiedColumns[] = PartVariantPeer::USE_DEFAULT_UNITS;
		}

		return $this;
	} // setUseDefaultUnits()

	/**
	 * Set the value of [units] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUnits($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->units !== $v) {
			$this->units = $v;
			$this->modifiedColumns[] = PartVariantPeer::UNITS;
		}

		return $this;
	} // setUnits()

	/**
	 * Set the value of [use_default_costing] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUseDefaultCosting($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->use_default_costing !== $v || $v === false) {
			$this->use_default_costing = $v;
			$this->modifiedColumns[] = PartVariantPeer::USE_DEFAULT_COSTING;
		}

		return $this;
	} // setUseDefaultCosting()

	/**
	 * Set the value of [cost_calculation_method] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setCostCalculationMethod($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->cost_calculation_method !== $v || $v === 'lifo') {
			$this->cost_calculation_method = $v;
			$this->modifiedColumns[] = PartVariantPeer::COST_CALCULATION_METHOD;
		}

		return $this;
	} // setCostCalculationMethod()

	/**
	 * Set the value of [unit_cost] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUnitCost($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unit_cost !== $v) {
			$this->unit_cost = $v;
			$this->modifiedColumns[] = PartVariantPeer::UNIT_COST;
		}

		return $this;
	} // setUnitCost()

	/**
	 * Set the value of [use_default_pricing] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUseDefaultPricing($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->use_default_pricing !== $v || $v === false) {
			$this->use_default_pricing = $v;
			$this->modifiedColumns[] = PartVariantPeer::USE_DEFAULT_PRICING;
		}

		return $this;
	} // setUseDefaultPricing()

	/**
	 * Set the value of [broker_fees] column.
	 *
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setBrokerFees($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->broker_fees !== $v) {
			$this->broker_fees = $v;
			$this->modifiedColumns[] = PartVariantPeer::BROKER_FEES;
		}

		return $this;
	} // setBrokerFees()

  /**
	 * Set the value of [shipping_fees] column.
	 *
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingFees($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_fees !== $v) {
			$this->shipping_fees = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_FEES;
		}

		return $this;
	} // setShippingFees()

  /**
	 * Set the value of [unit_price] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUnitPrice($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unit_price !== $v) {
			$this->unit_price = $v;
			$this->modifiedColumns[] = PartVariantPeer::UNIT_PRICE;
		}

		return $this;
	} // setUnitPrice()

	/**
	 * Set the value of [markup_amount] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setMarkupAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->markup_amount !== $v) {
			$this->markup_amount = $v;
			$this->modifiedColumns[] = PartVariantPeer::MARKUP_AMOUNT;
		}

		return $this;
	} // setMarkupAmount()

	/**
	 * Set the value of [markup_percent] column.
	 * 
	 * @param      int $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setMarkupPercent($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->markup_percent !== $v) {
			$this->markup_percent = $v;
			$this->modifiedColumns[] = PartVariantPeer::MARKUP_PERCENT;
		}

		return $this;
	} // setMarkupPercent()

	/**
	 * Set the value of [taxable_hst] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setTaxableHst($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->taxable_hst !== $v || $v === true) {
			$this->taxable_hst = $v;
			$this->modifiedColumns[] = PartVariantPeer::TAXABLE_HST;
		}

		return $this;
	} // setTaxableHst()

	/**
	 * Set the value of [taxable_gst] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setTaxableGst($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->taxable_gst !== $v || $v === true) {
			$this->taxable_gst = $v;
			$this->modifiedColumns[] = PartVariantPeer::TAXABLE_GST;
		}

		return $this;
	} // setTaxableGst()

	/**
	 * Set the value of [taxable_pst] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setTaxablePst($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->taxable_pst !== $v || $v === true) {
			$this->taxable_pst = $v;
			$this->modifiedColumns[] = PartVariantPeer::TAXABLE_PST;
		}

		return $this;
	} // setTaxablePst()

	/**
	 * Set the value of [enviro_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setEnviroLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->enviro_levy !== $v) {
			$this->enviro_levy = $v;
			$this->modifiedColumns[] = PartVariantPeer::ENVIRO_LEVY;
		}

		return $this;
	} // setEnviroLevy()

	/**
	 * Set the value of [battery_levy] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setBatteryLevy($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->battery_levy !== $v) {
			$this->battery_levy = $v;
			$this->modifiedColumns[] = PartVariantPeer::BATTERY_LEVY;
		}

		return $this;
	} // setBatteryLevy()

	/**
	 * Set the value of [use_default_dimensions] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUseDefaultDimensions($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->use_default_dimensions !== $v || $v === false) {
			$this->use_default_dimensions = $v;
			$this->modifiedColumns[] = PartVariantPeer::USE_DEFAULT_DIMENSIONS;
		}

		return $this;
	} // setUseDefaultDimensions()

	/**
	 * Set the value of [shipping_weight] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingWeight($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_weight !== $v) {
			$this->shipping_weight = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_WEIGHT;
		}

		return $this;
	} // setShippingWeight()

	/**
	 * Set the value of [shipping_width] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingWidth($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_width !== $v) {
			$this->shipping_width = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_WIDTH;
		}

		return $this;
	} // setShippingWidth()

	/**
	 * Set the value of [shipping_height] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingHeight($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_height !== $v) {
			$this->shipping_height = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_HEIGHT;
		}

		return $this;
	} // setShippingHeight()

	/**
	 * Set the value of [shipping_depth] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingDepth($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_depth !== $v) {
			$this->shipping_depth = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_DEPTH;
		}

		return $this;
	} // setShippingDepth()

	/**
	 * Set the value of [shipping_volume] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setShippingVolume($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->shipping_volume !== $v) {
			$this->shipping_volume = $v;
			$this->modifiedColumns[] = PartVariantPeer::SHIPPING_VOLUME;
		}

		return $this;
	} // setShippingVolume()

	/**
	 * Set the value of [use_default_inventory] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setUseDefaultInventory($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->use_default_inventory !== $v || $v === false) {
			$this->use_default_inventory = $v;
			$this->modifiedColumns[] = PartVariantPeer::USE_DEFAULT_INVENTORY;
		}

		return $this;
	} // setUseDefaultInventory()

	/**
	 * Set the value of [track_inventory] column.
	 * 
	 * @param      boolean $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setTrackInventory($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->track_inventory !== $v || $v === true) {
			$this->track_inventory = $v;
			$this->modifiedColumns[] = PartVariantPeer::TRACK_INVENTORY;
		}

		return $this;
	} // setTrackInventory()

	/**
	 * Set the value of [minimum_on_hand] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setMinimumOnHand($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->minimum_on_hand !== $v || $v === '0') {
			$this->minimum_on_hand = $v;
			$this->modifiedColumns[] = PartVariantPeer::MINIMUM_ON_HAND;
		}

		return $this;
	} // setMinimumOnHand()

	/**
	 * Set the value of [maximum_on_hand] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setMaximumOnHand($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->maximum_on_hand !== $v) {
			$this->maximum_on_hand = $v;
			$this->modifiedColumns[] = PartVariantPeer::MAXIMUM_ON_HAND;
		}

		return $this;
	} // setMaximumOnHand()

	/**
	 * Set the value of [current_on_hand] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setCurrentOnHand($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->current_on_hand !== $v || $v === '0') {
			$this->current_on_hand = $v;
			$this->modifiedColumns[] = PartVariantPeer::CURRENT_ON_HAND;
		}

		return $this;
	} // setCurrentOnHand()

	/**
	 * Set the value of [current_on_hold] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setCurrentOnHold($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->current_on_hold !== $v || $v === '0') {
			$this->current_on_hold = $v;
			$this->modifiedColumns[] = PartVariantPeer::CURRENT_ON_HOLD;
		}

		return $this;
	} // setCurrentOnHold()

	/**
	 * Set the value of [current_on_order] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setCurrentOnOrder($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->current_on_order !== $v || $v === '0') {
			$this->current_on_order = $v;
			$this->modifiedColumns[] = PartVariantPeer::CURRENT_ON_ORDER;
		}

		return $this;
	} // setCurrentOnOrder()

	/**
	 * Set the value of [location] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setLocation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->location !== $v) {
			$this->location = $v;
			$this->modifiedColumns[] = PartVariantPeer::LOCATION;
		}

		return $this;
	} // setLocation()

	/**
	 * Sets the value of [last_inventory_update] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setLastInventoryUpdate($v)
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

		if ( $this->last_inventory_update !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->last_inventory_update !== null && $tmpDt = new DateTime($this->last_inventory_update)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->last_inventory_update = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = PartVariantPeer::LAST_INVENTORY_UPDATE;
			}
		} // if either are not null

		return $this;
	} // setLastInventoryUpdate()

	/**
	 * Set the value of [standard_package_qty] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setStandardPackageQty($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->standard_package_qty !== $v) {
			$this->standard_package_qty = $v;
			$this->modifiedColumns[] = PartVariantPeer::STANDARD_PACKAGE_QTY;
		}

		return $this;
	} // setStandardPackageQty()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     PartVariant The current object (for fluent API support)
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
				$this->modifiedColumns[] = PartVariantPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Set the value of [stocking_notes] column.
	 * 
	 * @param      string $v new value
	 * @return     PartVariant The current object (for fluent API support)
	 */
	public function setStockingNotes($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->stocking_notes !== $v) {
			$this->stocking_notes = $v;
			$this->modifiedColumns[] = PartVariantPeer::STOCKING_NOTES;
		}

		return $this;
	} // setStockingNotes()

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
			if (array_diff($this->modifiedColumns, array(PartVariantPeer::USE_DEFAULT_UNITS,PartVariantPeer::USE_DEFAULT_COSTING,PartVariantPeer::COST_CALCULATION_METHOD,PartVariantPeer::USE_DEFAULT_PRICING,PartVariantPeer::TAXABLE_HST,PartVariantPeer::TAXABLE_GST,PartVariantPeer::TAXABLE_PST,PartVariantPeer::USE_DEFAULT_DIMENSIONS,PartVariantPeer::USE_DEFAULT_INVENTORY,PartVariantPeer::TRACK_INVENTORY,PartVariantPeer::MINIMUM_ON_HAND,PartVariantPeer::CURRENT_ON_HAND,PartVariantPeer::CURRENT_ON_HOLD,PartVariantPeer::CURRENT_ON_ORDER))) {
				return false;
			}

			if ($this->use_default_units !== false) {
				return false;
			}

			if ($this->use_default_costing !== false) {
				return false;
			}

			if ($this->cost_calculation_method !== 'lifo') {
				return false;
			}

			if ($this->use_default_pricing !== false) {
				return false;
			}

			if ($this->taxable_hst !== true) {
				return false;
			}

			if ($this->taxable_gst !== true) {
				return false;
			}

			if ($this->taxable_pst !== true) {
				return false;
			}

			if ($this->use_default_dimensions !== false) {
				return false;
			}

			if ($this->use_default_inventory !== false) {
				return false;
			}

			if ($this->track_inventory !== true) {
				return false;
			}

			if ($this->minimum_on_hand !== '0') {
				return false;
			}

			if ($this->current_on_hand !== '0') {
				return false;
			}

			if ($this->current_on_hold !== '0') {
				return false;
			}

			if ($this->current_on_order !== '0') {
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
			$this->part_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->is_default_variant = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
			$this->manufacturer_sku = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->internal_sku = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->use_default_units = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->units = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->use_default_costing = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->cost_calculation_method = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->unit_cost = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->use_default_pricing = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
			$this->broker_fees = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->shipping_fees = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->unit_price = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->markup_amount = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->markup_percent = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
			$this->taxable_hst = ($row[$startcol + 16] !== null) ? (boolean) $row[$startcol + 16] : null;
			$this->taxable_gst = ($row[$startcol + 17] !== null) ? (boolean) $row[$startcol + 17] : null;
			$this->taxable_pst = ($row[$startcol + 18] !== null) ? (boolean) $row[$startcol + 18] : null;
			$this->enviro_levy = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
			$this->battery_levy = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
			$this->use_default_dimensions = ($row[$startcol + 21] !== null) ? (boolean) $row[$startcol + 21] : null;
			$this->shipping_weight = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
			$this->shipping_width = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
			$this->shipping_height = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
			$this->shipping_depth = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
			$this->shipping_volume = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
			$this->use_default_inventory = ($row[$startcol + 27] !== null) ? (boolean) $row[$startcol + 27] : null;
			$this->track_inventory = ($row[$startcol + 28] !== null) ? (boolean) $row[$startcol + 28] : null;
			$this->minimum_on_hand = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
			$this->maximum_on_hand = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
			$this->current_on_hand = ($row[$startcol + 31] !== null) ? (string) $row[$startcol + 31] : null;
			$this->current_on_hold = ($row[$startcol + 32] !== null) ? (string) $row[$startcol + 32] : null;
			$this->current_on_order = ($row[$startcol + 33] !== null) ? (string) $row[$startcol + 33] : null;
			$this->location = ($row[$startcol + 34] !== null) ? (string) $row[$startcol + 34] : null;
			$this->last_inventory_update = ($row[$startcol + 35] !== null) ? (string) $row[$startcol + 35] : null;
			$this->standard_package_qty = ($row[$startcol + 36] !== null) ? (string) $row[$startcol + 36] : null;
			$this->created_at = ($row[$startcol + 37] !== null) ? (string) $row[$startcol + 37] : null;
			$this->stocking_notes = ($row[$startcol + 38] !== null) ? (string) $row[$startcol + 38] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + PartVariantPeer::NUM_COLUMNS;
			//PartVariantPeer::NUM_COLUMNS = 39
			//return $startcol + 37; // 37 = PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating PartVariant object", $e);
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
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = PartVariantPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aPart = null;
			$this->collPartOptionValues = null;
			$this->lastPartOptionValueCriteria = null;

			$this->collPartSuppliers = null;
			$this->lastPartSupplierCriteria = null;

			$this->collPartPhotos = null;
			$this->lastPartPhotoCriteria = null;

			$this->collPartFiles = null;
			$this->lastPartFileCriteria = null;

			$this->collBarcodes = null;
			$this->lastBarcodeCriteria = null;

			$this->collSubpartsRelatedByParentId = null;
			$this->lastSubpartRelatedByParentIdCriteria = null;

			$this->collSubpartsRelatedByChildId = null;
			$this->lastSubpartRelatedByChildIdCriteria = null;

			$this->collSupplierOrderItems = null;
			$this->lastSupplierOrderItemCriteria = null;

			$this->collPartLots = null;
			$this->lastPartLotCriteria = null;

			$this->collPartInstances = null;
			$this->lastPartInstanceCriteria = null;

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

    foreach (sfMixer::getCallables('BasePartVariant:delete:pre') as $callable)
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
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			PartVariantPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BasePartVariant:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BasePartVariant:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(PartVariantPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BasePartVariant:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			PartVariantPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = PartVariantPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = PartVariantPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += PartVariantPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPartOptionValues !== null) {
				foreach ($this->collPartOptionValues as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartSuppliers !== null) {
				foreach ($this->collPartSuppliers as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartPhotos !== null) {
				foreach ($this->collPartPhotos as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartFiles !== null) {
				foreach ($this->collPartFiles as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collBarcodes !== null) {
				foreach ($this->collBarcodes as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSubpartsRelatedByParentId !== null) {
				foreach ($this->collSubpartsRelatedByParentId as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSubpartsRelatedByChildId !== null) {
				foreach ($this->collSubpartsRelatedByChildId as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSupplierOrderItems !== null) {
				foreach ($this->collSupplierOrderItems as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collPartLots !== null) {
				foreach ($this->collPartLots as $referrerFK) {
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


			if (($retval = PartVariantPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPartOptionValues !== null) {
					foreach ($this->collPartOptionValues as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartSuppliers !== null) {
					foreach ($this->collPartSuppliers as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartPhotos !== null) {
					foreach ($this->collPartPhotos as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartFiles !== null) {
					foreach ($this->collPartFiles as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collBarcodes !== null) {
					foreach ($this->collBarcodes as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSubpartsRelatedByParentId !== null) {
					foreach ($this->collSubpartsRelatedByParentId as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSubpartsRelatedByChildId !== null) {
					foreach ($this->collSubpartsRelatedByChildId as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSupplierOrderItems !== null) {
					foreach ($this->collSupplierOrderItems as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collPartLots !== null) {
					foreach ($this->collPartLots as $referrerFK) {
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
		$pos = PartVariantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPartId();
				break;
			case 2:
				return $this->getIsDefaultVariant();
				break;
			case 3:
				return $this->getManufacturerSku();
				break;
			case 4:
				return $this->getInternalSku();
				break;
			case 5:
				return $this->getUseDefaultUnits();
				break;
			case 6:
				return $this->getUnits();
				break;
			case 7:
				return $this->getUseDefaultCosting();
				break;
			case 8:
				return $this->getCostCalculationMethod();
				break;
			case 9:
				return $this->getUnitCost();
				break;
			case 10:
				return $this->getUseDefaultPricing();
				break;
			case 11:
				return $this->getUnitPrice();
				break;
			case 12:
				return $this->getMarkupAmount();
				break;
			case 13:
				return $this->getMarkupPercent();
				break;
			case 14:
				return $this->getTaxableHst();
				break;
			case 15:
				return $this->getTaxableGst();
				break;
			case 16:
				return $this->getTaxablePst();
				break;
			case 17:
				return $this->getEnviroLevy();
				break;
			case 18:
				return $this->getBatteryLevy();
				break;
			case 19:
				return $this->getUseDefaultDimensions();
				break;
			case 20:
				return $this->getShippingWeight();
				break;
			case 21:
				return $this->getShippingWidth();
				break;
			case 22:
				return $this->getShippingHeight();
				break;
			case 23:
				return $this->getShippingDepth();
				break;
			case 24:
				return $this->getShippingVolume();
				break;
			case 25:
				return $this->getUseDefaultInventory();
				break;
			case 26:
				return $this->getTrackInventory();
				break;
			case 27:
				return $this->getMinimumOnHand();
				break;
			case 28:
				return $this->getMaximumOnHand();
				break;
			case 29:
				return $this->getCurrentOnHand();
				break;
			case 30:
				return $this->getCurrentOnHold();
				break;
			case 31:
				return $this->getCurrentOnOrder();
				break;
			case 32:
				return $this->getLocation();
				break;
			case 33:
				return $this->getLastInventoryUpdate();
				break;
			case 34:
				return $this->getStandardPackageQty();
				break;
			case 35:
				return $this->getCreatedAt();
				break;
			case 36:
				return $this->getStockingNotes();
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
		$keys = PartVariantPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPartId(),
			$keys[2] => $this->getIsDefaultVariant(),
			$keys[3] => $this->getManufacturerSku(),
			$keys[4] => $this->getInternalSku(),
			$keys[5] => $this->getUseDefaultUnits(),
			$keys[6] => $this->getUnits(),
			$keys[7] => $this->getUseDefaultCosting(),
			$keys[8] => $this->getCostCalculationMethod(),
			$keys[9] => $this->getUnitCost(),
			$keys[10] => $this->getUseDefaultPricing(),
			$keys[11] => $this->getUnitPrice(),
			$keys[12] => $this->getMarkupAmount(),
			$keys[13] => $this->getMarkupPercent(),
			$keys[14] => $this->getTaxableHst(),
			$keys[15] => $this->getTaxableGst(),
			$keys[16] => $this->getTaxablePst(),
			$keys[17] => $this->getEnviroLevy(),
			$keys[18] => $this->getBatteryLevy(),
			$keys[19] => $this->getUseDefaultDimensions(),
			$keys[20] => $this->getShippingWeight(),
			$keys[21] => $this->getShippingWidth(),
			$keys[22] => $this->getShippingHeight(),
			$keys[23] => $this->getShippingDepth(),
			$keys[24] => $this->getShippingVolume(),
			$keys[25] => $this->getUseDefaultInventory(),
			$keys[26] => $this->getTrackInventory(),
			$keys[27] => $this->getMinimumOnHand(),
			$keys[28] => $this->getMaximumOnHand(),
			$keys[29] => $this->getCurrentOnHand(),
			$keys[30] => $this->getCurrentOnHold(),
			$keys[31] => $this->getCurrentOnOrder(),
			$keys[32] => $this->getLocation(),
			$keys[33] => $this->getLastInventoryUpdate(),
			$keys[34] => $this->getStandardPackageQty(),
			$keys[35] => $this->getCreatedAt(),
			$keys[36] => $this->getStockingNotes(),
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
		$pos = PartVariantPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPartId($value);
				break;
			case 2:
				$this->setIsDefaultVariant($value);
				break;
			case 3:
				$this->setManufacturerSku($value);
				break;
			case 4:
				$this->setInternalSku($value);
				break;
			case 5:
				$this->setUseDefaultUnits($value);
				break;
			case 6:
				$this->setUnits($value);
				break;
			case 7:
				$this->setUseDefaultCosting($value);
				break;
			case 8:
				$this->setCostCalculationMethod($value);
				break;
			case 9:
				$this->setUnitCost($value);
				break;
			case 10:
				$this->setUseDefaultPricing($value);
				break;
			case 11:
				$this->setUnitPrice($value);
				break;
			case 12:
				$this->setMarkupAmount($value);
				break;
			case 13:
				$this->setMarkupPercent($value);
				break;
			case 14:
				$this->setTaxableHst($value);
				break;
			case 15:
				$this->setTaxableGst($value);
				break;
			case 16:
				$this->setTaxablePst($value);
				break;
			case 17:
				$this->setEnviroLevy($value);
				break;
			case 18:
				$this->setBatteryLevy($value);
				break;
			case 19:
				$this->setUseDefaultDimensions($value);
				break;
			case 20:
				$this->setShippingWeight($value);
				break;
			case 21:
				$this->setShippingWidth($value);
				break;
			case 22:
				$this->setShippingHeight($value);
				break;
			case 23:
				$this->setShippingDepth($value);
				break;
			case 24:
				$this->setShippingVolume($value);
				break;
			case 25:
				$this->setUseDefaultInventory($value);
				break;
			case 26:
				$this->setTrackInventory($value);
				break;
			case 27:
				$this->setMinimumOnHand($value);
				break;
			case 28:
				$this->setMaximumOnHand($value);
				break;
			case 29:
				$this->setCurrentOnHand($value);
				break;
			case 30:
				$this->setCurrentOnHold($value);
				break;
			case 31:
				$this->setCurrentOnOrder($value);
				break;
			case 32:
				$this->setLocation($value);
				break;
			case 33:
				$this->setLastInventoryUpdate($value);
				break;
			case 34:
				$this->setStandardPackageQty($value);
				break;
			case 35:
				$this->setCreatedAt($value);
				break;
			case 36:
				$this->setStockingNotes($value);
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
		$keys = PartVariantPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPartId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setIsDefaultVariant($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setManufacturerSku($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setInternalSku($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUseDefaultUnits($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUnits($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUseDefaultCosting($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCostCalculationMethod($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUnitCost($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setUseDefaultPricing($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUnitPrice($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setMarkupAmount($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setMarkupPercent($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setTaxableHst($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setTaxableGst($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setTaxablePst($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setEnviroLevy($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setBatteryLevy($arr[$keys[18]]);
		if (array_key_exists($keys[19], $arr)) $this->setUseDefaultDimensions($arr[$keys[19]]);
		if (array_key_exists($keys[20], $arr)) $this->setShippingWeight($arr[$keys[20]]);
		if (array_key_exists($keys[21], $arr)) $this->setShippingWidth($arr[$keys[21]]);
		if (array_key_exists($keys[22], $arr)) $this->setShippingHeight($arr[$keys[22]]);
		if (array_key_exists($keys[23], $arr)) $this->setShippingDepth($arr[$keys[23]]);
		if (array_key_exists($keys[24], $arr)) $this->setShippingVolume($arr[$keys[24]]);
		if (array_key_exists($keys[25], $arr)) $this->setUseDefaultInventory($arr[$keys[25]]);
		if (array_key_exists($keys[26], $arr)) $this->setTrackInventory($arr[$keys[26]]);
		if (array_key_exists($keys[27], $arr)) $this->setMinimumOnHand($arr[$keys[27]]);
		if (array_key_exists($keys[28], $arr)) $this->setMaximumOnHand($arr[$keys[28]]);
		if (array_key_exists($keys[29], $arr)) $this->setCurrentOnHand($arr[$keys[29]]);
		if (array_key_exists($keys[30], $arr)) $this->setCurrentOnHold($arr[$keys[30]]);
		if (array_key_exists($keys[31], $arr)) $this->setCurrentOnOrder($arr[$keys[31]]);
		if (array_key_exists($keys[32], $arr)) $this->setLocation($arr[$keys[32]]);
		if (array_key_exists($keys[33], $arr)) $this->setLastInventoryUpdate($arr[$keys[33]]);
		if (array_key_exists($keys[34], $arr)) $this->setStandardPackageQty($arr[$keys[34]]);
		if (array_key_exists($keys[35], $arr)) $this->setCreatedAt($arr[$keys[35]]);
		if (array_key_exists($keys[36], $arr)) $this->setStockingNotes($arr[$keys[36]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);

		if ($this->isColumnModified(PartVariantPeer::ID)) $criteria->add(PartVariantPeer::ID, $this->id);
		if ($this->isColumnModified(PartVariantPeer::PART_ID)) $criteria->add(PartVariantPeer::PART_ID, $this->part_id);
		if ($this->isColumnModified(PartVariantPeer::IS_DEFAULT_VARIANT)) $criteria->add(PartVariantPeer::IS_DEFAULT_VARIANT, $this->is_default_variant);
		if ($this->isColumnModified(PartVariantPeer::MANUFACTURER_SKU)) $criteria->add(PartVariantPeer::MANUFACTURER_SKU, $this->manufacturer_sku);
		if ($this->isColumnModified(PartVariantPeer::INTERNAL_SKU)) $criteria->add(PartVariantPeer::INTERNAL_SKU, $this->internal_sku);
		if ($this->isColumnModified(PartVariantPeer::USE_DEFAULT_UNITS)) $criteria->add(PartVariantPeer::USE_DEFAULT_UNITS, $this->use_default_units);
		if ($this->isColumnModified(PartVariantPeer::UNITS)) $criteria->add(PartVariantPeer::UNITS, $this->units);
		if ($this->isColumnModified(PartVariantPeer::USE_DEFAULT_COSTING)) $criteria->add(PartVariantPeer::USE_DEFAULT_COSTING, $this->use_default_costing);
		if ($this->isColumnModified(PartVariantPeer::COST_CALCULATION_METHOD)) $criteria->add(PartVariantPeer::COST_CALCULATION_METHOD, $this->cost_calculation_method);
		if ($this->isColumnModified(PartVariantPeer::UNIT_COST)) $criteria->add(PartVariantPeer::UNIT_COST, $this->unit_cost);
		if ($this->isColumnModified(PartVariantPeer::USE_DEFAULT_PRICING)) $criteria->add(PartVariantPeer::USE_DEFAULT_PRICING, $this->use_default_pricing);
    if ($this->isColumnModified(PartVariantPeer::BROKER_FEES)) $criteria->add(PartVariantPeer::BROKER_FEES, $this->broker_fees);
    if ($this->isColumnModified(PartVariantPeer::SHIPPING_FEES)) $criteria->add(PartVariantPeer::SHIPPING_FEES, $this->shipping_fees);
    if ($this->isColumnModified(PartVariantPeer::UNIT_PRICE)) $criteria->add(PartVariantPeer::UNIT_PRICE, $this->unit_price);
		if ($this->isColumnModified(PartVariantPeer::MARKUP_AMOUNT)) $criteria->add(PartVariantPeer::MARKUP_AMOUNT, $this->markup_amount);
		if ($this->isColumnModified(PartVariantPeer::MARKUP_PERCENT)) $criteria->add(PartVariantPeer::MARKUP_PERCENT, $this->markup_percent);
		if ($this->isColumnModified(PartVariantPeer::TAXABLE_HST)) $criteria->add(PartVariantPeer::TAXABLE_HST, $this->taxable_hst);
		if ($this->isColumnModified(PartVariantPeer::TAXABLE_GST)) $criteria->add(PartVariantPeer::TAXABLE_GST, $this->taxable_gst);
		if ($this->isColumnModified(PartVariantPeer::TAXABLE_PST)) $criteria->add(PartVariantPeer::TAXABLE_PST, $this->taxable_pst);
		if ($this->isColumnModified(PartVariantPeer::ENVIRO_LEVY)) $criteria->add(PartVariantPeer::ENVIRO_LEVY, $this->enviro_levy);
		if ($this->isColumnModified(PartVariantPeer::BATTERY_LEVY)) $criteria->add(PartVariantPeer::BATTERY_LEVY, $this->battery_levy);
		if ($this->isColumnModified(PartVariantPeer::USE_DEFAULT_DIMENSIONS)) $criteria->add(PartVariantPeer::USE_DEFAULT_DIMENSIONS, $this->use_default_dimensions);
		if ($this->isColumnModified(PartVariantPeer::SHIPPING_WEIGHT)) $criteria->add(PartVariantPeer::SHIPPING_WEIGHT, $this->shipping_weight);
		if ($this->isColumnModified(PartVariantPeer::SHIPPING_WIDTH)) $criteria->add(PartVariantPeer::SHIPPING_WIDTH, $this->shipping_width);
		if ($this->isColumnModified(PartVariantPeer::SHIPPING_HEIGHT)) $criteria->add(PartVariantPeer::SHIPPING_HEIGHT, $this->shipping_height);
		if ($this->isColumnModified(PartVariantPeer::SHIPPING_DEPTH)) $criteria->add(PartVariantPeer::SHIPPING_DEPTH, $this->shipping_depth);
		if ($this->isColumnModified(PartVariantPeer::SHIPPING_VOLUME)) $criteria->add(PartVariantPeer::SHIPPING_VOLUME, $this->shipping_volume);
		if ($this->isColumnModified(PartVariantPeer::USE_DEFAULT_INVENTORY)) $criteria->add(PartVariantPeer::USE_DEFAULT_INVENTORY, $this->use_default_inventory);
		if ($this->isColumnModified(PartVariantPeer::TRACK_INVENTORY)) $criteria->add(PartVariantPeer::TRACK_INVENTORY, $this->track_inventory);
		if ($this->isColumnModified(PartVariantPeer::MINIMUM_ON_HAND)) $criteria->add(PartVariantPeer::MINIMUM_ON_HAND, $this->minimum_on_hand);
		if ($this->isColumnModified(PartVariantPeer::MAXIMUM_ON_HAND)) $criteria->add(PartVariantPeer::MAXIMUM_ON_HAND, $this->maximum_on_hand);
		if ($this->isColumnModified(PartVariantPeer::CURRENT_ON_HAND)) $criteria->add(PartVariantPeer::CURRENT_ON_HAND, $this->current_on_hand);
		if ($this->isColumnModified(PartVariantPeer::CURRENT_ON_HOLD)) $criteria->add(PartVariantPeer::CURRENT_ON_HOLD, $this->current_on_hold);
		if ($this->isColumnModified(PartVariantPeer::CURRENT_ON_ORDER)) $criteria->add(PartVariantPeer::CURRENT_ON_ORDER, $this->current_on_order);
		if ($this->isColumnModified(PartVariantPeer::LOCATION)) $criteria->add(PartVariantPeer::LOCATION, $this->location);
		if ($this->isColumnModified(PartVariantPeer::LAST_INVENTORY_UPDATE)) $criteria->add(PartVariantPeer::LAST_INVENTORY_UPDATE, $this->last_inventory_update);
		if ($this->isColumnModified(PartVariantPeer::STANDARD_PACKAGE_QTY)) $criteria->add(PartVariantPeer::STANDARD_PACKAGE_QTY, $this->standard_package_qty);
		if ($this->isColumnModified(PartVariantPeer::CREATED_AT)) $criteria->add(PartVariantPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(PartVariantPeer::STOCKING_NOTES)) $criteria->add(PartVariantPeer::STOCKING_NOTES, $this->stocking_notes);

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
		$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);

		$criteria->add(PartVariantPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of PartVariant (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPartId($this->part_id);

		$copyObj->setIsDefaultVariant($this->is_default_variant);

		$copyObj->setManufacturerSku($this->manufacturer_sku);

		$copyObj->setInternalSku($this->internal_sku);

		$copyObj->setUseDefaultUnits($this->use_default_units);

		$copyObj->setUnits($this->units);

		$copyObj->setUseDefaultCosting($this->use_default_costing);

		$copyObj->setCostCalculationMethod($this->cost_calculation_method);

		$copyObj->setUnitCost($this->unit_cost);

		$copyObj->setUseDefaultPricing($this->use_default_pricing);

		$copyObj->setBrokerFees($this->broker_fees);

    $copyObj->setShippingFees($this->shipping_fees);

    $copyObj->setUnitPrice($this->unit_price);

		$copyObj->setMarkupAmount($this->markup_amount);

		$copyObj->setMarkupPercent($this->markup_percent);

		$copyObj->setTaxableHst($this->taxable_hst);

		$copyObj->setTaxableGst($this->taxable_gst);

		$copyObj->setTaxablePst($this->taxable_pst);

		$copyObj->setEnviroLevy($this->enviro_levy);

		$copyObj->setBatteryLevy($this->battery_levy);

		$copyObj->setUseDefaultDimensions($this->use_default_dimensions);

		$copyObj->setShippingWeight($this->shipping_weight);

		$copyObj->setShippingWidth($this->shipping_width);

		$copyObj->setShippingHeight($this->shipping_height);

		$copyObj->setShippingDepth($this->shipping_depth);

		$copyObj->setShippingVolume($this->shipping_volume);

		$copyObj->setUseDefaultInventory($this->use_default_inventory);

		$copyObj->setTrackInventory($this->track_inventory);

		$copyObj->setMinimumOnHand($this->minimum_on_hand);

		$copyObj->setMaximumOnHand($this->maximum_on_hand);

		$copyObj->setCurrentOnHand($this->current_on_hand);

		$copyObj->setCurrentOnHold($this->current_on_hold);

		$copyObj->setCurrentOnOrder($this->current_on_order);

		$copyObj->setLocation($this->location);

		$copyObj->setLastInventoryUpdate($this->last_inventory_update);

		$copyObj->setStandardPackageQty($this->standard_package_qty);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setStockingNotes($this->stocking_notes);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPartOptionValues() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartOptionValue($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartSuppliers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartSupplier($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartPhotos() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartPhoto($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartFiles() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartFile($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getBarcodes() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addBarcode($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSubpartsRelatedByParentId() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSubpartRelatedByParentId($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSubpartsRelatedByChildId() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSubpartRelatedByChildId($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSupplierOrderItems() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSupplierOrderItem($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartLots() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartLot($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getPartInstances() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPartInstance($relObj->copy($deepCopy));
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
	 * @return     PartVariant Clone of current object.
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
	 * @return     PartVariantPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new PartVariantPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Part object.
	 *
	 * @param      Part $v
	 * @return     PartVariant The current object (for fluent API support)
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
			$v->addPartVariant($this);
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
			   $this->aPart->addPartVariants($this);
			 */
		}
		return $this->aPart;
	}

	/**
	 * Clears out the collPartOptionValues collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartOptionValues()
	 */
	public function clearPartOptionValues()
	{
		$this->collPartOptionValues = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartOptionValues collection (array).
	 *
	 * By default this just sets the collPartOptionValues collection to an empty array (like clearcollPartOptionValues());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartOptionValues()
	{
		$this->collPartOptionValues = array();
	}

	/**
	 * Gets an array of PartOptionValue objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartOptionValues from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartOptionValue[]
	 * @throws     PropelException
	 */
	public function getPartOptionValues($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartOptionValues === null) {
			if ($this->isNew()) {
			   $this->collPartOptionValues = array();
			} else {

				$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

				PartOptionValuePeer::addSelectColumns($criteria);
				$this->collPartOptionValues = PartOptionValuePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

				PartOptionValuePeer::addSelectColumns($criteria);
				if (!isset($this->lastPartOptionValueCriteria) || !$this->lastPartOptionValueCriteria->equals($criteria)) {
					$this->collPartOptionValues = PartOptionValuePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartOptionValueCriteria = $criteria;
		return $this->collPartOptionValues;
	}

	/**
	 * Returns the number of related PartOptionValue objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartOptionValue objects.
	 * @throws     PropelException
	 */
	public function countPartOptionValues(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartOptionValues === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

				$count = PartOptionValuePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

				if (!isset($this->lastPartOptionValueCriteria) || !$this->lastPartOptionValueCriteria->equals($criteria)) {
					$count = PartOptionValuePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartOptionValues);
				}
			} else {
				$count = count($this->collPartOptionValues);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartOptionValue object to this object
	 * through the PartOptionValue foreign key attribute.
	 *
	 * @param      PartOptionValue $l PartOptionValue
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartOptionValue(PartOptionValue $l)
	{
		if ($this->collPartOptionValues === null) {
			$this->initPartOptionValues();
		}
		if (!in_array($l, $this->collPartOptionValues, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartOptionValues, $l);
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartOptionValues from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartOptionValuesJoinPartOption($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartOptionValues === null) {
			if ($this->isNew()) {
				$this->collPartOptionValues = array();
			} else {

				$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

				$this->collPartOptionValues = PartOptionValuePeer::doSelectJoinPartOption($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartOptionValuePeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartOptionValueCriteria) || !$this->lastPartOptionValueCriteria->equals($criteria)) {
				$this->collPartOptionValues = PartOptionValuePeer::doSelectJoinPartOption($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartOptionValueCriteria = $criteria;

		return $this->collPartOptionValues;
	}

	/**
	 * Clears out the collPartSuppliers collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartSuppliers()
	 */
	public function clearPartSuppliers()
	{
		$this->collPartSuppliers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartSuppliers collection (array).
	 *
	 * By default this just sets the collPartSuppliers collection to an empty array (like clearcollPartSuppliers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartSuppliers()
	{
		$this->collPartSuppliers = array();
	}

	/**
	 * Gets an array of PartSupplier objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartSuppliers from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartSupplier[]
	 * @throws     PropelException
	 */
	public function getPartSuppliers($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartSuppliers === null) {
			if ($this->isNew()) {
			   $this->collPartSuppliers = array();
			} else {

				$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

				PartSupplierPeer::addSelectColumns($criteria);
				$this->collPartSuppliers = PartSupplierPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

				PartSupplierPeer::addSelectColumns($criteria);
				if (!isset($this->lastPartSupplierCriteria) || !$this->lastPartSupplierCriteria->equals($criteria)) {
					$this->collPartSuppliers = PartSupplierPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartSupplierCriteria = $criteria;
		return $this->collPartSuppliers;
	}

	/**
	 * Returns the number of related PartSupplier objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartSupplier objects.
	 * @throws     PropelException
	 */
	public function countPartSuppliers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartSuppliers === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

				$count = PartSupplierPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

				if (!isset($this->lastPartSupplierCriteria) || !$this->lastPartSupplierCriteria->equals($criteria)) {
					$count = PartSupplierPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartSuppliers);
				}
			} else {
				$count = count($this->collPartSuppliers);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartSupplier object to this object
	 * through the PartSupplier foreign key attribute.
	 *
	 * @param      PartSupplier $l PartSupplier
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartSupplier(PartSupplier $l)
	{
		if ($this->collPartSuppliers === null) {
			$this->initPartSuppliers();
		}
		if (!in_array($l, $this->collPartSuppliers, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartSuppliers, $l);
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartSuppliers from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartSuppliersJoinSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartSuppliers === null) {
			if ($this->isNew()) {
				$this->collPartSuppliers = array();
			} else {

				$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

				$this->collPartSuppliers = PartSupplierPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartSupplierPeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartSupplierCriteria) || !$this->lastPartSupplierCriteria->equals($criteria)) {
				$this->collPartSuppliers = PartSupplierPeer::doSelectJoinSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartSupplierCriteria = $criteria;

		return $this->collPartSuppliers;
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
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartPhotos from storage. If this PartVariant is new, it will return
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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
			   $this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

				PartPhotoPeer::addSelectColumns($criteria);
				$this->collPartPhotos = PartPhotoPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
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

				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

				$count = PartPhotoPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

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
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartPhotosJoinPart($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPart($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

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
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartPhotos from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartPhotosJoinPhoto($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartPhotos === null) {
			if ($this->isNew()) {
				$this->collPartPhotos = array();
			} else {

				$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartPhotoPeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartPhotoCriteria) || !$this->lastPartPhotoCriteria->equals($criteria)) {
				$this->collPartPhotos = PartPhotoPeer::doSelectJoinPhoto($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartPhotoCriteria = $criteria;

		return $this->collPartPhotos;
	}

	/**
	 * Clears out the collPartFiles collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartFiles()
	 */
	public function clearPartFiles()
	{
		$this->collPartFiles = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartFiles collection (array).
	 *
	 * By default this just sets the collPartFiles collection to an empty array (like clearcollPartFiles());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartFiles()
	{
		$this->collPartFiles = array();
	}

	/**
	 * Gets an array of PartFile objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartFiles from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartFile[]
	 * @throws     PropelException
	 */
	public function getPartFiles($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
			   $this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				PartFilePeer::addSelectColumns($criteria);
				$this->collPartFiles = PartFilePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				PartFilePeer::addSelectColumns($criteria);
				if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
					$this->collPartFiles = PartFilePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartFileCriteria = $criteria;
		return $this->collPartFiles;
	}

	/**
	 * Returns the number of related PartFile objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartFile objects.
	 * @throws     PropelException
	 */
	public function countPartFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				$count = PartFilePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
					$count = PartFilePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartFiles);
				}
			} else {
				$count = count($this->collPartFiles);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartFile object to this object
	 * through the PartFile foreign key attribute.
	 *
	 * @param      PartFile $l PartFile
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartFile(PartFile $l)
	{
		if ($this->collPartFiles === null) {
			$this->initPartFiles();
		}
		if (!in_array($l, $this->collPartFiles, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartFiles, $l);
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartFiles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartFilesJoinPart($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				$this->collPartFiles = PartFilePeer::doSelectJoinPart($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
				$this->collPartFiles = PartFilePeer::doSelectJoinPart($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartFileCriteria = $criteria;

		return $this->collPartFiles;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartFiles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartFilesJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartFiles === null) {
			if ($this->isNew()) {
				$this->collPartFiles = array();
			} else {

				$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

				$this->collPartFiles = PartFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartFilePeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartFileCriteria) || !$this->lastPartFileCriteria->equals($criteria)) {
				$this->collPartFiles = PartFilePeer::doSelectJoinFile($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartFileCriteria = $criteria;

		return $this->collPartFiles;
	}

	/**
	 * Clears out the collBarcodes collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addBarcodes()
	 */
	public function clearBarcodes()
	{
		$this->collBarcodes = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collBarcodes collection (array).
	 *
	 * By default this just sets the collBarcodes collection to an empty array (like clearcollBarcodes());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initBarcodes()
	{
		$this->collBarcodes = array();
	}

	/**
	 * Gets an array of Barcode objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related Barcodes from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Barcode[]
	 * @throws     PropelException
	 */
	public function getBarcodes($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBarcodes === null) {
			if ($this->isNew()) {
			   $this->collBarcodes = array();
			} else {

				$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

				BarcodePeer::addSelectColumns($criteria);
				$this->collBarcodes = BarcodePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

				BarcodePeer::addSelectColumns($criteria);
				if (!isset($this->lastBarcodeCriteria) || !$this->lastBarcodeCriteria->equals($criteria)) {
					$this->collBarcodes = BarcodePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastBarcodeCriteria = $criteria;
		return $this->collBarcodes;
	}

	/**
	 * Returns the number of related Barcode objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Barcode objects.
	 * @throws     PropelException
	 */
	public function countBarcodes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collBarcodes === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

				$count = BarcodePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

				if (!isset($this->lastBarcodeCriteria) || !$this->lastBarcodeCriteria->equals($criteria)) {
					$count = BarcodePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collBarcodes);
				}
			} else {
				$count = count($this->collBarcodes);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Barcode object to this object
	 * through the Barcode foreign key attribute.
	 *
	 * @param      Barcode $l Barcode
	 * @return     void
	 * @throws     PropelException
	 */
	public function addBarcode(Barcode $l)
	{
		if ($this->collBarcodes === null) {
			$this->initBarcodes();
		}
		if (!in_array($l, $this->collBarcodes, true)) { // only add it if the **same** object is not already associated
			array_push($this->collBarcodes, $l);
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related Barcodes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getBarcodesJoinPartSupplier($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBarcodes === null) {
			if ($this->isNew()) {
				$this->collBarcodes = array();
			} else {

				$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

				$this->collBarcodes = BarcodePeer::doSelectJoinPartSupplier($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(BarcodePeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastBarcodeCriteria) || !$this->lastBarcodeCriteria->equals($criteria)) {
				$this->collBarcodes = BarcodePeer::doSelectJoinPartSupplier($criteria, $con, $join_behavior);
			}
		}
		$this->lastBarcodeCriteria = $criteria;

		return $this->collBarcodes;
	}

	/**
	 * Clears out the collSubpartsRelatedByParentId collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSubpartsRelatedByParentId()
	 */
	public function clearSubpartsRelatedByParentId()
	{
		$this->collSubpartsRelatedByParentId = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSubpartsRelatedByParentId collection (array).
	 *
	 * By default this just sets the collSubpartsRelatedByParentId collection to an empty array (like clearcollSubpartsRelatedByParentId());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSubpartsRelatedByParentId()
	{
		$this->collSubpartsRelatedByParentId = array();
	}

	/**
	 * Gets an array of Subpart objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related SubpartsRelatedByParentId from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Subpart[]
	 * @throws     PropelException
	 */
	public function getSubpartsRelatedByParentId($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSubpartsRelatedByParentId === null) {
			if ($this->isNew()) {
			   $this->collSubpartsRelatedByParentId = array();
			} else {

				$criteria->add(SubpartPeer::PARENT_ID, $this->id);

				SubpartPeer::addSelectColumns($criteria);
				$this->collSubpartsRelatedByParentId = SubpartPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SubpartPeer::PARENT_ID, $this->id);

				SubpartPeer::addSelectColumns($criteria);
				if (!isset($this->lastSubpartRelatedByParentIdCriteria) || !$this->lastSubpartRelatedByParentIdCriteria->equals($criteria)) {
					$this->collSubpartsRelatedByParentId = SubpartPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSubpartRelatedByParentIdCriteria = $criteria;
		return $this->collSubpartsRelatedByParentId;
	}

	/**
	 * Returns the number of related Subpart objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Subpart objects.
	 * @throws     PropelException
	 */
	public function countSubpartsRelatedByParentId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSubpartsRelatedByParentId === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SubpartPeer::PARENT_ID, $this->id);

				$count = SubpartPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SubpartPeer::PARENT_ID, $this->id);

				if (!isset($this->lastSubpartRelatedByParentIdCriteria) || !$this->lastSubpartRelatedByParentIdCriteria->equals($criteria)) {
					$count = SubpartPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSubpartsRelatedByParentId);
				}
			} else {
				$count = count($this->collSubpartsRelatedByParentId);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Subpart object to this object
	 * through the Subpart foreign key attribute.
	 *
	 * @param      Subpart $l Subpart
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSubpartRelatedByParentId(Subpart $l)
	{
		if ($this->collSubpartsRelatedByParentId === null) {
			$this->initSubpartsRelatedByParentId();
		}
		if (!in_array($l, $this->collSubpartsRelatedByParentId, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSubpartsRelatedByParentId, $l);
			$l->setPartVariantRelatedByParentId($this);
		}
	}

	/**
	 * Clears out the collSubpartsRelatedByChildId collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSubpartsRelatedByChildId()
	 */
	public function clearSubpartsRelatedByChildId()
	{
		$this->collSubpartsRelatedByChildId = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSubpartsRelatedByChildId collection (array).
	 *
	 * By default this just sets the collSubpartsRelatedByChildId collection to an empty array (like clearcollSubpartsRelatedByChildId());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSubpartsRelatedByChildId()
	{
		$this->collSubpartsRelatedByChildId = array();
	}

	/**
	 * Gets an array of Subpart objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related SubpartsRelatedByChildId from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Subpart[]
	 * @throws     PropelException
	 */
	public function getSubpartsRelatedByChildId($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSubpartsRelatedByChildId === null) {
			if ($this->isNew()) {
			   $this->collSubpartsRelatedByChildId = array();
			} else {

				$criteria->add(SubpartPeer::CHILD_ID, $this->id);

				SubpartPeer::addSelectColumns($criteria);
				$this->collSubpartsRelatedByChildId = SubpartPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SubpartPeer::CHILD_ID, $this->id);

				SubpartPeer::addSelectColumns($criteria);
				if (!isset($this->lastSubpartRelatedByChildIdCriteria) || !$this->lastSubpartRelatedByChildIdCriteria->equals($criteria)) {
					$this->collSubpartsRelatedByChildId = SubpartPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSubpartRelatedByChildIdCriteria = $criteria;
		return $this->collSubpartsRelatedByChildId;
	}

	/**
	 * Returns the number of related Subpart objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Subpart objects.
	 * @throws     PropelException
	 */
	public function countSubpartsRelatedByChildId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSubpartsRelatedByChildId === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SubpartPeer::CHILD_ID, $this->id);

				$count = SubpartPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SubpartPeer::CHILD_ID, $this->id);

				if (!isset($this->lastSubpartRelatedByChildIdCriteria) || !$this->lastSubpartRelatedByChildIdCriteria->equals($criteria)) {
					$count = SubpartPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collSubpartsRelatedByChildId);
				}
			} else {
				$count = count($this->collSubpartsRelatedByChildId);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Subpart object to this object
	 * through the Subpart foreign key attribute.
	 *
	 * @param      Subpart $l Subpart
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSubpartRelatedByChildId(Subpart $l)
	{
		if ($this->collSubpartsRelatedByChildId === null) {
			$this->initSubpartsRelatedByChildId();
		}
		if (!in_array($l, $this->collSubpartsRelatedByChildId, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSubpartsRelatedByChildId, $l);
			$l->setPartVariantRelatedByChildId($this);
		}
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
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related SupplierOrderItems from storage. If this PartVariant is new, it will return
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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrderItems === null) {
			if ($this->isNew()) {
			   $this->collSupplierOrderItems = array();
			} else {

				$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

				SupplierOrderItemPeer::addSelectColumns($criteria);
				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
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

				$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

				$count = SupplierOrderItemPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

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
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related SupplierOrderItems from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getSupplierOrderItemsJoinSupplierOrder($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSupplierOrderItems === null) {
			if ($this->isNew()) {
				$this->collSupplierOrderItems = array();
			} else {

				$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelectJoinSupplierOrder($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastSupplierOrderItemCriteria) || !$this->lastSupplierOrderItemCriteria->equals($criteria)) {
				$this->collSupplierOrderItems = SupplierOrderItemPeer::doSelectJoinSupplierOrder($criteria, $con, $join_behavior);
			}
		}
		$this->lastSupplierOrderItemCriteria = $criteria;

		return $this->collSupplierOrderItems;
	}

	/**
	 * Clears out the collPartLots collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPartLots()
	 */
	public function clearPartLots()
	{
		$this->collPartLots = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPartLots collection (array).
	 *
	 * By default this just sets the collPartLots collection to an empty array (like clearcollPartLots());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPartLots()
	{
		$this->collPartLots = array();
	}

	/**
	 * Gets an array of PartLot objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartLots from storage. If this PartVariant is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array PartLot[]
	 * @throws     PropelException
	 */
	public function getPartLots($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartLots === null) {
			if ($this->isNew()) {
			   $this->collPartLots = array();
			} else {

				$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

				PartLotPeer::addSelectColumns($criteria);
				$this->collPartLots = PartLotPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

				PartLotPeer::addSelectColumns($criteria);
				if (!isset($this->lastPartLotCriteria) || !$this->lastPartLotCriteria->equals($criteria)) {
					$this->collPartLots = PartLotPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPartLotCriteria = $criteria;
		return $this->collPartLots;
	}

	/**
	 * Returns the number of related PartLot objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related PartLot objects.
	 * @throws     PropelException
	 */
	public function countPartLots(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPartLots === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

				$count = PartLotPeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

				if (!isset($this->lastPartLotCriteria) || !$this->lastPartLotCriteria->equals($criteria)) {
					$count = PartLotPeer::doCount($criteria, $con);
				} else {
					$count = count($this->collPartLots);
				}
			} else {
				$count = count($this->collPartLots);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a PartLot object to this object
	 * through the PartLot foreign key attribute.
	 *
	 * @param      PartLot $l PartLot
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPartLot(PartLot $l)
	{
		if ($this->collPartLots === null) {
			$this->initPartLots();
		}
		if (!in_array($l, $this->collPartLots, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPartLots, $l);
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartLots from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartLotsJoinSupplierOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartLots === null) {
			if ($this->isNew()) {
				$this->collPartLots = array();
			} else {

				$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

				$this->collPartLots = PartLotPeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartLotPeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartLotCriteria) || !$this->lastPartLotCriteria->equals($criteria)) {
				$this->collPartLots = PartLotPeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartLotCriteria = $criteria;

		return $this->collPartLots;
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
	 * Otherwise if this PartVariant has previously been saved, it will retrieve
	 * related PartInstances from storage. If this PartVariant is new, it will return
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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
			   $this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				PartInstancePeer::addSelectColumns($criteria);
				$this->collPartInstances = PartInstancePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

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
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
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

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				$count = PartInstancePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

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
			$l->setPartVariant($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartInstancesJoinSupplierOrderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinSupplierOrderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

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
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartInstancesJoinWorkorderItem($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinWorkorderItem($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

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
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartInstancesJoinInvoice($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinInvoice($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

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
	 * Otherwise if this PartVariant is new, it will return
	 * an empty collection; or if this PartVariant has previously
	 * been saved, it will retrieve related PartInstances from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in PartVariant.
	 */
	public function getPartInstancesJoinEmployee($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPartInstances === null) {
			if ($this->isNew()) {
				$this->collPartInstances = array();
			} else {

				$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(PartInstancePeer::PART_VARIANT_ID, $this->id);

			if (!isset($this->lastPartInstanceCriteria) || !$this->lastPartInstanceCriteria->equals($criteria)) {
				$this->collPartInstances = PartInstancePeer::doSelectJoinEmployee($criteria, $con, $join_behavior);
			}
		}
		$this->lastPartInstanceCriteria = $criteria;

		return $this->collPartInstances;
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
			if ($this->collPartOptionValues) {
				foreach ((array) $this->collPartOptionValues as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartSuppliers) {
				foreach ((array) $this->collPartSuppliers as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartPhotos) {
				foreach ((array) $this->collPartPhotos as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartFiles) {
				foreach ((array) $this->collPartFiles as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collBarcodes) {
				foreach ((array) $this->collBarcodes as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSubpartsRelatedByParentId) {
				foreach ((array) $this->collSubpartsRelatedByParentId as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSubpartsRelatedByChildId) {
				foreach ((array) $this->collSubpartsRelatedByChildId as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSupplierOrderItems) {
				foreach ((array) $this->collSupplierOrderItems as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartLots) {
				foreach ((array) $this->collPartLots as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collPartInstances) {
				foreach ((array) $this->collPartInstances as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPartOptionValues = null;
		$this->collPartSuppliers = null;
		$this->collPartPhotos = null;
		$this->collPartFiles = null;
		$this->collBarcodes = null;
		$this->collSubpartsRelatedByParentId = null;
		$this->collSubpartsRelatedByChildId = null;
		$this->collSupplierOrderItems = null;
		$this->collPartLots = null;
		$this->collPartInstances = null;
			$this->aPart = null;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BasePartVariant:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BasePartVariant::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BasePartVariant
