<?php

class progressDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($workorder = WorkorderPeer::retrieveByPk($request->getParameter('id')));

    $itemsarray = array();
    $c = new Criteria();
    $c->addJoin(WorkorderInvoicePeer::INVOICE_ID, InvoicePeer::ID);
    $c->addAscendingOrderByColumn(InvoicePeer::ISSUED_DATE);
    $invoices = $workorder->getWorkorderInvoices($c);
    $counter = 1;
    foreach ($invoices AS $invoice)
    {
      $desc = $invoice->getInvoice()->getIssuedDate('M j, Y');
      $itemsarray[] = array('id' => $invoice->getId(), 'desc' => $desc, 'name' => 'Progress Billing #'.$counter);
      $counter ++;
    }
    $itemsarray[] = array('id' => 'final', 'desc' => 'Current', 'name' => 'Final Invoice');

    $dataarray = array('progress' => $itemsarray);
    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
