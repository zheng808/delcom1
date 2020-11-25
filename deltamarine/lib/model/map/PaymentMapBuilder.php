<?php


/**
 * This class adds structure of 'payment' table to 'propel' DatabaseMap object.
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
class PaymentMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PaymentMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(PaymentPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PaymentPeer::TABLE_NAME);
		$tMap->setPhpName('Payment');
		$tMap->setClassname('Payment');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_ORDER_ID', 'CustomerOrderId', 'INTEGER', 'customer_order', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_ID', 'WorkorderId', 'INTEGER', 'workorder', 'ID', false, null);

		$tMap->addColumn('AMOUNT', 'Amount', 'DECIMAL', true, 8);

		$tMap->addColumn('TENDERED', 'Tendered', 'DECIMAL', true, 8);

		$tMap->addColumn('CHANGE', 'Change', 'DECIMAL', true, 8);

		$tMap->addColumn('PAYMENT_METHOD', 'PaymentMethod', 'VARCHAR', false, 128);

		$tMap->addColumn('PAYMENT_DETAILS', 'PaymentDetails', 'VARCHAR', false, 255);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // PaymentMapBuilder
