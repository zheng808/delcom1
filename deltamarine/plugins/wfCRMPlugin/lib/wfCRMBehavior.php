<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */

class wfCRMBehavior
{
  public function getCRM($object)
  {
    if (method_exists($object, 'getwfCRM'))
    {
      return $object->getwfCRM();
    }

    return null;
  }
  
  public function getName($object)
  {
    $crm = $this->getCRM($object);
    if ($crm)
    {
      return (string) $crm;
    } 
    
    return null;
  }
    
}
