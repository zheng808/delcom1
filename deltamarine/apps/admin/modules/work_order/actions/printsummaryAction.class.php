<?php

class printsummaryAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isMethod('post'));
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    $pdf = new WorkorderSummaryPDF($workorder, $s);
    $pdf->generate();
    $pdf->Output('workorder_'.$workorder->getId().'_summary_'.date('Y-M-d').'.pdf', 'D');

    return sfView::NONE;
  }

}
