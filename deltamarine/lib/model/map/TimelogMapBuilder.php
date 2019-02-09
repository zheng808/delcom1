<?php


/**
 * This class adds structure of 'timelog' table to 'propel' DatabaseMap object.
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
class TimelogMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.TimelogMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(TimelogPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(TimelogPeer::TABLE_NAME);
		$tMap->setPhpName('Timelog');
		$tMap->setClassname('Timelog');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('EMPLOYEE_ID', 'EmployeeId', 'INTEGER', 'employee', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_ITEM_ID', 'WorkorderItemId', 'INTEGER', 'workorder_item', 'ID', false, null);

		$tMap->addForeignKey('WORKORDER_INVOICE_ID', 'WorkorderInvoiceId', 'INTEGER', 'invoice', 'ID', false, null);

		$tMap->addForeignKey('LABOUR_TYPE_ID', 'LabourTypeId', 'INTEGER', 'labour_type', 'ID', false, null);

		$tMap->addForeignKey('NONBILL_TYPE_ID', 'NonbillTypeId', 'INTEGER', 'nonbill_type', 'ID', false, null);

		$tMap->addColumn('CUSTOM_LABEL', 'CustomLabel', 'VARCHAR', false, 128);

		$tMap->addColumn('RATE', 'Rate', 'DECIMAL', true, 5);

		$tMap->addColumn('START_TIME', 'StartTime', 'TIMESTAMP', false, null);

		$tMap->addColumn('END_TIME', 'EndTime', 'TIMESTAMP', false, null);

		$tMap->addColumn('PAYROLL_HOURS', 'PayrollHours', 'DECIMAL', true, 5);

		$tMap->addColumn('BILLABLE_HOURS', 'BillableHours', 'DECIMAL', true, 5);

		$tMap->addColumn('COST', 'Cost', 'DECIMAL', false, 8);

		$tMap->addColumn('TAXABLE_HST', 'TaxableHst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_GST', 'TaxableGst', 'DECIMAL', true, 8);

		$tMap->addColumn('TAXABLE_PST', 'TaxablePst', 'DECIMAL', true, 8);

		$tMap->addColumn('EMPLOYEE_NOTES', 'EmployeeNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('ADMIN_NOTES', 'AdminNotes', 'LONGVARCHAR', false, null);

		$tMap->addColumn('ADMIN_FLAGGED', 'AdminFlagged', 'BOOLEAN', true, null);

		$tMap->addColumn('ESTIMATE', 'Estimate', 'BOOLEAN', true, null);

		$tMap->addColumn('APPROVED', 'Approved', 'BOOLEAN', true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // TimelogMapBuilder