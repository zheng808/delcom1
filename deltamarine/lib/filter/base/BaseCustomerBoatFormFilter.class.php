<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerBoat filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerBoatFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id'   => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'serial_number' => new sfWidgetFormFilterInput(),
      'make'          => new sfWidgetFormFilterInput(),
      'model'         => new sfWidgetFormFilterInput(),
      'name'          => new sfWidgetFormFilterInput(),
      'registration'  => new sfWidgetFormFilterInput(),
      'notes'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'customer_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Customer', 'column' => 'id')),
      'serial_number' => new sfValidatorPass(array('required' => false)),
      'make'          => new sfValidatorPass(array('required' => false)),
      'model'         => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'registration'  => new sfValidatorPass(array('required' => false)),
      'notes'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_boat_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerBoat';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'customer_id'   => 'ForeignKey',
      'serial_number' => 'Text',
      'make'          => 'Text',
      'model'         => 'Text',
      'name'          => 'Text',
      'registration'  => 'Text',
      'notes'         => 'Text',
    );
  }
}
