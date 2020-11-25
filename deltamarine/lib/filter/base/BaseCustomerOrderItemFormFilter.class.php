<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerOrderItem filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerOrderItemFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_order_id'  => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'part_instance_id'   => new sfWidgetFormPropelChoice(array('model' => 'PartInstance', 'add_empty' => true)),
      'quantity_completed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'customer_order_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerOrder', 'column' => 'id')),
      'part_instance_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartInstance', 'column' => 'id')),
      'quantity_completed' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('customer_order_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerOrderItem';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'customer_order_id'  => 'ForeignKey',
      'part_instance_id'   => 'ForeignKey',
      'quantity_completed' => 'Number',
    );
  }
}
