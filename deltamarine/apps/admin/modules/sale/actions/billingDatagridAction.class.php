<?php

class billingDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($sale = CustomerOrderPeer::retrieveByPk($request->getParameter('id')));

    //get invoices
    $c = new Criteria();
    $c_a = $c->getNewCriterion(CustomerOrderPeer::ID, $sale->getId());
    $c_b = $c->getNewCriterion(CustomerReturnPeer::CUSTOMER_ORDER_ID, $sale->getId());
    $c_a->addOr($c_b);
    $c->addAnd($c_a);

    $invoices = InvoicePeer::doSelectForListing($c);
    $invoice_ids = array_keys($invoices);

    //get payments
    $payments = $sale->getPayments();

    //combine into a time-sorted array
    $output = array();
    foreach ($invoices AS $invoice)
    {
      $output[$invoice->getInvoice()->getIssuedDate('U').rand(1000,9999)] = $invoice;
    }
    foreach ($payments AS $payment)
    {
      $output[$payment->getCreatedAt('U').rand(1000,9999)] = $payment;
    }
    ksort($output);

    //generate JSON output
    $itemsarray = array();
    $total_invoices = 0;
    $total_payments = 0;
    foreach ($output AS $item)
    {
      if ($item instanceof CustomerOrder)
      {
        $description = 'Sale #'.$sale->getId().' Invoice';
        $details = '';
        $invoice_id = $item->getInvoice()->getId();
        $return_id = '';
        $payment_id = '';
        $date = $item->getInvoice()->getIssuedDate('M j, Y h:iA');
        $amount = $item->getInvoice()->getTotal();
        $total_invoices += $amount;
      }
      else if ($item instanceof CustomerReturn)
      {
        $description = 'Part Return';
        $details = '';
        $invoice_id = $item->getInvoice()->getId();
        $return_id = $item->getId();
        $payment_id = '';
        $date = $item->getInvoice()->getIssuedDate('M j, Y h:iA');
        $amount = $item->getInvoice()->getTotal();
        $total_invoices += $amount;
      }
      else if ($item instanceof Payment)
      {
        if ($item->getAmount() < 0)
        {
          $description = 'Refund Given';
        }
        else
        {
          $description = 'Payment Received';
        }
        $details = $item->getPaymentMethod().($item->getPaymentDetails() ? ' - '.$item->getPaymentDetails() : '');
        $invoice_id = '';
        $return_id = '';
        $payment_id = $item->getId();
        $date = $item->getCreatedAt('M j, Y h:iA');
        $amount = $item->getAmount();
        $total_payments += $amount;
      }
      $itemsarray[] = array(
                            'invoice_id'  => $invoice_id,
                            'payment_id'  => $payment_id,
                            'return_id'   => $return_id,
                            'date'        => $date,
                            'description' => $description,
                            'details'     => $details,
                            'amount'      => number_format($amount, 2),
                            'is_original' => ($item instanceof CustomerOrder)
                          );
    }
    $dataarray = array('invoices' => number_format(round($total_invoices, 2),2),
                       'payments' => number_format(round($total_payments, 2),2),
                       'owing'    => number_format(round($total_invoices - $total_payments,2),2),
                       'items'    => $itemsarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
