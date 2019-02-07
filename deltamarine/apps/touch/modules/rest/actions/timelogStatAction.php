<?php

class timelogStatAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {

    //calculate the date range for this request
    if (!($day = strtotime($request->getParameter('ondate'))))
    {
      $day = time();
    }
    if ($request->getParameter('onweek'))
    {
      $dow = (int) date('w', $day);
      $starttime = $week - ($dow * 86400); //subtract days back to Sunday
      $starttime = mktime(0,0,0,date('n', $starttime),date('j', $starttime),date('Y', $starttime));
      $endtime = $week + ((6 - $dow) * 86400); //add days up till Saturday
      $starttime = mktime(0,0,0,date('n', $endtime),date('j', $endtime),date('Y', $endtime));
    }
    else 
    {
      $starttime = mktime(0,0,0,date('n', $day),date('j', $day),date('Y', $day));
      $endtime = mktime(23,59,59,date('n', $day),date('j', $day),date('Y', $day));
    }

    //get the timelogs for the given period
    $c = new Criteria();
    if ($request->hasParameter('employee_id') && ($timelog = EmployeePeer::retrieveByPk($request->getParameter('employee_id'))))
    {
      $c->add(TimelogPeer::EMPLOYEE_ID, $timelog->getId()); 
    }
    $c1 = $c->getNewCriterion(TimelogPeer::END_TIME, $endtime, Criteria::LESS_EQUAL);
    $c2 = $c->getNewCriterion(TimelogPeer::END_TIME, $starttime, Criteria::GREATER_EQUAL);
    $c1->addAnd($c2);
    $c->addAnd($c1);

    //filter by other parameter
    //TODO

    //generate JSON output
    $timelogs = array();
    $stats = array(
      'billable' => array('total' => 0),
      'nonbillable' => array('total' => 0),
      'workorder' => array(),
      'labour_type' => array(),
      'nonbill_type' => array()
    );

    $daystats = 
    for ($i = $starttime; $i <= $endtime; $i += 86400)
    {
      $daystats[date($i, 'Ymd')] = array('date' => date($i, 'M j, Y'), 'billable' => 0, 'nonbillable' => 0);
    }

    foreach (TimelogPeer::doSelect($c) as $timelog)
    {
      $woi = $timelog->getWorkorderItem();
      $day = $timelog->getEndTime('Ymd');
      if ($timelog->getLabourTypeId())
      {
        $this->addToArray($stats['billable'], 'total', $timelog->getPayrollHours());
        $this->addToArray($stats['billable'], $day, $timelog->getPayrollHours());
        $this->addToArray($stats['workorder'], $woi->getWorkorderId(), $timelog->getPayrollHours());
        $this->addToArray($stats['labour_type'], $timelog->getLabourTypeId(), $timelog->getPayrollHours()); 
        $this->addToArray($daystats[$day], 'billable', $timelog->getPayrollHours());
      }
      else
      {
        $this->addToArray($stats['nonbillable'], 'total', $timelog->getPayrollHours());
        $this->addToArray($stats['nonbillable'], $day, $timelog->getPayrollHours());
        $this->addToArray($stats['nonbill_type'], $timelog->getLabourTypeId(), $timelog->getPayrollHours()); 
        $this->addToArray($daystats[$day], 'nonbillable', $timelog->getPayrollHours());
      }
    }
    $dataarray = array('success' => true, 'stats' => $stats, 'daystats' => array_values($daystats));

    return $dataarray;
  }

  private function addToArray(&$array, $key, $amount){
    if (!isset($array[$key]))
    {
      $array[$key] = 0;
    }
    $array[$key] += $amount;
  }
}
