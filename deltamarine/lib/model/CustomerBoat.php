<?php

class CustomerBoat extends BaseCustomerBoat
{
  public function __toString()
  {
    $desc = $this->getName();
    if ($this->getMake() || $this->getModel())
    {
      $desc .= ' ('.
               ($this->getMake() ? $this->getMake().' ' :'').
               ($this->GetModel() ? $this->getModel() : '').
               ')';
    }

    return $desc;
  }

  public function getMakeModel()
  {
    $info = array();
    if ($this->getMake()) $info[] = $this->getMake();
    if ($this->getModel()) $info[] = $this->getModel();

    return join(' ', $info);
  }

  public function delete(PropelPDO $con = null)
  {
    if ($this->getWorkOrders())
    {
      return false;
    }
    else
    {
      parent::delete($con);
      return true;
    }
  }

}
