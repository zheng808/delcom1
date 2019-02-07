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
 * @version    SVN: $Id: sfGuardPermissionPeer.php 9999 2008-06-29 21:24:44Z fabien $
 */
class sfGuardPermissionPeer extends PluginsfGuardPermissionPeer
{

  public static function getSortedPermissions($c = null)
  {
    if (!$c) $c = new Criteria();

    $sorted = array();

    $perms = self::doSelect($c);
    foreach ($perms AS $perm)
    {
      if (strpos($perm->getDescription(), '::') !== false)
      {
        $parts = explode('::', $perm->getDescription());
        $prefix = array_shift($parts);
        $perm->setDescription(implode('::', $parts));

        if (strpos($prefix, '_') !== false)
        {
          $parts = explode('_', $prefix);
          $prefix = array_shift($parts);
        }

        if (!isset($sorted[$prefix])) $sorted[$prefix] = array();
        $sorted[$prefix][] = $perm;
      }
      else
      {
        if (!isset($sorted['General'])) $sorted['General'] = array();
        $sorted['General'][] = $perm;
      }
    }

    return $sorted;
  }

  public static function getOMClass()
  {
    return 'lib.model.sfGuardPlugin.sfGuardPermission';
  }

}
