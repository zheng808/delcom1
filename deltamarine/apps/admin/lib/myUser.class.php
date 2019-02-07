<?php

class myUser extends sfGuardSecurityUser
{
  public function getEmployee()
  {
    $c = new Criteria();
    $c->add(EmployeePeer::GUARD_USER_ID, $this->getGuardUser()->getId());
    $employee = EmployeePeer::doSelectJoinWfCRM($c);

    if (count($employee) > 0)
    {
      return $employee[0];
    }

    return false;
  }

  public function getCustomer()
  {
    $c = new Criteria();
    $c->add(CustomerPeer::GUARD_USER_ID, $this->getGuardUser()->getId());
    $customer = CustomerPeer::doSelectJoinWfCRM($c);

    if (count($customer) > 0)
    {
      return $customer[0];
    }

    return false;
  }
}
