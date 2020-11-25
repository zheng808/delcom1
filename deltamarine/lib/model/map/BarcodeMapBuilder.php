<?php


/**
 * This class adds structure of 'barcode' table to 'propel' DatabaseMap object.
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
class BarcodeMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.BarcodeMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(BarcodePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(BarcodePeer::TABLE_NAME);
		$tMap->setPhpName('Barcode');
		$tMap->setClassname('Barcode');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('VALUE', 'Value', 'VARCHAR', true, 255);

		$tMap->addColumn('DEFAULT_SYMBOLOGY', 'DefaultSymbology', 'VARCHAR', false, 8);

		$tMap->addForeignKey('PART_VARIANT_ID', 'PartVariantId', 'INTEGER', 'part_variant', 'ID', false, null);

		$tMap->addForeignKey('PART_SUPPLIER_ID', 'PartSupplierId', 'INTEGER', 'part_supplier', 'ID', false, null);

	} // doBuild()

} // BarcodeMapBuilder
