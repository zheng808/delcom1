<?php

/**
 * CustomerOrder form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseCustomerOrderForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'customer_id'          => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'finalized'            => new sfWidgetFormInputCheckbox(),
      'approved'             => new sfWidgetFormInputCheckbox(),
      'sent_some'            => new sfWidgetFormInputCheckbox(),
      'sent_all'             => new sfWidgetFormInputCheckbox(),
      'invoice_per_shipment' => new sfWidgetFormInputCheckbox(),
      'invoice_id'           => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'date_ordered'         => new sfWidgetFormDateTime(),
      'hst_exempt'           => new sfWidgetFormInputCheckbox(),
      'gst_exempt'           => new sfWidgetFormInputCheckbox(),
      'pst_exempt'           => new sfWidgetFormInputCheckbox(),
      'for_rigging'          => new sfWidgetFormInputCheckbox(),
      'discount_pct'         => new sfWidgetFormInput(),
      'po_num'               => new sfWidgetFormInput(),
      'boat_name'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'CustomerOrder', 'column' => 'id', 'required' => false)),
      'customer_id'          => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => false)),
      'finalized'            => new sfValidatorBoolean(),
      'approved'             => new sfValidatorBoolean(),
      'sent_some'            => new sfValidatorBoolean(),
      'sent_all'             => new sfValidatorBoolean(),
      'invoice_per_shipment' => new sfValidatorBoolean(),
      'invoice_id'           => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'date_ordered'         => new sfValidatorDateTime(array('required' => false)),
      'hst_exempt'           => new sfValidatorBoolean(),
      'gst_exempt'           => new sfValidatorBoolean(),
      'pst_exempt'           => new sfValidatorBoolean(),
      'for_rigging'          => new sfValidatorBoolean(),
      'discount_pct'         => new sfValidatorInteger(),
      'po_num'               => new sfValidatorString(array('max_length' => 127, 'required' => false)),
      'boat_name'            => new sfValidatorString(array('max_length' => 127, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerOrder';
  }


}
