<?php


/**
 * This class adds structure of 'customer' table to 'propel' DatabaseMap object.
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
class CustomerMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CustomerMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CustomerPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CustomerPeer::TABLE_NAME);
		$tMap->setPhpName('Customer');
		$tMap->setClassname('Customer');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WF_CRM_ID', 'WfCrmId', 'INTEGER', 'wf_crm', 'ID', true, null);

		$tMap->addForeignKey('GUARD_USER_ID', 'GuardUserId', 'INTEGER', 'sf_guard_user', 'ID', false, null);

		$tMap->addColumn('PST_NUMBER', 'PstNumber', 'VARCHAR', false, 255);

		$tMap->addColumn('HIDDEN', 'Hidden', 'BOOLEAN', true, null);

	} // doBuild()

} // CustomerMapBuilder
