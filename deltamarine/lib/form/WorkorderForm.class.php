<?php

/**
 * Workorder form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class WorkorderForm extends BaseWorkorderForm
{
  public function configure()
  {
    unset($this['customer_equipment_id'],
          $this['completed_on'],
          $this['customer_notes'],
          $this['created_on']);

    $this->widgetSchema['customer_id']->setLabel('Customer');
    $this->setWidget('status', new sfWidgetFormInputHidden());
    $this->setDefault('status', 'estimate');
  }
}
