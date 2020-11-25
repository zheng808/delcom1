<?php

class LabourTypePeer extends BaseLabourTypePeer
{

  public static function loadTypesArray($active_only = false)
  {
    $query = 'SELECT %s, %s, %s FROM %s';
    $query = sprintf($query, self::ID, self::NAME, self::HOURLY_RATE, self::TABLE_NAME);

    if ($active_only)
    {
      $query = $query.sprintf(" WHERE %s = 1", self::ACTIVE);
    }

    $conn = Propel::getConnection();
    $statement = $conn->prepare($query);
    $statement->execute();

    $types = array();
    while ($row = $statement->fetch(PDO::FETCH_NUM))
    {
      $types[$row[0]] = array('name' => $row[1], 'rate' => $row[2]);
    }

    return $types;
  }
}
