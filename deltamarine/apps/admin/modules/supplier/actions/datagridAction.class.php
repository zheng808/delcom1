<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());    

    $c = new Criteria();
    $c2 = new Criteria();

    //filter
    if ($request->getParameter('query'))
    {
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
      $c2->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c2->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'account':
      $col = SupplierPeer::ACCOUNT_NUMBER;
      break;
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

    $suppliers = SupplierPeer::doSelectForListing($c);
    $count_all = SupplierPeer::doCount($c2);

    //generate JSON output
    $supplierarray = array();
    foreach ($suppliers AS $supplier)
    {
      $supplierarray[] = array('name' => $supplier['data']->getName(), 
                               'id' => $supplier['data']->getId(),
                               'phone' => $supplier['data']->getWfCRM()->getWorkPhone(),
                               'account' => $supplier['data']->getAccountNumber(),
                               'count' => $supplier['count']);
    }
    $dataarray = array('totalCount' => $count_all, 'suppliers' => $supplierarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }


}
