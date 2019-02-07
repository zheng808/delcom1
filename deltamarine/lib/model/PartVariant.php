<?php

class PartVariant extends BasePartVariant
{

  protected $calculatedUnitCost;
  protected $calculatedUnitPrice;
  protected $partLotsFullyLoaded;
  protected $partSuppliersFullyLoaded;
  protected $partOptionValuesFullyLoaded;
  
  public function isFitOptionValues($option_values)
  {
  	$option_quont = count($option_values);
  	if ( $option_quont < 1 )
  		return true;
  	$c = new Criteria();
  	$c->add(PartOptionValuePeer::PART_VARIANT_ID, $this->getId());
  	foreach ( $option_values as $option_value )
  		$c->addOr($c->getNewCriterion(PartOptionValuePeer::ID, $option_value));
  	
  	if ( PartOptionValuePeer::doCount($c) < $option_quont )
  		return false;
  	else
  		return true;
  }

  public function __toString()
  {
    $out = $this->getPart()->getName();
    if ($this->getPart()->getIsMultisku())
    {
       $out .= ' ('.$this->outputOptionValuesList().')';
    }

    return $out;
  }

  public function getQuantity($type, $with_units = true)
  {
    if ($this->getTrackInventory())
    {
      if ($type == 'onhand')         $amount = $this->getCurrentOnHand();
      else if ($type == 'onhold')    $amount = $this->getCurrentOnHold();
      else if ($type == 'onorder')   $amount = $this->getCurrentOnOrder();
      else if ($type == 'available') $amount = $this->getCurrentAvailable();
      else if ($type == 'minimum')   $amount = $this->getMinimumOnHand();
      else if ($type == 'maximum')   $amount = $this->getMaximumOnHand();
      else if ($type == 'standard')  $amount = $this->getStandardPackageQty();
      else return null;

      return round($amount,3).(($with_units && $this->getUnits()) ? ' '.$this->getUnits() : '');
    }

    return ($with_units ? 'Not Tracked' : false);
  }

  public function getCurrentAvailable()
  {
    return ($this->getCurrentOnHand() - $this->getCurrentOnHold());
  }

  public function getOptionValue($option_id)
  {
    $obj = $this->getOptionValueObject($option_id);

    return ($obj ? $obj->getValue() : null);
  }

  public function getOptionValueObject($option_id)
  {
    foreach ($this->getPartOptionValues() AS $value)
    {
      if ($value->getPartOptionId() == $option_id)
      {
        return ($value);
      }
    }

    return null;
  }

  //outputs a comma-separated list of option values for quick output of a variant's info
  public function outputOptionValuesList($separator = ', ', $with_option_names = false)
  {
    if ($this->getPart()->getIsMultisku())
    {
      $output = array();
      $options = $this->getPart()->getPartOptionsArray();
      try
      {
          $pov = $this->getPartOptionValues();
	  if(is_array($pov))
	  {
          foreach ($pov AS $value)
          {
            $option = $options[$value->getPartOptionId()];
            $output[$value->getPartOptionId()] = ($with_option_names ? $option['name'].': ' : '').$value->getValue();
          }
	  }
      }
      catch(Exception $e)
      {
      }

      ksort($output);
      return implode($separator, $output);
    }

    return null;
  }


  public function outputUnitCost($include_units = true)
  {
    if ($price = $this->calculateUnitCost(true))
    {
      if ($include_units && $this->getUnits())
      {
        $price .= '/'.$this->getUnits();
      }
      return $price;
    }

    return 'Not Set';
  }

  public function outputUnitPrice($include_units = true)
  {
    if ($price = $this->calculateUnitPrice(true))
    {
      if ($include_units && $this->getUnits())
      {
        $price .= '/'.$this->getUnits();
      }
      return $price;
    }

    return 'Not Set';
  }

  public function calculateUnitCost($format = false, $source_variant = null)
  {
    if ($this->calculatedUnitCost)
    {
      $amount = $this->calculatedUnitCost;
    }
    else if ($this->getUseDefaultCosting() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->calculateUnitCost($format, $this);
    }
    else if ($this->getUnitCost())
    {
      $amount = $this->getUnitCost();
    }
    else 
    {
      //even if this is being calculated from the default variant, if this call originated from another variant
      //then we use the landed cost of that variant, not the default, since there will be no lots for the default
      if (!($amount = ($source_variant ? $source_variant->calculateCostFromLots() : $this->calculateCostFromLots())))
      {
        //no lots around to generate current price
        return false;
      }
    }

    if (!$this->calculatedUnitCost)
    {
      $this->calculatedUnitCost = $amount;
    }

    if ($format)
    {
      $symbol = '$';
      return $symbol.number_format($amount, 2);
    }

    return $amount;
  }

  //source_variant is set if we're using the default variant for price markup information,
  // but using the part lots
  public function calculateUnitPrice($format = false, $source_variant = null)
  {
    $save_amount = false;
    if ($this->calculatedUnitPrice)
    {
      $amount = $this->calculatedUnitPrice;
    }
    else if ($this->getUseDefaultPricing() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->calculateUnitPrice($format, $this);
    }
    else if ($this->getUnitPrice())
    {
      $amount = $this->getUnitPrice();
      $save_amount = true;
    }
    else if (($this->getMarkupAmount() || $this->getMarkupPercent()) && ($unit_cost = ($source_variant ? $source_variant->calculateUnitCost(false) : $this->calculateUnitCost(false))))
    {
      if ($this->getMarkupAmount())
      {
        //price is set by a specified increase over specified cost
        $amount = $unit_cost + $this->getMarkupAmount();
      }
      else
      {
        //price is set by a percentage increase over specified cost
        $amount = $unit_cost * (1 + ($this->getMarkupPercent() / 100));
      }
      $save_amount = ($source_variant == null);
    }
    else
    {
      //no cost or markup specified
      return false;
    }

    if (!$this->calculatedUnitPrice && $save_amount)
    {
      $this->calculatedUnitPrice = $amount;
    }

    if ($format)
    {
      $symbol = '$';
      return $symbol.number_format($amount, 2);
    }

    return $amount;
  }

  public function calculateCostFromLots()
  {
    if ($this->getCostCalculationMethod() == 'fifo') //first in first out
    {
      return $this->getFifoLotPrice();
    }
    else if ($this->getCostCalculationMethod() == 'lifo') //last in first out
    {
      return $this->getLifoLotPrice();
    }
    else if ($this->getCostCalculationMethod() == 'average')
    {
      return $this->getAverageLotPrice();
    }

    return false;
  }

  //re-calculates the Quantity on Hand field using the part_lots with values left
  public function calculateCurrentOnHand()
  {
    if ($this->getTrackInventory())
    {
      $c = new Criteria();
      $c->add(PartLotPeer::PART_VARIANT_ID, $this->getId());
      $c->add(PartLotPeer::QUANTITY_RECEIVED, 0, Criteria::GREATER_THAN);
      $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
      $lots = PartLotPeer::doSelect($c);
      $tally = 0;
      foreach ($lots AS $lot)
      {
        $tally += $lot->getQuantityRemaining();
      }

      $this->setCurrentOnHand($tally);
      $this->save();

      return $tally;
    }
    else
    {
      return false;
    }
  }

  //re-calculates the Quantity on Hold field using the part_instances that indicate a hold
  public function calculateCurrentOnHold()
  {
    if ($this->getTrackInventory())
    {
      $c = new Criteria();
      $c->add(PartInstancePeer::PART_VARIANT_ID, $this->getId());
      $c->add(PartInstancePeer::ALLOCATED, true);
      $c->add(PartInstancePeer::DELIVERED, false);
      $instances = PartInstancePeer::doSelect($c);
      $tally = 0;
      foreach ($instances AS $instance)
      {
        $tally += $instance->getQuantity();
      }
    
      $this->setCurrentOnHold($tally);
      $this->save();

      return $tally;
    }
    else
    {
      return false;
    }
  }

  //re-calculates the Quantity On Order field using the unclosed orders
  public function calculateCurrentOnOrder()
  {
    if ($this->getTrackInventory())
    {
      //get all supplier_orders which have no items in them
      $c = new Criteria();
      $c->add(SupplierOrderItemPeer::PART_VARIANT_ID, $this->getId());
      $c->add(SupplierOrderItemPeer::QUANTITY_REQUESTED, 0, Criteria::GREATER_THAN);
      $c->add(SupplierOrderItemPeer::QUANTITY_COMPLETED, SupplierOrderItemPeer::QUANTITY_COMPLETED.'<'.SupplierOrderItemPeer::QUANTITY_REQUESTED, Criteria::CUSTOM);
      $orders = SupplierOrderItemPeer::doSelect($c);
      $tally = 0;
      foreach ($orders AS $order)
      {
        $tally += ($order->getQuantityRequested() - $order->getQuantityCompleted());
      }
    
      $this->setCurrentOnOrder($tally);
      $this->save();

      return $tally;
    }
    else
    {
      return false;
    }
  }

  public function getAverageLotPrice()
  {
    //get all lots which have items in them
    $c = new Criteria();
    $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
    $c->add(PartLotPeer::LANDED_COST, 0, Criteria::GREATER_THAN);

    if ($lots = $this->getPartLots($c))
    {
      $total_quantity = 0;
      $total_cost = 0;
      foreach ($lots AS $lot)
      {
        if ($lot->getLandedCost())
        {
          $total_quantity += $lot->getQuantityRemaining();
          $total_cost += ($lot->getQuantityRemaining() * $lot->getLandedCost());
        }
      }
      if ($total_cost > 0 && $total_quantity > 0)
      {
        return round($total_cost / $total_quantity, 2);
      }
    }

    return false;
  }

  public function getFifoLotPrice()
  {
    //get oldest lot which has items in them
    $c = new Criteria();
    $c->add(PartLotPeer::PART_VARIANT_ID, $this->getId());
    $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
    $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
    $lot = PartLotPeer::doSelectOne($c);

    return ($lot && $lot->getLandedCost() ? $lot->getLandedCost() : false);
  }

  public function getLifoLotPrice()
  {
    //get oldest lot which has items in them
    $c = new Criteria();
    $c->add(PartLotPeer::PART_VARIANT_ID, $this->getId());
    $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
    $c->addDescendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
    $lot = PartLotPeer::doSelectOne($c);

    return ($lot && $lot->getLandedCost() ? $lot->getLandedCost() : false);
  }

  public function calculateInventoryCost()
  {
    $method = $this->getCostCalculationMethod();
    $this->setCostCalculationMethod('average'); //ensures that we take into account all lots
    $unit_cost = $this->calculateUnitCost();
    $this->setCostCalculationMethod($method);

    return ($unit_cost ? $unit_cost * $this->getCurrentOnHand() : false);
  }

  public function calculateInventoryRetailValue()
  {
    $method = $this->getCostCalculationMethod();
    $this->setCostCalculationMethod('average'); //ensures that we take into account all lots
    $unit_price = $this->calculateUnitPrice();
    $this->setCostCalculationMethod($method);

    return ($unit_price ? $unit_price * $this->getCurrentOnHand() : false);
  }

  public function setPartSuppliersFullyLoaded($v, $criteria_used = null)
  {
    $this->partSuppliersFullyLoaded = $v;
  }

  public function getPartSuppliers($criteria = null, PropelPDO $con = null)
  {
    if ($this->partSuppliersFullyLoaded)
    {
      return $this->collPartSuppliers;
    }
    else
    {
      return parent::getPartSuppliers($criteria, $con);
    }
  }

  public function getPartSupplierById($supplier_id)
  {
    $supps = $this->getPartSuppliers();
    foreach ($supps AS $sup)
    {
      if ($sup->getSupplierId() == $supplier_id)
      {
        return $sup;
      }
    }

    return false;
  }

  public function hasSupplier($supplier_id)
  {
    return ($this->getPartSupplierById($supplier_id) !== false);
  }

  public function setPartLotsFullyLoaded($v, $criteria_used = null)
  {
    $this->partLotsFullyLoaded = $v;

  }

  public function getPartLots($criteria = null, PropelPDO $con = null)
  {
    if ($this->partLotsFullyLoaded)
    {
      return $this->collPartLots;
    }
    else
    {
      return parent::getPartLots($criteria, $con);
    }
  }

  public function setPartOptionValuesFullyLoaded($v, $criteria_used = null)
  {
    $this->partOptionValuesFullyLoaded = $v;

  }

  public function getPartOptionValues($criteria = null, PropelPDO $con = null)
  {
    if ($this->partOptionValuesFullyLoaded)
    {
      return $this->collPartOptionValues;
    }
    else
    {
      return parent::getPartOptionValues($criteria, $con);
    }
  }

  public function canDelete($ignore_defaults = false)
  {
    if (!$ignore_defaults)
    {
      //make sure this isn't a default one
      if ($this->getIsDefaultVariant()) return false;

      //make sure this isn't the only non-default variant
      if (count($this->getPart()->getPartVariants()) == 1) return false;
    }

    //make sure there are no part_instances with this in it
    if ($this->countSupplierOrderItems() > 0) return false;

    //make sure there are no (non-adjustment) part lots
    $c = new Criteria();
    $c->add(PartLotPeer::SUPPLIER_ORDER_ITEM_ID, null, Criteria::ISNOTNULL);
    $c->add(PartLotPeer::QUANTITY_RECEIVED, 0, Criteria::GREATER_THAN);
    if ($this->countPartLots($c) > 0) return false;

    //make sure there are no (non-adjustment) part instances
    $c = new Criteria();
    $c->add(PartInstancePeer::IS_INVENTORY_ADJUSTMENT, false);
    if ($this->countPartInstances($c) > 0) return false;

    return true;
  }

  public function delete(PropelPDO $con = null)
  {
    //delete part_option_values
    $c = new Criteria();
    $c->add(PartOptionValuePeer::PART_VARIANT_ID, $this->getId());
    PartOptionValuePeer::doDelete($c);

    //delete part_suppliers
    $c = new Criteria();
    $c->add(PartSupplierPeer::PART_VARIANT_ID, $this->getId());
    PartSupplierPeer::doDelete($c);

    //delete part_photos
    $c = new Criteria();
    $c->add(PartPhotoPeer::PART_VARIANT_ID, $this->getId());
    PartPhotoPeer::doDelete($c);

    //delete part_files
    $c = new Criteria();
    $c->add(PartFilePeer::PART_VARIANT_ID, $this->getId());
    PartFilePeer::doDelete($c);

    //delete part_instances (possible to delete with adjustment part_instances present)
    $c = new Criteria();
    $c->add(PartInstancePeer::PART_VARIANT_ID, $this->getId());
    PartInstancePeer::doDelete($c);

    //delete part_lots (possible to delete with adjustment part_lots present)
    $c = new Criteria();
    $c->add(PartLotPeer::PART_VARIANT_ID, $this->getId());
    PartLotPeer::doDelete($c);

    parent::delete();
  }

  /****************************************************************************/
  /*                                                                          */
  /* Below are overrides of functions that allow automatic falling back to    */
  /* default values                                                           */
  /*                                                                          */
  /****************************************************************************/

  public function getMinimumOnHand()
  {
    if ($this->getUseDefaultInventory() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getMinimumOnHand();
    }
    else
    {
      return parent::getMinimumOnHand();
    }
  }

  public function getMaximumOnHand()
  {
    if ($this->getUseDefaultInventory() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getMaximumOnHand();
    }
    else
    {
      return parent::getMaximumOnHand();
    }  
  }

  public function getUnits()
  {
    if ($this->getUseDefaultUnits() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getUnits();
    }
    else
    {
      return parent::getUnits();
    }
  }

  public function getCostCalculationMethod()
  {
    if ($this->getUseDefaultCosting() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getCostCalculationMethod();
    }
    else
    {
      return parent::getCostCalculationMethod();
    }
  }

  public function getShippingWeight()
  {
    if ($this->getUseDefaultDimensions() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getShippingWeight();
    }
    else
    {
      return parent::getShippingWeight();
    }
  }

  public function getShippingWidth()
  {
    if ($this->getUseDefaultDimensions() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getShippingWidth();
    }
    else
    {
      return parent::getShippingWidth();
    }
  }

  public function getShippingHeight()
  {
    if ($this->getUseDefaultDimensions() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getShippingHeight();
    }
    else
    {
      return parent::getShippingHeight();
    }
  }

  public function getShippingDepth()
  {
    if ($this->getUseDefaultDimensions() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getShippingDepth();
    }
    else
    {
      return parent::getShippingDepth();
    }
  }

  public function getShippingVolume()
  {
    if ($this->getUseDefaultDimensions() && !$this->getIsDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getShippingVolume();
    }
    else
    {
      return parent::getShippingVolume();
    }
  }

  public function getTaxableHst()
  {
    if ($this->getUseDefaultPricing() && !$this->isDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getTaxableHst();
    }
    else
    {
      return parent::getTaxableHst();
    }
  }

  public function getTaxablePst()
  {
    if ($this->getUseDefaultPricing() && !$this->isDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getTaxablePst();
    }
    else
    {
      return parent::getTaxablePst();
    }
  }

  public function getTaxableGst()
  {
    if ($this->getUseDefaultPricing() && !$this->isDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getTaxableGst();
    }
    else
    {
      return parent::getTaxableGst();
    }
  }

  public function getEnviroLevy()
  {
    if ($this->getUseDefaultPricing() && !$this->isDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getEnviroLevy();
    }
    else
    {
      return parent::getEnviroLevy();
    }
  }

  public function getBatteryLevy()
  {
    if ($this->getUseDefaultPricing() && !$this->isDefaultVariant())
    {
      return $this->getPart()->getDefaultVariant()->getBatteryLevy();
    }
    else
    {
      return parent::getBatteryLevy();
    }
  }

}
