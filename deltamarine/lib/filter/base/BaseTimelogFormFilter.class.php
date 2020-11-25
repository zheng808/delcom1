<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Timelog filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseTimelogFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'employee_id'          => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'workorder_item_id'    => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id' => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'labour_type_id'       => new sfWidgetFormPropelChoice(array('model' => 'LabourType', 'add_empty' => true)),
      'nonbill_type_id'      => new sfWidgetFormPropelChoice(array('model' => 'NonbillType', 'add_empty' => true)),
      'start_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'end_time'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'payroll_hours'        => new sfWidgetFormFilterInput(),
      'billable_hours'       => new sfWidgetFormFilterInput(),
      'cost'                 => new sfWidgetFormFilterInput(),
      'taxable_hst'          => new sfWidgetFormFilterInput(),
      'taxable_gst'          => new sfWidgetFormFilterInput(),
      'taxable_pst'          => new sfWidgetFormFilterInput(),
      'employee_notes'       => new sfWidgetFormFilterInput(),
      'admin_notes'          => new sfWidgetFormFilterInput(),
      'admin_flagged'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'approved'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'employee_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Employee', 'column' => 'id')),
      'workorder_item_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'workorder_invoice_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'labour_type_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'LabourType', 'column' => 'id')),
      'nonbill_type_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'NonbillType', 'column' => 'id')),
      'start_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'end_time'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'payroll_hours'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'billable_hours'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'cost'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_hst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_gst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_pst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'employee_notes'       => new sfValidatorPass(array('required' => false)),
      'admin_notes'          => new sfValidatorPass(array('required' => false)),
      'admin_flagged'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'approved'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('timelog_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Timelog';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'employee_id'          => 'ForeignKey',
      'workorder_item_id'    => 'ForeignKey',
      'workorder_invoice_id' => 'ForeignKey',
      'labour_type_id'       => 'ForeignKey',
      'nonbill_type_id'      => 'ForeignKey',
      'start_time'           => 'Date',
      'end_time'             => 'Date',
      'payroll_hours'        => 'Number',
      'billable_hours'       => 'Number',
      'cost'                 => 'Number',
      'taxable_hst'          => 'Number',
      'taxable_gst'          => 'Number',
      'taxable_pst'          => 'Number',
      'employee_notes'       => 'Text',
      'admin_notes'          => 'Text',
      'admin_flagged'        => 'Boolean',
      'approved'             => 'Boolean',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
