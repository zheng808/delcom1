<?php
/**
 * wfCRMPlugin actions.
 *
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRM extends BasewfCRMNestedSet
{

  public function __toString()
  {
    return $this->getName();
  }

  public function getMainPhone($use_mobile = true)
  {
    if ($this->getIsCompany() && $this->getWorkPhone()) return $this->getWorkPhone();
    else if (!$this->getIsCompany() && $this->getHomePhone()) return $this->getHomePhone();
    else if ($use_mobile && $this->getMobilePhone()) return $this->getMobilePhone();
    else if ($this->getHomePhone()) return $this->getHomePhone();
    else if ($this->getWorkPhone()) return $this->getWorkPhone();

    return null;
  }

  public function getName($with_salutation = false, $with_titles = false, $last_name_first = false)
  {
    if ($this->getIsCompany() || $this->getDepartmentName())
    {
      return $this->getDepartmentName();
    }
    else
    {
      //get name if any
      $names = array();
      if ($last_name_first && $this->getLastName())
        $names[] = $this->getLastName() . ',';
      if ($with_salutation && $this->getSalutation())
        $names[] = $this->getSalutation();
      if ($this->getFirstname())
        $names[] = $this->getFirstName();
      if ($this->getMiddleName())
        $names[] = $this->getMiddleName();
      if (!$last_name_first && $this->getLastName())
        $names[] = $this->getLastName();
      
      $name = join(' ', $names);
      if ($with_titles && $this->getTitles())
        $name .= ", " . $this->getTitles();
      
      return $name;
    }
  }

  public function getNameWithLevel($spacer = '&nbsp;')
  {
    $padding = '';
    for($i = 0; $i < $this->getLevel(); $i++)
      $padding .= $spacer;
    return $padding . $this->getName();
  }

  public function setParentNodeId($id)
  {
    $parent = ($id > 0 ? ($parent = $this->getPeer()->retrieveByPK($id)) : null);
    if ($parent && true === wfCRMPeer::isValid($parent))
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
      $this->setScopeIdValue(substr(time(),2).rand(1,9));
    }
    parent::setParentNodeId($id);
  }

  /*
   * Propel Nested Sets fix
   *
   */
  
  public function getDescendants(PropelPDO $con = null)
  {
    $this->getLevel();
    return wfCRMPeer::retrieveDescendants($this, $con);
  }

  public function getwfCRMCategories(PropelPDO $con = null)
  {
    $cats = array();
    $cat_refs = $this->getwfCRMCategoryRefsJoinwfCRMCategory();
    foreach ( $cat_refs as $cat_ref )
    {
      $cats[] = $cat_ref->getwfCRMCategory();
    }
    return $cats;
  }

  public function getDepartmentsList($padding_str = '&nbsp;', $padding_amt = 4)
  {
    $list = array();
    if ($this->getDepartmentName())
    {
      $base_level = $this->getLevel();
      foreach ($this->getDescendants() AS $desc)
      {
        if ($desc->getDepartmentName() && !$desc->getIsCompany())
        {
          $list[$desc->getId()] = str_repeat($padding_str, ($desc->getLevel() - $base_level - 1) * $padding_amt).
                                  $desc->getDepartmentName();
        }
      }
    }

    return $list;
  }

  public function getDepartmentHierarchy($none_return = 'None', $as_text = true, $text_join = ' &gt; ')
  {
    $path = $this->getPath(); 
    $data = array();

    foreach ($path AS $pathitem)
    {
      if ($pathitem->getIsCompany()) continue;
      if ($pathitem->getId() == $this->getId()) break;
      $data[$pathitem->getId()] = $pathitem;
    }
    array_reverse($data);

    if ($as_text)
    {
      return (count($data) > 0 ? join($text_join, $data) : $none_return);
    }
    else
    {
      return $data; 
    }
  }

}
