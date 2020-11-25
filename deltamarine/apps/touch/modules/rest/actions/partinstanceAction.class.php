<?php

class partinstanceAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //get a single part -- multiple parts go below
    if ($request->getParameter('id')){
      $this->forward404Unless($inst = PartInstancePeer::retrieveByPk($request->getParameter('id')));
      $woi = $inst->getWorkorderItem();
      $var = $inst->getPartVariant();
      $part = $var->getPart();
      $instsdata = array(
        'id'              => $inst->getId(),
        'part_id'         => $part->GetId(),
        'part_name'       => $part->getName(),
        'sku'             => $part->getDefaultVariant()->getInternalSku(),
        'part_variant_id' => $inst->getPartVariantId(),
        'quantity'        => $inst->getQuantity(), 
        'employee_id'     => $inst->getAddedBy(),
        'employee_name'   => ($inst->getAddedBy() ? $inst->getEmployee()->generateName() : ''),
        'serial_number'   => $inst->getSerialNumber(),
        'date_used'       => $inst->getDateUsed('U'),
        'task_id'         => $inst->getWorkorderItemId(),
        'task_name'       => ($woi ? $woi->getLabel() : null),
        'task_hierarchy'  => ($woi ? $woi->getHierarchy(' &gt; ') : null),
        'workorder_id'    => ($woi ? $woi->getWorkorderId() : null),
        'boat_name'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getName() : null),
        'boat_type'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getMakeModel() : null)
      );
      $insts = array($instsdata);

      return array('success' => true, 'instances' => $insts);   
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


    //find the parts for the given time period
    $c = new Criteria();
    if ($request->hasParameter('employee_id') && ($emp = EmployeePeer::retrieveByPk($request->getParameter('employee_id'))))
    {
      $c->add(PartInstancePeer::ADDED_BY, $emp->getId()); 
    }
    $c1 = $c->getNewCriterion(PartInstancePeer::DATE_USED, $endtime, Criteria::LESS_EQUAL);
    $c2 = $c->getNewCriterion(PartInstancePeer::DATE_USED, $starttime, Criteria::GREATER_EQUAL);
    $c1->addAnd($c2);
    $c->addAnd($c1);

    //filter by other parameter
    //TODO

    //generate JSON output
    $insts = array();
    foreach (PartInstancePeer::doSelect($c) as $inst)
    {
      $woi = $inst->getWorkorderItem();
      $var = $inst->getPartVariant();
      $part = $var->getPart();
      $instsdata = array(
        'id'              => $inst->getId(),
        'part_id'         => $part->GetId(),
        'part_name'       => $part->getName(),
        'category_hierarchy' => $part->getPartCategory()->getHierarchy(),
        'category_name'   => $part->getPartCategory()->getName(),
        'sku'             => $part->getDefaultVariant()->getInternalSku(),
        'part_variant_id' => $inst->getPartVariantId(),
        'quantity'        => $inst->getQuantity(), 
        'employee_id'     => $inst->getAddedBy(),
        'employee_name'   => ($inst->getAddedBy() ? $inst->getEmployee()->generateName() : ''),
        'serial_number'   => $inst->getSerialNumber(),
        'date_used'       => $inst->getDateUsed('U'),
        'task_id'         => $inst->getWorkorderItemId(),
        'task_name'       => ($woi ? $woi->getLabel() : null),
        'task_hierarchy'  => ($woi ? $woi->getHierarchy(' &gt; ') : null),
        'workorder_id'    => ($woi ? $woi->getWorkorderId() : null),
        'customer_name'   => ($woi ? $woi->getWorkorder()->getCustomer()->generateName() : null),
        'boat_name'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getName() : null),
        'boat_type'       => ($woi ? $woi->getWorkorder()->getCustomerBoat()->getMakeModel() : null)
      );
      $insts[] = $instsdata;
    }
    
    $dataarray = array('success' => true, 'instances' => $insts);

    return $dataarray;
  }

  //delete an existig one
  public function delete($req)
  {
    if ($inst = PartInstancePeer::retrieveByPk($req->id))
    {
      $inst->delete();
      return array('success' => 'Part deleted');
    } 
    else
    {
      return array('error' => 'Couldn\'t get part info to delete it.');
    }
  }
  
  //add a new one
  public function post($req, $existing = false)
  {
    //do validation
    $result = true;
    $errors = array();
    if (!($var = PartVariantPeer::retrieveByPk($req->part_variant_id)))
    {
      $result = false;
      $errors['part_variant_id'] = 'Invalid Part specified!';
    }
    if (!($emp = EmployeePeer::retrieveByPk($req->employee_id)))
    {
      $result = false;
      $errors['employee_id'] = 'Error passing along employee details!';
    }
    if ($req->quantity <= 0){
      $result = false;
      $errors['quantity'] = 'Quantity must be positive!';
    }
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
    if ($req->date_used < (mktime(0,0,0) - (3 * 86400)))
    {
      $result = false;
      $errors['time_used'] = 'Cannot set a date more than 3 days ago';
    }


    if (!$result) {
      return array('success' => true, 'errors' => implode("<br />", $errors));
    }

    //do saving
    if ($existing){
      $inst = $existing;
    }
    else
    {
      $inst = new PartInstance();
    }

    $inst->setPartVariant($var);
    $inst->setQuantity((float) $req->quantity);
    $inst->setWorkorderItem($woi);
    $inst->setEmployee($emp);
    $inst->setDateUsed(round($req->date_used));
    $inst->copyDefaults();
    $inst->save();

    $inst->allocate();
    $inst->deliver(); 
    return array('success' => true, 'newid' => $inst->getId());
  }

  //save an edited one
  public function put($req)
  {
    $this->forward404Unless($inst = PartInstancePeer::retrieveByPk($req->id));
    
    return $this->post($req, $inst);
  }
}
