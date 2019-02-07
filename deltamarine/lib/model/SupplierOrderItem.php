<?php

class SupplierOrderItem extends BaseSupplierOrderItem
{
  public function outputQuantityRemaining($with_units = true)
  {
    $amount = $this->getQuantityRequested() - $this->getQuantityCompleted();

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }
  
  public function outputQuantityRequested($with_units = true)
  {
    $amount = $this->getQuantityRequested();

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }
  
  public function outputQuantityCompleted($with_units = true)
  {
    $amount = $this->getQuantityCompleted();

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }

  public function getQuantityUnreserved()
  {
    $available = $this->getQuantityRequested() - $this->getQuantityCompleted();
    $instances = $this->getPartInstances();
    foreach ($instances AS $instance)
    {
      //subtract the number available by the INCOMPLETE quantity
      if ($instance->getAllocated() && !$instance->getDelivered())
      {
        $inst_qty = $instance->getQuantity();
        if ($orderitems = $instance->getCustomerOrderItems())
        {
          foreach ($orderitems AS $orderitem) //should only be one at most
          {
            $inst_qty -= $orderitem->getQuantityCompleted();
          }
        }
        $available -= $inst_qty;
      }
    }

    return $available;
  }

  public function getQuantityReserved()
  {
    return ($this->getQuantityRequested() - $this->getQuantityUnreserved());
  }

  public function save(PropelPDO $con = null)
  {
    parent::save($con);

    $this->getPartVariant()->calculateCurrentOnOrder();
  }

  public function hasSpecialOrders()
  {
    return ($this->countPartInstances() > 0);
  }

  public function hasPartLots()
  {
    return ($this->countPartLots() > 0);
  }

  public function delete(PropelPDO $con = null)
  {
    $variant = $this->getPartVariant();
    parent::delete($con);

    $variant->calculateCurrentOnOrder();
  }

}
