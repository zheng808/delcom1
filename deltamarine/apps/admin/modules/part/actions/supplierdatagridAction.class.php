<?php

class supplierdatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($variant = PartVariantPeer::retrieveByPk($request->getParameter('id')));

    $partsuppliers = $variant->getPartSuppliersJoinSupplier();

    //generate JSON output
    $supplierarray = array();
    foreach ($partsuppliers AS $ps)
    {
      $supplierarray[] = array('part_supplier_id' => $ps->getId(),
                               'part_variant_id'  => $ps->getPartVariantId(),
                               'supplier_id'      => $ps->getSupplierId(),
                               'supplier_name'    => $ps->getSupplier()->getName(),
                               'supplier_sku'     => $ps->getSupplierSku(),
                               'notes'            => $ps->getNotes()
                              );
    }
    $dataarray = array('totalCount' => count($supplierarray), 'suppliers' => $supplierarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
