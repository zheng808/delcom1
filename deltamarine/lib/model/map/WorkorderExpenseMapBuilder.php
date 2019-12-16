<?php


/**
 * This class adds structure of 'workorder_expense' table to 'propel' DatabaseMap object.
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
class WorkorderExpenseMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.WorkorderExpenseMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(WorkorderExpensePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(WorkorderExpensePeer::TABLE_NAME);
		$tMap->setPhpName('WorkorderExpense');
		$tMap->setClassname('WorkorderExpense');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('WORKORDER_ITEM_ID', 'WorkorderItemId', 'INTEGER', 'workorder_item', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_INVOICE_ID', 'WorkorderInvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

		$tMap->addColumn('LABEL', 'Label', 'VARCHAR', true, 255);

		$tMap->addColumn('CUSTOMER_NOTES', 'CustomerNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('INTERNAL_NOTES', 'InternalNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('COST', 'Cost', 'DECIMAL', false, 8);

		$tMap->addColumn('ESTIMATE', 'Estimate', 'BOOLEAN', true, null);

		$tMap->addColumn('INVOICE', 'Invoice', 'BOOLEAN', true, null);

		$tMap->addColumn('PRICE', 'Price', 'DECIMAL', false, 8);

		$tMap->addColumn('ORIGIN', 'Origin', 'VARCHAR', false, 255);

		$tMap->addColumn('TAXABLE_HST', 'TaxableHst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_GST', 'TaxableGst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_PST', 'TaxablePst', 'DECIMAL', true, 8);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('SUB_CONTRACTOR_FLG', 'SubContractorFlg', 'VARCHAR', true, 1);

		$tMap->addColumn('PST_OVERRIDE_FLG', 'pstOverrideFlg', 'VARCHAR', true, 1);

		$tMap->addColumn('GST_OVERRIDE_FLG', 'gstOverrideFlg', 'VARCHAR', true, 1);


	} // doBuild()

} // WorkorderExpenseMapBuilder
