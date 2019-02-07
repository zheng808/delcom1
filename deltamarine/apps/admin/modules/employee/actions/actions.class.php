<?php

/**
 * employee actions.
 *
 * @package    deltamarine
 * @subpackage employee
 * @author     Dave Achtemichuk
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class employeeActions extends sfActions
{

  public function preExecute()
  {
    sfConfig::set('app_selected_menu', 'employees');
  }

 /*
  * Displays the datagrid of employees
  */ 
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * Views a single employee record including related data
   */
  public function executeView(sfWebRequest $request)
  {
    $this->employee = $this->loadEmployee($request);

    return sfView::SUCCESS;
  }

  /*
   * Loads information about an employee for use via JSON
   */
  public function executeLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $employee = $this->loadEmployee($request);
    $contact = $employee->getCRM();

    if ($employee && $contact)
    {
      $data = array(
                    'first_name' => $contact->getFirstName(),
                    'last_name' => $contact->getLastName(),
                    'company_name' => ($contact->getIsCompany() ? $contact->getDepartmentName() : ''),
                    'job_title' => $contact->getJobTitle(),
                    'parent_node' => $contact->getParentNodeId(),
                    'email' => $contact->getEmail(),
                    'work_phone' => $contact->getWorkPhone(),
                    'mobile_phone' => $contact->getMobilePhone(),
                    'home_phone' => $contact->getHomePhone(),
                    'fax' => $contact->getFax(),
                    'private_notes' => $contact->getPrivateNotes(),
                    'payrate' => $employee->getPayrate(),
                    'emptype' => $contact->getIsCompany() ? 'Contractor' : 'Employee',
                    'status' => $employee->getHidden() ? 'Inactive' : 'Active'
                  );
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;
  }

  /*
   * Edits contact information of employee
   */
  public function executeEdit(sfWebRequest $request)
  {
    $employee = $this->loadEmployee($request);
    $contact = $employee->getCRM();

    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();
    if (!$parent = wfCRMPeer::retrieveByPk($request->getParameter('parent_node')))
    {
      if ($request->getParameter('parent_node') > 0)
      {
        $result = false;
        $errors['parent_node'] = 'Invalid Department selected!';
      }
      else
      {
        $owner = wfCRMPeer::getSiteOwnerCompany();
        $request->getParameterHolder()->set('parent_node', $owner->getId());
      }
    }
    if ($request->hasParameter('payrate') && !is_numeric($request->getParameter('payrate')))
    {
      $result = false;
      $errors['payrate'] = 'Invalid pay rate specified.';
    }
    if (strtolower($request->getParameter('emptype')) == 'employee')
    {
      if (trim($request->getParameter('first_name')) == '')
      {
        $result = false;
        $errors['first_name'] = 'First Name is Required';
      }
      if (trim($request->getParameter('last_name')) == '')
      {
        $result = false;
        $errors['last_name'] = 'Last Name is Required';
      }
    }
    else if ((strtolower($request->getParameter('emptype')) == 'contractor') && trim($request->getParameter('company_name')) == '')
    {
      $result = false;
      $errors['company_name'] = 'Company Name is required';
    }

    //create object
    if ($result)
    {
      if (strtolower($request->getParameter('emptype')) == 'employee')
      {
        $contact->setFirstName($request->getParameter('first_name'));
        $contact->setLastName($request->getParameter('last_name'));
        $contact->setHomePhone($request->getParameter('home_phone'));
        $contact->setDepartmentName(null);
        $contact->setIsCompany(false);
        $contact->setJobTitle($request->getParameter('job_title'));
        $contact->setParentNodeId($request->getParameter('parent_node'));
      }
      else
      {
        $contact->setDepartmentName($request->getParameter('company_name'));
        $contact->setFirstName(null);
        $contact->setLastName(null);
        $contact->setHomePhone(null);
        $contact->setIsCompany(true);
      }
      $contact->setWorkPhone($request->getParameter('work_phone'));
      $contact->setHomePhone($request->getParameter('home_phone'));
      $contact->setMobilePhone($request->getParameter('mobile_phone'));
      $contact->setFax($request->getParameter('fax'));
      $contact->setEmail($request->getParameter('email'));
      $contact->setPrivateNotes($request->getParameter('private_notes'));
      $contact->save();

      //update the address record
      $address = $contact->getWfCRMAddresss();
      $address = ($address ? $address[0] : new wfCRMAddress());
      if ($request->getParameter('address_line1') || $request->getParameter('address_line2') || $request->getParameter('address_city') || $request->getParameter('address_region'))
      {
        $address->setLine1($request->getParameter('address_line1'));
        $address->setLine2($request->getParameter('address_line2'));
        $address->setCity($request->getParameter('address_city'));
        $address->setRegion($request->getParameter('address_region'));
        $address->setPostal($request->getParameter('address_postal'));
        $address->setCountry($request->getParameter('address_country'));
        $address->setWfCRM($contact);
        $address->save();
      }
      else
      {
        $address->delete();
      }

      if ($request->hasParameter('payrate'))
      {
        $employee->setPayrate($request->getParameter('payrate'));
      }
      
      $employee->setHidden($request->getParameter('status') != 'Active');
      $employee->save();

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * Add a new employee to the system, including optional user account generation
   */
  public function executeAdd(sfWebRequest $request)
  {
    $crm = wfCRMPeer::getSiteOwnerCompany();
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    //validate
    $result = true;
    $errors = array();
    if (!$parent = wfCRMPeer::retrieveByPk($request->getParameter('parent_node')))
    {
      if ($request->getParameter('parent_node') > 0)
      {
        $result = false;
        $errors['parent_node'] = 'Invalid Department selected!';
      }
      else
      {
        $request->getParameterHolder()->set('parent_node', $crm->getId());
      }
      if ($request->hasParameter('payrate') && !is_numeric($request->getParameter('payrate')))
      {
        $result = false;
        $errors['payrate'] = 'Invalid pay rate specified.';
      }
    }

    //create object
    if ($result)
    {
      $contact = new wfCRM();
      if (strtolower($request->getParameter('emptype')) == 'employee')
      {
        $contact->setFirstName($request->getParameter('first_name'));
        $contact->setLastName($request->getParameter('last_name'));
        $contact->setHomePhone($request->getParameter('home_phone'));
        $contact->setDepartmentName(null);
        $contact->setIsCompany(false);
        $contact->setJobTitle($request->getParameter('job_title'));
      }
      else
      {
        $contact->setDepartmentName($request->getParameter('company_name'));
        $contact->setFirstName(null);
        $contact->setLastName(null);
        $contact->setHomePhone(null);
        $contact->setIsCompany(true);
      }
      $contact->setParentNodeId($request->getParameter('parent_node'));
      $contact->setWorkPhone($request->getParameter('work_phone'));
      $contact->setMobilePhone($request->getParameter('mobile_phone'));
      $contact->setFax($request->getParameter('fax'));
      $contact->setEmail($request->getParameter('email'));
      $contact->setPrivateNotes($request->getParameter('private_notes'));
      $contact->save();

      if ($request->getParameter('address_line1') || $request->getParameter('address_line2') || $request->getParameter('address_city') || $request->getParameter('address_region'))
      {
        $address = new wfCRMAddress();
        $address->setLine1($request->getParameter('address_line1'));
        $address->setLine2($request->getParameter('address_line2'));
        $address->setCity($request->getParameter('address_city'));
        $address->setRegion($request->getParameter('address_region'));
        $address->setPostal($request->getParameter('address_postal'));
        $address->setCountry($request->getParameter('address_country'));
        $address->setWfCRM($contact);
        $address->save();
      }

      $employee = new Employee();
      $employee->setWfCRM($contact);
      if ($request->hasParameter('payrate'))
      {
        $employee->setPayrate($request->getParameter('payrate'));
      }

      $employee->setHidden($request->getParameter('status') != 'Active');
      $employee->save();

      //output result as JSON
      $this->renderText("{success:true,newid:".$employee->getId()."}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


  /*
   * Deletes a employee and any associated information
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    $employee = $this->loadEmployee($request);
    if (!($employee && $employee->delete()))
    {
      $this->forward404('Could Not Delete Employee');
    }

    return sfView::NONE;
  }



  protected function loadEmployee(sfWebRequest $request)
  {
    $employee = EmployeePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($employee, sprintf('Object employee does not exist (%s).', $request->getParameter('id')));

    return $employee;
  }


//////////////////////////////// USERS ////////////////////////////////////////

  /*
   * loads up the current sfGuardUser's username, if any. JSON.
   */
  public function executeUserLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $employee = $this->loadEmployee($request);
    $user = $employee->getSfGuardUser();

    if ($employee)
    {
      $data = array('enabled'  => ($user ? (int) $user->getIsActive() : 0),
                    'username' => ($user ? $user->getUsername() : ''));
      $this->renderText("{success:true, data:".json_encode($data)."}");
    }
    else
    {
      $this->renderText("{success:false}");
    }

    return sfView::NONE;
  }


  public function executeUserEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $employee = $this->loadEmployee($request);
    $existing = $employee->getSfGuardUser();

    //validate
    $result = true;
    $errors = array();
    if ($request->getParameter('enabled'))
    {
      $user = trim($request->getParameter('username'));
      if (!$user)
      {
        $result = false;
        $errors['username'] = 'You must set a username to enable login!';
      }
      else if (strlen($user) < 3)
      {
        $result = false;
        $errors['username'] = 'Username must be at least 3 letters long';
      }
      else if (!preg_match('/^[a-zA-Z0-9_]+$/', $user))
      {
        $result = false;
        $errors['username'] = 'Username must contain only letters, numbers, or underscores';
      }
      else
      {
        //check for existing
        $c = new Criteria();
        $c->add(sfGuardUserPeer::USERNAME, $user);
        if ($existing)
        {
          $c->add(sfGuardUserPeer::ID, $existing->getId(), Criteria::NOT_EQUAL);
        }
        if (sfGuardUserPeer::doSelectOne($c))
        {
          $result = false;
          $errors['username'] = 'Username already exists! Try something else.';
        }
      }

      //check passwords
      $pass1 = trim($request->getParameter('password1'));
      $pass2 = trim($request->getParameter('password2'));
      if ($pass1 != $pass2)
      {
        $result = false;
        $errors['password2'] = 'The passwords you entered didn\'t match!';
      }
      else if (!$existing && (!$pass1 || !$pass2))
      {
        $result = false;
        $errors['password1'] = 'If creating a user, you must specify a password.';
      }
      else if ($pass1 && (strlen($pass1) < 6))
      {
        $result = false;
        $errors['password1'] = 'The password must be at least 6 characters long.';
      }
    }

    //create object
    if ($result)
    {
      if ($request->getParameter('enabled'))
      {
        if (!$existing)
        {
          $existing = new sfGuardUser();
        }
        $existing->setIsActive(true);
        $existing->setUsername($user);
        if ($pass1)
        {
          $existing->setPassword($pass1);
        }
        $existing->save();
        $employee->setSfGuardUser($existing);
        $employee->save();
      }
      else if ($existing)
      {
        $existing->setIsActive(false);
        $existing->save();
      }

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }


///////////////////////////// PERMISSIONS /////////////////////////////////////


  public function executePermsLoad(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $employee = $this->loadEmployee($request);
    $this->forward404Unless($user = $employee->getSfGuardUser());

    //get the custom list of permissions and current settings
    $perms = sfGuardPermissionPeer::getSortedPermissions();
    $exist = $user->getPermissionIds();

    //manually build JSON array, since it requires combining
    // both object and array notation (even PHP5.3 can't do that)
    $formdata = array();
    foreach ($perms AS $catname => $catperms)
    {
      $catdata = array();
      foreach ($catperms AS $perm)
      {
        $permdata = array('name' => 'perms['.$perm->getId().']',
                          'checked' => isset($exist[$perm->getId()]),
                          'hideLabel' => true,
                          'boxLabel' => $perm->getDescription());
        $catdata[] = '{'.substr(json_encode($permdata), 1, -1).'}';
      }
      $catresult = json_encode(array('xtype' => 'fieldset', 
                                     'title' => $catname, 
                                     'width' => 325,
                                     'autoWidth' => false,
                                     'autoHeight' => true, 
                                     'defaultType' => 'checkbox'));
      $catresult = substr($catresult, 0, -1).
                   ',"items":[' . join(',',$catdata) . ']}';
      $formdata[] = $catresult;
    }

    $this->renderText('['.join(',', $formdata).']');

    return sfView::NONE;
  }

  public function executePermsEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    $employee = $this->loadEmployee($request);
    $this->forward404Unless($user = $employee->getSfGuardUser());

    //note: validation not needed
    $newperms = $request->getParameter('perms', array());
    $oldperms = $user->getIndexedUserPermissions();

    foreach ($newperms AS $permid => $val)
    {
      if ($val && $val=='on')
      {
        //only create the record if it doesn't exist already
        if (!isset($oldperms[$permid]))
        {
          //create the permission
          $userperm = new sfGuardUserPermission();
          $userperm->setUserId($user->getId());
          $userperm->setPermissionId($permid);
          $userperm->save();
        }
        else
        {
          unset($oldperms[$permid]);
        }
      }
    }
    //now go through any that are no longer set and delete them.
    foreach ($oldperms AS $permid => $delperm)
    {
      $delperm->delete();
    }

    //output result as JSON
    $this->renderText("{success:true}");

    return sfView::NONE;
  }


///////////////////////////// DEPARTMENTS /////////////////////////////////////


  /*
   *  View the list of departments for editing/adding
   */
  public function executeDepartment(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * add a new department
   */
  public function executeDepartmentEdit(sfWebRequest $request)
  {
    $company = wfCRMPeer::getSiteOwnerCompany();
    $existing = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    //validate
    $result = true;
    $errors = array();

    //check for same name
    $c = new Criteria();
    $c->add(wfCRMPeer::PARENT_NODE_ID, $company->getId());
    $c->add(wfCRMPeer::DEPARTMENT_NAME, trim($request->getParameter('name')));
    if ($existing)
    {
      $c->add(wfCRMPeer::ID, $existing->getId(), Criteria::NOT_EQUAL);
    }
    if (wfCRMPeer::doSelectOne($c))
    {
      $result = false;
      $errors['name'] = 'Department already exists!';
    }
    else if ($request->getParameter('name') == '')
    {
      $result = false;
      $errors['name'] = 'Name must be specified!';
    }

    //create object
    if ($result)
    {
      if (!$existing)
      {
        $existing = new wfCRM();
        $existing->setParentNodeId($company->getId());
        $existing->setIsCompany(false);
      }
      $existing->setDepartmentName(trim($request->getParameter('name')));
      $existing->save();

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;

  }

  public function executeDepartmentDelete(sfWebRequest $request)
  {
    $dep = wfCRMPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($dep && !$dep->getIsCompany() && $dep->getDepartmentName());
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //get children and move to parent
    $parent = $dep->retrieveParent();
    $children = $dep->getChildren();
    foreach ($children AS $child)
    {
      $child->moveToLastChildOf($parent);
    }

    //delete
    $dep->delete();

    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;

  }

}
