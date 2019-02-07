<?php

/**
 * Timelog form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseTimelogForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'employee_id'          => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'workorder_item_id'    => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id' => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'labour_type_id'       => new sfWidgetFormPropelChoice(array('model' => 'LabourType', 'add_empty' => true)),
      'nonbill_type_id'      => new sfWidgetFormPropelChoice(array('model' => 'NonbillType', 'add_empty' => true)),
      'start_time'           => new sfWidgetFormDateTime(),
      'end_time'             => new sfWidgetFormDateTime(),
      'payroll_hours'        => new sfWidgetFormInput(),
      'billable_hours'       => new sfWidgetFormInput(),
      'cost'                 => new sfWidgetFormInput(),
      'taxable_hst'          => new sfWidgetFormInput(),
      'taxable_gst'          => new sfWidgetFormInput(),
      'taxable_pst'          => new sfWidgetFormInput(),
      'employee_notes'       => new sfWidgetFormTextarea(),
      'admin_notes'          => new sfWidgetFormTextarea(),
      'admin_flagged'        => new sfWidgetFormInputCheckbox(),
      'approved'             => new sfWidgetFormInputCheckbox(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'Timelog', 'column' => 'id', 'required' => false)),
      'employee_id'          => new sfValidatorPropelChoice(array('model' => 'Employee', 'column' => 'id', 'required' => false)),
      'workorder_item_id'    => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'workorder_invoice_id' => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'labour_type_id'       => new sfValidatorPropelChoice(array('model' => 'LabourType', 'column' => 'id', 'required' => false)),
      'nonbill_type_id'      => new sfValidatorPropelChoice(array('model' => 'NonbillType', 'column' => 'id', 'required' => false)),
      'start_time'           => new sfValidatorDateTime(array('required' => false)),
      'end_time'             => new sfValidatorDateTime(array('required' => false)),
      'payroll_hours'        => new sfValidatorNumber(),
      'billable_hours'       => new sfValidatorNumber(),
      'cost'                 => new sfValidatorNumber(array('required' => false)),
      'taxable_hst'          => new sfValidatorNumber(),
      'taxable_gst'          => new sfValidatorNumber(),
      'taxable_pst'          => new sfValidatorNumber(),
      'employee_notes'       => new sfValidatorString(array('required' => false)),
      'admin_notes'          => new sfValidatorString(array('required' => false)),
      'admin_flagged'        => new sfValidatorBoolean(),
      'approved'             => new sfValidatorBoolean(),
      'created_at'           => new sfValidatorDateTime(array('required' => false)),
      'updated_at'           => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('timelog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Timelog';
  }


}
