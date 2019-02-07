<?php


/**
 * This class adds structure of 'part_variant' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class PartVariantMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PartVariantMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(PartVariantPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PartVariantPeer::TABLE_NAME);
		$tMap->setPhpName('PartVariant');
		$tMap->setClassname('PartVariant');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PART_ID', 'PartId', 'INTEGER', 'part', 'ID', true, null);

		$tMap->addColumn('IS_DEFAULT_VARIANT', 'IsDefaultVariant', 'BOOLEAN', true, null);

		$tMap->addColumn('MANUFACTURER_SKU', 'ManufacturerSku', 'VARCHAR', false, 255);

		$tMap->addColumn('INTERNAL_SKU', 'InternalSku', 'VARCHAR', false, 255);

		$tMap->addColumn('USE_DEFAULT_UNITS', 'UseDefaultUnits', 'BOOLEAN', true, null);

		$tMap->addColumn('UNITS', 'Units', 'VARCHAR', false, 6);

		$tMap->addColumn('USE_DEFAULT_COSTING', 'UseDefaultCosting', 'BOOLEAN', true, null);

		$tMap->addColumn('COST_CALCULATION_METHOD', 'CostCalculationMethod', 'VARCHAR', true, 7);

		$tMap->addColumn('UNIT_COST', 'UnitCost', 'DECIMAL', false, 8);

		$tMap->addColumn('USE_DEFAULT_PRICING', 'UseDefaultPricing', 'BOOLEAN', true, null);

		$tMap->addColumn('UNIT_PRICE', 'UnitPrice', 'DECIMAL', false, 8);

		$tMap->addColumn('MARKUP_AMOUNT', 'MarkupAmount', 'DECIMAL', false, 8);

		$tMap->addColumn('MARKUP_PERCENT', 'MarkupPercent', 'INTEGER', false, null);

		$tMap->addColumn('TAXABLE_HST', 'TaxableHst', 'BOOLEAN', true, null);

		$tMap->addColumn('TAXABLE_GST', 'TaxableGst', 'BOOLEAN', true, null);

		$tMap->addColumn('TAXABLE_PST', 'TaxablePst', 'BOOLEAN', true, null);

		$tMap->addColumn('ENVIRO_LEVY', 'EnviroLevy', 'DECIMAL', false, 8);

		$tMap->addColumn('BATTERY_LEVY', 'BatteryLevy', 'DECIMAL', false, 8);

		$tMap->addColumn('USE_DEFAULT_DIMENSIONS', 'UseDefaultDimensions', 'BOOLEAN', true, null);

		$tMap->addColumn('SHIPPING_WEIGHT', 'ShippingWeight', 'DECIMAL', false, 8);

		$tMap->addColumn('SHIPPING_WIDTH', 'ShippingWidth', 'DECIMAL', false, 8);

		$tMap->addColumn('SHIPPING_HEIGHT', 'ShippingHeight', 'DECIMAL', false, 8);

		$tMap->addColumn('SHIPPING_DEPTH', 'ShippingDepth', 'DECIMAL', false, 8);

		$tMap->addColumn('SHIPPING_VOLUME', 'ShippingVolume', 'DECIMAL', false, 8);

		$tMap->addColumn('USE_DEFAULT_INVENTORY', 'UseDefaultInventory', 'BOOLEAN', true, null);

		$tMap->addColumn('TRACK_INVENTORY', 'TrackInventory', 'BOOLEAN', true, null);

		$tMap->addColumn('MINIMUM_ON_HAND', 'MinimumOnHand', 'DECIMAL', true, 8);

		$tMap->addColumn('MAXIMUM_ON_HAND', 'MaximumOnHand', 'DECIMAL', false, 8);

		$tMap->addColumn('CURRENT_ON_HAND', 'CurrentOnHand', 'DECIMAL', true, 8);

		$tMap->addColumn('CURRENT_ON_HOLD', 'CurrentOnHold', 'DECIMAL', true, 8);

		$tMap->addColumn('CURRENT_ON_ORDER', 'CurrentOnOrder', 'DECIMAL', true, 8);

		$tMap->addColumn('LOCATION', 'Location', 'VARCHAR', false, 255);

		$tMap->addColumn('LAST_INVENTORY_UPDATE', 'LastInventoryUpdate', 'TIMESTAMP', false, null);

		$tMap->addColumn('STANDARD_PACKAGE_QTY', 'StandardPackageQty', 'DECIMAL', false, 8);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('STOCKING_NOTES', 'StockingNotes', 'LONGVARCHAR', false, null);

	} // doBuild()

} // PartVariantMapBuilder
