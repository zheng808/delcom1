<?php

class WorkorderItem extends BaseWorkorderItemNestedSet
{
  public function __toString()
  {
    return $this->getLabel();
  }

  public function getColorCodeName()
  {
    $conv = WorkorderPeer::getItemColorCodesArray();
    return $conv[$this->getColorCode()];
  }

  public function getTaskColorCodeName()
  {
    $conv = WorkorderPeer::getTaskColorCodesArray();
    return $conv[$this->getTaskColorCode()];
  }

  public function getLabelWithLevel($spacer = '&nbsp;', $repeat_chars = 4)
  {
    return (str_repeat($spacer, $this->getLevel() * $repeat_chars).$this->getLabel());
  }

  public function getTotalPartEstimate($recursive = false)
  {
    $val = $this->getPartEstimate();

    //get parts instances that are estimates as well, including from descendants
    $sql = 'SELECT SUM(ROUND('.PartInstancePeer::QUANTITY.' * '.PartInstancePeer::UNIT_PRICE.'))'.
       ' FROM '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
       ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
       ' AND '.PartInstancePeer::ESTIMATE.' = 1';

    if ($recursive && $this->hasChildren())
    {
      $sql .= ' AND '.WorkorderItemPeer::LEFT_COL.' >= '.$this->getLeftValue().
              ' AND '.WorkorderItemPeer::RIGHT_COL.' <= '.$this->getRightValue().
              ' AND '.WorkorderItemPeer::SCOPE_COL.' = '.$this->getScopeIdValue();
    }
    else
    {
      $sql .= ' AND '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.$this->getId();
    }
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val += $row[0];
    }
    unset($con, $stmt, $row);

    return ($val > 0 ? $val : null);
  }

  public function getTotalLabourEstimate($recursive = false)
  {
    $val = $this->getLabourEstimate();

    //get timelogs that are estimates as well, including from descendants
    $sql = 'SELECT SUM('.TimelogPeer::COST.')'.
           ' FROM '.TimelogPeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' WHERE '.TimelogPeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.TimelogPeer::ESTIMATE.' = 1';

    if ($recursive && $this->hasChildren())
    {
      $sql .= ' AND '.WorkorderItemPeer::LEFT_COL.' >= '.$this->getLeftValue().
              ' AND '.WorkorderItemPeer::RIGHT_COL.' <= '.$this->getRightValue().
              ' AND '.WorkorderItemPeer::SCOPE_COL.' = '.$this->getScopeIdValue();
    }
    else 
    {
      $sql .= ' AND '.TimelogPeer::WORKORDER_ITEM_ID.' = '.$this->getId();
    }
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val += $row[0];
    }
    unset($con, $stmt, $row);

    return ($val > 0 ? $val : null);
  }

  public function getTotalOtherEstimate($recursive = false)
  {
    $val = $this->getOtherEstimate();

    //get expense instances that are estimates as well, including from descendants
    $sql = 'SELECT SUM('.WorkorderExpensePeer::PRICE.')'.
           ' FROM '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderExpensePeer::ESTIMATE.' = 1';

    if ($recursive && $this->hasChildren())
    {
      $sql .= ' AND '.WorkorderItemPeer::LEFT_COL.' >= '.$this->getLeftValue().
              ' AND '.WorkorderItemPeer::RIGHT_COL.' <= '.$this->getRightValue().
              ' AND '.WorkorderItemPeer::SCOPE_COL.' = '.$this->getScopeIdValue();
    }
    else 
    {
      $sql .= ' AND '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.$this->getId();
    }

    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val += $row[0];
    }
    unset($con, $stmt, $row);

    return ($val > 0 ? $val : null);
  }

  public function getTotalEstimate($recursive = false)
  {
    $val = $this->getTotalPartEstimate($recursive) + $this->getTotalLabourEstimate($recursive) + $this->getTotalOtherEstimate($recursive);

    return ($val > 0 ? $val : null);
  }

  public function getFeesActual($whom = null, $billables = null, $passed_billable = null)
  {
    $ret = $this->getTotalActual($whom, $billables, $passed_billable, true);
    return $ret;
  }

  //combine both items associated with this task, as well as any children task
  public function getTotalActual($whom = null, $billables = null, $passed_billable = null, $just_fees = false, $invoice_id = null)
  {
    if (!$billables) $billables = array();

    if ($whom && (isset($billables[$this->getId()]) || $passed_billable))
    {
      //need to check to see if this billable has any factor for the current 'whom' iteration, and if so, set the percentage amount
      if (isset($billables[$this->getId()]))
      {
        //select the new billable
        $selected = $billables[$this->getId()];
      }
      else
      {
        $selected = $passed_billable;
      }
      $part_pct = 0;
      $labour_pct = 0;
      if ((substr($whom, 0, 2) == 's_') && ($selected->getSupplierId() == substr($whom, 2)) && ($selected->getSupplierPartsPercent() > 0 || $selected->getSupplierLabourPercent() > 0))
      {
        $part_pct = $selected->getSupplierPartsPercent();
        $labour_pct = $selected->getSupplierLabourPercent();
      }
      else if ((substr($whom, 0, 2) == 'm_') && ($selected->getManufacturerId() == substr($whom, 2)) && ($selected->getManufacturerPartsPercent() > 0 || $selected->getManufacturerLabourPercent() > 0))
      {
        $part_pct = $selected->getManufacturerPartsPercent();
        $labour_pct = $selected->getManufacturerLabourPercent();
      }
      else if (($whom == 'inhouse') && ($selected->getInHousePartsPercent() > 0 || $selected->getInHouseLabourPercent() > 0))
      {
        $part_pct = $selected->getInHousePartsPercent();
        $labour_pct = $selected->getInHouseLabourPercent();
      }
      else if (($whom == 'cust') && ($selected->getCustomerPartsPercent() > 0 || $selected->getCustomerLabourPercent() > 0))
      {
        $part_pct = $selected->getCustomerPartsPercent();
        $labour_pct = $selected->getCustomerLabourPercent();
      }
      $part_pct = (float) $part_pct;
      $labour_pct = (float) $labour_pct;
      
    }
    else if ($whom == 'cust' || !$whom)
    {
      $part_pct = 100;
      $labour_pct = 100;
    }
    else
    {
      $part_pct = 0;
      $labour_pct = 0;
    }

    if ($just_fees)
    {
      $val = round(($part_pct/100) * $this->calculateActualFees(), 2);
    }
    else
    {
      if ($invoice_id === null)
      {
        //just get everything
        $part_amt = $this->getPartActual();
        $labour_amt = $this->getLabourActual();
        $other_amt = $this->getOtherActual();
      }
      else
      {
          //get the details for this task, for each of the three types
          $part_amt = $this->calculateActualPart($invoice_id);
          $labour_amt = $this->calculateActualLabour($invoice_id);
          $other_amt = $this->calculateActualOther($invoice_id);
      }
      $val = round(($part_pct/100) * $part_amt, 2) + round(($labour_pct/100) * ($labour_amt + $other_amt), 2);
      
    }

    $need_recurse = ($invoice_id !== null);
    //check to see if we can do a quick total or need to recurse (if there are any overriding payables of this item)
    foreach ($billables AS $billable)
    {
      if ($billable->getWorkorderItem()->getLeftValue() > $this->getLeftValue() && $billable->getWorkorderItem()->getRightValue() < $this->getRightValue())
      {
        $need_recurse = true;
      }
    }

    //we can just return now if there's no overriding payables and the percentage of this one is zero.
    //OR, if there are no children
    if (!$this->hasChildren() || ($part_pct == 0 && $labour_pct == 0 && (!$need_recurse)))
    {
      return ($val > 0 ? $val : null);
    }


    if ($need_recurse || $just_fees)
    {
      foreach ($this->getChildren() AS $child)
      {
        $val += $child->getTotalActual($whom, $billables, (isset($selected) && $selected->getRecurse() ? $selected : $passed_billable), $just_fees, $invoice_id);
      }
    }
    else
    {
      //sum all child nodes' actual values, checking for NULL values
      $sql = 'SELECT SUM(COALESCE('.WorkorderItemPeer::PART_ACTUAL.',0)), '.
                ' SUM(COALESCE('.WorkorderItemPeer::LABOUR_ACTUAL.',0) + COALESCE('.WorkorderItemPeer::OTHER_ACTUAL.',0))'.
             ' FROM '.WorkorderItemPeer::TABLE_NAME.
             ' WHERE '.WorkorderItemPeer::LEFT_COL.' > '.$this->getLeftValue().
             ' AND '.WorkorderItemPeer::RIGHT_COL.' < '.$this->getRightValue().
             ' AND '.WorkorderItemPeer::SCOPE_COL.' = '.$this->getScopeIdValue();
      
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      
      if ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $val += round(($part_pct / 100) * $row[0], 2);
        $val += round(($labour_pct / 100) * $row[1], 2);
      }
      unset($con, $stmt, $row);
    }
    return ($val > 0 ? $val : null);
  }

  //caled when a child labour item is updated - NOTE: NO TAX INCLUDED
  public function calculateActualLabour($invoice_id = null)
  {
    $val = null;
    $sql = 'SELECT SUM('.TimelogPeer::COST.')'.
           ' FROM '.TimelogPeer::TABLE_NAME.
           ' WHERE '.TimelogPeer::WORKORDER_ITEM_ID.' = '.$this->getId().
           ' AND '.TimelogPeer::APPROVED.' = 1'.
           ' AND '.TimelogPeer::ESTIMATE.' = 0';
    if ($invoice_id !== null)
    {
      $sql .= ' AND '.TimelogPeer::WORKORDER_INVOICE_ID.($invoice_id === 0 ? ' IS NULL' : ' = '.$invoice_id);
    }         
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val = $row[0];
    }
    unset($con, $stmt, $row);

    //check to see if we need to update and go up the tree
    if ($invoice_id === null && ($val != $this->getLabourActual()))
    {
      $this->setLabourActual($val);
      $this->save();
    }

    return $val;
  }

  //only used for calculation of fees for interim totals
  public function calculateActualFees()
  {
    $val = null;
    $sql = 'SELECT SUM(ROUND('.PartInstancePeer::QUANTITY.' * ('.PartInstancePeer::ENVIRO_LEVY.' + '.PartInstancePeer::BATTERY_LEVY.'), 2))'.
      ' FROM '.PartInstancePeer::TABLE_NAME.
      ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.$this->getId().
      ' AND '.PartInstancePeer::ALLOCATED.' = 1'.
      ' AND ('.PartInstancePeer::ENVIRO_LEVY.' > 0 OR '.PartInstancePeer::BATTERY_LEVY.' > 0)';
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val = $row[0];
    }
    unset($con, $stmt, $row);

    return $val;
  }

  //caled when a child part is updated - NOTE: NO TAX INCLUDED
  public function calculateActualPart($invoice_id = null)
  {
    $val = null;
    $sql = 'SELECT SUM(ROUND('.PartInstancePeer::QUANTITY.' * '.PartInstancePeer::UNIT_PRICE.', 2))'.
      ' FROM '.PartInstancePeer::TABLE_NAME.
      ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.$this->getId().
      ' AND '.PartInstancePeer::ALLOCATED.' = 1';
    if ($invoice_id !== null)
    {
      $sql .= ' AND '.PartInstancePeer::WORKORDER_INVOICE_ID.($invoice_id === 0 ? ' IS NULL' : ' = '.$invoice_id);
    }
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val = $row[0];
    }
    unset($con, $stmt, $row);

    //check to see if we need to update
    if ($invoice_id === null && ($val != $this->getPartActual()))
    {
      $this->setPartActual($val);
      $this->save();
    }

    return $val;
  }

  //caled when a child expense item is updated
  public function calculateActualOther($invoice_id = null)
  {
    $vall = null;
    $sql = 'SELECT SUM('.WorkorderExpensePeer::PRICE.')'.
           ' FROM '.WorkorderExpensePeer::TABLE_NAME.
           ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.$this->getId().
           ' AND '.WorkorderExpensePeer::INVOICE.' = 1';
    if ($invoice_id !== null)
    {
      $sql .= ' AND '.WorkorderExpensePeer::WORKORDER_INVOICE_ID.($invoice_id === 0 ? ' IS NULL' : ' = '.$invoice_id);
    }           
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $val = $row[0];
    }
    unset($con, $stmt, $row);

    //check to see if we need to update
    if ($invoice_id === null && ($val != $this->getOtherActual()))
    {
      $this->setOtherActual($val);
      $this->save();
    }

    return $val;
  }

  //set separator to false to return array
  public function getHierarchy($separator = ' &gt; ')
  {
    $ret = array();
    foreach ($this->getPath() AS $parent)
    {
      if (!$parent->isRoot() && ($parent->getId() != $this->getId()))
      {
        $ret[] = $parent->getLabel();
      }
    }

    if ($separator)
    {
      $ret = implode($separator, $ret);
    }
      
    return $ret;
  }
  
  // for parts, estimates and labour ($p, $e, $l), the options are as follows:
  // 1 = duplicate objects (not labour)
  // 2 = copy object totals to estimate
  // 3 = don't copy
  public function duplicate(Workorder $newwo, $parent = null, $p, $pest, $e, $eest, $l, $lest, $notes)
  {
      $new_task = new WorkorderItem();
      $new_task->setWorkorderId($newwo->getId());
      $new_task->setLabel($this->getLabel());
      $new_task->setCustomerNotes($notes);
      if ($parent)
      {
        $new_task->insertAsLastChildOf($parent);
      }
      $new_task->save();

      //PARTS
      $part_has_items = false;
      if ($p == 1 )
      {
        $thisparts = $this->getPartInstances();
        foreach ($thisparts AS $part)
        {
          $newpart = new PartInstance();
          $newpart->setWorkorderItemId($new_task->getId());
          $newpart->setPartVariantId($part->getPartVariantId());
          $newpart->setCustomName($part->getCustomName());
          $newpart->setQuantity($part->getQuantity());
          $newpart->setUnitPrice($part->getUnitPrice());
          $newpart->setUnitCost($part->getUnitCost());
          $newpart->setEstimate(true);
          $newpart->setTaxableGst($newwo->getGstExempt() ? 0 : sfConfig::get('app_gst_rate'));
          $newpart->setTaxablePst($newwo->getPstExempt() ? 0 : sfConfig::get('app_pst_rate'));
          $newpart->setEnviroLevy($part->getEnviroLevy());
          $newpart->setBatteryLevy($part->getBatteryLevy());
          $newpart->save();
          $part_has_items = true;
        }
        $new_task->calculateActualPart();
      }
      else
      {
        $new_task->setPartEstimate($new_task->getTotalPartEstimate(false) + $this->getPartActual());
        if ($this->getPartActual() > 0){
          $part_has_items = true;
        }
      }
      if (!$part_has_items)
      {
        $new_task->setPartEstimate($this->getPartEstimate());
      }

      //EXPENSES
      $expense_has_items = false;
      if ($e == 1)
      {
        $thisexps = $this->getWorkorderExpenses();
        foreach ($thisexps AS $thisexp)
        {
          $new_exp = new WorkorderExpense();
          $new_exp->setWorkorderItemId($new_task->getId());
          $new_exp->setLabel($thisexp->getLabel());
          $new_exp->setCost($thisexp->getCost());
          $new_exp->setPrice($thisexp->getPrice());
          $new_exp->setEstimate(true);
          $new_exp->setTaxableGst($newwo->getGstExempt() ? 0 : sfConfig::get('app_gst_rate'));
          $new_exp->setTaxablePst($newwo->getPstExempt() ? 0 : sfConfig::get('app_pst_rate'));
          $new_exp->save();
          $expense_has_items = true;
        }
        $new_task->calculateActualOther();
      }
      else if ($e == 2)
      {
        $new_task->setOtherEstimate($new_task->getTotalOtherEstimate(false) + $this->getOtherActual());
        if ($this->getOtherActual() > 0)
        {
          $expense_has_items = true;
        }
      }
      if (!$expense_has_items)
      {
        $new_task->setOtherEstimate($this->getOtherEstimate());
      }

      //LABOUR
      $labour_has_items = false;
      if ($l == 2)
      {
          $new_task->setLabourEstimate($new_task->getLabourEstimate() + $this->getLabourActual());
          if ($this->getLabourActual() > 0)
          {
            $labour_has_items = true;
          }
      }
      if (!$labour_has_items)
      {
        $new_task->setLabourEstimate($this->getLabourEstimate());
      }

      $new_task->save();

      //do things recursively:
      if ($this->hasChildren())
      {
        foreach ($this->getChildren() AS $child)
        {
           $child->duplicate($newwo, $new_task, $p, $pest, $e, $eest, $l, $lest);
        }
      }

      return $new_task;
  }

  public function delete (PropelPDO $con = null)
  {
    //delete parts (and remove from backorders)
    if ($parts = $this->getPartInstances())
    {
      foreach ($parts AS $part)
      {
        $part->delete();
      }
    }

    //delete expenses
    $c = new Criteria();
    $c->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $this->getId());
    WorkorderExpensePeer::doDelete($c);

    //delete timelogs
    $c = new Criteria();
    $c->add(TimelogPeer::WORKORDER_ITEM_ID, $this->getId());
    TimelogPeer::doDelete($c);

    //delete billables
    $c = new Criteria();
    $c->add(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, $this->getId());
    WorkorderItemBillablePeer::doDelete($c);

    //delete photos
    if ($photos = $this->getWorkorderItemPhotos())
    {
      foreach ($photos AS $photo)
      {
        $photo->delete();
      }
    }

    //delete files
    if ($files = $this->getWorkorderItemFiles())
    {
      foreach ($files AS $file)
      {
        $file->delete();
      }
    }

    //remove children
    $children = $this->getChildren();
    foreach ($children AS $child)
    {
      $child->delete();
    }

    parent::delete($con);
  }

}
