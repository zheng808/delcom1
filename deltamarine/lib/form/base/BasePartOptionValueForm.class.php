<?php

/**
 * PartOptionValue form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartOptionValueForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => false)),
      'part_option_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartOption', 'add_empty' => false)),
      'value'           => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'PartOptionValue', 'column' => 'id', 'required' => false)),
      'part_variant_id' => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id')),
      'part_option_id'  => new sfValidatorPropelChoice(array('model' => 'PartOption', 'column' => 'id')),
      'value'           => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('part_option_value[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartOptionValue';
  }


}
