<?php


/**
 * This class adds structure of 'shipment_item' table to 'propel' DatabaseMap object.
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
class ShipmentItemMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ShipmentItemMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(ShipmentItemPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ShipmentItemPeer::TABLE_NAME);
		$tMap->setPhpName('ShipmentItem');
		$tMap->setClassname('ShipmentItem');

		$tMap->setUseIdGenerator(true);

		$tMap->addForeignKey('SHIPMENT_ID', 'ShipmentId', 'INTEGER', 'shipment', 'ID', false, null);

		$tMap->addForeignKey('CUSTOMER_ORDER_ITEM_ID', 'CustomerOrderItemId', 'INTEGER', 'customer_order_item', 'ID', false, null);

		$tMap->addColumn('QUANTITY', 'Quantity', 'DECIMAL', false, 8);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

	} // doBuild()

} // ShipmentItemMapBuilder
