<?php

/**
 * Part form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'part_category_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartCategory', 'add_empty' => true)),
      'name'              => new sfWidgetFormInput(),
      'description'       => new sfWidgetFormTextarea(),
      'has_serial_number' => new sfWidgetFormInputCheckbox(),
      'is_multisku'       => new sfWidgetFormInputCheckbox(),
      'manufacturer_id'   => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'active'            => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'Part', 'column' => 'id', 'required' => false)),
      'part_category_id'  => new sfValidatorPropelChoice(array('model' => 'PartCategory', 'column' => 'id', 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255)),
      'description'       => new sfValidatorString(array('required' => false)),
      'has_serial_number' => new sfValidatorBoolean(),
      'is_multisku'       => new sfValidatorBoolean(),
      'manufacturer_id'   => new sfValidatorPropelChoice(array('model' => 'Manufacturer', 'column' => 'id', 'required' => false)),
      'active'            => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('part[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Part';
  }


}
