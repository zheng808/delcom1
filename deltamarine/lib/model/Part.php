<?php

class Part extends BasePart
{
  protected $partVariantsFullyLoaded = false;
  protected $defaultVariant = null;
  protected $partOptions = null;

  public function __toString()
  {
    return $this->getName();
  }

  public function getSuppliers()
  {
    return $this->getDefaultVariant()->getPartSuppliersJoinSupplier();
  }

  public function loadAllSubparts()
  {
    $subparts = array();
    if ($this->getIsMultisku())
    {
      foreach ($this->getPartVariants() AS $variant)
      {
        if ($var_subparts = $variant->getSubpartsRelatedByParentId())
        {
          $subparts[$variant->getId()] = $var_subparts;
        }
      }
    }
    else
    {
      if ($default_subparts = $this->getDefaultVariant()->getSubpartsRelatedByParentId())
      {
        $subparts[$this->getDefaultVariant()->getId()] = $default_subparts;
      }
    }

    return $subparts;
  }

  public function loadAllBarcodes()
  {
    $barcodes = array();

    $c = new Criteria();
    $c->add(PartVariantPeer::PART_ID, $this->getId());
    $c->addJoin(PartVariantPeer::ID, BarcodePeer::PART_VARIANT_ID);
    foreach (BarcodePeer::doSelect($c) AS $custom_barcode)
    {
      if (!isset($barcodes[$custom_barcode->getPartVariantId()]))
      {
        $barcodes[$custom_barcode->getPartVariantId()] = array();
      }
      $barcodes[$custom_barcode->getPartVariantId()][] = $custom_barcode;
    }

    return $barcodes;
  }

  public function getPartOptionsArray()
  {
    if ($this->partOptions === null)
    {
      //get all the options and option values
      $c = new Criteria();
      $c->add(PartOptionPeer::PART_ID, $this->getId());
      $c->addJoin(PartOptionPeer::ID, PartOptionValuePeer::PART_OPTION_ID);

      PartOptionPeer::addSelectColumns($c);
      PartOptionValuePeer::addSelectColumns($c);
      $val_startcol = 0 + (PartOptionPeer::NUM_COLUMNS - PartOptionPeer::NUM_LAZY_LOAD_COLUMNS);
      $stmt = BasePeer::doSelect($c);
      $results = array();
      while ($row = $stmt->fetch(PDO::FETCH_BOTH))
      {
        if (!isset($results[$row[0]]))
        {
          $results[$row[0]] = array('name' => $row['NAME'], 'values' => array());
        }
        if (!in_array($row['VALUE'], $results[$row[0]]['values']))
        {
          $results[$row[0]]['values'][$row[$val_startcol]] = $row['VALUE'];
        }
      }

      $this->partOptions = $results;
    }

    return $this->partOptions;
  }

  public function findPartVariantByOptionValues($params)
  {
    $result = false;
    $vars = $this->getPartVariants();
    foreach ($vars AS $var)
    {
      $found = true;
      foreach ($params AS $option_id => $option_value)
      {
        if ($var->getOptionValue($option_id) != $option_value)
        {
          $found = false;
          break;
        }
      }
      if ($found)
      {
        return ($var);
      }
    }

    return false;
  }

  public function findPartVariantsByOptionValues($params)
  {
    $found_vars = array();
    $vars = $this->getPartVariants();
    foreach ($vars AS $var)
    {
      $matches = true;
      foreach ($params AS $option_id => $option_value)
      {
        if ($option_value != 0 && $params[$option_id] != $var->getOptionValue($option_id))
        {
          $matches = false;
          break;
        }
      }
      if ($matches)
      {
        $found_vars[] = $var;
      }
    }

    return $found_vars;
  }

  //returns the total quantity of all variants
  public function getQuantity($type = 'onhand', $with_units = true)
  {
    $default = $this->getDefaultVariant();
    if ($this->getIsMultisku())
    {
      $units = false;
      $mixed_units = false;
      $tally = 0;
      foreach ($this->getPartVariants() AS $variant)
      {
        if ($variant->getUnits() == $units || $units === false)
        {
          $tally += $variant->getQuantity($type);
          $units = $variant->getUnits();
        }
        else
        {
          $mixed_units = true;
          break;
        }
      }

      return ($mixed_units ? 'Various Units' : round($tally,3).' '.$units);
    }
    else
    {
      return $default->getQuantity($type, false).($default->getUnits() ? ' '.$default->getUnits() : '');
    }
  }

  public function getPartVariants($criteria = null, PropelPDO $con = null)
  {
    if ($criteria === null && $this->partVariantsFullyLoaded)
    {
      return ($this->collPartVariants == null ? array() : $this->collPartVariants);
    }
    else
    {
      if ($criteria === null) $criteria = new Criteria();
      $criteria->add(PartVariantPeer::IS_DEFAULT_VARIANT, false);

      return parent::getPartVariants($criteria, $con);
    }
  }

  public function getPartVariantsOptions($separator = ' / ', $show_option_names = true)
  {
    $data = array();
    foreach ($this->getPartVariants() AS $part_variant)
    {
      $data[$part_variant->getId()] = $part_variant->outputOptionValuesList($separator, $show_option_names);
    }

    return $data;
  }

  public function sortPartVariants()
  {
    $new_array = array();
    foreach ($this->getPartVariants() AS $variant)
    {
      $key = $variant->outputOptionValuesList('', false);
      $new_array[$key] = $variant;
    }
    ksort($new_array);
    $this->collPartVariants = array_values($new_array);
  }

  //attempts to return a unit price. only effective if non-multisku or if all
  //variants have the same price and units
  public function getUnitPrice()
  {
    $default = $this->getDefaultVariant();
    if ($this->getIsMultisku())
    {
      $units = false;
      $min = null;
      $min_value = 0;
      $max = null;
      $max_value = 0;

      foreach ($this->getPartVariants() AS $variant)
      {
        if ($variant->getUnits() == $units || $units === false)
        {
          $units = $variant->getUnits();
          $this_val = $variant->calculateUnitPrice();
          if (!$min || ($this_val < $min_value))
          {
            $min = $variant;
            $min_value = $this_val;
          }
          if ($this_val > $max_value)
          {
            $max = $variant;
            $max_value = $this_val;
          }
        }
        else
        {
          return 'Various Units';
        }
      }

      if (!$min_value && !$max_value)
      {
        return 'Not Set';
      }
      if ($min_value && $max_value && ($min_value != $max_value))
      {
        $cost = $min->outputUnitPrice(false).' - '.$max->outputUnitPrice(false);
      }
      else if ($min_value)
      {
        $cost = $min->outputUnitPrice(false);
      }
      else if ($max_value)
      {
        $cost = $max->outputUnitPrice(false);
      }
      return $cost.($units ? '/'.$units : '');
    }
    else
    {
      return $default->outputUnitPrice(true); //include units
    }
  }

  //this is called when the first option is added
  public function convertToMultisku()
  {
    //move default into a new variation, referencing the new default's settings
    //we keep the same variation Id so that orders, part_instances, etc still reference the proper version
    $old_default = $this->getDefaultVariant();

    //set up the new default
    $new_default = $old_default->copy();
    $new_default->setCurrentOnHand(0);
    $new_default->setCurrentOnHold(0);
    $new_default->setIsDefaultVariant(true);
    $new_default->save();

    //set up the old default
    $old_default->setUseDefaultUnits(true);
    $old_default->setUseDefaultPricing(true);
    $old_default->setUseDefaultDimensions(true);
    $old_default->setUseDefaultInventory(true);
    $old_default->setIsDefaultVariant(false);
    $old_default->save();

    $this->setIsMultisku(true);
    $this->save();

    //move suppliers from old default to new default
    $suppliers = $old_default->getPartSuppliers();
    foreach ($suppliers AS $supplier)
    {
      if ($supplier->getSupplierSku())
      {
        $supp = new PartSupplier();
        $supp->setPartVariantId($new_default->getId());
        $supp->setSupplierId($supplier->getSupplierId());
        $supp->setNotes($supplier->getNotes());
        $supp->save();

        $supplier->setNotes(null);
        $supplier->save();
      }
      else
      {
        //move
        $supplier->setPartVariantId($new_default->getId());
        $supplier->save();
      }
    }
  }

  //this is called when the last option is deleted. by now, only one variation would be left.
  public function convertToSinglesku()
  {
    //move last remaining variation into a default
    $old_default = $this->getDefaultVariant();

    //set up the new default
    $new_default = $this->getPartVariants();
    if (!$new_default || count($new_default) != 1) return false;  //crude check, just in case
    $new_default = $new_default[0];
    $new_default->setIsDefaultVariant(true);
    if ($new_default->getUseDefaultUnits())
    {
      $new_default->setUseDefaultUnits(false);
      $new_default->setUnits($old_default->getUnits());
    }
    if ($new_default->getUseDefaultPricing())
    {
      $new_default->setUseDefaultPricing(false);
      $new_default->setCostCalculationMethod($old_default->getCostCalculationMethod());
      $new_default->setUnitCost($old_default->getUnitCost());
      $new_default->setUnitPrice($old_default->getUnitPrice());
      $new_default->setMarkupAmount($old_default->getMarkupAmount());
      $new_default->setMarkupPercent($old_default->getMarkupPercent());
    }
    if ($new_default->getUseDefaultDimensions())
    {
      $new_default->setUseDefaultDimensions(false);
      $new_default->setShippingWeight($old_default->getShippingWeight());
      $new_default->setShippingWidth($old_default->getShippingWidth());
      $new_default->setShippingHeight($old_default->getShippingHeight());
      $new_default->setShippingDepth($old_default->getShippingDepth());
      $new_default->setShippingVolume($old_default->getShippingVolume());
    }
    if ($new_default->getUseDefaultInventory())
    {
      $new_default->setUseDefaultInventory(false);
      $new_default->setMinimumOnHand($old_default->getMinimumOnHand());
      $new_default->setMaximumOnHand($old_default->getMaximumOnHand());
    }
    $new_default->save();

    //move suppliers from old default to new default
    $old_suppliers = $old_default->getPartSuppliers();
    $new_suppliers = $new_default->getPartSuppliers();
    foreach ($old_suppliers AS $old_supplier)
    {
      $found = false;
      foreach ($new_suppliers AS $new_supplier)
      {
        if ($old_supplier->getSupplierId() == $new_supplier->getSupplierId())
        {
          $new_supplier->setNotes($old_supplier->getNotes());
          $new_supplier->save();
          $found = true;
        }
      }
      if (!$found)
      {
        $supp = new PartSupplier();
        $supp->setPartVariantId($new_default->getId());
        $supp->setSupplierId($old_supplier->getSupplierId());
        $supp->setNotes($old_supplier->getNotes());
        $supp->save();
      }
      $old_supplier->delete();
    }

    $old_default->delete();

    $this->setIsMultisku(false);
    $this->save();
  }


  //gets the default variant by searching the already-retrieved records (if set)
  //or by looking it up directly in the database otherwise
  public function getDefaultVariant()
  {
    if ($this->defaultVariant === null && !$this->isNew())
    {
      $c = new Criteria();
      $c->add(PartVariantPeer::PART_ID, $this->getId());
      $c->add(PartVariantPeer::IS_DEFAULT_VARIANT, true);

      $this->defaultVariant = PartVariantPeer::doSelectOne($c);
    }

    return $this->defaultVariant;
  }

  public function setDefaultVariant($v)
  {
    $this->defaultVariant = $v;
  }

  public function setPartVariantsFullyLoaded($v)
  {
    $this->partVariantsFullyLoaded = $v;
  }

  public function reload($deep = false, PropelPDO $con = null)
  {
    $this->setDefaultVariant(null);
    $this->setPartVariantsFullyLoaded(null);
    $this->partOptions = null;

    parent::reload($deep, $con);
  }

  public function canDelete()
  {
    $result = true; 
    if ($this->getIsMultisku())
    {
      foreach ($this->getPartVariants() AS $variant)
      {
        if (!$variant->canDelete(true))
        {
          $result = false;
          break;
        }
      }
    }
    else
    {
      $result = $this->getDefaultVariant()->canDelete(true);
    }

    return $result;
  }

  public function delete(PropelPDO $con = null)
  {
    foreach ($this->getPartOptions() AS $option)
    {
      $option->delete();
    }

    $this->getDefaultVariant()->delete();

    foreach ($this->getPartVariants() AS $variant)
    {
      $variant->delete();
    }

    parent::delete($con);
  }
}
