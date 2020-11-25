<?php


/**
 * This class adds structure of 'shipment' table to 'propel' DatabaseMap object.
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
class ShipmentMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ShipmentMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(ShipmentPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ShipmentPeer::TABLE_NAME);
		$tMap->setPhpName('Shipment');
		$tMap->setClassname('Shipment');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('CARRIER', 'Carrier', 'VARCHAR', false, 64);

		$tMap->addColumn('TRACKING_NUMBER', 'TrackingNumber', 'VARCHAR', false, 127);

		$tMap->addColumn('DATE_SHIPPED', 'DateShipped', 'TIMESTAMP', true, null);

		$tMap->addForeignKey('INVOICE_ID', 'InvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

	} // doBuild()

} // ShipmentMapBuilder
