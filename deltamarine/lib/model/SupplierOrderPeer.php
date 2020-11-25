<?php

class SupplierOrderPeer extends BaseSupplierOrderPeer
{

  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();
    
    self::addSelectColumns($c);
    SupplierPeer::addSelectColumns($c);
    wfCRMPeer::addSelectColumns($c);

    $supp_startcol = (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $crm_startcol  = $supp_startcol + (SupplierPeer::NUM_COLUMNS - SupplierPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::SUPPLIER_ID, SupplierPeer::ID);
    $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);

    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $order_key = SupplierOrderPeer::getPrimaryKeyHashFromRow($row, 0);
      $order = new SupplierOrder();
      $order->hydrate($row);
      SupplierOrderPeer::addInstanceToPool($order, $order_key);

      $supp_key = SupplierPeer::getPrimaryKeyHashFromRow($row, $supp_startcol);
      $supp = new Supplier();
      $supp->hydrate($row, $supp_startcol);
      SupplierPeer::addInstanceToPool($supp, $supp_key);

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      $crm = new wfCRM();
      $crm->hydrate($row, $crm_startcol);
      wfCRMPeer::addInstanceToPool($crm, $crm_key);

      $supp->setWfCRM($crm);
      $order->setSupplier($supp);

      $results[] = $order;
    } 
    
    unset($stmt, $row);   
      
    return $results;
  }
 
}
