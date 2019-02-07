<?php

/**
 * PartVariant form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartVariantPricingForm extends PartVariantForm
{
  public function configure()
  {
    parent::configure();

    unset($this['units'],
          $this['manufacturer_sku'],
          $this['internal_sku'],
          $this['shipping_weight'],
          $this['shipping_height'],
          $this['shipping_width'],
          $this['shipping_depth'],
          $this['shipping_volume'],
          $this['use_default_units'],
          $this['use_default_dimensions'],
          $this['use_default_inventory'],
          $this['track_inventory'],
          $this['minimum_on_hand'],
          $this['maximum_on_hand']);

  }
}
