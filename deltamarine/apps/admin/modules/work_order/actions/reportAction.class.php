<?php

class reportAction extends sfAction
{
  public function execute($request)
  {
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    $sub_by_task = ($this->getRequestParameter('sub_by_task') == '1');
    $sub_by_type = ($this->getRequestParameter('sub_by_type') == '1');
    $sub_by_profit = ($this->getRequestParameter('sub_by_profit') == '1');

    $data = $workorder->getReportData($sub_by_task, $sub_by_type, $sub_by_profit);

    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($data[$workorder->getId()]));   
  }

}