<?php

/**
 * sfGuardUser form for admin.
 *
 * @package    form
 * @subpackage sf_guard_user
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 13000 2008-11-14 10:44:57Z noel $
 */
class sfGuardUserPermissionsForm extends BasesfGuardUserForm
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
      $this['is_super_admin'],
      $this['username'],
      $this['is_active'],
      $this['password']
    );

    $this->widgetSchema['sf_guard_user_group_list']->setOption('expanded', true);
    $this->widgetSchema['sf_guard_user_permission_list']->setOption('expanded', true);

    $this->widgetSchema['sf_guard_user_group_list']->setLabel('Groups');
  }
}
