<?php

/**
 * Base static class for performing query and update operations on the 'workorder' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseWorkorderPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'workorder';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.Workorder';

	/** The total number of columns. */
	const NUM_COLUMNS = 26;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID field */
	const ID = 'workorder.ID';

	/** the column name for the CUSTOMER_ID field */
	const CUSTOMER_ID = 'workorder.CUSTOMER_ID';

	/** the column name for the CUSTOMER_BOAT_ID field */
	const CUSTOMER_BOAT_ID = 'workorder.CUSTOMER_BOAT_ID';

	/** the column name for the WORKORDER_CATEGORY_ID field */
	const WORKORDER_CATEGORY_ID = 'workorder.WORKORDER_CATEGORY_ID';

	/** the column name for the STATUS field */
	const STATUS = 'workorder.STATUS';

	/** the column name for the SUMMARY_COLOR field */
	const SUMMARY_COLOR = 'workorder.SUMMARY_COLOR';

	/** the column name for the SUMMARY_NOTES field */
	const SUMMARY_NOTES = 'workorder.SUMMARY_NOTES';

	/** the column name for the HAULOUT_DATE field */
	const HAULOUT_DATE = 'workorder.HAULOUT_DATE';

	/** the column name for the HAULIN_DATE field */
	const HAULIN_DATE = 'workorder.HAULIN_DATE';

	/** the column name for the CREATED_ON field */
	const CREATED_ON = 'workorder.CREATED_ON';

	/** the column name for the STARTED_ON field */
	const STARTED_ON = 'workorder.STARTED_ON';

	/** the column name for the COMPLETED_ON field */
	const COMPLETED_ON = 'workorder.COMPLETED_ON';

	/** the column name for the HST_EXEMPT field */
	const HST_EXEMPT = 'workorder.HST_EXEMPT';

	/** the column name for the GST_EXEMPT field */
	const GST_EXEMPT = 'workorder.GST_EXEMPT';

	/** the column name for the PST_EXEMPT field */
	const PST_EXEMPT = 'workorder.PST_EXEMPT';

	/** the column name for the CUSTOMER_NOTES field */
	const CUSTOMER_NOTES = 'workorder.CUSTOMER_NOTES';

	/** the column name for the INTERNAL_NOTES field */
	const INTERNAL_NOTES = 'workorder.INTERNAL_NOTES';

	/** the column name for the FOR_RIGGING field */
	const FOR_RIGGING = 'workorder.FOR_RIGGING';

	/** the column name for the SHOP_SUPPLIES_SURCHARGE field */
	const SHOP_SUPPLIES_SURCHARGE = 'workorder.SHOP_SUPPLIES_SURCHARGE';

	/** the column name for the MOORAGE_SURCHARGE field */
	const MOORAGE_SURCHARGE = 'workorder.MOORAGE_SURCHARGE';

	/** the column name for the MOORAGE_SURCHARGE_AMT field */
	const MOORAGE_SURCHARGE_AMT = 'workorder.MOORAGE_SURCHARGE_AMT';

	const EXEMPTION_FILE = 'workorder.EXEMPTION_FILE';

	const CANADA_ENTRY_NUM = 'workorder.CANADA_ENTRY_NUM';

	const CANADA_ENTRY_DATE = 'workorder.CANADA_ENTRY_DATE';

	const USA_ENTRY_NUM = 'workorder.USA_ENTRY_NUM';

	const USA_ENTRY_DATE = 'workorder.USA_ENTRY_DATE';

	/**
	 * An identiy map to hold any loaded instances of Workorder objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array Workorder[]
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
		BasePeer::TYPE_PHPNAME => array ('Id', 'CustomerId', 'CustomerBoatId', 'WorkorderCategoryId', 'Status', 'SummaryColor', 'SummaryNotes', 'HauloutDate', 'HaulinDate', 'CreatedOn', 'StartedOn', 'CompletedOn', 'HstExempt', 'GstExempt', 'PstExempt', 'CustomerNotes', 'InternalNotes', 'ForRigging', 'ShopSuppliesSurcharge', 'MoorageSurcharge', 'MoorageSurchargeAmt', 'ExemptionFile','CanadaEntryNum','CanadaEntryDate','UsaEntryNum','UsaEntryDate', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'customerId', 'customerBoatId', 'workorderCategoryId', 'status', 'summaryColor', 'summaryNotes', 'hauloutDate', 'haulinDate', 'createdOn', 'startedOn', 'completedOn', 'hstExempt', 'gstExempt', 'pstExempt', 'customerNotes', 'internalNotes', 'forRigging', 'shopSuppliesSurcharge', 'moorageSurcharge', 'moorageSurchargeAmt', 'exemptionFile','canadaEntryNum','canadaEntryDate','usaEntryNum','usaEntryDate', ),
		BasePeer::TYPE_COLNAME => array (self::ID, self::CUSTOMER_ID, self::CUSTOMER_BOAT_ID, self::WORKORDER_CATEGORY_ID, self::STATUS, self::SUMMARY_COLOR, self::SUMMARY_NOTES, self::HAULOUT_DATE, self::HAULIN_DATE, self::CREATED_ON, self::STARTED_ON, self::COMPLETED_ON, self::HST_EXEMPT, self::GST_EXEMPT, self::PST_EXEMPT, self::CUSTOMER_NOTES, self::INTERNAL_NOTES, self::FOR_RIGGING, self::SHOP_SUPPLIES_SURCHARGE, self::MOORAGE_SURCHARGE, self::MOORAGE_SURCHARGE_AMT, self::EXEMPTION_FILE, self::CANADA_ENTRY_NUM, self::CANADA_ENTRY_DATE, self::USA_ENTRY_NUM, self::USA_ENTRY_DATE,),
		BasePeer::TYPE_FIELDNAME => array ('id', 'customer_id', 'customer_boat_id', 'workorder_category_id', 'status', 'summary_color', 'summary_notes', 'haulout_date', 'haulin_date', 'created_on', 'started_on', 'completed_on', 'hst_exempt', 'gst_exempt', 'pst_exempt', 'customer_notes', 'internal_notes', 'for_rigging', 'shop_supplies_surcharge', 'moorage_surcharge', 'moorage_surcharge_amt', 'exemption_file','canada_entry_num','canada_entry_date','usa_entry_num','usa_entry_date', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21,22, 23, 24, 25,)
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'CustomerId' => 1, 'CustomerBoatId' => 2, 'WorkorderCategoryId' => 3, 'Status' => 4, 'SummaryColor' => 5, 'SummaryNotes' => 6, 'HauloutDate' => 7, 'HaulinDate' => 8, 'CreatedOn' => 9, 'StartedOn' => 10, 'CompletedOn' => 11, 'HstExempt' => 12, 'GstExempt' => 13, 'PstExempt' => 14, 'CustomerNotes' => 15, 'InternalNotes' => 16, 'ForRigging' => 17, 'ShopSuppliesSurcharge' => 18, 'MoorageSurcharge' => 19, 'MoorageSurchargeAmt' => 20, 'ExemptionFile' => 21, 'CanadaEntryNum' => 22,'CanadaEntryDate' => 23,'UsaEntryNum' => 24,'UsaEntryDate' => 25, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'customerId' => 1, 'customerBoatId' => 2, 'workorderCategoryId' => 3, 'status' => 4, 'summaryColor' => 5, 'summaryNotes' => 6, 'hauloutDate' => 7, 'haulinDate' => 8, 'createdOn' => 9, 'startedOn' => 10, 'completedOn' => 11, 'hstExempt' => 12, 'gstExempt' => 13, 'pstExempt' => 14, 'customerNotes' => 15, 'internalNotes' => 16, 'forRigging' => 17, 'shopSuppliesSurcharge' => 18, 'moorageSurcharge' => 19, 'moorageSurchargeAmt' => 20, 'exemptionFile' => 21,  'canadaEntryNum' => 22,'canadaEntryDate' => 23,'usaEntryNum' => 24,'usaEntryDate' => 25, ),
		BasePeer::TYPE_COLNAME => array (self::ID => 0, self::CUSTOMER_ID => 1, self::CUSTOMER_BOAT_ID => 2, self::WORKORDER_CATEGORY_ID => 3, self::STATUS => 4, self::SUMMARY_COLOR => 5, self::SUMMARY_NOTES => 6, self::HAULOUT_DATE => 7, self::HAULIN_DATE => 8, self::CREATED_ON => 9, self::STARTED_ON => 10, self::COMPLETED_ON => 11, self::HST_EXEMPT => 12, self::GST_EXEMPT => 13, self::PST_EXEMPT => 14, self::CUSTOMER_NOTES => 15, self::INTERNAL_NOTES => 16, self::FOR_RIGGING => 17, self::SHOP_SUPPLIES_SURCHARGE => 18, self::MOORAGE_SURCHARGE => 19, self::MOORAGE_SURCHARGE_AMT => 20, self::EXEMPTION_FILE => 21,  self::CANADA_ENTRY_NUM => 22, self::CANADA_ENTRY_DATE => 23, self::USA_ENTRY_NUM => 24, self::USA_ENTRY_DATE => 25,),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'customer_id' => 1, 'customer_boat_id' => 2, 'workorder_category_id' => 3, 'status' => 4, 'summary_color' => 5, 'summary_notes' => 6, 'haulout_date' => 7, 'haulin_date' => 8, 'created_on' => 9, 'started_on' => 10, 'completed_on' => 11, 'hst_exempt' => 12, 'gst_exempt' => 13, 'pst_exempt' => 14, 'customer_notes' => 15, 'internal_notes' => 16, 'for_rigging' => 17, 'shop_supplies_surcharge' => 18, 'moorage_surcharge' => 19, 'moorage_surcharge_amt' => 20, 'exemption_file' => 21, 'canada_entry_num' => 22,'canada_entry_date' => 23,'usa_entry_num' => 24,'usa_entry_date' => 25, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25,)
	);

	/**
	 * Get a (singleton) instance of the MapBuilder for this peer class.
	 * @return     MapBuilder The map builder for this peer
	 */
	public static function getMapBuilder()
	{
		if (self::$mapBuilder === null) {
			self::$mapBuilder = new WorkorderMapBuilder();
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
	 * @param      string $column The column name for current table. (i.e. WorkorderPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(WorkorderPeer::TABLE_NAME.'.', $alias.'.', $column);
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

		$criteria->addSelectColumn(WorkorderPeer::ID);

		$criteria->addSelectColumn(WorkorderPeer::CUSTOMER_ID);

		$criteria->addSelectColumn(WorkorderPeer::CUSTOMER_BOAT_ID);

		$criteria->addSelectColumn(WorkorderPeer::WORKORDER_CATEGORY_ID);

		$criteria->addSelectColumn(WorkorderPeer::STATUS);

		$criteria->addSelectColumn(WorkorderPeer::SUMMARY_COLOR);

		$criteria->addSelectColumn(WorkorderPeer::SUMMARY_NOTES);

		$criteria->addSelectColumn(WorkorderPeer::HAULOUT_DATE);

		$criteria->addSelectColumn(WorkorderPeer::HAULIN_DATE);

		$criteria->addSelectColumn(WorkorderPeer::CREATED_ON);

		$criteria->addSelectColumn(WorkorderPeer::STARTED_ON);

		$criteria->addSelectColumn(WorkorderPeer::COMPLETED_ON);

		$criteria->addSelectColumn(WorkorderPeer::HST_EXEMPT);

		$criteria->addSelectColumn(WorkorderPeer::GST_EXEMPT);

		$criteria->addSelectColumn(WorkorderPeer::PST_EXEMPT);

		$criteria->addSelectColumn(WorkorderPeer::CUSTOMER_NOTES);

		$criteria->addSelectColumn(WorkorderPeer::INTERNAL_NOTES);

		$criteria->addSelectColumn(WorkorderPeer::FOR_RIGGING);

		$criteria->addSelectColumn(WorkorderPeer::SHOP_SUPPLIES_SURCHARGE);

		$criteria->addSelectColumn(WorkorderPeer::MOORAGE_SURCHARGE);

		$criteria->addSelectColumn(WorkorderPeer::MOORAGE_SURCHARGE_AMT);

		$criteria->addSelectColumn(WorkorderPeer::EXEMPTION_FILE);

		$criteria->addSelectColumn(WorkorderPeer::CANADA_ENTRY_NUM);

		$criteria->addSelectColumn(WorkorderPeer::CANADA_ENTRY_DATE);

		$criteria->addSelectColumn(WorkorderPeer::USA_ENTRY_NUM);

		$criteria->addSelectColumn(WorkorderPeer::USA_ENTRY_DATE);
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
		$criteria->setPrimaryTableName(WorkorderPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}


    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * @return     Workorder
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = WorkorderPeer::doSelect($critcopy, $con);
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
		return WorkorderPeer::populateObjects(WorkorderPeer::doSelectStmt($criteria, $con));
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

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doSelectStmt:doSelectStmt') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			WorkorderPeer::addSelectColumns($criteria);
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
	 * @param      Workorder $value A Workorder object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(Workorder $obj, $key = null)
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
	 * @param      mixed $value A Workorder object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof Workorder) {
				$key = (string) $value->getId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Workorder object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     Workorder Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
		$cls = WorkorderPeer::getOMClass();
		$cls = substr('.'.$cls, strrpos('.'.$cls, '.') + 1);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = WorkorderPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
		
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				WorkorderPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related Customer table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinCustomer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WorkorderPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related CustomerBoat table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinCustomerBoat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WorkorderPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related WorkorderCategory table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinWorkorderCategory(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WorkorderPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);


    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Selects a collection of Workorder objects pre-filled with their Customer objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinCustomer(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);
		CustomerPeer::addSelectColumns($c);

		$c->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = CustomerPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = CustomerPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = CustomerPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					CustomerPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Workorder) to $obj2 (Customer)
				$obj2->addWorkorder($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Workorder objects pre-filled with their CustomerBoat objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinCustomerBoat(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);
		CustomerBoatPeer::addSelectColumns($c);

		$c->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = CustomerBoatPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = CustomerBoatPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					CustomerBoatPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Workorder) to $obj2 (CustomerBoat)
				$obj2->addWorkorder($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Workorder objects pre-filled with their WorkorderCategory objects.
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinWorkorderCategory(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);
		WorkorderCategoryPeer::addSelectColumns($c);

		$c->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = WorkorderCategoryPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = WorkorderCategoryPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = WorkorderCategoryPeer::getOMClass();

					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					WorkorderCategoryPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (Workorder) to $obj2 (WorkorderCategory)
				$obj2->addWorkorder($obj1);

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
		$criteria->setPrimaryTableName(WorkorderPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
		$criteria->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);
		$criteria->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Selects a collection of Workorder objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol2 = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerBoatPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (CustomerBoatPeer::NUM_COLUMNS - CustomerBoatPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderCategoryPeer::addSelectColumns($c);
		$startcol5 = $startcol4 + (WorkorderCategoryPeer::NUM_COLUMNS - WorkorderCategoryPeer::NUM_LAZY_LOAD_COLUMNS);

		$c->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
		$c->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);
		$c->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);
		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined Customer rows

			$key2 = CustomerPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = CustomerPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$omClass = CustomerPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					CustomerPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (Workorder) to the collection in $obj2 (Customer)
				$obj2->addWorkorder($obj1);
			} // if joined row not null

			// Add objects for joined CustomerBoat rows

			$key3 = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = CustomerBoatPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$omClass = CustomerBoatPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					CustomerBoatPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (Workorder) to the collection in $obj3 (CustomerBoat)
				$obj3->addWorkorder($obj1);
			} // if joined row not null

			// Add objects for joined WorkorderCategory rows

			$key4 = WorkorderCategoryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = WorkorderCategoryPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$omClass = WorkorderCategoryPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					WorkorderCategoryPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (Workorder) to the collection in $obj4 (WorkorderCategory)
				$obj4->addWorkorder($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related Customer table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptCustomer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);
				$criteria->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related CustomerBoat table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptCustomerBoat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
				$criteria->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Returns the number of rows matching criteria, joining the related WorkorderCategory table
	 *
	 * @param      Criteria $c
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptWorkorderCategory(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WorkorderPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
				$criteria->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
				$criteria->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doCount:doCount') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $criteria, $con);
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
	 * Selects a collection of Workorder objects pre-filled with all related objects except Customer.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptCustomer(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doSelectJoinAllExcept:doSelectJoinAllExcept') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $c, $con);
    }


		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol2 = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerBoatPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (CustomerBoatPeer::NUM_COLUMNS - CustomerBoatPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderCategoryPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderCategoryPeer::NUM_COLUMNS - WorkorderCategoryPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);
				$c->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined CustomerBoat rows

				$key2 = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = CustomerBoatPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = CustomerBoatPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					CustomerBoatPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj2 (CustomerBoat)
				$obj2->addWorkorder($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderCategory rows

				$key3 = WorkorderCategoryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderCategoryPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderCategoryPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderCategoryPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj3 (WorkorderCategory)
				$obj3->addWorkorder($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Workorder objects pre-filled with all related objects except CustomerBoat.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptCustomerBoat(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol2 = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS);

		WorkorderCategoryPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (WorkorderCategoryPeer::NUM_COLUMNS - WorkorderCategoryPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
				$c->addJoin(array(WorkorderPeer::WORKORDER_CATEGORY_ID,), array(WorkorderCategoryPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Customer rows

				$key2 = CustomerPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = CustomerPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = CustomerPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					CustomerPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj2 (Customer)
				$obj2->addWorkorder($obj1);

			} // if joined row is not null

				// Add objects for joined WorkorderCategory rows

				$key3 = WorkorderCategoryPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = WorkorderCategoryPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = WorkorderCategoryPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					WorkorderCategoryPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj3 (WorkorderCategory)
				$obj3->addWorkorder($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of Workorder objects pre-filled with all related objects except WorkorderCategory.
	 *
	 * @param      Criteria  $c
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of Workorder objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptWorkorderCategory(Criteria $c, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		// $c->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		WorkorderPeer::addSelectColumns($c);
		$startcol2 = (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + (CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS);

		CustomerBoatPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + (CustomerBoatPeer::NUM_COLUMNS - CustomerBoatPeer::NUM_LAZY_LOAD_COLUMNS);

				$c->addJoin(array(WorkorderPeer::CUSTOMER_ID,), array(CustomerPeer::ID,), $join_behavior);
				$c->addJoin(array(WorkorderPeer::CUSTOMER_BOAT_ID,), array(CustomerBoatPeer::ID,), $join_behavior);

		$stmt = BasePeer::doSelect($c, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WorkorderPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$omClass = WorkorderPeer::getOMClass();

				$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
				$obj1 = new $cls();
				$obj1->hydrate($row);
				WorkorderPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined Customer rows

				$key2 = CustomerPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = CustomerPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$omClass = CustomerPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					CustomerPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj2 (Customer)
				$obj2->addWorkorder($obj1);

			} // if joined row is not null

				// Add objects for joined CustomerBoat rows

				$key3 = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = CustomerBoatPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$omClass = CustomerBoatPeer::getOMClass();


					$cls = substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					CustomerBoatPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (Workorder) to the collection in $obj3 (CustomerBoat)
				$obj3->addWorkorder($obj1);

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
		return WorkorderPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a Workorder or Criteria object.
	 *
	 * @param      mixed $values Criteria or Workorder object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseWorkorderPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from Workorder object
		}

		if ($criteria->containsKey(WorkorderPeer::ID) && $criteria->keyContainsValue(WorkorderPeer::ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.WorkorderPeer::ID.')');
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

		
    foreach (sfMixer::getCallables('BaseWorkorderPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a Workorder or Criteria object.
	 *
	 * @param      mixed $values Criteria or Workorder object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseWorkorderPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(WorkorderPeer::ID);
			$selectCriteria->add(WorkorderPeer::ID, $criteria->remove(WorkorderPeer::ID), $comparison);

		} else { // $values is Workorder object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseWorkorderPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseWorkorderPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the workorder table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += WorkorderPeer::doOnDeleteCascade(new Criteria(WorkorderPeer::DATABASE_NAME), $con);
			$affectedRows += BasePeer::doDeleteAll(WorkorderPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a Workorder or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or Workorder object or primary key or array of primary keys
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
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			WorkorderPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof Workorder) {
			// invalidate the cache for this single object
			WorkorderPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(WorkorderPeer::ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				WorkorderPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += WorkorderPeer::doOnDeleteCascade($criteria, $con);
			
				// Because this db requires some delete cascade/set null emulation, we have to
				// clear the cached instance *after* the emulation has happened (since
				// instances get re-added by the select statement contained therein).
				if ($values instanceof Criteria) {
					WorkorderPeer::clearInstancePool();
				} else { // it's a PK or object
					WorkorderPeer::removeInstanceFromPool($values);
				}
			
			$affectedRows += BasePeer::doDelete($criteria, $con);

			// invalidate objects in WorkorderItemPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
			WorkorderItemPeer::clearInstancePool();

			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
	 * feature (like MySQL or SQLite).
	 *
	 * This method is not very speedy because it must perform a query first to get
	 * the implicated records and then perform the deletes by calling those Peer classes.
	 *
	 * This method should be used within a transaction if possible.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
	{
		// initialize var to track total num of affected rows
		$affectedRows = 0;

		// first find the objects that are implicated by the $criteria
		$objects = WorkorderPeer::doSelect($criteria, $con);
		foreach ($objects as $obj) {


			// delete related WorkorderItem objects
			$c = new Criteria(WorkorderItemPeer::DATABASE_NAME);
			
			$c->add(WorkorderItemPeer::WORKORDER_ID, $obj->getId());
			$affectedRows += WorkorderItemPeer::doDelete($c, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given Workorder object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      Workorder $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(Workorder $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(WorkorderPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(WorkorderPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(WorkorderPeer::DATABASE_NAME, WorkorderPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        foreach ($res as $failed) {
            $col = WorkorderPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
        }
    }

    return $res;
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     Workorder
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = WorkorderPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
		$criteria->add(WorkorderPeer::ID, $pk);

		$v = WorkorderPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(WorkorderPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
			$criteria->add(WorkorderPeer::ID, $pks, Criteria::IN);
			$objs = WorkorderPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseWorkorderPeer

// This is the static code needed to register the MapBuilder for this table with the main Propel class.
//
// NOTE: This static code cannot call methods on the WorkorderPeer class, because it is not defined yet.
// If you need to use overridden methods, you can add this code to the bottom of the WorkorderPeer class:
//
// Propel::getDatabaseMap(WorkorderPeer::DATABASE_NAME)->addTableBuilder(WorkorderPeer::TABLE_NAME, WorkorderPeer::getMapBuilder());
//
// Doing so will effectively overwrite the registration below.

Propel::getDatabaseMap(BaseWorkorderPeer::DATABASE_NAME)->addTableBuilder(BaseWorkorderPeer::TABLE_NAME, BaseWorkorderPeer::getMapBuilder());

