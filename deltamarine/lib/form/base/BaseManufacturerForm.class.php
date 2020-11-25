<?php

/**
 * Manufacturer form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseManufacturerForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'wf_crm_id' => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => false)),
      'hidden'    => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'Manufacturer', 'column' => 'id', 'required' => false)),
      'wf_crm_id' => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id')),
      'hidden'    => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('manufacturer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Manufacturer';
  }


}
