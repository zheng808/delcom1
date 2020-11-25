<?php

/**
 * PartVariant form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartVariantForm extends BasePartVariantForm
{
  public function configure()
  {
    unset($this['part_id'],
          $this['is_default_variant'],
          $this['current_on_hand'],
          $this['current_on_hold'],
          $this['current_on_order'],
          $this['shipping_weight'],
          $this['shipping_width'],
          $this['shipping_height'],
          $this['shipping_depth'],
          $this['shipping_volume']);


    $this->widgetSchema->setFormFormatterName('cells');

    $this->widgetSchema['cost_calculation_method'] = new sfWidgetFormChoice(array('choices' => PartVariantPeer::getCostCalculationOptions()));
    $this->validationSchema['cost_calculation_method'] = new sfValidatorChoice(array('choices' => PartVariantPeer::getCostCalculationOptions()));

    $this->widgetSchema['units'] = new sfWidgetFormChoice(array('choices' => PartVariantPeer::getUnitOptions()));

    $this->widgetSchema['unit_cost']->setAttribute('size', 6);
    $this->widgetSchema['markup_percent']->setAttribute('size', 6);
    $this->widgetSchema['markup_amount']->setAttribute('size', 6);
    $this->widgetSchema['unit_price']->setAttribute('size', 6);
    $this->widgetSchema['manufacturer_sku']->setAttribute('size', 10);
    $this->widgetSchema['internal_sku']->setAttribute('size', 10);
    $this->widgetSchema['minimum_on_hand']->setAttribute('size', 6);
    $this->widgetSchema['maximum_on_hand']->setAttribute('size', 6);

    $this->widgetSchema->setLabels(array('cost_calculation_method' => 'Calculated Cost Method',
                                         'unit_cost'               => 'Specify Cost ($)',
                                         'markup_percent'          => 'Markup Percent (%)',
                                         'markup_amount'           => 'Markup Amount ($)',
                                         'unit_price'              => 'Specify Price ($)',
                                         'use_default_units'       => 'Use Defaults',
                                         'use_default_dimensions'  => 'Use Defaults',
                                         'use_default_inventory'   => 'Use Defaults',
                                         'track_inventory'         => 'Track Inventory'
                                       ));
    $this->widgetSchema->setHelps( array('cost_calculation_method' => 'Part cost will be calculated by getting the landed cost '.
                                                                      'recorded in each received/inputted Lot for this part. This option '.
                                                                      'allows you to determine which Lot(s) to use to calculate the cost '.
                                                                      'for the purposes of pricing and profit calculation.',
                                         'unit_cost'               => 'This will override the Calculated Cost if Specified',
                                         'markup_percent'          => 'Sets the retail price by marking up cost by this specified percent amount',
                                         'markup_amount'           => 'Sets the retail price by marking up cost by this specified dollar amount',
                                         'unit_price'              => 'You can specify the retail cost here. This will override the '.
                                                                      'amounts for Markup Amount and Markup Percent.',
                                         'units'                   => 'Set to "Items" if sold per item, otherwise set the unit for bulk '.
                                                                      'purchase parts',
                                         'minimum_on_hand'         => 'You will be notified that this part is low on inventory if below '.
                                                                      'this number of units is in stock. Set to 0 to disable notifications.',
                                         'maximum_on_hand'         => 'When replenishing inventory with a supplier order, this number will '.
                                                                      'be shown as the maximum desired number of units',
                                         'track_inventory'         => 'If you uncheck this box, the system will not track the inventory '.
                                                                      'levels of this part. This is useful for creating Bundle products '.
                                                                      'which are not stocked, but instead made up of other parts '.
                                                                      '(which ARE tracked in inventory)'
                                                                    ));
    //fake a blank entry
    if ($this->getObject()->getMinimumOnHand() == 0)
    {
      $this->getObject()->setMinimumOnHand(null);
    }

    if ($this->getObject()->isNew())
    {
      $this->setDefaults(array('use_default_inventory' => true,
                               'use_default_units' => true,
                               'use_default_costing' => true,
                               'use_default_pricing' => true,
                               'use_default_dimensions' => true));
    }
  }

  public function bind(array $taintedValues = array(), array $taintedFiles = array())
  {
    //un-fake a blank entry
    if(!isset($taintedValues['minimum_on_hand']))
        $taintedValues['minimum_on_hand'] = '';
    if ($taintedValues['minimum_on_hand'] == '')
    {
      $taintedValues['minimum_on_hand'] = '0';
    }

    parent::bind($taintedValues, $taintedFiles);
  }
}
