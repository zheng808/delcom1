<?php

class printAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isMethod('post'));
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    //get the settings

    $s['whom']              = $request->getParameter('whom_id', 'cust');
    
    $invoice_id = $request->getParameter('invoice_id', 'final');
    
    if ($invoice_id == 'final')
    {
      $s['progress_title']  = 'New Charges';
      $s['invoice']         = null;  
    }
    else
    {
      $invoice = WorkorderInvoicePeer::retrieveByPk($invoice_id);
      
      $c = new Criteria();
      $c->add(WorkorderInvoicePeer::WORKORDER_ID, $workorder->getId());
      $c->addJoin(WorkorderInvoicePeer::INVOICE_ID, InvoicePeer::ID);
      $c->addAscendingOrderByColumn(InvoicePeer::ISSUED_DATE);
      $invoices = WorkorderInvoicePeer::doSelect($c);
      $counter = 1;
      foreach ($invoices AS $test_invoice)
      {
        if ($test_invoice->getId() == $invoice->getId())
        {
          $s['progress_title'] = 'Progress Billing #'.$counter;
        }
        $counter ++;
      }
      $s['invoice']         = $invoice;
    }

    $s['show_progress']     = (bool) $request->getParameter('show_progress');
    $s['taxable_hst']       = !$workorder->getHstExempt();
    $s['taxable_pst']       = (bool) $request->getParameter('taxable_pst');
    $s['taxable_gst']       = (bool) $request->getParameter('taxable_gst');
    $s['shop_supplies']     = (bool) $request->getParameter('shop_supplies');
    $s['moorage']           = (bool) $request->getParameter('moorage');
    $s['show_discounts']    = (bool) $request->getParameter('show_discounts');

    $s['tasks_notes']       = (bool) $request->getParameter('tasks_notes');
    $s['tasks_paged']       = (bool) $request->getParameter('tasks_paged');

    $s['parts_detail']      = $request->getParameter('parts_detail',   'all');
    $s['labour_detail']     = $request->getParameter('labour_detail',  'cat');
    $s['expense_detail']    = $request->getParameter('expense_detail', 'all');

    $s['origin']            = ($request->getParameter('origin') == 1);
    $s['parts_minvalue']    = ($s['parts_detail'] == 'value' ? $request->getParameter('parts_value_min', false) : false);

    $s['summary_tasks']     = (bool) $request->getParameter('summary_tasks');
    $s['summary_parts']     = (bool) $request->getParameter('summary_parts');
    $s['summary_labour']    = (bool) $request->getParameter('summary_labour');

    $s['payments']          = (bool) $request->getParameter('payments');
    $s['payments_existing'] = (bool) $request->getParameter('payments_existing');

    $pdf = new WorkorderPDF($workorder, $s);
    $pdf->generate();
    $pdf->Output('workorder_'.$workorder->getId().'_'.date('Y-M-d').'.pdf', 'D');

    return sfView::NONE;
  }

  private function _recurse_sections($parent, $c, $task_prefix)
  {
    $sections = array();

    //step 1: get all instances which have this has parent
    if (!$parent->isRoot())
    {
      $c1 = clone $c;
      $c1->add(PartInstancePeer::WORKORDER_ITEM_ID, $parent->getId());
      $items = PartInstancePeer::doSelectJoinPartVariant($c1);
      $sections[] = array('title' => 'Task '.$task_prefix.': '.$parent->getLabel(), 'items' => $items);
    }

    //step 2: look for children
    if ($parent->hasChildren())
    {
      $counter = 0;
      foreach ($parent->getChildren() AS $child)
      {
        $counter ++;
        $new_task_prefix = $task_prefix.($task_prefix == '' ? '' : '.').$counter;
        $return = $this->_recurse_sections($child, $c, $new_task_prefix);
        foreach ($return AS $ret)
        {
          $sections[] = $ret;
        }
      }
    }

    return $sections;
  }

}
