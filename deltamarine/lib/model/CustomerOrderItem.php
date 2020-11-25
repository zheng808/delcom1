<?php

class CustomerOrderItem extends BaseCustomerOrderItem
{
  public function outputQuantityCompleted($with_units = true)
  {
    $amount = $this->getQuantityCompleted();

    return round($amount,3).(($with_units && $this->getPartInstance()->getPartVariant()->getUnits()) ? ' '.$this->getPartInstance()->getPartVariant()->getUnits() : '');
  }

  public function delete (PropelPDO $con = null)
  {
    $inst = $this->getPartInstance();

    if ($shipment_items = $this->getShipmentItems())
    {
        foreach ($shipment_items AS $shipment_item)
        {
            $shipment_item->delete();
        }
    }

    if ($return_items = $this->getCustomerReturnItems())
    {
      foreach ($return_items AS $return_item)
      {
        $return_item->delete();
      }
    }
    parent::delete($con);

    if ($inst)
    {
      $inst->delete();
    }
  }

 }
