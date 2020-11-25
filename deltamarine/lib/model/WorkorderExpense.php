<?php

class WorkorderExpense extends BaseWorkorderExpense
{

  //calculates subtotal (before taxes and fees)
  public function getSubtotal()
  {
    return $this->getPrice();
  }

  public function getHstTotal($round = false)
  {
    if ($this->getTaxableHst() > 0)
    {
      //round base amount before calculating to reduce rounding errors
      $amt = round($this->getSubtotal(),2) * $this->getTaxableHst()/100; 
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getPstTotal($round = false)
  {
    if ($this->getTaxablePst() > 0)
    {
      //round base amount before calculating to reduce rounding errors
      $amt = round($this->getSubtotal(),2) * $this->getTaxablePst()/100; 
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getGstTotal($round = false)
  {
    if ($this->getTaxableGst() > 0)
    {
      //round base amount before calculating to reduce rounding errors
      $amt = round($this->getSubtotal(),2) * $this->getTaxableGst()/100; 
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }


  //including all fees and taxes
  public function getTotal()
  {
    return ($this->getSubtotal() + $this->getHstTotal() + $this->getPstTotal() + $this->getGstTotal());
  }


  public function save (PropelPDO $con = null)
  {
    parent::save($con);

    //recalculate actual expense amounts of parent task(s)
    if ($item = $this->getWorkorderItem())
    {
      $item->calculateActualOther();
    }
  }

  public function delete (PropelPDO $con = null)
  {
    $item = $this->getWorkorderItem();

    parent::delete($con);

    if ($item)
    {
      $item->calculateActualOther();
    }

  }

}
