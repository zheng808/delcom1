<?php

/**
 * WorkorderItem form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class WorkorderItemForm extends BaseWorkorderItemForm
{
  public function configure()
  {
    unset($this['workorder_id'],
          $this['lft'],
          $this['rgt'],
          $this['part_instance_id'],
          $this['completed'],
          $this['customer_notes'],
          $this['invoice_id'],
          $this['employee_id']);

    $this->widgetSchema['labour_type_id']->setLabel('Labour Type');
    // TODO add restrictions if need
    $owners = new Criteria();
    $owners = wfCRMPeer::doSelect($owners);
    $owners = array_merge(array('' => ''), $owners);
    $this->setWidget('owner_company', new sfWidgetFormSelect(array('choices' => $owners)));
    //$this->setValidator('owner_company', new sfValidatorString(array( 'required' => true ))	);
    $this->setValidator('label', new sfValidatorString(array( 'min_length' => 1 ))	);
  }
}
