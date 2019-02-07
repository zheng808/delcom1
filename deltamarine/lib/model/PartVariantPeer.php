<?php

class PartVariantPeer extends BasePartVariantPeer
{
  public static function getCostCalculationOptions()
  {
    return array('fifo' => 'First In, First Out',
                 'lifo' => 'Last In, First Out',
                 'average' => 'Average Cost');
  }

  public static function getUnitOptions()
  {
    return array('' => 'Items', 
                 'Lengths:' => array('m' => 'm', 'cm' => 'cm', 'mm' => 'mm', 'yd' => 'yd', 'ft' => 'ft', 'in' => 'in'),
                 'Weights:' => array('kg' => 'kg', 'g' => 'g', 'lb' => 'lb', 'oz' => 'oz'),
                 'Volumes:' => array('L' => 'L', 'ml' => 'ml', 'gal' => 'gal', 'qt' => 'qt', 'fl. oz.' => 'fl. oz.'));
  }

  public static function doSelectJoinPartLots(Criteria $c = null, $con = null)
  {
    if (!$c) $c = new Criteria();

    self::addSelectColumns($c);
    PartLotPeer::addSelectColumns($c);

    $variant_startcol = 0;
    $lot_startcol = $variant_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::ID, PartLotPeer::PART_VARIANT_ID, Criteria::LEFT_JOIN);

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol);
      if (isset($results[$variant_key]))
      {
        $variant = $results[$variant_key];
      }
      else if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
      {
        $variant = new PartVariant();
        $variant->hydrate($row, $variant_startcol);
        PartVariantPeer::addInstanceToPool($variant, $variant_key);
      }

      $lot_key = PartLotPeer::getPrimaryKeyHashFromRow($row, $lot_startcol);
      if (null === ($lot = PartLotPeer::getInstanceFromPool($lot_key)))
      {
        $lot = new PartLot();
        $lot->hydrate($row, $lot_startcol);
        PartLotPeer::addInstanceToPool($lot, $lot_key);
      }

      $variant->addPartLot($lot);

      $results[$variant_key] = $variant;
    }

    //this makes it look as if we'd loaded the part lots using 'getPartLots();'.
    //otherwise, it would re-query the db when you next called getPartLots.
    foreach ($results AS $result)
    {
      $result->setPartLotsFullyLoaded(true);
    }
    return array_values($results);
  }

  public static function autocreateVariants($part_id, $options, $option_values, $set_values = null)
  {
    if (count($option_values) > 0)
    {
      $this_key = key($option_values);
      $this_data = current($option_values);
      unset($option_values[$this_key]);
      foreach ($this_data AS $this_value)
      {
        $set_values[$this_key] = $this_value;
        if (count($option_values) > 0)
        {
          self::autocreateVariants($part_id, $options, $option_values, $set_values);
        }
        else
        {
          $var = new PartVariant();
          $var->setPartId($part_id);
          $var->setUseDefaultUnits(true);
          $var->setUseDefaultPricing(true);
          $var->setUseDefaultDimensions(true);
          $var->setUseDefaultInventory(true);
          $var->save();
          foreach ($set_values AS $set_key => $set_value)
          {
            $poval = new PartOptionValue();
            $poval->setPartVariant($var);
            $poval->setPartOptionId($options[$set_key]->getId());
            $poval->setValue($set_value);
            $poval->save();
          }
        }
      }
    }
  }

  public static function doCountOnHold(Criteria $c = null, PropelPDO $con = null)
  {
      if (!$c) $c = new Criteria;
      $c->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
      $c->addJoin(PartVariantPeer::ID, PartInstancePeer::PART_VARIANT_ID);
      $c->add(PartPeer::ACTIVE, true);
      $c->add(PartInstancePeer::ALLOCATED, true);
      $c->add(PartInstancePeer::DELIVERED, false);
      $c->addJoin(PartInstancePeer::ID, CustomerOrderItemPeer::PART_INSTANCE_ID, Criteria::LEFT_JOIN);
      $c1 = $c->getNewCriterion(PartInstancePeer::WORKORDER_ITEM_ID, null, Criteria::ISNOTNULL);
      $c2 = $c->getNewCriterion(CustomerOrderItemPeer::ID, null, Criteria::ISNOTNULL);
      $c1->addOr($c2);
      $c->addAnd($c1);

      return PartInstancePeer::doCount($c);
  }

  public static function doSelectBelowMin(Criteria $c = null, PropelPDO $con = null)
  {
    //step 1: determine understocked, accounting for items on order
    $sql = 'SELECT id FROM ('.
              'SELECT '.PartVariantPeer::ID.', ('.PartVariantPeer::MINIMUM_ON_HAND.' - '.PartVariantPeer::CURRENT_ON_HAND.') AS needed, '.
              ' COALESCE(SUM('.SupplierOrderItemPeer::QUANTITY_REQUESTED.' - '.SupplierOrderItemPeer::QUANTITY_COMPLETED.'), 0) AS coming '.
              ' FROM '.PartVariantPeer::TABLE_NAME.' LEFT JOIN '.SupplierOrderItemPeer::TABLE_NAME.
              ' ON ('.PartVariantPeer::ID.' = '.SupplierOrderItemPeer::PART_VARIANT_ID.')'.
              ' WHERE '.PartVariantPeer::MINIMUM_ON_HAND.' > '.PartVariantPeer::CURRENT_ON_HAND.
              ' GROUP BY '.PartVariantPeer::ID.') '.
            ' AS a WHERE ((a.needed - a.coming) > 0)'; 
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $ids = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $ids[] = $row[0];
    }
    if (!$c)
    {
			$c = new Criteria();
    }
    $c->add(PartVariantPeer::ID, $ids, Criteria::IN);
    return PartVariantPeer::doSelect($c, $con);
  }
  
  public static function doSelectOld(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c)
    {
			$c = new Criteria();
    }
    
    //$c->add(PartVariantPeer::MINIMUM_ON_HAND, PartVariantPeer::MINIMUM_ON_HAND.'>'.PartVariantPeer::CURRENT_ON_HAND, Criteria::CUSTOM);
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID);
    $oldparts = array();
    $allparts = PartInstancePeer::doSelect($c, $con);
    foreach($allparts as $pin)
    {
        $date_used = strtotime($pin->getDateUsed());
        $currdate = time();
        if(($currdate - $date_used) > 3*30*24*60*60)
                $oldparts[] = $pin;
    }
    return $oldparts;
  }
  
  public static function doCountOld(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c)
    {
			$c = new Criteria();
    }
    
    //$c->add(PartVariantPeer::MINIMUM_ON_HAND, PartVariantPeer::MINIMUM_ON_HAND.'>'.PartVariantPeer::CURRENT_ON_HAND, Criteria::CUSTOM);
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID);
    $oldparts = array();
    $allparts = PartInstancePeer::doSelect($c, $con);
    foreach($allparts as $pin)
    {
        $date_used = strtotime($pin->getDateUsed());
        $currdate = time();
        if(($currdate - $date_used) > 3*30*24*60*60)
                $oldparts[] = $pin;
    }
    $c_not_used_at_all = new Criteria();
    //$c_not_used_at_all->add(PartVariantPeer::ID, PartInstancePeer::PART_VARIANT_ID, Criteria::NOT_EQUAL, Criteria::CUSTOM);
    $tparts_not_used_at_all = PartVariantPeer::doSelect($c_not_used_at_all);
    $parts_not_used_at_all = array();
    foreach($tparts_not_used_at_all as $part_not_used_at_all)
    {
        $c_fix = new Criteria();
        $c_fix->add(PartInstancePeer::PART_VARIANT_ID, $part_not_used_at_all->getId());
        $t_fix = PartInstancePeer::doCount($c_fix);
        if($t_fix == 0)
               $parts_not_used_at_all[] =  $part_not_used_at_all;
    }
    $res = $parts_not_used_at_all;
    
    return count($oldparts) + count($res);
  }

  public static function doCountBelowMin(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c)
    {
			$c = new Criteria();
    }

    $c->add(PartVariantPeer::MINIMUM_ON_HAND, PartVariantPeer::MINIMUM_ON_HAND.'>'.PartVariantPeer::CURRENT_ON_HAND, Criteria::CUSTOM);
    return PartVariantPeer::doCount($c, $con);
  }

}
