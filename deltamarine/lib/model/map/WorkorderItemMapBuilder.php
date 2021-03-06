<?php


/**
 * This class adds structure of 'workorder_item' table to 'propel' DatabaseMap object.
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
class WorkorderItemMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.WorkorderItemMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(WorkorderItemPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(WorkorderItemPeer::TABLE_NAME);
		$tMap->setPhpName('WorkorderItem');
		$tMap->setClassname('WorkorderItem');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WORKORDER_ID', 'WorkorderId', 'INTEGER', 'workorder', 'ID', true, null);

		$tMap->addColumn('LABEL', 'Label', 'VARCHAR', false, 255);

		$tMap->addColumn('LFT', 'Left', 'INTEGER', true, null);

		$tMap->addColumn('RGT', 'Right', 'INTEGER', true, null);

		$tMap->addColumn('OWNER_COMPANY', 'OwnerCompany', 'INTEGER', false, null);

		$tMap->addColumn('LABOUR_ESTIMATE', 'LabourEstimate', 'DECIMAL', false, 8);

		$tMap->addColumn('LABOUR_ACTUAL', 'LabourActual', 'DECIMAL', false, 8);

		$tMap->addColumn('OTHER_ESTIMATE', 'OtherEstimate', 'DECIMAL', false, 8);

		$tMap->addColumn('OTHER_ACTUAL', 'OtherActual', 'DECIMAL', false, 8);

		$tMap->addColumn('PART_ESTIMATE', 'PartEstimate', 'DECIMAL', false, 8);

		$tMap->addColumn('PART_ACTUAL', 'PartActual', 'DECIMAL', false, 8);

		$tMap->addColumn('AMOUNT_PAID', 'AmountPaid', 'DECIMAL', false, 8);

		$tMap->addColumn('COMPLETED', 'Completed', 'BOOLEAN', true, null);

		$tMap->addForeignKey('COMPLETED_BY', 'CompletedBy', 'INTEGER', 'employee', 'ID', false, null);

		$tMap->addColumn('COMPLETED_DATE', 'CompletedDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('CUSTOMER_NOTES', 'CustomerNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('INTERNAL_NOTES', 'InternalNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('COLOR_CODE', 'ColorCode', 'VARCHAR', true, 6);

		$tMap->addColumn('TASK_CODE', 'TASK_CODE', 'VARCHAR', true, 6);

	} // doBuild()

} // WorkorderItemMapBuilder
