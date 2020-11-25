<?php

class PartOption extends BasePartOption
{

  public function __toString()
  {
    return $this->getName();
  }

  public function canDelete()
  {
    $c = new Criteria();
    $c->add(PartOptionValuePeer::PART_OPTION_ID, $this->getId());
    $values = PartOptionValuePeer::doSelect($c);

    $textval = false;
    foreach ($values AS $value)
    {
      if ($textval === false)
      {
        $textval = $value->getValue();
      }
      else if ($textval != $value->getValue())
      {
        //there are more than one different values currently used/set
        return false;
      }
    }
  
    return true;
  }

  public function delete(PropelPDO $con = null)
  {
    $c = new Criteria();
    $c->add(PartOptionValuePeer::PART_OPTION_ID, $this->getId());
    PartOptionValuePeer::doDelete($c);

    parent::delete();
  }
}
