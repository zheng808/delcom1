<?php


/**
 * This class adds structure of 'customer_return_item' table to 'propel' DatabaseMap object.
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
class CustomerReturnItemMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerReturnItemMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerReturnItemPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerReturnItemPeer::TABLE_NAME);
		$tMap->setPhpName('CustomerReturnItem');
		$tMap->setClassname('CustomerReturnItem');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_RETURN_ID', 'CustomerReturnId', 'INTEGER', 'customer_return', 'ID', false, null);

		$tMap->addForeignKey('CUSTOMER_ORDER_ITEM_ID', 'CustomerOrderItemId', 'INTEGER', 'customer_order_item', 'ID', false, null);

		$tMap->addForeignKey('PART_INSTANCE_ID', 'PartInstanceId', 'INTEGER', 'part_instance', 'ID', false, null);

	} // doBuild()

} // CustomerReturnItemMapBuilder
