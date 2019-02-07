<?php

class detailstreeAction extends sfAction
{

  public function execute($request)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'START detailstreeAction.execute()=============';
      sfContext::getInstance()->getLogger()->info($message);
    }

    //$this->forward404Unless($request->isXmlHttpRequest());
    $node = $request->getParameter('node');

    if (strpos($node, 'part-') === 0)
    {
      //OUTPUT PARTS INFO FOR AN ITEM
      $node = substr($node, 5);
      $this->forward404Unless($node = WorkorderItemPeer::retrieveByPk($node));
      $c = new Criteria();
      $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
      $c->addAscendingOrderByColumn(PartVariantPeer::INTERNAL_SKU);
      $parts = $node->getPartInstances($c);
      $output = array();
      foreach ($parts AS $part)
      {
        $info = '';
        $cls = '';
        if ($part->getSupplierOrderItemId() && !$part->getDelivered())
        {
          $cls = 'red-row';
          $supp = $part->getSupplierOrderItem()->getSupplierOrder()->getSupplier();
          $info = 'Special Ordered from '.$supp->getName();
        }
        else if ($part->getDelivered())
        {
          $cls = 'green-row';
          $info = 'Utilized';
          if ($part->getSerialNumber() && !$part->getCustomName())
          {
            $info .= '; Serial #'.$part->getSerialNumber();
          }
        }
        else if ($part->getAllocated())
        {
          $cls = 'orange-row';
          $info = 'On Hold in Inventory';
        }
        else
        {
          $info = 'Estimate';
        }

        //deal with one-off parts
        if ($part->getCustomName())
        {
          $name = $part->getCustomName();
          $info .= (strlen($info) ? '; ' : '').'One-Off Part';
        } 
        else
        {
          $sku = ($part->getPartVariant()->getInternalSku() ? $part->getPartVariant()->getInternalSku().': ' : '');
          $name = $sku.$part->getPartVariant()->__toString();
        }
        $name .= '<div style="display:inline-block;border: 1px solid #c9dac9;float: right; font-size: 0.9em; padding: 1px 2px;background-color: #f7f7f7;">Qty: '.$part->outputQuantity(!($part->getCustomName())).'</div>';
        $output[] = array('id' => 'part-'.$node->getId().'-'.$part->getId(),
                          'text' => $name,
                          'iconCls' => 'part',
                          'qty' => $part->outputQuantity(!($part->getCustomName())),
                          'cls' => $cls,
                          'estimate' => ($part->getEstimate() ? $part->getSubtotal() : null),
                          'actual' => ($part->getAllocated() ? $part->getSubtotal() : null ),
                          'leaf' => true,
                          'info' => $info,
                          'custom' => ($part->getCustomName() ? true : false)
                          );
      }
    }
    else if (strpos($node, 'labour-') === 0)
    {
      //OUTPUT ALL LABOUR INFO FOR AN ITEM
      $node = substr($node, 7);
      $this->forward404Unless($node = WorkorderItemPeer::retrieveByPk($node));

      $c = new Criteria();
      $c->add(TimelogPeer::WORKORDER_ITEM_ID, $node->getId());
      $c->addDescendingOrderByColumn(wfCRMPeer::LAST_NAME);
      $c->addDescendingOrderByColumn(TimelogPeer::END_TIME);
      $logs = TimelogPeer::doSelectJoinEmployeeAndLabour($c);

      $estimates = array();
      $estimates_total = 0;
      $labourdata = array();
      $labourtypes = array();
      $labourtotals = array();
      $employees = array();

      foreach ($logs AS $log)
      {
        if ($log->getEstimate())
        {
          $estimates[] = $log;
          $estimates_total += $log->getCost();

          if ($log->getLabourTypeId())
          {
            $labourtypes[$log->getLabourTypeId()] = $log->getLabourType();
          }
        }
        else
        {
          $type_index = ($log->getLabourTypeId() ? $log->getLabourTypeId() : 'Custom: '.$log->getCustomLabel());

          if (!isset($labourtypes[$type_index]))
          {
            $labourtypes[$type_index] = $log->getLabourType();
          }

          if (!isset($labourdata[$type_index]))
          {
            $labourdata[$type_index] = array();
          }
          
          $labourdata[$type_index][] = $log;

          //add up the total cost by employee and labour type
          if (!isset($labourtotals[$type_index]))
          {
            $labourtotals[$type_index] = 0;
          }

          $labourtotals[$type_index] += $log->getCost();
        }
      }
      
      //retrieve needed employees
      $c = new Criteria();
      $c->add(EmployeePeer::ID, array_keys($employees), Criteria::IN);
      $employees_query = EmployeePeer::doSelectJoinWfCRM($c);
      foreach ($employees_query AS $employee)
      {
        $employees[$employee->getId()] = $employee;
      }

      //construct output array
      $output = array();

      //fetch the estimate stuff first
      if (count($estimates) > 0)
      {
          $labour_out = array('id' => 'labour-'.$node->getId().'-estimate',
                            'text' => 'Estimated Labour',
                            'cls' => 'unselectable_node',
                            'iconCls' => 'labour',
                            'draggable' => false,
                            'expanded' => true,
                            'allowDrop' => false,
                            'estimate' => $estimates_total,
                            'actual' => null,
                            'leaf' => false,
                            'children' => array()
                          );
        $children = array();
        foreach ($estimates AS $log)
        {
          $label = ($log->getLabourTypeId() ? $log->getLabourType()->getName() : $log->getCustomLabel());
          $labour_out['children'][] = array('id' => 'labour-'.$node->getId().'-estimate-'.$log->getId(),
                                            'text' => $label.' ('.$log->getHoursAndMinutes(false).' @ '.$log->getRate().'/hr)',
                                            'iconCls' => 'labour',
                                            'estimate' => $log->getCost(),
                                            'actual' => null,
                                            'leaf' => true,
                                            'draggable' => true,
                                            'info' => 'Estimate'
                                           );
        }

        $output[] = $labour_out;
      }

      foreach ($labourdata AS $this_labourtype_id => $this_logs)
      {
        $labour_out = array('id' => 'labour-'.$node->getId().'-'.$this_labourtype_id,
                            'text' => (is_int($this_labourtype_id) ? $labourtypes[$this_labourtype_id]->getName() : $this_labourtype_id),
                            'cls' => 'unselectable_node',
                            'iconCls' => 'labour',
                            'draggable' => false,
                            'expanded' => true,
                            'allowDrop' => false,
                            'estimate' => null,
                            'actual' => $labourtotals[$this_labourtype_id],
                            'leaf' => false,
                            'children' => array()
                          );

        foreach ($this_logs AS $log)
        {
          $status = '';
          $cls = '';
          if (trim($log->getAdminNotes()) != '')
          {
            $status .= '<img src="/images/silkicon/page_red.png" title="Admin Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
          }
          if (trim($log->getEmployeeNotes()) != '')
          {
            $status .= '<img src="/images/silkicon/page_green.png" title="Employee Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
          }
          if ($log->getAdminFlagged())
          {
            $cls = 'red-row';
            $status .= '<img src="/images/silkicon/flag_red.png" title="FLAGGED FOR REVIEW" width="14" height="14" style="float: left; margin-right: 3px;" /> FLAGGED FOR REVIEW';
          }
          else if ($log->getApproved())
          {
            $cls = 'green-row';
            $status .= '<img src="/images/silkicon/tick.png" title="Approved" width="14" height="14" style="float: left; margin-right: 3px;" /> Approved';
          }
          else
          {
            $cls = 'orange-row';
            $status .= '<img src="/images/silkicon/error.png" title="Unapproved" width="14" height="14" style="float: left; margin-right: 3px;" /> Unapproved';
          }
          $labour_out['children'][] = array('id' => 'labour-'.$node->getId().'-'.$this_labourtype_id.'-'.$log->getId(),
                                            'text' => $log->getEmployee()->generateName().': '.$log->getEndTime('M j, Y').' ('.$log->getHoursAndMinutes(false).' @ '.$log->getRate().'/hr)',
                                            'iconCls' => 'labour',
                                            'estimate' => null,
                                            'actual' => $log->getCost(),
                                            'leaf' => true,
                                            'cls'  => $cls,
                                            'info' => $status
                                           );
        } //end logs

        $output[] = $labour_out;
      }//end labour type
    }
    else if (strpos($node, 'expense-') === 0)
    {
      //OUTPUT EXPENSE INFO FOR AN ITEM
      $node = substr($node, 8);
      $this->forward404Unless($node = WorkorderItemPeer::retrieveByPk($node));
      $c = new Criteria();
      $expenses = $node->getWorkorderExpenses($c);
      $output = array();
      foreach ($expenses AS $expense)
      {
        $status = '';
        if (trim($expense->getInternalNotes()) != '')
        {
          $status .= '<img src="/images/silkicon/page_red.png" title="Internal Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
        }
        if (trim($expense->getCustomerNotes()) != '')
        {
          $status .= '<img src="/images/silkicon/page_green.png" title="Customer Notes" width="14" height="14" style="float: left; margin-right: 3px;" /> ';
        }
        if ($expense->getEstimate() && !$expense->getInvoice())
        {
          $status .= 'Estimate Only';
        }

        $output[] = array('id' => 'expense-'.$node->getId().'-'.$expense->getId(),
                          'text' => $expense->getLabel(),
                          'iconCls' => 'expense',
                          'estimate' => $expense->getEstimate() ? $expense->getPrice() : null,
                          'info' => $status,
                          'actual' => $expense->getInvoice() ? $expense->getPrice() : null,
                          'leaf' => true
                         );
      }
    }
    else
    {
      //must check for root nodes and convert node text into actual tree node
      $node = $request->getParameter('node');
      if (strpos($node, 'root-') === 0)
      {
        $node = substr($node, 5);
        $this->forward404Unless($node = WorkorderItemPeer::retrieveRoot($node));
      }
      else
      {
        $this->forward404Unless($node = WorkorderItemPeer::retrieveByPk($node));
      }

      $wo = $node->getWorkorder();
      $output = $wo->baseDetailsTree($node);
    }

    $this->renderText(json_encode($output));

    if (sfConfig::get('sf_logging_enabled'))
    {
      $message = 'DONE detailstreeAction.execute()=============';
      sfContext::getInstance()->getLogger()->info($message);
    }

    return sfView::NONE;
  }//execute()-----------------------------------------------------------------

}//detailstreeAction{}=========================================================
