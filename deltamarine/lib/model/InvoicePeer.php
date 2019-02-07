<?php

class InvoicePeer extends BaseInvoicePeer
{

  //gets all the information for an entry, regardless of what its related to
  //WARNING: this returns an array of objects of potentially mixed type!!!!!
  public static function doSelectForListing(Criteria $c = null, PropelPDO $con = null)
  {
    if (!$c) $c = new Criteria();

    //is it a customer sale invoice?
    $c->addJoin(self::ID, CustomerOrderPeer::INVOICE_ID, Criteria::LEFT_JOIN);

    //or a customer return?
    $c->addJoin(self::ID, CustomerReturnPeer::INVOICE_ID, Criteria::LEFT_JOIN);

    self::addSelectColumns($c);
    CustomerOrderPeer::addSelectColumns($c);
    CustomerReturnPeer::addSelectColumns($c);

    $invoice_startcol = 0;
    $co_startcol = $invoice_startcol + (self::NUM_COLUMNS - self::NUM_LAZY_LOAD_COLUMNS);
    $cr_startcol = $co_startcol + (CustomerOrderPeer::NUM_COLUMNS - CustomerOrderPeer::NUM_LAZY_LOAD_COLUMNS);

    $stmt = BasePeer::doSelect($c, $con);
    $results = array();
    $variants = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $invoice = null;
      $co = null;
      $cr = null;

      //load invoice
      $invoice_key = InvoicePeer::getPrimaryKeyHashFromRow($row, $invoice_startcol);
      if (null === ($invoice = InvoicePeer::getInstanceFromPool($invoice_key)))
      {
        $invoice = new Invoice();
        $invoice->hydrate($row, $invoice_startcol);
      }
      
      //load orderitem
      if ($co_key = CustomerOrderPeer::getPrimaryKeyHashFromRow($row, $co_startcol))
      {
        if (null === ($co = CustomerOrderPeer::getInstanceFromPool($co_key)))
        {
          $co = new CustomerOrder();
          $co->hydrate($row, $co_startcol);
          CustomerOrderPeer::addInstanceToPool($co, $co_key);
        }
      }

      //load returnitem
      if ($cr_key = CustomerReturnPeer::getPrimaryKeyHashFromRow($row, $cr_startcol))
      {
        if (null === ($cr = CustomerReturnPeer::getInstanceFromPool($cr_key)))
        {
          $cr = new CustomerReturn();
          $cr->hydrate($row, $cr_startcol);
          CustomerReturnPeer::addInstanceToPool($cr, $cr_key);
        }
      }

      //link all the objects depending on what type it is
      if (isset($co) && $co->getId() > 0)
      {
        $co->setInvoice($invoice);
        $results[$invoice->getId()] = $co;
      }
      else if (isset($cr) && $cr->getId() > 0)
      {
        $cr->setInvoice($invoice);
        $results[$invoice->getId()] = $cr;
      }
    }

    return $results;
  }

}
