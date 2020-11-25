<?php

/**
 * WorkorderItemFile form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderItemFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'workorder_item_id' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'file_id'           => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'WorkorderItemFile', 'column' => 'id', 'required' => false)),
      'workorder_item_id' => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'file_id'           => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemFile';
  }


}
