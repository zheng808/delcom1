<?php

/**
 * Payment form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePaymentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'customer_order_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'workorder_id'      => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'amount'            => new sfWidgetFormInput(),
      'tendered'          => new sfWidgetFormInput(),
      'change'            => new sfWidgetFormInput(),
      'payment_method'    => new sfWidgetFormInput(),
      'payment_details'   => new sfWidgetFormInput(),
      'created_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'Payment', 'column' => 'id', 'required' => false)),
      'customer_order_id' => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'workorder_id'      => new sfValidatorPropelChoice(array('model' => 'Workorder', 'column' => 'id', 'required' => false)),
      'amount'            => new sfValidatorNumber(),
      'tendered'          => new sfValidatorNumber(),
      'change'            => new sfValidatorNumber(),
      'payment_method'    => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'payment_details'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }


}
