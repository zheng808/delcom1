<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMBasicContactForm extends wfCRMForm
{
  public function configure()
  {
    parent::configure();

    unset($this->widgetSchema['department_name'],$this->widgetSchema['titles'],$this->widgetSchema['middle_name'],
      $this->widgetSchema['fax'],$this->widgetSchema['homepage'],$this->widgetSchema['public_notes']);

    $this->widgetSchema['is_company'] = new sfWidgetFormInputHidden();
    $this->defaults['is_company'] = false;

		$salutation_choices = array(
			'Mr.' => 'Mr.',
			'Mrs.' => 'Mrs.',
			'Ms.' => 'Ms.',
			'Mr. & Mrs.' => 'Mr. & Mrs.',
			'Dr.' => 'Dr.'
		);

    $this->widgetSchema['salutation'] = new sfWidgetFormSelect(array('choices' => $salutation_choices));
		$this->validatorSchema['salutation'] = new sfValidatorChoice(array('required' => false, 'choices' => array_keys($salutation_choices)));

  }

}
