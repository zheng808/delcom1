<?php

class workorderAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {

    //get the workorders for the given period
    $c = new Criteria();
    $c->add(WorkorderPeer::STATUS, 'In Progress');
    if ($request->getParameter('id'))
    {
        $c->add(WorkorderPeer::ID, $request->getParameter('id'));
    }
    else
    {
        //filter by customer last name
        if ($filter_name = $request->getParameter('filter_name', false))
        {
          $c->add(wfCRMPeer::LAST_NAME, $filter_name.'%', Criteria::LIKE);
        }

        //filter by boat name letter
        if ($filter_boat = $request->getParameter('filter_boat', false))
        {
          $c->add(CustomerBoatPeer::NAME, $filter_boat.'%', Criteria::LIKE);
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

        //filter by recently used
        $empid = $request->getParameter('recent_employee');
        if ($emp = EmployeePeer::retrieveByPk($empid)){
          $c->setDistinct(true);
          $c->addJoin(WorkorderPeer::ID, WorkorderItemPeer::WORKORDER_ID, Criteria::LEFT_JOIN);
          $c->addJoin(WorkorderItemPeer::ID, PartInstancePeer::WORKORDER_ITEM_ID, Criteria::LEFT_JOIN);
          $c->addJoin(WorkorderItemPeer::ID, TimelogPeer::WORKORDER_ITEM_ID, Criteria::LEFT_JOIN);
          $c1 = $c->getNewCriterion(PartInstancePeer::ADDED_BY, $empid);
          $c2 = $c->getNewCriterion(TimelogPeer::EMPLOYEE_ID, $empid);
          $c1->addOr($c2);
          $c->addAnd($c1);
        }
    }

    //clone for getting total count
    $c_count = clone $c;

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
    case 'category_name':
      $c->addJoin(WorkorderPeer::WORKORDER_CATEGORY_ID, WorkorderCategoryPeer::ID, Criteria::LEFT_JOIN);
      $col = WorkorderCategoryPeer::NAME;
      break;
    }
    ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                    :  $c->addDescendingOrderByColumn($col));

    //set paging
    if ($request->getParameter('limit') > 0)
    {
      $c->setOffset($request->getParameter('start'));
      $c->setLimit($request->getParameter('limit'));
    }
  
    $categories = WorkorderCategoryPeer::loadCatsArray();
    $workorders = array();
    foreach (WorkorderPeer::doSelectForListing($c) as $workorder)
    {
      $workorders[] = array(
        'id' => $workorder->getId(),
        'active' => ($workorder->getStatus() == 'In Progress' ? true : false),
        'customer_name' => $workorder->getCustomer()->generateName(),
        'lastnamefirst' => $workorder->getCustomer()->generateName(false, false, true),
        'boat_name' => $workorder->getCustomerBoat()->getName(),
        'boat_type' => $workorder->getCustomerBoat()->getMakeModel(),
        'completion' => implode('/', $workorder->getItemsProgress()),
        'started_on' => $workorder->getStartedOn('M j, Y'),
        'customer_notes' => $workorder->getCustomerNotes(),
        'internal_notes' => $workorder->getInternalNotes(),
        'category_id' => ($workorder->getWorkorderCategoryId() ? $workorder->getWorkorderCategoryId() : 0),
        'category_name' => ($workorder->getWorkorderCategoryId() ? $categories[$workorder->getWorkorderCategoryId()]['name'] : ''),
        'rigging' => ($workorder->getForRigging() ? 1 : 0)
      );
    }
    $count_all = WorkorderPeer::doCount($c_count);
    $dataarray = array('success' => true, 'totalCount' => $count_all, 'workorders' => $workorders);

    return $dataarray;
  }

}
