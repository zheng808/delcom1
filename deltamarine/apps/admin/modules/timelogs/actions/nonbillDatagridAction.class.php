<?php

class nonbillDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
 
    $c = new Criteria();

    //filter
    $c->addAscendingOrderByColumn(NonbillTypePeer::NAME);
    if ($request->getParameter('query'))
    {
      $c->add(NonbillTypePeer::NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    //sort
    $col = NonbillTypePeer::NAME;
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));

    //get the list
    $types = NonbillTypePeer::doSelect($c);

    //output the data array
    $nonbillarray = array();
    foreach ($types AS $type)
    {
      $nonbillarray[] = array('id' => $type->getId(),
                             'name' => $type->getName());
    }
    $dataarray = array('totalCount' => count($nonbillarray), 'nonbilltypes' => $nonbillarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE; 

  }

}
