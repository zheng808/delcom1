<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    $c = new Criteria();
    $c2 = new Criteria();
    $c2->addJoin(EmployeePeer::WF_CRM_ID, wfCRMPeer::ID);

    //filter
    if ($request->getParameter('name'))
    {
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('name').'%', Criteria::LIKE);
      $c2->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('name').'%', Criteria::LIKE);
    }

    $status = strtolower($request->getParameter('status'));
    $type = strtolower($request->getParameter('type'));

    if ($type == 'contractors only')
    {
      $c->add(wfCRMPeer::IS_COMPANY, true);
      $c2->add(wfCRMPeer::IS_COMPANY, true);
    }
    else if ($type == 'employees only')
    {
      $c->add(wfCRMPeer::IS_COMPANY, false);
      $c2->add(wfCRMPeer::IS_COMPANY, false);
    }

    if ($status == 'active')
    {
      $c->add(EmployeePeer::HIDDEN, false);
      $c2->add(EmployeePeer::HIDDEN, false);
    }
    else if ($status == 'inactive')
    {
      $c->add(EmployeePeer::HIDDEN, true);
      $c2->add(EmployeePeer::HIDDEN, true);
    }
    if ($request->getParameter('title'))
    {
      $c->add(wfCRMPeer::JOB_TITLE, '%'.$request->getParameter('title').'%', Criteria::LIKE);
      $c2->add(wfCRMPeer::JOB_TITLE, '%'.$request->getParameter('title').'%', Criteria::LIKE);
    }

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'mobile':
      $col = wfCRMPeer::MOBILE_PHONE;
      break;
    case 'home':
      $col = wfCRMPeer::HOME_PHONE;
      break;
    case 'firstname':
      $col = wfCRMPeer::FIRST_NAME;
      break;
    case 'job_title':
      $col = wfCRMPeer::JOB_TITLE;
      break;
    case 'name':
    default:
      $col = wfCRMPeer::ALPHA_NAME;
    }
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));
    if ($request->getParameter('sort') == 'firstname')
    {
        $col = wfCRMPeer::LAST_NAME;
        ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                       :  $c->addDescendingOrderByColumn($col));
        
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

    $firstlast = (bool) $request->getParameter('firstlast', true);
    $checkself = (bool) $request->getParameter('checkself', false);

    if ($checkself && !$this->getUser()->hasCredential('timelogs_add_other') && $this->getUser()->hasCredential('timelogs_add_self'))
    {
      $c->add(EmployeePeer::ID, $this->getUser()->getEmployee()->getId());
      $c2->add(EmployeePeer::ID, $this->getUser()->getEmployee()->getId());
    }

    $employees = EmployeePeer::doSelectForListing($c);
    $count_all = EmployeePeer::doCount($c2);
  
    //generate JSON output
    $employeearray = array();
    foreach ($employees AS $employee)
    {
      $latest_time = strtotime($employee['latest']);
      if ($latest_time)
      {
        if ((mktime(0,0,0) <= $latest_time) && ($latest_time <= mktime(23,59,59))) $latest = 'Today';
        else if ((mktime(0,0,0,date('n'),date('j')-1) <= $latest_time) && ($latest_time < mktime(0,0,0))) $latest = 'Yesterday';
        else $latest = date('M j, Y', $latest_time);
      }
      else $latest = 'Never'; 
      
      $employeearray[] = array('id'           => $employee['data']->getId(),
                               'name'         => $employee['data']->generateName(false, false, $firstlast), 
                               'job_title'    => $employee['data']->getWfCRM()->getJobTitle(),
                               'mobile'       => $employee['data']->getWfCRM()->getMobilePhone(),
                               'home'         => $employee['data']->getWfCRM()->getHomePhone(),
                               'email'        => $employee['data']->getWfCRM()->getEmail(),
                               'department'   => $employee['data']->getDepartmentName(),
                               'last_timelog' => $latest,
                               'type'         => $employee['data']->getWfCRM()->getIsCompany() ? 'Contractor' : 'Employee',
                               'status'       => $employee['data']->getHidden() ? 'Inactive' : 'Active'
                             );
    }
    $dataarray = array('totalCount' => $count_all, 'employees' => $employeearray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
