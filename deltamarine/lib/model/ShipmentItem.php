<?php

class ShipmentItem extends BaseShipmentItem
{
  public function set_shipment($q)
  {
      $this->setShipmentId($q);
      $this->save();
  }  
}
