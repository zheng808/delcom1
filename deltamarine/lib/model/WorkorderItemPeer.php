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
  }

}
