<?php

/**
 * Base static class for performing query and update operations on the 'part_variant' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartVariantPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'part_variant';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.PartVariant';

	/** The total number of columns. */
	const NUM_COLUMNS = 37;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'part_variant.ID';

	/** the column name for the PART_ID field */
	const PART_ID = 'part_variant.PART_ID';

	/** the column name for the IS_DEFAULT_VARIANT field */
	const IS_DEFAULT_VARIANT = 'part_variant.IS_DEFAULT_VARIANT';

	/** the column name for the MANUFACTURER_SKU field */
	const MANUFACTURER_SKU = 'part_variant.MANUFACTURER_SKU';

	/** the column name for the INTERNAL_SKU field */
	const INTERNAL_SKU = 'part_variant.INTERNAL_SKU';

	/** the column name for the USE_DEFAULT_UNITS field */
	const USE_DEFAULT_UNITS = 'part_variant.USE_DEFAULT_UNITS';

	/** the column name for the UNITS field */
	const UNITS = 'part_variant.UNITS';

	/** the column name for the USE_DEFAULT_COSTING field */
	const USE_DEFAULT_COSTING = 'part_variant.USE_DEFAULT_COSTING';

	/** the column name for the COST_CALCULATION_METHOD field */
	const COST_CALCULATION_METHOD = 'part_variant.COST_CALCULATION_METHOD';

	/** the column name for the UNIT_COST field */
	const UNIT_COST = 'part_variant.UNIT_COST';

	/** the column name for the USE_DEFAULT_PRICING field */
	const USE_DEFAULT_PRICING = 'part_variant.USE_DEFAULT_PRICING';

	/** the column name for the UNIT_PRICE field */
	const UNIT_PRICE = 'part_variant.UNIT_PRICE';

	/** the column name for the MARKUP_AMOUNT field */
	const MARKUP_AMOUNT = 'part_variant.MARKUP_AMOUNT';

	/** the column name for the MARKUP_PERCENT field */
	const MARKUP_PERCENT = 'part_variant.MARKUP_PERCENT';

	/** the column name for the TAXABLE_HST field */
	const TAXABLE_HST = 'part_variant.TAXABLE_HST';

	/** the column name for the TAXABLE_GST field */
	const TAXABLE_GST = 'part_variant.TAXABLE_GST';

	/** the column name for the TAXABLE_PST field */
	const TAXABLE_PST = 'part_variant.TAXABLE_PST';

	/** the column name for the ENVIRO_LEVY field */
	const ENVIRO_LEVY = 'part_variant.ENVIRO_LEVY';

	/** the column name for the BATTERY_LEVY field */
	const BATTERY_LEVY = 'part_variant.BATTERY_LEVY';

	/** the column name for the USE_DEFAULT_DIMENSIONS field */
	const USE_DEFAULT_DIMENSIONS = 'part_variant.USE_DEFAULT_DIMENSIONS';

	/** the column name for the SHIPPING_WEIGHT field */
	const SHIPPING_WEIGHT = 'part_variant.SHIPPING_WEIGHT';

	/** the column name for the SHIPPING_WIDTH field */
	const SHIPPING_WIDTH = 'part_variant.SHIPPING_WIDTH';

	/** the column name for the SHIPPING_HEIGHT field */
	const SHIPPING_HEIGHT = 'part_variant.SHIPPING_HEIGHT';

	/** the column name for the SHIPPING_DEPTH field */
	const SHIPPING_DEPTH = 'part_variant.SHIPPING_DEPTH';

	/** the column name for the SHIPPING_VOLUME field */
	const SHIPPING_VOLUME = 'part_variant.SHIPPING_VOLUME';

	/** the column name for the USE_DEFAULT_INVENTORY field */
	const USE_DEFAULT_INVENTORY = 'part_variant.USE_DEFAULT_INVENTORY';

	/** the column name for the TRACK_INVENTORY field */
	const TRACK_INVENTORY = 'part_variant.TRACK_INVENTORY';

	/** the column name for the MINIMUM_ON_HAND field */
	const MINIMUM_ON_HAND = 'part_variant.MINIMUM_ON_HAND';

	/** the column name for the MAXIMUM_ON_HAND field */
	const MAXIMUM_ON_HAND = 'part_variant.MAXIMUM_ON_HAND';

	/** the column name for the CURRENT_ON_HAND field */
	const CURRENT_ON_HAND = 'part_variant.CURRENT_ON_HAND';

	/** the column name for the CURRENT_ON_HOLD field */
	const CURRENT_ON_HOLD = 'part_variant.CURRENT_ON_HOLD';

	/** the column name for the CURRENT_ON_ORDER field */
	const CURRENT_ON_ORDER = 'part_variant.CURRENT_ON_ORDER';

	/** the column name for the LOCATION field */
	const LOCATION = 'part_variant.LOCATION';

	/** the column name for the LAST_INVENTORY_UPDATE field */
	const LAST_INVENTORY_UPDATE = 'part_variant.LAST_INVENTORY_UPDATE';

	/** the column name for the STANDARD_PACKAGE_QTY field */
	const STANDARD_PACKAGE_QTY = 'part_variant.STANDARD_PACKAGE_QTY';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'part_variant.CREATED_AT';

	/** the column name for the STOCKING_NOTES field */
	const STOCKING_NOTES = 'part_variant.STOCKING_NOTES';

	/**
	 * An identiy map to hold any loaded instances of PartVariant objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array PartVariant[]
	 */
	public static $instances = array();

	/**
	 * The MapBuilder instance for this peer.
	 * @var        MapBuilder
	 */
	private static $mapBuilder = null;

	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'PartId', 'IsDefaultVariant', 'ManufacturerSku', 'InternalSku', 'UseDefaultUnits', 'Units', 'UseDefaultCosting', 'CostCalculationMethod', 'UnitCost', 'UseDefaultPricing', 'UnitPrice', 'MarkupAmount', 'MarkupPercent', 'TaxableHst', 'TaxableGst', 'TaxablePst', 'EnviroLevy', 'BatteryLevy', 'UseDefaultDimensions', 'ShippingWeight', 'ShippingWidth', 'ShippingHeight', 'ShippingDepth', 'ShippingVolume', 'UseDefaultInventory', 'TrackInventory', 'MinimumOnHand', 'MaximumOnHand', 'CurrentOnHand', 'CurrentOnHold', 'CurrentOnOrder', 'Location', 'LastInventoryUpdate', 'StandardPackageQty', 'CreatedAt', 'StockingNotes', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'partId', 'isDefaultVariant', 'manufacturerSku', 'internalSku', 'useDefaultUnits', 'units', 'useDefaultCosting', 'costCalculationMethod', 'unitCost', 'useDefaultPricing', 'unitPrice', 'markupAmount', 'markupPercent', 'taxableHst', 'taxableGst', 'taxablePst', 'enviroLevy', 'batteryLevy', 'useDefaultDimensions', 'shippingWeight', 'shippingWidth', 'shippingHeight', 'shippingDepth', 'shippingVolume', 'useDefaultInventory', 'trackInventory', 'minimumOnHand', 'maximumOnHand', 'currentOnHand', 'currentOnHold', 'currentOnOrder', 'location', 'lastInventoryUpdate', 'standardPackageQty', 'createdAt', 'stockingNotes', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::PART_ID, self::IS_DEFAULT_VARIANT, self::MANUFACTURER_SKU, self::INTERNAL_SKU, self::USE_DEFAULT_UNITS, self::UNITS, self::USE_DEFAULT_COSTING, self::COST_CALCULATION_METHOD, self::UNIT_COST, self::USE_DEFAULT_PRICING, self::UNIT_PRICE, self::MARKUP_AMOUNT, self::MARKUP_PERCENT, self::TAXABLE_HST, self::TAXABLE_GST, self::TAXABLE_PST, self::ENVIRO_LEVY, self::BATTERY_LEVY, self::USE_DEFAULT_DIMENSIONS, self::SHIPPING_WEIGHT, self::SHIPPING_WIDTH, self::SHIPPING_HEIGHT, self::SHIPPING_DEPTH, self::SHIPPING_VOLUME, self::USE_DEFAULT_INVENTORY, self::TRACK_INVENTORY, self::MINIMUM_ON_HAND, self::MAXIMUM_ON_HAND, self::CURRENT_ON_HAND, self::CURRENT_ON_HOLD, self::CURRENT_ON_ORDER, self::LOCATION, self::LAST_INVENTORY_UPDATE, self::STANDARD_PACKAGE_QTY, self::CREATED_AT, self::STOCKING_NOTES, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'part_id', 'is_default_variant', 'manufacturer_sku', 'internal_sku', 'use_default_units', 'units', 'use_default_costing', 'cost_calculation_method', 'unit_cost', 'use_default_pricing', 'unit_price', 'markup_amount', 'markup_percent', 'taxable_hst', 'taxable_gst', 'taxable_pst', 'enviro_levy', 'battery_levy', 'use_default_dimensions', 'shipping_weight', 'shipping_width', 'shipping_height', 'shipping_depth', 'shipping_volume', 'use_default_inventory', 'track_inventory', 'minimum_on_hand', 'maximum_on_hand', 'current_on_hand', 'current_on_hold', 'current_on_order', 'location', 'last_inventory_update', 'standard_package_qty', 'created_at', 'stocking_notes', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PartId' => 1, 'IsDefaultVariant' => 2, 'ManufacturerSku' => 3, 'InternalSku' => 4, 'UseDefaultUnits' => 5, 'Units' => 6, 'UseDefaultCosting' => 7, 'CostCalculationMethod' => 8, 'UnitCost' => 9, 'UseDefaultPricing' => 10, 'UnitPrice' => 11, 'MarkupAmount' => 12, 'MarkupPercent' => 13, 'TaxableHst' => 14, 'TaxableGst' => 15, 'TaxablePst' => 16, 'EnviroLevy' => 17, 'BatteryLevy' => 18, 'UseDefaultDimensions' => 19, 'ShippingWeight' => 20, 'ShippingWidth' => 21, 'ShippingHeight' => 22, 'ShippingDepth' => 23, 'ShippingVolume' => 24, 'UseDefaultInventory' => 25, 'TrackInventory' => 26, 'MinimumOnHand' => 27, 'MaximumOnHand' => 28, 'CurrentOnHand' => 29, 'CurrentOnHold' => 30, 'CurrentOnOrder' => 31, 'Location' => 32, 'LastInventoryUpdate' => 33, 'StandardPackageQty' => 34, 'CreatedAt' => 35, 'StockingNotes' => 36, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'partId' => 1, 'isDefaultVariant' => 2, 'manufacturerSku' => 3, 'internalSku' => 4, 'useDefaultUnits' => 5, 'units' => 6, 'useDefaultCosting' => 7, 'costCalculationMethod' => 8, 'unitCost' => 9, 'useDefaultPricing' => 10, 'unitPrice' => 11, 'markupAmount' => 12, 'markupPercent' => 13, 'taxableHst' => 14, 'taxableGst' => 15, 'taxablePst' => 16, 'enviroLevy' => 17, 'batteryLevy' => 18, 'useDefaultDimensions' => 19, 'shippingWeight' => 20, 'shippingWidth' => 21, 'shippingHeight' => 22, 'shippingDepth' => 23, 'shippingVolume' => 24, 'useDefaultInventory' => 25, 'trackInventory' => 26, 'minimumOnHand' => 27, 'maximumOnHand' => 28, 'currentOnHand' => 29, 'currentOnHold' => 30, 'currentOnOrder' => 31, 'location' => 32, 'lastInventoryUpdate' => 33, 'standardPackageQty' => 34, 'createdAt' => 35, 'stockingNotes' => 36, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::PART_ID => 1, self::IS_DEFAULT_VARIANT => 2, self::MANUFACTURER_SKU => 3, self::INTERNAL_SKU => 4, self::USE_DEFAULT_UNITS => 5, self::UNITS => 6, self::USE_DEFAULT_COSTING => 7, self::COST_CALCULATION_METHOD => 8, self::UNIT_COST => 9, self::USE_DEFAULT_PRICING => 10, self::UNIT_PRICE => 11, self::MARKUP_AMOUNT => 12, self::MARKUP_PERCENT => 13, self::TAXABLE_HST => 14, self::TAXABLE_GST => 15, self::TAXABLE_PST => 16, self::ENVIRO_LEVY => 17, self::BATTERY_LEVY => 18, self::USE_DEFAULT_DIMENSIONS => 19, self::SHIPPING_WEIGHT => 20, self::SHIPPING_WIDTH => 21, self::SHIPPING_HEIGHT => 22, self::SHIPPING_DEPTH => 23, self::SHIPPING_VOLUME => 24, self::USE_DEFAULT_INVENTORY => 25, self::TRACK_INVENTORY => 26, self::MINIMUM_ON_HAND => 27, self::MAXIMUM_ON_HAND => 28, self::CURRENT_ON_HAND => 29, self::CURRENT_ON_HOLD => 30, self::CURRENT_ON_ORDER => 31, self::LOCATION => 32, self::LAST_INVENTORY_UPDATE => 33, self::STANDARD_PACKAGE_QTY => 34, self::CREATED_AT => 35, self::STOCKING_NOTES => 36, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'part_id' => 1, 'is_default_variant' => 2, 'manufacturer_sku' => 3, 'internal_sku' => 4, 'use_default_units' => 5, 'units' => 6, 'use_default_costing' => 7, 'cost_calculation_method' => 8, 'unit_cost' => 9, 'use_default_pricing' => 10, 'unit_price' => 11, 'markup_amount' => 12, 'markup_percent' => 13, 'taxable_hst' => 14, 'taxable_gst' => 15, 'taxable_pst' => 16, 'enviro_levy' => 17, 'battery_levy' => 18, 'use_default_dimensions' => 19, 'shipping_weight' => 20, 'shipping_width' => 21, 'shipping_height' => 22, 'shipping_depth' => 23, 'shipping_volume' => 24, 'use_default_inventory' => 25, 'track_inventory' => 26, 'minimum_on_hand' => 27, 'maximum_on_hand' => 28, 'current_on_hand' => 29, 'current_on_hold' => 30, 'current_on_order' => 31, 'location' => 32, 'last_inventory_update' => 33, 'standard_package_qty' => 34, 'created_at' => 35, 'stocking_notes' => 36, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new PartVariantMapBuilder();
		}
		return self::$mapBuilder;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. PartVariantPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(PartVariantPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(PartVariantPeer::ID);

		$criteria->addSelectColumn(PartVariantPeer::PART_ID);

		$criteria->addSelectColumn(PartVariantPeer::IS_DEFAULT_VARIANT);

		$criteria->addSelectColumn(PartVariantPeer::MANUFACTURER_SKU);

		$criteria->addSelectColumn(PartVariantPeer::INTERNAL_SKU);

		$criteria->addSelectColumn(PartVariantPeer::USE_DEFAULT_UNITS);

		$criteria->addSelectColumn(PartVariantPeer::UNITS);

		$criteria->addSelectColumn(PartVariantPeer::USE_DEFAULT_COSTING);

		$criteria->addSelectColumn(PartVariantPeer::COST_CALCULATION_METHOD);

		$criteria->addSelectColumn(PartVariantPeer::UNIT_COST);

		$criteria->addSelectColumn(PartVariantPeer::USE_DEFAULT_PRICING);

		$criteria->addSelectColumn(PartVariantPeer::UNIT_PRICE);

		$criteria->addSelectColumn(PartVariantPeer::MARKUP_AMOUNT);

		$criteria->addSelectColumn(PartVariantPeer::MARKUP_PERCENT);

		$criteria->addSelectColumn(PartVariantPeer::TAXABLE_HST);

		$criteria->addSelectColumn(PartVariantPeer::TAXABLE_GST);

		$criteria->addSelectColumn(PartVariantPeer::TAXABLE_PST);

		$criteria->addSelectColumn(PartVariantPeer::ENVIRO_LEVY);

		$criteria->addSelectColumn(PartVariantPeer::BATTERY_LEVY);

		$criteria->addSelectColumn(PartVariantPeer::USE_DEFAULT_DIMENSIONS);

		$criteria->addSelectColumn(PartVariantPeer::SHIPPING_WEIGHT);

		$criteria->addSelectColumn(PartVariantPeer::SHIPPING_WIDTH);

		$criteria->addSelectColumn(PartVariantPeer::SHIPPING_HEIGHT);

		$criteria->addSelectColumn(PartVariantPeer::SHIPPING_DEPTH);

		$criteria->addSelectColumn(PartVariantPeer::SHIPPING_VOLUME);

		$criteria->addSelectColumn(PartVariantPeer::USE_DEFAULT_INVENTORY);

		$criteria->addSelectColumn(PartVariantPeer::TRACK_INVENTORY);

		$criteria->addSelectColumn(PartVariantPeer::MINIMUM_ON_HAND);

		$criteria->addSelectColumn(PartVariantPeer::MAXIMUM_ON_HAND);

		$criteria->addSelectColumn(PartVariantPeer::CURRENT_ON_HAND);

		$criteria->addSelectColumn(PartVariantPeer::CURRENT_ON_HOLD);

		$criteria->addSelectColumn(PartVariantPeer::CURRENT_ON_ORDER);

		$criteria->addSelectColumn(PartVariantPeer::LOCATION);

		$criteria->addSelectColumn(PartVariantPeer::LAST_INVENTORY_UPDATE);

		$criteria->addSelectColumn(PartVariantPeer::STANDARD_PACKAGE_QTY);

		$criteria->addSelectColumn(PartVariantPeer::CREATED_AT);

		$criteria->addSelectColumn(PartVariantPeer::STOCKING_NOTES);

	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartVariantPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartVariantPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BasePartVariantPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $criteria, $con);
    }


		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     PartVariant
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = PartVariantPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return PartVariantPeer::populateObjects(PartVariantPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasePartVariantPeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			PartVariantPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      PartVariant $value A PartVariant object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(PartVariant $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A PartVariant object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof PartVariant) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PartVariant object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     PartVariant Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol + 0] === null) {
			return null;
		}
		return (string) $row[$startcol + 0];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = PartVariantPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = PartVariantPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = PartVariantPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				PartVariantPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Part table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinPart(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartVariantPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartVariantPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartVariantPeer::PART_ID,), array(PartPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartVariantPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of PartVariant objects pre-filled with their Part objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartVariant objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinPart(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BasePartVariantPeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartVariantPeer::addSelectColumns($c);
		$startcol = (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);
		PartPeer::addSelectColumns($c);

		$c->addJoin(array(PartVariantPeer::PART_ID,), array(PartPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartVariantPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartVariantPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartVariantPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartVariantPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = PartPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = PartPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = PartPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					PartPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartVariant) to $obj2 (Part)
				$obj2->addPartVariant($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartVariantPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartVariantPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartVariantPeer::PART_ID,), array(PartPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartVariantPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}

	/**
	 * Selects a collection of PartVariant objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartVariant objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BasePartVariantPeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartVariantPeer::addSelectColumns($c);
		$startcol2 = (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		PartPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartPeer::NUM_COLUMNS - PartPeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(PartVariantPeer::PART_ID,), array(PartPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartVariantPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartVariantPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartVariantPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartVariantPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined Part rows

			$key2 = PartPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = PartPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = PartPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (PartVariant) to the collection in $obj2 (Part)
				$obj2->addPartVariant($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


  static public function getUniqueColumnNames()
  {
    return array();
  }
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return PartVariantPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a PartVariant or Criteria object.
	 *
	 * @param      mixed $values Criteria or PartVariant object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasePartVariantPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BasePartVariantPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from PartVariant object
		}

		if ($criteria->containsKey(PartVariantPeer::ID) && $criteria->keyContainsValue(PartVariantPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.PartVariantPeer::ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BasePartVariantPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a PartVariant or Criteria object.
	 *
	 * @param      mixed $values Criteria or PartVariant object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasePartVariantPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BasePartVariantPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(PartVariantPeer::ID);
			$selectCriteria->add(PartVariantPeer::ID, $criteria->remove(PartVariantPeer::ID), $comparison);

		} else { // $values is PartVariant object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BasePartVariantPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BasePartVariantPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the part_variant table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(PartVariantPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a PartVariant or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or PartVariant object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			PartVariantPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof PartVariant) {
			// invalidate the cache for this single object
			PartVariantPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(PartVariantPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				PartVariantPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given PartVariant object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      PartVariant $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(PartVariant $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(PartVariantPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(PartVariantPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(PartVariantPeer::DATABASE_NAME, PartVariantPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        foreach ($res as $failed) {
            $col = PartVariantPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     PartVariant
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = PartVariantPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
		$criteria->add(PartVariantPeer::ID, $pk);

		$v = PartVariantPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartVariantPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(PartVariantPeer::DATABASE_NAME);
			$criteria->add(PartVariantPeer::ID, $pks, Criteria::IN);
			$objs = PartVariantPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BasePartVariantPeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the PartVariantPeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the PartVariantPeer class:
//
// Propel::getDatabaseMap(PartVariantPeer::DATABASE_NAME)->addTableBuilder(PartVariantPeer::TABLE_NAME, PartVariantPeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BasePartVariantPeer::DATABASE_NAME)->addTableBuilder(BasePartVariantPeer::TABLE_NAME, BasePartVariantPeer::getMapBuilder());

