<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * SupplierOrderItem filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseSupplierOrderItemFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'supplier_order_id'  => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrder', 'add_empty' => true)),
      'part_variant_id'    => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'quantity_requested' => new sfWidgetFormFilterInput(),
      'quantity_completed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'supplier_order_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SupplierOrder', 'column' => 'id')),
      'part_variant_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'quantity_requested' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'quantity_completed' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('supplier_order_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SupplierOrderItem';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'supplier_order_id'  => 'ForeignKey',
      'part_variant_id'    => 'ForeignKey',
      'quantity_requested' => 'Number',
      'quantity_completed' => 'Number',
    );
  }
}
