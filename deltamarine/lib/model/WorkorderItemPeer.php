<?php

class WorkorderItemPeer extends BaseWorkorderItemNestedSetPeer
{

  public static function retrieveAllTree($workorder_id, $con = null)
  {
    $tree = array();
    if ($root = self::retrieveRoot($workorder_id))
    {
      $root->setLevel(0);
      $tree = array($root);

      if ($descendants = self::retrieveDescendants($root, $con))
      {
        foreach ($descendants as $descendant)
        {
          $tree[] = $descendant;
        }
      }
    }

    return $tree;
  }//retrieveAllTree()---------------------------------------------------------


	/**
	 * Retrieve multiple objects by workorder id.
	 *
	 */
	public static function retrieveByWorkorderId($workorder_id, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WorkorderItemPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$items = null;
		$criteria = new Criteria(WorkorderItemPeer::DATABASE_NAME);
		$criteria->add(WorkorderItemPeer::WORKORDER_ID, $workorder_id);
		$items = WorkorderItemPeer::doSelect($criteria, $con);
		
		return $items;
	}//retrieveByWorkorderId()---------------------------------------------------



  public static function getItemsByWorkordeId($workorder_id = null)
  {
    $items = array();
    if ($workorder_id)
    {
      $query = 'SELECT %s, %s, %s FROM %s WHERE %s = %s';
      $query = sprintf($query, self::ID, self::WORKORDER_ID, self::LABEL, 
                               self::TABLE_NAME, 
                               self::WORKORDER_ID, $workorder_id);

      $conn = Propel::getConnection();
      $statement = $conn->prepare($query);
      $statement->execute();
      while ($row = $statement->fetch(PDO::FETCH_NUM))
      {
        $items[$row[0]] = $row[1];
      }
    }

    return $items;
  }//getItemsByWorkordeId{} -----------------------------------------------------


}//WorkorderItemPeer{}=========================================================
