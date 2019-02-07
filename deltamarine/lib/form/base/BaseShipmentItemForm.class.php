<?php

/**
 * ShipmentItem form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseShipmentItemForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'shipment_id'            => new sfWidgetFormPropelChoice(array('model' => 'Shipment', 'add_empty' => true)),
      'customer_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrderItem', 'add_empty' => true)),
      'quantity'               => new sfWidgetFormInput(),
      'id'                     => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'shipment_id'            => new sfValidatorPropelChoice(array('model' => 'Shipment', 'column' => 'id', 'required' => false)),
      'customer_order_item_id' => new sfValidatorPropelChoice(array('model' => 'CustomerOrderItem', 'column' => 'id', 'required' => false)),
      'quantity'               => new sfValidatorNumber(array('required' => false)),
      'id'                     => new sfValidatorPropelChoice(array('model' => 'ShipmentItem', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('shipment_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ShipmentItem';
  }


}
