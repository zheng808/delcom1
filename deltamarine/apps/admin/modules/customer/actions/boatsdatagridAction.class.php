<?php

class boatsdatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'lastworkorder':
      $col = 'latest';
      break;
    case 'name':
      $col = CustomerBoatPeer::NAME;
      break;
    case 'make':
      $col = CustomerBoatPeer::MAKE;
      break;
    case 'model':
      $col = CustomerBoatPeer::MODEL;
      break;
    }
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));

    //filtering
    if ($request->getParameter('customer_id'))
    {
      $c->add(CustomerBoatPeer::CUSTOMER_ID, $request->getParameter('customer_id'));
    }
    if ($request->getParameter('query'))
    {
      $c->add(CustomerBoatPeer::NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    $boats = CustomerBoatPeer::doSelectForListing($c);
    $count_all = count($boats);

    //generate JSON output
    $boatarray = array();
    foreach ($boats AS $boat)
    {
      $boatarray[] = array('id'    => $boat['data']->getId(),
                           'name'  => $boat['data']->getName(), 
                           'make'  => $boat['data']->getMake(),
                           'model' => $boat['data']->getModel(),
                           'lastworkorder' => ($boat['latest'] ? date('m/d/Y', $boat['latest']) : 'Never')
                          );
    }
    $dataarray = array('totalCount' => $count_all, 'boats' => $boatarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
