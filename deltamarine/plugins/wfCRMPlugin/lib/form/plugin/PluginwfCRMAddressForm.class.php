<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMAddressForm extends BasewfCRMAddressForm
{
  public function configure()
  {
    $this->widgetSchema['crm_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['type'] = new sfWidgetFormChoice(array('choices'=>wfCRMAddress::getTypes()));
    $this->widgetSchema['country'] = new sfWidgetFormI18nSelectCountry(array('culture'=>'en_US','add_empty'=>true));
  }
  
}
