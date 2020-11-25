<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMPeer extends BasewfCRMNestedSetPeer
{

  public static function retrieveAllTree(Criteria $c = null, PropelPDO $con = null)
  {
    $tree = array();
    $c = new Criteria();
    $c->add(wfCRMPeer::LEFT_COL, 1, Criteria::EQUAL);
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    $c->addAscendingOrderByColumn(wfCRMPeer::DEPARTMENT_NAME);
    $roots = wfCRMPeer::doSelect($c,$con);
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

  public static function retrieveAllCompaniesTree(Criteria $c = null, PropelPDO $con = null)
  {
    $tree = array();
    if(! $c instanceof Criteria )
      $c = new Criteria();
    $c->add(wfCRMPeer::LEFT_COL, 1, Criteria::EQUAL);
    $c->add(wfCRMPeer::IS_COMPANY, 1, Criteria::EQUAL);
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    $c->addAscendingOrderByColumn(wfCRMPeer::DEPARTMENT_NAME);
    $roots = wfCRMPeer::doSelect($c);
    foreach ( $roots as $root )
    {
      $tree[] = $root;
      $descedants = $root->getDescendants($con);
      if ($descedants)
      {
        foreach ( $descedants as $descedant )
        {
          if ($descedant->getIsCompany())
            $tree[] = $descedant;
        }
      }
    }
    return $tree;
  }

  public static function getSiteOwnerCompany()
  {
    return self::retrieveRoot('1');
  }

  protected static function hydrateDescendants(NodeObject $node, PDOStatement $stmt)
  {
    $descendants = array();
    $children = array();
    $prevSibling = null;
    
    // set the class once to avoid overhead in the loop
    $cls = wfCRMPeer::getOMClass();
    $cls = substr('.' . $cls, strrpos('.' . $cls, '.') + 1);
    
    while ( $row = $stmt->fetch(PDO::FETCH_NUM) )
    {
      $key = wfCRMPeer::getPrimaryKeyHashFromRow($row, 0);
      if (null === ($child = wfCRMPeer::getInstanceFromPool($key)))
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
        $descendants = array_merge($descendants, wfCRMPeer::hydrateDescendants($child, $stmt));
      }
      else
      {
        $child->setChildren(array());
      }
      
      wfCRMPeer::addInstanceToPool($child);
      if ($child->getRightValue() + 1 == $node->getRightValue())
      {
        $child->setNextSibling(null);
        break;
      }
    }
    $node->setChildren($children);
    return $descendants;
  }

  public static function getFirstLevel()
  {
    $c = new Criteria();
    $c->add(self::TREE_LEFT, 1);
    $c->add(self::IS_COMPANY, 1);
    $c->addAscendingOrderByColumn(self::ALPHA_NAME);

    return self::doSelect($c);
  }
}
