<?php

/**
 * Employee form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class EmployeeForm extends BaseEmployeeForm
{
  public function configure()
  {
    unset($this['guard_user_id'], $this['hidden']);
  }
}
