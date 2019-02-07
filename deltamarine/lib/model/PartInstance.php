<?php

class PartInstance extends BasePartInstance
{

  private $netQuantity = null;

  public function __toString()
  {
    if ($this->getCustomName()) {
      return $this->getCustomName();
    }
    else
    {
      return $this->getPartVariant()->__toString();
    }
  }

  public function outputUnitPrice($with_units = true)
  {
    if (!$this->getPartVariantId())
    {
      $with_units = false;
    }

    $amount = number_format($this->getUnitPrice(), 2);

    return $amount.(($with_units && $this->getPartVariant()->getUnits()) ? '/'.$this->getPartVariant()->getUnits() : '');
  }

  public function outputQuantity($with_units = true, $net = false)
  {
    if (!$this->getPartVariantId())
    {
      $with_units = false;
    }

    $amount = $this->getQuantity($net);

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }


  //sets item as being on hold. doesn't affect prices or costs or lots.
  public function allocate($val = true)
  {
    $this->setAllocated($val);
    $this->save();

    //set as on hold (increase on hold quantity for related part variant)
    if ($this->getPartVariantId())
    {
      $this->getPartVariant()->calculateCurrentOnHold();
    }
  }

  public function unallocate()
  {
    if ($this->getDelivered())
    {
      $this->undeliver();
    }

    $this->allocate(false);
  }


  //sets item as being delivered. doesn't affect prices or costs, but does subtract from first available lot(s)
  public function deliver($redeliver_quantity = false)
  {
    //redeliver_quantity is called by CustomerReturnItem->delete() 
    // to take inventory back out when deleting a return item.
    if (!$redeliver_quantity && $this->getDelivered())
    {
      return;
    }

    if ($this->getPartVariant())
    {

      //subtract from oldest lot unless it's lifo 
      //(don't need to check for Net quantity, since can't do return yet on this!)
      $quantity_needing_delivery = ($redeliver_quantity > 0 ? $redeliver_quantity : $this->getQuantity());

      if (!$this->getPartVariant()->getTrackInventory())
      {
        //don't subtract from lots!
        $quantity_needing_delivery = 0;
      }

      while ($quantity_needing_delivery > 0)
      {
        $c = new Criteria();
        $c->add(PartLotPeer::PART_VARIANT_ID, $this->getPartVariantId());
        $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
        if ($this->getPartVariant()->getCostCalculationMethod() == 'lifo')
        {
          $c->addDescendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
        }
        else
        {
          $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
        }
        if ($lot = PartLotPeer::doSelectOne($c))
        {
          if ($lot->getQuantityRemaining() >= $quantity_needing_delivery)
          {
            $lot->setQuantityRemaining($lot->getQuantityRemaining() - $quantity_needing_delivery);
            $lot->save();
            $quantity_needing_delivery = 0;
          }
          else
          {
            $quantity_needing_delivery -= $lot->getQuantityRemaining();
            $lot->setQuantityRemaining(0);
            $lot->save();
          }
        }
        else
        {
          return false; //keeps as not delivered
        }
      }
    }

    $this->setAllocated(true); //just in case 
    $this->setDelivered(true);
    if (!$this->getDateUsed())
    {
      $this->setDateUsed(time());
    }
    $this->save();

    if ($this->getPartVariant())
    {
      $this->getPartVariant()->calculateCurrentOnHold();
      $this->getPartVariant()->calculateCurrentOnHand();
    }
  }

  public function calculateCost()
  {
    if ($this->getPartVariantId())
    {
      $this->setUnitCost($this->getPartVariant()->calculatUnitCost(false));
    }
  }

  public function calculatePrice()
  {
    if ($this->getPartVariantId())
    {
      $this->setUnitPrice($this->getPartVariant()->calculatUnitPrice(false));
    }
  }

  //calculates subtotal (before taxes and fees)
  public function getSubtotal($net = true)
  {
    return round(($this->getUnitPrice() * $this->getQuantity($net)), 2);
  }

  public function getEnviroLevyTotal($net = true)
  {
    return round(($this->getEnviroLevy() * $this->getQuantity($net)), 2);
  }

  public function getBatteryLevyTotal($net = true)
  {
    return round(($this->getBatteryLevy() * $this->getQuantity($net)), 2);
  }

  public function getHstTotal($net = true, $round = false)
  {
    //HST is charged on the subtotal PLUS enviro and battery fees
    //(see http://www.sbr.gov.bc.ca/documents_library/bulletins/sst_015.pdf)
    if ($this->getTaxableHst() > 0)
    {
      $amt = $this->getSubtotal($net) + $this->getEnviroLevyTotal($net) + $this->getBatteryLevyTotal($net);
      $amt = round($amt, 2) * $this->getTaxableHst()/100; //round base amount before calculating to reduce rounding errors
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getPstTotal($net = true, $round = false)
  {
    if ($this->getTaxablePst() > 0)
    {
      $amt = $this->getSubtotal($net) + $this->getEnviroLevyTotal($net) + $this->getBatteryLevyTotal($net);
      $amt = round($amt, 2) * $this->getTaxablePst()/100; //round base amount before calculating to reduce rounding errors
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getGstTotal($net = true, $round = false)
  {
    if ($this->getTaxableGst() > 0)
    {
      $amt = $this->getSubtotal($net) + $this->getEnviroLevyTotal($net) + $this->getBatteryLevyTotal($net);
      $amt = round($amt, 2) * $this->getTaxableGst()/100; //round base amount before calculating to reduce rounding errors
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }


  //including all fees and taxes
  public function getTotal($net)
  {
    return ($this->getSubtotal($net) + $this->getEnviroLevyTotal($net) + $this->GetBatteryLevyTotal($net) + $this->getHstTotal($net) + $this->getPstTotal($net) + $this->getGstTotal($net));
  }

  //override to allow using NetQuantity instead
  public function getQuantity($net = false)
  {
    return ($net ? $this->getNetQuantity() : parent::getQuantity());
  }

  //quantity minus returns
  public function getNetQuantity()
  {
    if ($this->netQuantity === null)
    {
      $this->netQuantity = $this->getQuantity();
      if ($this->getPartVariantId())
      {
        $c = new Criteria();
        $c->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->getId());
        $c->addJoin(CustomerOrderItemPeer::ID, CustomerReturnItemPeer::CUSTOMER_ORDER_ITEM_ID);
        $c->addJoin(CustomerReturnItemPeer::PART_INSTANCE_ID, PartInstancePeer::ID);
        $c->add(PartInstancePeer::PART_VARIANT_ID, $this->getPartVariantId());
        $returns = PartInstancePeer::doSelect($c);
        foreach ($returns AS $return)
        {
          //return quantities are negative!!
          $this->netQuantity += $return->getQuantity();
        }
      }
    }

    return $this->netQuantity;
  }

  public function getCustomerOrderItem()
  {
    $c = new Criteria();
    $c->add(CustomerOrderItemPeer::PART_INSTANCE_ID, $this->getId());
    return (CustomerOrderItemPeer::doSelectOne($c));
  }

  public function getReturnedQuantity()
  {
    return ($this->getNetQuantity() - $this->getQuantity());
  }

  /*
   * this copies the default settings for price, tax, fees etc from the related variant
   */
  public function copyDefaults($hst_exempt = false, $pst_exempt = false, $gst_exempt = false)
  {
    if ($this->getPartVariantId())
    {
      $variant = $this->getPartVariant();
      $this->setUnitPrice($variant->calculateUnitPrice(false));
      $this->setUnitCost($variant->calculateUnitCost(false));
      $this->setTaxableHst(!$hst_exempt ? sfConfig::get('app_hst_rate') : 0);
      $this->setTaxablePst(!$pst_exempt ? sfConfig::get('app_pst_rate') : 0);
      $this->setTaxableGst(!$gst_exempt ? sfConfig::get('app_gst_rate') : 0);
      $this->setEnviroLevy((float) $variant->getEnviroLevy());
      $this->setBatteryLevy((float) $variant->getBatteryLevy()); 
    }
  }

  public function undeliver()
  {
    if ($this->getDelivered())
    { 
      if ($this->getPartVariantId() && $this->getPartVariant()->getTrackInventory())
      {
        $lot = true;

        //get the un-returned quantity
        $quantity_needing_return = $this->getQuantity(true);
        $counter = 1;
        while ($quantity_needing_return > 0 && $lot)
        {
          $lot = false;

          //find the first non-full lot
          $c = new Criteria();
          $c->add(PartLotPeer::PART_VARIANT_ID, $this->getPartVariantId());
          $c->add(PartLotPeer::QUANTITY_REMAINING, 0, Criteria::GREATER_THAN);
          $c->add(PartLotPeer::QUANTITY_RECEIVED, PartLotPeer::QUANTITY_RECEIVED.'>'.PartLotPeer::QUANTITY_REMAINING, Criteria::CUSTOM);
          if ($this->getPartVariant()->getCostCalculationMethod() == 'lifo')
          {
            $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
          }
          else
          {
            $c->addDescendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
          }
          $lot = PartLotPeer::doSelectOne($c);
        
          //next, try an empty lot
          if (!$lot)
          {
            $c = new Criteria();
            $c->add(PartLotPeer::PART_VARIANT_ID, $this->getPartVariantId());
            $c->add(PartLotPeer::QUANTITY_RECEIVED, 0, Criteria::GREATER_THAN);
            $c->add(PartLotPeer::QUANTITY_REMAINING, 0);
            if ($this->getPartVariant()->getCostCalculationMethod() == 'lifo')
            {
              $c->addDescendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
            }
            else
            {
              $c->addAscendingOrderByColumn(PartLotPeer::RECEIVED_DATE);
            }
            $lot = PartLotPeer::doSelectOne($c);
          }

          //there is no reason why lot should not be set by now, if inventory is tracked.
          if (!$lot)
          {
            break;
          }

          if (($lot->getQuantityReceived() - $lot->getQuantityRemaining()) < $quantity_needing_return)
          {
            $quantity_needing_return -= ($lot->getQuantityReceived() - $lot->getQuantityRemaining());
            $lot->setQuantityRemaining($lot->getQuantityReceived());
          }
          else
          {
            $lot->setQuantityRemaining($lot->getQuantityRemaining() + $quantity_needing_return);
            $quantity_needing_return = 0;
          }
          $lot->save();
        }
      }

      $this->setDelivered(false);
      $this->setDateUsed(null);
      $this->save();

      if ($this->getPartVariant())
      { 
        $this->getPartVariant()->calculateCurrentOnHand();
        $this->getPartVariant()->calculateCurrentOnHold();
      }
    }
  }

  public function delete(PropelPDO $con = null)
  {
    //SPECIAL ORDER
    if ($this->getSupplierOrderItemId())
    {
      $orderitem = $this->getSupplierOrderItem();
      if ($orderitem && $orderitem->getSupplierOrder() && !$orderitem->getSupplierOrder()->getFinalized())
      {
        $order = $orderitem->getSupplierOrder();
        if ($orderitem->getQuantityRequested() > $this->getQuantity())
        {
          //reduce order amount automatically
          $orderitem->setQuantityRequested($orderitem->getQuantityRequested() - $this->getQuantity());
          $orderitem->save();
          $order->setReceivedSome($order->calculateReceivedSome());
          $order->setReceivedAll($order->calculateReceivedAll());
          $order->save();
        }
        else
        {
          $this->setSupplierOrderItem(null);
          $this->save();
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
        $this->getPartVariant()->calculateCurrentOnOrder();
      }
    }

    //ALREADY TAKEN FROM INVENTORY
    $this->undeliver();
    $item = $this->getWorkorderItem();

    parent::delete($con);

    if ($this->getPartVariantId())
    {
      $this->getPartVariant()->calculateCurrentOnHold();
    }
    if ($item)
    {
      $item->calculateActualPart();
    }
  }

  public function save (PropelPDO $con = null)
  {
    parent::save($con);

    //recalculate actual parts amounts of parent task(s)
    if ($item = $this->getWorkorderItem())
    {
      $item->calculateActualPart();
    }
  }

}
