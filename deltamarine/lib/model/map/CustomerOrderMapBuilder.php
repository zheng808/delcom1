<?php


/**
 * This class adds structure of 'customer_order' table to 'propel' DatabaseMap object.
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
class CustomerOrderMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerOrderMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerOrderPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerOrderPeer::TABLE_NAME);
		$tMap->setPhpName('CustomerOrder');
		$tMap->setClassname('CustomerOrder');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', false, null);

		$tMap->addColumn('FINALIZED', 'Finalized', 'BOOLEAN', true, null);

		$tMap->addColumn('APPROVED', 'Approved', 'BOOLEAN', true, null);

		$tMap->addColumn('SENT_SOME', 'SentSome', 'BOOLEAN', true, null);

		$tMap->addColumn('SENT_ALL', 'SentAll', 'BOOLEAN', true, null);

		$tMap->addColumn('INVOICE_PER_SHIPMENT', 'InvoicePerShipment', 'BOOLEAN', true, null);

		$tMap->addForeignKey('INVOICE_ID', 'InvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

		$tMap->addColumn('DATE_ORDERED', 'DateOrdered', 'TIMESTAMP', false, null);

		$tMap->addColumn('HST_EXEMPT', 'HstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('GST_EXEMPT', 'GstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('PST_EXEMPT', 'PstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('FOR_RIGGING', 'ForRigging', 'BOOLEAN', true, null);

		$tMap->addColumn('DISCOUNT_PCT', 'DiscountPct', 'TINYINT', true, null);

		$tMap->addColumn('PO_NUM', 'PoNum', 'VARCHAR', false, 127);

		$tMap->addColumn('BOAT_NAME', 'BoatName', 'VARCHAR', false, 127);

	} // doBuild()

} // CustomerOrderMapBuilder
