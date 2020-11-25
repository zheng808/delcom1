<?php

class PartCategory extends BasePartCategoryNestedSet
{

  public function hasChildren()
  {
    $c = new Criteria();
    $c->add(PartCategoryPeer::LEFT_COL, $this->getLeftValue(), Criteria::GREATER_THAN);
    $c->add(PartCategoryPeer::RIGHT_COL, $this->getRightValue(), Criteria::LESS_THAN);
    return (PartCategoryPeer::doCount($c) > 0);
  }

  public function getNameWithLevel($spacer = '&nbsp;', $repeat_chars = 4)
  {
    return (str_repeat($spacer, $this->getLevel() * $repeat_chars).$this->getName());
  }

  public function __toString()
  {
    return $this->getName();
  }

  //set separator to false to return array
  public function getHierarchy($separator = ' &gt; ')
  {
    $ret = array();
    foreach ($this->getPath() AS $parent)
    {
      if (!$parent->isRoot() && ($parent->getId() != $this->getId()))
      {
        $ret[] = $parent->getName();
      }
    }

    if ($separator)
    {
      $ret = implode($separator, $ret);
    }

    return $ret;
  }


  public function delete(PropelPDO $con = null)
  {
    $parent = $this->retrieveParent();

    //move sub categories
    if ($this->hasChildren())
    {
      foreach ($this->getChildren() AS $subcat)
      {
        $subcat->moveToLastChildOf($parent);
      }
    }

    //move parts
    if ($this->countParts())
    {
      foreach ($this->getParts() AS $part)
      {
        $part->setPartCategoryId($parent->getId());
        $part->save();
      }
    }
    parent::delete($con);
  }

}
