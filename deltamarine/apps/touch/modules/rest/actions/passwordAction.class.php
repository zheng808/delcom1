<?php

class passwordAction extends sfAction {

  public function execute($request){

    $this->forward404Unless($emp = EmployeePeer::retrieveByPk($request->getParameter('employee_id')));
    $this->forward404Unless($guard = $emp->getSfGuardUser());

    $pass = trim($request->getParameter('oldpass'));
    $pass1 = trim($request->getParameter('newpass'));
    $pass2 = trim($request->getParameter('newpass2'));

    //validate
    $result = true;
    $errors = array();
    if (!$guard->checkPassword($pass)){
      $result = false;
      $errors['old'] = 'Your old password was incorrect. Try again.';
    }
    else if ($pass1 == '' || $pass2 == '')
    {   
      $result = false;
      $errors['new'] = 'You cannot leave the new password fields blank';
    }
    else if ($pass1 != $pass2) 
    {
      $result = false;
      $errors['new'] = 'The two new passwords you entered did not match. Try again.';
    }
    else if (strlen($pass1) < 6)
    {
      $result = false;
      $errors['new'] = 'Passwords must be at least 6 characters long.';
    }
    else if ($pass1 == $pass){
      $result = false;
      $errors['new'] = 'Your new and old passwords are the same.';
    }

    if (!$result)
    {
      $this->getResponse()->setContentType('application/json');
      $this->renderText(json_encode(array('success' => false, 'errorfields' => key($errors), 'error' => current($errors))));
      return sfView::NONE;
    }

    $guard->setPassword($pass1);
    $guard->save();

    $this->getResponse()->setContentType('application/json');
    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;
  }
}
