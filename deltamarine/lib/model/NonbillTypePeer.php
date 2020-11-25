<?php

class NonbillTypePeer extends BaseNonbillTypePeer
{
  public static function loadTypesArray()
  {
    $query = 'SELECT %s, %s FROM %s';
    $query = sprintf($query, self::ID, self::NAME, self::TABLE_NAME);

    $conn = Propel::getConnection();
    $statement = $conn->prepare($query);
    $statement->execute();

    $types = array();
    while ($row = $statement->fetch(PDO::FETCH_NUM))
    {
      $types[$row[0]] = array('name' => $row[1]);
    }

    return $types;
  }

}
