<?php

class retreivePartsAction extends sfAction
{

  public function execute($request)
  {
      $taskcount = 0;
      $workorder_id =  $request->getParameter('id', null);
      if(isset($workorder_id)){
        $parts = PartPeer::getPartCSVData($workorder_id);
        $len = count($parts);
        foreach($parts As $key=>$part){
          $previndex = $key - 1;
          $task = 'Task';
          if($nextindex < $len){
            if($parts[$key][0]!=$parts[$previndex][0]){
              $taskcount = $taskcount + 1;
              $task = $task . $taskcount;
            }else{
              $task = $task . $taskcount;
            }
          }  
          $childarray[] = array('taskname' => $part[0],
             'tasknumber' => $task,
             'partname' => $part[1],
             'quantity' => $part[2],
             'unitprice' =>$part[3],
             'origin' => $part[4],
             'total' => $part[5],
             'ExpectedDate' => $part[6]
          );
        }
      }
     
      $this->renderText(json_encode($childarray));
    return sfView::NONE;
  }
}
