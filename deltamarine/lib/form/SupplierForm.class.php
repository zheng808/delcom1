<?php

/**
 * Supplier form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class SupplierForm extends BaseSupplierForm
{
  public function configure()
  {
    unset($this['hidden'],
          $this['wf_crm_id'],
          $this['credit_limit'],
          $this['net_days']);

    $this->widgetSchema['account_number']->setLabel('Supplier Acct No.');
  }
}
