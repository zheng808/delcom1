<?php

/**
 * Supplier form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseSupplierForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'wf_crm_id'      => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => false)),
      'account_number' => new sfWidgetFormInput(),
      'credit_limit'   => new sfWidgetFormInput(),
      'net_days'       => new sfWidgetFormInput(),
      'hidden'         => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'wf_crm_id'      => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id')),
      'account_number' => new sfValidatorString(array('max_length' => 127, 'required' => false)),
      'credit_limit'   => new sfValidatorNumber(array('required' => false)),
      'net_days'       => new sfValidatorInteger(),
      'hidden'         => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('supplier[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Supplier';
  }


}
