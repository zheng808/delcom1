<?php

/**
 * PartInstance form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartInstanceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'part_variant_id'         => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'custom_name'             => new sfWidgetFormInput(),
      'quantity'                => new sfWidgetFormInput(),
      'unit_price'              => new sfWidgetFormInput(),
      'unit_cost'               => new sfWidgetFormInput(),
      'taxable_hst'             => new sfWidgetFormInput(),
      'taxable_gst'             => new sfWidgetFormInput(),
      'taxable_pst'             => new sfWidgetFormInput(),
      'enviro_levy'             => new sfWidgetFormInput(),
      'battery_levy'            => new sfWidgetFormInput(),
      'supplier_order_item_id'  => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrderItem', 'add_empty' => true)),
      'workorder_item_id'       => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id'    => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'added_by'                => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'allocated'               => new sfWidgetFormInputCheckbox(),
      'delivered'               => new sfWidgetFormInputCheckbox(),
      'include_in_estimate'     => new sfWidgetFormInputCheckbox(),
      'serial_number'           => new sfWidgetFormInput(),
      'date_used'               => new sfWidgetFormDateTime(),
      'is_inventory_adjustment' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorPropelChoice(array('model' => 'PartInstance', 'column' => 'id', 'required' => false)),
      'part_variant_id'         => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'custom_name'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'quantity'                => new sfValidatorNumber(),
      'unit_price'              => new sfValidatorNumber(),
      'unit_cost'               => new sfValidatorNumber(array('required' => false)),
      'taxable_hst'             => new sfValidatorNumber(),
      'taxable_gst'             => new sfValidatorNumber(),
      'taxable_pst'             => new sfValidatorNumber(),
      'enviro_levy'             => new sfValidatorNumber(),
      'battery_levy'            => new sfValidatorNumber(),
      'supplier_order_item_id'  => new sfValidatorPropelChoice(array('model' => 'SupplierOrderItem', 'column' => 'id', 'required' => false)),
      'workorder_item_id'       => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'workorder_invoice_id'    => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'added_by'                => new sfValidatorPropelChoice(array('model' => 'Employee', 'column' => 'id', 'required' => false)),
      'allocated'               => new sfValidatorBoolean(),
      'delivered'               => new sfValidatorBoolean(),
      'include_in_estimate'     => new sfValidatorBoolean(),
      'serial_number'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'date_used'               => new sfValidatorDateTime(array('required' => false)),
      'is_inventory_adjustment' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('part_instance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartInstance';
  }


}
