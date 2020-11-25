<?php


/**
 * This class adds structure of 'supplier' table to 'propel' DatabaseMap object.
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
class SupplierMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SupplierMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(SupplierPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(SupplierPeer::TABLE_NAME);
		$tMap->setPhpName('Supplier');
		$tMap->setClassname('Supplier');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WF_CRM_ID', 'WfCrmId', 'INTEGER', 'wf_crm', 'ID', true, null);

		$tMap->addColumn('ACCOUNT_NUMBER', 'AccountNumber', 'VARCHAR', false, 127);

		$tMap->addColumn('CREDIT_LIMIT', 'CreditLimit', 'DECIMAL', false, 8);

		$tMap->addColumn('NET_DAYS', 'NetDays', 'INTEGER', true, null);

		$tMap->addColumn('HIDDEN', 'Hidden', 'BOOLEAN', true, null);

	} // doBuild()

} // SupplierMapBuilder
