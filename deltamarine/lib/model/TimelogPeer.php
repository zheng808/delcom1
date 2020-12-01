<?php

class TimelogPeer extends BaseTimelogPeer
{
  public static function doSelectJoinEmployeeAndLabour($c = null)
  {
    if (!$c) $c = new Criteria();

    $c->addJoin(self::EMPLOYEE_ID, EmployeePeer::ID);
    $c->addJoin(EmployeePeer::WF_CRM_ID, wfCRMPeer::ID);
    $c->addJoin(self::LABOUR_TYPE_ID, LabourTypePeer::ID, Criteria::LEFT_JOIN);

    self::addSelectColumns($c);
    EmployeePeer::addSelectColumns($c);
    wfCRMPeer::addSelectColumns($c);
    LabourTypePeer::addSelectColumns($c);

    $log_startcol = 0;
    $emp_startcol = $log_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $crm_startcol = $emp_startcol + (EmployeePeer::NUM_COLUMNS - EmployeePeer::NUM_LAZY_LOAD_COLUMNS);
    $lab_startcol = $crm_startcol + (wfCRMPeer::NUM_COLUMNS - wfCRMPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $time_key = TimelogPeer::getPrimaryKeyHashFromRow($row, $log_startcol);
      $time = new Timelog();
      $time->hydrate($row);
      TimelogPeer::addInstanceToPool($time, $time_key);

      $employee_key = EmployeePeer::getPrimaryKeyHashFromRow($row, $emp_startcol);
      $employee = new Employee();
      $employee->hydrate($row, $emp_startcol);
      EmployeePeer::addInstanceToPool($employee, $employee_key);

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      $crm = new wfCRM();
      $crm->hydrate($row, $crm_startcol);
      wfCRMPeer::addInstanceToPool($crm, $crm_key);

      $lab_key = LabourTypePeer::getPrimaryKeyHashFromRow($row, $lab_startcol);
      $lab = new LabourType();
      $lab->hydrate($row, $lab_startcol);
      LabourTypePeer::addInstanceToPool($lab, $lab_key);

      if ($lab->getId())
      {
        $time->setLabourType($lab);
      }
      $employee->setWfCRM($crm);
      $time->setEmployee($employee);

      $results[] = $time;
    }

    return $results;
  }

  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();
    
    self::addSelectColumns($c);
    WorkorderItemPeer::addSelectColumns($c);
    WorkorderPeer::addSelectColumns($c);
    CustomerBoatPeer::addSelectColumns($c);

    $woi_startcol  = (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $wo_startcol   = $woi_startcol + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);
    $boat_startcol = $wo_startcol  + (WorkorderPeer::NUM_COLUMNS - WorkorderPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::WORKORDER_ITEM_ID, WorkorderItemPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(WorkorderItemPeer::WORKORDER_ID, WorkorderPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(WorkorderPeer::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID, Criteria::LEFT_JOIN);

    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $time_key = TimelogPeer::getPrimaryKeyHashFromRow($row, 0);
      $time = new Timelog();
      $time->hydrate($row);
      TimelogPeer::addInstanceToPool($time, $time_key);

      if ($woi_key = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $woi_startcol))
      {
        $woi = new WorkorderItem();
        $woi->hydrate($row, $woi_startcol);
        WorkorderItemPeer::addInstanceToPool($woi, $woi_key);

        $wo_key = WorkorderPeer::getPrimaryKeyHashFromRow($row, $wo_startcol);
        $wo = new Workorder();
        $wo->hydrate($row, $wo_startcol);
        WorkorderPeer::addInstanceToPool($wo, $wo_key);

        $boat_key = CustomerBoatPeer::getPrimaryKeyHashFromRow($row, $boat_startcol);
        $boat = new CustomerBoat();
        $boat->hydrate($row, $boat_startcol);
        CustomerBoatPeer::addInstanceToPool($boat, $boat_key);

        $wo->setCustomerBoat($boat);
        $woi->setWorkorder($wo);
        $time->setWorkorderItem($woi);
      }

      $results[] = $time;
    } 
    
    unset($stmt, $row);   
      
    return $results;
  }

  public static function retrieveWorkOrderID($TimeLogID){
     $sql = 'select workorder_id from workorder_item where id = (SELECT workorder_item_id FROM deltamarine.timelog where id =' .$TimeLogID.');';
     $con = Propel::getConnection();
     $stmt = $con->prepare($sql);
     $stmt->execute();
     $row = $stmt->fetch(PDO::FETCH_NUM);
     return $row;
  }

  public static function retrieveCustomerName($workOrderID){
    $sql = 'select alpha_name from deltamarine.wf_crm where id = (select wf_crm_id from customer where id = (select customer_id from workorder where id = ' .$workOrderID.'));';
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchall(PDO::FETCH_NUM);
    return $row;
 }
}
