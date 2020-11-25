<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();
    $c2 = new Criteria();

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

    //filter
    if ($request->getParameter('query'))
    {
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
      $c2->addJoin(ManufacturerPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c2->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    $manufacturers = ManufacturerPeer::doSelectForListing($c);
    $count_all = ManufacturerPeer::doCount($c2);

    //generate JSON output
    $manufacturerarray = array();
    foreach ($manufacturers AS $manufacturer)
    {
      $manufacturerarray[] = array('name'  => $manufacturer['data']->getName(), 
                                   'id'    => $manufacturer['data']->getId(),
                                   'phone' => $manufacturer['data']->getWfCRM()->getWorkPhone(),
                                   'count' => $manufacturer['count']);
    }
    $dataarray = array('totalCount' => $count_all, 'manufacturers' => $manufacturerarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }


}
