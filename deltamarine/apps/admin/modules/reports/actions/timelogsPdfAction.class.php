<?php

class timelogsPdfAction extends sfAction
{

  public function execute($request)
  {
//    $this->forward404Unless($request->isMethod('post'));
    //get the settings
    $workorder = WorkorderPeer::retrieveByPk((int) $request->getParameter('workorder_id', null));
    $employee  = EmployeePeer::retrieveByPk((int) $request->getParameter('employee_id', null));
    $type      = $request->getParameter('type');
    $summary   = $request->getParameter('summary') == 'summary' ? true : false;
    $status    = $request->getParameter('status');
    $start     = strtotime($request->getParameter('start_date'));
    $end       = strtotime($request->getParameter('end_date'));
    if (!$start) $start = mktime(0,0,0);
    if (!$end) $end = mktime(23,59,59);
    
    //set title and subtitles
    $title = 'Employee Timesheet Report ('.date('M d, Y', $start).' to '.date('M d, Y', $end).')';
    $subtitles = array();

    //create base criteria for sorting
    $sql = "SELECT ".TimelogPeer::EMPLOYEE_ID.", (CASE WHEN ".wfCRMPeer::IS_COMPANY." THEN ".wfCRMPeer::DEPARTMENT_NAME." ELSE CONCAT_WS(' ',".wfCRMPeer::FIRST_NAME.",".wfCRMPeer::LAST_NAME.") END) as customer, ".WorkorderItemPeer::WORKORDER_ID." as woid, ".TimeLogPeer::RATE." as rate, ";
    if (!$summary)
    {
        $sql .= "date(".TimelogPeer::END_TIME.") as logdate, ";
    }
    $sql .= "SUM(".TimelogPeer::PAYROLL_HOURS.") as hours ".
            "FROM ".TimelogPeer::TABLE_NAME.", ".WorkorderItemPeer::TABLE_NAME.", ".WorkorderPeer::TABLE_NAME.", ".CustomerPeer::TABLE_NAME.", ".wfCRMPeer::TABLE_NAME;

    //perform filtering based on settings
    $sql .= " WHERE ";
    $wheres = array();
    $wheres[] = TimelogPeer::WORKORDER_ITEM_ID." = ".WorkorderItemPeer::ID;
    $wheres[] = WorkorderItemPeer::WORKORDER_ID." = ".WorkorderPeer::ID;
    $wheres[] = WorkorderPeer::CUSTOMER_ID." = ".CustomerPeer::ID;
    $wheres[] = CustomerPeer::WF_CRM_ID." = ".WfCRMPeer::ID;
    $wheres[] = TimelogPeer::ESTIMATE ." = 0";
    if ($workorder)
    {
        $wheres[] = WorkorderItemPeer::WORKORDER_ID." = ".$workorder->getId();
        $subtitles[] = "For Workorder #".$workorder->getId();
    }
    if ($employee)
    {
        $wheres[] = TimelogPeer::EMPLOYEE_ID ." = ".$employee->getId();
    }
    if ($status == 'Approved')
    {
        $wheres[] = TimelogPeer::APPROVED ." = 1";
        $subtitles[] = 'Approved Timelogs only';
    }
    else if ($status == 'Flagged')
    {
        $wheres[] = TimelogPeer::ADMIN_FLAGGED ." = 1";
        $subtitles[] = 'Flagged Timelogs';
    }
    else if ($status == 'Unapproved')
    {
        $wheres[] = TimelogPeer::APPROVED ." = 0";
        $wheres[] = TimelogPeer::ADMIN_FLAGGED ." = 0";
        $subtitles[] = 'Unapproved Timelogs';
    }
    $wheres[] = TimelogPeer::END_TIME." >= '".date('Y-m-d 00:00:00', $start)."'";
    $wheres[] = TimelogPeer::END_TIME." <= '".date('Y-m-d 23:59:59', $end)."'";

    //pre-fill data array with employee IDs
    if ($employee)
    {
        $data[$employee->getId()] = array('items' => array(), 'title' => $employee->generateName());
    }
    else
    {
        $c = new Criteria();
        $c->add(EmployeePeer::HIDDEN, false);
        $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);
        $emps = EmployeePeer::doSelectJoinWfCRM($c);
        $data = array();
        foreach ($emps AS $emp)
        {
            $data[$emp->getId()] = array('items' => array(), 'title' => $emp->generateName());
        }
    }

    //finish up the query
    $sql .= implode(' AND ', $wheres);
    $sql .= ' GROUP BY '.TimelogPeer::EMPLOYEE_ID.', '.WorkorderItemPeer::WORKORDER_ID.', '.TimelogPeer::RATE;
    if (!$summary)
    {
        $sql .= ', DATE('.TimelogPeer::END_TIME.')';
    }

    //perform the query
    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        if ($summary)
        {
            $data[$row['EMPLOYEE_ID']]['items'] = array('bill' => $row['hours'], 'nonbill' => 0);
        }
        else
        {
            if (!isset($data[$row['EMPLOYEE_ID']]['items'][$row['logdate']])) $data[$row['EMPLOYEE_ID']]['items'][$row['logdate']] = array();
            $data[$row['EMPLOYEE_ID']]['items'][$row['logdate']][] = $row;
        }
    }

    //compile subtitles
    $subtitle = implode(' / ', $subtitles);

    $pdf = new TimelogsReportPDF($title, $subtitle);
    $pdf->generate($data, $start, $end, $summary);
    $pdf->Output('timesheet_report.pdf', 'D');

    return sfView::NONE;
  }
}
