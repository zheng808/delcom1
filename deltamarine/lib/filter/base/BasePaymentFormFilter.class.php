<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Payment filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePaymentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_order_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'workorder_id'      => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'amount'            => new sfWidgetFormFilterInput(),
      'tendered'          => new sfWidgetFormFilterInput(),
      'change'            => new sfWidgetFormFilterInput(),
      'payment_method'    => new sfWidgetFormFilterInput(),
      'payment_details'   => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'customer_order_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerOrder', 'column' => 'id')),
      'workorder_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Workorder', 'column' => 'id')),
      'amount'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tendered'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'change'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'payment_method'    => new sfValidatorPass(array('required' => false)),
      'payment_details'   => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'customer_order_id' => 'ForeignKey',
      'workorder_id'      => 'ForeignKey',
      'amount'            => 'Number',
      'tendered'          => 'Number',
      'change'            => 'Number',
      'payment_method'    => 'Text',
      'payment_details'   => 'Text',
      'created_at'        => 'Date',
    );
  }
}
