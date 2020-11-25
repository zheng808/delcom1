<?php

class printestimateAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isMethod('post'));
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    //get the settings

    $s['whom']              = 'cust';
    $s['taxable_hst']       = !$workorder->getHstExempt();
    $s['taxable_pst']       = (bool) $request->getParameter('taxable_pst');
    $s['taxable_gst']       = (bool) $request->getParameter('taxable_gst');
    $s['shop_supplies']     = (bool) $request->getParameter('shop_supplies');
    $s['moorage']           = (bool) $request->getParameter('moorage');

    $s['customer_notes']    = (bool) $request->getParameter('estimate_notes');
    $s['subtasks']          = (bool) $request->getParameter('subtasks');
    $s['delivery_time']     = trim($request->getParameter('delivery_time'));

    $s['parts_detail']      = $request->getParameter('parts_detail',  'all');
    $s['labour_detail']     = $request->getParameter('labour_detail',  'all');
    $s['other_detail']      = $request->getParameter('other_detail',  'all');
    $s['show_blank']       = $request->getParameter('show_blank',  'all');


    $pdf = new EstimatePDF($workorder, $s);
    $pdf->generate();
    $pdf->Output('estimate_'.$workorder->getId().'_'.date('Y-M-d').'.pdf', 'D');

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
