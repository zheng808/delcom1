<?php

/**
 * part actions.
 *
 * @package    deltamarine
 * @subpackage part
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class partActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'parts');
  }

  /*
   * Executes index action to view and filter parts
   */
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * add a new part
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    //check for valid category
    if (!($category = PartCategoryPeer::retrieveByPk($request->getParameter('part_category_id'))))
    {
      $result = false;
      $errors['part_category_id'] = 'Invalid Category Selected!';
    }
    //check for both markup amount and percent
    if (is_numeric($request->getParameter('markup_amount')) && is_numeric($request->getParameter('markup_percent')))
    {
      $result = false;
      $errors['markup_percent'] = 'Please specify only the markup amount or markup percent, but not both!';
    }
    if (((float) $request->getParameter('markup_amount')) < 0)
    {
      $result = false;
      $errors['markup_amount'] = 'Markup amount cannot be negative';
    }
    if (((float) $request->getParameter('markup_percent')) < 0)
    {
      $result = false;
      $errors['markup_percent'] = 'Markup percent cannot be negative';
    }
    if (((float) $request->getParameter('enviro_levy')) < 0)
    {
      $result = false;
      $errors['enviro_levy'] = 'Enviro Levy cannot be negative';
    }
    if (((float) $request->getParameter('battery_levy')) < 0)
    {
      $result = false;
      $errors['battery_levy'] = 'Battery Levy cannot be negative';
    }  
    $internal_sku = trim($request->getParameter('internal_sku'));
    $c = new Criteria();
    $c->add(PartPeer::ACTIVE, true);
    $c->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
    $c->add(PartVariantPeer::INTERNAL_SKU, $internal_sku, Criteria::LIKE);
    if ($used = PartVariantPeer::doSelectOne($c))
    {
      $result = false;
      $errors['internal_sku'] = 'The inputted internal SKU is already used!<br />'.
        '<strong>'.$used->getInternalSku().':</strong> <a href="'.$this->getController()->genUrl('part/view?id='.$used->getPartId()).'">'.$used->getPart()->getName().'</a>';
    }
    //check for valid supplier info
    $supplier_id = $request->getParameter('supplier_id');
    $supplier_sku = $request->getParameter('supplier_sku');
    $supplier_notes = $request->getParameter('supplier_notes');
    if ($supplier_id && (!SupplierPeer::retrieveByPk($supplier_id)))
    {
      $result = false;
      $errors['supplier_id'] = 'Invalid Supplier Selected';
    }
    else if (($supplier_sku || $supplier_notes) && !$supplier_id)
    {
      $result = false;
      $errors['supplier_id'] = 'Must select valid supplier to add supplier sku and notes!';
    }
    //check initial quantity info
    if (((float) $request->getParameter('initial_quantity')) < 0)
    {
      $result = false;
      $errors['initial_quantity'] = 'Quantities cannot be negative';
    }
    else if (is_numeric($request->getParameter('initial_quantity')))
    {
      if (!is_numeric($request->getParameter('initial_cost')))
      {
        $result = false;
        $errors['initial_cost'] = 'Initial Cost must be specified if setting initial quantity (or set to 0)';
      }
      else if (((float) $request->getParameter('initial_cost')) < 0)
      {
        $result = false;
        $errors['initial_cost'] = 'Initial cost cannot be negative!';
      }
    }
    
    if ($result)
    {
      $part = new Part();
      $part->setPartCategoryId($request->getParameter('part_category_id'));
      $part->setName($request->getParameter('name'));
      $part->setDescription($request->getParameter('description'));
      $part->setHasSerialNumber(($request->getParameter('has_serial_number') == '1'));
      $part->setActive($request->getParameter('active') == '1');
      $part->setOrigin($request->getParameter('origin'));
      if ($request->getParameter('manufacturer_id'))
      {
        $part->setManufacturerId($request->getParameter('manufacturer_id'));
      }
      $part->save();

      $variant = new PartVariant();
      $variant->setPart($part);
      $variant->setIsDefaultVariant(true);
      $variant->setManufacturerSku($request->getParameter('manufacturer_sku'));
      $variant->setInternalSku($request->getParameter('internal_sku'));
      if ($request->getParameter('units') && ($request->getParameter('units') != 'Items'))
      {
        $variant->setUnits($request->getParameter('units'));
      }
      $variant->setCostCalculationMethod($request->getParameter('cost_calculation_method'));
      if (((float) $request->getParameter('unit_cost')) > 0)
      {
        $variant->setUnitCost($request->getParameter('unit_cost'));
      } else if (!((float) $request->getParameter('unit_cost')) || ((float) $request->getParameter('unit_cost')) <= 0)
      {
        $variant->setUnitCost(0);
      }
      if (((float) $request->getParameter('broker_fees')) > 0)
      {
        $variant->setBrokerFees($request->getParameter('broker_fees'));
      }
      if (((float) $request->getParameter('shipping_fees')) > 0)
      {
        $variant->setShippingFees($request->getParameter('shipping_fees'));
      }
      if (((float) $request->getParameter('unit_price')) > 0)
      {
        $variant->setUnitPrice($request->getParameter('unit_price'));
      }
      if ($request->getParameter('markup_amount') > 0)
      {
        $variant->setMarkupAmount($request->getParameter('markup_amount'));
      }
      if ($request->getParameter('markup_percent') > 0)
      {
        $variant->setMarkupPercent($request->getParameter('markup_percent'));
      }
      if ($request->getParameter('enviro_levy') > 0)
      {
        $variant->setEnviroLevy($request->getParameter('enviro_levy'));
      }
      if ($request->getParameter('battery_levy') > 0)
      {
        $variant->setBatteryLevy($request->getParameter('battery_levy'));
      }
      $variant->setTrackInventory(($request->getParameter('track_inventory') == '1'));
      $variant->setMinimumOnHand($request->getParameter('minimum_on_hand',0));
      if ($request->getParameter('maximum_on_hand') > 0)
      {
       $variant->setMaximumOnHand($request->getParameter('maximum_on_hand'));
      }
      if ($request->getParameter('standard_package_qty') > 0)
      $variant->setStandardPackageQty($request->getParameter('standard_package_qty'));
      $variant->setStockingNotes($request->getParameter('stocking_notes') ? $request->getParameter('stocking_notes') : null);
      $variant->setLocation($request->getParameter('location') ? $request->getParameter('location') : null);
      $variant->save();

      //add initial lot 
      if ($request->getParameter('initial_quantity') > 0)
      {
        $lot = new PartLot();
        $lot->setPartVariant($variant);
        $lot->setQuantityReceived($request->getParameter('initial_quantity'));
        $lot->setQuantityRemaining($request->getParameter('initial_quantity'));
        $lot->setReceivedDate(time());
        $lot->setLandedCost($request->getParameter('initial_cost'));
        $lot->save();
        
        $variant->setCurrentOnHand($lot->getQuantityRemaining());
        $variant->save();
      }
 
      //add supplier record(s)
      if ($supplier_id)
      {
        $partsup = new PartSupplier();
        $partsup->setPartVariant($variant);
        $partsup->setSupplierId($supplier_id);
        $partsup->setSupplierSku($supplier_sku);
        if (substr($supplier_notes, 0, 11) != 'Enter price')
        {
          $partsup->setNotes($supplier_notes);
        }
        $partsup->save();
      }

      $this->renderText('{success:true,newid:'.$part->getId().'}');
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   *  View details about a part and interface for editing
   */
  public function executeView(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeView====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->part = $this->loadPart($request);

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeView====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::SUCCESS;
  }//executeView()-------------------------------------------------------------

  /*
   * Load a part's information for editing
   */
  public function executeLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $part = $this->loadPart($request);
    $default = $part->getDefaultVariant();

    $data = array(
        'id' => $part->getId(),
        'name' => $part->getName(),
        'part_category_id' => $part->getPartCategoryId(),
        'has_serial_number' => ($part->getHasSerialNumber() ? '1' : '0'),
        'internal_sku' => $default->getInternalSku(),
        'manufacturer_id' => $part->getManufacturerId(),
        'manufacturer_name' => ($part->getManufacturerId() ? $part->getManufacturer()->getName() : ''),
        'manufacturer_sku' => $default->getManufacturerSku(),
        'description' => $part->getDescription(),
        'cost_calculation_method' => $default->getCostCalculationMethod(),
        'unit_cost' => $default->getUnitCost(),
        'markup_amount' => $default->getMarkupAmount(),
        'markup_percent' => $default->getMarkupPercent(),
        'broker_fees' => $default->getBrokerFees(),
        'shipping_fees' => $default->getShippingFees(),
        'unit_price' => $default->getUnitPrice(),
        'enviro_levy' => $default->getEnviroLevy(),
        'battery_levy' => $default->getBatteryLevy(),
        'units' => ($default->getUnits() ? $default->getUnits() : 'Items'),
        'track_inventory' => ($default->getTrackInventory() ? '1' : '0'),
        'minimum_on_hand' => $default->getQuantity('minimum', false),
        'maximum_on_hand' => $default->getQuantity('maximum', false),
        'current_on_hand' => $default->getQuantity('current', false),
        'standard_package_qty' => $default->getStandardPackageQty(),
        'location' => (string) $default->getLocation(),
        'stocking_notes' => str_replace("\n", '<br />', addslashes($default->getStockingNotes())),
        'active' => ($part->getActive() ? '1' : '0'),
        'origin' => $part->getOrigin()
      );

    $this->renderText('{success:true,data:'.json_encode($data).'}');

    return sfView::NONE;
  }//executeLoad()-------------------------------------------------------------

  /*
   * save a part's information after editing
   */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $part = $this->loadPart($request);
    
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Start executeEdit';
      sfContext::getInstance()->getLogger()->info($message);
    }


    //validate
    $result = true;
    $errors = array();

    //check for valid category
    if (!($category = PartCategoryPeer::retrieveByPk($request->getParameter('part_category_id'))))
    {
      $result = false;
      $errors['part_category_id'] = 'Invalid Category Selected!';
    }
    //check for both markup amount and percent
    if (is_numeric($request->getParameter('markup_amount')) && is_numeric($request->getParameter('markup_percent')))
    {
      $result = false;
      $errors['markup_percent'] = 'Please specify only the markup amount or markup percent, but not both!';
    }
    if (((float) $request->getParameter('markup_amount')) < 0)
    {
      $result = false;
      $errors['markup_amount'] = 'Markup amount cannot be negative';
    }
    if (((float) $request->getParameter('markup_percent')) < 0)
    {
      $result = false;
      $errors['markup_percent'] = 'Markup percent cannot be negative';
    }
    if (((float) $request->getParameter('enviro_levy')) < 0)
    {
      $result = false;
      $errors['enviro_levy'] = 'Enviro Levy cannot be negative';
    }
    if (((float) $request->getParameter('battery_levy')) < 0)
    {
      $result = false;
      $errors['battery_levy'] = 'Battery Levy cannot be negative';
    }  
    
    if ($result)
    {
      $part->setPartCategoryId($request->getParameter('part_category_id'));
      $part->setName($request->getParameter('name'));
      $part->setDescription($request->getParameter('description'));
      $part->setHasSerialNumber(($request->getParameter('has_serial_number') == '1'));
      $part->setManufacturerId($request->getParameter('manufacturer_id') ? $request->getParameter('manufacturer_id') : null);
      $part->setActive($request->getParameter('active') == '1');
      $part->setOrigin($request->getParameter('origin'));
      $part->save();

      $variant = $part->getDefaultVariant();
      $variant->setManufacturerSku($request->getParameter('manufacturer_sku'));
      $variant->setInternalSku($request->getParameter('internal_sku'));
      $variant->setUnits(($request->getParameter('units') && ($request->getParameter('units') != 'Items'))
                            ? $request->getParameter('units') : null);
      $variant->setCostCalculationMethod($request->getParameter('cost_calculation_method'));
      $variant->setUnitCost($request->getParameter('unit_cost') > 0 ? $request->getParameter('unit_cost') : null);
      $variant->setBrokerFees($request->getParameter('broker_fees') > 0 ? $request->getParameter('broker_fees') : null);
      $variant->setShippingFees($request->getParameter('shipping_fees') > 0 ? $request->getParameter('shipping_fees') : null);
      $variant->setUnitPrice($request->getParameter('unit_price') > 0 ? $request->getParameter('unit_price') : null);
      $variant->setMarkupAmount($request->getParameter('markup_amount') > 0 ? $request->getParameter('markup_amount') : null);
      $variant->setMarkupPercent($request->getParameter('markup_percent') > 0 ? $request->getParameter('markup_percent') : null);
      $variant->setEnviroLevy($request->getParameter('enviro_levy') > 0 ? $request->getParameter('enviro_levy') : null);
      $variant->setBatteryLevy($request->getParameter('battery_levy') > 0 ? $request->getParameter('battery_levy') : null);
      $variant->setTrackInventory(($request->getParameter('track_inventory') == '1'));
      $variant->setMinimumOnHand($request->getParameter('minimum_on_hand'));
      $variant->setMaximumOnHand($request->getParameter('maximum_on_hand') > 0 ? $request->getParameter('maximum_on_hand') : null);
      $variant->setStandardPackageQty($request->getParameter('standard_package_qty') > 0 ? $request->getParameter('standard_package_qty') : null);
      $variant->setLocation($request->getParameter('location') ? $request->getParameter('location') : null);
      $variant->setStockingNotes($request->getParameter('stocking_notes') ? $request->getParameter('stocking_notes') : null);
      $variant->save();
    
      $this->renderText('{success:true}');
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done executeEdit';
      sfContext::getInstance()->getLogger()->info($message);
    }
    return sfView::NONE;
  }//executeEdit()-------------------------------------------------------------

/*
   * updates the inventory level for this part
   */
  public function executeMinmax(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeMinmax------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $part = $this->loadPart($request);

    //validate
    $valid = true;
    $errors = array();
    if (!is_numeric($request->getParameter('min_quantity')))
    {
      $valid = false;
      $errors['min_quantity'] = 'Invalid number entered!';
    }    
    if (!is_numeric($request->getParameter('max_quantity')))
    {
      $valid = false;
      $errors['max_quantity'] = 'Invalid number entered!';
    }
    if ($request->getParameter('standard_package_qty') && !is_numeric($request->getParameter('standard_package_qty')))
    {
      $valid = false;
      $errors['standard_package_qty'] = 'Invalid number entered!';
    }    
    if (!is_numeric($request->getParameter('onhand')))
    {
      $valid = false;
      $errors['onhand'] = 'Invalid number entered!';
    } 
    
    if ($valid)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '++ Checkpoint VALID ++';
        sfContext::getInstance()->getLogger()->info($message);
      }
      
      $variant = $part->getDefaultVariant();
      $variant->setMinimumOnHand((int) $request->getParameter('min_quantity'));
      $variant->setMaximumOnHand((int) $request->getParameter('max_quantity'));
      if ($request->getParameter('standard_package_qty'))
      {
        $variant->setStandardPackageQty((int) $request->getParameter('standard_package_qty'));
      }
      else
      {
        $variant->setStandardPackageQty(null);
      }

      $newonhand = (float) $request->getParameter('onhand');
      $difference = $variant->getCurrentOnHand() - $newonhand;
      //subtracting, so we need to register a loss
      if ($difference == 0)
      {
        //do nothing
      }
      else if ($difference > 0)
      {
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'New On-Hand < Current On-Hand';
          sfContext::getInstance()->getLogger()->info($message);
        }
        
        $instance = new PartInstance();
        $instance->setPartVariant($variant);
        $instance->setQuantity($difference);
        $instance->setUnitPrice($variant->calculateUnitPrice());
        //$instance->setUnitCost($variant->calculateUnitPrice());
        $instance->setDateUsed(time());
        $instance->setTaxableHst(0); //no taxes on corrections
        $instance->setTaxablePst(0); //no taxes on corrections
        $instance->setTaxableGst(0); //no taxes on corrections
        //$instance->setTaxableHst(false); //no taxes on corrections
        //$instance->setTaxablePst(false); //no taxes on corrections
        //$instance->setTaxableGst(false); //no taxes on corrections
        $instance->setShippingFees($instance->getShippingFees());
        $instance->setBrokerFees($instance->getBrokerFees());
        $instance->setEnviroLevy($instance->getEnviroLevy());
        $instance->setBatteryLevy($instance->getBatteryLevy());
       
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'Updating part instance.....';
          sfContext::getInstance()->getLogger()->info($message);
        }

        $instance->setIsInventoryAdjustment(true);
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'done setIsInventoryAdjustment';
          sfContext::getInstance()->getLogger()->info($message);
        }
        
        $instance->save();
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'done save';
          sfContext::getInstance()->getLogger()->info($message);
        }

        $instance->allocate();
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'done allocate';
          sfContext::getInstance()->getLogger()->info($message);
        }

        $instance->deliver();
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'done deliver';
          sfContext::getInstance()->getLogger()->info($message);
        }

        $variant->setLastInventoryUpdate(time()); 
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'done setLastInventoryUpdate';
          $message = 'done updating part instance.....';
          sfContext::getInstance()->getLogger()->info($message);
        }       
      }
      //adding, so we need to add a part lot
      else
      {
        if (sfConfig::get('sf_logging_enabled'))
        {
          $message = 'New On-Hand > Current On-Hand';
          sfContext::getInstance()->getLogger()->info($message);
        }

        $lot = new PartLot();
        $lot->setPartVariant($variant);
        $lot->setQuantityReceived(-1 * $difference);
        $lot->setQuantityRemaining(-1 * $difference);
        $lot->setReceivedDate(time());
        $lot->setLandedCost($variant->calculateUnitCost());
        $lot->save();
        $variant->calculateCurrentOnHand();
        $variant->setLastInventoryUpdate(time());        
      }
      $variant->save();
      
      $this->renderText('{success:true}');
    }
    else
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '-- Checkpoint INVALID --';
        sfContext::getInstance()->getLogger()->info($message);
      }

      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeMinmax------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeMinmax()-----------------------------------------------------------


  /*
   * updates the inventory level for this part
   */
  public function executeInventory(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeInventory------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());


    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Start executeInventory';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $part = $this->loadPart($request);

    //validate
    $valid = true;
    $errors = array();
    if (!is_numeric($request->getParameter('current_on_hand')))
    {
      $valid = false;
      $errors['current_on_hand'] = 'Invalid number entered!';
    }

    if ($valid)
    {
      $newonhand = (float) $request->getParameter('current_on_hand');
      $variant = $part->getDefaultVariant();
      $difference = $variant->getCurrentOnHand() - $newonhand;
      //subtracting, so we need to register a loss
      if ($difference == 0)
      {
        //do nothing
      }
      else if ($difference > 0)
      {
        $instance = new PartInstance();
        $instance->setPartVariant($variant);
        $instance->setQuantity($difference);
        $instance->setUnitPrice($variant->calculateUnitPrice());
//        $instance->setUnitCost($variant->calculateUnitPrice());
        $instance->setDateUsed(time());
        $instance->setTaxableHst(0); //no taxes on corrections
        $instance->setTaxablePst(0); //no taxes on corrections
        $instance->setTaxableGst(0); //no taxes on corrections
//        $instance->setTaxableHst(false); //no taxes on corrections
//        $instance->setTaxablePst(false); //no taxes on corrections
//        $instance->setTaxableGst(false); //no taxes on corrections
        $instance->setShippingFees($instance->getShippingFees());
        $instance->setBrokerFees($instance->getBrokerFees());
        $instance->setEnviroLevy($instance->getEnviroLevy());
        $instance->setBatteryLevy($instance->getBatteryLevy());
        $instance->setIsInventoryAdjustment(true);
        $instance->save();
        $instance->allocate();
        $instance->deliver();
      }
      //adding, so we need to add a part lot
      else
      {
        $lot = new PartLot();
        $lot->setPartVariant($variant);
        $lot->setQuantityReceived(-1 * $difference);
        $lot->setQuantityRemaining(-1 * $difference);
        $lot->setReceivedDate(time());
        $lot->setLandedCost($variant->calculateUnitCost());
        $lot->save();
        $variant->calculateCurrentOnHand();
      }
      $variant->setLastInventoryUpdate(time());
      $variant->save();

      $this->renderText('{success:true}');
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done executeInventory';
      sfContext::getInstance()->getLogger()->info($message);
    }
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeInventory------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeInventory()--------------------------------------------------------

  /*
   * print out barcodes for this part
   * does not currently support variants.
   */
  public function executeBarcodes(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeBarcodes------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));

    $part = $this->loadPart($request);

    sfContext::getInstance()->getLogger()->info($part->getName());

    //validate
    $result = true;
    $errors = array();
    if (!($request->getParameter('quantity') > 0))
    {
      $result = false;
      $errors['quantity'] = 'You must specify a quantity!';
    }

    if ($result)
    {
      $variants = array($part->getDefaultVariant()->getId() => $request->getParameter('quantity'));
      $request->getParameterHolder()->set('variants', $variants);
      $this->forward('barcode', 'part');
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeBarcodes------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeBarcodes()---------------------------------------------------------

  /*
   * deletes a part, if possible
   */
  public function executeDelete(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeDelete------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $part = $this->loadPart($request);
    $this->forward404Unless($part->canDelete());

    $part->delete();
    $this->renderText('{success:true}');

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeDelete------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeDelete()-----------------------------------------------------------

/////////////////////////////////// MANAGE SUPPLIERS ////////////////////////////////////

  /*
   * adds or edits an existing supplier for a given part
   */
  public function executeSupplierEdit(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeSupplierEdit------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $part = $this->loadPart($request);
    $variant = $part->getDefaultVariant();
    $ps = PartSupplierPeer::retrieveByPk($request->getParameter('part_supplier_id'));

    //validate
    $result = true;
    $errors = array();

    //check for same supplier
    $c = new Criteria();
    $c->add(PartSupplierPeer::PART_VARIANT_ID, $variant->getId());
    $c->add(PartSupplierPeer::SUPPLIER_ID, $request->getParameter('supplier_id'));
    if ($ps)
    {
      $c->add(PartSupplierPeer::ID, $ps->getId(), Criteria::NOT_EQUAL);
    }
    if (wfCRMPeer::doSelectOne($c))
    {
      $result = false;
      $errors['supplier_id'] = 'This supplier already exists for this part!';
    }

    //create object
    if ($result)
    {
      if (!$ps)
      {
        $ps = new PartSupplier();
        $ps->setPartVariantId($variant->getId());
        $ps->setSupplierId($request->getParameter('supplier_id'));
      }
      $ps->setSupplierSku($request->getParameter('supplier_sku'));
      $ps->setNotes((substr($request->getParameter('notes'), 0, 11) == 'Enter price')
                      ? '' : $request->getParameter('notes'));
      $ps->save();

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeSupplierEdit------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeSupplierEdit()-----------------------------------------------------

  public function executeSupplierRemove(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeSupplierRemove------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $part = $this->loadPart($request);
    $this->forward404Unless($ps = PartSupplierPeer::retrieveByPk($request->getParameter('part_supplier_id')));
    $this->forward404Unless($ps->getPartVariant()->getPartId() == $part->getId());

    $ps->delete();

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeSupplierRemove------------------';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executeSupplierRemove()---------------------------------------------------


/////////////////////////////////// UTILITY FUNCTIONS ///////////////////////////////////

  public function executeFindByBarcode(sfWebRequest $request) {
    $code = $request->getParameter('code');

    $parts = PartPeer::searchByBarcode($code);
    $partsarray = array();
    foreach ($parts AS $part)
    {
      $partsarray[] = array('id'   => $part->getId(),
                            'part_variant_id' => $part->getDefaultVariant()->getId(),
                            'name' => $part->getName());
    }

    $this->renderText('{success:true,parts:'.json_encode($partsarray).'}');

    return sfView::NONE;
  }//executeFindByBarcode()----------------------------------------------------

  public function executeAddBarcode(sfWebRequest $request) {
    $code = $request->getParameter('code');
    $part_variant_id = $request->getParameter('part_variant_id');
    $supplier_id = $request->getParameter('supplier_id');
    $type = $request->getParameter('type');

    $c = new Criteria();
    
    switch ($type) {

      case 'manufacturer_sku':
        $c->add(PartVariantPeer::ID, $part_variant_id);
        $c->add(PartVariantPeer::MANUFACTURER_SKU, $code);
        PartVariantPeer::doUpdate($c);
       break;

       case 'internal_sku':
        $c->add(PartVariantPeer::ID, $part_variant_id);
        $c->add(PartVariantPeer::INTERNAL_SKU, $code);
        PartVariantPeer::doUpdate($c);
       break;

       case 'supplier_sku':
        $c->add(PartSupplierPeer::SUPPLIER_SKU, $code);

        $sp = new Criteria();
        $sp->add(PartSupplierPeer::SUPPLIER_ID, $supplier_id);
        $sp->add(PartSupplierPeer::PART_VARIANT_ID, $part_variant_id);
        $sp = PartSupplierPeer::doSelectOne($sp);
        if ( $sp ) {
          $c->add(PartSupplierPeer::ID, $sp->getId());
          PartSupplierPeer::doUpdate($c);
        } 
        break;
    }

    return sfView::NONE;
  }//executeAddBarcode()-------------------------------------------------------


  private function loadPart(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START loadPart====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    if ($request->getParameter('part_id')){
      $this->forward404Unless($part = PartPeer::retrieveByPkJoinMost($request->getParameter('part_id')));
    } else {
      $this->forward404Unless($part = PartPeer::retrieveByPkJoinMost($request->getParameter('id')));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE loadPart====================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return $part;
  }//loadPart()----------------------------------------------------------------
  
///////////////////////////// INVENTORY UPDATING /////////////////////////////////////

  /*
   * Update Inventory Levels using barcode scanner inventory mode
   */
  public function executeBulkInventory(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }//executeBulkInventory()----------------------------------------------------

  public function executeBulkInventoryReview(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    //decode and convert input into items, and output as form fields for confirmation.
    $data = $request->getParameter('inventory');
    $data = explode('[', $data);

    $errors = array();
    $sku_array = array();
    foreach ($data AS $line)
    {
      $line = explode(',', $line);
      if (count($line) == 2)
      {
        $qty = (int) $line[0];
        $sku = $line[1];

        //strip symbology prefix
        $sku = trim(preg_replace('/^\!\!(A|E0|FF|F|B1|B2|B3|\]e0)?/', '', $sku));

        //if a part is scanned twice, only the 2nd quantity is used
        $sku_array[$sku] = $qty;
      }
      else
      {
        $errors[] = $line;
      }      
    }

    //look up current part names and inventory levels by SKU
    $final_data = array();
    foreach ($sku_array AS $sku => $qty)
    {
      $parts = PartPeer::searchByBarcode($sku);
      if (count($parts) == 0)
      {
        $final_data[$sku] = false;
      }
      else if (count($parts) == 1)
      {
        $final_data[$sku] = array ('part_id'   => $parts[0]->getId(),
                                   'part_variant_id' => $parts[0]->getDefaultVariant()->getId(),
                                   'internal_sku' => $parts[0]->getDefaultVariant()->getInternalSku(),
                                   'current_on_hand' => $parts[0]->getDefaultVariant()->getQuantity('onhand'),
                                   'name' => $parts[0]->getName(),
                                   'qty' => $qty);
        unset($parts[0], $parts);
      }
      else
      {
        $final_data[$sku] = true;
        unset($parts);
      }
    }

    $this->final_data = $final_data;

    return sfView::SUCCESS;
  }//executeBulkInventoryReview()----------------------------------------------

  //update inventory levels for all requested items
  public function executeBulkInventoryPost(sfWebRequest $request)
  {

    $this->forward404Unless($request->isMethod('post'));

    $selections = $request->getParameter('enabled');
    $quantities = $request->getParameter('qty');

    $count_updated = 0;
    $count_unchanged = 0;
    $count_errored = 0;

    foreach ($selections AS $var_id => $selected)
    {
      if ($selected)
      {
        if (isset($quantities[$var_id]) && ($variant = PartVariantPeer::retrieveByPk($var_id)))
        {
          $numeric = is_numeric($quantities[$var_id]);
          $new_qty = (float) $quantities[$var_id];
          if (!$numeric || $new_qty === false || $new_qty < 0)
          {
            $count_errored ++;
          }
          else if ($variant->getCurrentOnHand() == $new_qty)
          {
            $count_unchanged ++;
          }
          else
          {
            $difference = $variant->getCurrentOnHand() - $new_qty;
            //subtracting, so we need to register a loss
            if ($difference > 0)
            {
              $instance = new PartInstance();
              $instance->setPartVariant($variant);
              $instance->setQuantity($difference);
              $instance->setUnitPrice($variant->calculateUnitPrice());
//              $instance->setUnitCost($variant->calculateUnitPrice());
              $instance->setDateUsed(time());
              $instance->setTaxableHst(0); //no taxes on corrections
              $instance->setTaxablePst(0); //no taxes on corrections
              $instance->setTaxableGst(0); //no taxes on corrections
//              $instance->setTaxableHst(false); //no taxes on corrections
//              $instance->setTaxablePst(false); //no taxes on corrections
//              $instance->setTaxableGst(false); //no taxes on corrections
              $instance->setShippingFees($instance->getShippingFees());
              $instance->setBrokerFees($instance->getBrokerFees());
              $instance->setEnviroLevy($instance->getEnviroLevy());
              $instance->setBatteryLevy($instance->getBatteryLevy());
              $instance->setIsInventoryAdjustment(true);
              $instance->save();
              $instance->allocate();
              $instance->deliver();
              unset($instance);
            }
            //adding, so we need to add a part lot
            else
            {
              $lot = new PartLot();
              $lot->setPartVariant($variant);
              $lot->setQuantityReceived(-1 * $difference);
              $lot->setQuantityRemaining(-1 * $difference);
              $lot->setReceivedDate(time());
              $lot->setLandedCost($variant->calculateUnitCost());
              $lot->save();
              $variant->calculateCurrentOnHand();
              unset($lot);
            }  

            $count_updated++;
          }
          $variant->setLastInventoryUpdate(time());
          $variant->save();
          unset($variant);
        }
        else
        {
          $count_errored ++;
        }
      }
    }

    $this->count_updated   = $count_updated;
    $this->count_unchanged = $count_unchanged;
    $this->count_errored   = $count_errored;

    return sfView::SUCCESS;
  }//executeBulkInventoryPost()------------------------------------------------

///////////////////////////// CATEGORIES MANAGEMENT /////////////////////////////////////

  /*
   *  View the list of categories for editing/adding
   */
  public function executeCategory(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   *  Loads the category info via JSON for editing
   */
  public function executeCategoryLoad(sfWebRequest $request)
  {
    $this->forward404Unless($cat = PartCategoryPeer::retrieveByPk($request->getParameter('category_id')));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $this->renderText(json_encode(array('success' => true, 
                                        'data' => array(
                                          'category_id' => $cat->getId(),
                                          'parent_id' => $cat->retrieveParent()->getId(),
                                          'category_name' => $cat->getName()
                                          ))));
    return sfView::NONE;
  }

  /*
   * add a new category
   */
  public function executeCategoryEdit(sfWebRequest $request)
  {
    $cat = PartCategoryPeer::retrieveByPk($request->getParameter('category_id'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    //check for same name with same parent
    $c = new Criteria();
    $c->add(PartCategoryPeer::NAME, $request->getParameter('category_name'));
    if ($cat)
    {
      $c->add(PartCategoryPeer::ID, $cat->getId(), Criteria::NOT_EQUAL);
    }
    if (PartCategoryPeer::doSelectOne($c))
    {
      $result = false;
      $errors['category_name'] = 'Category name already exists!';
    }
    //check to make sure valid parent is selected
    if (!($parent = PartCategoryPeer::retrieveByPk($request->getParameter('parent_id'))))
    {
      $result = false;
      $errors['parent_id'] = 'Invalid parent category selected';
    }
    //check to make sure not moving existing item to child of itself.
    if ($cat && $parent && ($parent->getLeftValue() >= $cat->getLeftValue()) && ($parent->getRightValue() <= $cat->getRightValue()))
    {
      $result = false;
      $errors['parent_id'] = 'Cannot move category to a "child" of itself!';
    }

    //create object
    if ($result)
    {
      if (!$cat)
      {
        $cat = new PartCategory();
        $cat->setName(trim($request->getParameter('category_name')));
        $cat->insertAsLastChildOf($parent);
        $cat->save();
      }
      else
      {
        $cat->setName(trim($request->getParameter('category_name')));
        $cat->moveToLastChildOf($parent);
        $cat->save();
      }

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;

  }

  public function executeCategoryDelete(sfWebRequest $request)
  {
    $this->forward404Unless($cat = PartCategoryPeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //delete (automatically moves subcategories and parts to parent)
    $cat->delete();

    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;

  }

}
