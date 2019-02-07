<?php

/**
 * supplier_order actions.
 *
 * @package    deltamarine
 * @subpackage supplier_order
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class supplier_orderActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'parts');
  }

  /*
   * display the datagrid of all supplier orders
   */
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * set up a new supplier order for a given supplier
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();
    if (!($supplier = SupplierPeer::retrieveByPk($request->getParameter('supplier_id'))))
    {
      $result = false;
      $errors['supplier_id'] = 'Invalid Supplier provided!';
    }
    if ($request->hasParameter('part_variant_id') && ($variant = PartVariantPeer::retrieveByPk($request->getParameter('part_variant_id'))))
    {
      if (!$variant->hasSupplier($supplier->getId()))
      {
        $result = false;
        $errors['supplier_id'] = 'This part is not available from the selected supplier!';
      }
      else
      {
        if (!is_numeric($request->getParameter('quantity')) || ((float) $request->getParameter('quantity') < 0))
        {
          $result = false;
          $errors['quantity'] = 'Invalid Quantity specified';
        }
      }
    }

    //create object
    if ($result)
    {
      $order = new SupplierOrder();
      $order->setSupplier($supplier);
      $order->save();

      //add an initial item to the newly-created order
      if (isset($variant) && $variant && ((float) $request->getParameter('quantity') > 0))
      {
        $orderitem = new SupplierOrderItem();
        $orderitem->setSupplierOrder($order);
        $orderitem->setPartVariant($variant);
        $orderitem->setQuantityRequested($request->getParameter('quantity'));
        $orderitem->save();
      }

      //output result as JSON
      $this->renderText("{success:true,newid:".$order->getId()."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * displays info about an order and UI for editing, etc
   */
  public function executeView(sfWebRequest $request)
  {
    $this->order = $this->loadSupplierOrder($request);

    return sfView::SUCCESS;
  }

  /*
   * attempts to delete the order if incomplete
   */
  public function executeDelete(sfWebRequest $request)
  {
    $order = $this->loadSupplierOrder($request);
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //check permissions
    if ($order->getApproved() && !$this->getUser()->hasCredential('orders_unfinalize'))
    {
      $this->forward404();
    }
    else if (!$this->getUser()->hasCredential('orders_edit'))
    {
      $this->forward404();
    }

    //check to see if paid at all
    if ($order->hasPartLots() || $order->hasSpecialOrders())
    {
      $this->forward404();
    }
    else
    {
      $order->delete();
      $this->renderText('{success:true}');
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
    $order = $this->loadSupplierOrder($request);

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
    else if (!$variant->getUnits() && (round((float) $quantity) != (float) $quantity))
    {
      $valid = false;
      $errors['quantity'] = 'Cannot enter decimal quantities for non-bulk parts';
    }

    //add item
    if ($valid)
    {
      //create the supplier order items and part instances 
      $quantity = (float) $quantity;
      if (!($orderitem = $order->findExistingItem($variant->getId())))
      {
        $orderitem = new SupplierOrderItem();
        $orderitem->setQuantityRequested(0);
      }
      $orderitem->setSupplierOrderId($order->getId());
      $orderitem->setPartVariantId($variant->getId());
      $orderitem->setQuantityRequested($orderitem->getQuantityRequested() + $quantity);
      $orderitem->save();

      if ($order->getReceivedAll())
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
   * edits an order lineitem
   */
  public function executeEdititem(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $order = $this->loadSupplierOrder($request);
    $this->forward404Unless($item = SupplierOrderItemPeer::retrieveByPk($request->getParameter('supplier_order_item')));
    $this->forward404Unless($item->getSupplierOrderId() == $order->getId());
    $this->forward404If($order->getFinalized());

    //validate
    $valid = true;
    $errors = array();

    //check for quantity
    $old_quantity = $item->getQuantityRequested();
    $new_quantity = (float) $request->getParameter('quantity');

    if (!($new_quantity > 0))
    {
      $valid = false;
      $errors['quantity'] = 'Invalid Quantity Selected.';
    }
    else if (!$item->getPartVariant()->getUnits() && (round((float) $new_quantity) != (float) $new_quantity))
    {
      $valid = false;
      $errors['quantity'] = 'Cannot enter decimal quantities for non-bulk parts';
    }
    else if ($new_quantity < $item->getQuantityCompleted())
    {
      $valid = false;
      $errors['quantity'] = 'Quantity must be greater than the number of received items';
      $errors['reason'] = 'You are trying to reduce the quantity of an item to below the'.
                          ' quantity of previously-received items. This is not possible,'.
                          ' as this would represent a negative initial purchase quantity.';
    }
    if ($item->getSupplierOrder()->getFinalized())
    {
      $valid = false;
      $errors['reason'] = 'Order has already been finalized and cannot be edited!';
    }

    //check stock levels
    if ($valid)
    {
      $difference = ($new_quantity - $old_quantity);
      $reserved = $item->getQuantityReserved();
      if ($reserved > $new_quantity)
      {
        $valid = false;
        $errors['reason'] = 'This order item is a special order item. There are one '.
                            ' or more parts orders which require '.round($reserved,3).
                            ' units of this part, therefore the quantity cannot be '.
                            ' reduced below this value. ';
      }
    }

    //save
    if ($valid)
    {
      //update values
      $item->setQuantityRequested($new_quantity);
      $item->save();
      $item->getPartVariant()->calculateCurrentOnOrder();

      if ($order->getReceivedAll())
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
   * attempts to delete an order in the line item
   */
  public function executeDeleteitem(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $order = $this->loadSupplierOrder($request);
    $this->forward404Unless($item = SupplierOrderItemPeer::retrieveByPk($request->getParameter('supplier_order_item')));
    $this->forward404Unless($item->getSupplierOrderId() == $order->getId());
    $this->forward404If($order->getFinalized());

    if ($item->hasPartLots() || $item->hasSpecialOrders())
    {
      $this->forward404();
    }
    else
    {
      $item->delete();


      if ($order->getReceivedSome())
      {
        $order->setReceivedSome($order->calculateReceivedSome());
        $order->setReceivedAll($order->calculateReceivedAll());
        $order->save();
      }

      $this->renderText('{success:true}');
    }

    return sfView::NONE;
  }
  

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));    
    $order = $this->loadSupplierOrder($request);

    $order->setPurchaseOrder($request->getParameter('purchase_order'));
    $order->setNotes(trim($request->getParameter('notes')));
    $order->setDateExpected($request->getParameter('date_expected') ? strtotime($request->getParameter('date_expected')) : null);

    $order->save();

    return $this->renderText('{success:true}');
  }

  public function executeChangestatus(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $order = $this->loadSupplierOrder($request);
    
    $success = true;

    switch ($request->getParameter('status')) 
    {
    case 'finalize': 
      if ($order->countSupplierOrderItems() == 0)
      {
        $success = false;
        $errors['reason'] = 'You must add items to the order before finalizing it!';
      }
      else
      {
        $order->setFinalized(true);
        if ($this->getUser()->hasCredential('orders_approve'))
        {
          //auto approve if they have the permission.
          $order->setApproved(true);
        }
      }
      break; 				
    case 'approve':
      if ($this->getUser()->hasCredential('orders_approve'))
      {
        $order->setApproved(true);
      }
      else
      {
        $success = false;
        $errors['reason'] = 'Permission denied to approve order.';
      }
      break;
    case 'unapprove':
      if ($this->getUser()->hasCredential('orders_unfinalize'))
      {
        $order->setApproved(false);
      }
      else
      {
        $success = false;
        $errors['reason'] = 'Permission denied to un-approve order.';
      }
      break;
    case 'send':
      if ($this->getUser()->hasCredential('orders_send'))
      {
        if (!$order->getDateOrdered())
        {
          $order->setDateOrdered(time());
        }
        $order->setPurchaseOrder($request->getParameter('purchase_order'));
        $order->setNotes(trim($request->getParameter('notes')));
        $order->setDateExpected($request->getParameter('date_expected') ? strtotime($request->getParameter('date_expected')) : null);
        $order->setSent(true);
      }
      else
      {
        $success = false;
        $errors['reason'] = 'Permission denied to send order.';
      }
      break;
    case 'unfinalize':
      if (!$order->getApproved() && $this->getUser()->hasCredential('orders_edit'))
      {
        $order->setFinalized(false);
      }
      else if (!$order->getSent() && $this->getUser()->hasCredential('orders_approve'))
      {
        $order->setApproved(false);
        $order->setFinalized(false);
      }
      else if ($this->getUser()->hasCredential('orders_unfinalize'))
      {
        $order->setSent(false);
        $order->setApproved(false);
        $order->setFinalized(false);
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
      $order->save(); 
      $this->renderText('{success:true}');
    }
    else
    {
      $this->renderText('{success:false,errors:'.json_encode($errors).'}');
    }
    
    return sfView::NONE;
  }

  /*
   * initiates a return of items
   */
  public function executeReceiveitems(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $order = $this->loadSupplierOrder($request);

    $receivedarray = $request->getParameter('received', array());
    $receivedcostarray = $request->getParameter('receivedcost', array());
    $c = new Criteria();
    $c->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $order->getId());
    $c->add(SupplierOrderItemPeer::ID, array_keys($receivedarray), Criteria::IN);
    if ($order_items = SupplierOrderItemPeer::doSelect($c))
    {
      $special_received = false;
      foreach ($order_items AS $order_item)
      {
        $receive_quantity = (isset($receivedarray[$order_item->getId()]) ? $receivedarray[$order_item->getId()] : 0);
        if ($receive_quantity > 0)
        {
          $lot = new PartLot();
          $lot->setPartVariantId($order_item->getPartVariantId());
          $lot->setQuantityReceived($receive_quantity);
          $lot->setQuantityRemaining($receive_quantity);
          $lot->setSupplierOrderItemId($order_item->getId());
          $lot->setReceivedDate(time());
          $lot->setLandedCost($receivedcostarray[$order_item->getId()]);
          $lot->save();

          $order_item->setQuantityCompleted($order_item->getQuantityCompleted() + $receive_quantity);
          $order_item->save();

          //if any part instances refer to this, remove the reference to the special order
          //NOTE: this will mess things up a bit if there is an incomplete receipt.
          if ($insts = $order_item->getPartInstances())
          {
            foreach ($insts AS $inst)
            {
              $special_received = true;
              $inst->setSupplierOrderItemId(null);
              $inst->setAllocated(true);
              $inst->save();
            }
          }

          $order_item->getPartVariant()->calculateCurrentOnOrder();
          $order_item->getPartVariant()->calculateCurrentOnHand();
          $order_item->getPartVariant()->calculateCurrentOnHold();
        }
      }

      //update order status
      $order->setReceivedSome($order->calculateReceivedSome());
      $order->setReceivedAll($order->calculateReceivedAll());
      if ($order->getReceivedAll() && !$order->getDateReceived())
      {
        $order->setDateReceived(time());
      }
      $order->save();

      $this->renderText('{success:true'.($special_received ? ',special:true':'').'}');
    }
    else
    {
      $this->forward404();
    }

    return sfView::NONE;
  }

  /*
   * Outputs an HTML invoice
   */
  public function executeInvoice(sfWebRequest $request)
  {
    $order = $this->loadSupplierOrder($request);

    $this->order = $order;
    $this->items = $order->getSupplierOrderItemsJoinPartInfo();

    sfConfig::set('sf_web_debug', false);

    return sfView::SUCCESS;   
  }

  //TODO
  public function executeChangeExpectedDate(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->add(SupplierOrderPeer::ID, $request->getParameter('id') );
    $c->add(SupplierOrderPeer::DATE_EXPECTED, $request->getParameter('date') );
    SupplierOrderPeer::doUpdate($c);
    return $this->renderText('');
  }


  private function loadSupplierOrder(sfWebRequest $request)
  {
    $this->forward404Unless($order = SupplierOrderPeer::retrieveByPk($request->getParameter('id')));

    return $order;
  }

}
