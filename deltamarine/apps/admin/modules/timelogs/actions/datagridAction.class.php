<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();
    $c->add(TimelogPeer::ESTIMATE, false);

    //filter by employee
    if ($request->getParameter('employee_id'))
    {
      $c->add(TimelogPeer::EMPLOYEE_ID, $request->getParameter('employee_id'));
    }
    
    //filter by workorder
    if ($request->getParameter('workorder_id'))
    {
      $c->addJoin(TimelogPeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
      $c->add(WorkorderItemPeer::WORKORDER_ID, $request->getParameter('workorder_id'));
    }
    //filter by status
    if ($status = strtolower($request->getParameter('status')))
    {
      if ($status == 'approved')
      {
        $c->add(TimelogPeer::APPROVED, true);
        $c->add(TimelogPeer::ADMIN_FLAGGED, false);
      }
      else if ($status == 'flagged')
      {
        $c->add(TimelogPeer::ADMIN_FLAGGED, true);
      }
      else if ($status == 'unapproved')
      {
        $c->add(TimelogPeer::APPROVED, false);
        $c->add(TimelogPeer::ADMIN_FLAGGED, false);
      }
    }
    //filter by type
    if ($type = strtolower($request->getParameter('type')))
    {
      if ($type == 'billable')
      {
        $c->add(TimelogPeer::LABOUR_TYPE_ID, null, Criteria::ISNOTNULL);
      }
      else if ($type == 'non-billable')
      {
        $c->add(TimelogPeer::NONBILL_TYPE_ID, null, Criteria::ISNOTNULL);
      }
    }
    //filter by start date and/or end date
    $startdate = strtotime($request->getParameter('start_date'));
    $enddate = strtotime($request->getParameter('end_date'));
    if ($startdate && $enddate)
    {
      $c_a = $c->getNewCriterion(TimelogPeer::END_TIME, $startdate, Criteria::GREATER_EQUAL);
      $c_b = $c->getNewCriterion(TimelogPeer::END_TIME, $enddate + 86399, Criteria::LESS_EQUAL);
      $c_a->addAnd($c_b);
      $c->addAnd($c_a);
    }
    else if ($startdate)
    {
      $c->add(TimelogPeer::END_TIME, $startdate, Criteria::GREATER_EQUAL);
    }
    else if ($enddate)
    {
      $c->add(TimelogPeer::END_TIME, $enddate + 86399, Criteria::LESS_EQUAL);
    }

    //make a copy for counting purposes
    $c2 = clone $c;

    //sort
    $dir = $request->getParameter('dir', 'DESC');
    $sort = $request->getParameter('sort', 'date');
    if ($sort == 'status')
    {
      if ($dir == 'ASC')
      {
        $c->addAscendingOrderByColumn(TimelogPeer::APPROVED);
        $c->addAscendingOrderByColumn(TimelogPeer::ADMIN_FLAGGED);
      }
      else
      {
        $c->addAscendingOrderByColumn(TimelogPeer::ADMIN_FLAGGED);
        $c->addAscendingOrderByColumn(TimelogPeer::APPROVED);
      }
    }
    else
    {
      if ($sort == 'employee')
      {
        $col = TimelogPeer::EMPLOYEE_ID;
      }
      else if ($sort == 'date')
      {
        $col = TimelogPeer::END_TIME;
      }
      ($dir == 'ASC' ? $c->addAscendingOrderByColumn($col)
                     : $c->addDescendingOrderByColumn($col));
    }
    $c->addDescendingOrderByColumn(TimelogPeer::ID);

    //paging
    if ($request->getParameter('limit')) $c->setLimit($request->getParameter('limit'));
    if ($request->getParameter('start')) $c->setOffset($request->getParameter('start'));

    //perform queries
    $timelogs = TimelogPeer::doSelectForListing($c);
    $count_all = TimelogPeer::doCount($c2);

    
    //generate JSON output
    $timelogarray = array();
    foreach ($timelogs AS $timelog)
    {
      if ($timelog->getLabourTypeId())
      {
        $billable = true;
        $type = ($timelog->getLabourType()
                  ? $timelog->getLabourType()->getName()
                  : "General");
        $rate = $timelog->getRate();
      }
      else if ($timelog->getCustomLabel())
      {
        $billable = true;
        $type = $timelog->getCustomLabel();
        $rate = $timelog->getRate();
      }
      else
      {
        $billable = false;
        $type = ($timelog->getNonbillType()
                  ? $timelog->getNonbillType()->getName()
                  : "General");
        $rate = null;
      }
      $workorderID = TimelogPeer::retrieveWorkOrderID($timelog->getId());
      $timelogarray[] = array('id'          => $timelog->getId(), 
                              'date'        => $timelog->getEndTime('m/d/Y'),
                              'employee_id' => $timelog->getEmployeeId(),
                              'employee'    => ($timelog->getEmployee() ? $timelog->getEmployee()->getName(false, false, false) : '[Estimate]'),
                              'billable'    => $billable, 
                              'type'        => $type,
                              'rate'        => ($rate ? $rate : 'Unknown'),
                              'cost'        => ($timelog->getCost() ? $timelog->getCost() : ''),
                              'payroll_hours'  => $timelog->getPayrollHoursAndMinutes(),
                              'billable_hours' => $timelog->getBillableHoursAndMinutes(),
                              'start_time'  => ($timelog->getStartTime() ? $timelog->getStartTime('g:iA') : ''),
                              'end_time'    => ($timelog->getStartTime() ? $timelog->getEndTime('g:iA') : ''),
                              'workorder'   => $workorderID,
                              'item'        => $timelog->getWorkorderItemName(),
                              'boat'        => $timelog->getWorkorderBoat(),
                              'customer'    => $timelog->getWorkorderCustomerName(),
                              'status'      => $timelog->getStatus(),
                              'custom_label'   =>  $timelog->getCustomLabel(),
                              'employee_notes' => ($timelog->getEmployeeNotes() ? nl2br($timelog->getEmployeeNotes()) : ''),
                              'admin_notes'    => ($timelog->getAdminNotes() ? nl2br($timelog->getAdminNotes()) : ''),
		              'created_at'     => $timelog->getCreatedAt('M/d/Y g:i A'),
		              'updated_at'     => $timelog->getUpdatedAt('M/d/Y g:i A')

                              );
    }
    $dataarray = array('totalCount' => $count_all, 'timelogs' => $timelogarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
