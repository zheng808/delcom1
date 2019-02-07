<?php

/**
 * wfCRMAddress form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMAddressForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'crm_id'  => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'type'    => new sfWidgetFormInput(),
      'line1'   => new sfWidgetFormInput(),
      'line2'   => new sfWidgetFormInput(),
      'city'    => new sfWidgetFormInput(),
      'region'  => new sfWidgetFormInput(),
      'postal'  => new sfWidgetFormInput(),
      'country' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorPropelChoice(array('model' => 'wfCRMAddress', 'column' => 'id', 'required' => false)),
      'crm_id'  => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id', 'required' => false)),
      'type'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'line1'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'line2'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'    => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'region'  => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'postal'  => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'country' => new sfValidatorString(array('max_length' => 2)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMAddress';
  }


}
