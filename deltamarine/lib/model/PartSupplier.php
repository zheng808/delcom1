<?php

class PartSupplier extends BasePartSupplier
{
	public function delete(PropelPDO $con = null)
  {
    if ($barcodes = $this->getBarcodes())
    {
      foreach ($barcodes AS $bc)
      {
        $bc->delete();
      }
    }

    $variant = $this->getPartVariant();
    if ($variant->getIsDefaultVariant())
    {
      $c = new Criteria();
      $c->add(PartVariantPeer::PART_ID, $variant->getPartId());
      $c->add(PartVariantPeer::IS_DEFAULT_VARIANT, false);
      $c->addJoin(PartVariantPeer::ID, PartSupplierPeer::PART_VARIANT_ID);
      $c->add(PartSupplierPeer::SUPPLIER_ID, $this->getSupplierId());
      $sups = PartSupplierPeer::doSelect($c);
      foreach ($sups AS $sup)
      {
        $sup->delete();
      }
    }

    parent::delete($con);
  }
}
