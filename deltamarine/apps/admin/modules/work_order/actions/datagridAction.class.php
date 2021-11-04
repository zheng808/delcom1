<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();
    $haulin = false;
    $haulout = false;

    //filter by boat
    if ($request->getParameter('boat_id'))
    {
      $c->add(WorkorderPeer::CUSTOMER_BOAT_ID, $request->getParameter('boat_id'));
    }

    if ($request->getParameter('haulout') == '1')
    {
      $start_of_today = mktime(0,0,0);
      $c->add(WorkorderPeer::HAULOUT_DATE, null, Criteria::ISNOTNULL);
      $c->add(WorkorderPeer::HAULOUT_DATE, $start_of_today, Criteria::GREATER_EQUAL);
      $haulout = true;
    }
    else if ($request->getParameter('haulin') == '1')
    {
      $start_of_today = mktime(0,0,0);      
      $c->add(WorkorderPeer::HAULIN_DATE, null, Criteria::ISNOTNULL);
      $c->add(WorkorderPeer::HAULIN_DATE, $start_of_today, Criteria::GREATER_EQUAL); 
      $haulin = true;
    }

    //filter by rigging
    if ($request->getParameter('for_rigging'))
    {
      if ($request->getParameter('for_rigging') == '1')
      {
        $c->add(WorkorderPeer::FOR_RIGGING, true);
      }
      else if ($request->getParameter('for_rigging') == '2')
      {
        $c->add(WorkorderPeer::FOR_RIGGING, false);
      }
    }
    //filter by category
    $catid = $request->getParameter('workorder_category_id');
    if ($catid === '-1')
    {
      $c->add(WorkorderPeer::WORKORDER_CATEGORY_ID, null, Criteria::ISNULL);
    }
    else if ($catid && ($cat = WorkorderCategoryPeer::retrieveByPk($catid)))
    {
      $c->add(WorkorderPeer::WORKORDER_CATEGORY_ID, $cat->getId());
    }
    //filterby boat type
    if ($request->getParameter('boat_type') && (strpos($request->getParameter('boat_type'), '::') !== false))
    {
      $type = explode('::', $request->getParameter('boat_type'));
      if (isset($type[0]) && $type[0])
      {
        $c->addJoin(WorkorderPeer::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID);
        $c->add(CustomerBoatPeer::MAKE, $type[0]);
      }
      if (isset($type[1]) && $type[1])
      {
        $c->add(CustomerBoatPeer::MODEL, $type[1]);
      }
    }
    //filter by customer
    if ($request->getParameter('customer_id'))
    {
      $c->add(WorkorderPeer::CUSTOMER_ID, $request->getParameter('customer_id'));
    }
    //filter by status
    if ($request->getParameter('status'))
    {
      $c->add(WorkorderPeer::STATUS, $request->getParameter('status'));
    }
    //search by boat or customer name (for timelog filtering)
    if ($request->getParameter('query'))
    {
      //check to see if # is included
      if (preg_match('/^#?([0-9]+)/', $request->getParameter('query'), $matches))
      {
        $c->add(WorkorderPeer::ID, $matches[1]);
      }
      else
      {
        $query = '%'.$request->getParameter('query').'%';
        $c->addJoin(WorkorderPeer::CUSTOMER_BOAT_ID, CustomerBoatPeer::ID);
        $c->addJoin(WorkorderPeer::CUSTOMER_ID, CustomerPeer::ID);
        $c->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);
        $c_a = $c->getNewCriterion(CustomerBoatPeer::NAME, $query, Criteria::LIKE);
        $c_b = $c->getNewCriterion(wfCRMPeer::ALPHA_NAME, $query, Criteria::LIKE);
        $c_a->addOr($c_b);
        $c->addAnd($c_a);
      }
    }
    //filter by start date and/or end date
    $startdate = strtotime($request->getParameter('start_date'));
    $enddate = strtotime($request->getParameter('end_date'));
    if ($startdate && $enddate)
    {
      $c_a = $c->getNewCriterion(WorkorderPeer::CREATED_ON, $startdate, Criteria::GREATER_EQUAL);
      $c_b = $c->getNewCriterion(WorkorderPeer::CREATED_ON, $enddate + 86399, Criteria::LESS_EQUAL);
      $c_a->addAnd($c_b);
      $c->addAnd($c_a);
    }
    else if ($startdate)
    {
      $c->add(WorkorderPeer::CREATED_ON, $startdate, Criteria::GREATER_EQUAL);
    }
    else if ($enddate)
    {
      $c->add(WorkorderPeer::CREATED_ON, $enddate + 86399, Criteria::LESS_EQUAL);
    }
    //filter by color code
    if ($request->getParameter('color'))
    {
      $c->add(WorkorderPeer::SUMMARY_COLOR, $request->getParameter('color'));
    }


    //copy the criteria for later total count
    $c2 = clone $c;

    //sort
    switch ($request->getParameter('sort', 'date'))
    {
    case 'id':
      $col = WorkorderPeer::ID;
      break;
    case 'customer':
      $col = wfCRMPeer::ALPHA_NAME;
      break;
    case 'date':
      $col = WorkorderPeer::CREATED_ON;
      break;
    case 'status':
      $col = WorkorderPeer::STATUS;
      break;
    case 'haulout':
      $col = WorkorderPeer::HAULOUT_DATE;
      break;
    case 'haulin':
      $col = WorkorderPeer::HAULIN_DATE;
      break;
    case 'category_name':
      $c->addJoin(WorkorderPeer::WORKORDER_CATEGORY_ID, WorkorderCategoryPeer::ID, Criteria::LEFT_JOIN);
      $col = WorkorderCategoryPeer::NAME;
      break;
    case 'division_name':
      $col = WorkorderPeer::DIVISION;
      break;
    }
    ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                    :  $c->addDescendingOrderByColumn($col));

    if ($col != 'date')
    {
      $c->addDescendingOrderByColumn(WorkorderPeer::CREATED_ON);
    }

    //paging
    if ($request->getParameter('limit'))
    {
      $c->setLimit($request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $c->setOffset($request->getParameter('start'));
    }

    $workorders = WorkorderPeer::doSelectForListing($c);
    $count_all = WorkorderPeer::doCount($c2);

    //get completion information
    $completion = array();
    if (count($workorders) > 0)
    {
      foreach ($workorders AS $workorder)
      {
        $completion[] = $workorder->getId();
      }
      $completion = WorkorderPeer::getItemsProgress($completion);
    }

    //generate JSON output
    $workorderarray = array();
    foreach ($workorders AS $workorder)
    {

      $workorderarray[] = array(
        'id'       => $workorder->getId(), 
        'summary'  => '#'.$workorder->getId().' - '.$workorder->getCustomerBoat()->getName(),
        'customer' => $workorder->getCustomer()->getName(false, false, false),
        'boat'     => $workorder->getCustomerBoat()->getName(),
        'boattype' => $workorder->getCustomerBoat()->getMakeModel(),
        'date'     => $workorder->getCreatedOn('m/d/Y'),
        'status'   => ucfirst($workorder->getStatus()),
        'haulout'  => ($haulout ? $workorder->getHauloutDateTime('m/d/Y') : $workorder->getHauloutDate('m/d/Y')),
        'haulin'   => ($haulin   ? $workorder->getHaulinDateTime('m/d/Y') : $workorder->getHaulinDate('m/d/Y ')),
        'color'    => $workorder->getSummaryColor(),
        'for_rigging' => $workorder->getForRigging(),
        'division_name' => $workorder->getDivision() == '1' ? 'Delta Marine' : 'Elite Marine',
        'progress' => isset($completion[$workorder->getId()]) ? implode('/',$completion[$workorder->getId()]) : '',
        'pst_exempt' => ($workorder->getPstExempt() ? 'Y' : 'N'), 
        'gst_exempt' => ($workorder->getGstExempt() ? 'Y' : 'N'),
        'tax_exempt' => ($workorder->getGstExempt() || $workorder->getPstExempt() ? 'Y' : 'N'),
        'text'  => '['.$workorder->getId().'] '.$workorder->getCustomer()->getName(false, false, false)
       );
    }
    $dataarray = array('totalCount' => $count_all, 'workorders' => $workorderarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
