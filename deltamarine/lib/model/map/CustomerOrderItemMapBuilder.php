<?php


/**
 * This class adds structure of 'customer_order_item' table to 'propel' DatabaseMap object.
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
class CustomerOrderItemMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerOrderItemMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerOrderItemPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerOrderItemPeer::TABLE_NAME);
		$tMap->setPhpName('CustomerOrderItem');
		$tMap->setClassname('CustomerOrderItem');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_ORDER_ID', 'CustomerOrderId', 'INTEGER', 'customer_order', 'ID', false, null);

		$tMap->addForeignKey('PART_INSTANCE_ID', 'PartInstanceId', 'INTEGER', 'part_instance', 'ID', false, null);

		$tMap->addColumn('QUANTITY_COMPLETED', 'QuantityCompleted', 'DECIMAL', true, 8);

	} // doBuild()

} // CustomerOrderItemMapBuilder
