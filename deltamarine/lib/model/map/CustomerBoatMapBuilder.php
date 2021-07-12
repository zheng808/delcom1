<?php


/**
 * This class adds structure of 'customer_boat' table to 'propel' DatabaseMap object.
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
class CustomerBoatMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerBoatMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerBoatPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerBoatPeer::TABLE_NAME);
		$tMap->setPhpName('CustomerBoat');
		$tMap->setClassname('CustomerBoat');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', false, null);

		$tMap->addColumn('SERIAL_NUMBER', 'SerialNumber', 'VARCHAR', false, 128);

		$tMap->addColumn('MAKE', 'Make', 'VARCHAR', false, 255);

		$tMap->addColumn('MODEL', 'Model', 'VARCHAR', false, 255);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', false, 255);

		$tMap->addColumn('REGISTRATION', 'Registration', 'VARCHAR', false, 255);

		$tMap->addColumn('NOTES', 'Notes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('FIRE_DATE', 'Fire_Date ', 'TIMESTAMP', false, null);

	} // doBuild()

} // CustomerBoatMapBuilder
