<?php

class CustomerOrderPeer extends BaseCustomerOrderPeer
{

  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();
    
    self::addSelectColumns($c);
    CustomerPeer::addSelectColumns($c);
    wfCRMPeer::addSelectColumns($c);

    $cust_startcol = (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $crm_startcol  = $cust_startcol + (CustomerPeer::NUM_COLUMNS - CustomerPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::CUSTOMER_ID, CustomerPeer::ID);
    $c->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);

    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $sale_key = CustomerOrderPeer::getPrimaryKeyHashFromRow($row, 0);
      $sale = new CustomerOrder();
      $sale->hydrate($row);
      CustomerOrderPeer::addInstanceToPool($sale, $sale_key);

      $cust_key = CustomerPeer::getPrimaryKeyHashFromRow($row, $cust_startcol);
      $cust = new Customer();
      $cust->hydrate($row, $cust_startcol);
      CustomerPeer::addInstanceToPool($cust, $cust_key);

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      $crm = new wfCRM();
      $crm->hydrate($row, $crm_startcol);
      wfCRMPeer::addInstanceToPool($crm, $crm_key);

      $cust->setWfCRM($crm);
      $sale->setCustomer($cust);

      $results[] = $sale;
    } 
    
    unset($stmt, $row);   
      
    return $results;
  }

}
