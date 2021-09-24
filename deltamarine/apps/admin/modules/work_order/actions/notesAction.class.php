<?php
class notesAction extends sfAction{
    public function execute($request)
    {
        $task_id =  $request->getParameter('taskId');
        if(isset($task_id) && $task_id!=''){
            $notes = PartPeer::getAllNotes($task_id);
            foreach($notes As $key=>$note){
                $childarray[] = array(
                'worker_notes' => $note[0],
                'owner_name' => $note[1],
                'created_at' => $note[2],
                'path' => $note[3]
                );
            }    
        }
        
        $this->renderText(json_encode($childarray));
        return sfView::NONE;
    }
}
