<?php

class PartCategoryPeer extends BasePartCategoryNestedSetPeer
{

  public static function retrieveAllTree($c = null, $con = null)
  {
    $tree = array();
    if ($root = self::retrieveRoot())
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

  public static function retrieveAllPaths($separator = ' &gt; ', $c = null, $con = null)
  {
    $tree = self::retrieveAllTree($c, $con);
    $paths = array();
    foreach ($tree AS $tree_item)
    {
      $this_id = $tree_item->getId();
      $this_path = array();
      while ($tree_item->hasParent())
      {
        array_unshift($this_path, $tree_item->getName());
        $tree_item = $tree_item->retrieveParent();
      }
      $paths[$this_id] = implode($separator, $this_path);
    }

    return $paths;
  }

  protected static function hydrateDescendants(NodeObject $node, PDOStatement $stmt)
  {
    $descendants = array(); //1D array of ALL descendants (recursive)
    $children = array();    //1D array of immediate children (not recursive). used for populating node.
    $prevSibling = null;    //used to properly populate node relations
    
    // set the class once to avoid overhead in the loop
    $cls = PartCategoryPeer::getOMClass();
    $cls = substr('.' . $cls, strrpos('.' . $cls, '.') + 1);
    
    while ( $row = $stmt->fetch(PDO::FETCH_NUM) )
    {
      $key = PartCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
      if (null === ($child = PartCategoryPeer::getInstanceFromPool($key)))
      {
        $child = new $cls();
        $child->hydrate($row);
      }
      
      $child->setLevel($node->getLevel() + 1);
      $child->setParentNode($node);
      if (!empty($prevSibling))
      {
        $child->setPrevSibling($prevSibling);
        $prevSibling->setNextSibling($child);
      }
      
      $children[] = $child;
      $descendants[$node->getName().' '.$child->getName()] = $child;
      $prevSibling = $child;
      
      if ($child->hasChildren())
      {
        $subchildren = PartCategoryPeer::hydrateDescendants($child, $stmt);
        $subchildren_prefixed = array();
        foreach ($subchildren AS $subchildkey => $subchild)
        {
            $subchildren_prefixed[$node->getName().' '.$subchildkey] = $subchild;
        }
        unset($subchildren);
        $descendants = array_merge($descendants, $subchildren_prefixed);
      }
      else
      {
        $child->setChildren(array());
      }
      
      PartCategoryPeer::addInstanceToPool($child);
      if ($child->getRightValue() + 1 == $node->getRightValue())
      {
        $child->setNextSibling(null);
        break;
      }
    }
    $node->setChildren($children);
    uksort($descendants, 'strnatcasecmp');
    return $descendants;
  }


}
