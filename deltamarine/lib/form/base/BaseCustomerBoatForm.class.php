<?php

/**
 * CustomerBoat form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerBoatForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'customer_id'   => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'serial_number' => new sfWidgetFormInput(),
      'make'          => new sfWidgetFormInput(),
      'model'         => new sfWidgetFormInput(),
      'name'          => new sfWidgetFormInput(),
      'registration'  => new sfWidgetFormInput(),
      'notes'         => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'CustomerBoat', 'column' => 'id', 'required' => false)),
      'customer_id'   => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => false)),
      'serial_number' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'make'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'model'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'name'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'registration'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'notes'         => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_boat[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerBoat';
  }


}
