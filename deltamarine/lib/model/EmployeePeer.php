<?php

class EmployeePeer extends BaseEmployeePeer
{
  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();

    $results = self::doSelectJoinWfCRM($c);
    $indexed = array();
    foreach ($results AS $result)
    {
      $indexed[$result->getId()] = array('data' => $result, 'latest' => 0);
    }
    $results = $indexed;
    $keys = array_keys($results);

    if (count($keys) > 0)
    {
      $sql = 'SELECT '.EmployeePeer::ID.', MAX('.TimelogPeer::END_TIME.') AS latest'.
             ' FROM '.EmployeePeer::TABLE_NAME.', '.TimelogPeer::TABLE_NAME.
             ' WHERE '.EmployeePeer::ID.' IN ('.join(',', $keys).')'.
             ' AND '.EmployeePeer::ID.' = '.TimelogPeer::EMPLOYEE_ID.
             ' GROUP BY '.EmployeePeer::ID;
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $results[$row[0]]['latest'] = $row[1];
      }
    }
    unset($stmt, $row, $rs, $con, $sql);
      
    return $results;    
  }
}
