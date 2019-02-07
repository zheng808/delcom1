<?php

/**
 * Invoice form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseInvoiceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'receivable'      => new sfWidgetFormInputCheckbox(),
      'customer_id'     => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_id' => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'subtotal'        => new sfWidgetFormInput(),
      'shipping'        => new sfWidgetFormInput(),
      'hst'             => new sfWidgetFormInput(),
      'gst'             => new sfWidgetFormInput(),
      'pst'             => new sfWidgetFormInput(),
      'enviro_levy'     => new sfWidgetFormInput(),
      'battery_levy'    => new sfWidgetFormInput(),
      'duties'          => new sfWidgetFormInput(),
      'total'           => new sfWidgetFormInput(),
      'issued_date'     => new sfWidgetFormDateTime(),
      'payable_date'    => new sfWidgetFormDateTime(),
      'archived'        => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'receivable'      => new sfValidatorBoolean(),
      'customer_id'     => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => false)),
      'supplier_id'     => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'manufacturer_id' => new sfValidatorPropelChoice(array('model' => 'Manufacturer', 'column' => 'id', 'required' => false)),
      'subtotal'        => new sfValidatorNumber(),
      'shipping'        => new sfValidatorNumber(),
      'hst'             => new sfValidatorNumber(),
      'gst'             => new sfValidatorNumber(),
      'pst'             => new sfValidatorNumber(),
      'enviro_levy'     => new sfValidatorNumber(),
      'battery_levy'    => new sfValidatorNumber(),
      'duties'          => new sfValidatorNumber(),
      'total'           => new sfValidatorNumber(),
      'issued_date'     => new sfValidatorDateTime(array('required' => false)),
      'payable_date'    => new sfValidatorDateTime(array('required' => false)),
      'archived'        => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('invoice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invoice';
  }


}
