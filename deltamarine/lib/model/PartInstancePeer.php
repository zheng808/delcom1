<?php

class PartInstancePeer extends BasePartInstancePeer
{

  public static function doSelectJoinPart(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    $c->addJoin(self::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID, Criteria::LEFT_JOIN);

    self::addSelectColumns($c);
    PartVariantPeer::addSelectColumns($c);
    PartPeer::addSelectColumns($c);

    $instance_startcol = 0;
    $variant_startcol = $instance_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $part_startcol = $variant_startcol + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      //load instance
      $instance_key = PartInstancePeer::getPrimaryKeyHashFromRow($row, $instance_startcol);
      if (null === ($instance = PartInstancePeer::getInstanceFromPool($instance_key)))
      {
        $instance = new PartInstance();
        $instance->hydrate($row, $instance_startcol);
      }
      
      //load variant
      if ($variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol))
      {
        if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
        {
          $variant = new PartVariant();
          $variant->hydrate($row, $variant_startcol);
          $variant->setPartOptionValuesFullyLoaded(true);
          PartVariantPeer::addInstanceToPool($variant, $variant_key);
        }

        //load part
        if ($part_key = PartPeer::getPrimaryKeyHashFromRow($row, $part_startcol))
        {
          if (null === ($part = PartPeer::getInstanceFromPool($part_key)))
          {
            $part = new Part();
            $part->hydrate($row, $part_startcol);
            PartPeer::addInstanceToPool($part, $part_key);
          }

          $variant->setPart($part);
        }

        $instance->setPartVariant($variant);   
      }

      $results[] = $instance;
    }

    return array_values($results);
  }

  //gets all the information for an entry, regardless of what its related to
  //NOTE: this returns an array of objects of potentially mixed type!!!!!
  public static function doSelectForListing(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    //is it a customer sale item?
    $c->addJoin(self::ID, CustomerOrderItemPeer::PART_INSTANCE_ID, Criteria::LEFT_JOIN);

    //or a customer return?
    $c->addJoin(self::ID, CustomerReturnItemPeer::PART_INSTANCE_ID, Criteria::LEFT_JOIN);

    //hmm, maybe its a workorder item?
    $c->addJoin(self::WORKORDER_ITEM_ID, WorkorderItemPeer::ID, Criteria::LEFT_JOIN);

    $c->addJoin(self::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID, Criteria::LEFT_JOIN);

    self::addSelectColumns($c);
    CustomerOrderItemPeer::addSelectColumns($c);
    CustomerReturnItemPeer::addSelectColumns($c);
    WorkorderItemPeer::addSelectColumns($c); 
    PartVariantPeer::addSelectColumns($c);
    PartPeer::addSelectColumns($c);

    $instance_startcol = 0;
    $coi_startcol = $instance_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $cri_startcol = $coi_startcol + (CustomerOrderItemPeer::NUM_COLUMNS - CustomerOrderItemPeer::NUM_LAZY_LOAD_COLUMNS);
    $woi_startcol = $cri_startcol + (CustomerReturnItemPeer::NUM_COLUMNS - CustomerReturnItemPeer::NUM_LAZY_LOAD_COLUMNS);
    $variant_startcol = $woi_startcol + (WorkorderItemPeer::NUM_COLUMNS - WorkorderItemPeer::NUM_LAZY_LOAD_COLUMNS);
    $part_startcol = $variant_startcol + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    $variants = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      //load instance
      $instance_key = PartInstancePeer::getPrimaryKeyHashFromRow($row, $instance_startcol);
      if (null === ($instance = PartInstancePeer::getInstanceFromPool($instance_key)))
      {
        $instance = new PartInstance();
        $instance->hydrate($row, $instance_startcol);
      }
      
      //load orderitem
      if ($coi_key = CustomerOrderItemPeer::getPrimaryKeyHashFromRow($row, $coi_startcol))
      {
        if (null === ($coi = CustomerOrderItemPeer::getInstanceFromPool($coi_key)))
        {
          $coi = new CustomerOrderItem();
          $coi->hydrate($row, $coi_startcol);
          CustomerOrderItemPeer::addInstanceToPool($coi, $coi_key);
        }
      }

      //load returnitem
      if ($cri_key = CustomerReturnItemPeer::getPrimaryKeyHashFromRow($row, $cri_startcol))
      {
        if (null === ($cri = CustomerReturnItemPeer::getInstanceFromPool($cri_key)))
        {
          $cri = new CustomerReturnItem();
          $cri->hydrate($row, $cri_startcol);
          CustomerReturnItemPeer::addInstanceToPool($cri, $cri_key);
        }
      }

      //load workorderitem
      if ($woi_key = WorkorderItemPeer::getPrimaryKeyHashFromRow($row, $woi_startcol))
      {
        if (null === ($woi = WorkorderItemPeer::getInstanceFromPool($woi_key)))
        {
          $woi = new WorkorderItem();
          $woi->hydrate($row, $woi_startcol);
          WorkorderItemPeer::addInstanceToPool($woi, $woi_key);
        }
      }

      //load variant
      if ($variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol))
      {
        if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
        {
          $variant = new PartVariant();
          $variant->hydrate($row, $variant_startcol);
          $variant->setPartOptionValuesFullyLoaded(true);
          PartVariantPeer::addInstanceToPool($variant, $variant_key);
        }

        //load part
        if ($part_key = PartPeer::getPrimaryKeyHashFromRow($row, $part_startcol))
        {
          if (null === ($part = PartPeer::getInstanceFromPool($part_key)))
          {
            $part = new Part();
            $part->hydrate($row, $part_startcol);
            PartPeer::addInstanceToPool($part, $part_key);
          }

          $variant->setPart($part);
        }

        $instance->setPartVariant($variant); 
      }

      //link all the objects depending on what type it is
      if (isset($coi) && $coi->getId() > 0)
      {
        $coi->setPartInstance($instance);
        $results[] = $coi;
      }
      else if (isset($cri) && $cri->getId() > 0)
      {
        $cri->setPartInstance($instance);
        $results[] = $cri;
      }
      else
      {
        if (isset($woi) && $woi->getId() > 0)
        {
          $instance->setWorkorderItem($woi);
        }
        $results[] = $instance;
      }

      unset($coi,$cri,$woi);
    }

    return array_values($results);
  }


}
