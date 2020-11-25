<?php

/**
 * WorkorderPayment form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderPaymentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'workorder_id'    => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_id' => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'amount'          => new sfWidgetFormInput(),
      'created_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'WorkorderPayment', 'column' => 'id', 'required' => false)),
      'workorder_id'    => new sfValidatorPropelChoice(array('model' => 'Workorder', 'column' => 'id', 'required' => false)),
      'supplier_id'     => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'manufacturer_id' => new sfValidatorPropelChoice(array('model' => 'Manufacturer', 'column' => 'id', 'required' => false)),
      'amount'          => new sfValidatorNumber(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderPayment';
  }


}
