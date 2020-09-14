<?php

class billingDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($workorder = WorkorderPeer::retrieveByPk($request->getParameter('id')));

    $output = array();
    $itemsarray = array();

    $total_charges = 0;
    $total_custcharges = 0;
    $total_progress_charges = 0;
    $total_payments = 0;

    //add up all the invoices
    $invoices = $workorder->getWorkorderInvoices();
    $inv_counter = 1;
    foreach ($invoices AS $wo_invoice)
    {
      $invoice = $wo_invoice->getInvoice();
      $total = $invoice->getTotal();

      $itemsarray[$invoice->getIssuedDate('U').rand(0,10000)] = array(
                            'invoice_id'  => $wo_invoice->getId(),
                            'date'        => $invoice->getIssuedDate('M j, Y'),
                            'description' => 'Progress Billing #'.$inv_counter,
                            'amount'      => number_format($invoice->getTotal(), 2),
                          );
      $inv_counter ++;
      $total_charges += $total;
      $total_custcharges += ($wo_invoice->getWhomId() == 'cust' ? $total : 0);
      $total_progress_charges += $total;
    }

    //collate totals, broken down by payer
    $totals = $workorder->getTotalsByPayer();
    foreach ($totals AS $key => $payer_total)
    { 
      $total = $payer_total['amount'];

      $fees = $payer_total['fees'];
      $supplies = round($total * ($workorder->getShopSuppliesSurcharge()/100), 2);
      $moorage = round($total * ($workorder->getMoorageSurchargeAmt()/100), 2); 

      $total_amt = $total + $fees + $supplies + $moorage;
      $total_with_tax = $total_amt;
      $total_with_tax += ($total_amt * ($workorder->getHstExempt() ? 0 : (sfConfig::get('app_hst_rate') / 100)));
      $total_with_tax += ($total_amt * ($workorder->getPstExempt() ? 0 : (sfConfig::get('app_pst_rate') / 100)));
      $total_with_tax += ($total_amt * ($workorder->getGstExempt() ? 0 : (sfConfig::get('app_gst_rate') / 100)));
      $total_amt = round($total_with_tax, 2);
      if (isset($total['obj']))
      {
        $description = 'Amount Payable By '.$payer_total['obj']->getName();
        if (get_class($payer_total['obj']) == 'Supplier') $description .= ' (Supplier)';
        else if (get_class($payer_total['obj']) == 'Manufacturer') $description .= ' (Manufacturer)';
      }
      else
      {
        $description = ($key == 'inhouse' ? 'Discounts/Covered In-House' : 'Amount Payable By Customer');
      }

      if ($key == 'cust') 
      {
        $total_amt -= $total_progress_charges;
      }
      $itemsarray[(time()+250000).rand(0,10000)] = array(
                            'payment_id' => null,
                            'date'       => '[Current]',
                            'description' => $description,
                            'amount'     => number_format($total_amt, 2)
                          );

      $total_charges += $total_amt;
      $total_custcharges += ($key == 'cust' ? $total_amt : 0);
    }

    //add up all the payments
    $payments = $workorder->getWorkorderPayments();
    foreach ($payments AS $payment)
    {
      $whom = $payment->getWhomDescription();
      $itemsarray[$payment->getCreatedAt('U').rand(0,10000)] = array(
                            'payment_id'  => $payment->getId(),
                            'date'        => $payment->getCreatedAt('M j, Y'),
                            'description' => ($payment->getAmount() < 0 ? 'Refund Given to ' : 'Payment Received from ').$whom,
                            'amount'      => number_format(-1 * $payment->getAmount(), 2),
                          );
      if ($payment->getWhomIndex() == 'cust')
      {
        $total_payments += $payment->getAmount();
      }
    }

    //sort the items array
    ksort($itemsarray);
    $itemsarray = array_values($itemsarray);

    $dataarray = array('charges' => number_format($total_charges, 2),
                       'custcharges' => number_format($total_custcharges, 2),
                       'payments' => number_format($total_payments, 2),
                       'owing'    => number_format($total_custcharges - $total_payments, 2),
                       'items'    => $itemsarray);

    $this->renderText(json_encode($dataarray));
    error_log(print_r($dataarray, TRUE)); 
    return sfView::NONE;
  }

}
