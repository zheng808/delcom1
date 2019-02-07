<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */


if (sfConfig::get('app_wf_crm_plugin_routes_register', true) && in_array('wfCRMPlugin', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect('routing.load_configuration', array('wfCRMRouting', 'listenToRoutingLoadConfigurationEvent'));
}

sfPropelBehavior::registerMethods('wfCRMBehavior', array (
  array ('wfCRMBehavior', 'getCRM'),
  array ('wfCRMBehavior', 'getName'),
));
