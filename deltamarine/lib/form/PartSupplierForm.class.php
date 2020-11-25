<?php

/**
 * PartSupplier form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartSupplierForm extends BasePartSupplierForm
{
  public function configure()
  {
    unset($this['part_variant_id']);

    $this->widgetSchema['supplier_name'] = new sfWidgetFormInput(array(), array('size' => 20));
    $this->widgetSchema['supplier_name']->setLabel('Supplier');
    $this->validatorSchema['supplier_name'] = new sfValidatorString(array('required' => true));
    if (!$this->isNew())
    {
      $supplier = $this->getObject()->getSupplier();
      $this->widgetSchema->setDefault('supplier_name', $supplier ? $supplier->generateName() : null);
    }

    $this->widgetSchema['supplier_sku']->setAttribute('size', '10');
    $this->widgetSchema['notes']->setAttribute('rows', '2');
    $this->widgetSchema['notes']->setAttribute('cols', '57');
    $this->widgetSchema->setHelp('notes', 'You can write notes here about this part related to this supplier, such as pricing breaks, '.
                                          'special order instructions, etc. These notes will be displayed when creating supplier orders.');

  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    //add supplier (or remove input if not filled in)
    if (isset($taintedValues['supplier_name']) && strlen($taintedValues['supplier_name']) > 0)
    {
      $supp = SupplierPeer::retrieveOrCreateByName($taintedValues['supplier_name']);
      $taintedValues['supplier_id'] = $supp->getId();
    }
    else
    {
      $taintedValues['supplier_id'] = null;
    }

    parent::bind($taintedValues, $taintedFiles);
  }

}
