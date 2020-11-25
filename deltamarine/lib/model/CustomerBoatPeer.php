<?php

class CustomerBoatPeer extends BaseCustomerBoatPeer
{
  public static function doSelectForListing($c = null, $con = null)
  {
    if (!$c) $c = new Criteria();

    $results = self::doSelect($c);
    $indexed = array();
    foreach ($results AS $result)
    {
      $indexed[$result->getId()] = array('data' => $result, 'latest' => 0);
    }
    $results = $indexed;
    $keys = array_keys($results);

    if (count($keys) > 0)
    {
      $sql = 'SELECT '.CustomerBoatPeer::ID.', MAX('.WorkorderPeer::CREATED_ON.') AS latest'.
             ' FROM '.CustomerBoatPeer::TABLE_NAME.' LEFT JOIN '.WorkorderPeer::TABLE_NAME.
             ' ON ('.CustomerBoatPeer::ID.'='.WorkorderPeer::CUSTOMER_BOAT_ID.')'.
             ' WHERE '.CustomerBoatPeer::ID.' IN ('.join(',', $keys).')'.
             ' GROUP BY '.CustomerBoatPeer::ID;
      $con = Propel::getConnection();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $results[$row[0]]['latest'] = strtotime($row[1]);
      }
    }

    unset($stmt, $row, $rs, $con, $sql);
      
    return $results;
  }

  public static function getBoatTypes($search = null)
  {
    $con = Propel::getConnection();
    $search = ($search ? '%'.$search.'%' : false);
    $sql = "SELECT DISTINCT ".CustomerBoatPeer::MAKE.",".CustomerBoatPeer::MODEL.
           " FROM ".CustomerBoatPeer::TABLE_NAME.
            ($search ? " WHERE ".CustomerBoatPeer::MAKE." LIKE ?".
                       " OR ".CustomerBoatPeer::MODEL." LIKE ?"
                      : '').
           " ORDER BY ".CustomerBoatPeer::MAKE." ASC, ".CustomerBoatPeer::MODEL." ASC";
    $stmt = $con->prepare($sql);
    if ($search)
    {
      $stmt->bindValue(1, $search);
      $stmt->bindValue(2, $search);
    }
    $stmt->execute();

    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $make = ucwords($row[0]);
      $model = ucwords($row[1]);
      if (!isset($results[$make]))
      {
        $results[$make] = array();
      }
      if ($model != '')
      {
        $results[$make][] = $model;
      }
    }
  
    return $results;
  }

  public static function getBoatMakes($search = null)
  {
    $con = Propel::getConnection();
    $search = ($search ? '%'.$search.'%' : false);
    $sql = "SELECT DISTINCT ".CustomerBoatPeer::MAKE.
           " FROM ".CustomerBoatPeer::TABLE_NAME.
           ($search ? " WHERE ".CustomerBoatPeer::MAKE." LIKE ?" : "").
           " ORDER BY ".CustomerBoatPeer::MAKE." ASC";
    $stmt = $con->prepare($sql);
    if ($search)
    {
      $stmt->bindValue(1, $search);
    }
    $stmt->execute();

    $results = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $results[] = ucwords($row[0]);
    }
  
    return $results;
  }

  public static function getBoatModels($make = null, $search = null)
  {
    $results = array();
    if ($make || $search)
    {
      $con = Propel::getConnection();
      $search = ($search ? '%'.$search.'%' : false);
      $sql = "SELECT DISTINCT ".CustomerBoatPeer::MODEL.
             " FROM ".CustomerBoatPeer::TABLE_NAME.
             ($search || $make ? " WHERE " : '').
             ($make ? CustomerBoatPeer::MAKE." = ? " : '').
             ($search && $make ? " AND " : '').
             ($search ? CustomerBoatPeer::MODEL." LIKE ?" : '').
             " ORDER BY ".CustomerBoatPeer::MODEL." ASC";
      $stmt = $con->prepare($sql);
      if ($make && $search)
      {
        $stmt->bindValue(1, $make);
        $stmt->bindValue(2, $search);
      }
      else if ($make)
      {
        $stmt->bindValue(1, $make);
      }
      else
      {
        $stmt->bindValue(1, $search);
      }
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_NUM))
      {
        $results[] = ucwords($row[0]);
      }
    }

    return $results;
  }
}
