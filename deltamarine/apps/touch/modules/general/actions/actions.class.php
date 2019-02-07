<?php

/**
 * general actions.
 *
 * @package    deltamarine
 * @subpackage general
 * @author     Eugene Trinchuk
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class generalActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeCheckpwd(sfWebRequest $request)
  {
     
  }

  public function executeIndex(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->getUser()->signOut();
      $this->redirect('general/index');
    }

    $c = new Criteria();
    $c->addJoin(EmployeePeer::GUARD_USER_ID, sfGuardUserPeer::ID);
    $c->addJoin(sfGuardUserPeer::ID, sfGuardUserPermissionPeer::USER_ID);
    $c->addJoin(sfGuardUserPermissionPeer::PERMISSION_ID, sfGuardPermissionPeer::ID);
    $c->add(sfGuardUserPeer::IS_ACTIVE, true);
    $c->add(sfGuardPermissionPeer::NAME, 'app_touch');
    $c->add(EmployeePeer::HIDDEN, false);
    $c->addAscendingOrderByColumn(wfCRMPeer::IS_COMPANY);
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    $employees = EmployeePeer::doSelectForListing($c);

    $emps = array();
    foreach ($employees AS $employee)
    {
      $emps[] = $employee['data'];
    }
    $this->employees = $emps;

    return sfView::SUCCESS;
  }  

  public function executeError404()
  {
    return sfView::SUCCESS;
  }

  public function executeStart(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  //TODO, there is some left over stuff from old touch in here, but this is used by newtouch as well. checkonly can by removed later.
  public function executeLogin(sfWebRequest $request)
  {
    $valid = true;
    $error = null;

    $emp_id = $request->getParameter('id');
    $employee = EmployeePeer::retrieveByPk($emp_id);
    if ($employee && $employee->getGuardUserId() > 0 && $employee->getSfGuardUser() && $employee->getSfGuardUser()->getIsActive())
    {
      $user = $employee->getSfGuardUser();
      if ($user->hasPermission('app_touch'))
      {
        if ($user->checkPassword($request->getParameter('pass')))
        {
          if (!$request->hasParameter('checkonly'))
          {
            $this->getUser()->signIn($user);
          }
        }
        else
        {
          $valid = false;
          $error = 'Incorrect Password! Try again, or make sure you selected the right user.';
        }
      }
      else
      {
        $valid = false;
        $error = 'User does not have access to the Touch system';
      }
    }
    else
    {
      $valid = false;
      $error = 'Invalid User Selected!';
    }

    $result = array();
    if ($request->isXmlHttpRequest())
    {
      if ($valid)
      {
        $result = array('success' => true, 'empid' => $employee->getId());
      }
      else
      {
        $result = array('success' => false, 'error' => $error);
      }
      $this->getResponse()->setContentType('application/json');
      $this->renderText(json_encode($result));
      return sfView::NONE;
    }
    else
    {
      $this->redirect('general/index');
    }
  }
}
