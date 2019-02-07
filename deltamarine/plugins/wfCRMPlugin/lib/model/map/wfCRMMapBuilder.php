<?php


/**
 * This class adds structure of 'wf_crm' table to 'propel' DatabaseMap object.
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
class wfCRMMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.wfCRMPlugin.lib.model.map.wfCRMMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(wfCRMPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(wfCRMPeer::TABLE_NAME);
		$tMap->setPhpName('wfCRM');
		$tMap->setClassname('wfCRM');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('TREE_LEFT', 'TreeLeft', 'INTEGER', false, null);

		$tMap->addColumn('TREE_RIGHT', 'TreeRight', 'INTEGER', false, null);

		$tMap->addForeignKey('PARENT_NODE_ID', 'ParentNodeId', 'INTEGER', 'wf_crm', 'ID', false, null);

		$tMap->addColumn('TREE_ID', 'TreeId', 'INTEGER', false, null);

		$tMap->addColumn('DEPARTMENT_NAME', 'DepartmentName', 'VARCHAR', false, 255);

		$tMap->addColumn('FIRST_NAME', 'FirstName', 'VARCHAR', false, 255);

		$tMap->addColumn('MIDDLE_NAME', 'MiddleName', 'VARCHAR', false, 255);

		$tMap->addColumn('LAST_NAME', 'LastName', 'VARCHAR', false, 255);

		$tMap->addColumn('SALUTATION', 'Salutation', 'VARCHAR', false, 64);

		$tMap->addColumn('TITLES', 'Titles', 'VARCHAR', false, 255);

		$tMap->addColumn('JOB_TITLE', 'JobTitle', 'VARCHAR', false, 255);

		$tMap->addColumn('ALPHA_NAME', 'AlphaName', 'VARCHAR', false, 255);

		$tMap->addColumn('EMAIL', 'Email', 'VARCHAR', false, 255);

		$tMap->addColumn('WORK_PHONE', 'WorkPhone', 'VARCHAR', false, 64);

		$tMap->addColumn('MOBILE_PHONE', 'MobilePhone', 'VARCHAR', false, 64);

		$tMap->addColumn('HOME_PHONE', 'HomePhone', 'VARCHAR', false, 64);

		$tMap->addColumn('FAX', 'Fax', 'VARCHAR', false, 64);

		$tMap->addColumn('HOMEPAGE', 'Homepage', 'VARCHAR', false, 255);

		$tMap->addColumn('PRIVATE_NOTES', 'PrivateNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('PUBLIC_NOTES', 'PublicNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('IS_COMPANY', 'IsCompany', 'BOOLEAN', true, null);

		$tMap->addColumn('IS_IN_ADDRESSBOOK', 'IsInAddressbook', 'BOOLEAN', true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // wfCRMMapBuilder
