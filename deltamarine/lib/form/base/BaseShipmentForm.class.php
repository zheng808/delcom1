<?php

/**
 * Shipment form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseShipmentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'carrier'         => new sfWidgetFormInput(),
      'tracking_number' => new sfWidgetFormInput(),
      'date_shipped'    => new sfWidgetFormDateTime(),
      'invoice_id'      => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'Shipment', 'column' => 'id', 'required' => false)),
      'carrier'         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'tracking_number' => new sfValidatorString(array('max_length' => 127, 'required' => false)),
      'date_shipped'    => new sfValidatorDateTime(),
      'invoice_id'      => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('shipment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Shipment';
  }


}
