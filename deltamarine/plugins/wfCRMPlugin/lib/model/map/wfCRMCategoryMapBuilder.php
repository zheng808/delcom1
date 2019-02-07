<?php


/**
 * This class adds structure of 'wf_crm_category' table to 'propel' DatabaseMap object.
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
class wfCRMCategoryMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.wfCRMPlugin.lib.model.map.wfCRMCategoryMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(wfCRMCategoryPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(wfCRMCategoryPeer::TABLE_NAME);
		$tMap->setPhpName('wfCRMCategory');
		$tMap->setClassname('wfCRMCategory');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('TREE_LEFT', 'TreeLeft', 'INTEGER', false, null);

		$tMap->addColumn('TREE_RIGHT', 'TreeRight', 'INTEGER', false, null);

		$tMap->addColumn('TREE_ID', 'TreeId', 'INTEGER', false, null);

		$tMap->addForeignKey('PARENT_NODE_ID', 'ParentNodeId', 'INTEGER', 'wf_crm_category', 'ID', false, null);

		$tMap->addColumn('PRIVATE_NAME', 'PrivateName', 'VARCHAR', true, 255);

		$tMap->addColumn('PUBLIC_NAME', 'PublicName', 'VARCHAR', true, 255);

		$tMap->addColumn('IS_SUBSCRIBABLE', 'IsSubscribable', 'BOOLEAN', true, null);

	} // doBuild()

} // wfCRMCategoryMapBuilder
