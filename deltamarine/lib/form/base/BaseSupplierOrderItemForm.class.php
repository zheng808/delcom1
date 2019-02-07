<?php

/**
 * SupplierOrderItem form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseSupplierOrderItemForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'supplier_order_id'  => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrder', 'add_empty' => true)),
      'part_variant_id'    => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'quantity_requested' => new sfWidgetFormInput(),
      'quantity_completed' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'SupplierOrderItem', 'column' => 'id', 'required' => false)),
      'supplier_order_id'  => new sfValidatorPropelChoice(array('model' => 'SupplierOrder', 'column' => 'id', 'required' => false)),
      'part_variant_id'    => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'quantity_requested' => new sfValidatorNumber(),
      'quantity_completed' => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('supplier_order_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SupplierOrderItem';
  }


}
