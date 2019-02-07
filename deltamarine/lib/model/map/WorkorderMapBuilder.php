<?php


/**
 * This class adds structure of 'workorder' table to 'propel' DatabaseMap object.
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
class WorkorderMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.WorkorderMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(WorkorderPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(WorkorderPeer::TABLE_NAME);
		$tMap->setPhpName('Workorder');
		$tMap->setClassname('Workorder');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('CUSTOMER_ID', 'CustomerId', 'INTEGER', 'customer', 'ID', false, null);

		$tMap->addForeignKey('CUSTOMER_BOAT_ID', 'CustomerBoatId', 'INTEGER', 'customer_boat', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_CATEGORY_ID', 'WorkorderCategoryId', 'INTEGER', 'workorder_category', 'ID', false, null);

		$tMap->addColumn('STATUS', 'Status', 'VARCHAR', true, 15);

		$tMap->addColumn('SUMMARY_COLOR', 'SummaryColor', 'VARCHAR', true, 6);

		$tMap->addColumn('SUMMARY_NOTES', 'SummaryNotes', 'VARCHAR', false, 255);

		$tMap->addColumn('HAULOUT_DATE', 'HauloutDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('HAULIN_DATE', 'HaulinDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('CREATED_ON', 'CreatedOn', 'TIMESTAMP', false, null);

		$tMap->addColumn('STARTED_ON', 'StartedOn', 'TIMESTAMP', false, null);

		$tMap->addColumn('COMPLETED_ON', 'CompletedOn', 'TIMESTAMP', false, null);

		$tMap->addColumn('HST_EXEMPT', 'HstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('GST_EXEMPT', 'GstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('PST_EXEMPT', 'PstExempt', 'BOOLEAN', true, null);

		$tMap->addColumn('CUSTOMER_NOTES', 'CustomerNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('INTERNAL_NOTES', 'InternalNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('FOR_RIGGING', 'ForRigging', 'BOOLEAN', true, null);

		$tMap->addColumn('SHOP_SUPPLIES_SURCHARGE', 'ShopSuppliesSurcharge', 'DECIMAL', false, 5);

		$tMap->addColumn('MOORAGE_SURCHARGE', 'MoorageSurcharge', 'DECIMAL', false, 5);

		$tMap->addColumn('MOORAGE_SURCHARGE_AMT', 'MoorageSurchargeAmt', 'DECIMAL', false, 8);

	} // doBuild()

} // WorkorderMapBuilder
