<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartInstance filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartInstanceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_variant_id'         => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'custom_name'             => new sfWidgetFormFilterInput(),
      'quantity'                => new sfWidgetFormFilterInput(),
      'unit_price'              => new sfWidgetFormFilterInput(),
      'unit_cost'               => new sfWidgetFormFilterInput(),
      'taxable_hst'             => new sfWidgetFormFilterInput(),
      'taxable_gst'             => new sfWidgetFormFilterInput(),
      'taxable_pst'             => new sfWidgetFormFilterInput(),
      'enviro_levy'             => new sfWidgetFormFilterInput(),
      'battery_levy'            => new sfWidgetFormFilterInput(),
      'supplier_order_item_id'  => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrderItem', 'add_empty' => true)),
      'workorder_item_id'       => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id'    => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'added_by'                => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'allocated'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'delivered'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'include_in_estimate'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'serial_number'           => new sfWidgetFormFilterInput(),
      'date_used'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'is_inventory_adjustment' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'part_variant_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'custom_name'             => new sfValidatorPass(array('required' => false)),
      'quantity'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unit_price'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unit_cost'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_hst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_gst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_pst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'enviro_levy'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'battery_levy'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'supplier_order_item_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SupplierOrderItem', 'column' => 'id')),
      'workorder_item_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'workorder_invoice_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'added_by'                => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Employee', 'column' => 'id')),
      'allocated'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'delivered'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'include_in_estimate'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'serial_number'           => new sfValidatorPass(array('required' => false)),
      'date_used'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'is_inventory_adjustment' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('part_instance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartInstance';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'part_variant_id'         => 'ForeignKey',
      'custom_name'             => 'Text',
      'quantity'                => 'Number',
      'unit_price'              => 'Number',
      'unit_cost'               => 'Number',
      'taxable_hst'             => 'Number',
      'taxable_gst'             => 'Number',
      'taxable_pst'             => 'Number',
      'enviro_levy'             => 'Number',
      'battery_levy'            => 'Number',
      'supplier_order_item_id'  => 'ForeignKey',
      'workorder_item_id'       => 'ForeignKey',
      'workorder_invoice_id'    => 'ForeignKey',
      'added_by'                => 'ForeignKey',
      'allocated'               => 'Boolean',
      'delivered'               => 'Boolean',
      'include_in_estimate'     => 'Boolean',
      'serial_number'           => 'Text',
      'date_used'               => 'Date',
      'is_inventory_adjustment' => 'Boolean',
    );
  }
}
