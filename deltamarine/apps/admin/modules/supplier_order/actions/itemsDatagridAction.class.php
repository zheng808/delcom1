<?php

class itemsDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($order = SupplierOrderPeer::retrieveByPk($request->getParameter('id')));

    $orderitems = $order->getSupplierOrderItemsJoinPartInfo();
    $item_ids = array();
    $variant_ids = array();
    foreach ($orderitems AS $orderitem)
    {
      $item_ids[] = $orderitem->getId();
      $variant_ids[] = $orderitem->getPartVariantId();
    }

    //get lots
    $c = new Criteria();
    $c->add(PartLotPeer::SUPPLIER_ORDER_ITEM_ID, $item_ids, Criteria::IN);
    $lots = PartLotPeer::doSelect($c);
    $lots_array = array();
    foreach ($lots AS $lot)
    {
      $idx = $lot->getSupplierOrderItemId();
      if (!isset($lots_array[$idx])) $lots_array[$idx] = array();
      $lots_array[$idx][] = $lot;
    }
    
    //get special orders
    $c = new Criteria();
    $c->add(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID, $item_ids, Criteria::IN);
    $sporders = PartInstancePeer::doSelect($c);
    $sporders_array = array();
    foreach ($sporders AS $sporder)
    {
      $idx = $sporder->getSupplierOrderItemId();
      if (!isset($sporders_array[$idx])) $sporders_array[$idx] = array();
      $sporders_array[$idx][] = $sporder;
    }

    //get part supplier field
    $c = new Criteria();
    $c->add(PartSupplierPeer::PART_VARIANT_ID, $variant_ids, Criteria::IN);
    $c->add(PartSupplierPeer::SUPPLIER_ID, $order->getSupplierId());
    $supps = PartSupplierPeer::doSelect($c);
    $supps_array = array();
    foreach ($supps AS $supp)
    {
      $supps_array[$supp->getPartVariantId()] = $supp;
    }

    //generate JSON output
    $itemsarray = array();
    foreach ($orderitems AS $item)
    {
      $var = $item->getPartVariant();
      $part = $var->getPart();

      //calculate supplier info
      if (isset($supps_array[$item->getPartVariantId()]))
      {
        $supplier_sku = $supps_array[$item->getPartVariantId()]->getSupplierSku();
        $supplier_notes = $supps_array[$item->getPartVariantId()]->getNotes();
      }
      else
      {
        $supplier_sku = $supplier_notes = '';
      }

      //calculate lot info
      $lots = array();
      if (isset($lots_array[$item->getId()]))
      {
        foreach ($lots_array[$item->getId()] AS $lot)
        {
          $lots[] = array('id' => $lot->getId(),
                          'date' => $lot->getReceivedDate('M j, Y'),
                          'quantity_received' => $lot->outputQuantityReceived()
                         );
        }
      }

      //calculate special order info
      $sporders = array();
      if (isset($sporders_array[$item->getId()]))
      {
        foreach ($sporders_array[$item->getId()] AS $sporder)
        {
          if ($sporder->getWorkorderItemId())
          {
            $workorder_id = $sporder->getWorkorderItem()->getWorkorderId();
            $sale_id = '';
          }
          else
          {
            $workorder_id = '';
            $sale_id = $sporder->getCustomerOrderItem()->getCustomerOrderId();
          }
          $sporders[] = array('id' => $sporder->getId(),
                          'workorder_id' => $workorder_id,
                          'sale_id' => $sale_id,
                          'quantity' => $sporder->outputQuantity()
                         );
        }
      }

      $itemsarray[] = array('supplier_order_item' => $item->getId(),
                            'part_variant_id'     => $var->getId(),
                            'part_id'             => $part->getId(),
                            'name'                => $part->getName(),
                            'sku'                 => $var->getInternalSku(),
                            'units'               => (string) $var->getUnits(),
                            'quantity'            => $item->outputQuantityRequested(false),
                            'received'            => $item->outputQuantityCompleted(false),
                            'supplier_sku'        => $supplier_sku,
                            'supplier_notes'      => $supplier_notes,
                            'lots'                => $lots,
                            'special_orders'      => $sporders,
                            'location'            => (string) $var->getLocation()
                          );
    }
    $dataarray = array('success' => true, 'items' => $itemsarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
