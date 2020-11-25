<?php


/**
 * This class adds structure of 'invoice' table to 'propel' DatabaseMap object.
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
class InvoiceMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.InvoiceMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(InvoicePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(InvoicePeer::TABLE_NAME);
		$tMap->setPhpName('Invoice');
		$tMap->setClassname('Invoice');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('RECEIVABLE', 'Receivable', 'BOOLEAN', true, null);

		$tMap->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', false, null);

		$tMap->addForeignKey('SUPPLIER_ID', 'SupplierId', 'INTEGER', 'supplier', 'ID', false, null);

		$tMap->addForeignKey('MANUFACTURER_ID', 'ManufacturerId', 'INTEGER', 'manufacturer', 'ID', false, null);

		$tMap->addColumn('SUBTOTAL', 'Subtotal', 'DECIMAL', true, 8);

		$tMap->addColumn('SHIPPING', 'Shipping', 'DECIMAL', true, 8);

		$tMap->addColumn('HST', 'Hst', 'DECIMAL', true, 8);

		$tMap->addColumn('GST', 'Gst', 'DECIMAL', true, 8);

		$tMap->addColumn('PST', 'Pst', 'DECIMAL', true, 8);

		$tMap->addColumn('ENVIRO_LEVY', 'EnviroLevy', 'DECIMAL', true, 8);

		$tMap->addColumn('BATTERY_LEVY', 'BatteryLevy', 'DECIMAL', true, 8);

		$tMap->addColumn('DUTIES', 'Duties', 'DECIMAL', true, 8);

		$tMap->addColumn('TOTAL', 'Total', 'DECIMAL', true, 8);

		$tMap->addColumn('ISSUED_DATE', 'IssuedDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('PAYABLE_DATE', 'PayableDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('ARCHIVED', 'Archived', 'BOOLEAN', true, null);

	} // doBuild()

} // InvoiceMapBuilder
