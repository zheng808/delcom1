<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUser.php 9999 2008-06-29 21:24:44Z fabien $
 */
class sfGuardUser extends PluginsfGuardUser
{
  public function getPermissionIds()
  {
    $perms = $this->getPermissions();
    $perm_ids = array();

    foreach ($perms as $perm)
    {
      $perm_ids[$perm->getId()] = $perm;
    }

    unset($perms);

    return $perm_ids;
  }

  //same as above, but for getting the userpermission records
  public function getIndexedUserPermissions()
  {
    $perms = $this->getsfGuardUserPermissions();
    $perm_ids = array();
    foreach ($perms AS $perm)
    {
      $perm_ids[$perm->getPermissionId()] = $perm;
    }

    unset($perms);

    return $perm_ids;
  }

}
