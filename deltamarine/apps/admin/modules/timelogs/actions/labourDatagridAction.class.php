<?php

class labourDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
 
    $c = new Criteria();

    //filter
    $c->addAscendingOrderByColumn(LabourTypePeer::NAME);
    if ($request->getParameter('query'))
    {
      $c->add(LabourTypePeer::NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }
    if ($this->getRequestParameter('showall') != '1')
    {
      $c->add(LabourTypePeer::ACTIVE, true);
    }    

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'name':
      $col = LabourTypePeer::NAME;
      break;
    case 'rate':
      $col = LabourTypePeer::HOURLY_RATE;
      break;
    }
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));

    //get the list
    $types = LabourTypePeer::doSelect($c);

    //output the data array
    $labourarray = array();
    foreach ($types AS $type)
    {
      $labourarray[] = array('id' => $type->getId(),
                             'name' => $type->getName(),
                             'desc' => $type->getName().' ($'.number_format($type->getHourlyRate(), 2).'/hr)',
                             'rate' => $type->getHourlyRate(),
                             'active' => $type->getActive() ? 1 : 0);
    }
    $dataarray = array('totalCount' => count($labourarray), 'labourtypes' => $labourarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE; 

  }

}
