<?php


/**
 * This class adds structure of 'wf_crm_correspondence' table to 'propel' DatabaseMap object.
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
class wfCRMCorrespondenceMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.wfCRMPlugin.lib.model.map.wfCRMCorrespondenceMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(wfCRMCorrespondencePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(wfCRMCorrespondencePeer::TABLE_NAME);
		$tMap->setPhpName('wfCRMCorrespondence');
		$tMap->setClassname('wfCRMCorrespondence');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WF_CRM_ID', 'WfCrmId', 'INTEGER', 'wf_crm', 'ID', false, null);

		$tMap->addColumn('RECEIVED', 'Received', 'BOOLEAN', true, null);

		$tMap->addColumn('METHOD', 'Method', 'VARCHAR', true, 16);

		$tMap->addColumn('SUBJECT', 'Subject', 'VARCHAR', false, 128);

		$tMap->addColumn('MESSAGE', 'Message', 'LONGVARCHAR', false, null);

		$tMap->addColumn('WHENDONE', 'Whendone', 'TIMESTAMP', true, null);

		$tMap->addColumn('IS_NEW', 'IsNew', 'BOOLEAN', true, null);

	} // doBuild()

} // wfCRMCorrespondenceMapBuilder
