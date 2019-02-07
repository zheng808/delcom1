<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();

    //filter by customer id
    if ($request->getParameter('customer_id'))
    {
      $c->add(CustomerOrderPeer::CUSTOMER_ID, $request->getParameter('customer_id'));
    }

    //filter by customer name
    if ($request->getParameter('query'))
    {
      $c->addJoin(CustomerOrderPeer::CUSTOMER_ID, CustomerPeer::ID);
      $c->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c->add(wfCRMPeer::ALPHA_NAME, '%'.$request->getParameter('query').'%', Criteria::LIKE);
    }

    if ($request->getParameter('id'))
    {
      $c->add(CustomerOrderPeer::ID, $request->getParameter('id').'%', Criteria::LIKE);
    }

    //filter by rigging
    if ($request->getParameter('for_rigging'))
    {
      if ($request->getParameter('for_rigging') == '1')
      {
        $c->add(CustomerOrderPeer::FOR_RIGGING, true);
      }
      else if ($request->getParameter('for_rigging') == '2')
      {
        $c->add(CustomerOrderPeer::FOR_RIGGING, false);
      }
    }

    //copy criteria for counting
    $c2 = clone $c;

    //sort
    $sort = $request->getParameter('sort', 'date');
    $dir = $request->getParameter('dir', 'ASC');
    if ($sort == 'status')
    {
      if ($dir == 'ASC')
      {
        $c->addAscendingOrderByColumn(CustomerOrderPeer::FINALIZED);
        $c->addAscendingOrderByColumn(CustomerOrderPeer::APPROVED );
        $c->addAscendingOrderByColumn(CustomerOrderPeer::SENT_SOME);
        $c->addAscendingOrderByColumn(CustomerOrderPeer::SENT_ALL );
      }
      else
      {
        $c->addDecendingOrderByColumn(CustomerOrderPeer::SENT_ALL );
        $c->addDecendingOrderByColumn(CustomerOrderPeer::SENT_SOME);
        $c->addDecendingOrderByColumn(CustomerOrderPeer::APPROVED );
        $c->addDecendingOrderByColumn(CustomerOrderPeer::FINALIZED);
      }
    }
    else
    {
      $col = ($sort == 'date' ? CustomerOrderPeer::DATE_ORDERED : CustomerOrderPeer::ID);
      ($dir == 'ASC' ?  $c->addAscendingOrderByColumn($col) :  $c->addDescendingOrderByColumn($col));
    }

    //paging (only applies to $c, not $c2)
    if ($request->getParameter('limit'))
    {
      $c->setLimit($request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $c->setOffset($request->getParameter('start'));
    }

    $orders = CustomerOrderPeer::doSelectforListing($c);
    $count_all = CustomerOrderPeer::doCount($c2);

    //generate JSON output
    $orderarray = array();
    foreach ($orders AS $order)
    {
      $status = ($order->getFinalized() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Finalized</span> &gt; '.
                ($order->getApproved() ? '<span style="color:green">' : '<span style="color:#aaa;">').
                'Approved</span> &gt; '.
                ($order->getSentAll() ? '<span style="color:green">Sent</span>' 
                                      : ($order->getSentSome() ? '<span style="color:orange">Sent Partial</span>'
                                                               : '<span style="color:#aaa;">Sent</span>'));
      $dateval = ($order->getDateOrdered('U') ? date('m/d/Y', $order->getDateOrdered('U')) : '');

      $orderarray[] = array(
        'id' => $order->getId(),
        'customer' => $order->getCustomer()->generateName(false, false, false), 
        'date' => $dateval,
        'status' => $status,
        'for_rigging' => $order->getForRigging()
       );
    }
    $dataarray = array('totalCount' => $count_all, 'sales' => $orderarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
