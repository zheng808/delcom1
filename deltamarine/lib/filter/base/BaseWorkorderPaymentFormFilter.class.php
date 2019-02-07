<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderPayment filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderPaymentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_id'    => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_id' => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'amount'          => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'workorder_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Workorder', 'column' => 'id')),
      'supplier_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Supplier', 'column' => 'id')),
      'manufacturer_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Manufacturer', 'column' => 'id')),
      'amount'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('workorder_payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderPayment';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'workorder_id'    => 'ForeignKey',
      'supplier_id'     => 'ForeignKey',
      'manufacturer_id' => 'ForeignKey',
      'amount'          => 'Number',
      'created_at'      => 'Date',
    );
  }
}
