<?php

class WorkorderPayment extends BaseWorkorderPayment
{

  public function getWhomIndex()
  {
    if ($this->getSupplierId())
    {
      return 's_'.$this->getSupplierId();
    }
    else if ($this->getManufacturerId())
    {
      return 'm_'.$this->getManufacturerId();
    }
    else
    {
      return 'cust';
    }
  }

  public function getWhomObject()
  {
    if ($this->getSupplierId())
    {
      return $this->getSupplier;
    }
    else if ($this->getManufacturerId())
    {
      return $this->getManufacturer();
    }
    else
    {
      return $this->getWorkorder()->getCustomer();
    }
  }


  public function getWhomDescription()
  {
    if ($this->getSupplierId())
    {
      return $this->getSupplier()->getName().' (Supplier)';
    }
    else if ($this->getManufacturerId())
    {
      return $this->getManufacturer()->getName().' (Manufacturer)';
    }
    else
    {
      return 'Customer';
    }
  }
}
