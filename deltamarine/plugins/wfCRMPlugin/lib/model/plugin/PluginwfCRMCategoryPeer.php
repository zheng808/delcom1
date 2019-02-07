<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMCategoryPeer extends BasewfCRMCategoryNestedSetPeer
{
  public static function doSelectSubscribeble(Criteria $criteria, PropelPDO $con = null)
  {
    if (!$criteria)
    {
      $criteria = new Criteria();
    }
    $criteria->add(wfCRMCategoryPeer::IS_SUBSCRIBABLE, true);
    return parent::doSelect($criteria, $con);
  }
  
  public static function retrieveAllTree(Criteria $c = null, PropelPDO $con = null)
  {
    $tree = array();
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(wfCRMCategoryPeer::LEFT_COL, 1, Criteria::EQUAL);
    $c->addAscendingOrderByColumn(wfCRMCategoryPeer::PRIVATE_NAME);
    $roots = wfCRMCategoryPeer::doSelect($c, $con);
    
    foreach ( $roots as $root )
    {
      $tree[] = $root;
      $descedants = $root->getDescendants($con);
      if ($descedants)
      {
        foreach ( $descedants as $descedant )
        {
          $tree[] = $descedant;
        }
      }
    }
    return $tree;
  }

  protected static function hydrateDescendants(NodeObject $node, PDOStatement $stmt)
  {
    $descendants = array();
    $children = array();
    $prevSibling = null;
    
    // set the class once to avoid overhead in the loop
    $cls = wfCRMCategoryPeer::getOMClass();
    $cls = substr('.' . $cls, strrpos('.' . $cls, '.') + 1);
    
    while ( $row = $stmt->fetch(PDO::FETCH_NUM) )
    {
      $key = wfCRMCategoryPeer::getPrimaryKeyHashFromRow($row, 0);
      if (null === ($child = wfCRMCategoryPeer::getInstanceFromPool($key)))
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
      $descendants[] = $child;
      $prevSibling = $child;
      
      if ($child->hasChildren())
      {
        $descendants = array_merge($descendants, wfCRMCategoryPeer::hydrateDescendants($child, $stmt));
      }
      else
      {
        $child->setChildren(array());
      }
      
      wfCRMCategoryPeer::addInstanceToPool($child);
      if ($child->getRightValue() + 1 == $node->getRightValue())
      {
        $child->setNextSibling(null);
        break;
      }
    }
    $node->setChildren($children);
    return $descendants;
  }
}
