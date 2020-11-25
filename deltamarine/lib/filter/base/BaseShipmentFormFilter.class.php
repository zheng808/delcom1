<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Shipment filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseShipmentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'carrier'         => new sfWidgetFormFilterInput(),
      'tracking_number' => new sfWidgetFormFilterInput(),
      'date_shipped'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'invoice_id'      => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'carrier'         => new sfValidatorPass(array('required' => false)),
      'tracking_number' => new sfValidatorPass(array('required' => false)),
      'date_shipped'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'invoice_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('shipment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Shipment';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'carrier'         => 'Text',
      'tracking_number' => 'Text',
      'date_shipped'    => 'Date',
      'invoice_id'      => 'ForeignKey',
    );
  }
}
