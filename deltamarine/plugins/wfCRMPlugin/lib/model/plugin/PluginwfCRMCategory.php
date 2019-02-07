<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMCategory extends BasewfCRMCategoryNestedSet
{

  public function __toString()
  {
    return $this->getPrivateName();
  }

  public function getNameWithLevel($spacer = '&nbsp;')
  {
    $padding = '';
    for($i = 0; $i < $this->getLevel(); $i++)
      $padding .= $spacer;
    return $padding . $this->getPrivateName();
  }

  public function getPublicNameWithLevel($spacer = '&nbsp;&nbsp;')
  {
    $padding = '';
    for($i = 0; $i < $this->getLevel(); $i++)
      $padding .= $spacer;
    return $padding . $this->getPublicName();
  }

  public function setParentNodeId($id)
  {
    $parent = $this->getPeer()->retrieveByPK($id);
    if ($parent && true === wfCRMCategoryPeer::isValid($parent))
    {
      if ($this->isNew())
      {
        $this->insertAsLastChildOf($parent);
      }
      elseif ($id != $this->getParentNodeId())
      {
        $this->moveToLastChildOf($parent);
      }
    }
    else
    {
      $this->makeRoot();
      $this->setScopeIdValue(time());
    }
    parent::setParentNodeId($id);
  }

  public function getDescendants(PropelPDO $con = null)
  {
    $this->getLevel();
    
    return wfCRMCategoryPeer::retrieveDescendants($this, $con);
  }
}
