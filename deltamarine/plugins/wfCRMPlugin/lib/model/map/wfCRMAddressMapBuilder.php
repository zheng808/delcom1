<?php


/**
 * This class adds structure of 'wf_crm_address' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.wfCRMPlugin.lib.model.map
 */
class wfCRMAddressMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.wfCRMPlugin.lib.model.map.wfCRMAddressMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(wfCRMAddressPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(wfCRMAddressPeer::TABLE_NAME);
		$tMap->setPhpName('wfCRMAddress');
		$tMap->setClassname('wfCRMAddress');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CRM_ID', 'CrmId', 'INTEGER', 'wf_crm', 'ID', false, null);

		$tMap->addColumn('TYPE', 'Type', 'VARCHAR', false, 255);

		$tMap->addColumn('LINE1', 'Line1', 'VARCHAR', false, 255);

		$tMap->addColumn('LINE2', 'Line2', 'VARCHAR', false, 255);

		$tMap->addColumn('CITY', 'City', 'VARCHAR', false, 128);

		$tMap->addColumn('REGION', 'Region', 'VARCHAR', false, 128);

		$tMap->addColumn('POSTAL', 'Postal', 'VARCHAR', false, 16);

		$tMap->addColumn('COUNTRY', 'Country', 'VARCHAR', true, 2);

	} // doBuild()

} // wfCRMAddressMapBuilder
