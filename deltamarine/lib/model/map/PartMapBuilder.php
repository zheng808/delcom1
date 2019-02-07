<?php


/**
 * This class adds structure of 'part' table to 'propel' DatabaseMap object.
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
class PartMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PartMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(PartPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PartPeer::TABLE_NAME);
		$tMap->setPhpName('Part');
		$tMap->setClassname('Part');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PART_CATEGORY_ID', 'PartCategoryId', 'INTEGER', 'part_category', 'ID', false, null);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', true, 255);

		$tMap->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', false, null);

		$tMap->addColumn('HAS_SERIAL_NUMBER', 'HasSerialNumber', 'BOOLEAN', true, null);

		$tMap->addColumn('IS_MULTISKU', 'IsMultisku', 'BOOLEAN', true, null);

		$tMap->addForeignKey('MANUFACTURER_ID', 'ManufacturerId', 'INTEGER', 'manufacturer', 'ID', false, null);

		$tMap->addColumn('ACTIVE', 'Active', 'BOOLEAN', true, null);

		$tMap->addColumn('ORIGIN', 'Origin', 'VARCHAR', false, 255);

	} // doBuild()

} // PartMapBuilder
