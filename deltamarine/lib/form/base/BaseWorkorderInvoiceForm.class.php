<?php

/**
 * WorkorderInvoice form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderInvoiceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'workorder_id' => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'invoice_id'   => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'is_estimate'  => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'WorkorderInvoice', 'column' => 'id', 'required' => false)),
      'workorder_id' => new sfValidatorPropelChoice(array('model' => 'Workorder', 'column' => 'id', 'required' => false)),
      'invoice_id'   => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'is_estimate'  => new sfValidatorBoolean(),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_invoice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderInvoice';
  }


}
