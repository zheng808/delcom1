<?php

/**
 * WorkorderItem form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderItemForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'workorder_id'    => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => false)),
      'label'           => new sfWidgetFormInput(),
      'lft'             => new sfWidgetFormInput(),
      'rgt'             => new sfWidgetFormInput(),
      'owner_company'   => new sfWidgetFormInput(),
      'labour_estimate' => new sfWidgetFormInput(),
      'labour_actual'   => new sfWidgetFormInput(),
      'other_estimate'  => new sfWidgetFormInput(),
      'other_actual'    => new sfWidgetFormInput(),
      'part_estimate'   => new sfWidgetFormInput(),
      'part_actual'     => new sfWidgetFormInput(),
      'completed'       => new sfWidgetFormInputCheckbox(),
      'completed_by'    => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'completed_date'  => new sfWidgetFormDateTime(),
      'customer_notes'  => new sfWidgetFormTextarea(),
      'internal_notes'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'workorder_id'    => new sfValidatorPropelChoice(array('model' => 'Workorder', 'column' => 'id')),
      'label'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'lft'             => new sfValidatorInteger(),
      'rgt'             => new sfValidatorInteger(),
      'owner_company'   => new sfValidatorInteger(array('required' => false)),
      'labour_estimate' => new sfValidatorNumber(array('required' => false)),
      'labour_actual'   => new sfValidatorNumber(array('required' => false)),
      'other_estimate'  => new sfValidatorNumber(array('required' => false)),
      'other_actual'    => new sfValidatorNumber(array('required' => false)),
      'part_estimate'   => new sfValidatorNumber(array('required' => false)),
      'part_actual'     => new sfValidatorNumber(array('required' => false)),
      'completed'       => new sfValidatorBoolean(),
      'completed_by'    => new sfValidatorPropelChoice(array('model' => 'Employee', 'column' => 'id', 'required' => false)),
      'completed_date'  => new sfValidatorDateTime(array('required' => false)),
      'customer_notes'  => new sfValidatorString(array('required' => false)),
      'internal_notes'  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItem';
  }


}
