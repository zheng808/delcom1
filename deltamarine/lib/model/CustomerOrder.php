<?php

class CustomerOrder extends BaseCustomerOrder
{
  public function __toString()
  {
  	return $this->getCustomer()->getName();
  }
  
  public function outputStatus()
  {
    if (!$this->getFinalized())
    {
      return 'Adding Items (Incomplete)';
    }
    if (!$this->getApproved())
    {
      return 'Awaiting Approval';
    }
    if (!$this->getSentSome())
    {
      return 'Awaiting Shipment/Delivery';
    }
    if (!$this->getSentAll())
    {
      return 'Partially Shipped/Delivered';
    }

    return 'Completed';
  }

  public function calculateSentAll()
  {
    $items = $this->getCustomerOrderItemsJoinPartInstance();
    foreach ($items AS $item)
    {
      if ($item->getQuantityCompleted() < $item->getPartInstance()->getQuantity())
      {
        return false;
      }
    }

    return true;
  }

  public function calculateSentSome()
  {
    $items = $this->getCustomerOrderItemsJoinPartInstance();
    foreach ($items AS $item)
    {
      if ($item->getQuantityCompleted() > 0)
      {
        return true;
      }
    }

    return false;
  }


  public function hasReturnedItems()
  {
    $c = new Criteria();
    $c->add(CustomerReturnPeer::CUSTOMER_ORDER_ID, $this->getId());

    return (CustomerReturnPeer::doCount($c) > 0);
  }

  public function hasPayments()
  {
    return ($this->countPayments() > 0);
  } 

  public function getTotalPayments()
  {
    $tally = 0;

    if ($payments = $this->getPayments())
    {
      foreach ($payments AS $payment)
      {
        $tally += $payment->getAmount();
      }
    }

    return $tally;
  }

  public function getCustomerOrderItemsJoinPartInfo()
  {
    $c = new Criteria();
    if ($this->collCustomerOrderItems === null)
    {
      if ($this->isNew())
      {
        $this->collCustomerOrderItems = array();
      }
      else
      {
        $c->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->getId());
        $this->collCustomerOrderItems = CustomerOrderItemPeer::doSelectJoinPartInfo($c);
      }
    }
    else
    {
      $c->add(CustomerOrderItemPeer::CUSTOMER_ORDER_ID, $this->getId());
    }
    $this->lastCustomerOrderItemCriteria = $c;

    return $this->collCustomerOrderItems;


    return CustomerOrderItemPeer::doSelectJoinPartInfo($c);
  }

  //gets the subtotal (no taxes, fees) for all items
  public function getTotals($type = 'total', $net = true)
  {
    $total = 0;
    foreach ($this->getCustomerOrderItemsJoinPartInfo() AS $item)
    {
      switch ($type){
      case 'subtotal':
        $total += $item->getPartInstance()->getSubtotal($net);
        break;
      case 'battery':
        $total += $item->getPartInstance()->getBatteryLevyTotal($net);
        break;
      case 'enviro':
        $total += $item->getPartInstance()->getEnviroLevyTotal($net);
        break;
      case 'hst':
        $total += $item->getPartInstance()->getHstTotal($net);
        break;
      case 'pst':
        $total += $item->getPartInstance()->getPstTotal($net);
        break;
      case 'gst':
        $total += $item->getPartInstance()->getGstTotal($net);
        break;
      case 'total':
        $total += $item->getPartInstance()->getTotal($net);
        break;
      }
    }

    return $total;
  }

  /*
   * creates or edits an invoice with the original quantities (ignores returns)
   */
  public function generateInvoice()
  {
    if (!($invoice = $this->getInvoice()))
    {
      $invoice = new Invoice();
      $invoice->setCustomerId($this->getCustomerId());
    }

    //net = false below. we want quantities BEFORE returns
    $invoice->setSubtotal($this->getTotals('subtotal', false));
    $invoice->setHst($this->getTotals('hst', false));
    $invoice->setPst($this->getTotals('pst', false));
    $invoice->setGst($this->getTotals('gst', false));
    $invoice->setEnviroLevy($this->getTotals('enviro', false));
    $invoice->setBatteryLevy($this->getTotals('battery', false));
    $invoice->setTotal($this->getTotals('total', false));

    if (!$this->getInvoiceId())
    {
      $this->setInvoice($invoice);
      $invoice->setIssuedDate(time());
      $invoice->setPayableDate(time());
    }

    $invoice->save();
    $this->save();
  }

  public function updateTaxes()
  {
    $items = $this->getCustomerOrderItems();
    foreach ($items AS $item)
    {
      $inst = $item->getPartInstance();
      $inst->setTaxableHst($this->getHstExempt() ? 0 : sfConfig::get('app_hst_rate'));
      $inst->setTaxablePst($this->getPstExempt() ? 0 : sfConfig::get('app_pst_rate'));
      $inst->setTaxableGst($this->getGstExempt() ? 0 : sfConfig::get('app_gst_rate'));
      $inst->save();
    }
    $this->generateInvoice();
  }

  public function updateDiscount($old_discount = 0)
  {
    //this will change any existing items that match the old discount
    // to have the new discounted price.
    $items = $this->getCustomerOrderItemsJoinPartInfo();
    foreach ($items AS $item)
    {
      if ($item->getPartInstance()->getPartVariant())
      {
        $recalculate = false;
        //calculate the current discount
        $source = round($item->getPartInstance()->getPartVariant()->calculateUnitPrice(), 2);
        $current = $item->getPartInstance()->getUnitPrice();
        $discounted_source = round((1 - ($old_discount / 100)) * $source, 2);
        if ($discounted_source == $current)
        {
          //this means the old part had the sale-wide discount as set before
          // so it needs to be updated
          $new_price = round($source * (1 - ($this->getDiscountPct() / 100)), 2);
          $item->getPartInstance()->setUnitPrice($new_price);
          $item->getPartInstance()->save();
        }
      }
    }
  }

  public function delete(PropelPDO $con = null)
  {
    //delete returns + items + instances
    if ($returns = $this->getCustomerReturns())
    {
      foreach ($returns AS $return)
      {
        $return->delete();
      }
    }

    //delete items + instances
    if ($items = $this->getCustomerOrderItems())
    {
      foreach ($items AS $item)
      {
        $item->delete();
      }
    }

    //delete invoice later.
    $invoice = $this->getInvoice();

    //delete payments
    if ($payments = $this->getPayments())
    {
      foreach ($payments AS $payment)
      {
        $payment->delete();
      }
    }

    parent::delete($con);

    if ($invoice)  
    {
      $invoice->delete();
    }


  }

}
