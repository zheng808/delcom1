<?php
if (sfConfig::get('app_work_order_plugin_routes_register', true) && in_array('sfWorkorderPlugin', sfConfig::get('sf_enabled_modules', array())))
{
  /*$this->dispatcher->connect('routing.load_configuration', array('sfSupplierRouting', 'listenToRoutingLoadConfigurationEvent'));*/
}
?>
