<?php

class CustomerReturnItemPeer extends BaseCustomerReturnItemPeer
{
  //gets all the information for an entry.
  //customer_return_item->part_instance->variant->part
  public static function doSelectJoinPartInfo(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    //part info
    $c->addJoin(self::PART_INSTANCE_ID, PartInstancePeer::ID);
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID);

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
      $item_key = CustomerReturnItemPeer::getPrimaryKeyHashFromRow($row, $item_startcol);
      if (null === ($item = CustomerReturnItemPeer::getInstanceFromPool($item_key)))
      {
        $item = new CustomerReturnItem();
        $item->hydrate($row, $item_startcol);
        CustomerReturnItemPeer::addInstanceToPool($item, $item_key);
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
      $item->setPartInstance($instance);

      $results[] = $item;
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
