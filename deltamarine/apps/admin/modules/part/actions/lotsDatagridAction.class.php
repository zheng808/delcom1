<?php

class lotsDatagridAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START lotsDatagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($part = PartPeer::retrieveByPk($request->getParameter('id')));

    $c = new Criteria();
    $c->add(PartPeer::ID, $part->getId());

    //filter
    // (N/A)

    //copy for getting total count later
    $c2 = clone $c;

    //sort
    switch ($request->getParameter('sort', 'supplier_order_id'))
    {
    case 'id':
      $col = PartLotPeer::ID;
      break;
    case 'supplier_order_id':
      $col = SupplierOrderPeer::ID;
      break;
    case 'supplier_name':
      $col = wfCRMPeer::ALPHA_NAME;
      break;
    case 'received_date':
      $col = PartLotPeer::RECEIVED_DATE;
      break;
    case 'landed_cost':
      $col = PartLotPeer::LANDED_COST;
      break;
    case 'quantity_received':
      $col = PartLotPeer::QUANTITY_RECEIVED;
      break;
    case 'quantity_remaining':
      $col = PartLotPeer::QUANTITY_REMAINING;
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

    $lots = PartLotPeer::doSelectJoinPartAndOrderInfo($c);

    //generate data array
    $lotsarray = array();
    foreach ($lots AS $lot)
    {
      $order = ($lot->getSupplierOrderItem() ? $lot->getSupplierOrderItem()->getSupplierOrder(): null);
      $description = $lot->getPartVariant()->__toString();
      $lotsarray[] = array('id' => $lot->getId(),
                           'supplier_order_id' => ($order ? $order->getId() : ''),
                           'supplier_id' => ($order ? $order->getSupplierId() : ''),
                           'supplier_name' => ($order ? $order->getSupplier()->getName() : ''),
                           'part_id' => $lot->getPartVariant()->getPartId(),
                           'part_variant_id' => $lot->getPartVariantId(),
                           'part_description' => $description,
                           'quantity_received' => $lot->outputQuantityReceived(),
                           'quantity_remaining' => $lot->outputQuantityRemaining(),
                           'received_date' => $lot->getReceivedDate('M j, Y')
                          );
    }

    //count the totals and add stuff to the final array
    $c2->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
    $c2->addJoin(PartVariantPeer::ID, PartLotPeer::PART_VARIANT_ID);
    $count_all = PartLotPeer::doCount($c2);
    $dataarray = array('totalCount' => $count_all, 'lots' => $lotsarray);

    $this->renderText(json_encode($dataarray));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE lotsDatagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//lotsDatagridAction{}========================================================
