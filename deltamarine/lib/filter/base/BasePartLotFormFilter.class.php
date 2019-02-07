<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartLot filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartLotFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_variant_id'        => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'supplier_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'SupplierOrderItem', 'add_empty' => true)),
      'quantity_received'      => new sfWidgetFormFilterInput(),
      'quantity_remaining'     => new sfWidgetFormFilterInput(),
      'received_date'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'landed_cost'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'part_variant_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'supplier_order_item_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SupplierOrderItem', 'column' => 'id')),
      'quantity_received'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'quantity_remaining'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'received_date'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'landed_cost'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('part_lot_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartLot';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'part_variant_id'        => 'ForeignKey',
      'supplier_order_item_id' => 'ForeignKey',
      'quantity_received'      => 'Number',
      'quantity_remaining'     => 'Number',
      'received_date'          => 'Date',
      'landed_cost'            => 'Number',
    );
  }
}
