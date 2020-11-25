<?php

/**
 * sfGuardUser form for admin.
 *
 * @package    form
 * @subpackage sf_guard_user
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 13000 2008-11-14 10:44:57Z noel $
 */
class sfGuardUserBasicAdminForm extends BasesfGuardUserForm
{
  protected
    $pkName = null;

  public function configure()
  {
    unset(
      $this['last_login'],
      $this['created_at'],
      $this['salt'],
      $this['algorithm'],
      $this['sf_guard_user_group_list'],
      $this['sf_guard_user_permission_list'],
      $this['is_super_admin']
    );

    $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password']->setOption('required', false);
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];

    $this->widgetSchema->moveField('password_again', 'after', 'password');

    $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again', array(), array('invalid' => 'The two passwords must be the same.')));

    $this->widgetSchema['password']->setLabel('New Password');
    $this->widgetSchema['password_again']->setLabel('Re-enter Password');
    $this->widgetSchema['is_active']->setLabel('Allow Login');

  }

}
