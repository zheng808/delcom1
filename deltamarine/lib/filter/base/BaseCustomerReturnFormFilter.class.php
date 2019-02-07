<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerReturn filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerReturnFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_order_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'invoice_id'        => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'date_returned'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'customer_order_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerOrder', 'column' => 'id')),
      'invoice_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'date_returned'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('customer_return_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerReturn';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'customer_order_id' => 'ForeignKey',
      'invoice_id'        => 'ForeignKey',
      'date_returned'     => 'Date',
    );
  }
}
