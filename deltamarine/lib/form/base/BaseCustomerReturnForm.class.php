<?php

/**
 * CustomerReturn form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerReturnForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'customer_order_id' => new sfWidgetFormPropelChoice(array('model' => 'CustomerOrder', 'add_empty' => true)),
      'invoice_id'        => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'date_returned'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'CustomerReturn', 'column' => 'id', 'required' => false)),
      'customer_order_id' => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'invoice_id'        => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'date_returned'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_return[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerReturn';
  }


}
