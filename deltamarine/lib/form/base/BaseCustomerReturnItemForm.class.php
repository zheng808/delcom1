<?php

/**
 * CustomerReturnItem form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerReturnItemForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'customer_return_id'     => new sfWidgetFormPropelChoice(array('model' => 'CustomerReturn', 'add_empty' => true)),
      'customer_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrderItem', 'add_empty' => true)),
      'part_instance_id'       => new sfWidgetFormPropelChoice(array('model' => 'PartInstance', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorPropelChoice(array('model' => 'CustomerReturnItem', 'column' => 'id', 'required' => false)),
      'customer_return_id'     => new sfValidatorPropelChoice(array('model' => 'CustomerReturn', 'column' => 'id', 'required' => false)),
      'customer_order_item_id' => new sfValidatorPropelChoice(array('model' => 'CustomerOrderItem', 'column' => 'id', 'required' => false)),
      'part_instance_id'       => new sfValidatorPropelChoice(array('model' => 'PartInstance', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_return_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerReturnItem';
  }


}
