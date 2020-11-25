<?php

class timelogsprintAction extends sfAction
{

  public function execute($request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    //get the settings
    $cost     = (bool) $request->getParameter('cost', true);
    $status    = (bool) $request->getParameter('status', true);
    $format    = $request->getParameter('format', 'alllevel');
    $sorting   = $request->getParameter('sorting', 'date');
 
    //load the PDF generator class
    $final_data = array();

    //create base criteria for sorting
    $c = new Criteria();
    $c->addJoin(TimelogPeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    $c->add(TimelogPeer::ESTIMATE, false);

    //apply the sorting rules
    if ($sorting == 'date')
    {
      $c->addAscendingOrderByColumn(TimelogPeer::END_TIME);
    }
    else if ($sorting == 'labour')
    {
      $c->addAscendingOrderByColumn(LabourTypePeer::NAME);
    }
    else if ($sorting == 'employee')
    {
      $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
    }

    //determine the format and collate items as needed
    if ($format == 'single')
    {
      $c1 = clone $c;
      $c1->add(WorkorderItemPeer::WORKORDER_ID, $workorder->getId());

      $items = TimelogPeer::doSelectJoinEmployeeAndLabour($c1);
      $sections = array(array('title' => 'Overall List', 'items' => $items));
    }
    else if ($format == 'toplevel')
    {
      $children = $workorder->getRootItem()->getChildren();
      $counter = 0;
      $sections = array();
      foreach ($children AS $child)
      {
        $descendant_ids = array($child->getId());
        $descendants = $child->getDescendants();
        foreach ($descendants AS $descendant)
        {
          $descendant_ids[] = $descendant->getId();
        }
        unset($descendants);
        $counter++;
        $c1 = clone $c;
        $c1->add(TimelogPeer::WORKORDER_ITEM_ID, $descendant_ids, Criteria::IN);
        $items = TimelogPeer::doSelectJoinEmployeeAndLabour($c1);
        $sections[] = array('title' => 'Task '.$counter.': '.$child->getLabel(), 'items' => $items);
      }
    }
    else if ($format == 'alllevel')
    {
      $sections = array();
      $task_prefix = '';
      $parent = $workorder->getRootItem();
      $sections = $this->_recurse_sections($parent, $c, $task_prefix);
    }

    $pdf = new TimelogsPDF($workorder, $cost, $status);
    $pdf->generate($sections);
    $pdf->Output('timelogslist.pdf', 'D');

    return sfView::NONE;
  }

  private function _recurse_sections($parent, $c, $task_prefix)
  {
    $sections = array();

    //step 1: get all instances which have this has parent
    if (!$parent->isRoot())
    {
      $c1 = clone $c;
      $c1->add(TimelogPeer::WORKORDER_ITEM_ID, $parent->getId());
      $items = TimelogPeer::doSelectJoinEmployeeAndLabour($c1);
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
