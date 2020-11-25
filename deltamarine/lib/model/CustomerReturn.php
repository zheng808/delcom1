<?php

class CustomerReturn extends BaseCustomerReturn
{

  public function getCustomerReturnItemsJoinPartInfo()
  {
    $c = new Criteria();
    if ($this->collCustomerReturnItems === null)
    {
      if ($this->isNew())
      {
        $this->collCustomerReturnItems = array();
      }
      else
      {
        $c->add(CustomerReturnItemPeer::CUSTOMER_RETURN_ID, $this->getId());
        $this->collCustomerReturnItems = CustomerReturnItemPeer::doSelectJoinPartInfo($c);
      }
    }
    else
    {
      $c->add(CustomerReturnItemPeer::CUSTOMER_RETURN_ID, $this->getId());
    }
    $this->lastCustomerReturnItemCriteria = $c;

    return $this->collCustomerReturnItems;
  }

  //gets the subtotal (no taxes, fees) for all items
  public function getTotals($type = 'total')
  {
    $total = 0;
    foreach ($this->getCustomerReturnItemsJoinPartInfo() AS $item)
    {
      switch ($type){
      case 'subtotal':
        $total += $item->getPartInstance()->getSubtotal(false);
        break;
      case 'battery':
        $total += $item->getPartInstance()->getBatteryLevyTotal(false);
        break;
      case 'enviro':
        $total += $item->getPartInstance()->getEnviroLevyTotal(false);
        break;
      case 'hst':
        $total += $item->getPartInstance()->getHstTotal(false);
        break;
      case 'pst':
        $total += $item->getPartInstance()->getPstTotal(false);
        break;
      case 'gst':
        $total += $item->getPartInstance()->getGstTotal(false);
        break;
      case 'total':
        $total += $item->getPartInstance()->getTotal(false);
        break;
      }
    }

    return $total;
  }

  /*
   * creates or edits a return invoice
   */
  public function generateInvoice()
  {
    if (!($invoice = $this->getInvoice()))
    {
      $invoice = new Invoice();
      $invoice->setCustomerId($this->getCustomerOrder()->getCustomerId());
    }

    //net = false below. we want quantities BEFORE returns
    $invoice->setSubtotal($this->getTotals('subtotal'));
    $invoice->setHst($this->getTotals('hst'));
    $invoice->setPst($this->getTotals('pst'));
    $invoice->setGst($this->getTotals('gst'));
    $invoice->setEnviroLevy($this->getTotals('enviro'));
    $invoice->setBatteryLevy($this->getTotals('battery'));
    $invoice->setTotal($this->getTotals('total'));

    $invoice->setIssuedDate(time());
    $invoice->setPayableDate(time());

    if (!$this->getInvoiceId())
    {
      $this->setInvoice($invoice);
    }

    $invoice->save();
    $this->save();
  }

  public function delete (PropelPDO $con = null)
  {
    if ($items = $this->getCustomerReturnItems())
    {
      foreach ($items AS $item)
      {
        $item->delete();
      }
    }
    if ($invoice = $this->getInvoice())
    {
      $invoice->delete();
    }
    parent::delete($con);
  }

}
