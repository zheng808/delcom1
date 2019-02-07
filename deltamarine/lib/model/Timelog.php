<?php

class Timelog extends BaseTimelog
{
  /*
   * generates a single text of the current status
   */
  public function getStatus()
  {
    if ($this->getAdminFlagged())
    {
      return 'Flagged';
    }
    else if ($this->getApproved())
    {
      return 'Approved';
    }
    else
    {
      return 'Unapproved';
    }
  }


  /*
   * converts hours into hours and minutes
   * for displaying in various places
   */
  public function getBillableHoursAndMinutes() { return $this->getHoursAndMinutes(false); }
  public function getPayrollHoursAndMinutes()  { return $this->getHoursAndMinutes(true);  }
  public function getHoursAndMinutes($payroll)
  {
    $amt = ($payroll ? $this->getPayrollHours() : $this->getBillableHours());
    $output = '';
    if ($amt > 0)
    {
      if ($amt >= 1)
      {
        $hours = floor($amt);
        $output .= $hours.'h';
        $amt = $amt-$hours;
      }
      $mins = $amt*60;
      if (round($mins) >= 1)
      {
        $output .= ($output != '' ? ' ' : '').round($mins).'m';
      }
    }
    else
    {
      $output = '0h';
    }

    return $output;
  }

  /*
   * displays work order boat name
   * for listing in datagrid
   */
  public function getWorkorderBoat()
  {
    if ($woi = $this->getWorkorderItem())
    {
      if ($wo = $woi->getWorkorder())
      {
        if ($boat = $wo->getCustomerBoat())
        {
          return $boat->getName();
        }
      }
    }

    return '';
  }

  public function getWorkorderCustomerName()
  {
    if ($woi = $this->getWorkorderItem())
    {
      if ($wo = $woi->getWorkorder())
      {
        if ($cust = $wo->getCustomer())
        {
          return $cust->getName();
        }
      }
    }

    return '';
  }

  /*
   * returns workorder id if present
   */
  public function getWorkorderId()
  {
    if ($woi = $this->getWorkorderItem())
    {
      return $woi->getWorkorderId();
    }

    return '';
  }

  public function getWorkorderSummary()
  {
    if (($wo = $this->getWorkorderId()) && ($boat = $this->getWorkorderBoat()))
    {
      return '#'.$wo . ' - '. $boat;
    }

    return '';
  }

  /*
   * returns workorder item description if present
   */
  public function getWorkorderItemName()
  {
    if ($woi = $this->getWorkorderItem())
    {
      return ($woi->getLabel() ? $woi->getLabel() : 'Unnamed Task');
    }
    return '';
  }

  /*
   * calculates the hours field based on start and end date
   */
  public function calculateHours()
  {
    if ($this->getStartTime() && $this->getEndTime())
    {
      $this->setHours(round(($this->getEndTime('U') - $this->getStartTime('U')) / 3600, 2));
    }
  }

  /*
   * calculates the overall cost for billable items - note: this is labour cost, not employee cost
   */
  public function calculateCost()
  {
    if ($this->getLabourType() && $this->getBillableHours())
    {
      $this->setCost($this->getLabourType()->getHourlyRate() * $this->getBillableHours());
      $this->setRate($this->getLabourType()->getHourlyRate());
    }
    else if ($this->getCustomLabel())
    {
      $this->setCost($this->getRate() * $this->getBillableHours());
    }
  }

  //calculates subtotal (before taxes and fees)
  public function getSubtotal()
  {
    return $this->getCost();
  }

  public function getHstTotal($round = false)
  {
    //HST is charged on the subtotal PLUS enviro and battery fees
    //(see http://www.sbr.gov.bc.ca/documents_library/bulletins/sst_015.pdf)
    if ($this->getTaxableHst() > 0)
    {
      $amt = $this->getSubtotal() * $this->getTaxableHst()/100;
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getPstTotal($round = false)
  {
    if ($this->getTaxablePst() > 0)
    {
      $amt = $this->getSubtotal() * $this->getTaxablePst()/100;
      return ($round ? round($amt,2) : $amt);
    }
    else return 0;
  }

  public function getGstTotal($round = false)
  {
    if ($this->getTaxableGst() > 0)
    {
      $amt = $this->getSubtotal() * $this->getTaxableGst()/100;
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
    $this->calculateCost();
    parent::save($con);

    //recalculate actual labour amounts of parent task(s)
    if ($item = $this->getWorkorderItem())
    {
      $item->calculateActualLabour();
    }
  }

  public function delete (PropelPDO $con = null)
  {
    $item = $this->getWorkorderItem();

    parent::delete($con);

    if ($item)
    {
      $item->calculateActualLabour();
    }

  }

}
