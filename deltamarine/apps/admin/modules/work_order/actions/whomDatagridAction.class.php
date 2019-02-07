<?php

class whomDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($workorder = WorkorderPeer::retrieveByPk($request->getParameter('id')));

    $itemsarray = array();
    $payers = $workorder->getPayers();
    foreach ($payers AS $payer_id => $payer_info)
    {
      $itemsarray[] = array('id' => $payer_id, 'desc' => $payer_info[0], 'name' => $payer_info[1], 'taxable_hst' => $payer_info[2], 'taxable_pst' => $payer_info[3], 'taxable_gst' => $payer_info[4]);
    }

    $dataarray = array('whoms' => $itemsarray);
    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
