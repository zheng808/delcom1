<?php

class WorkorderCategoryPeer extends BaseWorkorderCategoryPeer
{
  public static function loadCatsArray()
  {
    $query = 'SELECT %s, %s FROM %s';
    $query = sprintf($query, self::ID, self::NAME, self::TABLE_NAME);

    $conn = Propel::getConnection();
    $statement = $conn->prepare($query);
    $statement->execute();

    $cats = array();
    while ($row = $statement->fetch(PDO::FETCH_NUM))
    {
      $cats[$row[0]] = array('name' => $row[1]);
    }

    return $cats;
  }

}
