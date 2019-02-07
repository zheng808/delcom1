<?php

/**
 * PartVariant form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartVariantForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'part_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => false)),
      'is_default_variant'      => new sfWidgetFormInputCheckbox(),
      'manufacturer_sku'        => new sfWidgetFormInput(),
      'internal_sku'            => new sfWidgetFormInput(),
      'use_default_units'       => new sfWidgetFormInputCheckbox(),
      'units'                   => new sfWidgetFormInput(),
      'use_default_costing'     => new sfWidgetFormInputCheckbox(),
      'cost_calculation_method' => new sfWidgetFormInput(),
      'unit_cost'               => new sfWidgetFormInput(),
      'use_default_pricing'     => new sfWidgetFormInputCheckbox(),
      'unit_price'              => new sfWidgetFormInput(),
      'markup_amount'           => new sfWidgetFormInput(),
      'markup_percent'          => new sfWidgetFormInput(),
      'taxable_hst'             => new sfWidgetFormInputCheckbox(),
      'taxable_gst'             => new sfWidgetFormInputCheckbox(),
      'taxable_pst'             => new sfWidgetFormInputCheckbox(),
      'enviro_levy'             => new sfWidgetFormInput(),
      'battery_levy'            => new sfWidgetFormInput(),
      'use_default_dimensions'  => new sfWidgetFormInputCheckbox(),
      'shipping_weight'         => new sfWidgetFormInput(),
      'shipping_width'          => new sfWidgetFormInput(),
      'shipping_height'         => new sfWidgetFormInput(),
      'shipping_depth'          => new sfWidgetFormInput(),
      'shipping_volume'         => new sfWidgetFormInput(),
      'use_default_inventory'   => new sfWidgetFormInputCheckbox(),
      'track_inventory'         => new sfWidgetFormInputCheckbox(),
      'minimum_on_hand'         => new sfWidgetFormInput(),
      'maximum_on_hand'         => new sfWidgetFormInput(),
      'current_on_hand'         => new sfWidgetFormInput(),
      'current_on_hold'         => new sfWidgetFormInput(),
      'current_on_order'        => new sfWidgetFormInput(),
      'location'                => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'part_id'                 => new sfValidatorPropelChoice(array('model' => 'Part', 'column' => 'id')),
      'is_default_variant'      => new sfValidatorBoolean(),
      'manufacturer_sku'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'internal_sku'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'use_default_units'       => new sfValidatorBoolean(),
      'units'                   => new sfValidatorString(array('max_length' => 6, 'required' => false)),
      'use_default_costing'     => new sfValidatorBoolean(),
      'cost_calculation_method' => new sfValidatorString(array('max_length' => 7)),
      'unit_cost'               => new sfValidatorNumber(array('required' => false)),
      'use_default_pricing'     => new sfValidatorBoolean(),
      'unit_price'              => new sfValidatorNumber(array('required' => false)),
      'markup_amount'           => new sfValidatorNumber(array('required' => false)),
      'markup_percent'          => new sfValidatorInteger(array('required' => false)),
      'taxable_hst'             => new sfValidatorBoolean(),
      'taxable_gst'             => new sfValidatorBoolean(),
      'taxable_pst'             => new sfValidatorBoolean(),
      'enviro_levy'             => new sfValidatorNumber(array('required' => false)),
      'battery_levy'            => new sfValidatorNumber(array('required' => false)),
      'use_default_dimensions'  => new sfValidatorBoolean(),
      'shipping_weight'         => new sfValidatorNumber(array('required' => false)),
      'shipping_width'          => new sfValidatorNumber(array('required' => false)),
      'shipping_height'         => new sfValidatorNumber(array('required' => false)),
      'shipping_depth'          => new sfValidatorNumber(array('required' => false)),
      'shipping_volume'         => new sfValidatorNumber(array('required' => false)),
      'use_default_inventory'   => new sfValidatorBoolean(),
      'track_inventory'         => new sfValidatorBoolean(),
      'minimum_on_hand'         => new sfValidatorNumber(),
      'maximum_on_hand'         => new sfValidatorNumber(array('required' => false)),
      'current_on_hand'         => new sfValidatorNumber(),
      'current_on_hold'         => new sfValidatorNumber(),
      'current_on_order'        => new sfValidatorNumber(),
      'location'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_variant[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartVariant';
  }


}
