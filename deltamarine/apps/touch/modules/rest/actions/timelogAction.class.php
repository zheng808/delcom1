<?php

class timelogAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    $labourtypes = LabourTypePeer::loadTypesArray();
    $nonbilltypes = NonbillTypePeer::loadTypesArray();

    //get a single timelog -- multiple timelogs go below
    if ($request->getParameter('id')){
      $this->forward404Unless($timelog = TimelogPeer::retrieveByPk($request->getParameter('id')));
      $woi = $timelog->getWorkorderItem();
      $timelogs = array(array(
        'id'              => $timelog->getId(),
        'end_date'        => $timelog->getEndTime('U'),
        'employee_id'     => $timelog->getEmployeeId(),
        'billable'        => ($timelog->getLabourTypeId() ? 1 : 0),
        'task_id'         => $timelog->getWorkorderItemId(),
        'task_name'       => ($woi ? $woi->getLabel() : null),
        'task_hierarchy'  => ($woi ? $woi->getHierarchy(' &gt; ') : null),
        'workorder_id'    => ($woi ? $woi->getWorkorderId() : null),
        'boat_name'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getName() : null),
        'boat_type'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getMakeModel() : null),
        'labour_type_id'  => $timelog->getLabourTypeId(),
        'labour_type_name' => ($timelog->getLabourTypeId() ? $labourtypes[$timelog->getLabourTypeId()]['name'] : null),
        'nonbill_type_id' => $timelog->getNonbillTypeId(),
        'nonbill_type_name' => ($timelog->getNonbillTypeId() ? $nonbilltypes[$timelog->getNonbillTypeId()]['name'] : null),
        'payroll_hours'   => $timelog->getPayrollHours(),
        'payroll_hourmins'  => $timelog->getPayrollHoursAndMinutes(),
        'billable_hours'  => $timelog->getBillableHours(),
        'notes'           => $timelog->getEmployeeNotes(),
        'approved'        => $timelog->getApproved(),
      ));

      return array('success' => true, 'timelogs' => $timelogs);   
    }



    //calculate the date range for this request
    if (!($day = strtotime($request->getParameter('ondate'))))
    {
      $day = time();
    }
    if ($request->getParameter('onweek'))
    {
      $dow = (int) date('w', $day);
      $starttime = $week - ($dow * 86400); //subtract days back to Sunday
      $starttime = mktime(0,0,0,date('n', $starttime),date('j', $starttime),date('Y', $starttime));
      $endtime = $week + ((6 - $dow) * 86400); //add days up till Saturday
      $starttime = mktime(0,0,0,date('n', $endtime),date('j', $endtime),date('Y', $endtime));
    }
    else 
    {
      $starttime = mktime(0,0,0,date('n', $day),date('j', $day),date('Y', $day));
      $endtime = mktime(23,59,59,date('n', $day),date('j', $day),date('Y', $day));
    }


    //find the timelogs for the given time period
    $c = new Criteria();
    if ($request->hasParameter('employee_id') && ($timelog = EmployeePeer::retrieveByPk($request->getParameter('employee_id'))))
    {
      $c->add(TimelogPeer::EMPLOYEE_ID, $timelog->getId()); 
    }
    $c1 = $c->getNewCriterion(TimelogPeer::END_TIME, $endtime, Criteria::LESS_EQUAL);
    $c2 = $c->getNewCriterion(TimelogPeer::END_TIME, $starttime, Criteria::GREATER_EQUAL);
    $c1->addAnd($c2);
    $c->addAnd($c1);

    //filter by other parameter
    //TODO

    //generate JSON output
    $timelogs = array();
    $stats = array(
      'billable' => array('total' => 0),
      'nonbillable' => array('total' => 0),
      'workorder' => array(),
      'labour_type' => array(),
      'nonbill_type' => array()
    );
    foreach (TimelogPeer::doSelect($c) as $timelog)
    {
      $woi = $timelog->getWorkorderItem();
      $timelogsdata = array(
        'id'              => $timelog->getId(),
        'end_date'        => $timelog->getEndTime('U'),
        'employee_id'     => $timelog->getEmployeeId(),
        'billable'        => ($timelog->getLabourTypeId() ? 1 : 0),
        'task_id'         => $timelog->getWorkorderItemId(),
        'task_name'       => ($woi ? $woi->getLabel() : null),
        'task_hierarchy'  => ($woi ? $woi->getHierarchy(' &gt; ') : null),
        'workorder_id'    => ($woi ? $woi->getWorkorderId() : null),
        'boat_name'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getName() : null),
        'boat_type'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getMakeModel() : null),
        'labour_type_id'  => $timelog->getLabourTypeId(),
        'labour_type_name' => ($timelog->getLabourTypeId() ? $labourtypes[$timelog->getLabourTypeId()]['name'] : null),
        'nonbill_type_id' => $timelog->getNonbillTypeId(),
        'nonbill_type_name' => ($timelog->getNonbillTypeId() ? $nonbilltypes[$timelog->getNonbillTypeId()]['name'] : null),
        'payroll_hours'   => $timelog->getPayrollHours(),
        'payroll_hourmins'  => $timelog->getPayrollHoursAndMinutes(),
        'billable_hours'  => $timelog->getBillableHours(),
        'notes'           => $timelog->getEmployeeNotes(),
        'approved'        => $timelog->getApproved(),
      );
      $timelogs[] = $timelogsdata;

      //add to stats
      $day = $timelog->getEndTime('Ymd');
      if ($timelog->getLabourTypeId())
      {
        $this->addToArray($stats['billable'], 'total', $timelog->getPayrollHours());
        $this->addToArray($stats['billable'], $day, $timelog->getPayrollHours());
        $this->addToArray($stats['workorder'], $woi->getWorkorderId(), $timelog->getPayrollHours());
        $this->addToArray($stats['labour_type'], $timelog->getLabourTypeId(), $timelog->getPayrollHours()); 
      }
      else
      {
        $this->addToArray($stats['nonbillable'], 'total', $timelog->getPayrollHours());
        $this->addToArray($stats['nonbillable'], $day, $timelog->getPayrollHours());
        $this->addToArray($stats['nonbill_type'], $timelog->getNonbillTypeId(), $timelog->getPayrollHours()); 
      }
    }
    
    $dataarray = array('success' => true, 'stats' => $stats, 'timelogs' => $timelogs);

    return $dataarray;
  }

  private function addToArray(&$array, $key, $amount){
    if (!isset($array[$key]))
    {
      $array[$key] = 0;
    }
    $array[$key] += $amount;
  }


  //delete an existig one
  public function delete($req)
  {
    if ($timelog = TimelogPeer::retrieveByPk($req->id))
    {
      $timelog->delete();
      return array('success' => 'Timelog deleted');
    } 
    else
    {
      return array('error' => 'Couldn\'t get timelog to delete it.');
    }
  }
  
  //add a new one
  public function post($req, $existing = false)
  {
    //do validation
    $result = true;
    $errors = array();
    if (!($date = ((int) $req->end_date)))
    { 
      $result = false;
      $errors['end_date'] = 'Invalid date specified!';
    }
    else if ($date > time() + 7200){
      $result = false;
      $errors['end_date'] = 'Cannot set a timelog date in the future!';
    }
    else if ($date < mktime(0,0,0) - 259200){
      $result = false;
      $errors['end_date'] = 'Cannot set a timelog more than 3 days in the past!';
    }
    if (!($emp = EmployeePeer::retrieveByPk($req->employee_id)))
    {
      $result = false;
      $errors['employee_id'] = 'Error passing along employee details!';
    }
    if ($req->payroll_hours < 0.25){
      $result = false;
      $errors['payroll_hours'] = 'Can\'t enter a zero-time timelog!';
    }

    if ($req->billable == 1)
    {
      if (!($wo = WorkorderPeer::retrieveByPk($req->workorder_id)))
      {
        $result = false;
        $errors['workorder_id'] = 'Must supply a valid workorder!';
      }
      else if (!($woi = WorkorderItemPeer::retrieveByPk($req->task_id)))
      {
        $result = false;
        $errors['task_id'] = 'Must supply a valid task!';
      }
      if (!($type = LabourTypePeer::retrieveByPk($req->labour_type_id)))
      {
        $result = false;
        $errors['labour_type_id'] = 'Must select a valid labour type!';
      }
      if ($notes = $req->notes)
      {
        $bad_words = array(' fuck',' shit',' cock',' cunt');
        foreach ($bad_words AS $poo){
          if (strpos($notes, $poo) !== false)
          {
            $result = false;
            $errors['notes'] = 'Notes may be visible to the customer. No swearin\'!';
            break;
          }
        }
      }
    }
    else
    {
      if (!($type = NonbillTypePeer::retrieveByPk($req->nonbill_type_id)))
      {
        $result = false;
        $errors['nonbill_type_id'] = 'Must select a valid work type!';
      }
    }    

    if (!$result) {
      return array('success' => true, 'errors' => implode("<br />", $errors));
    }

    //do saving
    if ($existing){
      $timelog = $existing;
    }
    else
    {
      $timelog = new Timelog();
    }

    $timelog->setEndTime($date);
    $timelog->setEmployeeId($emp->getId());
    $timelog->setEmployeeNotes(trim($req->notes));
    $timelog->setPayrollHours($req->payroll_hours);
    $timelog->setBillableHours($req->payroll_hours);
    $timelog->setTaxableHst(($wo->getHstExempt() ? 0 : sfConfig::get('app_hst_rate'))); 
    $timelog->setTaxablePst(($wo->getPstExempt() ? 0 : sfConfig::get('app_pst_rate'))); 
    $timelog->setTaxableGst(($wo->getGstExempt() ? 0 : sfConfig::get('app_gst_rate'))); 
    if ($req->billable == 1)
    {
      $timelog->setWorkorderItemId($woi->getId());
      $timelog->setLabourTypeId($type->getId());
      $timelog->setNonbillTypeId(null);
    } 
    else
    {
      $timelog->setWorkorderItemId(null);
      $timelog->setLabourTypeId(null);
      $timelog->setNonbillTypeId($type->getId());
    }
    $timelog->setApproved(false);
    $timelog->calculateCost();
    $timelog->save();
    
    return array('success' => true, 'newid' => $timelog->getId());
  }

  //save an edited one
  public function put($req)
  {
    $this->forward404Unless($timelog = TimelogPeer::retrieveByPk($req->id));
    
    return $this->post($req, $timelog);
  }
}
