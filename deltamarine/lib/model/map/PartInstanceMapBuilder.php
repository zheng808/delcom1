<?php


/**
 * This class adds structure of 'part_instance' table to 'propel' DatabaseMap object.
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
class PartInstanceMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PartInstanceMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(PartInstancePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PartInstancePeer::TABLE_NAME);
		$tMap->setPhpName('PartInstance');
		$tMap->setClassname('PartInstance');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PART_VARIANT_ID', 'PartVariantId', 'INTEGER', 'part_variant', 'ID', false, null);

		$tMap->addColumn('CUSTOM_NAME', 'CustomName', 'VARCHAR', false, 255);

		$tMap->addColumn('CUSTOM_ORIGIN', 'CustomOrigin', 'VARCHAR', false, 255);

		$tMap->addColumn('QUANTITY', 'Quantity', 'DECIMAL', true, 8);

		$tMap->addColumn('UNIT_PRICE', 'UnitPrice', 'DECIMAL', true, 8);

		$tMap->addColumn('UNIT_COST', 'UnitCost', 'DECIMAL', false, 8);

		$tMap->addColumn('TAXABLE_HST', 'TaxableHst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_GST', 'TaxableGst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_PST', 'TaxablePst', 'DECIMAL', true, 8);

		$tMap->addColumn('ENVIRO_LEVY', 'EnviroLevy', 'DECIMAL', true, 8);

		$tMap->addColumn('BATTERY_LEVY', 'BatteryLevy', 'DECIMAL', true, 8);

		$tMap->addForeignKey('SUPPLIER_ORDER_ITEM_ID', 'SupplierOrderItemId', 'INTEGER', 'supplier_order_item', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_ITEM_ID', 'WorkorderItemId', 'INTEGER', 'workorder_item', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_INVOICE_ID', 'WorkorderInvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

		$tMap->addForeignKey('ADDED_BY', 'AddedBy', 'INTEGER', 'employee', 'ID', false, null);

		$tMap->addColumn('ESTIMATE', 'Estimate', 'BOOLEAN', true, null);

		$tMap->addColumn('ALLOCATED', 'Allocated', 'BOOLEAN', true, null);

		$tMap->addColumn('DELIVERED', 'Delivered', 'BOOLEAN', true, null);

		$tMap->addColumn('SERIAL_NUMBER', 'SerialNumber', 'VARCHAR', false, 255);

		$tMap->addColumn('DATE_USED', 'DateUsed', 'TIMESTAMP', false, null);

		$tMap->addColumn('IS_INVENTORY_ADJUSTMENT', 'IsInventoryAdjustment', 'BOOLEAN', true, null);

		$tMap->addColumn('INTERNAL_NOTES', 'InternalNotes', 'LONGVARCHAR', false, null);

	} // doBuild()

} // PartInstanceMapBuilder
