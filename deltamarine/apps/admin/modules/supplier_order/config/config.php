<?php
if (sfConfig::get('app_supplier_order_plugin_routes_register', true) && in_array('sfSupplierPlugin', sfConfig::get('sf_enabled_modules', array())))
{
  /*$this->dispatcher->connect('routing.load_configuration', array('sfSupplierRouting', 'listenToRoutingLoadConfigurationEvent'));*/
}
?>
