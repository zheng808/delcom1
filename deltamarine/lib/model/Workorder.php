<?php

class Workorder extends BaseWorkorder
{
  public function getRootItem() {
    return WorkorderItemPeer::retrieveRoot($this->getId());
  }

  public function isEstimate()
  {
    return ($this->getStatus() == 'Estimate');
  }

  public function isInProgress()
  {
    return ($this->getStatus() == 'In Progress');
  }

  public function getItemsProgress() 
  {
    $sql = 'SELECT SUM('.WorkorderItemPeer::COMPLETED.'), COUNT('.WorkorderItemPeer::ID.')'.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      return array($row[0], $row[1] - 1); //subtract root node
    }
    
    return array(0,0);
  }

  /*
   * outputs an array, indexed by workorder_item_id, of workorder items for a given workorder that have timelogs
   */
  public function getItemsListWithTimelogs()
  {
    $result = array();

    $sql = 'SELECT DISTINCT '.WorkorderItemPeer::ID.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.', '.TimelogPeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId().
           ' AND '.WorkorderItemPeer::ID.' = '.TimelogPeer::WORKORDER_ITEM_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = 1;
    }

    return $result;
  }

  /*
   * outputs an array, indexed by workorder_item_id, of workorder items for a given workorder that have parts
   */
  public function getItemsListWithParts()
  {
    $result = array();

    $sql = 'SELECT DISTINCT '.WorkorderItemPeer::ID.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.', '.PartInstancePeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId().
           ' AND '.WorkorderItemPeer::ID.' = '.PartInstancePeer::WORKORDER_ITEM_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = 1;
    }

    return $result;
  }

  /*
   * outputs an array, indexed by workorder_item_id, of workorder items for a given workorder that have expenses
   */
  public function getItemsListWithExpenses()
  {
    $result = array();

    $sql = 'SELECT DISTINCT '.WorkorderItemPeer::ID.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.', '.WorkorderExpensePeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId().
           ' AND '.WorkorderItemPeer::ID.' = '.WorkorderExpensePeer::WORKORDER_ITEM_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = 1;
    }

    return $result;
  }

  /*
   * outputs an array, indexed by workorder_item_id, of workorder items for a given workorder that have photos
   */
  public function getItemsListWithPhotos()
  {
    $result = array();

    $sql = 'SELECT DISTINCT '.WorkorderItemPeer::ID.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.', '.WorkorderItemPhotoPeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId().
           ' AND '.WorkorderItemPeer::ID.' = '.WorkorderItemPhotoPeer::WORKORDER_ITEM_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = 1;
    }

    return $result;
  }

  /*
   * outputs an array, indexed by workorder_item_id, of workorder items for a given workorder that have files
   */
  public function getItemsListWithFiles()
  {
    $result = array();

    $sql = 'SELECT DISTINCT '.WorkorderItemPeer::ID.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.', '.WorkorderItemFilePeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::WORKORDER_ID.' = '.$this->getId().
           ' AND '.WorkorderItemPeer::ID.' = '.WorkorderItemFilePeer::WORKORDER_ITEM_ID;
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[$row[0]] = 1;
    }

    return $result;
  }

  public function getAllParts()
  {
    $c = new Criteria();
    $c->addJoin(WorkorderItemPeer::ID, PartInstancePeer::WORKORDER_ITEM_ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    return PartInstancePeer::doSelectJoinPartVariant($c);
  }


  public function canDelete()
  {
    //check for delivered parts
    $c = new Criteria();
    $c->addJoin(PartInstancePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    $c->add(PartInstancePeer::DELIVERED, true);
    if (PartInstancePeer::doSelectOne($c))
    {
      return false;
    }

    //check for expenses
    $c = new Criteria();
    $c->addJoin(WorkorderExpensePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    if (WorkorderExpensePeer::doSelectOne($c))
    {
      return false;
    }

    //check for timelogs
    $c = new Criteria();
    $c->addJoin(TimelogPeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    if (TimelogPeer::doSelectOne($c))
    {
      return false;
    }

    //check for payments
    if ($this->countPayments() > 0)
    {
      return false;
    }

    return true;
  }

  public function save (PropelPDO $con = null)
  {
    $new = $this->isNew();
    parent::save();

    if ($new)
    {
      //create root item
      $root = new WorkorderItem();
      $root->setWorkorder($this);
      $root->makeRoot();
      $root->save(); 

      //add default tasks as described in app.yml
      if ($tasks = sfConfig::get('app_workorder_default_tasks'))
      {
        foreach ($tasks AS $task)
        {
          $item = new WorkorderItem();
          $item->setLabel($task);
          $item->insertAsLastChildOf($root);
          $item->save();
        }
      }
    }
  }

  public function chargeAllHst()
  {
    $this->removeAllHst(false);
  }

  public function removeAllHst($remove = true)
  {
    //add/remove all hst from workorder expenses
    $sql = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.WorkorderExpensePeer::TAXABLE_HST.' = '.
               ($remove ? 0 : sfConfig::get('app_hst_rate')).
           ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //add/remove all hst from workorder parts
    $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.PartInstancePeer::TAXABLE_HST.' = '.
               ($remove ? 0 : sfConfig::get('app_hst_rate')).
           ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //add/remove all hst from workorder timelogs
    $sql = 'UPDATE '.TimelogPeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.TimelogPeer::TAXABLE_HST.' = '.
               ($remove ? 0 : sfConfig::get('app_hst_rate')).
           ' WHERE '.TimelogPeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute(); 
  }

  public function chargeAllPst()
  {
    $this->removeAllPst(false);
  }//chargeAllPST()------------------------------------------------------------

  public function removeAllPst($remove = true)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START removeAllPst======================';
      sfContext::getInstance()->getLogger()->info($message);
    }  

    //add/remove all pst from workorder expenses
    $sql = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.WorkorderExpensePeer::TAXABLE_PST.' = '.
               ($remove ? 0 : sfConfig::get('app_pst_rate')).
           ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //ensure pst rates are still set for pst override workorder expenses
    if ($remove) {
      $sql = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
            ' SET '.WorkorderExpensePeer::TAXABLE_PST.' = '.sfConfig::get('app_pst_rate').
            ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
            ' AND '.WorkorderExpensePeer::PST_OVERRIDE_FLG.' = \'Y\' '.
            ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();   
    } 

    //add/remove all pst from workorder parts
    $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.PartInstancePeer::TAXABLE_PST.' = '.
               ($remove ? 0 : sfConfig::get('app_pst_rate')).
           ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //ensure pst rates are still set for pst override workorder parts
    if ($remove) {
      $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
             ' SET '.PartInstancePeer::TAXABLE_PST.' = '.sfConfig::get('app_pst_rate').
             ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
             ' AND '.PartInstancePeer::PST_OVERRIDE_FLG.' = \'Y\' '.
             ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
    }

    //ensure pst rates are still set for enviro override workorder parts
    if ($remove) {
      $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
              ' SET '.PartInstancePeer::ENVIRO_TAXABLE_FLG.' = \'Y\' '.
              ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
              ' AND '.PartInstancePeer::ENVIRO_OVERRIDE_FLG.' = \'Y\' '.
              ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
    }

    //add/remove all pst from workorder timelogs
    $sql = 'UPDATE '.TimelogPeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.TimelogPeer::TAXABLE_PST.' = '.
               ($remove ? 0 : sfConfig::get('app_pst_rate')).
           ' WHERE '.TimelogPeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute(); 

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE removeAllPst======================';
      sfContext::getInstance()->getLogger()->info($message);
    } 

  }//removeAllPst()--------------------------------------------------

  public function chargeAllGst()
  {
    $this->removeAllGst(false);
  }

  public function removeAllGst($remove = true)
  {
    //add/remove all gst from workorder expenses
    $sql = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.WorkorderExpensePeer::TAXABLE_GST.' = '.
               ($remove ? 0 : sfConfig::get('app_gst_rate')).
           ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //ensure gst rates are still set for pst override workorder expenses
    if ($remove) {
      $sql = 'UPDATE '.WorkorderExpensePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
            ' SET '.WorkorderExpensePeer::TAXABLE_GST.' = '.sfConfig::get('app_gst_rate').
            ' WHERE '.WorkorderExpensePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
            ' AND '.WorkorderExpensePeer::GST_OVERRIDE_FLG.' = \'Y\' '.
            ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();   
    } 

    //add/remove all gst from workorder parts
    $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.PartInstancePeer::TAXABLE_GST.' = '.
               ($remove ? 0 : sfConfig::get('app_gst_rate')).
           ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    //ensure gst rates are still set for pst override workorder parts
    if ($remove) {
      $sql = 'UPDATE '.PartInstancePeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
              ' SET '.PartInstancePeer::TAXABLE_GST.' = '.sfConfig::get('app_gst_rate').
              ' WHERE '.PartInstancePeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
              ' AND '.PartInstancePeer::GST_OVERRIDE_FLG.' = \'Y\' '.
              ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
    }

    //add/remove all gst from workorder timelogs
    $sql = 'UPDATE '.TimelogPeer::TABLE_NAME.', '.WorkorderItemPeer::TABLE_NAME.
           ' SET '.TimelogPeer::TAXABLE_GST.' = '.
               ($remove ? 0 : sfConfig::get('app_gst_rate')).
           ' WHERE '.TimelogPeer::WORKORDER_ITEM_ID.' = '.WorkorderItemPeer::ID.
           ' AND '.WorkorderItemPeer::WORKORDER_ID.' = '. $this->getId();
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute(); 
  }

  
  public function getWorkorderPayments($criteria = null, PropelPDO $con = null)
  {
    if ($criteria === null) 
    {
      $criteria = new Criteria(WorkorderPeer::DATABASE_NAME);
      $criteria->addAscendingOrderByColumn(WorkorderPaymentPeer::CREATED_AT);
    }

    return parent::getWorkorderPayments($criteria, $con);
  }

  public function getPayers()
  {
    //array is index=>(0 => label, 1 => name, 2 => charge_hst, 3 => charge_pst, 4 => charge_gst)
    $payers_array = array('cust' => array('Customer', $this->getCustomer()->getName(), ($this->getHstExempt() ? 0 : 1), ($this->getPstExempt() ? 0 : 1), ($this->getGstExempt() ? 0 : 1)));

    //check for workorder_item_billable records
    $c = new Criteria();
    $c1 = $c->getNewCriterion(WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT, 100, Criteria::NOT_EQUAL);
    $c2 = $c->getNewCriterion(WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT, 100, Criteria::NOT_EQUAL);
    $c1->addor($c2);
    $c->addAnd($c1);
    $c->addJoin(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    if ($billables = WorkorderItemBillablePeer::doSelect($c))
    {
      foreach ($billables AS $billable)
      {
        if ($billable->getSupplierId() && ($billable->getSupplierPartsPercent() > 0 || $billable->getSupplierLabourPercent() > 0))
        {
          $key = 's_'.$billable->getSupplierId();
          $addr = $billable->getSupplier()->getWfCRM()->getWfCRMAddresss();
          $taxable = ($addr && isset($addr[0]) && $addr[0]->getCountry() != '' && $addr[0]->getCountry() != 'CA' ? 0 : 1);
          if (!isset($payers_array[$key])) $payers_array[$key] = array('Supplier', $billable->getSupplier()->getName(), $taxable, $taxable, $taxable);
        }
        if ($billable->getManufacturerId() && ($billable->getManufacturerPartsPercent() > 0 || $billable->getManufacturerLabourPercent() > 0))
        {
          $key = 'm_'.$billable->getManufacturerId();
          $addr = $billable->getManufacturer()->getWfCRM()->getWfCRMAddresss();
          $taxable = ($addr && isset($addr[0]) && $addr[0]->getCountry() != '' && $addr[0]->getCountry() != 'CA' ? 0 : 1);
          if (!isset($payers_array[$key])) $payers_array[$key] = array('Manufacturer', $billable->getManufacturer()->getName(), $taxable, $taxable, $taxable);
        }
        if ($billable->getInHousePartsPercent() > 0 || $billable->getInHouseLabourPercent() > 0)
        {
          $key = 'inhouse';
          if (!isset($payers_array['inhouse'])) $payers_array[$key] = array('Discounts', 'Delta Marine', 0, 0, 0);
        }
      }
    }

    return $payers_array;
  }

  ///sorts payments by payer
  public function getPaymentsByPayer()
  {
    $output = array();
    $payments = $this->getWorkorderPayments();
    foreach ($payments AS $payment)
    {
      $wid = $payment->getWhomIndex();
      if (!isset($output[$wid]))
      {
        $output[$wid]['payments'] = array();
        $output[$wid]['obj'] = $payment->getWhomObject();
      }
      $output[$payment->getWhomIndex()]['payments'][] = $payment;
    }

    return $output;
  }

  public function getTotalsByPayer($invoice_id = null)
  {
    $totals = array();
    $payers_array = array('cust' => array('amount' => '0'));
    $billable_array = array();
    $root = $this->getRootItem();

    //check for workorder_item_billable records
    $c = new Criteria();
    $c1 = $c->getNewCriterion(WorkorderItemBillablePeer::CUSTOMER_PARTS_PERCENT, 100, Criteria::NOT_EQUAL);
    $c2 = $c->getNewCriterion(WorkorderItemBillablePeer::CUSTOMER_LABOUR_PERCENT, 100, Criteria::NOT_EQUAL);
    $c1->addor($c2);
    $c->addAnd($c1);
    $c->addJoin(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(WorkorderItemPeer::WORKORDER_ID, $this->getId());
    if ($billables = WorkorderItemBillablePeer::doSelect($c))
    {
      foreach ($billables AS $billable)
      {
        if ($billable->getSupplierId() && ($billable->getSupplierPartsPercent() > 0 || $billable->getSupplierLabourPercent() > 0))
        {
          $key = 's_'.$billable->getSupplierId();
          if (!isset($payers_array[$key])) $payers_array[$key] = array('amount' => 0, 'obj' => $billable->getSupplier());
        }
        if ($billable->getManufacturerId() && ($billable->getManufacturerPartsPercent() > 0 || $billable->getManufacturerLabourPercent() > 0))
        {
          $key = 'm_'.$billable->getManufacturerId();
          if (!isset($payers_array[$key])) $payers_array[$key] = array('amount' => 0, 'obj' => $billable->getManufacturer());
        }
        if ($billable->getInHousePartsPercent() > 0 || $billable->getInHouseLabourPercent() > 0)
        {
          $key = 'inhouse';
          if (!isset($payers_array['inhouse'])) $payers_array['inhouse'] = array('amount' => 0);
        }
        $billable_array[$billable->getWorkorderItemId()] = $billable;
      } 
    }
    unset($billables);
    foreach ($payers_array AS $key => $data)
    {
      $payers_array[$key]['amount'] = $root->getTotalActual($key, $billable_array, null, false, $invoice_id);
      $payers_array[$key]['fees'] = $root->getFeesActual($key, $billable_array, null, false, $invoice_id);
    }

    return $payers_array;
  }

  public function getGrandTotal()
  {
    $val = 0;
    //sum all child nodes values, checking for NULL values
    $sql = 'SELECT SUM(COALESCE('.WorkorderItemPeer::PART_ACTUAL.',0)'.
              ' + COALESCE('.WorkorderItemPeer::LABOUR_ACTUAL.',0)'.
              ' + COALESCE('.WorkorderItemPeer::OTHER_ACTUAL.',0))'.
           ' FROM '.WorkorderItemPeer::TABLE_NAME.
           ' WHERE '.WorkorderItemPeer::SCOPE_COL.' = '.$this->getScopeIdValue();
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

  public function baseDetailsTree($node = null)
  {
      $output_root = false;
      if (!$node)
      {
        $node = $this->getRootItem();
        $output_root = true;
      }

      //GET ALL DESCENDENTS AND FIGURE OUT WHICH HAVE TIMELOGS, EXPENSES, AND PARTS
      $have_timelogs = $this->getItemsListWithTimelogs();
      $have_parts    = $this->getItemsListWithParts();
      $have_expenses = $this->getItemsListWithExpenses();
      $have_photos   = $this->getItemsListWithPhotos();
      $have_files    = $this->getItemsListWithFiles();

      //OUTPUT DESCENDANTS
      $node->getDescendants();
      $output = $this->_output_treedata($node, $have_timelogs, $have_parts, $have_expenses, $have_photos, $have_files, false);

      if ($output_root)
      {
        $output = array('text' => '-- Top Level Item--', 'id' => $this->getRootItem, 'expanded' => true, 'children' => $output);
      }

      return $output;
  }

  private function _output_treedata($node, $have_timelogs, $have_parts, $have_expenses, $have_photos, $have_files, $prefix)
  {
      $children = $node->getChildren();
      $output = array();
      if (!$prefix) $prefix = array();

      $this_number = 0;
      foreach ($children AS $child)
      {
        $status = '';
        $this_number ++;
        if (trim($child->getInternalNotes()) != '')
        {
          $status .= '<img src="/images/silkicon/page_red.png" title="Admin Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
        }
        if (trim($child->getCustomerNotes()) != '')
        {
          $status .= '<img src="/images/silkicon/page_green.png" title="Customer Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
        }
        if (isset($have_photos[$child->getId()]))
        {
          $status .= '<img src="/images/silkicon/pictures.png" title="Photos Attached" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
        }
        if (isset($have_files[$child->getId()]))
        {
          $status .= '<img arc="/images/silkicon/page_white_stack.png" title="Files Attached" width="14" height="14" style="float: left; margin-right: 3px;" />';
        }

        $subprefix = $prefix;
        $subprefix[] = $this_number;
        $numbering = implode('.', $subprefix);
        $childarray = array('id' => $child->getId(),
                            'text' => '<span class="blocky bl-'.strtolower($child->getColorCodeName()).'">Task '.$numbering.'</span> '.'<span class="blocky bl-'.strtolower($child->getTaskColorCodeName()).'">'.$child->getLabel(),
                            'iconCls' => $child->getCompleted() ? 'folder-done' : 'folder',
                            'estimate' => $child->getTotalEstimate(true),
                            'actual' => $child->getTotalActual(),
                            'leaf' => false,
                            'info' => $status,
                            'children' => array()
                          );
        //add child nodes
        if ($child->hasChildren())
        {
          $childarray['children'] = $this->_output_treedata($child, $have_timelogs, $have_parts, $have_expenses, $have_photos, $have_files, $subprefix);
        }

        //add labour, parts, expenses
        $has_timelogs = isset($have_timelogs[$child->getId()]);
        $has_parts    = isset($have_parts[$child->getId()]);
        $has_expenses = isset($have_expenses[$child->getId()]);
        $estimated_timelogs = $child->getTotalLabourEstimate(false);
        $estimated_parts    = $child->getTotalPartEstimate(false);
        $estimated_expenses = $child->getTotalOtherEstimate(false);
        $is_empty = !($has_timelogs || $has_parts || $has_expenses || $estimated_timelogs > 0 || $estimated_parts > 0 || $estimated_expenses > 0 || $child->hasChildren());

        if ($has_expenses || $estimated_expenses > 0)
        {
          array_unshift($childarray['children'], array('id' => 'expense-'.$child->getId(),
                                                       'text' => 'Expenses',
                                                       'cls' => 'unselectable_node',
                                                       'leaf' => !$has_expenses,
                                                       'draggable' => false,
                                                       'allowDrop' => false,
                                                       'estimate' => $estimated_expenses,
                                                       'actual' => $child->getOtherActual(),
                                                       'iconCls' => 'expense'));
        }
        if ($has_timelogs || $estimated_timelogs > 0)
        {
          array_unshift($childarray['children'], array('id' => 'labour-'.$child->getId(),
                                                       'text' => 'Labour',
                                                       'cls' => 'unselectable_node',
                                                       'leaf' => !$has_timelogs,
                                                       'draggable' => false,
                                                       'allowDrop' => false,
                                                       'estimate' => $estimated_timelogs,
                                                       'actual' => $child->getLabourActual(),
                                                       'iconCls' => 'labour'));
        }
        if ($has_parts || $estimated_parts > 0)
        {
          array_unshift($childarray['children'], array('id' => 'part-'.$child->getId(),
                                                       'text' => 'Parts',
                                                       'cls' => 'unselectable_node',
                                                       'leaf' => !$has_parts,
                                                       'draggable' => false,
                                                       'allowDrop' => false,
                                                       'estimate' => $estimated_parts,
                                                       'actual' => $child->getPartActual(),
                                                       'iconCls' => 'part'));
        }
        $output[] = $childarray;
      }

      return $output;
  }

  public function getHauloutDateTime($date_fmt)
  {
    $date = $this->getHauloutDate($date_fmt);
    if ($this->getHauloutDate('G') > 0) 
    {
      $date .= ' @ '.$this->getHauloutDate('g:i A');
    }

    return $date;
  }

  public function getHaulinDateTime($date_fmt)
  {
    $date = $this->getHaulinDate($date_fmt);
    if ($this->getHaulinDate('G') > 0) 
    {
      $date .= ' @ '.$this->getHaulinDate('g:i A');
    }

    return $date;
  }

  public function getReportData($sub_by_task = false, $sub_by_type = false, $sub_by_profit = false)
  {
    return WorkorderPeer::getReportData($this->getId(), null, $sub_by_task, $sub_by_type, $sub_by_profit);
  }

  public function delete (PropelPDO $con = null)
  {
    if ($this->canDelete())
    {
      //remove root item (will remove all subitems)
      if ($root = $this->getRootItem())
      {
        $root->delete();
      }

      //remove invoices
      if ($invoices = $this->getWorkorderInvoices())
      {
        foreach ($invoices AS $invoice)
        {
          $invoice->delete();
        }
      }

      //remove payments
      if ($payments = $this->getWorkorderPayments())
      {
        foreach ($payments AS $payment)
        {
          $payment->delete();
        }
      }

      parent::delete($con);

      return true;
    }
    else
    {
      return false;
    }
  }

}
