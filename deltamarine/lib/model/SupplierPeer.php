<?php

class SupplierPeer extends BaseSupplierPeer
{
  public static function getAutocompleteChoices()
  {
    $supp_array = array();
  
    $c = new Criteria();
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    $c->add(self::HIDDEN, false);
    $supps = self::doSelectJoinwfCRM($c);
    foreach ($supps AS $supp)
    {
      $supp_array[] = "'".$supp."'";
    }

    return '['.join(',', $supp_array).']';
  }

  public static function retrieveOrCreateByName($name)
  {
    $c = new Criteria();
    $c->addJoin(self::WF_CRM_ID, wfCRMPeer::ID);
    $c->add(wfCRMPeer::DEPARTMENT_NAME, $name);
    if ($existing = self::doSelectOne($c))
    {
      return $existing;
    }

    $crm = new wfCRM();
    $crm->setIsCompany(true);
    $crm->setDepartmentName($name);
    $crm->save();

    $supp = new Supplier();
    $supp->setwfCRM($crm);
    $supp->save();

    return $supp;
  }
  
  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();
    
    $results = self::doSelectJoinWfCRM($c);
    $indexed = array();
    foreach ($results AS $result)
    {
      $indexed[$result->getId()] = array('data' => $result, 'count' => 0);
    }
    $results = $indexed;
    $keys = array_keys($results);

    if (count($keys) > 0)
    {
      $sql = 'SELECT '.PartSupplierPeer::SUPPLIER_ID.', COUNT('.PartSupplierPeer::PART_VARIANT_ID.') AS count'.
             ' FROM '.PartSupplierPeer::TABLE_NAME.
             ' WHERE '.PartSupplierPeer::SUPPLIER_ID.' IN ('.join(',', $keys).')'.
             ' GROUP BY '.PartSupplierPeer::SUPPLIER_ID;
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $results[$row[0]]['count'] = $row[1];
      }
    }
    unset($stmt, $row, $rs, $con, $sql);
      
    return $results;
  }
}
