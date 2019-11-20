<?php

/**
 * Base static class for performing query and update operations on the 'part_instance' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BasePartInstancePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'part_instance';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.PartInstance';

	/** The total number of columns. */
	const NUM_COLUMNS = 26;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'part_instance.ID';

	/** the column name for the PART_VARIANT_ID field */
	const PART_VARIANT_ID = 'part_instance.PART_VARIANT_ID';

	/** the column name for the CUSTOM_NAME field */
	const CUSTOM_NAME = 'part_instance.CUSTOM_NAME';

	/** the column name for the CUSTOM_ORIGIN field */
	const CUSTOM_ORIGIN = 'part_instance.CUSTOM_ORIGIN';

	/** the column name for the QUANTITY field */
	const QUANTITY = 'part_instance.QUANTITY';

	/** the column name for the SUB_CONTRACTOR_FLG field */
	const SUB_CONTRACTOR_FLG = 'part_instance.SUB_CONTRACTOR_FLG';

	/** the column name for the BROKER_FEES field */
	const BROKER_FEES = 'part_instance.BROKER_FEES';

	/** the column name for the SHIPPING_FEES field */
	const SHIPPING_FEES = 'part_instance.SHIPPING_FEES';

	/** the column name for the UNIT_PRICE field */
	const UNIT_PRICE = 'part_instance.UNIT_PRICE';

	/** the column name for the UNIT_COST field */
	const UNIT_COST = 'part_instance.UNIT_COST';

	/** the column name for the TAXABLE_HST field */
	const TAXABLE_HST = 'part_instance.TAXABLE_HST';

	/** the column name for the TAXABLE_GST field */
	const TAXABLE_GST = 'part_instance.TAXABLE_GST';

	/** the column name for the TAXABLE_PST field */
	const TAXABLE_PST = 'part_instance.TAXABLE_PST';

	/** the column name for the ENVIRO_LEVY field */
	const ENVIRO_LEVY = 'part_instance.ENVIRO_LEVY';

	/** the column name for the BATTERY_LEVY field */
	const BATTERY_LEVY = 'part_instance.BATTERY_LEVY';

	/** the column name for the SUPPLIER_ORDER_ITEM_ID field */
	const SUPPLIER_ORDER_ITEM_ID = 'part_instance.SUPPLIER_ORDER_ITEM_ID';

	/** the column name for the WORKORDER_ITEM_ID field */
	const WORKORDER_ITEM_ID = 'part_instance.WORKORDER_ITEM_ID';

	/** the column name for the WORKORDER_INVOICE_ID field */
	const WORKORDER_INVOICE_ID = 'part_instance.WORKORDER_INVOICE_ID';

	/** the column name for the ADDED_BY field */
	const ADDED_BY = 'part_instance.ADDED_BY';

	/** the column name for the ESTIMATE field */
	const ESTIMATE = 'part_instance.ESTIMATE';

	/** the column name for the ALLOCATED field */
	const ALLOCATED = 'part_instance.ALLOCATED';

	/** the column name for the DELIVERED field */
	const DELIVERED = 'part_instance.DELIVERED';

	/** the column name for the SERIAL_NUMBER field */
	const SERIAL_NUMBER = 'part_instance.SERIAL_NUMBER';

	/** the column name for the DATE_USED field */
	const DATE_USED = 'part_instance.DATE_USED';

	/** the column name for the IS_INVENTORY_ADJUSTMENT field */
	const IS_INVENTORY_ADJUSTMENT = 'part_instance.IS_INVENTORY_ADJUSTMENT';

	/** the column name for the INTERNAL_NOTES field */
	const INTERNAL_NOTES = 'part_instance.INTERNAL_NOTES';

	/**
	 * An identiy map to hold any loaded instances of PartInstance objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array PartInstance[]
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
		BasePeer::TYPE_PHPNAME => array ('Id', 'PartVariantId', 'CustomName', 'CustomOrigin', 'Quantity', 'BrokerFees', 'ShippingFees', 'UnitPrice', 'UnitCost', 'TaxableHst', 'TaxableGst', 'TaxablePst', 'EnviroLevy', 'BatteryLevy', 'SupplierOrderItemId', 'WorkorderItemId', 'WorkorderInvoiceId', 'AddedBy', 'Estimate', 'Allocated', 'Delivered', 'SerialNumber', 'DateUsed', 'IsInventoryAdjustment', 'InternalNotes', 'SubContractorFlg', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'partVariantId', 'customName', 'customOrigin', 'quantity', 'BrokerFees', 'ShippingFees', 'unitPrice', 'unitCost', 'taxableHst', 'taxableGst', 'taxablePst', 'enviroLevy', 'batteryLevy', 'supplierOrderItemId', 'workorderItemId', 'workorderInvoiceId', 'addedBy', 'estimate', 'allocated', 'delivered', 'serialNumber', 'dateUsed', 'isInventoryAdjustment', 'internalNotes', 'subContractorFlg', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::PART_VARIANT_ID, self::CUSTOM_NAME, self::CUSTOM_ORIGIN, self::QUANTITY, self::BROKER_FEES, self::SHIPPING_FEES, self::UNIT_PRICE, self::UNIT_COST, self::TAXABLE_HST, self::TAXABLE_GST, self::TAXABLE_PST, self::ENVIRO_LEVY, self::BATTERY_LEVY, self::SUPPLIER_ORDER_ITEM_ID, self::WORKORDER_ITEM_ID, self::WORKORDER_INVOICE_ID, self::ADDED_BY, self::ESTIMATE, self::ALLOCATED, self::DELIVERED, self::SERIAL_NUMBER, self::DATE_USED, self::IS_INVENTORY_ADJUSTMENT, self::INTERNAL_NOTES, self::SUB_CONTRACTOR_FLG, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'part_variant_id', 'custom_name', 'custom_origin', 'quantity', 'broker_fees', 'shipping_fees', 'unit_price', 'unit_cost', 'taxable_hst', 'taxable_gst', 'taxable_pst', 'enviro_levy', 'battery_levy', 'supplier_order_item_id', 'workorder_item_id', 'workorder_invoice_id', 'added_by', 'estimate', 'allocated', 'delivered', 'serial_number', 'date_used', 'is_inventory_adjustment', 'internal_notes', 'sub_contractor_flg', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PartVariantId' => 1, 'CustomName' => 2, 'CustomOrigin' => 3, 'Quantity' => 4, 'BrokerFees' => 5, 'ShippingFees' => 6, 'UnitPrice' => 7, 'UnitCost' => 8, 'TaxableHst' => 9, 'TaxableGst' => 10, 'TaxablePst' => 11, 'EnviroLevy' => 12, 'BatteryLevy' => 13, 'SupplierOrderItemId' => 14, 'WorkorderItemId' => 15, 'WorkorderInvoiceId' => 16, 'AddedBy' => 17, 'Estimate' => 18, 'Allocated' => 19, 'Delivered' => 20, 'SerialNumber' => 21, 'DateUsed' => 22, 'IsInventoryAdjustment' => 23, 'InternalNotes' => 24, 'SubContractorFlg' => 25, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'partVariantId' => 1, 'customName' => 2, 'customOrigin' => 3, 'quantity' => 4, 'brokerFees' => 5, 'shippingFees' => 6, 'unitPrice' => 7, 'unitCost' => 8, 'taxableHst' => 9, 'taxableGst' => 10, 'taxablePst' => 11, 'enviroLevy' => 12, 'batteryLevy' => 13, 'supplierOrderItemId' => 14, 'workorderItemId' => 15, 'workorderInvoiceId' => 16, 'addedBy' => 17, 'estimate' => 18, 'allocated' => 19, 'delivered' => 20, 'serialNumber' => 21, 'dateUsed' => 22, 'isInventoryAdjustment' => 23, 'internalNotes' => 24, 'subContractorFlg' => 25, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::PART_VARIANT_ID => 1, self::CUSTOM_NAME => 2, self::CUSTOM_ORIGIN => 3, self::QUANTITY => 4, self::BROKER_FEES => 5, self::SHIPPING_FEES => 6, self::UNIT_PRICE => 7, self::UNIT_COST => 8, self::TAXABLE_HST => 9, self::TAXABLE_GST => 10, self::TAXABLE_PST => 11, self::ENVIRO_LEVY => 12, self::BATTERY_LEVY => 13, self::SUPPLIER_ORDER_ITEM_ID => 14, self::WORKORDER_ITEM_ID => 15, self::WORKORDER_INVOICE_ID => 16, self::ADDED_BY => 17, self::ESTIMATE => 18, self::ALLOCATED => 19, self::DELIVERED => 20, self::SERIAL_NUMBER => 21, self::DATE_USED => 22, self::IS_INVENTORY_ADJUSTMENT => 23, self::INTERNAL_NOTES => 24, self::SUB_CONTRACTOR_FLG => 25, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'part_variant_id' => 1, 'custom_name' => 2, 'custom_origin' => 3, 'quantity' => 4, 'broker_fees' => 5, 'shipping_fees' => 6, 'unit_price' => 7, 'unit_cost' => 8, 'taxable_hst' => 9, 'taxable_gst' => 10, 'taxable_pst' => 11, 'enviro_levy' => 12, 'battery_levy' => 13, 'supplier_order_item_id' => 14, 'workorder_item_id' => 15, 'workorder_invoice_id' => 16, 'added_by' => 17, 'estimate' => 18, 'allocated' => 19, 'delivered' => 20, 'serial_number' => 21, 'date_used' => 22, 'is_inventory_adjustment' => 23, 'internal_notes' => 24, 'sub_contractor_flg' => 25, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new PartInstanceMapBuilder();
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
	 * @param      string $column The column name for current table. (i.e. PartInstancePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(PartInstancePeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(PartInstancePeer::ID);

		$criteria->addSelectColumn(PartInstancePeer::PART_VARIANT_ID);

		$criteria->addSelectColumn(PartInstancePeer::CUSTOM_NAME);

		$criteria->addSelectColumn(PartInstancePeer::CUSTOM_ORIGIN);

		$criteria->addSelectColumn(PartInstancePeer::QUANTITY);

		$criteria->addSelectColumn(PartInstancePeer::BROKER_FEES);

		$criteria->addSelectColumn(PartInstancePeer::SHIPPING_FEES);

		$criteria->addSelectColumn(PartInstancePeer::UNIT_PRICE);

		$criteria->addSelectColumn(PartInstancePeer::UNIT_COST);

		$criteria->addSelectColumn(PartInstancePeer::TAXABLE_HST);

		$criteria->addSelectColumn(PartInstancePeer::TAXABLE_GST);

		$criteria->addSelectColumn(PartInstancePeer::TAXABLE_PST);

		$criteria->addSelectColumn(PartInstancePeer::ENVIRO_LEVY);

		$criteria->addSelectColumn(PartInstancePeer::BATTERY_LEVY);

		$criteria->addSelectColumn(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID);

		$criteria->addSelectColumn(PartInstancePeer::WORKORDER_ITEM_ID);

		$criteria->addSelectColumn(PartInstancePeer::WORKORDER_INVOICE_ID);

		$criteria->addSelectColumn(PartInstancePeer::ADDED_BY);

		$criteria->addSelectColumn(PartInstancePeer::ESTIMATE);

		$criteria->addSelectColumn(PartInstancePeer::ALLOCATED);

		$criteria->addSelectColumn(PartInstancePeer::DELIVERED);

		$criteria->addSelectColumn(PartInstancePeer::SERIAL_NUMBER);

		$criteria->addSelectColumn(PartInstancePeer::DATE_USED);

		$criteria->addSelectColumn(PartInstancePeer::IS_INVENTORY_ADJUSTMENT);

		$criteria->addSelectColumn(PartInstancePeer::INTERNAL_NOTES);

		$criteria->addSelectColumn(PartInstancePeer::SUB_CONTRACTOR_FLG);

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
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * @return     PartInstance
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = PartInstancePeer::doSelect($critcopy, $con);
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
		return PartInstancePeer::populateObjects(PartInstancePeer::doSelectStmt($criteria, $con));
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

    foreach (sfMixer::getCallables('BasePartInstancePeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			PartInstancePeer::addSelectColumns($criteria);
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
	 * @param      PartInstance $value A PartInstance object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(PartInstance $obj, $key = null)
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
	 * @param      mixed $value A PartInstance object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof PartInstance) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PartInstance object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     PartInstance Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
		$cls = PartInstancePeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = PartInstancePeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				PartInstancePeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related PartVariant table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinPartVariant(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related SupplierOrderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinSupplierOrderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related WorkorderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinWorkorderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related Invoice table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinInvoice(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related Employee table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinEmployee(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Selects a collection of PartInstance objects pre-filled with their PartVariant objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinPartVariant(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BasePartInstancePeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
		PartVariantPeer::addSelectColumns($c);

		$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = PartVariantPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = PartVariantPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartInstance) to $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with their SupplierOrderItem objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinSupplierOrderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
		SupplierOrderItemPeer::addSelectColumns($c);

		$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = SupplierOrderItemPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = SupplierOrderItemPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					SupplierOrderItemPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartInstance) to $obj2 (SupplierOrderItem)
				$obj2->addPartInstance($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with their WorkorderItem objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinWorkorderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
		WorkorderItemPeer::addSelectColumns($c);

		$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = WorkorderItemPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = WorkorderItemPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					WorkorderItemPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartInstance) to $obj2 (WorkorderItem)
				$obj2->addPartInstance($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with their Invoice objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinInvoice(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
		InvoicePeer::addSelectColumns($c);

		$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = InvoicePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = InvoicePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					InvoicePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartInstance) to $obj2 (Invoice)
				$obj2->addPartInstance($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with their Employee objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinEmployee(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
		EmployeePeer::addSelectColumns($c);

		$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = EmployeePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = EmployeePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (PartInstance) to $obj2 (Employee)
				$obj2->addPartInstance($obj1);

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
		$criteria->setPrimaryTableName(PartInstancePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
		$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
		$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Selects a collection of PartInstance objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BasePartInstancePeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		PartVariantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		SupplierOrderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (SupplierOrderItemPeer::NUM_COLUMNS - SupplierOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol7 = $startcol6 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
		$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
		$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined PartVariant rows

			$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = PartVariantPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = PartVariantPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);
			} // if joined row not null

			// Add objects for joined SupplierOrderItem rows

			$key3 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = SupplierOrderItemPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$omClass = SupplierOrderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					SupplierOrderItemPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (SupplierOrderItem)
				$obj3->addPartInstance($obj1);
			} // if joined row not null

			// Add objects for joined WorkorderItem rows

			$key4 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = WorkorderItemPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					WorkorderItemPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (WorkorderItem)
				$obj4->addPartInstance($obj1);
			} // if joined row not null

			// Add objects for joined Invoice rows

			$key5 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = InvoicePeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					InvoicePeer::addInstanceToPool($obj5, $key5);
				} // if obj5 loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Invoice)
				$obj5->addPartInstance($obj1);
			} // if joined row not null

			// Add objects for joined Employee rows

			$key6 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = EmployeePeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					EmployeePeer::addInstanceToPool($obj6, $key6);
				} // if obj6 loaded

				// Add the $obj1 (PartInstance) to the collection in $obj6 (Employee)
				$obj6->addPartInstance($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related PartVariant table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptPartVariant(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related SupplierOrderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptSupplierOrderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related WorkorderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptWorkorderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related Invoice table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptInvoice(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related Employee table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptEmployee(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			PartInstancePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BasePartInstancePeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $criteria, $con);
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
	 * Selects a collection of PartInstance objects pre-filled with all related objects except PartVariant.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptPartVariant(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BasePartInstancePeer:doSelectJoinAllExcept:doSelectJoinAllExcept') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		SupplierOrderItemPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (SupplierOrderItemPeer::NUM_COLUMNS - SupplierOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined SupplierOrderItem rows

				$key2 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = SupplierOrderItemPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = SupplierOrderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					SupplierOrderItemPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (SupplierOrderItem)
				$obj2->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (WorkorderItem)
				$obj3->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = InvoicePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (Invoice)
				$obj4->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Employee rows

				$key5 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = EmployeePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					EmployeePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Employee)
				$obj5->addPartInstance($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with all related objects except SupplierOrderItem.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptSupplierOrderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		PartVariantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined PartVariant rows

				$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = PartVariantPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = PartVariantPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (WorkorderItem)
				$obj3->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = InvoicePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (Invoice)
				$obj4->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Employee rows

				$key5 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = EmployeePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					EmployeePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Employee)
				$obj5->addPartInstance($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with all related objects except WorkorderItem.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptWorkorderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		PartVariantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		SupplierOrderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (SupplierOrderItemPeer::NUM_COLUMNS - SupplierOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined PartVariant rows

				$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = PartVariantPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = PartVariantPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined SupplierOrderItem rows

				$key3 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = SupplierOrderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = SupplierOrderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					SupplierOrderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (SupplierOrderItem)
				$obj3->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = InvoicePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (Invoice)
				$obj4->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Employee rows

				$key5 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = EmployeePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					EmployeePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Employee)
				$obj5->addPartInstance($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with all related objects except Invoice.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptInvoice(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		PartVariantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		SupplierOrderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (SupplierOrderItemPeer::NUM_COLUMNS - SupplierOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::ADDED_BY,), array(EmployeePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined PartVariant rows

				$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = PartVariantPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = PartVariantPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined SupplierOrderItem rows

				$key3 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = SupplierOrderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = SupplierOrderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					SupplierOrderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (SupplierOrderItem)
				$obj3->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key4 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = WorkorderItemPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					WorkorderItemPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (WorkorderItem)
				$obj4->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Employee rows

				$key5 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = EmployeePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					EmployeePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Employee)
				$obj5->addPartInstance($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of PartInstance objects pre-filled with all related objects except Employee.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of PartInstance objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptEmployee(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		PartInstancePeer::addSelectColumns($c);
		$startcol2 = (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);

		PartVariantPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

		SupplierOrderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (SupplierOrderItemPeer::NUM_COLUMNS - SupplierOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(PartInstancePeer::PART_VARIANT_ID,), array(PartVariantPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID,), array(SupplierOrderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(PartInstancePeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = PartInstancePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = PartInstancePeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = PartInstancePeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				PartInstancePeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined PartVariant rows

				$key2 = PartVariantPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = PartVariantPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = PartVariantPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					PartVariantPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj2 (PartVariant)
				$obj2->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined SupplierOrderItem rows

				$key3 = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = SupplierOrderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = SupplierOrderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					SupplierOrderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj3 (SupplierOrderItem)
				$obj3->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key4 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = WorkorderItemPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					WorkorderItemPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj4 (WorkorderItem)
				$obj4->addPartInstance($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key5 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = InvoicePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					InvoicePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (PartInstance) to the collection in $obj5 (Invoice)
				$obj5->addPartInstance($obj1);

			} // if joined row is not null

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
		return PartInstancePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a PartInstance or Criteria object.
	 *
	 * @param      mixed $values Criteria or PartInstance object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasePartInstancePeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BasePartInstancePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from PartInstance object
		}

		if ($criteria->containsKey(PartInstancePeer::ID) && $criteria->keyContainsValue(PartInstancePeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.PartInstancePeer::ID.')');
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

		
    foreach (sfMixer::getCallables('BasePartInstancePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a PartInstance or Criteria object.
	 *
	 * @param      mixed $values Criteria or PartInstance object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BasePartInstancePeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BasePartInstancePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(PartInstancePeer::ID);
			$selectCriteria->add(PartInstancePeer::ID, $criteria->remove(PartInstancePeer::ID), $comparison);

		} else { // $values is PartInstance object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BasePartInstancePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BasePartInstancePeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the part_instance table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(PartInstancePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a PartInstance or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or PartInstance object or primary key or array of primary keys
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
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			PartInstancePeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof PartInstance) {
			// invalidate the cache for this single object
			PartInstancePeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(PartInstancePeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				PartInstancePeer::removeInstanceFromPool($singleval);
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
	 * Validates all modified columns of given PartInstance object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      PartInstance $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(PartInstance $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(PartInstancePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(PartInstancePeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(PartInstancePeer::DATABASE_NAME, PartInstancePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        foreach ($res as $failed) {
            $col = PartInstancePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     PartInstance
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = PartInstancePeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
		$criteria->add(PartInstancePeer::ID, $pk);

		$v = PartInstancePeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(PartInstancePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(PartInstancePeer::DATABASE_NAME);
			$criteria->add(PartInstancePeer::ID, $pks, Criteria::IN);
			$objs = PartInstancePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BasePartInstancePeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the PartInstancePeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the PartInstancePeer class:
//
// Propel::getDatabaseMap(PartInstancePeer::DATABASE_NAME)->addTableBuilder(PartInstancePeer::TABLE_NAME, PartInstancePeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BasePartInstancePeer::DATABASE_NAME)->addTableBuilder(BasePartInstancePeer::TABLE_NAME, BasePartInstancePeer::getMapBuilder());

