<?php

class itemsDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($sale = CustomerOrderPeer::retrieveByPk($request->getParameter('id')));

    //get returns
    $returns = $sale->getCustomerReturns();
    $returned_qtys = array();
    foreach ($returns AS $return)
    {
      $items = $return->getCustomerReturnItems();
      foreach ($items AS $item)
      {
        if (!isset($returned_qtys[$item->getCustomerOrderItemId()]))
        {
          $returned_qtys[$item->getCustomerOrderItemId()] = 0;
        }
        $returned_qtys[$item->getCustomerOrderItemId()] -= $item->getPartInstance()->getQuantity();
      }
    }

    //generate JSON output
    $itemsarray = array();
    foreach ($sale->getCustomerOrderItemsJoinPartInfo() AS $item)
    {
      $inst = $item->getPartInstance();
      if ($var = $inst->getPartVariant())
      {
        $part = $var->getPart();
        $regular = $item->getPartInstance()->getPartVariant()->calculateUnitPrice();
        $name = $part->getName();
        $disc = round(100 * (($item->getPartInstance()->getUnitPrice()/$regular) - 1));
        if ($disc > 0) $disc = '+' . $disc . '%';
        else if ($disc < 0) $disc = $disc . '%';
        else { $disc = '-'; }
      } else {
        $name = $inst->getCustomName();
        $var = null;
        $part = null;
        $disc = '-';
      }

      $cost = $item->getPartInstance()->getUnitCost();
      $order_id = ($inst->getSupplierOrderItemId() ? $inst->getSupplierOrderItem()->getSupplierOrderId() : '');
      $returned = (isset($returned_qtys[$item->getId()]) ? $returned_qtys[$item->getId()] : 0);
      $undelivered = ($order_id > 0 ? 0 : ($inst->outputQuantity(false) - $item->outputQuantityCompleted(false)));
      $itemsarray[] = array('customer_order_item' => $item->getId(),
                            'part_variant_id'     => $inst->getPartVariantId(),
                            'part_id'             => ($part ? $part->getId() : 0),
                            'name'                => $name,
                            'custom_name'         => $inst->getCustomName(),
                            'sku'                 => ($var ? $var->getInternalSku() : ''),
                            'units'               => ($var ? (string) $var->getUnits() : ''),
                            'quantity'            => $inst->outputQuantity(false),
                            'returned'            => round($returned, 3),
                            'delivered'           => $item->outputQuantityCompleted(false),
                            'undelivered'         => $undelivered,
                            'unit_cost'           => number_format($cost, 2),
                            'unit_price'          => number_format($inst->getUnitPrice(), 2),
                            'regular_price'       => number_format($regular, 2),
                            'calc_discount'       => $disc,
                            'taxable_hst'         => ($inst->getTaxableHst() > 0) ? 1 : 0,
                            'taxable_pst'         => ($inst->getTaxablePst() > 0) ? 1 : 0,
                            'taxable_gst'         => ($inst->getTaxableGst() > 0) ? 1 : 0,
                            'enviro_levy'         => $inst->getEnviroLevy(),
                            'battery_levy'        => $inst->getBatteryLevy(),
                            'total'               => number_format($inst->getSubtotal(), 2),
                            'supplier_order_id'   => $order_id,
                            'serial'              => (string) $inst->getSerialNumber(),
                            'has_serial_number'   => ($part ? (int) $part->getHasSerialNumber() : 0),
                            'location'            => ($var ? (string) $var->getLocation() : '')
                          );
    }
    $dataarray = array('subtotal' => number_format($sale->getTotals('subtotal'),2),
                       'hst' => number_format($sale->getTotals('hst'),2),
                       'pst' => number_format($sale->getTotals('pst'),2),
                       'gst' => number_format($sale->getTotals('gst'),2),
                       'enviro_levy' => number_format($sale->getTotals('enviro'), 2),
                       'battery_levy' => number_format($sale->getTotals('battery'), 2),
                       'total' => number_format($sale->getTotals(),2),
                       'items' => $itemsarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
