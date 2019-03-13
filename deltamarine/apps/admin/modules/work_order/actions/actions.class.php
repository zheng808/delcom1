<?php

/**
 * work_order actions.
 *
 * @package    deltamarine
 * @subpackage work_order
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class work_orderActions extends sfActions
{
  public function preExecute()
  {
    if ($this->getActionName() != 'haulouts' && $this->getActionName() != 'haulins')
    sfConfig::set('app_selected_menu', 'workorders');
  }

  /*
   * display the list of all work orders in a datagrid
   */  
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  public function executeHaulouts(sfWebRequest $request)
  {
    $start_of_today = mktime(0,0,0);
    $c = new Criteria();
    $c->add(WorkorderPeer::HAULOUT_DATE, null, Criteria::ISNOTNULL);
    $c->add(WorkorderPeer::HAULOUT_DATE, $start_of_today, Criteria::GREATER_EQUAL);
    $c2 = clone $c;

    ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn(WorkorderPeer::HAULOUT_DATE)
                                                    :  $c->addDescendingOrderByColumn(WorkorderPeer::HAULOUT_DATE));

    //paging
    if ($request->getParameter('limit')) $c->setLimit($request->getParameter('limit'));
    if ($request->getParameter('start')) $c->setOffset($request->getParameter('start'));

    $workorders = WorkorderPeer::doSelectForListing($c);
    $count_all = WorkorderPeer::doCount($c2);

    $workorderarray = array();
    foreach ($workorders AS $workorder)
    {
      $workorderarray[] = array(
        'id'       => $workorder->getId(), 
        'boat'     => $workorder->getCustomerBoat()->getName(),
        'boattype' => $workorder->getCustomerBoat()->getMakeModel(),
        'haulout'  => $workorder->getHauloutDateTime('m/d/Y')
       );
    }
    $dataarray = array('totalCount' => $count_all, 'workorders' => $workorderarray);
    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

  public function executeHaulins(sfWebRequest $request)
  {
    $start_of_today = mktime(0,0,0);
    $c = new Criteria();
    $c->add(WorkorderPeer::HAULIN_DATE, null, Criteria::ISNOTNULL);
    $c->add(WorkorderPeer::HAULIN_DATE, $start_of_today, Criteria::GREATER_EQUAL);
    $c2 = clone $c;

    ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn(WorkorderPeer::HAULIN_DATE)
                                                    :  $c->addDescendingOrderByColumn(WorkorderPeer::HAULIN_DATE));

    //paging
    if ($request->getParameter('limit')) $c->setLimit($request->getParameter('limit'));
    if ($request->getParameter('start')) $c->setOffset($request->getParameter('start'));

    
    $workorders = WorkorderPeer::doSelectForListing($c);
    $count_all = WorkorderPeer::doCount($c2);

    $workorderarray = array();
    foreach ($workorders AS $workorder)
    {
      $workorderarray[] = array(
        'id'       => $workorder->getId(), 
        'boat'     => $workorder->getCustomerBoat()->getName(),
        'boattype' => $workorder->getCustomerBoat()->getMakeModel(),
        'haulin'  => $workorder->getHaulinDateTime('m/d/Y')
       );
    }
    $dataarray = array('totalCount' => $count_all, 'workorders' => $workorderarray);
    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }//executeHaulins()----------------------------------------------------------


  /*
   * create a new work order for a given customer and boat.
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();
    if (!($customer = CustomerPeer::retrieveByPk($request->getParameter('customer_id'))))
    {
      $result = false;
      $errors['customer_id'] = 'Invalid Customer provided!';
    }
    if (!($boat = CustomerBoatPeer::retrieveByPk($request->getParameter('customer_boat_id'))))
    {
      $result = false;
      $errors['customer_boat_id'] = 'Invalid Boat provided!';
    }
    if ($customer && $boat && ($boat->getCustomerId() != $customer->getId()))
    {
      $result = false;
      $errors['customer_boat_id'] = 'Boat does not belong to selected customer!';
    }
    if ($request->getParameter('status') != 'In Progress' && $request->getParameter('status') != 'Estimate')
    {
      $result = false;
      $errors['status'] = 'Invalid status selected';
    }

    //create object
    if ($result)
    {
      $order = new Workorder();
      $order->setCustomer($customer);
      $order->setCustomerBoat($boat);
      $order->setCreatedOn(time());
      $order->setStatus($request->getParameter('status'));
      $order->setHstExempt(true); //since april 1 2013
      $order->setSummaryColor($request->getParameter('color_code','FFFFFF'));
      $order->setPstExempt(!((bool) $request->getParameter('taxable_pst', false)));
      $order->setGstExempt(!((bool) $request->getParameter('taxable_gst', false)));
      $order->setShopSuppliesSurcharge($request->getParameter('shop_supplies_surcharge', 0));
      $order->setMoorageSurchargeAmt($request->getParameter('moorage_surcharge_amt', 0));
      $order->setForRigging($request->getParameter('for_rigging') == '1');
      $order->save();

      //output result as JSON
      $this->renderText("{success:true,newid:".$order->getId()."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }//executeAdd()--------------------------------------------------------------

  public function executeView(sfWebRequest $request)
  {
    $workorder = $this->loadWorkorder($request);
    $this->workorder = $workorder;

    return sfView::SUCCESS;
  }//executeView()-------------------------------------------------------------

  public function executeDelete(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));
    $workorder = $this->loadWorkorder($request);

    //checks to see if there are any expenses, or delivered parts, or timelogs for this workorder
    $result = $workorder->delete();
    if ($result)
    {
      $this->renderText("{success:true}");
      return sfView::NONE;
    }
    else
    {
      $this->forward404();
    }
  }//executeDelete()-----------------------------------------------------------

  public function executeEdit(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeEdit======================';
      sfContext::getInstance()->getLogger()->info($message);
    }    
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));
    $workorder = $this->loadWorkorder($request);

    //validate
    $result = true;
    $errors = array();

    if (!($customer = CustomerPeer::retrieveByPk($request->getParameter('customer_id'))))
    {
      $result = false;
      $errors['customer_id'] = 'Invalid Customer provided!';
    }
    if (!($boat = CustomerBoatPeer::retrieveByPk($request->getParameter('customer_boat_id'))))
    {
      $result = false;
      $errors['customer_boat_id'] = 'Invalid Boat provided!';
    }
    if ($customer && $boat && ($boat->getCustomerId() != $customer->getId()))
    {
      $result = false;
      $errors['customer_boat_id'] = 'Boat does not belong to selected customer!';
    }

    if ($result)
    {
      $old_pst = $workorder->getPstExempt();
      $old_gst = $workorder->getGstExempt();
      $oldstatus = $workorder->getStatus();

      $workorder->setCustomer($customer);
      $workorder->setCustomerBoat($boat);
      $workorder->setCreatedOn($request->getParameter('created_on') ? strtotime($request->getParameter('created_on')) : null);
      $workorder->setStartedOn($request->getParameter('started_on') ? strtotime($request->getParameter('started_on')) : null);
      $workorder->setCompletedOn($request->getParameter('completed_on') ? strtotime($request->getParameter('completed_on')) : null);
      $workorder->setHauloutDate($request->getParameter('haulout') ? strtotime($request->getParameter('haulout').' '.$request->getParameter('haulout_time')): null);
      $workorder->setHaulinDate($request->getParameter('haulin') ? strtotime($request->getParameter('haulin').' '.$request->getParameter('haulin_time')): null);
      $workorder->setStatus($request->getParameter('status'));
      $workorder->setSummaryColor($request->getParameter('color_code','FFFFFF'));

      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Setting Canada Entry Num';
        sfContext::getInstance()->getLogger()->info($message);
      }  
      $workorder->setCanadaEntryNum($request->getParameter('canada_entry_num') ? $request->getParameter('canada_entry_num') : null);
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Setting Canada Entry Date';
        sfContext::getInstance()->getLogger()->info($message);
      }  
      $workorder->setCanadaEntryDate($request->getParameter('canada_entry_date') ? strtotime($request->getParameter('canada_entry_date')) : null);
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Finished Setting Canada Entry Num and Date';
        sfContext::getInstance()->getLogger()->info($message);
      }  


      if ($cat = WorkorderCategoryPeer::retrieveByPk($request->getParameter('workorder_category_id')))
      {
        $workorder->setWorkorderCategoryId($cat->getId());
      }
      else
      {
        $workorder->setWorkorderCategoryId(null);
      }

      //make these conditional since not everyone is shows these fields because of billing permissions
      if ($request->hasParameter('pst_exempt')) $workorder->setPstExempt($request->getParameter('pst_exempt', 0));
      if ($request->hasParameter('gst_exempt')) $workorder->setGstExempt($request->getParameter('gst_exempt', 0));
      if ($request->hasParameter('shop_supplies_surcharge')) $workorder->setShopSuppliesSurcharge($request->getParameter('shop_supplies_surcharge', 0));
      if ($request->hasParameter('moorage_surcharge_amt')) $workorder->setMoorageSurchargeAmt($request->getParameter('moorage_surcharge_amt', 0));

      $workorder->setForRigging(($request->getParameter('for_rigging') == '1'));
      $workorder->save();
      $newstatus = $workorder->getStatus();

      //update tax exempted status
      if ($workorder->getPstExempt() != $old_pst) $workorder->removeAllPst($workorder->getPstExempt());
      if ($workorder->getGstExempt() != $old_gst) $workorder->removeAllGst($workorder->getGstExempt());

      $invalid_suppliers = false;
      //put items on hold, or remove from hold if moving from one status to another
      if ($oldstatus != $newstatus)
      {
        if ($newstatus == 'In Progress' && ($request->getParameter('holdaction') == 'hold' || $request->getParameter('orderaction') != 'leave'))
        {
          //loop through all parts in this workorder, and place items on hold or make special orders as needed
          $parts = $workorder->getAllParts();
          foreach ($parts AS $part)
          {
            //only worry about putting stuff on hold or special ordering if it isn't already on hold
            if ($part->getQuantity() <= $part->getPartVariant()->getCurrentAvailable() && !$part->getAllocated())
            {
              //place on hold if requested
              if ($request->getParameter('holdaction') == 'hold')
              {
                $part->allocate();
              }
            }
            //only create a special order for items if set to leave and there's not already a special order
            else if ($request->getParameter('orderaction') != 'leave' && !$part->getSupplierOrderItemId())
            {
              //create special order if specified!
              if ($request->getParameter('orderaction') == 'split')
              {
                $available = max($part->getPartVariant()->getCurrentAvailable(), 0);
                if ($available == 0)
                {
                  $special_order_part = $part;
                }
                else
                {
                  $remaining = $part->getQuantity() - $available;
                  $part->setQuantity($available);
                  $part->save();
                  $part2 = $part->copy();
                  $part2->setQuantity($remaining);
                  $part2->save(); 
                  $special_order_part = $part2;
  
                  //set available amount as on hold if specified.
                  if (!$part->getAllocated() && $request->getParameter('holdaction') == 'hold')
                  {
                    $part->allocate();
                  }
                }
              }
              else if ($request->getParameter('orderaction') == 'all')
              {
                $special_order_part = $part;
              }

              //create the special order for the full amount of the given part
              if ($special_order_part)
              {
                //check to make sure only one supplier, otherwise output a warning when done
                $suppliers = $special_order_part->getPartVariant()->getPartSuppliers();
                if (count($suppliers) != 1)
                {
                  $invalid_suppliers = true;
                }
                else
                {
                  $supplier = $suppliers[0];
                  $supplier = $supplier->getSupplier();
                  //find or create the supplier order
                  if (!($order = $supplier->getLatestOpenOrder()))
                  {
                    //not found existing non-finalized order, so open a new one
                    $order = new SupplierOrder();
                    $order->setSupplier($supplier);
                    $order->save();
                  }
                  //find or create the supplier order item
                  if (!($orderitem = $order->findExistingItem($special_order_part->getPartVariantId())))
                  {
                    //not found, so create a new order item
                    $orderitem = new SupplierOrderItem();
                    $orderitem->setSupplierOrder($order);
                    $orderitem->setPartVariantId($special_order_part->getPartVariantId());
                  }
                  $orderitem->setQuantityRequested($orderitem->getQuantityRequested() + $special_order_part->getQuantity());
                  $orderitem->save();
                  $special_order_part->setSupplierOrderItem($orderitem);
                  $special_order_part->save();

                  //also allocate, so that the system knows that the on-order parts are spoken for as well.
                  if (!$special_order_part->getAllocated() && $request->getParameter('holdaction') == 'hold')
                  {
                    $special_order_part->allocate();
                  }
                }
              }
            }
          }
        }

        //remove special orders or holds as needed
        if ($oldstatus == 'In Progress'  && ($request->getParameter('holdaction') == 'unhold' || $request->getParameter('orderaction') == 'remove'))
        {
          //loop through all parts in this workorder, and remove items from hold, and removing any special orders if possible/requested
          $parts = $workorder->getAllParts();
          foreach ($parts AS $part)
          {
            if (!$part->getDelivered() && $part->getAllocated() && $request->getParameter('holdaction') == 'unhold')
            {
              $part->unallocate();
            }
            if (!$part->getDelivered() && $part->getSupplierOrderItemId() && $request->getParameter('orderaction') == 'remove')
            {
              $orderitem = $part->getSupplierOrderItem();
              $part->setSupplierOrderItemId(null);
              $part->save();
              $part->unallocate();

              //attempt to remove order item
              if ($orderitem && $orderitem->getSupplierOrder() && !$orderitem->getSupplierOrder()->getFinalized())
              {
                $order = $orderitem->getSupplierOrder();
                if ($orderitem->getQuantityRequested() > $part->getQuantity())
                {
                  //reduce order amount automatically
                  $orderitem->setQuantityRequested($orderitem->getQuantityRequested() - $part->getQuantity());
                  $orderitem->save();
                  $order->setReceivedSome($order->calculateReceivedSome());
                  $order->setReceivedAll($order->calculateReceivedAll());
                  $order->save();
                }
                else
                {
                  $orderitem->delete();
                  if ($order->countSupplierOrderItems() == 0)
                  {
                    $order->clearSupplierOrderItems();
                    $order->delete();
                  }
                  else
                  {
                    $order->setReceivedSome($order->calculateReceivedSome());
                    $order->setReceivedAll($order->calculateReceivedAll());
                    $order->save();
                  }
                }
              }
            }
          }
        }
      }

      //output result as JSON
      $this->renderText("{success:true,suppliererror:".($invalid_suppliers ? 'true' : 'false')."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done executeEdit======================';
      sfContext::getInstance()->getLogger()->info($message);
    } 
    return sfView::NONE;
  }//executeEdit()-------------------------------------------------------------

  /**********************************/
  /*      ITEM METHODS              */
  /**********************************/

  public function executeItemcopy(sfWebRequest $request)
  {
    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($request->hasParameter('item_id') && $request->getParameter('item_id') != 'new');
    $this->forward404Unless($this->getUser()->hasCredential('workorder_edit'));
    $this->forward404Unless($item = WorkorderItemPeer::retrieveByPk($request->getParameter('item_id')));
    $this->forward404Unless($item->getWorkorderId() == $workorder->getId());
    $this->forward404Unless($newwo = WorkorderPeer::retrieveByPk($request->getParameter('workorder_id')));

    if ($newwo->getId() == $workorder->getId())
    {
      $errors['workorder_id'] = 'You can\'t copy a task to the same workorder.';
      return $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    $item->duplicate(
      $newwo, null, 
      $request->getParameter('p'), $request->getParameter('pest'),
      $request->getParameter('e'), $request->getParameter('eest'),
      $request->getParameter('l'), $request->getParameter('lest')
    );

    return $this->renderText("{success:true}");
  }

  public function executeItemmove(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($item = WorkorderItemPeer::retrieveByPk($request->getParameter('item_id')));
    $this->forward404Unless($target = WorkorderItemPeer::retrieveByPk($request->getParameter('target')));
    $old_parent = $item->retrieveParent();

    if ($request->getParameter('point') == 'after')
    {
      $item->moveToNextSiblingOf($target);
    }
    else if ($request->getParameter('point') == 'before')
    {
      $item->moveToPrevSiblingOf($target);
    }
    else if ($request->getParameter('point') == 'append')
    {
      $item->moveToLastChildOf($target);
      $item->save();
    }

    //need to clear out all the cached totals
    $old_parent->calculateActualLabour();
    $old_parent->calculateActualPart();
    $old_parent->calculateActualOther();
    $target->calculateActualLabour();
    $target->calculateActualPart();
    $target->calculateActualOther();

    $this->renderText("{success:true}");

    return sfView::NONE;
  }

  public function executeItemload(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    //$this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($item = WorkorderItemPeer::retrieveByPk($request->getParameter('item_id')));

    $splitarray = array();
    if ($splitbill = ($item->getWorkorderItemBillables()))
    {
      $splitbill = ($splitbill ? $splitbill[0] : null);
      $splitarray = array(
                  'supplier_name'    => ($splitbill->getSupplierId() ? $splitbill->getSupplier()->getName() : null),
                  'supplier_id'      => $splitbill->getSupplierId(),
                  'supplier_parts_percent' => $splitbill->getSupplierPartsPercent(),
                  'supplier_labour_percent' => $splitbill->getSupplierLabourPercent(),
                  'manufacturer_name' => ($splitbill->getManufacturerId() ? $splitbill->getManufacturer()->getName() : null),
                  'manufacturer_id'   => $splitbill->getManufacturerId(),
                  'manufacturer_parts_percent' => $splitbill->getManufacturerPartsPercent(),
                  'manufacturer_labour_percent' => $splitbill->getManufacturerLabourPercent(),
                  'in_house_parts_percent' => $splitbill->getInHousePartsPercent(),
                  'in_house_labour_percent' => $splitbill->getInHouseLabourPercent(),
                  'customer_parts_percent' => $splitbill->getCustomerPartsPercent(),
                  'customer_labour_percent' => $splitbill->getCustomerLabourPercent(),
                  'recurse'          => $splitbill->getRecurse() ? 1 : 0
        );
    }
    $parent = $item->retrieveParent();
    $completed_by = ($item->getCompleted() ? ($item->getEmployee() ? $item->getEmployee()->generateName() : 'Unknown') : '');
    $data = array('item_id'         => $item->getId(),
                  'label'           => $item->getLabel(),
                  'parent_id'       => $parent->getId(),
                  'parent_name'     => $parent->isRoot() ? '-- Top Level Item --' : $parent->getLabel(),
                  'labour_estimate' => $item->getLabourEstimate(),
                  'part_estimate'   => $item->getPartEstimate(),
                  'other_estimate'  => $item->getOtherEstimate(),
                  'amount_paid'     => ($item->getAmountPaid() > 0 ? $item->getAmountPaid() : null),
                  'color_code'      => $item->getColorCode(),
                  'completed'       => $item->getCompleted() ? 1 : 0,
                  'completed_by'    => $completed_by,
                  'completed_date'  => $item->getCompletedDate('M j, Y'),
                  'supplier_parts_percent'      => 0, 
                  'supplier_labour_percent'     => 0, 
                  'manufacturer_parts_percent'  => 0, 
                  'manufacturer_labour_percent' => 0, 
                  'in_house_parts_percent'      => 0, 
                  'in_house_labour_percent'     => 0, 
                  'customer_parts_percent'      => 100,
                  'customer_labour_percent'     => 100,
                  'customer_notes'  => $item->getCustomerNotes()

                );

    $data = array_merge($data, $splitarray);

    $this->renderText("{success:true, data:".json_encode($data)."}");

    return sfView::NONE;
  }

  public function executeItemedit(sfWebRequest $request)
  {
    $workorder = $this->loadWorkorder($request);

    if ($request->hasParameter('item_id') && $request->getParameter('item_id') != 'new')
    {
      $this->forward404Unless($this->getUser()->hasCredential('workorder_edit'));
      $this->forward404Unless($item = WorkorderItemPeer::retrieveByPk($request->getParameter('item_id')));
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    //check for same label
    $c = new Criteria();
    $c->add(WorkorderItemPeer::LABEL, $request->getParameter('label'));
    $c->add(WorkorderItemPeer::WORKORDER_ID, $workorder->getId());
    if (isset($item))
    {
      $c->add(WorkorderItemPeer::ID, $item->getId(), Criteria::NOT_EQUAL);
    }
    if (WorkorderItemPeer::doSelectOne($c))
    {
      $result = false;
      $errors['label'] = 'Task Label already exists in this workorder. Please pick something more descriptive to avoid confusion.';
    }

    //check for valid parent
    if (!($parent = WorkorderItemPeer::retrieveByPk($request->getParameter('parent_id'))))
    {
      $result = false;
      $errors['parent_id'] = 'Invalid parent task selected! Please pick a valid one from the list or choose the top-level task.';
    }

    //check for parent from other workorder
    if ($parent && $parent->getWorkorderId() != $workorder->getId())
    {
      $result = false;
      $errors['parent_id'] = 'Invalid parent task selected! Please pick a valid one from the list or choose the top-level task';
    }

    //check for moving to child of self
    if (isset($item) && $parent && ($parent->getLeftValue() >= $item->getLeftValue()) && ($parent->getRightValue() <= $item->getRightValue()))
    {
      $result = false;
      $errors['parent_id'] = 'You have tried to move this item to be a sub-task of itself or one of its sub-tasks!';
    }

    //check for estimate amounts
    if ($request->getParameter('labour_estimate') && (!is_numeric($request->getParameter('labour_estimate'))))
    {
      $result = false;
      $errors['labour_estimate'] = 'Invalid estimate amount entered! Use only positive numbers.';
    }
    if ($request->getParameter('part_estimate') && (!is_numeric($request->getParameter('part_estimate'))))
    {
      $result = false;
      $errors['part_estimate'] = 'Invalid estimate amount entered! Use only positive numbers.';
    }
    if ($request->getParameter('other_estimate') && (!is_numeric($request->getParameter('other_estimate'))))
    {
      $result = false;
      $errors['other_estimate'] = 'Invalid estimate amount entered! Use only positive numbers.';
    }
    if ($request->getParameter('amount_paid') && (!is_numeric($request->getParameter('amount_paid'))))
    {
      $result = false;
      $errors['amount_paid'] = 'Invalid amount paid entered! Use only positive numbers.';
    }

    //check billing adds up to 100
    if ($request->getParameter('manufacturer_parts_percent',0) + $request->getParameter('supplier_parts_percent', 0) + $request->getParameter('in_house_parts_percent',0) > 100)
    {
      $result = false;
      $errors['customer_parts_percent'] = 'The combined percentages must add up to 100%!';
    }
    //check billing adds up to 100
    if ($request->getParameter('manufacturer_labour_percent',0) + $request->getParameter('supplier_labour_percent', 0) + $request->getParameter('in_house_labour_percent',0) > 100) 
    {
      $result = false;
      $errors['customer_labour_percent'] = 'The combined percentages must add up to 100%!';
    }

    //check for manufacturer if specified
    if (($request->getParameter('manufacturer_parts_percent',0) > 0 || $request->getParameter('manufacturer_labour_percent',0)) && (!(ManufacturerPeer::retrieveByPk($request->getParameter('manufacturer_id')))))
    {
      $result = false;
      $errors['manufacturer_id'] = 'You must specify a valid Manufacturer if you specify to charge a manufacturer a certain percentage for this item.';
    }

    //check for supplier if specified
    if (($request->getParameter('supplier_parts_percent',0) > 0 || $request->getParameter('supplier_labour_percent',0)) && (!(SupplierPeer::retrieveByPk($request->getParameter('supplier_id')))))
    {
      $result = false;
      $errors['supplier_id'] = 'You must specify a valid supplier if you specify to charge a supplier a certain percentage for this item.';
    }


    //create object
    if ($result)
    {
      if (!isset($item))
      {
        $item = new WorkorderItem();
        $item->insertAsLastChildOf($parent);
      }
      else
      {
        //check to see if this node needs to be moved
        $old_parent = $item->retrieveParent();
        if ($old_parent->getId() != $parent->getId())
        {
          $item->moveToLastChildOf($parent);
        }
      }
      $item->setLabel($request->getParameter('label'));
      $item->setLabourEstimate($request->getParameter('labour_estimate') > 0 ? $request->getParameter('labour_estimate') : null);
      $item->setPartEstimate($request->getParameter('part_estimate') > 0 ? $request->getParameter('part_estimate') : null);
      $item->setOtherEstimate($request->getParameter('other_estimate') > 0 ? $request->getParameter('other_estimate') : null);
      $item->setCustomerNotes(trim($request->getParameter('customer_notes')));
      $item->setAmountPaid($request->getParameter('amount_paid') > 0 ? $request->getParameter('amount_paid') : 0);
      $item->setColorCode($request->getParameter('color_code','FFFFFF'));
      if (!$item->getCompleted() && $request->getParameter('completed') == '1')
      {
        $item->setCompleted(true);
        $item->setCompletedDate(time());
        $user = $this->getContext()->getUser();
        if ($user->isAuthenticated() && $user->getEmployee())
        {
          $item->setEmployee($user->getEmployee());
        }
        else
        {
          $item->setEmployee(null);
        }
      }
      else if ($item->getCompleted() && !$request->getParameter('completed'))
      {
        $item->setCompleted(false);
        $item->setCompletedBy(null);
        $item->setCompletedDate(null);
      }
      $item->save();

      //load/save/create billable item
      $billable = $item->getWorkorderItemBillables();
      $billable = ($billable ? $billable[0] : new WorkorderItemBillable());
      if ($request->getParameter('customer_parts_percent') != 100 || $request->getParameter('customer_labour_percent') != 100)
      {
        $billable->setManufacturerPartsPercent(0);
        $billable->setManufacturerLabourPercent(0);
        $billable->setManufacturerId(null);
        $billable->setSupplierPartsPercent(0);
        $billable->setSupplierLabourPercent(0);
        $billable->setSupplierId(null);
        $billable->setInHousePartsPercent($request->getParameter('in_house_parts_percent', 0));
        $billable->setInHouseLabourPercent($request->getParameter('in_house_labour_percent', 0));
        $billable->setWorkorderItemId($item->getId());
        $billable->setRecurse((bool) $request->getParameter('recurse') == '1');
        if ($request->getParameter('manufacturer_parts_percent') > 0 || $request->getParameter('manufacturer_labour_percent') > 0)
        {
          $billable->setManufacturerPartsPercent($request->getParameter('manufacturer_parts_percent',0));
          $billable->setManufacturerLabourPercent($request->getParameter('manufacturer_labour_percent',0));
          $billable->setManufacturerId($request->getParameter('manufacturer_id'));
        }
        if ($request->getParameter('supplier_parts_percent') > 0 || $request->getParameter('supplier_labour_percent') > 0)
        {
          $billable->setSupplierPartsPercent($request->getParameter('supplier_parts_percent',0));
          $billable->setSupplierLabourPercent($request->getParameter('supplier_labour_percent',0));
          $billable->setSupplierId($request->getParameter('supplier_id'));
        }
        $customer_parts = 100 - $billable->getManufacturerPartsPercent() - $billable->getSupplierPartsPercent() - $billable->getInHousePartsPercent();
        $customer_labour = 100 - $billable->getManufacturerLabourPercent() - $billable->getSupplierLabourPercent() - $billable->getInHouseLabourPercent();
        $billable->setCustomerPartsPercent($customer_parts);
        $billable->setCustomerLabourPercent($customer_labour);
        $billable->save();
      }
      else
      {
        //delete if necessary
        $billable->delete();
      }

      //output result as JSON
      $this->renderText("{success:true,newid:".$item->getId().",newlabel:".json_encode($item->getLabel())."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

  public function executeItemdelete(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($item = WorkorderItemPeer::retrieveByPk($request->getParameter('item_id')));
    $this->forward404Unless($item->getWorkorderId() == $workorder->getId());

    //check to see if empty first
    $empty = true;
    $c = new Criteria();
    $c->add(PartInstancePeer::DELIVERED, true);
    if ($item->countPartInstances($c) > 0) $empty = false;
    if ($item->countTimelogs() > 0) $empty = false;
    if ($item->countWorkorderExpenses() > 0) $empty = false;

    if ($empty)
    {
      $item->delete();
      $this->renderText("{success:true}");
    }
    else
    {
      $reason = 'Task was not empty-- could not delete. <br />Delete all parts/expenses/timelogs first if you wish to remove this task.';
      $this->renderText(json_encode(array('success' => false, 'errors' => array('reason' => $reason))));
    }

    return sfView::NONE;
  }

  /**********************************/
  /*      PARTS STUFF               */
  /**********************************/

  /*
   * adds a part to a workorder
   */
  public function executePartadd(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartadd======================';
      sfContext::getInstance()->getLogger()->info($message);
    }
    
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);

/*

    //THIS STUFF STILL HAS TO BE INTEGRATED INTO THE PARTEDIT METHOD!!!!!!!!
    //TODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODOTODO

    //check to see if part is in stock if not an estimate
    $statusaction = $request->getParameter('statusaction');
    $specialaction = $request->getParameter('orderaction');
    $quantity = (float) $quantity;
    $add_special_order = false;
    if ($variant->getQuantity('available', false) < $quantity)
    {
      //make sure they didn't specify later somehow for a non-estimate
      if ($specialaction == 'later' && $workorder->getStatus() != 'Estimate')
      {
        $valid = false;
        $errors['quantity'] = 'Special order was set to "later", but this workorder is not an Estimate!';
      }
      else if ($specialaction != 'later')
      {
        $add_special_order = true;
      }

      //check to make sure a supplier_id is set
      if ($add_special_order && !$variant->hasSupplier($request->getParameter('supplier_id')))
      {
        $valid = false;
        $errors['supplier_id'] = 'Invalid Supplier Selected!';
        $errors['reason'] = 'Invalid supplier for special order was selected. Note that this error can also be caused if you try to create an part estimate for an In Progress work order, but there is not enough quantity in stock to cover the estimate, and no suppliers set up for the part. Edit the part to add a supplier and try again.';
      }

      //check to make sure a special order item is created if status is on hold or delivered
      if ($statusaction == 'delivered')
      {
        $valid = false;
        $errors['statusaction'] = 'Cannot specify an item as delivered if a special order is required!! Use On Hold instead.';
      }
      else if ($statusaction != 'estimate' && $workorder->getStatus() == 'Estimate')
      {
        $valid = false;
        $errors['statusaction'] = 'Cannot put a part on hold for an Estimate. Convert to "In Progress" to trigger items to be put on hold.';
      }

    }

        
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

        //add special order reference
        if ($loop_specialorder && $item)
        {
          $instance->setSupplierOrderItemId($item->getId());
        }

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

      */
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'DONE executePartadd======================';
        sfContext::getInstance()->getLogger()->info($message);
      }

    return sfView::NONE;
  } 

  public function executePartdelete(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartdelete======================';
      sfContext::getInstance()->getLogger()->info($message);
    }
    
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);

    $this->forward404Unless($instance = PartInstancePeer::retrieveByPk($request->getParameter('instance_id')));
    $this->forward404Unless($instance->getWorkorderItem()->getWorkorderId() == $workorder->getId());
    $this->forward404If($workorder->getStatus() == 'Completed' || $workorder->getStatus() == 'Cancelled');

    //this takes care of deleting part instance, special orders,
    // and placing any "delivered" items back into appropriate lots
    // and recalculating variant hold/order/onhand levels
    $instance->delete();

    $this->renderText('{success:true}');

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartdelete======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executePartdelete()-------------------------------------------------------

  public function executeAttachExemption(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executeAttachExemption======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    //LOAD AND CHECK THE WORKORDER
    $workorder = $this->loadWorkorder($request);
    $this->forward404If($workorder->getStatus() == 'Completed' || $workorder->getStatus() == 'Cancelled');
    
    $filename = $request->getParameter('file_name');

    $workorder->setExemptionFile($filename);
    
    $workorder->save();

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executeAttachExemption======================';
      sfContext::getInstance()->getLogger()->info($message);
    }
    return sfView::NONE;

  }//executeAttachExemption()--------------------------------------------------

  public function executePartedit(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartedit======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $is_new = false;

    //LOAD AND CHECK THE WORKORDER
    $workorder = $this->loadWorkorder($request);
    $this->forward404If($workorder->getStatus() == 'Completed' || $workorder->getStatus() == 'Cancelled');

    //LOAD OR CREATE THE INSTANCE AS NEEDED
    if ($request->getParameter('instance_id') === 'new'){
      $is_new = true;
      $instance = new PartInstance();
      $instance->setPartVariantId($request->getParameter('part_variant_id'));
      $old_quantity = 0;
    }
    else
    {
      $this->forward404Unless($instance = PartInstancePeer::retrieveByPk($request->getParameter('instance_id')));
      $this->forward404Unless($instance->getWorkorderItem()->getWorkorderId() == $workorder->getId());
      $old_quantity = $instance->getQuantity();
    }
  
    //validate
    $valid = true;
    $errors = array();
    $delete = false;

    //check for quantity
    $new_quantity = (float) $request->getParameter('quantity');

    if (!is_numeric($new_quantity) || ($new_quantity < 0))
    {
      $valid = false;
      $errors['quantity'] = 'Invalid Quantity Selected.';
    }
    else if ($new_quantity == 0)
    {
        //DELETES PART!
        $instance->delete();
        $this->renderText('{success:true,deleted:true}');
        return sfView::NONE;
    }
    else if ($new_quantity != 1 && $instance->getPartVariant()->getPart()->getHasSerialNumber())
    {
      $valid = false;
      $errors['quantity'] = 'Quantity must be 1 for items with a serial number';
      $errors['reason'] = 'You cannot increase the quantity of an existing item which '.
                          'uses serial numbers. To increase the quantity you will have '.
                          'to add a new item to the sale.';
    }
    if (!(((float) $request->getParameter('unit_price')) > 0))
    {
      $valid = false;
      $errors['reason'] = 'Invalid Price specified. Price must not be negative!';
    }

    //check for valid parent
    if (!($parent_item = WorkorderItemPeer::retrieveByPk($request->getParameter('parent_id'))))
    {
      $valid = false;
      $errors['parent_id'] = 'Invalid Parent task was specified!';
    }
    else if ($parent_item->isRoot())
    {
      $valid = false;
      $errors['parent_id'] = 'Cannot select root item for a part; each part must be assigned to a specific task.';
    }

    //check for valid part
    if ($is_new && !($variant = PartVariantPeer::retrieveByPk($request->getParameter('part_variant_id'))))
    {
      $valid = false;
      $errors['reason'] = 'Invalid Part specified. Please close the add part window and try again.';
    }    

    //check stock levels
    if ($valid)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '++ Checkpoint 1 ++ VALID';
        sfContext::getInstance()->getLogger()->info($message);
      }

      $new_status = $request->getParameter('statusaction');
      $difference = ($new_quantity - $old_quantity);
      $left_in_stock = $instance->getPartVariant()->getQuantity('available', false);
      $special = $instance->GetSupplierOrderItem();
      if ($is_new && $difference > $left_in_stock && $new_status != 'estimate')
      {
        $valid = false;
        $errors['reason'] = 'There is not enough quantity in stock for this item. There are only '.
                            $instance->getPartVariant()->getQuantity('available').
                            ' available. The edit window will be shown again with the '.
                            ' quantity adjusted to the maximum amount. <br /><br />If you'.
                            ' would still like to add more than that quantity to this'.
                            ' work order, you will have to add a separate item, which will '.
                            ' generate a special order (supplier order)';
        $errors['maximum'] = ($old_quantity + $left_in_stock);
      }
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
                              ' Add a New Item to this work order for the same Part.';
        }
      }
      else if ($left_in_stock == 0 && !$special && $new_status == 'estimate')
      {
        //carry on.... no worries there.
      }
      else if (!$is_new && !$special && ($difference > 0) && ($difference > $left_in_stock))
      {
        $valid = false;
        $errors['reason'] = 'There is not enough quantity in stock to cover the increase'.
                            ' of quantity for this item. There are only '.
                            $instance->getPartVariant()->getQuantity('available').
                            ' more available. The edit window will be shown again with the '.
                            ' quantity adjusted to the maximum amount. <br /><br />If you'.
                            ' would still like to add more than that quantity to this'.
                            ' work order, you will have to add a separate item, which will '.
                            ' generate a special order (supplier order)';
        $errors['maximum'] = ($old_quantity + $left_in_stock);
      }
      else if (!$instance->getAllocated() && $new_status != 'estimate' && $instance->getQuantity() > $instance->getPartVariant()->getCurrentAvailable())
      {
        $valid = false;
        $errors['reason'] = 'There is not enough quantity in stock to put the requested'.
                            ' quantity on hold. There are only '.
                            $instance->getPartVariant()->getQuantity('available').
                            ' more available. The edit window will be shown again with the '.
                            ' quantity adjusted to the maximum amount. <br /><br />If you'.
                            ' would still like to place more than that quantity to this'.
                            ' work order, you will have to add a separate part for the '.
                            ' remaining quantity, which will allow you to generate a '.
                            ' special order (supplier order)';
        $errors['maximum'] = $left_in_stock;
      }
    } else {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '-- Checkpoint 1 -- NOT VALID';
        sfContext::getInstance()->getLogger()->info($message);
      }
    }

    //save
    if ($valid)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '++ Checkpoint 2 ++ VALID';
        sfContext::getInstance()->getLogger()->info($message);
      }

      //its possible that the part instance was previously set as delivered. because of this,
      // we need to set it as undelivered so that when it is delivered in the future the proper
      // amount is taken out of the part lots. This also takes into account returned items,
      // which have already been put back into the part lot.
      if ($redeliver = $instance->getDelivered())
      {
        $instance->undeliver();
      }

      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'setting values';
        sfContext::getInstance()->getLogger()->info($message);
        sfContext::getInstance()->getLogger()->info('unit Cost: '.$request->getParameter('unit_cost'));
        sfContext::getInstance()->getLogger()->info('unit Price: '.$request->getParameter('unit_price'));
        sfContext::getInstance()->getLogger()->info('enviro_levy: '.$request->getParameter('enviro_levy'));
        sfContext::getInstance()->getLogger()->info('battery_levy: '.$request->getParameter('battery_levy'));
        sfContext::getInstance()->getLogger()->info('broker_fees: '.$request->getParameter('broker_fees'));
        sfContext::getInstance()->getLogger()->info('shipping_fees: '.$request->getParameter('shipping_fees'));
      }

      //update values
      $old_parent = $instance->getWorkorderItem();
      $instance->setQuantity($new_quantity);
      $instance->setUnitPrice($request->getParameter('unit_price'));
      $instance->setEnviroLevy($request->getParameter('enviro_levy'));
      $instance->setBatteryLevy($request->getParameter('battery_levy'));
      $instance->setUnitCost($request->getParameter('unit_cost') ? $request->getParameter('unit_cost') : null);
      $instance->setSerialNumber($request->getParameter('serial') ? $request->getParameter('serial') : null);
      $instance->setWorkorderItemId($parent_item->getId());
      $instance->setEstimate($request->getParameter('estimate'));
      $instance->setBrokerFees($request->getParameter('broker_fees'));
      $instance->setShippingFees($request->getParameter('shipping_fees'));
      
      //this keeps existing tax rate in the instance if set
      $instance->setTaxableHst($request->getParameter('taxable_hst') ? ($instance->getTaxableHst() != 0 ? $instance->getTaxableHst() : sfConfig::get('app_hst_rate')) : 0);
      $instance->setTaxablePst($request->getParameter('taxable_pst') ? ($instance->getTaxablePst() != 0 ? $instance->getTaxablePst() : sfConfig::get('app_pst_rate')) : 0);
      $instance->setTaxableGst($request->getParameter('taxable_gst') ? ($instance->getTaxableGst() != 0 ? $instance->getTaxableGst() : sfConfig::get('app_gst_rate')) : 0);

      //update status
      if (!$instance->getAllocated() && $new_status == 'hold')
      {
        $instance->allocate();
      }
      else if ($instance->getAllocated() && $new_status == 'estimate')
      {
        $instance->unallocate();
      }
      else if (!$instance->getDelivered() && $new_status == 'delivered')
      {
        $instance->deliver();
      }
      else if ($redeliver && $new_status == 'estimate')
      {
        $instance->unallocate();
      }
      else if ($redeliver && $new_status == 'delivered')
      {
        $instance->deliver();
      }

      //add employee if new
      $user = $this->getContext()->getUser();
      if ($is_new && $user->isAuthenticated() && $user->getEmployee())
      {
        $instance->setEmployee($user->getEmployee());
      }

      $instance->save();      

      //save and update old parents' part amount total
      if (!$is_new && $old_parent->getId() != $instance->getWorkorderItemId())
      {
        $old_parent->calculateActualPart();
      }

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
        $instance->getPartVariant()->calculateCurrentOnOrder();
      }
      else
      {
        //update quantity on hold and on hand if needed
        if ($difference != 0)
        {
          $instance->getPartVariant()->calculateCurrentOnHand();
          $instance->getPartVariant()->calculateCurrentOnHold();
        }

        //output result as JSON
        $this->renderText("{success:true}");
      }
    }
    else
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = '-- Checkpoint 2 -- NOT VALID';
        sfContext::getInstance()->getLogger()->info($message);
      }

      if (!isset($errors['reason']))
      {
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartedit======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executePartedit()---------------------------------------------------------

  /*
   * Moves a part via drag-and-drop in the workorder screen
   */
  public function executePartmove(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartmove======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));
          
    //load up instance
    $this->forward404Unless($instance = PartInstancePeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($target = WorkorderItemPeer::retrieveByPk($request->getParameter('target')));
    $this->forward404Unless($instance->getWorkorderItemId());

    $old_item = $instance->getWorkorderItem();    
    $instance->setWorkorderItem($target);
    $instance->save();

    //recalculate cost of old branch. New branch is automatically recalculated on save.
    $old_item->calculateActualPart();

    $this->renderText("{success:true}");

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartmove======================';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executePartmove()---------------------------------------------------------

  public function executePartload(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartload======================';
      sfContext::getInstance()->getLogger()->info($message);
    }
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'isMethod POST';
      sfContext::getInstance()->getLogger()->info($message);
    }
    $workorder = $this->loadWorkorder($request);

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Retrieved workorder: ';
      sfContext::getInstance()->getLogger()->info($message.$workorder->getId());
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Retrieving instance by request param: ';
      sfContext::getInstance()->getLogger()->info($message.$request->getParameter('instance_id'));
    }
    
    $this->forward404Unless($inst = PartInstancePeer::retrieveByPk($request->getParameter('instance_id')));
  
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Retrieved instance by PK: ';
      sfContext::getInstance()->getLogger()->info($message.$inst->getId());
    }

    $var = $inst->getPartVariant();
    $part = $var->getPart();
    $order_id = ($inst->getSupplierOrderItemId() ? $inst->getSupplierOrderItem()->getSupplierOrderId() : null);
    $status = ($inst->getDelivered() ? 'delivered' : ($inst->getAllocated() ? 'hold' : 'estimate'));
    $who = ($inst->getEmployee() ? $inst->getEmployee()->generateName() : '<em>Unknown</em>').' on '.($inst->getDateUsed() ? $inst->getDateUsed('M j, Y') : 'N/A');
    $data = array('instance_id'         => $inst->getId(),
                  'part_variant_id'     => $inst->getPartVariantId(),
                  'part_id'             => $part->getId(),
                  'parent_id'           => $inst->getWorkorderItemId(),
                  'parent_name'         => $inst->getWorkorderItem()->getLabel(),
                  'name'                => $part->getName(),
                  'sku'                 => $var->getInternalSku(),
                  'units'               => (string) $var->getUnits(),
                  'quantity'            => $inst->outputQuantity(false),
                  'unit_cost'           => number_format($inst->getUnitCost(), 2, '.', ''),
                  'unit_price'          => number_format($inst->getUnitPrice(), 2, '.', ''),
                  'broker_fees'         => number_format($inst->getBrokerFees(), 2, '.', ''),
                  'shipping_fees'       => number_format($inst->getShippingFees(), 2, '.', ''),
                  'regular_price'       => number_format($var->calculateUnitPrice(), 2, '.', ''),
                  'estimate'            => $inst->getEstimate(),
                  'taxable_hst'         => ($inst->getTaxableHst() > 0),
                  'taxable_pst'         => ($inst->getTaxablePst() > 0),
                  'taxable_gst'         => ($inst->getTaxableGst() > 0),
                  'enviro_levy'         => $inst->getEnviroLevy(),
                  'battery_levy'        => $inst->getBatteryLevy(),
                  'total'               => number_format($inst->getSubtotal(), 2),
                  'supplier_order_id'   => $order_id,
                  'serial'              => (string) $inst->getSerialNumber(),
                  'has_serial_number'   => (int) $part->getHasSerialNumber(),
                  'location'            => (string) $var->getLocation(),
                  'who'                 => $who,
                  'statusaction'        => $status,
                  'available'           => $var->getQuantity('available', false),
                  'min_quantity'        => $var->getQuantity('minimum', false),
                  'max_quantity'        => $var->getQuantity('maximum', false)
                );

    $this->renderText("{success:true, data:".json_encode($data)."}");

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartload';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;

  }//executePartload()---------------------------------------------------------

  /**********************************/  
  /*      CUSTOM PART STUFF         */
  /**********************************/
  public function executePartcustomEdit(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartcustomEdit';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $is_new = false;

    if ($request->getParameter('instance_id') != 'new')
    {
      $this->forward404Unless($this->getUser()->hasCredential('workorder_edit'));
      $this->forward404Unless($instance = PartInstancePeer::retrieveByPk($request->getParameter('instance_id')));
    }
    else
    {
      $is_new = true;
    }

    //validate
    $result = true;
    $errors = array();

    if (!trim($request->getParameter('custom_name')))
    {
      $result = false;
      $errors['custom_name'] = 'You must specify a label/name for this part';
    }
    if (!(((float) $request->getParameter('unit_price')) > 0))
    {
      $result = false;
      $errors['unit_price'] = 'Invalid Price specified. Price must not be negative!';
    }
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Getting UNIT_COST';
      sfContext::getInstance()->getLogger()->info($message);
    }

    $unitCost = null;
    if ($request->getParameter('unit_cost') && ((float) $request->getParameter('unit_cost')) < 0)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Invalid Cost specified. Cost must not be negative!';
        sfContext::getInstance()->getLogger()->info($message);
      }

      $result = false;
      $errors['unit_cost'] = 'Invalid Cost specified. Cost must not be negative!';
    } else {
      $unitCost = (float) $request->getParameter('unit_cost');
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done getting UNIT_COST - ';
      sfContext::getInstance()->getLogger()->info($message.$unitCost);
    }

    $brokerFees = null;
    if ($request->getParameter('broker_fees') && ((float) $request->getParameter('broker_fees')) < 0)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Invalid broker fees specified. Fees must not be negative!';
        sfContext::getInstance()->getLogger()->info($message);
      }

      $result = false;
      $errors['broker_fees'] = 'Invalid broker fees specified. Fees must not be negative!';
    } else {
      $brokerFees = (float) $request->getParameter('broker_fees');
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done getting broker_fees - ';
      sfContext::getInstance()->getLogger()->info($message.$brokerFees);
    }

    $shippingFees = null;
    if ($request->getParameter('shipping_fees') && ((float) $request->getParameter('shipping_fees')) < 0)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $message = 'Invalid shipping fees specified. Fees must not be negative!';
        sfContext::getInstance()->getLogger()->info($message);
      }

      $result = false;
      $errors['shipping_fees'] = 'Invalid shipping fees specified. Fees must not be negative!';
    } else {
      $shippingFees = (float) $request->getParameter('shipping_fees');
    }

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'Done getting shipping_fees - ';
      sfContext::getInstance()->getLogger()->info($message.$shippingFees);
    }

    if (!(((float) $request->getParameter('quantity')) > 0))
    {
      $result = false;
      $errors['quantity'] = 'Invalid Quantity specified. Must be positive';
    }


    if ($result)
    {
      if (!isset($instance))
      {
        $instance = new PartInstance();
      }

      $old_parent = $instance->getWorkorderItem();
      $instance->setWorkorderItemId($request->getParameter('workorder_item_id'));
      $instance->setCustomName($request->getParameter('custom_name'));
      $instance->setCustomOrigin($request->getParameter('custom_origin'));
      $instance->setQuantity($request->getParameter('quantity'));
      $instance->setEstimate($request->getParameter('estimate') !== '0');  //true for 1 or 2
      $instance->setAllocated($request->getParameter('estimate') !== '1'); //true for 0 or 2
      $instance->setDelivered($request->getParameter('estimate') !== '1'); //true for 0 or 2
      $instance->setSerialNumber($request->getParameter('serial_number'));
      $instance->setBrokerFees($brokerFees);
      $instance->setShippingFees($shippingFees);
      //$instance->setUnitCost($request->getParameter('cost'));
      //$instance->setUnitCost($request->getParameter('unit_cost'));
      $instance->setUnitCost($unitCost);
      $instance->setUnitPrice($request->getParameter('unit_price'));
      $instance->setInternalNotes($request->getParameter('internal_notes'));

      //this keeps existing tax rate in the instance if set
      $instance->setTaxableHst($request->getParameter('taxable_hst') ? ($instance->getTaxableHst() != 0 ? $instance->getTaxableHst() : sfConfig::get('app_hst_rate')) : 0);
      $instance->setTaxablePst($request->getParameter('taxable_pst') ? ($instance->getTaxablePst() != 0 ? $instance->getTaxablePst() : sfConfig::get('app_pst_rate')) : 0);
      $instance->setTaxableGst($request->getParameter('taxable_gst') ? ($instance->getTaxableGst() != 0 ? $instance->getTaxableGst() : sfConfig::get('app_gst_rate')) : 0);

      //add employee if new
      $user = $this->getContext()->getUser();
      if ($is_new && $user->isAuthenticated() && $user->getEmployee())
      {
        $instance->setEmployee($user->getEmployee());
      }
      $instance->setDateUsed(time());
      $instance->save();      

      //save and update old parents' part amount total
      if (!$is_new && $old_parent->getId() != $instance->getWorkorderItemId())
      {
        $old_parent->calculateActualPart();
      }

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
      $message = 'DONE executePartcustomEdit';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executePartcustomEdit()---------------------------------------------------

   public function executePartcustomLoad(sfWebRequest $request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START executePartcustomLoad';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($inst = PartInstancePeer::retrieveByPk($request->getParameter('instance_id')));
  
    $who = ($inst->getEmployee() ? $inst->getEmployee()->generateName() : '<em>Unknown</em>').' on '.($inst->getDateUsed() ? $inst->getDateUsed('M j, Y') : 'N/A');
    $data = array('instance_id'         => $inst->getId(),
                  'workorder_item_id'   => $inst->getWorkorderItemId(),
                  'parent_name'         => $inst->getWorkorderItem()->getLabel(),
                  'custom_name'         => $inst->getCustomName(),
                  'custom_origin'       => $inst->getCustomOrigin(),
                  'quantity'            => $inst->outputQuantity(false),
                  'broker_fees'         => number_format($inst->getBrokerFees(), 2, '.', ''),
                  'shipping_fees'       => number_format($inst->getShippingFees(), 2, '.', ''),
                  'unit_cost'           => number_format($inst->getUnitCost(), 2, '.', ''),
                  'unit_price'          => number_format($inst->getUnitPrice(), 2, '.', ''),
                  'estimate'            => ($inst->getEstimate() && $inst->getDelivered() ? '2' : ($inst->getEstimate() ? '1' : '0')),
                  'taxable_hst'         => ($inst->getTaxableHst() > 0),
                  'taxable_pst'         => ($inst->getTaxablePst() > 0),
                  'taxable_gst'         => ($inst->getTaxableGst() > 0),
                  'total'               => number_format($inst->getSubtotal(), 2),
                  'serial_number'       => (string) $inst->getSerialNumber(),
                  'internal_notes'      => $inst->getInternalNotes(),
                  'who'                 => $who
                );

    $this->renderText("{success:true, data:".json_encode($data)."}");

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE executePartcustomLoad';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//executePartcustomLoad()---------------------------------------------------

  /**********************************/
  /*      EXPENSES STUFF            */
  /**********************************/

  public function executeExpenseedit(sfWebRequest $request)
  {
    if ($request->getParameter('expense_id') != 'new')
    {
      $this->forward404Unless($this->getUser()->hasCredential('workorder_edit'));
      $this->forward404Unless($expense = WorkorderExpensePeer::retrieveByPk($request->getParameter('expense_id')));
    }
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    if (!(((float) $request->getParameter('price')) > 0))
    {
      $result = false;
      $errors['price'] = 'Invalid Price specified. Price must not be negative!';
    }
    if ($request->getParameter('cost') && ((float) $request->getParameter('cost')) < 0)
    {
      $result = false;
      $errors['cost'] = 'Invalid Cost specified. Cost must not be negative!';
    }

    if ($result)
    {
      if (!isset($expense))
      {
        $expense = new WorkorderExpense();
      }

      $old_parent = $expense->getWorkorderItem();
      $expense->setWorkorderItemId($request->getParameter('workorder_item_id'));
      $expense->setLabel($request->getParameter('label'));
      $expense->setEstimate($request->getParameter('estimate') != '0');
      $expense->setInvoice($request->getParameter('estimate') != '1');
      $expense->setCustomerNotes($request->getParameter('customer_notes'));
      $expense->setInternalNotes($request->getParameter('internal_notes'));
      $expense->setCost($request->getParameter('cost'));
      $expense->setOrigin($request->getParameter('origin'));
      $expense->setPrice($request->getParameter('price'));

      //this keeps existing tax rate in the expense if set
      $expense->setTaxableHst($request->getParameter('taxable_hst') ? ($expense->getTaxableHst() != 0 ? $expense->getTaxableHst() : sfConfig::get('app_hst_rate')) : 0);
      $expense->setTaxablePst($request->getParameter('taxable_pst') ? ($expense->getTaxablePst() != 0 ? $expense->getTaxablePst() : sfConfig::get('app_pst_rate')) : 0);
      $expense->setTaxableGst($request->getParameter('taxable_gst') ? ($expense->getTaxableGst() != 0 ? $expense->getTaxableGst() : sfConfig::get('app_gst_rate')) : 0);


      /**
       * TODO :  Validate the default cost value
       * */
      //if (!$request->getParameter('cost'))
      //{
      //  $expense->setCost(0.00);
      //}

      //save and update labour cost of old parent if needed
      $expense->save();
      if ($old_parent && $old_parent->getId() != $expense->getWorkorderItemId())
      {
        $old_parent->calculateActualOther();
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
  }//executeExpenseedit()------------------------------------------------------

  /*
   * Moves an expense via drag-and-drop in the workorder screen
   */
  public function executeExpensemove(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));
          
    //load up expense
    $this->forward404Unless($expense = WorkorderExpensePeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($target = WorkorderItemPeer::retrieveByPk($request->getParameter('target')));
    $this->forward404Unless($expense->getWorkorderItemId());

    $old_item = $expense->getWorkorderItem();    
    $expense->setWorkorderItem($target);
    $expense->save();

    //recalculate cost of old branch. New branch is automatically recalculated on save.
    $old_item->calculateActualOther();

    $this->renderText("{success:true}");

    return sfView::NONE;
  }

  public function executeExpensedelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);

    $this->forward404Unless($expense = WorkorderExpensePeer::retrieveByPk($request->getParameter('expense_id')));
    $this->forward404Unless($expense->getWorkorderItem()->getWorkorderId() == $workorder->getId());
    $this->forward404If($workorder->getStatus() == 'Completed' || $workorder->getStatus() == 'Cancelled');

    $expense->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }

  public function executeExpenseload(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($request->isMethod('post'));

    //load up expense
    $this->forward404Unless($expense = WorkorderExpensePeer::retrieveByPk($request->getParameter('expense_id')));
    if ($expense->getEstimate() && $expense->getInvoice())
    {
      $estimate = '2';
    }
    else if ($expense->getEstimate())
    {
      $estimate = '1';
    }
    else
    {
      $estimate = '0';
    }
    $data = array('expense_id'          => $expense->getId(),
                  'workorder_item_id'   => $expense->getWorkorderItemId(),
                  'workorder_item_name' => $expense->getWorkorderItem()->getLabel(),
                  'label'               => $expense->getLabel(),
                  'cost'                => ($expense->getCost() > 0 ? $expense->getCost() : ''),
                  'price'               => $expense->getPrice(),
                  'estimate'            => $estimate,
                  'origin'              => $expense->getOrigin(),
                  'customer_notes'      => $expense->getCustomerNotes(),
                  'internal_notes'      => $expense->getInternalNotes(),
                  'taxable_hst'         => ($expense->getTaxableHst() > 0 ? 1 : 0),
                  'taxable_pst'         => ($expense->getTaxablePst() > 0 ? 1 : 0),
                  'taxable_gst'         => ($expense->getTaxableGst() > 0 ? 1 : 0)
                 );

    $this->renderText("{success:true, data:".json_encode($data)."}");

    return sfView::NONE;

  }


  /**********************************/
  /*      NOTES     STUFF           */
  /**********************************/

  public function executeNotesedit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $workorder = $this->loadWorkorder($request);

    //validate
    $result = true;
    $errors = array();

    //save
    if ($result)
    {
      $workorder->setCustomerNotes($request->getParameter('customer_notes'));
      $workorder->setInternalNotes($request->getParameter('internal_notes'));
      $workorder->save();

      $customer_notes = (trim($workorder->getCustomerNotes()) ? nl2br($workorder->getCustomerNotes()) : '<span class="inactive_text">No Customer Notes Specified.</span>');
      $internal_notes = (trim($workorder->getInternalNotes()) ? nl2br($workorder->getInternalNotes()) : '<span class="inactive_text">No Internal Notes Specified.</span>');
      $empty = (!(trim($workorder->getCustomerNotes())) && (!(trim($workorder->getInternalNotes()))));

      //output result as JSON
      $data = array( 'customer_notes' => $customer_notes, 'internal_notes' => $internal_notes, 'empty' => $empty );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  public function executeNotesload(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    $workorder = $this->loadWorkorder($request);

    $customer_notes = (trim($workorder->getCustomerNotes()) ? $workorder->getCustomerNotes() : '');
    $internal_notes = (trim($workorder->getInternalNotes()) ? $workorder->getInternalNotes() : '');
    $data = array( 'customer_notes' => $customer_notes, 'internal_notes' => $internal_notes );
    $this->renderText("{success:true, data:".json_encode($data)."}");

    return sfView::NONE;

  }


  /**********************************/
  /*      TIMELOGS STUFF            */
  /**********************************/


  /*
   * Moves a timelog via drag-and-drop in the workorder screen
   */
  public function executeTimelogmove(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    //load up timelog
    $this->forward404Unless($timelog = TimelogPeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($target = WorkorderItemPeer::retrieveByPk($request->getParameter('target')));

    $old_item = $timelog->getWorkorderItem();
    $timelog->setWorkorderItem($target);
    $timelog->save();

    //recalculate cost of old branch. New branch is automatically recalculated on save.
    $old_item->calculateActualLabour();

    $this->renderText("{success:true}");

    return sfView::NONE;
  }


  /**********************************/
  /*      BILLING STUFF             */
  /**********************************/
  public function executeDeletepayment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($payment = WorkorderPaymentPeer::retrieveByPk($request->getParameter('payment_id')));
    $this->forward404Unless($payment->getWorkorderId() == $workorder->getId());

    //deletes the payment.
    $payment->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }

  public function executeAddpayment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);

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
    if (!strtotime($request->getParameter('date')))
    {
      $valid = false;
      $errors['date'] = 'Invalid Date entered!';
    }
    if ($request->getParameter('whom_id') == 'inhouse')
    {
      $valid = false;
      $errors['whom_id'] = 'Cannot record payments for Delta/InHouse at the current time.';
    }

    //add payment if valid
    if ($valid)
    {
      $payment = new WorkorderPayment();
      $payment->setWorkorderId($workorder->getId());
      $payment->setAmount($amount);
      $payment->setCreatedAt(strtotime($request->getParameter('date')));

      //check for payee info
      if ($request->getParameter('whom_id', 'cust') !== 'cust')
      {
        $whom_id = $request->getParameter('whom_id');
        if (substr($whom_id, 0, 2) == 's_')
        {
          $payment->setSupplier(SupplierPeer::retrieveByPk(substr($whom_id, 2)));
        }
        else if (substr($whom_id, 0, 2) == 'm_')
        {
          $payment->setManufacturer(ManufacturerPeer::retrieveByPk(substr($whom_id, 2)));
        }
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

  public function executeDeleteinvoice(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);
    $this->forward404Unless($wo_invoice = WorkorderInvoicePeer::retrieveByPk($request->getParameter('invoice_id')));
    $this->forward404Unless($wo_invoice->getWorkorderId() == $workorder->getId());

    //unassign all the items
    $update_parts = 'UPDATE '.PartInstancePeer::TABLE_NAME.
      ' SET '.PartInstancePeer::WORKORDER_INVOICE_ID.' = NULL'.
      ' WHERE '.PartInstancePeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($update_parts);
    $stmt->execute();        
    $update_expenses = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.
      ' SET '.WorkorderExpensePeer::WORKORDER_INVOICE_ID.' = NULL'.
      ' WHERE '.WorkorderExpensePeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($update_expenses);
    $stmt->execute();
    $update_timelogs = 'UPDATE '.TimelogPeer::TABLE_NAME.
      ' SET '.TimelogPeer::WORKORDER_INVOICE_ID.' = NULL'.
      ' WHERE '.TimelogPeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($update_timelogs);
    $stmt->execute();

    //deletes the invoice records
    $invoice = $wo_invoice->getInvoice();
    $wo_invoice->delete();
    $invoice->delete();

    $this->renderText('{success:true}');

    return sfView::NONE;
  }

  public function executeAddinvoice(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $workorder = $this->loadWorkorder($request);

    //validate
    $valid = true;
    $errors = array();

    $date = strtotime($request->getParameter('date'));
    if ($date && (date('Ymd', $date) == date('Ymd')))
    {
      $date = time();
    }

    if (!$date)
    {
      $valid = false;
      $errors['date'] = 'Invalid Date entered!';
    }
    if ($date > time())
    {
      $valid = false;
      $errors['date'] = 'Cannot set the billing date in the future';
    }
    
    //add invoice if valid
    if ($valid)
    {
      $invoice = new Invoice();
      $invoice->setIssuedDate($date);
      $invoice->save();

      $wo_invoice = new WorkorderInvoice();
      $wo_invoice->setWorkorderId($workorder->getId());
      $wo_invoice->setInvoiceId($invoice->getId());
      $wo_invoice->save();
      
      //add all unassigned items before the invoice date
      $update_parts = 'UPDATE '.WorkorderItemPeer::TABLE_NAME.', '.PartInstancePeer::TABLE_NAME.
        ' SET '.PartInstancePeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId().
        ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$workorder->getId().
        ' AND '. WorkorderItemPeer::ID.' = '. PartInstancePeer::WORKORDER_ITEM_ID.
        ' AND '. PartInstancePeer::WORKORDER_INVOICE_ID.' IS NULL'.
        ' AND '. PartInstancePeer::DELIVERED.' = 1'.
        ' AND '. PartInstancePeer::DATE_USED ." < '".date('Y-m-d H:i:s', $date)."'";
      $con = Propel::getConnection();
      $stmt = $con->prepare($update_parts);
      $stmt->execute();        
      $update_expenses = 'UPDATE '.WorkorderItemPeer::TABLE_NAME.', '.WorkorderExpensePeer::TABLE_NAME.
        ' SET '.WorkorderExpensePeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId().
        ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$workorder->getId().
        ' AND '. WorkorderItemPeer::ID.' = '. WorkorderExpensePeer::WORKORDER_ITEM_ID.
        ' AND '. WorkorderExpensePeer::WORKORDER_INVOICE_ID.' IS NULL'.
        ' AND '. WorkorderExpensePeer::ESTIMATE.' = 0'.
        ' AND '. WorkorderExpensePeer::CREATED_AT ." < '".date('Y-m-d H:i:s', $date)."'";
      $con = Propel::getConnection();
      $stmt = $con->prepare($update_expenses);
      $stmt->execute();
      $update_timelogs = 'UPDATE '.WorkorderItemPeer::TABLE_NAME.', '.TimelogPeer::TABLE_NAME.
        ' SET '.TimelogPeer::WORKORDER_INVOICE_ID.' = '.$wo_invoice->getId().
        ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$workorder->getId().
        ' AND '. WorkorderItemPeer::ID.' = '. TimelogPeer::WORKORDER_ITEM_ID.
        ' AND '. TimelogPeer::WORKORDER_INVOICE_ID.' IS NULL'.
        ' AND '. TimelogPeer::APPROVED.' = 1'.
        ' AND '. TimelogPeer::END_TIME ." < '".date('Y-m-d H:i:s', $date)."'";
      $con = Propel::getConnection();
      $stmt = $con->prepare($update_timelogs);
      $stmt->execute();

      //calculate the invoice totals for quick retrieval later
      $wo_invoice->calculateTotal();
   
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


  /************************************/
  /*      CATEGORIES STUFF            */
  /************************************/

  /*
   *  View the list of categories for editing/adding
   */
  public function executeCategories(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * add a new category
   */
  public function executeCategoryEdit(sfWebRequest $request)
  {
    $existing = WorkorderCategoryPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    //check for same name
    $c = new Criteria();
    $c->add(WorkorderCategoryPeer::NAME, trim($request->getParameter('name')));
    if ($existing)
    {
      $c->add(WorkorderCategoryPeer::ID, $existing->getId(), Criteria::NOT_EQUAL);
    }
    if (WorkorderCategoryPeer::doSelectOne($c))
    {
      $result = false;
      $errors['name'] = 'Category with that name already exists!';
    }

    //create object
    if ($result)
    {
      if (!$existing)
      {
        $existing = new WorkorderCategory();
      }
      $existing->setName(trim($request->getParameter('name')));
      $existing->save();

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
    $this->forward404Unless($cat = WorkorderCategoryPeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //edit existing workorders
    $select_c = new Criteria(WorkorderPeer::DATABASE_NAME);
    $select_c->add(WorkorderPeer::WORKORDER_CATEGORY_ID, $cat->getId());
    $newval_c = new Criteria(WorkorderPeer::DATABASE_NAME);
    $newval_c->add(WorkorderPeer::WORKORDER_CATEGORY_ID, null);
    $con = Propel::getConnection(WorkorderPeer::DATABASE_NAME);
    BasePeer::doUpdate($select_c, $newval_c, $con);

    //delete
    $cat->delete();

    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;

  }



  /**********************************/
  /*      UNUSED OLD STUFF          */
  /**********************************/

  public function executeItemSearch(sfWebRequest $request)
  {
    if ( $request->getParameter('search_text') != '' ) {

      $workorder_items = new Criteria();
      $workorder_items->add(WorkorderItemPeer::LABEL, '%'.$request->getParameter('search_text').'%', Criteria::LIKE);
      $workorder_items = WorkorderItemPeer::doSelect($workorder_items);

      return $this->renderText($this->formatItemList($workorder_items));
    } else {
      return $this->renderText('[]');
    }
  }

  private function formatItemList($workorder_items) {
    //get all labour type data to not request it for each workorder_item
    $labour_types = LabourTypePeer::doSelect(new Criteria());
    $labour_types_array = array();
    foreach ( $labour_types as $labour_type )
      $labour_types_array[$labour_type->getId()] = $labour_type->getHourlyRate();

    //retrive part instance allocated and delivered states by one query
    $partInstanceIds = array();
    foreach ( $workorder_items as $workorder_item ) {
      if ( $workorder_item->getPartInstanceId() != '' )
        $partInstanceIds[] = $workorder_item->getPartInstanceId();
    }
    $partInstances_array = array();
    $partInstances = new Criteria();
    $partInstances->add(PartInstancePeer::ID, $partInstanceIds, Criteria::IN);
    $partInstances = PartInstancePeer::doSelect($partInstances);
    foreach ( $partInstances as $partInstance )
      $partInstances_array[$partInstance->getId()] = $partInstance->getDelivered() ? 'D' : ( $partInstance->getAllocated() ? 'A' : '' );

    //retrive attached files and photos by one query
    $workorder_item_ids = array();
    $workorder_item_files = array();
    $workorder_item_photos = array();
    foreach ( $workorder_items as $workorder_item ) {
      $workorder_item_ids[] = $workorder_item->getId();
      $workorder_item_files[$workorder_item->getId()] = false;
      $workorder_item_photos[$workorder_item->getId()] = false;
    }
    $attachedFiles = new Criteria();
    //$attachedFiles->addJoin(FilePeer::ID, WorkorderItemFilePeer::FILE_ID, Criteria::LEFT_JOIN);
    $attachedFiles->add(WorkorderItemFilePeer::WORKORDER_ITEM_ID, $workorder_item_ids, Criteria::IN);
    $attachedFiles = WorkorderItemFilePeer::doSelect($attachedFiles);
    foreach ( $attachedFiles as $attachedFile )
      $workorder_item_files[$attachedFile->getWorkorderItemId()] = true;

    $attachedPhotos = new Criteria();
    $attachedPhotos->add(WorkorderItemPhotoPeer::WORKORDER_ITEM_ID, $workorder_item_ids, Criteria::IN);
    $attachedPhotos = WorkorderItemPhotoPeer::doSelect($attachedPhotos);
    foreach ( $attachedPhotos as $attachedPhoto )
      $workorder_item_photos[$attachedPhoto->getWorkorderItemId()] = true;
      
    $workorder_items_array = array();
    foreach ( $workorder_items as $workorder_item ) {
      $isLeaf = !$workorder_item->hasChildren() && ($workorder_item->getPartEstimate() != '' || $workorder_item->getLabourEstimateHours() != '' || $workorder_item->getOtherEstimate() != '');
      $workorder_items_array[] = array(
        'label' => $workorder_item->getLabel(),
        'id' => $workorder_item->getId(),
        'iconCls' => 'icon-'.$workorder_item->getType(),
        'estimate' => $workorder_item->getEstimate($labour_types_array),
        'state' => array_key_exists($workorder_item->getPartInstanceId(), $partInstances_array) ? $partInstances_array[$workorder_item->getPartInstanceId()] : '',
        'internal_notes' => $workorder_item->getInternalNotes(),
        'customer_notes' => $workorder_item->getCustomerNotes(),
        'attached_files' => $workorder_item_files[$workorder_item->getId()],
        'attached_photos' => $workorder_item_photos[$workorder_item->getId()],
        'uiProvider' => 'col',
        'leaf' => $isLeaf
      );
    }

    return json_encode($workorder_items_array);
  }

  
  private function loadWorkorder(sfWebRequest $request)
  {
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    return $workorder;
  }

}
