<?php

class Employee extends BaseEmployee
{
  public function __toString()
  {
    return $this->getName();
  }

  //proxy method for customizable name generation
  public function generateName($titles = false, $salutation = false, $last_name_first = false)
  {
      return $this->getCRM()->getName($titles, $salutation, $last_name_first);
  }

  public function getLastLogin($format = 'M j, Y h:ia', $never_message = 'Never Logged In', $nouser_message = 'No User')
  {
     if ($this->getSfGuardUser())
     {
       return ( $this->getSfGuardUser()->getLastLogin($format) != null ? $this->getSfGuardUser()->getLastLogin($format) : $never_message);
     }
     else
     {
       return $nouser_message;
     }
  }

  //this gets the first timelog in the Employee's Timelog array. 
  public function getLastTimelog($format = 'M j, Y h:ia', $nolog_message = 'No Time Logged')
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(TimelogPeer::START_TIME);
    $c->setLimit(1);

    if ($log = $this->getTimelogs($c))
    {
      return ($log[0]->getEndTime('U') ? 'Completed '.$log[0]->getEndTime($format)
                                       : 'Started '.$log[0]->getStartTime($format));
    }
    else
    {
      return $nolog_message;
    }
  }
    
  public function getDepartment()
  {
    $crm = $this->getCRM();
    $parent = $crm->retrieveParent();
    if ($parent && !$parent->getIsCompany() && $parent->getDepartmentName())
    {
      return $parent;
    }

    return null;
  }

  public function getDepartmentName()
  {
    if ($dep = $this->getDepartment())
    {
      return $dep->getDepartmentName();
    }
    else
    {
      return 'None';
    }
  }

  public function getTimelogsOrdered()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(TimelogPeer::START_TIME);

    return $this->getTimelogs($c);
  }

  public function delete(PropelPDO $con = null)
  {
    if ($this->getTimelogs())
    {
      return false;
    }
    else
    {
      //delete user
      if ($user = $this->getSfGuardUser())
      {
        $user->delete();
      }

      parent::delete($con);
      return true;
    }
  }


}

sfPropelBehavior::add('Employee', array('wfCRMBehavior'));
