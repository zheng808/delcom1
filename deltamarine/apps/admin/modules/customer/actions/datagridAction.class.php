<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START customer.datagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    $c = new Criteria();
    $c2 = new Criteria();

    //filter
    if ($request->getParameter('query'))
    {
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
      $c2->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c2->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'phone':
      $col = wfCRMPeer::WORK_PHONE;
      break;
    case 'name':
      $col = wfCRMPeer::ALPHA_NAME;
      break;
    }
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));

    //paging
    if ($request->getParameter('limit'))
    {
      $c->setLimit($request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $c->setOffset($request->getParameter('start'));
    }

    $customers = CustomerPeer::doSelectForListing($c);
    $count_all = CustomerPeer::doCount($c2);

    //generate JSON output
    $firstlast = (bool) $request->getParameter('firstlast', true);
    $withcountry = (bool) $request->getParameter('withcountry', false);
    $customerarray = array();
    foreach ($customers AS $customer)
    {
       $customerarray = array('name'  => $customer->generateName(false, false, $firstlast), 
                              'id'    => $customer->getId(),
                              'phone' => $customer->getWfCRM()->getMainPhone(),
                              'email' => $customer->getWfCRM()->getEmail()
                             );
      if ($withcountry)
      {
        $addr = $customer->getWfCRM()->getWfCRMAddresss();
        $customerarray['country'] = ($addr && isset($addr[0]) && $addr[0]->getCountry() ? $addr[0]->getCountry() : '');
      }
      $customersarray[] = $customerarray;
    }
    $dataarray = array('totalCount' => $count_all, 'customers' => $customersarray);

    $this->renderText(json_encode($dataarray));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE customer.datagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//datagridAction{}============================================================
