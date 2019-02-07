<?php

/**
 * customer sale actions.
 *
 * @package    deltamarine
 * @subpackage customer
 * @author     Dave Achtemichuk, Eugene Trinchuk
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class saleActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'sales');
  }

 /*
  * Displays the datagrid of customers
  */ 
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }


  /*
   * displays info about a sale and UI for editing, etc
   */
  public function executeView(sfWebRequest $request)
  {
    $this->sale = $this->loadSale($request);

    return sfView::SUCCESS;
  }

  /*
   * attempts to delete the order if incomplete
   */
  public function executeDelete(sfWebRequest $request)
  {
    $sale = $this->loadSale($request);
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //check permissions
    if ($sale->getApproved() && !$this->getUser()->hasCredential('orders_unfinalize'))
    {
      $this->forward404();
    }
    else if (!$this->getUser()->hasCredential('orders_edit'))
    {
      $this->forward404();
    }

    //check to see if paid at all
    if ($sale->hasPayments())
    {
      $this->forward404();
    }
    else
    {
      $sale->delete();
      $this->renderText('{success:true}');
    }

    return sfView::NONE;
  }

  /*
   * Creates a new Customer Order / Sale
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $valid = true;
    $errors = array();
    if (!($customer = CustomerPeer::retrieveByPk($request->getParameter('customer_id'))))
    {
      $valid = false;
      $errors['customer_id'] = 'Invalid Customer provided!';
    }
    if ($discount = ((float) $request->getParameter('discount_pct')))
    {
      if ($discount < 0  || $discount > 100)
      {
        $valid = false;
        $errors['discount_pct'] = 'Discount must be between 0% and 100%';
      }
    }
    else if ($discount != '0')
    {
      $valid = false;
      $errors['discount_pct'] = 'Invalid Discount specified!';
    }
    if ($request->hasParameter('part_variant_id') 
        && ($variant = PartVariantPeer::retrieveByPk($request->getParameter('part_variant_id'))))
    {
      if (!is_numeric($request->getParameter('quantity')) || ((float) $request->getParameter('quantity') < 0))
      {
        $valid = false;
        $errors['quantity'] = 'Invalid Quantity specified';
      }
    }

    //create object
    if ($valid)
    {
      $order = new CustomerOrder();
      $order->setCustomer($customer);
      $order->setDateOrdered(time());
      $order->setHstExempt(true);
      $order->setPstExempt($request->getParameter('pst_exempt') == '1');
      $order->setGstExempt($request->getParameter('gst_exempt') == '1');
      $order->setDiscountPct($discount);
      $order->setForRigging(($request->getParameter('for_rigging') == '1'));
      $order->setPoNum(trim($request->getParameter('po_num')));
      $order->setBoatName(trim($request->getParameter('boat_name')));
      $order->save();

      //add an initial item to the newly-created order
      if (isset($variant) && $variant && ((float) $request->getParameter('quantity') > 0))
      {
        if (($variant->getQuantity('available', false) < ((float) $request->getParameter('quantity'))) || $variant->getPart()->getHasSerialNumber())
        {
          $this->renderText('{success:true,newid:'.$order->getId().','.
                            'failed_add:'.$variant->getId().','.
                            'failed_qty:'.(float) $request->getParameter('quantity').'}');
        }
        else
        {
          $instance = new PartInstance();
          $instance->setPartVariant($variant);
          $instance->setQuantity($request->getParameter('quantity'));
          $instance->copyDefaults($order->getHstExempt(), $order->getPstExempt(), $order->getGstExempt()); //copies pricing from variant
          if ($order->getDiscountPct() > 0)
          {
            $instance->setUnitPrice($instance->getUnitPrice() * (100 - $order->getDiscountPct()) / 100);
          }
          $instance->save();
          $instance->allocate(); //puts on hold

          $orderitem = new CustomerOrderItem();
          $orderitem->setCustomerOrder($order);
          $orderitem->setPartInstance($instance);
          $orderitem->save();

          //output result as JSON
          $this->renderText("{success:true,newid:".$order->getId()."}");
        }
      }
      else
      {
        //output result as JSON
        $this->renderText("{success:true,newid:".$order->getId()."}");
      }
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * Edits a sale settings
   */
  public function executeEdit(sfWebRequest $request)
  {
    $sale = $this->loadSale($request);
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $valid = true;
    $errors = array();
    if ($discount = ((float) $request->getParameter('discount_pct')))
    {
      if ($discount < 0  || $discount > 100)
      {
        $valid = false;
        $errors['discount_pct'] = 'Discount must be between 0% and 100%';
      }
    }
    else if ($discount != '0')
    {
      $valid = false;
      $errors['discount_pct'] = 'Invalid Discount specified!';
    }

    //create object
    if ($valid)
    {
      $old_discount = $sale->getDiscountPct();
      $old_pst = $sale->getPstExempt();
      $old_gst = $sale->getGstExempt();
      $sale->setPstExempt($request->getParameter('pst_exempt') == '1');
      $sale->setGstExempt($request->getParameter('gst_exempt') == '1');
      $sale->setDiscountPct($discount);
      $sale->setForRigging(($request->getParameter('for_rigging') == '1'));
      $sale->setPoNum(trim($request->getParameter('po_num')));
      $sale->setBoatName(trim($request->getParameter('boat_name')));      
      $sale->save();
      if ($old_pst != $sale->getPstExempt() || $old_gst != $sale->getGstExempt())
      {
        $sale->updateTaxes();
      }
      if ($old_discount != $sale->getDiscountPct())
      {
        $sale->updateDiscount($old_discount);
      }

      $this->renderText("{success:true,newid:".$sale->getId()."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  public function executeExpenseedit(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasCredential('sales_edit'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);    

    if ($request->getParameter('customer_order_item') != 'new')
    {
      $this->forward404Unless($item = CustomerOrderItemPeer::retrieveByPk($request->getParameter('customer_order_item')));
      $this->forward404Unless($item->getCustomerOrderId() == $sale->getId());
      $this->forward404Unless($instance = $item->getPartInstance());
    }    

    //validate
    $valid = true;
    $errors = array();

    if (!(((float) $request->getParameter('unit_price')) >= 0))
    {
      $valid = false;
      $errors['price'] = 'Invalid Price specified. Price must not be negative!';
    }
    if ($request->getParameter('unit_cost') && ((float) $request->getParameter('unit_cost')) < 0)
    {
      $valid = false;
      $errors['cost'] = 'Invalid Cost specified. Cost must not be negative!';
    }

    if ($valid)
    {
      //create new records if needed
      if (!$item || !$instance) 
      {
        $item = new CustomerOrderItem();
        $item->setCustomerOrderId($sale->getId());
        $instance = new PartInstance();
        $instance->setQuantity(1);
        $instance->setPartVariantId(null);
      }

      $instance->setCustomName($request->getParameter('custom_name'));
      $instance->setTaxableHst($request->getParameter('taxable_hst') ? ($instance->getTaxableHst() != 0 ? $instance->getTaxableHst() : sfConfig::get('app_hst_rate')) : 0);
      $instance->setTaxablePst($request->getParameter('taxable_pst') ? ($instance->getTaxablePst() != 0 ? $instance->getTaxablePst() : sfConfig::get('app_pst_rate')) : 0);
      $instance->setTaxableGst($request->getParameter('taxable_gst') ? ($instance->getTaxableGst() != 0 ? $instance->getTaxableGst() : sfConfig::get('app_gst_rate')) : 0);
      $instance->setUnitPrice($request->getParameter('unit_price'));
      $instance->setUnitCost($request->getParameter('unit_cost'));
      $instance->save();

      $item->setPartInstance($instance);
      $item->save();

    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   * creates a new lineitem
   */
  public function executeAdditem(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);

    //validate
    $valid = true;
    $errors = array();

    //check for valid part
    $quantity = $request->getParameter('quantity');
    if (!($variant = PartVariantPeer::retrieveByPk($request->getParameter('part_variant_id'))))
    {
      $valid = false;
      $errors['reason'] = 'Invalid Part specified. Please close the add item window and try again.';
    }
    //check for valid quantity
    if (!is_numeric($quantity) || $quantity <= 0)
    {
      $valid = false;
      $errors['quantity'] = 'Invalid quantity specified. Try again';
    }
    else if ($variant->getPart()->getHasSerialNumber() && ((float) $quantity > 10))
    {
      $valid = false;
      $errors['quantity'] = 'Maximum quantity of 10 for items with serial numbers';
    }
    else if (!$variant->getUnits() && (round((float) $quantity) != (float) $quantity))
    {
      $valid = false;
      $errors['quantity'] = 'Cannot enter decimal quantities for non-bulk parts';
    }

    //check to see if part is in stock
    $quantity = (float) $quantity;
    $add_special_order = false;
    if ($variant->getQuantity('available', false) < $quantity)
    {
      $add_special_order = true;
      //check to make sure a supplier_id is set
      if (!$variant->hasSupplier($request->getParameter('supplier_id')))
      {
        $valid = false;
        $errors['supplier_id'] = 'Invalid Supplier Selected!';
        $errors['reason'] = 'Invalid supplier for special order was selected. Please fix and try again.';
      }
    }

    //add item
    if ($valid)
    {
      //load serial numbers
      $serialsarray = $request->getParameter('serials', array());

      $quantity_to_order = $quantity;
      $quantity_to_special_order = 0;

      if ($add_special_order)
      {
        $supplier = SupplierPeer::retrieveByPk($request->getParameter('supplier_id'));

        //if the user chose to split the order, then we add the existing inventory first:
        if ($request->getParameter('orderaction') == 'split')
        {
          $quantity_to_special_order = $quantity_to_order - (max($variant->getQuantity('available', false), 0));
          $quantity_to_order = max($variant->getQuantity('available', false), 0);
        }
        else
        {
          $quantity_to_special_order = $quantity_to_order;
          $quantity_to_order = 0;
        }

        //find or create the supplier order
        if (!($order = $supplier->getLatestOpenOrder()))
        {
          //not found existing non-finalized order, so open a new one
          $order = new SupplierOrder();
          $order->setSupplier($supplier);
          $order->save();
        } 
      
        //find or create the supplier order item
        if (!($item = $order->findExistingItem($variant->getId())))
        {
          //not found, so create a new order item
          $item = new SupplierOrderItem();
          $item->setSupplierOrder($order);
          $item->setPartVariantId($variant->getId());
        } 
        $item->setQuantityRequested($item->getQuantityRequested() + $quantity_to_special_order);
        $item->save(); 
      }

      //create the customer order items and part instances
      $serial_counter = 0;
      while ($quantity_to_order > 0 || $quantity_to_special_order > 0)
      {
        if ($quantity_to_order > 0)
        {
          $loop_specialorder = false;
          $add_quantity = $quantity_to_order;
        }
        else
        {
          $loop_specialorder = true;
          $add_quantity = $quantity_to_special_order;
        }

        //only add one at a time if serial number'd part
        if ($variant->getPart()->getHasSerialNumber())
        {
          $add_quantity = 1;
        }

        $instance = new PartInstance();
        $instance->setPartVariant($variant);
        $instance->setQuantity($add_quantity);
        $instance->copyDefaults($sale->getHstExempt(), $sale->getPstExempt(), $sale->getGstExempt()); //copies pricing from variant
        if ($sale->getDiscountPct() > 0)
        {
          $instance->setUnitPrice($instance->getUnitPrice() * (100 - $sale->getDiscountPct()) / 100);
        }


        //add special order reference
        if ($loop_specialorder && $item)
        {
          $instance->setSupplierOrderItemId($item->getId());
        }

        //add serial number
        if ($variant->getPart()->getHasSerialNumber() && isset($serialsarray[$serial_counter]))
        {
          $instance->setSerialNumber(trim($serialsarray[$serial_counter]));
        }

        //save and place on hold
        $instance->save();
        $instance->allocate();

        $orderitem = new CustomerOrderItem();
        $orderitem->setCustomerOrder($sale);
        $orderitem->setPartInstance($instance);
        $orderitem->save();

        $serial_counter ++;
        if ($loop_specialorder)
        {
          $quantity_to_special_order -= $add_quantity;
        }
        else
        {
          $quantity_to_order -= $add_quantity;
        }
      }

      if ($add_special_order)
      {
        $order->setReceivedAll($order->calculateReceivedAll());
        $order->save();
      }

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      if (!isset($errors['reason']))
      {
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  } 


  /*
   * edits a sale lineitem
   */
  public function executeEdititem(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);
    $this->forward404Unless($item = CustomerOrderItemPeer::retrieveByPk($request->getParameter('customer_order_item')));
    $this->forward404Unless($item->getCustomerOrderId() == $sale->getId());
    $this->forward404If($sale->getFinalized());
    $instance = $item->getPartInstance();

    //validate
    $valid = true;
    $errors = array();

    //check for quantity
    $old_quantity = $instance->getQuantity();
    $new_quantity = (float) $request->getParameter('quantity');

    if (!($new_quantity > 0))
    {
      $valid = false;
      $errors['quantity'] = 'Invalid Quantity Selected.';
    }
    else if ($new_quantity != 1 && $instance->getPartVariant()->getPart()->getHasSerialNumber())
    {
      $valid = false;
      $errors['quantity'] = 'Quantity must be 1 for items with a serial number';
      $errors['reason'] = 'You cannot increase the quantity of an existing item which '.
                          'uses serial numbers. To increase the quantity you will have '.
                          'to add a new item to the sale.';
    }
    else if (!$instance->getPartVariant()->getUnits() && (round((float) $new_quantity) != (float) $new_quantity))
    {
      $valid = false;
      $errors['quantity'] = 'Cannot enter decimal quantities for non-bulk parts';
    }
    else if ($new_quantity < $instance->getReturnedQuantity())
    {
      $valid = false;
      $errors['quantity'] = 'Quantity must be greater than the number of returned items';
      $errors['reason'] = 'You are trying to reduce the quantity of an item to below the'.
                          ' quantity of previously-returned items. This is not possible,'.
                          ' as this would represent a negative initial purchase quantity.';
    }
    if (!(((float) $request->getParameter('unit_price')) > 0))
    {
      $valid = false;
      $errors['reason'] = 'Invalid Price specified. Price must not be negative!';
    }
    if ($item->getCustomerOrder()->getFinalized())
    {
      $valid = false;
      $errors['reason'] = 'Order has already been finalized and cannot be edited!';
    }

    //check stock levels
    if ($valid)
    {
      $difference = ($new_quantity - $old_quantity);
      $left_in_stock = $instance->getPartVariant()->getQuantity('available', false);
      $special = $instance->GetSupplierOrderItem();
      if ($special && ($difference > 0) && $special->getSupplierOrder()->getFinalized())
      {
        //check to see if there are un-spoken-for items on that supplier order's item
        if ($special->getQuantityUnReserved() < $difference)
        {
          $valid = false;
          $errors['reason'] = 'This order item is a special order item. The Supplier'.
                              ' Order it is linked to has already been finalized, '.
                              ' and all the quantity of this part in that order is '.
                              ' reserved for existing sales or work orders. <br /><br />'.
                              ' Therefore it is not possible to increase the quantity '.
                              ' of this item! <br /><br />'.
                              ' Either un-finalize the related supplier order, or '.
                              ' Add a New Item to this sale for the same Part.';
        }
      }
      else if (!$special && ($difference > 0) && ($difference > $left_in_stock))
      {
        $valid = false;
        $errors['reason'] = 'There is not enough quantity in stock to cover the increase'.
                            ' of quantity for this item. There are only '.
                            $instance->getPartVariant()->getQuantity('available').
                            ' avilable. The edit window will be shown again with the '.
                            ' quantity adjusted to the maximum amount. <br /><br />If you'.
                            ' would still like to add more than that quantity to this'.
                            ' sale, you will have to add a separate item, which will '.
                            ' generate a special order (supplier order)';    
        $errors['maximum'] = ($old_quantity + $left_in_stock);
      }
    }

    //save
    if ($valid)
    {
      //its possible that the part instance was previously set as delivered. because of this,
      // we need to set it as undelivered so that when it is delivered in the future the proper
      // amount is taken out of the part lots. This also takes into account returned items,
      // which have already been put back into the part lot.
      if ($instance->getDelivered())
      {
        $item->setQuantityCompleted(0);
        $item->save();
        $instance->undeliver();
      }
  
      //update values
      $instance->setQuantity($new_quantity);
      $instance->setUnitPrice($request->getParameter('unit_price'));
      $instance->setEnviroLevy($request->getParameter('enviro_levy'));
      $instance->setBatteryLevy($request->getParameter('battery_levy'));
      $instance->setSerialNumber($request->getParameter('serial') ? $request->getParameter('serial') : null);

      $instance->save();

      //modify special/supplier order if quantity changed
      if (($difference != 0) && $special && !$special->getSupplierOrder()->getFinalized())
      {
        //modify the order quantity
        $new_orderquantity = $special->getQuantityRequested() + $difference;
        if ($new_orderquantity <= 0)
        {
          $order = $orderitem->getSupplierOrder();
          if ($order->getCountSupplierOrderItems() == 1)
          {
            $order->delete();
            $this->renderText("{success:true,specialdeleted:true}");
          }
          else
          {
            $orderitem->delete();
            $this->renderText("{success:true,specialmodified:true}");
          }
        }
        else
        {
          $orderitem->setQuantityRequested($orderitem->getQuantityRequested() + $difference);
          $orderitem->save();
          $this->renderText("{success:true,specialmodified:true}");
        }
      }
      else
      {
        //output result as JSON
        $this->renderText("{success:true}");
      }
    }
    else
    {
      if (!isset($errors['reason']))
      {
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  /*
   * attempts to delete an order in the line item
   */
  public function executeDeleteitem(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);
    $this->forward404Unless($item = CustomerOrderItemPeer::retrieveByPk($request->getParameter('customer_order_item')));
    $this->forward404Unless($item->getCustomerOrderId() == $sale->getId());
    $this->forward404If($sale->getFinalized());

    //this takes care of deleting part instance, special orders,
    // and placing any "delivered" items back into appropriate lots
    // and recalculating variant hold/order/onhand levels
    $item->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }
  

  public function executeChangestatus(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);
    
    $success = true;

    switch ($request->getParameter('status')) 
    {
    case 'finalize': 
      if ($sale->countCustomerOrderItems() == 0)
      {
        $success = false;
        $errors['reason'] = 'You must add one or more items to the sale before finalizing the order!';
      }
      else
      {
        $sale->setFinalized(true);
        if ($this->getUser()->hasCredential('sales_approve'))
        {
          $sale->setApproved(true);
          $sale->generateInvoice();
        }
      }
      break; 				
    case 'approve':
      if ($this->getUser()->hasCredential('sales_approve'))
      {
        //also regenerates invoice
        $sale->setApproved(true);
        $sale->generateInvoice();
      }
      else
      {
        $success = false;
        $errors['reason'] = 'Permission denied to approve sale';
      }
      break;
    case 'unapprove':
      if (($this->getUser()->hasCredential('sales_approve') && !$sale->getSentSome()) || $this->getUser()->hasCredential('sales_unfinalize'))
      {
        $sale->setApproved(false);
      }
      else
      {
        $succes = false;
        $errors['reason'] = 'Permission denied to unapprove sale that is partially shipped.';
      }
      break;
    case 'unfinalize':
      if (!$sale->getApproved() && $this->getUser()->hasCredential('sales_edit'))
      {
        $sale->setFinalized(false);
      }
      else if (!$sale->getSentSome() && $this->getUser()->hasCredential('sales_approve'))
      {
        $sale->setApproved(false);
        $sale->setFinalized(false);
      }
      else if ($this->getUser()->hasCredential('orders_unfinalize'))
      {
        $sale->setSentAll(false);
        $sale->setSentSome(false);
        $sale->setApproved(false);
        $sale->setFinalized(false);
      }
      else
      {
        $success = false;
        $errors['reason'] = 'Permission denied to un-finalize order.';
      }
      break;
    }
    
    if ($success)
    {
      $sale->save(); 
      $this->renderText('{success:true}');
    }
    else
    {
      $this->forward404();
    }
    
    return sfView::NONE;
  }

  /*
   * Sets specific items as being shipped
   */
  public function executeShipitems(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);

    $items = $request->getParameter('items');
    $items = explode(',', $items);
    $c = new Criteria();
    $c->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $sale->getId());
    $c->add(CustomerOrderItemPeer::ID, $items, Criteria::IN);
    if ($orderitems = CustomerOrderItemPeer::doSelect($c))
    {
      $shipment = new Shipment();
      $shipment->setCarrier('pickup');
      $shipment->setDateShipped(time());
      $shipment->save();

      //if nothing actually changed except for the quantity of the items,
      //then we'll keep the existing shipment and update the shipment date.
      $created_new = false;

      foreach ($orderitems AS $orderitem)
      {
        if (!$orderitem->getPartInstance()->getSupplierOrderItemId())
        {
          //try to find existing for this item
          $c = new Criteria();
          $c->add(ShipmentItemPeer::CUSTOMER_ORDER_ITEM_ID, $orderitem->getId());
          if ($existing = ShipmentItemPeer::doSelectOne($c))
          {
            if ($existing->getQuantity() != $orderitem->getPartInstance()->getQuantity())
            {
              $existing->setQuantity($orderitem->getPartInstance()->getQuantity());
              $existing->save();

              $oldship = $existing->getShipment();
              $oldship->setDateShipped(time());
              $oldship->save();
            } 
          }
          else
          {
            $created_new = true;
            $shipitem = new ShipmentItem();
            $shipitem->setShipmentId($shipment->getId());
            $shipitem->setCustomerOrderItemId($orderitem->getId());
            $shipitem->setQuantity($orderitem->getPartInstance()->getQuantity());
            $shipitem->save();
          }

          $orderitem->setQuantityCompleted($orderitem->getPartInstance()->getQuantity());   
          $orderitem->save();

          $orderitem->getPartInstance()->deliver();
        }
      }

      if (!$created_new)
      {
        // no new shipment items were created, just edited old ones, so can delete shipment
        $shipment->delete();
      }

      $sale->setSentSome($sale->calculateSentSome());
      $sale->setSentAll($sale->calculateSentAll());
      $sale->save();

      $this->renderText('{success:true}');
    }
    else
    {
      $this->forward404();
    }

    return sfView::NONE;
  }

  /*
   * initiates a return of items
   */
  public function executeReturnitems(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);

    $returnsarray = $request->getParameter('returns', array());
    $c = new Criteria();
    $c->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $sale->getId());
    $c->add(CustomerOrderItemPeer::ID, array_keys($returnsarray), Criteria::IN);
    if ($order_items = CustomerOrderItemPeer::doSelect($c))
    {
      //var to allow undo later if nothing was done
      $made_return = false;

      $return = new CustomerReturn();
      $return->setCustomerOrderId($sale->getId());
      $return->setDateReturned(time());
      $return->save();

      foreach ($order_items AS $order_item)
      {
        $old_instance = $order_item->getPartInstance();
        $return_quantity = (isset($returnsarray[$order_item->getId()]) ? $returnsarray[$order_item->getId()] : 0);
        if ($old_instance->getDelivered() && ($return_quantity > 0))
        {
          $made_return = true;
          //create a return part instance
          $instance = $old_instance->copy();
          $instance->setQuantity((-1 * $returnsarray[$order_item->getId()]));
          $instance->setSupplierOrderItemId(null);
          $instance->setWorkorderItemId(null);
          $instance->setWorkorderInvoiceId(null);
          $instance->setAllocated(true);
          $instance->setDelivered(true);
          $instance->setDateUsed(time());
          $instance->save();

          //create a customer return item
          $return_item = new CustomerReturnItem();
          $return_item->setCustomerReturnId($return->getId());
          $return_item->setCustomerOrderItemId($order_item->getId());
          $return_item->setPartInstanceId($instance->getId());
          $return_item->save();

          //place returned inventory back into stock
          $return_item->putBackInInventory();

        }
      }

      if ($made_return)
      {
        //generate return invoice
        $return->generateInvoice();
        $this->renderText('{success:true}');
      }
      else
      {
        $return->delete();
        $this->forward404();
      }
    }
    else
    {
      $this->forward404();
    }

    return sfView::NONE;
  }

  public function executeDeletereturn(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);
    $this->forward404Unless($return = CustomerReturnPeer::retrieveByPk($request->getParameter('return_id')));
    $this->forward404If($return->getCustomerOrderId() != $sale->getId());

    //deletes the return items, which takes them back out of stock.
    // also deletes the invoice
    $return->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }

  public function executeDeletepayment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);
    $this->forward404Unless($payment = PaymentPeer::retrieveByPk($request->getParameter('payment_id')));
    $this->forward404Unless($payment->getCustomerOrderId() == $sale->getId());

    //deletes the payment.
    $payment->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }

  public function executeAddpayment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);

    //validate
    $valid = true;
    $errors = array();

    //check for valid quantity
    $amount = ((float) $request->getParameter('amount'));
    if ($amount == 0)
    {
      $valid = false;
      $errors['amount'] = 'Amount cannot be zero. Enter a positive number for payment or negative for refund';
    }
    if (!$request->getParameter('payment_method'))
    {
      $valid = false;
      $errors['payment_method'] = 'Invalid Payment Method selected';
    }
    else if ($request->getParameter('payment_method') == 'Cash')
    {
      $tendered = ((float) $request->getParameter('tendered'));
      if ($tendered == 0)
      {
        $valid = false;
        $errors['tendered'] = 'You must enter an amount tendered for a cash transaction.';
      }
      else if ((($amount > 0) && ($tendered < $amount)) || (($amount < 0) && ($tendered > $amount)))
      {
        $valid = false;
        $errors['tendered'] = 'The amount tendered value was less than the amount payable!';
      }
    }

    //add payment if valid
    if ($valid)
    {
      $payment = new Payment();
      $payment->setCustomerOrderId($sale->getId());
      $payment->setAmount($amount);
      $payment->setPaymentMethod($request->getParameter('payment_method'));
      if ($request->getParameter('payment_details'))
      {
        $payment->setPaymentDetails($request->getParameter('payment_details'));
      }
      if ($request->getParameter('tendered'))
      {
        $payment->setTendered($request->getParameter('tendered'));
        $payment->setChange($payment->getTendered() - $payment->getAmount());
      }
      $payment->save();

      $this->renderText('{success:true}');
    }
    else
    {
      if (!isset($errors['reason']))
      {
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  public function executeQuickcheckout(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $sale = $this->loadSale($request);

    //validate
    $valid = true;
    $errors = array();

    //check for valid quantity
    $amount = ((float) $request->getParameter('amount'));
    if ($amount <= 0)
    {
      $valid = false;
      $errors['amount'] = 'Amount must be positive!';
    }
    if (!$request->getParameter('checkout_method'))
    {
      $valid = false;
      $errors['checkout_method'] = 'Invalid Payment Method selected';
    }
    else if ($request->getParameter('checkout_method') == 'Cash')
    {
      $tendered = ((float) $request->getParameter('tendered'));
      if ($tendered == 0)
      {
        $valid = false;
        $errors['tendered'] = 'You must enter an amount tendered for a cash transaction.';
      }
      else if ((($amount > 0) && ($tendered < $amount)) || (($amount < 0) && ($tendered > $amount)))
      {
        $valid = false;
        $errors['tendered'] = 'The amount tendered value was less than the amount payable!';
      }
    }

    //do checkout
    if ($valid)
    {
      //change status
      $sale->setFinalized(true);
      $sale->setApproved(true);

      //create invoice, assign items
      $sale->generateInvoice();

      //create payment
      $payment = new Payment();
      $payment->setCustomerOrderId($sale->getId());
      $payment->setAmount($amount);
      $payment->setPaymentMethod($request->getParameter('checkout_method'));
      if ($request->getParameter('checkout_details'))
      {
        $payment->setPaymentDetails($request->getParameter('checkout_details'));
      }
      if ($request->getParameter('tendered'))
      {
        $payment->setTendered($request->getParameter('tendered'));
        $payment->setChange($payment->getTendered() - $payment->getAmount());
      }
      $payment->save();

      //create shipment
      $shipment = new Shipment();
      $shipment->setCarrier('pickup');
      $shipment->setDateShipped(time());
      $shipment->save();

      //if nothing actually changed except for the quantity of the items,
      //then we'll keep the existing shipment and update the shipment date.
      $created_new = false;

      $orderitems = $sale->getCustomerOrderItemsJoinPartInfo();
      foreach ($orderitems AS $orderitem)
      {
        if (!$orderitem->getPartInstance()->getSupplierOrderItemId())
        {
          //try to find existing for this item
          $c = new Criteria();
          $c->add(ShipmentItemPeer::CUSTOMER_ORDER_ITEM_ID, $orderitem->getId());
          if ($existing = ShipmentItemPeer::doSelectOne($c))
          {
            if ($existing->getQuantity() != $orderitem->getPartInstance()->getQuantity())
            {
              $existing->setQuantity($orderitem->getPartInstance()->getQuantity());
              $existing->save();

              $oldship = $existing->getShipment();
              $oldship->setDateShipped(time());
              $oldship->save();
            } 
          }
          else
          {
            $created_new = true;
            $shipitem = new ShipmentItem();
            $shipitem->setShipmentId($shipment->getId());
            $shipitem->setCustomerOrderItemId($orderitem->getId());
            $shipitem->setQuantity($orderitem->getPartInstance()->getQuantity());
            $shipitem->save();
          }

          $orderitem->setQuantityCompleted($orderitem->getPartInstance()->getQuantity());   
          $orderitem->save();

          $orderitem->getPartInstance()->deliver();
        }
      }

      if (!$created_new)
      {
        // no new shipment items were created, just edited old ones, so can delete shipment
        $shipment->delete();
      }

      //calculate shipment status
      $sale->setSentSome($sale->calculateSentSome());
      $sale->setSentAll($sale->calculateSentAll());
      $sale->save();

      $this->renderText('{success:true}');
    }
    else
    {
      if (!isset($errors['reason']))
      {
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * Outputs an HTML invoice
   */
  public function executeInvoice(sfWebRequest $request)
  {
    $sale = $this->loadSale($request);

    $items = $sale->getCustomerOrderItemsJoinPartInfo();
    $payments = $sale->getPayments();

    //returns
    $c = new Criteria();
    $c->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $sale->getId());
    $returns = CustomerReturnItemPeer::doSelectJoinCustomerReturn($c);
    $retsarray = array();
    foreach ($returns AS $return)
    {
      if (!isset($retsarray[$return->getCustomerOrderItemId()]))
      {
        $retsarray[$return->getCustomerOrderItemId()] = array();
      }
      $retsarray[$return->getCustomerOrderItemId()][] = $return;
    }
    $returns = $retsarray;

    $pdf = new SalePDF($sale, $items, $payments, $returns);
    $pdf->generate();
    $pdf->Output('sale_'.$sale->getId().'_'.date('Y-M-d').'.pdf');

    return sfView::NONE;   
  }


  protected function loadSale(sfWebRequest $request)
  {
    $this->forward404Unless(($sale = CustomerOrderPeer::retrieveByPk($request->getParameter('id'))),
                             sprintf('Object sale does not exist (%s).', $request->getParameter('id')));
    return $sale;
  }
  
}
