<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderInvoice filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderInvoiceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_id' => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'invoice_id'   => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'is_estimate'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'workorder_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Workorder', 'column' => 'id')),
      'invoice_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'is_estimate'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('workorder_invoice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderInvoice';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'workorder_id' => 'ForeignKey',
      'invoice_id'   => 'ForeignKey',
      'is_estimate'  => 'Boolean',
      'created_at'   => 'Date',
    );
  }
}
