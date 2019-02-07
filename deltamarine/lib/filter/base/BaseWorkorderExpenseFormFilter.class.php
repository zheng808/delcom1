<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderExpense filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderExpenseFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_item_id'    => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id' => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'label'                => new sfWidgetFormFilterInput(),
      'customer_notes'       => new sfWidgetFormFilterInput(),
      'internal_notes'       => new sfWidgetFormFilterInput(),
      'cost'                 => new sfWidgetFormFilterInput(),
      'price'                => new sfWidgetFormFilterInput(),
      'taxable_hst'          => new sfWidgetFormFilterInput(),
      'taxable_gst'          => new sfWidgetFormFilterInput(),
      'taxable_pst'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'workorder_item_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'workorder_invoice_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'label'                => new sfValidatorPass(array('required' => false)),
      'customer_notes'       => new sfValidatorPass(array('required' => false)),
      'internal_notes'       => new sfValidatorPass(array('required' => false)),
      'cost'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_hst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_gst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxable_pst'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('workorder_expense_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderExpense';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'workorder_item_id'    => 'ForeignKey',
      'workorder_invoice_id' => 'ForeignKey',
      'label'                => 'Text',
      'customer_notes'       => 'Text',
      'internal_notes'       => 'Text',
      'cost'                 => 'Number',
      'price'                => 'Number',
      'taxable_hst'          => 'Number',
      'taxable_gst'          => 'Number',
      'taxable_pst'          => 'Number',
    );
  }
}
