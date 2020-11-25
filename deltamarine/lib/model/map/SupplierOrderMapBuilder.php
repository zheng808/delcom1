<?php


/**
 * This class adds structure of 'supplier_order' table to 'propel' DatabaseMap object.
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
class SupplierOrderMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SupplierOrderMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(SupplierOrderPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(SupplierOrderPeer::TABLE_NAME);
		$tMap->setPhpName('SupplierOrder');
		$tMap->setClassname('SupplierOrder');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('SUPPLIER_ID', 'SupplierId', 'INTEGER', 'supplier', 'ID', false, null);

		$tMap->addColumn('PURCHASE_ORDER', 'PurchaseOrder', 'VARCHAR', false, 127);

		$tMap->addColumn('NOTES', 'Notes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('DATE_ORDERED', 'DateOrdered', 'TIMESTAMP', false, null);

		$tMap->addColumn('DATE_EXPECTED', 'DateExpected', 'TIMESTAMP', false, null);

		$tMap->addColumn('DATE_RECEIVED', 'DateReceived', 'TIMESTAMP', false, null);

		$tMap->addColumn('FINALIZED', 'Finalized', 'BOOLEAN', true, null);

		$tMap->addColumn('APPROVED', 'Approved', 'BOOLEAN', true, null);

		$tMap->addColumn('SENT', 'Sent', 'BOOLEAN', true, null);

		$tMap->addColumn('RECEIVED_SOME', 'ReceivedSome', 'BOOLEAN', true, null);

		$tMap->addColumn('RECEIVED_ALL', 'ReceivedAll', 'BOOLEAN', true, null);

		$tMap->addForeignKey('INVOICE_ID', 'InvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

	} // doBuild()

} // SupplierOrderMapBuilder
