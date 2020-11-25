<?php

class CustomerPeer extends BaseCustomerPeer
{

 public static function getDepartmentsList($c = null)
  {
    if (!$c) $c = new Criteria();
    /*
      :TODO
      $c->add(SfCrmPeer::ORG_ID, sfConfig::get('app_owner_organization_id'));
      $c->add(SfCrmPeer::DEP_ID, null, Criteria::ISNOTNULL);
      $departments = wfCRMPeer::doSelect($c);

      return $departments;
    */
    
    return array();
  }
  
  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();
    
    self::addSelectColumns($c);
    wfCRMPeer::addSelectColumns($c);
    sfGuardUserPeer::addSelectColumns($c);
    
    $crm_startcol     = (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $guard_startcol   = $crm_startcol + (wfCRMPeer::NUM_COLUMNS - wfCRMPeer::NUM_LAZY_LOAD_COLUMNS);

    $c->addJoin(self::WF_CRM_ID, wfCRMPeer::ID);  
    $c->addJoin(self::GUARD_USER_ID, sfGuardUserPeer::ID, Criteria::LEFT_JOIN);
    
    $stmt = BasePeer::doSelect($c);
    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) 
    {
      $cust_key = CustomerPeer::getPrimaryKeyHashFromRow($row, 0);
      $cust = new Customer();
      $cust->hydrate($row);
      CustomerPeer::addInstanceToPool($cust, $cust_key);

      $crm_key = wfCRMPeer::getPrimaryKeyHashFromRow($row, $crm_startcol);
      $crm = new wfCRM();
      $crm->hydrate($row, $crm_startcol);
      wfCRMPeer::addInstanceToPool($crm, $crm_key);
      $cust->setwfCRM($crm);

      $guard_key = sfGuardUserPeer::getPrimaryKeyHashFromRow($row, $guard_startcol);
      $guard = new sfGuardUser();
      $guard->hydrate($row, $guard_startcol);
      if ($guard->getId()) 
      {
        $cust->setSfGuardUser($guard);
        sfGuardUserPeer::addInstanceToPool($guard, $guard_key);
      } 
      
      $results[] = $cust;
    } 
    
    unset($stmt, $row);   
      
    return $results;
    
  }
}
