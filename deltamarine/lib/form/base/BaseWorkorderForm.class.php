<?php

/**
 * Workorder form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'customer_id'             => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'customer_boat_id'        => new sfWidgetFormPropelChoice(array('model' => 'CustomerBoat', 'add_empty' => true)),
      'workorder_category_id'   => new sfWidgetFormPropelChoice(array('model' => 'WorkorderCategory', 'add_empty' => true)),
      'status'                  => new sfWidgetFormInput(),
      'summary_color'           => new sfWidgetFormInput(),
      'summary_notes'           => new sfWidgetFormInput(),
      'haulout_date'            => new sfWidgetFormDateTime(),
      'haulin_date'             => new sfWidgetFormDateTime(),
      'created_on'              => new sfWidgetFormDateTime(),
      'started_on'              => new sfWidgetFormDateTime(),
      'completed_on'            => new sfWidgetFormDateTime(),
      'hst_exempt'              => new sfWidgetFormInputCheckbox(),
      'gst_exempt'              => new sfWidgetFormInputCheckbox(),
      'pst_exempt'              => new sfWidgetFormInputCheckbox(),
      'customer_notes'          => new sfWidgetFormTextarea(),
      'internal_notes'          => new sfWidgetFormTextarea(),
      'for_rigging'             => new sfWidgetFormInputCheckbox(),
      'shop_supplies_surcharge' => new sfWidgetFormInput(),
      'moorage_surcharge'       => new sfWidgetFormInput(),
      'moorage_surcharge_amt'   => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorPropelChoice(array('model' => 'Workorder', 'column' => 'id', 'required' => false)),
      'customer_id'             => new sfValidatorPropelChoice(array('model' => 'Customer', 'column' => 'id', 'required' => false)),
      'customer_boat_id'        => new sfValidatorPropelChoice(array('model' => 'CustomerBoat', 'column' => 'id', 'required' => false)),
      'workorder_category_id'   => new sfValidatorPropelChoice(array('model' => 'WorkorderCategory', 'column' => 'id', 'required' => false)),
      'status'                  => new sfValidatorString(array('max_length' => 15)),
      'summary_color'           => new sfValidatorString(array('max_length' => 6)),
      'summary_notes'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'haulout_date'            => new sfValidatorDateTime(array('required' => false)),
      'haulin_date'             => new sfValidatorDateTime(array('required' => false)),
      'created_on'              => new sfValidatorDateTime(array('required' => false)),
      'started_on'              => new sfValidatorDateTime(array('required' => false)),
      'completed_on'            => new sfValidatorDateTime(array('required' => false)),
      'hst_exempt'              => new sfValidatorBoolean(),
      'gst_exempt'              => new sfValidatorBoolean(),
      'pst_exempt'              => new sfValidatorBoolean(),
      'customer_notes'          => new sfValidatorString(array('required' => false)),
      'internal_notes'          => new sfValidatorString(array('required' => false)),
      'for_rigging'             => new sfValidatorBoolean(),
      'shop_supplies_surcharge' => new sfValidatorNumber(array('required' => false)),
      'moorage_surcharge'       => new sfValidatorNumber(array('required' => false)),
      'moorage_surcharge_amt'   => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Workorder';
  }


}
