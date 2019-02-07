<?php

class CustomerOrderItemPeer extends BaseCustomerOrderItemPeer
{
  //gets all the information for an entry.
  //customer_order_item->part_instance->variant->part
  public static function doSelectJoinPartInfo(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    //part info
    $c->addJoin(self::PART_INSTANCE_ID, PartInstancePeer::ID);
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID, Criteria::LEFT_JOIN);

    self::addSelectColumns($c);
    PartInstancePeer::addSelectColumns($c);
    PartVariantPeer::addSelectColumns($c);
    PartPeer::addSelectColumns($c);

    $item_startcol = 0;
    $instance_startcol = $item_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $variant_startcol = $instance_startcol + (PartInstancePeer::NUM_COLUMNS - PartInstancePeer::NUM_LAZY_LOAD_COLUMNS);
    $part_startcol = $variant_startcol + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);
       
    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    $variants = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      //load item
      $item_key = CustomerOrderItemPeer::getPrimaryKeyHashFromRow($row, $item_startcol);
      if (null === ($item = CustomerOrderItemPeer::getInstanceFromPool($item_key)))
      {
        $item = new CustomerOrderItem();
        $item->hydrate($row, $item_startcol);
        CustomerOrderItemPeer::addInstanceToPool($item, $item_key);
      }

      //load instance
      $instance_key = PartInstancePeer::getPrimaryKeyHashFromRow($row, $instance_startcol);
      if (null === ($instance = PartInstancePeer::getInstanceFromPool($instance_key)))
      {
        $instance = new PartInstance();
        $instance->hydrate($row, $instance_startcol);
        PartInstancePeer::addInstanceToPool($instance, $instance_key);
      }

      //load variant
      $variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol);
      if ($variant_key)
      {
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

        //link all the objects
        $variant->setPart($part);
        $instance->setPartVariant($variant);
      }
      
      $item->setPartInstance($instance);

      $results[] = $item;
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

  // TODO use CustomerOrderItemPeer::doSelectJoinPartAndOrderInfo($c, $con) but fix it before
  public static function doSelectReadyToPickUp(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c)
    {
			$c = new Criteria();
    }
    $c->add(PartInstancePeer::ALLOCATED, 1);
    $c->add(PartInstancePeer::DELIVERED, 0);
    $c->add(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID, null, Criteria::NOT_EQUAL);
    $c->addJoin(CustomerOrderItemPeer::PART_INSTANCE_ID, PartInstancePeer::ID);
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
    return CustomerOrderItemPeer::doSelect($c, $con);
  }

  public static function doCountReadyToPickUp(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c)
    {
			$c = new Criteria();
    }
    $c->add(PartInstancePeer::ALLOCATED, 1);
    $c->add(PartInstancePeer::DELIVERED, 0);
    $c->add(PartInstancePeer::SUPPLIER_ORDER_ITEM_ID, null, Criteria::NOT_EQUAL);
    $c->addJoin(CustomerOrderItemPeer::PART_INSTANCE_ID, PartInstancePeer::ID);
    return CustomerOrderItemPeer::doCount($c, $con);
  }
}
