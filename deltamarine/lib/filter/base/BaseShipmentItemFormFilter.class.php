<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ShipmentItem filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseShipmentItemFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'shipment_id'            => new sfWidgetFormPropelChoice(array('model' => 'Shipment', 'add_empty' => true)),
      'customer_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrderItem', 'add_empty' => true)),
      'quantity'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'shipment_id'            => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Shipment', 'column' => 'id')),
      'customer_order_item_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerOrderItem', 'column' => 'id')),
      'quantity'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('shipment_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ShipmentItem';
  }

  public function getFields()
  {
    return array(
      'shipment_id'            => 'ForeignKey',
      'customer_order_item_id' => 'ForeignKey',
      'quantity'               => 'Number',
      'id'                     => 'Number',
    );
  }
}
