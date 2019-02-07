<?php

class Customer extends BaseCustomer
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

  //proxy/convenience function. blindly selects first address for now.
  public function getAddress($line_break = "\n", $home_country = null)
  {
    $addresses = $this->getCRM()->getwfCRMAddresss();

    return ($addresses ? $addresses[0]->getAddress($line_break, $home_country) : null);
  }

  public function getLastLogin($format = 'M j, Y h:ia', $never_message = 'Never Logged In', $nouser_message = 'No User')
  {
     if ($this->getSfGuardUser())
     {
       return ( $this->getSfGuardUser()->getLastLogin($format) != null 
         ? $this->getSfGuardUser()->getLastLogin($format) 
         : $never_message);
     }
     else
     {
       return $nouser_message;
     }
  }

  public function delete(PropelPDO $con = null)
  {
    if ($this->getWorkOrders())
    {
      return false;
    }
    else if ($this->getCustomerOrders())
    {
      return false;
    }
    else
    {
      //delete boats
      if ($boats = $this->getCustomerBoats())
      {
        foreach ($boats AS $boat)
        {
          $boat->delete();
        }
      }

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

sfPropelBehavior::add('Customer', array('wfCRMBehavior'));
