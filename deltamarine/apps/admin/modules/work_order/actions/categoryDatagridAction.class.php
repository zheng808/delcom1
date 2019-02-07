<?php

class categoryDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
 
    $c = new Criteria();

    //filter
    $c->addAscendingOrderByColumn(WorkorderCategoryPeer::NAME);
    if ($request->getParameter('query'))
    {
      $c->add(WorkorderCategoryPeer::NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    //sort
    $col = WorkorderCategoryPeer::NAME;
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));

    //get the list
    $types = WorkorderCategoryPeer::doSelect($c);

    //output the data array
    $categoryarray = array();
    if ($this->getRequestParameter('uncat'))
    {
      $categoryarray[] = array('id' => '-1', 'name' => 'Uncategorized');
    }
    foreach ($types AS $type)
    {
      $categoryarray[] = array('id' => $type->getId(),
                             'name' => $type->getName());
    }
    $dataarray = array('totalCount' => count($categoryarray), 'categories' => $categoryarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE; 

  }

}
