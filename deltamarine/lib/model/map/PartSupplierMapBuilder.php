<?php


/**
 * This class adds structure of 'part_supplier' table to 'propel' DatabaseMap object.
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
class PartSupplierMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PartSupplierMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(PartSupplierPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PartSupplierPeer::TABLE_NAME);
		$tMap->setPhpName('PartSupplier');
		$tMap->setClassname('PartSupplier');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('PART_VARIANT_ID', 'PartVariantId', 'INTEGER', 'part_variant', 'ID', false, null);

		$tMap->addForeignKey('SUPPLIER_ID', 'SupplierId', 'INTEGER', 'supplier', 'ID', false, null);

		$tMap->addColumn('SUPPLIER_SKU', 'SupplierSku', 'VARCHAR', false, 255);

		$tMap->addColumn('NOTES', 'Notes', 'LONGVARCHAR', false, null);

	} // doBuild()

} // PartSupplierMapBuilder
