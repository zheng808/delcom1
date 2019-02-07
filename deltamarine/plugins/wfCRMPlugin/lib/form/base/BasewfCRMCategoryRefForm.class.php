<?php

/**
 * wfCRMCategoryRef form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMCategoryRefForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'crm_id'      => new sfWidgetFormInputHidden(),
      'category_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'crm_id'      => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id', 'required' => false)),
      'category_id' => new sfValidatorPropelChoice(array('model' => 'wfCRMCategory', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_category_ref[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMCategoryRef';
  }


}
