<?php

class Subpart extends BaseSubpart
{
  public function outputChildQuantity($with_units = true)
  {
    $amount = $this->getChildQuantity();
    $amount = (round($amount) == $amount ? round($amount) : $amount);

    return $amount.(($with_units && $this->getPartVariantRelatedByChildId()->getUnits()) ? ' '.$this->getPartVariantRelatedByChildId()->getUnits() : '');
  }

}
