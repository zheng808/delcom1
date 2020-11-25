<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */


class PluginwfCRMDepartmentForm extends wfCRMForm
{

  public function configure()
  { 
    parent::configure();
    unset($this->widgetSchema['first_name']);
    unset($this->widgetSchema['middle_name']);
    unset($this->widgetSchema['last_name']);
    unset($this->widgetSchema['salutation']);
    unset($this->widgetSchema['titles']);
    unset($this->widgetSchema['job_title']);
    unset($this->widgetSchema['is_in_addressbook']);
    unset($this->widgetSchema['alpha_name']);
    
    $this->widgetSchema['is_company'] = new sfWidgetFormInputHidden();
    $this->defaults['is_company'] = true;
  }
}
