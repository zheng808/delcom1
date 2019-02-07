<?php
/**
 * This file is part of the wfCRMplugin package.
 * 
 * @package    wfCRMplugin
 * @author Sergey Stepanov <sergey@acobby.com>
 */
class PluginwfCRMContactForm extends wfCRMForm
{
  public function configure()
  {
    parent::configure();

    unset($this->widgetSchema['department_name']);
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

		$this->validatorSchema['homepage'] = new sfValidatorUrl(array('required' => false));
		
		if($this->object->isNew()) { 
		  $addressForm = new PluginwfCRMAddressForm();
		  unset($addressForm['id'], $addressForm['crm_id']);
		  $this->embedForm('address', $addressForm);
		}
  }

	public function bind(array $taintedValues = null, array $taintedFiles = null)
	{
		$ret = parent::bind($taintedValues, $taintedFiles);
		
		foreach ($this->embeddedForms as $name => $form)
		{
			$this->embeddedForms[$name]->isBound = true;
			$this->embeddedForms[$name]->values = $this->values[$name];
		}
		return $ret;
	}
	
	public function saveEmbeddedForms($con = null, $forms = null)
	{
		foreach($this->getEmbeddedForms() as $form) {
			if (!$form->getObject()->getCrmId()) {
				$form->getObject()->setCrmId($this->getObject()->getId());
			}
		}
		parent::saveEmbeddedForms($con, $forms);
	}
}