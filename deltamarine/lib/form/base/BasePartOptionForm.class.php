<?php

/**
 * PartOption form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartOptionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'part_id'  => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => false)),
      'name'     => new sfWidgetFormInput(),
      'is_color' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorPropelChoice(array('model' => 'PartOption', 'column' => 'id', 'required' => false)),
      'part_id'  => new sfValidatorPropelChoice(array('model' => 'Part', 'column' => 'id')),
      'name'     => new sfValidatorString(array('max_length' => 255)),
      'is_color' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('part_option[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartOption';
  }


}
