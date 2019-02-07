<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartVariant filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartVariantFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'is_default_variant'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'manufacturer_sku'        => new sfWidgetFormFilterInput(),
      'internal_sku'            => new sfWidgetFormFilterInput(),
      'use_default_units'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'units'                   => new sfWidgetFormFilterInput(),
      'use_default_costing'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'cost_calculation_method' => new sfWidgetFormFilterInput(),
      'unit_cost'               => new sfWidgetFormFilterInput(),
      'use_default_pricing'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'unit_price'              => new sfWidgetFormFilterInput(),
      'markup_amount'           => new sfWidgetFormFilterInput(),
      'markup_percent'          => new sfWidgetFormFilterInput(),
      'taxable_hst'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'taxable_gst'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'taxable_pst'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'enviro_levy'             => new sfWidgetFormFilterInput(),
      'battery_levy'            => new sfWidgetFormFilterInput(),
      'use_default_dimensions'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'shipping_weight'         => new sfWidgetFormFilterInput(),
      'shipping_width'          => new sfWidgetFormFilterInput(),
      'shipping_height'         => new sfWidgetFormFilterInput(),
      'shipping_depth'          => new sfWidgetFormFilterInput(),
      'shipping_volume'         => new sfWidgetFormFilterInput(),
      'use_default_inventory'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'track_inventory'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'minimum_on_hand'         => new sfWidgetFormFilterInput(),
      'maximum_on_hand'         => new sfWidgetFormFilterInput(),
      'current_on_hand'         => new sfWidgetFormFilterInput(),
      'current_on_hold'         => new sfWidgetFormFilterInput(),
      'current_on_order'        => new sfWidgetFormFilterInput(),
      'location'                => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'part_id'                 => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Part', 'column' => 'id')),
      'is_default_variant'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'manufacturer_sku'        => new sfValidatorPass(array('required' => false)),
      'internal_sku'            => new sfValidatorPass(array('required' => false)),
      'use_default_units'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'units'                   => new sfValidatorPass(array('required' => false)),
      'use_default_costing'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'cost_calculation_method' => new sfValidatorPass(array('required' => false)),
      'unit_cost'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'use_default_pricing'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'unit_price'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'markup_amount'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'markup_percent'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'taxable_hst'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'taxable_gst'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'taxable_pst'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'enviro_levy'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'battery_levy'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'use_default_dimensions'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'shipping_weight'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping_width'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping_height'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping_depth'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'shipping_volume'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'use_default_inventory'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'track_inventory'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'minimum_on_hand'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'maximum_on_hand'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'current_on_hand'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'current_on_hold'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'current_on_order'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'location'                => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_variant_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartVariant';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'part_id'                 => 'ForeignKey',
      'is_default_variant'      => 'Boolean',
      'manufacturer_sku'        => 'Text',
      'internal_sku'            => 'Text',
      'use_default_units'       => 'Boolean',
      'units'                   => 'Text',
      'use_default_costing'     => 'Boolean',
      'cost_calculation_method' => 'Text',
      'unit_cost'               => 'Number',
      'use_default_pricing'     => 'Boolean',
      'unit_price'              => 'Number',
      'markup_amount'           => 'Number',
      'markup_percent'          => 'Number',
      'taxable_hst'             => 'Boolean',
      'taxable_gst'             => 'Boolean',
      'taxable_pst'             => 'Boolean',
      'enviro_levy'             => 'Number',
      'battery_levy'            => 'Number',
      'use_default_dimensions'  => 'Boolean',
      'shipping_weight'         => 'Number',
      'shipping_width'          => 'Number',
      'shipping_height'         => 'Number',
      'shipping_depth'          => 'Number',
      'shipping_volume'         => 'Number',
      'use_default_inventory'   => 'Boolean',
      'track_inventory'         => 'Boolean',
      'minimum_on_hand'         => 'Number',
      'maximum_on_hand'         => 'Number',
      'current_on_hand'         => 'Number',
      'current_on_hold'         => 'Number',
      'current_on_order'        => 'Number',
      'location'                => 'Text',
    );
  }
}
