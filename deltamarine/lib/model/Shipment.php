<?php

class Shipment extends BaseShipment
{
  public function set_invoice($q)
  {
      $this->setInvoiceId($q);
      $this->save();
  }  
}
