<?php

class supplierorderitemDatagridAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START supplierorderitemDatagridAction.execute====================';
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
    case 'supplier_order_id':
      $col = SupplierOrderPeer::ID;
      break;
    case 'supplier_name':
      $col = wfCRMPeer::ALPHA_NAME;
      break;
    case 'date_ordered':
      $col = SupplierOrderPeer::DATE_ORDERED;
      break;
    case 'date_expected':
      $col = SupplierOrderPeer::DATE_EXPECTED;
      break;
    case 'quantity_requested':
      $col = SupplierOrderItemPeer::QUANTITY_REQUESTED;
      break;
    case 'quantity_completed':
      $col = SupplierOrderItemPeer::QUANTITY_COMPLETED;
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

    $orderitems = SupplierOrderItemPeer::doSelectJoinPartAndOrderInfo($c);

    //generate data array
    $orderitemsarray = array();
    foreach ($orderitems AS $orderitem)
    {
      $description = $orderitem->getPartVariant()->__toString();
      $status = ($orderitem->getSupplierOrder()->getFinalized() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Finalized</span> &gt; '.
                ($orderitem->getSupplierOrder()->getApproved() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Approved</span> &gt; '.
                ($orderitem->getSupplierOrder()->getSent() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Sent</span>';
      $orderitemsarray[] = array('id' => $orderitem->getId(),
                                 'supplier_order_id' => $orderitem->getSupplierOrderId(),
                                 'supplier_id' => $orderitem->getSupplierOrder()->getSupplierId(),
                                 'supplier_name' => $orderitem->getSupplierOrder()->getSupplier()->getName(),
                                 'part_id' => $orderitem->getPartVariant()->getPartId(),
                                 'part_variant_id' => $orderitem->getPartVariantId(),
                                 'part_description' => $description,
                                 'quantity_requested' => $orderitem->outputQuantityRequested(),
                                 'quantity_completed' => $orderitem->outputQuantityCompleted(),
                                 'date_ordered' => $orderitem->getSupplierOrder()->getDateOrdered('M j, Y'),
                                 'date_expected' => $orderitem->getSupplierOrder()->getDateExpected('M j, Y'),
                                 'order_status' => $status
                                );
    }

    //count the totals and add stuff to the final array
    $c2->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
    $c2->addJoin(PartVariantPeer::ID, SupplierOrderItemPeer::PART_VARIANT_ID);
    $count_all = SupplierOrderItemPeer::doCount($c2);
    $dataarray = array('totalCount' => $count_all, 'orderitems' => $orderitemsarray);

    $this->renderText(json_encode($dataarray));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE supplierorderitemDatagridAction.execute====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//supplierorderitemDatagridAction{}===========================================
