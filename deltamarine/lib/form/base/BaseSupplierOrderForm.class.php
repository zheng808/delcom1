<?php

/**
 * SupplierOrder form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseSupplierOrderForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'supplier_id'    => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'purchase_order' => new sfWidgetFormInput(),
      'date_ordered'   => new sfWidgetFormDateTime(),
      'date_expected'  => new sfWidgetFormDateTime(),
      'date_received'  => new sfWidgetFormDateTime(),
      'finalized'      => new sfWidgetFormInputCheckbox(),
      'approved'       => new sfWidgetFormInputCheckbox(),
      'sent'           => new sfWidgetFormInputCheckbox(),
      'received_some'  => new sfWidgetFormInputCheckbox(),
      'received_all'   => new sfWidgetFormInputCheckbox(),
      'invoice_id'     => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'SupplierOrder', 'column' => 'id', 'required' => false)),
      'supplier_id'    => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'purchase_order' => new sfValidatorString(array('max_length' => 127, 'required' => false)),
      'date_ordered'   => new sfValidatorDateTime(array('required' => false)),
      'date_expected'  => new sfValidatorDateTime(array('required' => false)),
      'date_received'  => new sfValidatorDateTime(array('required' => false)),
      'finalized'      => new sfValidatorBoolean(),
      'approved'       => new sfValidatorBoolean(),
      'sent'           => new sfValidatorBoolean(),
      'received_some'  => new sfValidatorBoolean(),
      'received_all'   => new sfValidatorBoolean(),
      'invoice_id'     => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('supplier_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SupplierOrder';
  }


}
