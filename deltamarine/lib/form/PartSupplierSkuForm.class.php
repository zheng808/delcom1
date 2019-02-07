<?php

/**
 * PartSupplier form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartSupplierSkuForm extends PartSupplierForm
{
  public function configure()
  {
    parent::configure();

    unset($this['part_variant_id'],
          $this['notes'],
          $this['supplier_id']);

  }
}
