<?php

/**
 * Customer form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'wf_crm_id'     => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => false)),
      'guard_user_id' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'pst_number'    => new sfWidgetFormInput(),
      'hidden'        => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => false)),
      'wf_crm_id'     => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id')),
      'guard_user_id' => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id', 'required' => false)),
      'pst_number'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hidden'        => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('customer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Customer';
  }


}
