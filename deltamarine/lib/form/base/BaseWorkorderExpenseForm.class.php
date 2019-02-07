<?php

/**
 * WorkorderExpense form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderExpenseForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'workorder_item_id'    => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'workorder_invoice_id' => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'label'                => new sfWidgetFormInput(),
      'customer_notes'       => new sfWidgetFormTextarea(),
      'internal_notes'       => new sfWidgetFormTextarea(),
      'cost'                 => new sfWidgetFormInput(),
      'price'                => new sfWidgetFormInput(),
      'taxable_hst'          => new sfWidgetFormInput(),
      'taxable_gst'          => new sfWidgetFormInput(),
      'taxable_pst'          => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'WorkorderExpense', 'column' => 'id', 'required' => false)),
      'workorder_item_id'    => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'workorder_invoice_id' => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'label'                => new sfValidatorString(array('max_length' => 255)),
      'customer_notes'       => new sfValidatorString(array('required' => false)),
      'internal_notes'       => new sfValidatorString(array('required' => false)),
      'cost'                 => new sfValidatorNumber(array('required' => false)),
      'price'                => new sfValidatorNumber(array('required' => false)),
      'taxable_hst'          => new sfValidatorNumber(),
      'taxable_gst'          => new sfValidatorNumber(),
      'taxable_pst'          => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('workorder_expense[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderExpense';
  }


}
