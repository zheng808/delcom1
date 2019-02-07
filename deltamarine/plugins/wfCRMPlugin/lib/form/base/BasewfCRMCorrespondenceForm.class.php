<?php

/**
 * wfCRMCorrespondence form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMCorrespondenceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'wf_crm_id' => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'received'  => new sfWidgetFormInputCheckbox(),
      'method'    => new sfWidgetFormInput(),
      'subject'   => new sfWidgetFormInput(),
      'message'   => new sfWidgetFormTextarea(),
      'whendone'  => new sfWidgetFormDateTime(),
      'is_new'    => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'wfCRMCorrespondence', 'column' => 'id', 'required' => false)),
      'wf_crm_id' => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id', 'required' => false)),
      'received'  => new sfValidatorBoolean(),
      'method'    => new sfValidatorString(array('max_length' => 16)),
      'subject'   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'message'   => new sfValidatorString(array('required' => false)),
      'whendone'  => new sfValidatorDateTime(),
      'is_new'    => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_correspondence[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMCorrespondence';
  }


}
