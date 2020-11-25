<?php

/**
 * PartLot form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartLotForm extends BasePartLotForm
{
  public function configure()
  {
    parent::configure();

    unset($this['part_variant_id'],
          $this['supplier_order_item_id'],
          $this['quantity_remaining'],
          $this['received_date']);

    $this->widgetSchema->setLabels(array('quantity_received' => 'Existing Quantity',
                                         'landed_cost'  => 'Unit Cost for Existing ($)'));
    $this->widgetSchema['quantity_received']->setAttribute('size', 6);
    $this->validatorSchema['landed_cost']->setOption('required', false);
    $this->widgetSchema['landed_cost']->setAttribute('size', 6);

    $this->widgetSchema->setDefaults(array('quantity_received' => '0'));
  }
  
  public function save_lot($q,$q1)
  {
        $this->bind(array('quantity_received' =>$q1));//'quantity_remaining' => $q, 
        $this->save();
  }
}
