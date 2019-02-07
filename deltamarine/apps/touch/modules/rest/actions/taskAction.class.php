<?php

class taskAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {

    $tasks = array();

    if ($request->getParameter('id'))
    {
        $task = WorkorderItemPeer::retrieveByPk($request->getParameter('id'));
        $tasks[] = array(
          'id'             => $task->getId(),
          'name'           => $task->getLabel(),
          'workorder_id'   => $task->getWorkorderId(),
          'completed'      => $task->getCompleted(),
          'completed_name' => ($task->getCompletedBy() ? $task->getEmployee()->generateName() : ''),
          'completed_date' => $task->getCompletedDate('M j, Y'),
          'internal_notes' => $task->getInternalNotes(),
          'customer_notes' => $task->getCustomerNotes(),
          'level'          => $task->getLevel(),
          'path'           => $task->getHierarchy(),
          'numbering'      => 0
        );
    }
    else
    {
        $tree = WorkorderItemPeer::retrieveAllTree($request->getParameter('workorder_id'));
        array_shift($tree); //get rid of root node
    
        $numbering = array();
        $last_parent = null;
        $last_task = null;
        $last_number = null;
        $prefix = array();
        foreach ($tree AS $task)
        {
          $parent_id = $task->retrieveParent()->getId();
          if (isset($numbering[$parent_id])){
            //first task with this parent
            $numbering[$parent_id] = $numbering[$parent_id] + 1;
          } else { 
            $numbering[$parent_id] = 1;            
          }
          if ($last_task && $last_task == $parent_id) 
          {
            //this is a child of that one
            $prefix[] = $numbering[$last_parent];
          } 
          else if ($last_parent && $last_parent != $parent_id)
          {
            //stepping back out
            array_pop($prefix);
          }
          $last_parent = $parent_id;
          $last_number = $numbering[$parent_id];
          $last_task = $task->getId();
          $this_numbering = $prefix;
          $this_numbering[] = $last_number;
          $this_numbering = implode('.', $this_numbering);

          $tasks[] = array(
            'id'             => $task->getId(),
            'name'           => $task->getLabel(),
            'workorder_id'   => $task->getWorkorderId(),
            'completed'      => $task->getCompleted(),
            'completed_name' => ($task->getCompletedBy() ? $task->getEmployee()->generateName() : ''),
            'completed_date' => $task->getCompletedDate('M j, Y'),
            'internal_notes' => $task->getInternalNotes(),
            'customer_notes' => $task->getCustomerNotes(),
            'level'          => $task->getLevel() - 1,
            'path'           => $task->getHierarchy(),
            'numbering'     => $this_numbering
          );
        }
    }

    $count_all = count($tasks); 
    $dataarray = array('success' => true, 'totalCount' => $count_all, 'tasks' => $tasks);

    return $dataarray;
  }

}
