<?php

class PartLot extends BasePartLot
{
  public function outputQuantityReceived($with_units = true)
  {
    $amount = $this->getQuantityReceived();

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }

  public function outputQuantityRemaining($with_units = true)
  {
    $amount = $this->getQuantityRemaining();

    return round($amount,3).(($with_units && $this->getPartVariant()->getUnits()) ? ' '.$this->getPartVariant()->getUnits() : '');
  }


}
