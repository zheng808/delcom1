<?php

/**
 * PartLot form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartLotForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'part_variant_id'        => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'supplier_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrderItem', 'add_empty' => true)),
      'quantity_received'      => new sfWidgetFormInput(),
      'quantity_remaining'     => new sfWidgetFormInput(),
      'received_date'          => new sfWidgetFormDateTime(),
      'landed_cost'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorPropelChoice(array('model' => 'PartLot', 'column' => 'id', 'required' => false)),
      'part_variant_id'        => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'supplier_order_item_id' => new sfValidatorPropelChoice(array('model' => 'SupplierOrderItem', 'column' => 'id', 'required' => false)),
      'quantity_received'      => new sfValidatorNumber(),
      'quantity_remaining'     => new sfValidatorNumber(),
      'received_date'          => new sfValidatorDateTime(),
      'landed_cost'            => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('part_lot[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartLot';
  }


}
