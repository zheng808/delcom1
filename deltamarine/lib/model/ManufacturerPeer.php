<?php

class ManufacturerPeer extends BaseManufacturerPeer
{
  public static function getAutocompleteChoices()
  {
    $mfr_array = array();

    $c = new Criteria();
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    $c->add(self::HIDDEN, false);
    $mfrs = self::doSelectJoinWfCRM($c);
    foreach ($mfrs AS $mfr)
    {
      $mfr_array[] = "'".$mfr."'";
    }

    return '['.join(',', $mfr_array).']';
  }

  public static function retrieveOrCreateByName($name)
  {
    $c = new Criteria();
    $c->add(wfCRMPeer::DEPARTMENT_NAME, $name);
    $c->addJoin(self::WF_CRM_ID, wfCRMPeer::ID);
    if ($existing = self::doSelectOne($c))
    {
      return $existing;
    }

    $crm = new wfCRM();
    $crm->setIsCompany(true);
    $crm->setDepartmentName($name);
    $crm->save();

    $manu = new Manufacturer();
    $manu->setWfCRM($crm);
    $manu->save();

    return $manu;
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
      $sql = 'SELECT '.PartPeer::MANUFACTURER_ID.', COUNT('.PartVariantPeer::ID.') AS count'.
             ' FROM '.PartPeer::TABLE_NAME.', '.PartVariantPeer::TABLE_NAME.
             ' WHERE '.PartPeer::MANUFACTURER_ID.' IN ('.join(',', $keys).')'.
             ' AND '. PartPeer::ID .' = '. PartVariantPeer::PART_ID.
             ' GROUP BY '.PartPeer::MANUFACTURER_ID;
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
