<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerReturnItem filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerReturnItemFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_return_id'     => new sfWidgetFormPropelChoice(array('model' => 'CustomerReturn', 'add_empty' => true)),
      'customer_order_item_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrderItem', 'add_empty' => true)),
      'part_instance_id'       => new sfWidgetFormPropelChoice(array('model' => 'PartInstance', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'customer_return_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerReturn', 'column' => 'id')),
      'customer_order_item_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerOrderItem', 'column' => 'id')),
      'part_instance_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartInstance', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('customer_return_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerReturnItem';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'customer_return_id'     => 'ForeignKey',
      'customer_order_item_id' => 'ForeignKey',
      'part_instance_id'       => 'ForeignKey',
    );
  }
}
