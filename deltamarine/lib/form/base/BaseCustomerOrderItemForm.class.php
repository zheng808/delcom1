<?php

/**
 * CustomerOrderItem form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerOrderItemForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'customer_order_id'  => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'part_instance_id'   => new sfWidgetFormPropelChoice(array('model' => 'PartInstance', 'add_empty' => true)),
      'quantity_completed' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'CustomerOrderItem', 'column' => 'id', 'required' => false)),
      'customer_order_id'  => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'part_instance_id'   => new sfValidatorPropelChoice(array('model' => 'PartInstance', 'column' => 'id', 'required' => false)),
      'quantity_completed' => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('customer_order_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerOrderItem';
  }


}
