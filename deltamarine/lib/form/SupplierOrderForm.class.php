<?php

/**
 * SupplierOrder form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class SupplierOrderForm extends BaseSupplierOrderForm
{
  public function configure()
  {
  	unset($this['invoice_id'],
          $this['received_all'],
          $this['received_some'],
          $this['sent'],
          $this['approved'],
          $this['finalized'],
          $this['date_ordered'],
          $this['date_received'],
          $this['purchase_order'],
          $this['id']);
          
   	$this->widgetSchema['supplier_id']->setLabel('Supplier');
   	$this->setValidator('supplier_id', new sfValidatorString(array( 'required' => true ))	);
   	$this->setWidget('date_expected', new sfWidgetFormDate());
  }
}
