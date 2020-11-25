<?php

class SupplierOrderItemPeer extends BaseSupplierOrderItemPeer
{
  //gets all the information for an entry.
  //part->variant->part_lot->supplier_order_item->supplier_order->supplier->crm
  public static function doSelectJoinPartAndOrderInfo(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    //part info
    $c->addJoin(self::PART_VARIANT_ID, PartVariantPeer::ID);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID);

    //order and supplier info
    $c->addJoin(self::SUPPLIER_ORDER_ID, SupplierOrderPeer::ID);
    $c->addJoin(SupplierOrderPeer::SUPPLIER_ID, SupplierPeer::ID);
    $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);

    self::addSelectColumns($c);
    PartVariantPeer::addSelectColumns($c);
    PartPeer::addSelectColumns($c);
    SupplierOrderPeer::addSelectColumns($c); 
    SupplierPeer::addSelectColumns($c);
    WfCRMPeer::addSelectColumns($c);

    $orderitem_startcol = 0;
    $variant_startcol = $orderitem_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $part_startcol = $variant_startcol + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);
    $order_startcol = $part_startcol + (PartPeer::NUM_COLUMNS - PartPeer::NUM_LAZY_LOAD_COLUMNS);
    $supplier_startcol = $order_startcol + (SupplierOrderPeer::NUM_COLUMNS - SupplierOrderPeer::NUM_LAZY_LOAD_COLUMNS);
    $crm_startcol = $supplier_startcol + (SupplierPeer::NUM_COLUMNS - SupplierPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    $variants = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      //load orderitem
      $orderitem_key = SupplierOrderItemPeer::getPrimaryKeyHashFromRow($row, $orderitem_startcol);
      if (null === ($orderitem = SupplierOrderItemPeer::getInstanceFromPool($orderitem_key)))
      {
        $orderitem = new SupplierOrderItem();
        $orderitem->hydrate($row, $orderitem_startcol);
        SupplierOrderItemPeer::addInstanceToPool($orderitem, $orderitem_key);
      }

      //load variant
      $variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol);
      if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
      {
        $variant = new PartVariant();
        $variant->hydrate($row, $variant_startcol);
        $variant->setPartOptionValuesFullyLoaded(true);
        PartVariantPeer::addInstanceToPool($variant, $variant_key);
      }

    
      //load part
      $part_key = PartPeer::getPrimaryKeyHashFromRow($row, $part_startcol);
      if (null === ($part = PartPeer::getInstanceFromPool($part_key)))
      {
        $part = new Part();
        $part->hydrate($row, $part_startcol);
        PartPeer::addInstanceToPool($part, $part_key);
      }

      //load order
      $order_key = SupplierOrderPeer::getPrimaryKeyHashFromRow($row, $order_startcol);
      if (null === ($order = SupplierOrderPeer::getInstanceFromPool($part_key)))
      {
        $order = new SupplierOrder();
        $order->hydrate($row, $order_startcol);
        SupplierOrderPeer::addInstanceToPool($order, $order_key);
      }

      //load supplier
      $supplier_key = SupplierPeer::getPrimaryKeyHashFromRow($row, $supplier_startcol);
      if (null === ($supplier = SupplierPeer::getInstanceFromPool($supplier_key)))
      {
        $supplier = new Supplier();
        $supplier->hydrate($row, $supplier_startcol);
        SupplierPeer::addInstanceToPool($supplier, $supplier_key);
      }

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      if (null === ($crm = WfCrmPeer::getInstanceFromPool($crm_key)))
      {
        $crm = new wfCRM();
        $crm->hydrate($row, $crm_startcol);
        wfCRMPeer::addInstanceToPool($crm, $crm_key);
      }

      //link all the objects
      $variant->setPart($part);
      $orderitem->setPartVariant($variant);

      $supplier->setWfCrm($crm);
      $order->setSupplier($supplier);
      $orderitem->setSupplierOrder($order);

      $results[] = $orderitem;
      $variants[$variant->getId()] = $variant;
    }

    //step 2: gather variant option values (saves multiple queries per result).
    //this sets the option values to the variant objects by reference.
    $variant_ids = array_keys($variants);

    $c2 = new Criteria();
    $c2->add(PartVariantPeer::ID, $variant_ids, Criteria::IN);
    $c2->addJoin(PartVariantPeer::ID, PartOptionValuePeer::PART_VARIANT_ID);
    $values = PartOptionValuePeer::doSelect($c2);
    foreach ($values AS $value)
    {
      $variants[$value->getPartVariantId()]->addPartOptionValue($value);
    }

    return array_values($results);
  }

}
