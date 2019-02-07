<?php

class employeeActivityAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {
    //build the query of valid employees for touch app
    //TODO: build MERGE mysql query and execute to extract needed fields

    //generate JSON output
    $employeearray = array();
    foreach (EmployeePeer::doSelectJoinWfCRM($c) as $employee)
    {
      $employees[] = array(
        'id'           => $employee->getId(),
        'firstname'    => $employee->getWfCRM()->getFirstName(),
        'lastname'     => $employee->getWfCRM()->getLastName(),
        'fullname'     => $employee->generateName(),
        'shortname'    => null,
        'contractor'   => $employee->getWfCRM()->getIsCompany()
     );
    }

    $dataarray = array('success' => true, 'employees' => $employees);

    return $dataarray;
  }
}
