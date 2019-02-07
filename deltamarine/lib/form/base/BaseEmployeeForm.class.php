<?php

/**
 * Employee form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseEmployeeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'wf_crm_id'     => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => false)),
      'guard_user_id' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'payrate'       => new sfWidgetFormInput(),
      'hidden'        => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'Employee', 'column' => 'id', 'required' => false)),
      'wf_crm_id'     => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id')),
      'guard_user_id' => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id', 'required' => false)),
      'payrate'       => new sfValidatorNumber(array('required' => false)),
      'hidden'        => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('employee[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Employee';
  }


}
