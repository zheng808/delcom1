<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();

    //filtering
    if ($request->getParameter('supplier_id'))
    {
      $c->add(SupplierOrderPeer::SUPPLIER_ID, $request->getParameter('supplier_id'));
    }
    //filter by supplier name
    if ($request->getParameter('supplier_name'))
    {
      $c->addJoin(SupplierOrderPeer::SUPPLIER_ID, SupplierPeer::ID);
      $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('supplier_name').'%', Criteria::LIKE);
    }
    //filter by purchase order
    if ($request->getParameter('purchase_order'))
    {
      $c->add(SupplierOrderPeer::PURCHASE_ORDER, $request->getParameter('purchase_order').'%', Criteria::LIKE);
    } 
    if ($request->getParameter('id'))
    {
      $c->add(SupplierOrderPeer::ID, $request->getParameter('id').'%', Criteria::LIKE);
    }     


    //copy criteria for use with counting all
    $c2 = clone $c;

    //sort
    $dir = $request->getParameter('dir', 'ASC');
    $sort = $request->getParameter('sort', 'status');
    if ($sort == 'status' || $sort == 'received' || $sort == 'date')
    {
      if ($dir == 'ASC')
      {
        if ($sort == 'date')
        {
          $c->addAscendingOrderByColumn(SupplierOrderPeer::DATE_ORDERED);
        }
        $c->addAscendingOrderByColumn(SupplierOrderPeer::FINALIZED);
        $c->addAscendingOrderByColumn(SupplierOrderPeer::APPROVED);
        $c->addAscendingOrderByColumn(SupplierOrderPeer::SENT);
        $c->addAscendingOrderByColumn(SupplierOrderPeer::RECEIVED_SOME);
        $c->addAscendingOrderByColumn(SupplierOrderPeer::RECEIVED_ALL);
      }
      else
      {
        if ($sort == 'date')
        {
          $c->addDescendingOrderByColumn(SupplierOrderPeer::DATE_ORDERED);
        }
        $c->addDescendingOrderByColumn(SupplierOrderPeer::RECEIVED_ALL);
        $c->addDescendingOrderByColumn(SupplierOrderPeer::RECEIVED_SOME);
        $c->addDescendingOrderByColumn(SupplierOrderPeer::SENT);
        $c->addDescendingOrderByColumn(SupplierOrderPeer::APPROVED);
        $c->addDescendingOrderByColumn(SupplierOrderPeer::FINALIZED);
      }
    }
    else if ($sort == 'id')
    {
       ($dir == 'ASC' ? $c->addAscendingOrderByColumn(SupplierOrderPeer::ID)
                     : $c->addDescendingOrderByColumn(SupplierOrderPeer::ID));  
    }

    //paging
    if ($request->getParameter('limit'))
    {
      $c->setLimit($request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $c->setOffset($request->getParameter('start'));
    }

    $orders = SupplierOrderPeer::doSelectForListing($c);
    $count_all = SupplierOrderPeer::doCount($c2);

    //generate data array
    $ordersarray = array();
    foreach ($orders AS $order)
    {
      $status = ($order->getFinalized() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Finalized</span> &gt; '.
                ($order->getApproved() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Approved</span> &gt; '.
                ($order->getSent() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Sent</span>';
      $received = ($order->getReceivedAll() ? '<span style="color:green">Received All</span>'
                    : ($order->getReceivedSome() ? '<span style="color:orange">Received Some</span>'
                    : '<span color:#aaa;">No</span>'));
      $dateval = ($order->getDateOrdered('U') ? date('m/d/Y', $order->getDateOrdered('U')) : '');

      $ordersarray[] = array('id'       => $order->getId(),
                             'supplier' => $order->getSupplier()->getName(),
                             'date'     => $dateval,
                             'status'   => $status,
                             'purchase_order'=> $order->getPurchaseOrder(),
                             'received' => $received);
    }

    $dataarray = array('totalCount' => $count_all, 'orders' => $ordersarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }


}
