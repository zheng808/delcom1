<?php


/**
 * This class adds structure of 'workorder_item_billable' table to 'propel' DatabaseMap object.
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
class WorkorderItemBillableMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.WorkorderItemBillableMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(WorkorderItemBillablePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(WorkorderItemBillablePeer::TABLE_NAME);
		$tMap->setPhpName('WorkorderItemBillable');
		$tMap->setClassname('WorkorderItemBillable');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WORKORDER_ITEM_ID', 'WorkorderItemId', 'INTEGER', 'workorder_item', 'ID', false, null);

		$tMap->addForeignKey('MANUFACTURER_ID', 'ManufacturerId', 'INTEGER', 'manufacturer', 'ID', false, null);

		$tMap->addForeignKey('SUPPLIER_ID', 'SupplierId', 'INTEGER', 'supplier', 'ID', false, null);

		$tMap->addColumn('MANUFACTURER_PARTS_PERCENT', 'ManufacturerPartsPercent', 'TINYINT', true, null);

		$tMap->addColumn('MANUFACTURER_LABOUR_PERCENT', 'ManufacturerLabourPercent', 'TINYINT', true, null);

		$tMap->addColumn('SUPPLIER_PARTS_PERCENT', 'SupplierPartsPercent', 'TINYINT', true, null);

		$tMap->addColumn('SUPPLIER_LABOUR_PERCENT', 'SupplierLabourPercent', 'TINYINT', true, null);

		$tMap->addColumn('IN_HOUSE_PARTS_PERCENT', 'InHousePartsPercent', 'TINYINT', true, null);

		$tMap->addColumn('IN_HOUSE_LABOUR_PERCENT', 'InHouseLabourPercent', 'TINYINT', true, null);

		$tMap->addColumn('CUSTOMER_PARTS_PERCENT', 'CustomerPartsPercent', 'TINYINT', true, null);

		$tMap->addColumn('CUSTOMER_LABOUR_PERCENT', 'CustomerLabourPercent', 'TINYINT', true, null);

		$tMap->addColumn('RECURSE', 'Recurse', 'BOOLEAN', true, null);

	} // doBuild()

} // WorkorderItemBillableMapBuilder
