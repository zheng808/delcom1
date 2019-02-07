<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Invoice filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseInvoiceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'receivable'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'customer_id'     => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_id' => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'subtotal'        => new sfWidgetFormFilterInput(),
      'shipping'        => new sfWidgetFormFilterInput(),
      'hst'             => new sfWidgetFormFilterInput(),
      'gst'             => new sfWidgetFormFilterInput(),
      'pst'             => new sfWidgetFormFilterInput(),
      'enviro_levy'     => new sfWidgetFormFilterInput(),
      'battery_levy'    => new sfWidgetFormFilterInput(),
      'duties'          => new sfWidgetFormFilterInput(),
      'total'           => new sfWidgetFormFilterInput(),
      'issued_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'payable_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'archived'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'receivable'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'customer_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Customer', 'column' => 'id')),
      'supplier_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Supplier', 'column' => 'id')),
      'manufacturer_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Manufacturer', 'column' => 'id')),
      'subtotal'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'hst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'pst'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'enviro_levy'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'battery_levy'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duties'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'issued_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'payable_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'archived'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('invoice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invoice';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'receivable'      => 'Boolean',
      'customer_id'     => 'ForeignKey',
      'supplier_id'     => 'ForeignKey',
      'manufacturer_id' => 'ForeignKey',
      'subtotal'        => 'Number',
      'shipping'        => 'Number',
      'hst'             => 'Number',
      'gst'             => 'Number',
      'pst'             => 'Number',
      'enviro_levy'     => 'Number',
      'battery_levy'    => 'Number',
      'duties'          => 'Number',
      'total'           => 'Number',
      'issued_date'     => 'Date',
      'payable_date'    => 'Date',
      'archived'        => 'Boolean',
    );
  }
}
