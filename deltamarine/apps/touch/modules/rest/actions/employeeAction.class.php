<?php

class employeeAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //build the query of valid employees for touch app
    $c = new Criteria();
    $c->addJoin(EmployeePeer::GUARD_USER_ID, sfGuardUserPeer::ID);
    $c->addJoin(sfGuardUserPeer::ID, sfGuardUserPermissionPeer::USER_ID);
    $c->addJoin(sfGuardUserPermissionPeer::PERMISSION_ID, sfGuardPermissionPeer::ID);
    $c->add(sfGuardUserPeer::IS_ACTIVE, true);
    $c->add(sfGuardPermissionPeer::NAME, 'app_touch');
    $c->add(EmployeePeer::HIDDEN, false);
    if ($request->getParameter('id'))
    {
      $c->add(EmployeePeer::ID, $request->getParameter('id'));
    }

    //generate JSON output
    $employeearray = array();
    foreach (EmployeePeer::doSelectJoinWfCRM($c) as $employee)
    {
      $employees[] = array(
        'id'           => $employee->getId(),
        'firstname'    => $employee->getWfCRM()->getFirstName(),
        'lastname'     => $employee->getWfCRM()->getLastName(),
        'companyname'  => $employee->getWfCRM()->getDepartmentName(),
        'fullname'     => $employee->generateName(),
        'shortname'    => null,
        'contractor'   => $employee->getWfCRM()->getIsCompany()
     );
    }
    
    //build the shortname array
    $shortnames = array();
    foreach ($employees AS $key => $employee)
    {
      if ($employee['contractor'])
      {
        $employees[$key]['shortname'] = $employee['companyname'];
      }
      else if (isset($shortnames[$employee['firstname']]))
      {
        $otherkey = $shortnames[$employee['firstname']];
        $employees[$key]['shortname'] = $employee['firstname'].' '.substr($employee['lastname'], 0, 1).'.';
        $employees[$otherkey]['shortname'] = $employees[$otherkey]['firstname'].' '.substr($employees[$otherkey]['lastname'], 0, 1).'.';
      }
      else
      {
        $shortnames[$employee['firstname']] = $key;  
        $employees[$key]['shortname'] = $employee['firstname'];
      }
    }

    $dataarray = array('success' => true, 'employees' => $employees);

    return $dataarray;
  }
}
