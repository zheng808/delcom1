<?php

/**
 * Base static class for performing query and update operations on the 'timelog' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseTimelogPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'timelog';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.Timelog';

	/** The total number of columns. */
	const NUM_COLUMNS = 23;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'timelog.ID';

	/** the column name for the EMPLOYEE_ID field */
	const EMPLOYEE_ID = 'timelog.EMPLOYEE_ID';

	/** the column name for the WORKORDER_ITEM_ID field */
	const WORKORDER_ITEM_ID = 'timelog.WORKORDER_ITEM_ID';

	/** the column name for the WORKORDER_INVOICE_ID field */
	const WORKORDER_INVOICE_ID = 'timelog.WORKORDER_INVOICE_ID';

	/** the column name for the LABOUR_TYPE_ID field */
	const LABOUR_TYPE_ID = 'timelog.LABOUR_TYPE_ID';

	/** the column name for the NONBILL_TYPE_ID field */
	const NONBILL_TYPE_ID = 'timelog.NONBILL_TYPE_ID';

	/** the column name for the CUSTOM_LABEL field */
	const CUSTOM_LABEL = 'timelog.CUSTOM_LABEL';

	/** the column name for the RATE field */
	const RATE = 'timelog.RATE';

	/** the column name for the START_TIME field */
	const START_TIME = 'timelog.START_TIME';

	/** the column name for the END_TIME field */
	const END_TIME = 'timelog.END_TIME';

	/** the column name for the PAYROLL_HOURS field */
	const PAYROLL_HOURS = 'timelog.PAYROLL_HOURS';

	/** the column name for the BILLABLE_HOURS field */
	const BILLABLE_HOURS = 'timelog.BILLABLE_HOURS';

	/** the column name for the COST field */
	const COST = 'timelog.COST';

	/** the column name for the TAXABLE_HST field */
	const TAXABLE_HST = 'timelog.TAXABLE_HST';

	/** the column name for the TAXABLE_GST field */
	const TAXABLE_GST = 'timelog.TAXABLE_GST';

	/** the column name for the TAXABLE_PST field */
	const TAXABLE_PST = 'timelog.TAXABLE_PST';

	/** the column name for the EMPLOYEE_NOTES field */
	const EMPLOYEE_NOTES = 'timelog.EMPLOYEE_NOTES';

	/** the column name for the ADMIN_NOTES field */
	const ADMIN_NOTES = 'timelog.ADMIN_NOTES';

	/** the column name for the ADMIN_FLAGGED field */
	const ADMIN_FLAGGED = 'timelog.ADMIN_FLAGGED';

	/** the column name for the ESTIMATE field */
	const ESTIMATE = 'timelog.ESTIMATE';

	/** the column name for the APPROVED field */
	const APPROVED = 'timelog.APPROVED';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'timelog.CREATED_AT';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'timelog.UPDATED_AT';

	/**
	 * An identiy map to hold any loaded instances of Timelog objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array Timelog[]
	 */
	public static $instances = array();

	/**
	 * The MapBuilder instance for this peer.
	 * @var        MapBuilder
	 */
	private static $mapBuilder = null;

	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'EmployeeId', 'WorkorderItemId', 'WorkorderInvoiceId', 'LabourTypeId', 'NonbillTypeId', 'CustomLabel', 'Rate', 'StartTime', 'EndTime', 'PayrollHours', 'BillableHours', 'Cost', 'TaxableHst', 'TaxableGst', 'TaxablePst', 'EmployeeNotes', 'AdminNotes', 'AdminFlagged', 'Estimate', 'Approved', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'employeeId', 'workorderItemId', 'workorderInvoiceId', 'labourTypeId', 'nonbillTypeId', 'customLabel', 'rate', 'startTime', 'endTime', 'payrollHours', 'billableHours', 'cost', 'taxableHst', 'taxableGst', 'taxablePst', 'employeeNotes', 'adminNotes', 'adminFlagged', 'estimate', 'approved', 'createdAt', 'updatedAt', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::EMPLOYEE_ID, self::WORKORDER_ITEM_ID, self::WORKORDER_INVOICE_ID, self::LABOUR_TYPE_ID, self::NONBILL_TYPE_ID, self::CUSTOM_LABEL, self::RATE, self::START_TIME, self::END_TIME, self::PAYROLL_HOURS, self::BILLABLE_HOURS, self::COST, self::TAXABLE_HST, self::TAXABLE_GST, self::TAXABLE_PST, self::EMPLOYEE_NOTES, self::ADMIN_NOTES, self::ADMIN_FLAGGED, self::ESTIMATE, self::APPROVED, self::CREATED_AT, self::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'employee_id', 'workorder_item_id', 'workorder_invoice_id', 'labour_type_id', 'nonbill_type_id', 'custom_label', 'rate', 'start_time', 'end_time', 'payroll_hours', 'billable_hours', 'cost', 'taxable_hst', 'taxable_gst', 'taxable_pst', 'employee_notes', 'admin_notes', 'admin_flagged', 'estimate', 'approved', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'EmployeeId' => 1, 'WorkorderItemId' => 2, 'WorkorderInvoiceId' => 3, 'LabourTypeId' => 4, 'NonbillTypeId' => 5, 'CustomLabel' => 6, 'Rate' => 7, 'StartTime' => 8, 'EndTime' => 9, 'PayrollHours' => 10, 'BillableHours' => 11, 'Cost' => 12, 'TaxableHst' => 13, 'TaxableGst' => 14, 'TaxablePst' => 15, 'EmployeeNotes' => 16, 'AdminNotes' => 17, 'AdminFlagged' => 18, 'Estimate' => 19, 'Approved' => 20, 'CreatedAt' => 21, 'UpdatedAt' => 22, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'employeeId' => 1, 'workorderItemId' => 2, 'workorderInvoiceId' => 3, 'labourTypeId' => 4, 'nonbillTypeId' => 5, 'customLabel' => 6, 'rate' => 7, 'startTime' => 8, 'endTime' => 9, 'payrollHours' => 10, 'billableHours' => 11, 'cost' => 12, 'taxableHst' => 13, 'taxableGst' => 14, 'taxablePst' => 15, 'employeeNotes' => 16, 'adminNotes' => 17, 'adminFlagged' => 18, 'estimate' => 19, 'approved' => 20, 'createdAt' => 21, 'updatedAt' => 22, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::EMPLOYEE_ID => 1, self::WORKORDER_ITEM_ID => 2, self::WORKORDER_INVOICE_ID => 3, self::LABOUR_TYPE_ID => 4, self::NONBILL_TYPE_ID => 5, self::CUSTOM_LABEL => 6, self::RATE => 7, self::START_TIME => 8, self::END_TIME => 9, self::PAYROLL_HOURS => 10, self::BILLABLE_HOURS => 11, self::COST => 12, self::TAXABLE_HST => 13, self::TAXABLE_GST => 14, self::TAXABLE_PST => 15, self::EMPLOYEE_NOTES => 16, self::ADMIN_NOTES => 17, self::ADMIN_FLAGGED => 18, self::ESTIMATE => 19, self::APPROVED => 20, self::CREATED_AT => 21, self::UPDATED_AT => 22, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'employee_id' => 1, 'workorder_item_id' => 2, 'workorder_invoice_id' => 3, 'labour_type_id' => 4, 'nonbill_type_id' => 5, 'custom_label' => 6, 'rate' => 7, 'start_time' => 8, 'end_time' => 9, 'payroll_hours' => 10, 'billable_hours' => 11, 'cost' => 12, 'taxable_hst' => 13, 'taxable_gst' => 14, 'taxable_pst' => 15, 'employee_notes' => 16, 'admin_notes' => 17, 'admin_flagged' => 18, 'estimate' => 19, 'approved' => 20, 'created_at' => 21, 'updated_at' => 22, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, )
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new TimelogMapBuilder();
		}
		return self::$mapBuilder;
	}
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. TimelogPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(TimelogPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(TimelogPeer::ID);

		$criteria->addSelectColumn(TimelogPeer::EMPLOYEE_ID);

		$criteria->addSelectColumn(TimelogPeer::WORKORDER_ITEM_ID);

		$criteria->addSelectColumn(TimelogPeer::WORKORDER_INVOICE_ID);

		$criteria->addSelectColumn(TimelogPeer::LABOUR_TYPE_ID);

		$criteria->addSelectColumn(TimelogPeer::NONBILL_TYPE_ID);

		$criteria->addSelectColumn(TimelogPeer::CUSTOM_LABEL);

		$criteria->addSelectColumn(TimelogPeer::RATE);

		$criteria->addSelectColumn(TimelogPeer::START_TIME);

		$criteria->addSelectColumn(TimelogPeer::END_TIME);

		$criteria->addSelectColumn(TimelogPeer::PAYROLL_HOURS);

		$criteria->addSelectColumn(TimelogPeer::BILLABLE_HOURS);

		$criteria->addSelectColumn(TimelogPeer::COST);

		$criteria->addSelectColumn(TimelogPeer::TAXABLE_HST);

		$criteria->addSelectColumn(TimelogPeer::TAXABLE_GST);

		$criteria->addSelectColumn(TimelogPeer::TAXABLE_PST);

		$criteria->addSelectColumn(TimelogPeer::EMPLOYEE_NOTES);

		$criteria->addSelectColumn(TimelogPeer::ADMIN_NOTES);

		$criteria->addSelectColumn(TimelogPeer::ADMIN_FLAGGED);

		$criteria->addSelectColumn(TimelogPeer::ESTIMATE);

		$criteria->addSelectColumn(TimelogPeer::APPROVED);

		$criteria->addSelectColumn(TimelogPeer::CREATED_AT);

		$criteria->addSelectColumn(TimelogPeer::UPDATED_AT);

	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     Timelog
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = TimelogPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return TimelogPeer::populateObjects(TimelogPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			TimelogPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      Timelog $value A Timelog object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(Timelog $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A Timelog object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof Timelog) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Timelog object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     Timelog Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol + 0] === null) {
			return null;
		}
		return (string) $row[$startcol + 0];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = TimelogPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = TimelogPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				TimelogPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Employee table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinEmployee(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related WorkorderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinWorkorderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Invoice table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinInvoice(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related LabourType table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinLabourType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related NonbillType table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinNonbillType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with their Employee objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinEmployee(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);
		EmployeePeer::addSelectColumns($c);

		$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = EmployeePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = EmployeePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Timelog) to $obj2 (Employee)
				$obj2->addTimelog($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with their WorkorderItem objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinWorkorderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);
		WorkorderItemPeer::addSelectColumns($c);

		$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = WorkorderItemPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = WorkorderItemPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					WorkorderItemPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Timelog) to $obj2 (WorkorderItem)
				$obj2->addTimelog($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with their Invoice objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinInvoice(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);
		InvoicePeer::addSelectColumns($c);

		$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = InvoicePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = InvoicePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					InvoicePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Timelog) to $obj2 (Invoice)
				$obj2->addTimelog($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with their LabourType objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinLabourType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);
		LabourTypePeer::addSelectColumns($c);

		$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = LabourTypePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = LabourTypePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					LabourTypePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Timelog) to $obj2 (LabourType)
				$obj2->addTimelog($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with their NonbillType objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinNonbillType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);
		NonbillTypePeer::addSelectColumns($c);

		$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = NonbillTypePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = NonbillTypePeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					NonbillTypePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Timelog) to $obj2 (NonbillType)
				$obj2->addTimelog($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(TimelogPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
		$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
		$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}

	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		LabourTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (LabourTypePeer::NUM_COLUMNS - LabourTypePeer::NUM_LAZY_LOAD_COLUMNS);

		NonbillTypePeer::addSelectColumns($c);
		$startcol7 = $startcol6 + (NonbillTypePeer::NUM_COLUMNS - NonbillTypePeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
		$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
		$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
		$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
		$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined Employee rows

			$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = EmployeePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (Employee)
				$obj2->addTimelog($obj1);
			} // if joined row not null

			// Add objects for joined WorkorderItem rows

			$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (WorkorderItem)
				$obj3->addTimelog($obj1);
			} // if joined row not null

			// Add objects for joined Invoice rows

			$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = InvoicePeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (Invoice)
				$obj4->addTimelog($obj1);
			} // if joined row not null

			// Add objects for joined LabourType rows

			$key5 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = LabourTypePeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$omClass = LabourTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					LabourTypePeer::addInstanceToPool($obj5, $key5);
				} // if obj5 loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (LabourType)
				$obj5->addTimelog($obj1);
			} // if joined row not null

			// Add objects for joined NonbillType rows

			$key6 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = NonbillTypePeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$omClass = NonbillTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					NonbillTypePeer::addInstanceToPool($obj6, $key6);
				} // if obj6 loaded

				// Add the $obj1 (Timelog) to the collection in $obj6 (NonbillType)
				$obj6->addTimelog($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Employee table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptEmployee(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related WorkorderItem table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptWorkorderItem(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Invoice table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptInvoice(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related LabourType table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptLabourType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related NonbillType table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptNonbillType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			TimelogPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$criteria->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseTimelogPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $criteria, $con);
    }


		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects except Employee.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptEmployee(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doSelectJoinAllExcept:doSelectJoinAllExcept') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		LabourTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (LabourTypePeer::NUM_COLUMNS - LabourTypePeer::NUM_LAZY_LOAD_COLUMNS);

		NonbillTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (NonbillTypePeer::NUM_COLUMNS - NonbillTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined WorkorderItem rows

				$key2 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = WorkorderItemPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WorkorderItemPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (WorkorderItem)
				$obj2->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key3 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = InvoicePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					InvoicePeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (Invoice)
				$obj3->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined LabourType rows

				$key4 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = LabourTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = LabourTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					LabourTypePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (LabourType)
				$obj4->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined NonbillType rows

				$key5 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = NonbillTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = NonbillTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					NonbillTypePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (NonbillType)
				$obj5->addTimelog($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects except WorkorderItem.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptWorkorderItem(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		LabourTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (LabourTypePeer::NUM_COLUMNS - LabourTypePeer::NUM_LAZY_LOAD_COLUMNS);

		NonbillTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (NonbillTypePeer::NUM_COLUMNS - NonbillTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Employee rows

				$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = EmployeePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (Employee)
				$obj2->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key3 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = InvoicePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					InvoicePeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (Invoice)
				$obj3->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined LabourType rows

				$key4 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = LabourTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = LabourTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					LabourTypePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (LabourType)
				$obj4->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined NonbillType rows

				$key5 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = NonbillTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = NonbillTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					NonbillTypePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (NonbillType)
				$obj5->addTimelog($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects except Invoice.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptInvoice(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		LabourTypePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (LabourTypePeer::NUM_COLUMNS - LabourTypePeer::NUM_LAZY_LOAD_COLUMNS);

		NonbillTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (NonbillTypePeer::NUM_COLUMNS - NonbillTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Employee rows

				$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = EmployeePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (Employee)
				$obj2->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (WorkorderItem)
				$obj3->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined LabourType rows

				$key4 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = LabourTypePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = LabourTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					LabourTypePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (LabourType)
				$obj4->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined NonbillType rows

				$key5 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = NonbillTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = NonbillTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					NonbillTypePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (NonbillType)
				$obj5->addTimelog($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects except LabourType.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptLabourType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		NonbillTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (NonbillTypePeer::NUM_COLUMNS - NonbillTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::NONBILL_TYPE_ID,), array(NonbillTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Employee rows

				$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = EmployeePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (Employee)
				$obj2->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (WorkorderItem)
				$obj3->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = InvoicePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (Invoice)
				$obj4->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined NonbillType rows

				$key5 = NonbillTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = NonbillTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = NonbillTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					NonbillTypePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (NonbillType)
				$obj5->addTimelog($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Timelog objects pre-filled with all related objects except NonbillType.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Timelog objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptNonbillType(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TimelogPeer::addSelectColumns($c);
		$startcol2 = (TimelogPeer::NUM_COLUMNS - TimelogPeer::NUM_LAZY_LOAD_COLUMNS);

		EmployeePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderItemPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);

		InvoicePeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (InvoicePeer::NUM_COLUMNS - InvoicePeer::NUM_LAZY_LOAD_COLUMNS);

		LabourTypePeer::addSelectColumns($c);
		$startcol6 = $startcol5 + (LabourTypePeer::NUM_COLUMNS - LabourTypePeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(TimelogPeer::EMPLOYEE_ID,), array(EmployeePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_ITEM_ID,), array(WorkorderItemPeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::WORKORDER_INVOICE_ID,), array(InvoicePeer::ID,), $join_behavior);
				$c->addJoin(array(TimelogPeer::LABOUR_TYPE_ID,), array(LabourTypePeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = TimelogPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = TimelogPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				TimelogPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Employee rows

				$key2 = EmployeePeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = EmployeePeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = EmployeePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					EmployeePeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj2 (Employee)
				$obj2->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderItem rows

				$key3 = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderItemPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderItemPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderItemPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj3 (WorkorderItem)
				$obj3->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined Invoice rows

				$key4 = InvoicePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = InvoicePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$omClass = InvoicePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					InvoicePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj4 (Invoice)
				$obj4->addTimelog($obj1);

			} // if joined row is not null

				// Add objects for joined LabourType rows

				$key5 = LabourTypePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = LabourTypePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$omClass = LabourTypePeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					LabourTypePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (Timelog) to the collection in $obj5 (LabourType)
				$obj5->addTimelog($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


  static public function getUniqueColumnNames()
  {
    return array();
  }
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return TimelogPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a Timelog or Criteria object.
	 *
	 * @param      mixed $values Criteria or Timelog object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseTimelogPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from Timelog object
		}

		if ($criteria->containsKey(TimelogPeer::ID) && $criteria->keyContainsValue(TimelogPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.TimelogPeer::ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseTimelogPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a Timelog or Criteria object.
	 *
	 * @param      mixed $values Criteria or Timelog object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseTimelogPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseTimelogPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(TimelogPeer::ID);
			$selectCriteria->add(TimelogPeer::ID, $criteria->remove(TimelogPeer::ID), $comparison);

		} else { // $values is Timelog object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseTimelogPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseTimelogPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the timelog table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(TimelogPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a Timelog or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or Timelog object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			TimelogPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof Timelog) {
			// invalidate the cache for this single object
			TimelogPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(TimelogPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				TimelogPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given Timelog object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      Timelog $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(Timelog $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(TimelogPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(TimelogPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(TimelogPeer::DATABASE_NAME, TimelogPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        foreach ($res as $failed) {
            $col = TimelogPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     Timelog
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = TimelogPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(TimelogPeer::DATABASE_NAME);
		$criteria->add(TimelogPeer::ID, $pk);

		$v = TimelogPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(TimelogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(TimelogPeer::DATABASE_NAME);
			$criteria->add(TimelogPeer::ID, $pks, Criteria::IN);
			$objs = TimelogPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseTimelogPeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the TimelogPeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the TimelogPeer class:
//
// Propel::getDatabaseMap(TimelogPeer::DATABASE_NAME)->addTableBuilder(TimelogPeer::TABLE_NAME, TimelogPeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BaseTimelogPeer::DATABASE_NAME)->addTableBuilder(BaseTimelogPeer::TABLE_NAME, BaseTimelogPeer::getMapBuilder());

