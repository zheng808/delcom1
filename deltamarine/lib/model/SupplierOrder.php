<?php

class SupplierOrder extends BaseSupplierOrder
{

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
    if (!$this->getSent())
    {
      return 'Waiting to be Sent';
    }
    if (!$this->getReceivedSome())
    {
      return 'Waiting to Receive';
    }
    if (!$this->getReceivedAll())
    {
      return 'Partially Received';
    }

    return 'Completed';
  }

  //gets the status of the order
  public function calculateReceivedAll()
  {
    $items = $this->getSupplierOrderItems();
    foreach ($items AS $item)
    {
      if ($item->getQuantityCompleted() < $item->getQuantityRequested())
      {
        return false;
      }
    }

    return true;
  }

  public function calculateReceivedSome()
  {
    $items = $this->getSupplierOrderItems();
    foreach ($items AS $item)
    {
      if ($item->getQuantityCompleted() > 0)
      {
        return true;
      }
    }

    return false;
  }

  public function hasPartLots()
  {
    $c = new Criteria();
    $c->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->getId());
    $c->addJoin(SupplierOrderItemPeer::ID, PartLotPeer::SUPPLIER_ORDER_ITEM_ID);

    return (PartLotPeer::doCount($c) > 0);
  }

  public function hasSpecialOrders()
  {
    $c = new Criteria();
    $c->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->getId());
    $c->addJoin(SupplierOrderItemPeer::ID, PartInstancePeer::SUPPLIER_ORDER_ITEM_ID);

    return (PartInstancePeer::doCount($c) > 0);
  }

  //finds an existing item to add the supplier order item to
  public function findExistingItem($variant_id)
  {
    $items = $this->getSupplierOrderItems();
    foreach ($items AS $item)
    {
      if ($item->getPartVariantId() == $variant_id)
      {
        return $item;
      }
    }

    return false;
  }

  public function getSupplierOrderItemsJoinPartInfo()
  {
    $c = new Criteria();
    if ($this->collSupplierOrderItems === null)
    {
      if ($this->isNew())
      {
        $this->collSupplierOrderItems = array();
      }
      else
      {
        $c->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->getId());
        $this->collSupplierOrderItems = SupplierOrderItemPeer::doSelectJoinPartAndOrderInfo($c);
      }
    }
    else
    {
      $c->add(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, $this->getId());
    }
    $this->lastSupplierOrderItemCriteria = $c;

    return $this->collSupplierOrderItems;


    return SupplierOrderItemPeer::doSelectJoinPartInfo($c);
  }



  public function delete(PropelPDO $con = null)
  {
    if ($items = $this->getSupplierOrderItems())
    {
      foreach ($items AS $item)
      {
        $item->delete();
      }
    }

    parent::delete($con);
  }
}
