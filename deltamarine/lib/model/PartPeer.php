<?php

class PartPeer extends BasePartPeer
{
  public static function retrieveByPkJoinMost($pk, Criteria $c = null, $con = null)
  {

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START PartPeer.retrieveByPkJoinMost====================';
      sfContext::getInstance()->getLogger()->info($message);
      sfContext::getInstance()->getLogger()->info('Part ID: '.$pk);
    }


    if (!$c) $c = new Criteria();

    if ( $pk ) $c->add(self::ID, $pk);
    $c->addJoin(self::ID, PartVariantPeer::PART_ID);
    $c->addJoin(PartVariantPeer::ID, PartSupplierPeer::PART_VARIANT_ID, Criteria::LEFT_JOIN);
    $c->addJoin(PartVariantPeer::ID, PartOptionValuePeer::PART_VARIANT_ID, Criteria::LEFT_JOIN);
    $c->addAscendingOrderByColumn(PartOptionValuePeer::VALUE);

    self::addSelectColumns($c);
    PartVariantPeer::addSelectColumns($c);
    PartSupplierPeer::addSelectColumns($c);
    PartOptionValuePeer::addSelectColumns($c);

    $part_startcol = 0;
    $variant_startcol = $part_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $supplier_startcol = $variant_startcol + (PartVariantPeer::NUM_COLUMNS - PartVariantPeer::NUM_LAZY_LOAD_COLUMNS);
    $value_startcol = $supplier_startcol + (PartSupplierPeer::NUM_COLUMNS - PartSupplierPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c, $con);
    $part = null;
    $variants = array();
    $loaded_suppliers = array();
    $loaded_values = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      //load part if needed
      if ($part === null)
      {
        $part = new Part();
        $part->hydrate($row, $part_startcol);
        $part->initPartVariants();
        PartPeer::addInstanceToPool($part);
      }

      //load variant
      $variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol);
      if (isset($variants[$variant_key]))
      {
        $variant = $variants[$variant_key];
      }
      else
      {
        if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
        {
          $variant = new PartVariant();
          $variant->hydrate($row, $variant_startcol);
          PartVariantPeer::addInstanceToPool($variant, $variant_key);
        }
        $variants[$variant_key] = $variant;
        $variant->initPartSuppliers();
        $variant->initPartOptionValues();
        $variant->setPartSuppliersFullyLoaded(true);
        $variant->setPartOptionValuesFullyLoaded(true);
        if ($variant->getIsDefaultVariant())
        {
          $part->setDefaultVariant($variant);
        }
        else
        {
          $part->addPartVariant($variant);
          $variant->setPart($part);
        }
      }

      //load variant suppliers if any
      $supplier_key = PartSupplierPeer::getPrimaryKeyHashFromRow($row, $supplier_startcol);
      if ($supplier_key && !isset($loaded_suppliers[$supplier_key]))
      {
        if (null === ($supplier = PartSupplierPeer::getInstanceFromPool($supplier_key)))
        {
          $supplier = new PartSupplier();
          $supplier->hydrate($row, $supplier_startcol);
          PartSupplierPeer::addInstanceToPool($supplier, $supplier_key);
        }
        $loaded_suppliers[$supplier_key] = true;
        $variant->addPartSupplier($supplier);
      }

      //load values if any
      $value_key = PartOptionValuePeer::getPrimaryKeyHashFromRow($row, $value_startcol);
      if ($value_key && !isset($loaded_values[$value_key]))
      {
        if (null === ($value = PartOptionValuePeer::getInstanceFromPool($value_key)))
        {
          $value = new PartOptionValue();
          $value->hydrate($row, $value_startcol);
          PartOptionValuePeer::addInstanceToPool($value, $value_key);
        }
        $loaded_values[$value_key] = true;
        $variant->addPartOptionValue($value);
        unset($value);
      }

    }
    unset($variants, $stmt);  


    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE PartPeer.retrieveByPkJoinMost====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    if ($part)
    {
      $part->setPartVariantsFullyLoaded(true);
      return $part;
    }
    else
    {
      return null;
    }
  }//retrieveByPkJoinMost()----------------------------------------------------


  //THIS IS DONE AS A TWO STEP PROCESS
  //because the pager/datagrid objects will set a limit value on here, so the first query gets the parts,
  //and the second query gets the related part variants. humbug.
  public static function doSelectJoinPartVariants(Criteria $c = null, $con = null)
  {
    if (!$c) $c = new Criteria();

    //duplicate for 2nd query
    $original_c = clone $c;

    //starting columns
    $part_startcol = 0;
    $variant_startcol = $part_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);

    //select columns
    self::addSelectColumns($c);
    self::addSelectColumns($original_c);
    PartVariantPeer::addSelectColumns($c);
    PartVariantPeer::addSelectColumns($original_c);

    //join to variants
    $c->addJoin(self::ID, PartVariantPeer::PART_ID);
    // commented for using in supplier_order loadPartgrid action as I want to retrive parts by any part varian NOT just default
    //$c->add(PartVariantPeer::IS_DEFAULT_VARIANT, true);
    $original_c->addJoin(self::ID, PartVariantPeer::PART_ID);

    //set up query-specific settings
    $c->setDistinct(true);
    $original_c->setLimit(0);
    $original_c->setOffset(0);
    if (count($c->getOrderByColumns()) == 0) $c->addAscendingOrderByColumn(self::NAME);

    //perform first query to get parts
    $stmt = BasePeer::doSelect($c, $con);
        
    $results = array();
    $result_ids = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $part_key = PartPeer::getPrimaryKeyHashFromRow($row, $part_startcol);
      if (isset($results[$part_key]))
      {
        $part = $results[$part_key];
      }
      else if (null === ($part = PartPeer::getInstanceFromPool($part_key)))
      {
        $part = new Part();
        $part->hydrate($row, $part_startcol);
        PartPeer::addInstanceToPool($part, $part_key);
      }

      $results[$part_key] = $part;
      $result_ids[] = $part->getId();
    }    
    unset($stmt);

    //now get the variants
    $original_c->add(PartPeer::ID, $result_ids, Criteria::IN);
    $stmt = BasePeer::doSelect($original_c, $con);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $part_key = PartPeer::getPrimaryKeyHashFromRow($row, $part_startcol);
      $variant_key = PartVariantPeer::getPrimaryKeyHashFromRow($row, $variant_startcol);
      if (null === ($variant = PartVariantPeer::getInstanceFromPool($variant_key)))
      {
        $variant = new PartVariant();
        $variant->hydrate($row, $variant_startcol);
        PartVariantPeer::addInstanceToPool($variant, $variant_key);
      }

      $part = $results[$part_key];
      if ($variant->getIsDefaultVariant())
      {
        $part->setDefaultVariant($variant);
      }
      else
      {
        $part->addPartVariant($variant);
        $variant->setPart($part);
      }
    }
    unset($stmt);

    //this makes it look as if we'd loaded the part variants using 'getPartVariants();'.
    //otherwise, it would re-query the db when you next called getPartVariants.
    foreach ($results AS $result)
    {
      $result->setPartVariantsFullyLoaded(true);
    }

    return array_values($results);
  }

  public static function addBarcodeSearch($code, $c, $active_only = true)
  {
    $c->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
    if ($active_only)
    {
      $c->add(PartPeer::ACTIVE, true);
    }
    if (preg_match('/^\-[0-9]{5}$/', $code))
    {
      $c->add(PartVariantPeer::ID, (int) substr($code, 1));
    }
    else
    {
      $cs1 = $c->getNewCriterion(PartVariantPeer::INTERNAL_SKU, $code);
      $cs2 = $c->getNewCriterion(PartVariantPeer::MANUFACTURER_SKU, $code);
      $supplier_skus = sprintf("%s IN ( SELECT %s FROM %s WHERE %s = '".mysql_escape_string($code)."')",
        PartVariantPeer::ID,
        PartSupplierPeer::PART_VARIANT_ID,
        PartSupplierPeer::TABLE_NAME,
        PartSupplierPeer::SUPPLIER_SKU
      );
      $cs3 = $c->getNewCriterion(PartVariantPeer::ID, $supplier_skus, Criteria::CUSTOM);
      $cs1->addOr($cs2);
      $cs1->addOr($cs3);
      $c->add($cs1);
    }
  }

  public static function searchByBarcode($code, $active_only = true)
  {
    $c = new Criteria();
    self::addBarcodeSearch($code, $c, $active_only);

    return self::doSelectJoinPartVariants($c);
  }

  public static function getInventoryValue($include_on_hold = true, $retail = false, $date = null)
  {
    //gets the total inventory value for all parts
    //if date is set, attempt to undo all part lots since that date, and then add all parts used since then
    $amount = 0;

    //get the total current amount
    $query = 'SELECT SUM((' . PartVariantPeer::CURRENT_ON_HAND.($include_on_hold ? '' : ' - '.PartVariantPeer::CURRENT_ON_HOLD) . ")".
             ' * '.
             ($retail ? 'COALESCE('.PartVariantPeer::UNIT_PRICE.', '.
                                    PartVariantPeer::UNIT_COST.' * (1 + ('.PartVariantPeer::MARKUP_PERCENT.' / 100)),'.
                                    PartVariantPeer::UNIT_COST.' + '.PartVariantPeer::MARKUP_AMOUNT.')'
                      : PartVariantPeer::UNIT_COST).
              ') FROM '.PartVariantPeer::TABLE_NAME.' WHERE '.
              ($retail ? '('.PartVariantPeer::UNIT_COST.' IS NOT NULL OR '.PartVariantPeer::UNIT_PRICE.' IS NOT NULL)'
                       : PartVariantPeer::UNIT_COST.' IS NOT NULL');
    $conn = Propel::getConnection();


    /*
    * Define DB charset as UTF8
    */
    mysql_set_charset('utf8');

    $statement = $conn->prepare($query);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_NUM);
   
    $amount = $row[0];

    //add up amounts for parts where cost must be calculated
    $c = new Criteria(); 
    $c->add(PartVariantPeer::UNIT_COST, null, Criteria::ISNULL);
    if ($retail)
    {
      $c->add(PartVariantPeer::UNIT_PRICE, null, Criteria::ISNULL);
    }
    $variants = PartVariantPeer::doSelect($c);
    foreach ($variants AS $variant)
    {
      $amount += ($include_on_hold ? $variant->getCurrentOnHand() : $variant->getCurrentAvailable()) * 
                 ($retail ? $variant->calculateUnitPrice() : $variant->calculateUnitCost());
    }

    if ($date)
    {
      //we must add back any parts that were used since the specified date, and subtract part lots added since then as well

      //part instances
      $query = 'SELECT SUM('.PartInstancePeer::QUANTITY.' * '.($retail ? PartInstancePeer::UNIT_PRICE : PartInstancePeer::UNIT_COST).')'.
               ' FROM '.PartInstancePeer::TABLE_NAME.
               ' WHERE '.PartInstancePeer::DATE_USED." > '".date('Y-m-d H:i:s',$date)."'".
               ' AND '. ($include_on_hold ? PartInstancePeer::ALLOCATED.'=1 ' : PartInstancePeer::DELIVERED.'=1');
      $statement = $conn->prepare($query);
      $statement->execute();
      $row = $statement->fetch(PDO::FETCH_NUM);
     
      $amount += $row[0];

      //part lots
      $c = new Criteria();
      $c->add(PartLotPeer::RECEIVED_DATE, $date, Criteria::GREATER_THAN);
      $lots = PartLotPeer::doSelectJoinPartVariant($c);
      foreach ($lots AS $lot)
      {
        if ($retail)
        {
          $amount -= $lot->getQuantityReceived() * $lot->getPartVariant()->calculateUnitPrice();
        }
        else if ($lot->getPartVariant()->getUnitCost())
        {
          $amount -= $lot->getQuantityReceived() * $lot->getPartVariant()->calculateUnitCost();
        }
        else if ($lot->getLandedCost())
        {
          $amount -= $lot->getQuantityReceived() * $lot->getLandedCost();
        }
      }

    }

    return $amount;
  }


  public static function doCountDupeSkus()
  {
    $sql = 'SELECT count(internal_sku) FROM ('.
              ' SELECT '.PartVariantPeer::INTERNAL_SKU.', COUNT('.PartVariantPeer::INTERNAL_SKU.') AS cnt'.
              ' FROM '.PartPeer::TABLE_NAME.', '.PartVariantPeer::TABLE_NAME.
              ' WHERE '.PartPeer::ID.' = '.PartVariantPeer::PART_ID.
              ' AND '.PartPeer::ACTIVE.' = 1 AND '.PartVariantPeer::INTERNAL_SKU." <> ''".
              ' GROUP BY '.PartVariantPeer::INTERNAL_SKU.' ORDER BY '.PartVariantPeer::INTERNAL_SKU.' ASC'.
            ') AS src_query WHERE cnt > 1';

    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);

    return $row[0];
  }

  public static function getPartCSVData($workId){
    $sql = 'select b.label, d.name, a.quantity, a.unit_price, d.origin, round(a.quantity * a.unit_price, 2) as totalAmount from part_instance a
    join workorder_item b on a.workorder_item_id = b.id
    join part_variant c on a.part_variant_id = c.id
    join part d on c.part_id = d.id
    where b.workorder_id = ' .$workId; 
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchall(PDO::FETCH_NUM);
    return $row;
  }

}
