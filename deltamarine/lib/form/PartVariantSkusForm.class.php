<?php

/**
 * PartVariant form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartVariantSkusForm extends PartVariantForm
{
  public function configure()
  {
    parent::configure();

    unset($this['units'],
          $this['shipping_weight'],
          $this['shipping_height'],
          $this['shipping_width'],
          $this['shipping_depth'],
          $this['shipping_volume'],
          $this['use_default_units'],
          $this['use_default_dimensions'],
          $this['use_default_inventory'],
          $this['use_default_pricing'],
          $this['cost_calculation_method'],
          $this['unit_cost'],
          $this['unit_price'],
          $this['shipping_volume'],
          $this['markup_amount'],
          $this['markup_percent'],
          $this['track_inventory'],
          $this['minimum_on_hand'],
          $this['maximum_on_hand']);


    if (!$this->isNew()) 
    {
      $sup_array = array();
      $my_suppliers = $this->getObject()->getPartSuppliers();
      foreach ($my_suppliers AS $my_supplier)
      {
        $sup_array[$my_supplier->getSupplierId()] = $my_supplier;
      }
      foreach ($this->getObject()->getPart()->getSuppliers() AS $supplier)
      {
        $object = (isset($sup_array[$supplier->getSupplierId()]) ? $sup_array[$supplier->getSupplierId()] : new PartSupplier());
        $object->setPartVariantId($this->getObject()->getId());
        $object->setSupplierId($supplier->getSupplierId());
        $supplier_form = new PartSupplierSkuForm($object);
        $this->embedForm('supplier_'.$supplier->getSupplierId(), $supplier_form);

      }
    }
  }

  public function bind (array $taintedValues = null, array $taintedFiles = null)
  {
    //remove the embedded supplier form and related object if blank, but only if it's not the defaul variant
    if (!$this->getObject()->getIsDefaultVariant())
    {
      $sup_array = array();
      $my_suppliers = $this->getObject()->getPartSuppliers();
      foreach ($my_suppliers AS $my_supplier)
      {
        $sup_array[$my_supplier->getSupplierId()] = $my_supplier;
      }

      $suppliers = $this->getObject()->getPart()->getSuppliers();
      foreach ($suppliers AS $supplier)
      {
        if (is_null($taintedValues['supplier_'.$supplier->getSupplierId()]) 
            || strlen($taintedValues['supplier_'.$supplier->getSupplierId()]['supplier_sku']) === 0)
        {
          //delete the existing part_supplier record if it's been 'unset'
          if (isset($sup_array[$supplier->getSupplierId()]))
          {
            $sup_array[$supplier->getSupplierId()]->delete();
          }
          unset($this->embeddedForms['supplier_'.$supplier->getSupplierId()], $taintedValues['supplier_'.$supplier->getSupplierId()]);
        }
      }
    }

    parent::bind($taintedValues, $taintedFiles);

  }

}
