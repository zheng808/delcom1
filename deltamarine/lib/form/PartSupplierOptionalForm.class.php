<?php

/**
 * PartSupplier form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartSupplierOptionalForm extends PartSupplierForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema->setFormFormatterName('cells');
    $this->validatorSchema['supplier_name'] = new sfValidatorString(array('required' => false));
  }

}
