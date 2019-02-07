<?php

/**
 * PartFile form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'         => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'file_id'         => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'id'              => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'part_id'         => new sfValidatorPropelChoice(array('model' => 'Part', 'column' => 'id', 'required' => false)),
      'part_variant_id' => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'file_id'         => new sfValidatorPropelChoice(array('model' => 'File', 'column' => 'id', 'required' => false)),
      'id'              => new sfValidatorPropelChoice(array('model' => 'PartFile', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartFile';
  }


}
