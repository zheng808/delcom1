<?php

class WorkorderPeer extends BaseWorkorderPeer
{

  public static function getActiveByNameLetter($boatname = null)
  {
    if ($boatname)
    {
      $query = 'SELECT substring(%s, 1, 1), count(%s) FROM %s,%s,%s,%s WHERE %s = %s and %s = %s and %s = %s and %s LIKE \'%s\' AND %s = \'%s\' group by substring(%s,1,1)';
      $query = sprintf($query, wfCRMPeer::LAST_NAME, wfCRMPeer::LAST_NAME,
                               self::TABLE_NAME, CustomerBoatPeer::TABLE_NAME, CustomerPeer::TABLE_NAME, wfCRMPeer::TABLE_NAME,
                               self::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID,
                               self::CUSTOMER_ID, CustomerPeer::ID,
                               CustomerPeer::WF_CRM_ID, wfCRMPeer::ID,
                               CustomerBoatPeer::NAME, $boatname.'%',
                               self::STATUS, 'In Progress',
                               wfCRMPeer::LAST_NAME);
    }
    else
    {
      $query = 'SELECT substring(%s, 1, 1), count(%s) FROM %s,%s,%s WHERE %s = %s and %s = %s and %s = \'%s\' group by substring(%s,1,1)';
      $query = sprintf($query, wfCRMPeer::LAST_NAME, wfCRMPeer::LAST_NAME,
                               self::TABLE_NAME, CustomerPeer::TABLE_NAME, wfCRMPeer::TABLE_NAME,
                               self::CUSTOMER_ID, CustomerPeer::ID,
                               CustomerPeer::WF_CRM_ID, wfCRMPeer::ID,
                               self::STATUS, 'In Progress', 
                               wfCRMPeer::LAST_NAME);
    }

    $conn = Propel::getConnection();
    $statement = $conn->prepare($query);
    $statement->execute();
    $names = array();
    while ($row = $statement->fetch(PDO::FETCH_NUM))
    {
      $names[$row[0]] = $row[1];
    }
    return $names;
  }

  public static function getActiveByBoatLetter($custname = null)
  {
    if ($custname)
    {
      $query = 'SELECT substring(%s, 1, 1), count(%s) FROM %s,%s,%s,%s WHERE %s = %s and %s = %s and %s = %s and %s LIKE \'%s\' AND %s = \'%s\' group by substring(%s,1,1)';
      $query = sprintf($query, CustomerBoatPeer::NAME, CustomerBoatPeer::NAME,
                               self::TABLE_NAME, CustomerBoatPeer::TABLE_NAME, CustomerPeer::TABLE_NAME, wfCRMPeer::TABLE_NAME,
                               self::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID,
                               self::CUSTOMER_ID, CustomerPeer::ID,
                               CustomerPeer::WF_CRM_ID, wfCRMPeer::ID,
                               wfCRMPeer::LAST_NAME, $custname.'%',
                               self::STATUS, 'In Progress',
                               CustomerBoatPeer::NAME);
    }
    else
    {
      $query = 'SELECT substring(%s, 1, 1), count(%s) FROM %s,%s WHERE %s = %s and %s = \'%s\' group by substring(%s,1,1)';
      $query = sprintf($query, CustomerBoatPeer::NAME, CustomerBoatPeer::NAME,
                               self::TABLE_NAME, CustomerBoatPeer::TABLE_NAME,
                               self::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID,
                               self::STATUS, 'In Progress',
                               CustomerBoatPeer::NAME);
    }

    $conn = Propel::getConnection();
    $statement = $conn->prepare($query);
    $statement->execute();
    $names = array();
    while ($row = $statement->fetch(PDO::FETCH_NUM))
    {
      $names[$row[0]] = $row[1];
    }

    return $names;
  }

  public static function getItemsProgress($ids)
  {
    $sql = 'SELECT '.WorkorderItemPeer::WORKORDER_ID.', SUM('.WorkorderItemPeer::COMPLETED.'), COUNT('.WorkorderItemPeer::ID.')'.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' IN ('.implode(',', $ids).')'.
           ' GROUP BY '.WorkorderItemPeer::WORKORDER_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = array($row[1], $row[2] - 1); //subtract root node
    }

    return $result;
  }

  public static function doCountForListing($c = null, $con = null)
  {
    $c = ($c ? clone $c : new Criteria());

    //add joins so that searching works
    $c->addJoin(self::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID);
    $c->addJoin(self::CUSTOMER_ID, CustomerPeer::ID);
    $c->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);

    return self::doCount($c, $con);
  }

  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    else {
      $c = clone $c;
    }
    
    self::addSelectColumns($c);
    CustomerPeer::addSelectColumns($c);
    wfCRMPeer::addSelectColumns($c);
    CustomerBoatPeer::addSelectColumns($c);

    $cust_startcol  = (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $crm_startcol   = $cust_startcol + (CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS);
    $boat_startcol  = $crm_startcol  + (wfCRMPeer::NUM_COLUMNS - wfCRMPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::CUSTOMER_ID, CustomerPeer::ID);
    $c->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);
    $c->addJoin(self::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID);

    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $wo_key = WorkorderPeer::getPrimaryKeyHashFromRow($row, 0);
      $wo = new Workorder();
      $wo->hydrate($row);
      WorkorderPeer::addInstanceToPool($wo, $wo_key);

      $cust_key = CustomerPeer::getPrimaryKeyHashFromRow($row, $cust_startcol);
      $cust = new Customer();
      $cust->hydrate($row, $cust_startcol);
      CustomerPeer::addInstanceToPool($cust, $cust_key);

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      $crm = new wfCRM();
      $crm->hydrate($row, $crm_startcol);
      wfCRMPeer::addInstanceToPool($crm, $crm_key);
      $cust->setwfCRM($crm);
      $wo->setCustomer($cust);

      $boat_key = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $boat_startcol);
      $boat = new CustomerBoat();
      $boat->hydrate($row, $boat_startcol);
      CustomerBoatPeer::addInstanceToPool($boat, $boat_key);
      $wo->setCustomerBoat($boat);
      
      $results[] = $wo;
    } 
    
    unset($stmt, $row);   
      
    return $results;
  }

  public static function getReportData($workorder_id = null, Criteria $workorder_c = null, $sub_by_task = false, $sub_by_type = false, $sub_by_profit = false)
  {
    //get the set of workorders to work off of
    if ($workorder_id) $workorder_ids = array($workorder_id);
    else if ($workorder_c)
    {
      $wos = self::doSelect($workorder_c);
      $workorder_ids = array();
      foreach ($wos AS $wo)
      {
        $workorder_ids[] = $wo->getId();
      }
      unset($wos);
    }
    else
    {
      return null;
    }

    $data = self::report_empty_arr(false, false);
    foreach ($workorder_ids AS $wo_id)
    {
      $data[$wo_id] = self::report_empty_arr(true);
    }

    //fetch all expense information
    $expense_query = 'SELECT '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.','.
        ' SUM('.WorkorderExpensePeer::COST.') as cost,'.
        ' SUM('.WorkorderExpensePeer::PRICE.') as price,'.
        ' SUM(IF('.WorkorderExpensePeer::COST.' = 0, '.WorkorderExpensePeer::PRICE.', 0)) as unknown,'.
        ' '.WorkorderItemPeer::LABEL.' AS taskname'.
      ' FROM '.WorkorderItemPeer::TABLE_NAME.' LEFT JOIN '.WorkorderExpensePeer::TABLE_NAME.
        ' ON ('.WorkorderItemPeer::ID.' = '. WorkorderExpensePeer::WORKORDER_ITEM_ID.')'.
      ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' IN ('.implode(',', $workorder_ids).')'.
      ' AND '.WorkorderExpensePeer::ESTIMATE.' = 0'.
      ' GROUP BY '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.', '.WorkorderItemPeer::LABEL;
    
    $con = Propel::getConnection();
    $stmt = $con->prepare($expense_query);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      self::report_add_task($data, $row, 'expenses');
    }
    unset($row,$stmt);

    //fetch all part information
    $part_query = 'SELECT '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.','.
        ' SUM('.PartInstancePeer::UNIT_COST.' * '.PartInstancePeer::QUANTITY.') as cost,'.
        ' SUM('.PartInstancePeer::UNIT_PRICE.' * '.PartInstancePeer::QUANTITY.') as price,'.
        ' SUM(IF('.PartInstancePeer::UNIT_COST.' = 0, '.PartInstancePeer::UNIT_PRICE.' * '.PartInstancePeer::QUANTITY.', 0)) as unknown,'.
        ' '.WorkorderItemPeer::LABEL.' AS taskname'.
      ' FROM '.WorkorderItemPeer::TABLE_NAME.','.PartInstancePeer::TABLE_NAME.
      ' WHERE '.WorkorderItemPeer::ID.' = '. PartInstancePeer::WORKORDER_ITEM_ID.
      ' AND '.WorkorderItemPeer::WORKORDER_ID.' IN ('.implode(',', $workorder_ids).')'.
      ' AND '.PartInstancePeer::ALLOCATED.' = 1'.
      ' GROUP BY '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.', '.WorkorderItemPeer::LABEL;
    
    $con = Propel::getConnection();
    $stmt = $con->prepare($part_query);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      self::report_add_task($data, $row, 'parts');
    }
    unset($row,$stmt);

    //fetch all labour information (compiled into top-level bits)
    $labour_query = 'SELECT '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.','.
        ' SUM('.EmployeePeer::PAYRATE.' * '.TimelogPeer::PAYROLL_HOURS.') as cost,'.
        ' SUM('.TimelogPeer::COST.') as price,'.
        ' 0 as unknown, '.WorkorderItemPeer::LABEL.' as taskname'.
      ' FROM '.WorkorderItemPeer::TABLE_NAME.','.TimelogPeer::TABLE_NAME.','.EmployeePeer::TABLE_NAME.
      ' WHERE '.WorkorderItemPeer::ID.' = '. TimelogPeer::WORKORDER_ITEM_ID.
      ' AND '. TimelogPeer::EMPLOYEE_ID.' = '. EmployeePeer::ID.
      ' AND '.WorkorderItemPeer::WORKORDER_ID.' IN ('.implode(',', $workorder_ids).')'.
      ' AND '.TimelogPeer::APPROVED.' = 1'.
      ' GROUP BY '.WorkorderItemPeer::WORKORDER_ID.', '.WorkorderItemPeer::ID.', '.WorkorderItemPeer::LABEL;
    
    $con = Propel::getConnection();
    $stmt = $con->prepare($labour_query);
    $stmt->execute();
    $result = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      self::report_add_task($data, $row, 'labour');
    }
    unset($row,$stmt);

    self::report_create_tree($data);

    self::report_apply_discounts($data, $workorder_ids);

    self::report_flatten_tree($data, $workorder_ids);

    return $data;
  }

  private static function report_create_tree(&$data)
  {
    foreach ($data AS $workorder_id => $wo_data)
    {
      if (is_int($workorder_id))
      {
        $tree = WorkorderItemPeer::retrieveTree($workorder_id);
        $wo_data = $data[$workorder_id];
        foreach ($tree->getChildren() AS $toplevel)
        {
          if ($toplevel->hasChildren())
          {
            $wo_data = self::_report_tree_recurse($wo_data, $toplevel);
          }
        }
        $data[$workorder_id] = $wo_data;
      }
    }

  }

  private static function _report_tree_recurse($data, $parent)
  {
    if (!isset($data['tasks'][$parent->getId()]['children'])) $data['tasks'][$parent->getId()]['children'] = array();

    foreach ($parent->getChildren() as $child)
    {
      if ($child->hasChildren())
      {
        $data = self::_report_tree_recurse($data, $child);
      }

      $data['tasks'][$parent->getId()]['children'][$child->getId()] = $data['tasks'][$child->getId()];
      unset($data['tasks'][$child->getId()]);
    }

    return $data;
  }

  private static function report_apply_discounts(&$data, $wo_ids)
  {
    $c = new Criteria();
    $c->add(WorkorderItemPeer::WORKORDER_ID, $wo_ids, Criteria::IN);
    $c->addJoin(WorkorderItemPeer::ID, WorkorderItemBillablePeer::WORKORDER_ITEM_ID);
    $c1 = $c->getNewCriterion(WorkorderItemBillablePeer::IN_HOUSE_PARTS_PERCENT, 0, Criteria::GREATER_THAN);
    $c2 = $c->getNewCriterion(WorkorderItemBillablePeer::IN_HOUSE_LABOUR_PERCENT, 0, Criteria::GREATER_THAN);
    $c1->addOr($c2);
    $c->addAnd($c1);

    $discounts = WorkorderItemBillablePeer::doSelectJoinWorkorderItem($c);
    foreach ($discounts AS $discount)
    {
      $task = $discount->getWorkorderItem();
      $to_change = array(&$data[$task->getWorkorderId()]['tasks']);
      if ($discount->getInHousePartsPercent())
      {
        self::_report_calc_discount_recursive($data, $task, 'parts', $discount->getInHousePartsPercent(), $discount->getRecurse());
      }
      if ($discount->getInHouseLabourPercent())
      {
        self::_report_calc_discount_recursive($data, $task, 'labour', $discount->getInHouseLabourPercent(), $discount->getRecurse());
        self::_report_calc_discount_recursive($data, $task, 'expenses', $discount->getInHouseLabourPercent(), $discount->getRecurse());
      }
    }


  }

  private static function _report_calc_discount_recursive(&$data, $task, $type, $pct, $recurse, $parent = null)
  {

      $mods = array(0, 0, 0, 0, 0);
      if (!$parent && $recurse && $task->hasChildren())
      {
        foreach ($task->getChildren() AS $child)
        {
          if (isset($data[$task->getWorkorderId()]['tasks'][$task->getId()]['children'][$child->getId()]))
          {
            self::_report_calc_discount_recursive($data, $child, $type, $pct, true, $task);
          }
        }
      }

      if ($parent)
      {
        $child = $task;
        $task = $parent;
        $disc_amt = $pct/100 * $data[$task->getWorkorderId()]['tasks'][$task->getId()]['children'][$child->getId()][$type]['price'];
      }
      else
      {
        $disc_amt = $pct/100 * $data[$task->getWorkorderId()]['tasks'][$task->getId()][$type]['price'];
      }
      $to_incr = array(
        &$data[$task->getWorkorderId()]['tasks'][$task->getId()][$type],
        &$data[$task->getWorkorderId()]['tasks'][$task->getId()],
        &$data[$task->getWorkorderId()][$type],
        &$data[$task->getWorkorderId()],
        &$data
      );
      if ($parent)
      {
        $to_incr[] = &$data[$task->getWorkorderId()]['tasks'][$task->getId()]['children'][$child->getId()][$type];
        $to_incr[] = &$data[$task->getWorkorderId()]['tasks'][$task->getId()]['children'][$child->getId()];
      }

      array_walk($to_incr, 
        array('self', 'report_increment'), 
        array(0, 0, 0, - $disc_amt, $disc_amt)
      );
  }

  //doesn't actually flatten, just reduces to top-level items only
  private static function report_flatten_tree(&$data, $wo_ids)
  {
    foreach ($wo_ids AS $wo_id)
    {
      if($data[$wo_id])
      {
        foreach ($data[$wo_id]['tasks'] AS $woi_id => $woi_data)
        {
          if (isset($woi_data['children']))
          {
            unset($data[$wo_id]['tasks'][$woi_id]['children']);
          }
        }
      }
    }

  }

  private static function report_add_task(&$out_arr, $row, $type)
  {
    $workorder_id = $row['WORKORDER_ID'];
    $item_id = $row['ID'];

    if (!isset($out_arr[$workorder_id]['tasks'][$item_id]))
    {
      $out_arr[$workorder_id]['tasks'][$item_id] = self::report_empty_arr();
      $out_arr[$workorder_id]['tasks'][$item_id]['name'] = $row['taskname'];
    }

    $to_incr = array(
      &$out_arr[$workorder_id]['tasks'][$item_id][$type],
      &$out_arr[$workorder_id]['tasks'][$item_id],
      &$out_arr[$workorder_id][$type],
      &$out_arr[$workorder_id],
      &$out_arr
    );

    array_walk($to_incr, 
      array('self', 'report_increment'), 
      array($row['cost'], $row['price'], $row['unknown'], $row['price'] - $row['unknown'] - $row['cost'], 0)
    );
  }

  public static function report_increment(&$src, $key, $info)
  {
    $src['cost']    += round($info[0], 2);
    $src['price']   += round($info[1], 2);
    $src['unknown'] += round($info[2], 2);
    $src['profit']  += round($info[3], 2);
    $src['discounts'] += round($info[4], 2);
  }

  public static function report_empty_arr($add_tasks = false, $add_types = true)
  {
    $ret = array( 'cost' => 0, 'profit' => 0, 'unknown' => 0, 'discounts' => 0 );
    if ($add_tasks)
    {
      $ret['tasks'] = array();
    }
    if ($add_types)
    {
        $ret['expenses'] = array('cost' => 0, 'price' => 0, 'profit' => 0, 'unknown' => 0, 'discounts' => 0);        
        $ret['parts']    = array('cost' => 0, 'price' => 0, 'profit' => 0, 'unknown' => 0, 'discounts' => 0);
        $ret['labour']   = array('cost' => 0, 'price' => 0, 'profit' => 0, 'unknown' => 0, 'discounts' => 0);
    }

    return $ret;
  }

  public static function getColorCodesJSArray($include_any = false)
  {
    //taken from HTML color codes
    return "[".($include_any ? "['','All']," : '')."[['33DD33','Green'],'0000FF','Blue'],['FFA500','Orange'],['FF3333','Red']]";
    //return "[".($include_any ? "['','All']," : '')."['FFFFFF', 'White'],['0000FF','Blue'],['000000','Black'],['33DD33','Green'],['FF3333','Red'],['FFFF00','Yellow'],['FFA500','Orange'],['A52A2A','Brown']]";
  }

  public static function getItemColorCodesJSArray($include_any = false)
  {
    //taken from HTML color codes
    return "[".($include_any ? "['','All']," : '')."['FFFFFF', 'White'],['0000FF','Blue'],['000000','Black'],['33DD33','Green'],['FF3333','Red'],['FFFF00','Yellow'],['FFA500','Orange'],['A52A2A','Brown']]";
  }


  public static function getColorCodesArray(){

    return array(
      '33DD33' => 'Green',
      '0000FF' => 'Blue',
      'FFA500' => 'Orange',
      'FF3333' => 'Red'
    );

  }

  public static function getTaskColorCodesArray(){

    return array(
      '808080' => 'BoldGrey',
      'ffff99' => 'Yellow',
      'cc6600' => 'Brown',
      'ffa500' => 'Orange',
      'd2b48c' => 'Tan',
      '008080' => 'Teal',
      '800080' => 'Purple',
      'FF33F6' => 'Pink',
      '5DADE2' => 'LightBlue'
    );
    
  }

  public static function getItemColorCodesArray(){

    return array(
      'FFFFFF' => 'Grey',
      '0066ff' => 'Blue',
      '000000' => 'Black',
      '52BE80' => 'Green',
      'FF3300' => 'Red',
      '996623' => 'Brown'
    );
    
    
  }

}
