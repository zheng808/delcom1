<?php

/**
 * timelogs actions.
 *
 * @package    deltamarine
 * @subpackage timelogs
 * @author     Eugene Trinchuk
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class timelogsActions extends sfActions
{

  /*
   * display the main list and search interface
   */
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  public function executeChangeStatus(sfWebRequest $request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    //load up timelogs and ensure all are found
    $ids = explode(',', $request->getParameter('ids'));
    $this->forward404Unless($timelogs = TimelogPeer::retrieveByPks($ids));
    $this->forward404Unless(count($timelogs) == count($ids));

    //make sure a valid action is selected
    $action = $request->getParameter('dowhat');
    $this->forward404Unless($action == 'approve' || $action == 'unapprove' 
                              || $action == 'flag' || $action == 'unflag' 
                              || $action == 'delete' || $action == 'OT' || $action =='DT');

    //loop through found timelogs and edit them
    foreach ($timelogs AS $timelog)
    {
      if ($action == 'delete')
      {
        //don't allow deleting if workorder isn't in progress
        if ($timelog->getWorkorderItemId() && $timelog->getWorkorderItem()->getWorkorder()->getStatus() != 'In Progress')
        {
          $this->renderText('{success:false,reason:\'Cannot delete a timelog for a workorder that is not curently in progress.\'}');
          return sfView::NONE;
        }

        //let the model take care of updating the workorder info
        $timelog->delete();
      }else if($action == 'approve'){
        //unflagging and unapproving both set approved/flagged values to false
        $timelog->setApproved($action == 'approve');
        $timelog->save();
      }else if($action == 'OT'){
        $hours = $timelog->getBillableHours();
        $hours = $hours * 1.5;
        $hours = (round($hours*4))/4;
        $timelog->setBillableHours($hours);
        $timelog->save();
      }else if($action == 'flag'){
        $timelog->setAdminFlagged($action == 'flag');
        $timelog->save();
      }else if($action == 'DT'){
        $hours = $timelog->getBillableHours();
        $hours = $hours * 2;
        $timelog->setBillableHours($hours);
        $timelog->save();
      }else if($action == 'unapprove'){
        $timelog->setApproved(false);
        $timelog->save();
      }else if($action == 'unflag'){
        $timelog->setAdminFlagged(false);
        $timelog->save();
      }
    }
    //output result as JSON
    $this->renderText("{success:true, action:".json_encode($action)."}");
    return sfView::NONE;
  }

  /*
   * loads up information about a timelog for editing
   */
  public function executeLoad($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());
    $this->forward404Unless($request->isMethod('post'));

    //load up timelog
    $this->forward404Unless($timelog = TimelogPeer::retrieveByPk($request->getParameter('id')));

    $billable = true;
    $custom = false;
    if ($timelog->getNonbillTypeId())
    {
      $billable = false;
      $rate = null;
      $custom_label = null;
    }
    else if ($timelog->getLabourTypeId())
    {
      $rate = $timelog->getRate();
      $custom_label = $timelog->getLabourType()->getName();
    }
    else
    {
      $rate = $timelog->getRate();
      $custom_label = $timelog->getCustomLabel();
      $custom = true;
    }
    $data = array('id'                => $timelog->getId(),
                  'employee_id'       => $timelog->getEmployeeId(),
                  'employee_name'     => ($timelog->getEmployeeId() ? $timelog->getEmployee()->getName(false, false, false) : null),
                  'billable'          => $billable ? 1 : 0,
                  'labour_type_id'    => $timelog->getLabourTypeId(),
                  'nonbill_type_id'   => $timelog->getNonbillTypeId(),
                  'date'              => $timelog->getEndTime('m/d/Y'),
                  'start_time'        => ($timelog->getStartTime() ? $timelog->getStartTime('g:i A') : ''),
                  'end_time'          => ($timelog->getStartTime() ? $timelog->getEndTime('g:i A') : ''),
                  'billable_hours'    => $timelog->getBillableHours(),
                  'payroll_hours'     => $timelog->getPayrollHours(),
                  'rate'              => $rate,
                  'cost'              => $timelog->getCost(),
                  'workorder_id'      => $timelog->getWorkorderId(),
                  'workorder_summary' => $timelog->getWorkorderSummary(),
                  'workorder_item_id' => $timelog->getWorkorderItemId(),
                  'workorder_item_name' => $timelog->getWorkorderItemName(),
                  'status'            => $timelog->getStatus(),
                  'estimate'          => ($timelog->getEstimate() ? '1' : '0'),
                  'custom_label'      => $custom_label,
                  'custom'            => $custom,
                  'employee_notes'    => $timelog->getEmployeeNotes(),
                  'admin_notes'       => $timelog->getAdminNotes(),
		  'created_at'	      => $timelog->getCreatedAt('M/d/Y g:i A'),
		  'updated_at'	      => $timelog->getUpdatedAt('M/d/Y g:i A'),
                 );

    $this->renderText("{success:true, data:".json_encode($data)."}");

    return sfView::NONE;
  }

  /*
   * edit a timelog's details
   */
  public function executeSave(sfWebRequest $request)
  {
    if ($request->getParameter('id') != 'new')
    {
      $this->forward404Unless($tl = TimelogPeer::retrieveByPk($request->getParameter('id')));
    }
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //validate
    $result = true;
    $errors = array();

    //permissions
    $self_add = $this->getUser()->hasCredential('timelogs_add_self');
    $other_add = $this->getUser()->hasCredential('timelogs_add_other');
    $other_edit = $this->getUser()->hasCredential('timelogs_edit');
    $can_approve = $this->getUser()->hasCredential('timelogs_approve');
    if (isset($tl) && !$other_edit)
    {
      $result = false;
      $errors['employee_id'] = 'You do not have permission to edit timelogs of other employees';
    }
    else if (!isset($tl) && !$other_add && ($request->getParameter('employee_id') != $this->getUser()->getEmployee()->getId()))
    {
      $result = false;
      $errors['employee_id'] = 'You only have permission to add timelogs for yourself!';
    }
    else if (!isset($tl) && !$self_add)
    {
      $this->forward404();
    }

    $estimate = $request->getParameter('estimate') == '1';
    $custom = $request->getParameter('custom') == '1';

    //check for employee
    if (!$estimate && !($emp = EmployeePeer::retrieveByPk($request->getParameter('employee_id'))))
    {
      $result = false;
      $errors['employee_id'] = 'Invalid Employee selected';
    }

    //check for times/hours mismatch
    if (!$estimate && !($request->getParameter('payroll_hours')))
    {
      $result = false;
      $errors['payroll_hours'] = 'Must specify hours!'; 
    }
    //check for invalid workorder, workorderitem, labourtypeid if billable
    if ($request->getParameter('billable') == '1')
    {
      if ($request->getParameter('billable_hours') == '')
      {
        $result = false;
        $errors['billable_hours'] = 'Cannot be left blank. Set to "0" if you do not want to bill this timelog to customer.';
      }
      //check workorder
      if (!($wo = WorkorderPeer::RetrieveByPk($request->getParameter('workorder_id'))))
      {
        $result = false;
        $errors['workorder_id'] = 'Invalid Workorder Selected';
      }
      else if (!($woi = WorkorderItemPeer::RetrieveByPk($request->getParameter('workorder_item_id'))))
      {
        $result = false;
        $errors['workorder_item_id'] = 'Invalid Workorder Item Selected';
      }
      else if ($woi->getWorkorderId() != $wo->getId())
      {
        $result = false;
        $errors['workorder_item_id'] = 'Workorder Item does not belong to selected workorder! Please re-select workorder and try again.';
      }
      //check labour type
      if ($custom)
      {
        if (trim($request->getParameter('custom_label')) == '')
        {
          $result = false;
          $errors['custom_label'] = 'Invalid Custom Labour Type label';
        }
        $amt = (float) $request->getParameter('rate');
        if ($amt <= 0 || $amt > 1000)
        {
          $result = false;
          $errors['rate'] = 'Custom rate out of acceptible range.';
        }
      }
      else if (!($type = LabourTypePeer::retrieveByPk($request->getParameter('labour_type_id'))))
      {
        $result = false;
        $errors['labour_type_id'] = 'Invalid Labour Type selected';
      }
    }
    //check non-billable work type
    else 
    {
      if (!($type = NonbillTypePeer::retrieveByPk($request->getParameter('nonbill_type_id'))))
      {
        $result = false;
        $errors['nonbill_type_id'] = 'Invalid Non-Billable Type selected';
      }
    }

    //check for status hack
    if (!$can_approve && ($request->getParameter('status') == "Approved"))
    {
      if (isset($tl) && !$tl->getApproved())
      {
        $result = false;
        $errors['reason'] = 'You do not have permissions to approve timelogs.';
      }
      else
      {
        $result = false;
        $errors['reason'] = 'You do not have permissions to approve timelogs.';
      }
    }

    //save the object
    if ($result)
    {
      //create a new timelog object
      if (!isset($tl))
      {
        $tl = new Timelog();
      }

      $old_parent = $tl->getWorkorderItem();
      $tl->setEstimate($estimate);
      $tl->setEmployeeId($request->getParameter('employee_id'));
      if ($request->getParameter('billable') == '1')
      {
        $tl->setWorkorderItemId($request->getParameter('workorder_item_id'));
        $tl->setNonbillTypeId(null);
        $tl->setTaxableHst(($wo->getHstExempt() ? 0 : sfConfig::get('app_hst_rate')));
        $tl->setTaxablePst(($wo->getPstExempt() ? 0 : sfConfig::get('app_pst_rate')));
        $tl->setTaxableGst(($wo->getGstExempt() ? 0 : sfConfig::get('app_gst_rate')));
        $tl->setLabourTypeId(($custom ? null : $request->getParameter('labour_type_id')));
      }
      else
      {
        $tl->setWorkorderItemId(null);
        $tl->setNonbillTypeId($request->getParameter('nonbill_type_id'));
        $tl->setLabourTypeId(null);
        $tl->setTaxableHst(0);
        $tl->setTaxablePst(0);
        $tl->setTaxableGst(0);
      }
      $date = $request->getParameter('date');
      $tl->setEndTime(strtotime($date));

      if ($custom)
      {
        $tl->setCustomLabel($request->getParameter('custom_label'));
        $tl->setRate($request->getParameter('rate'));
      }

      $tl->setPayrollHours($request->getParameter('payroll_hours'));
      $tl->setBillableHours($request->getParameter('billable_hours'));
      $tl->setEmployeeNotes($request->getParameter('employee_notes'));
      $tl->setAdminNotes($request->getParameter('admin_notes'));
      $tl->setAdminFlagged($request->getParameter('status') == 'Flagged');
      $tl->setApproved($request->getParameter('status') == 'Approved');

      //save and update labour cost of old parent if needed
      $tl->save();
      if ($old_parent && $old_parent->getId() != $tl->getWorkorderItemId())
      {
        $old_parent->calculateActualLabour();
      }

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      if (!isset($errors['reason'])){
        $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      }
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;
  }

///////////////////////////// LABOUR TYPES /////////////////////////////////////

  /*
   *  View the list of labours for editing/adding
   */
  public function executeLabour(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * add a new labour
   */
  public function executeLabourEdit(sfWebRequest $request)
  {
    $existing = LabourTypePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    //validate
    $result = true;
    $errors = array();

    //check for same name
    $c = new Criteria();
    $c->add(LabourTypePeer::NAME, trim($request->getParameter('name')));
    if ($existing)
    {
      $c->add(LabourTypePeer::ID, $existing->getId(), Criteria::NOT_EQUAL);
    }
    if (LabourTypePeer::doSelectOne($c))
    {
      $result = false;
      $errors['name'] = 'Labour type with that name already exists!';
    }

    //create object
    if ($result)
    {
      if (!$existing)
      {
        $existing = new LabourType();
      }
      $existing->setName(trim($request->getParameter('name')));
      $existing->setHourlyRate($request->getParameter('rate'));
      $existing->setActive($request->getParameter('active') == '1');
      $existing->save();

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;

  }

  public function executeLabourDelete(sfWebRequest $request)
  {
    $this->forward404Unless($rate = LabourTypePeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //edit existing timelogs
    $select_c = new Criteria(TimelogPeer::DATABASE_NAME);
    $select_c->add(TimelogPeer::LABOUR_TYPE_ID, $rate->getId());
    $newval_c = new Criteria(TimelogPeer::DATABASE_NAME);
    $newval_c->add(TimelogPeer::LABOUR_TYPE_ID, null);
    $con = Propel::getConnection(TimelogPeer::DATABASE_NAME);
    BasePeer::doUpdate($select_c, $newval_c, $con);

    //delete
    $rate->delete();

    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;

  }

  /*
   *  View the list of nonbill for editing/adding
   */
  public function executeNonbill(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
   * add a new nonbill
   */
  public function executeNonbillEdit(sfWebRequest $request)
  {
    $existing = NonbillTypePeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());
    
    //validate
    $result = true;
    $errors = array();

    //check for same name
    $c = new Criteria();
    $c->add(NonbillTypePeer::NAME, trim($request->getParameter('name')));
    if ($existing)
    {
      $c->add(NonbillTypePeer::ID, $existing->getId(), Criteria::NOT_EQUAL);
    }
    if (NonbillTypePeer::doSelectOne($c))
    {
      $result = false;
      $errors['name'] = 'Nonbill type with that name already exists!';
    }

    //create object
    if ($result)
    {
      if (!$existing)
      {
        $existing = new NonbillType();
      }
      $existing->setName(trim($request->getParameter('name')));
      $existing->save();

      //output result as JSON
      $this->renderText("{success:true}");
    }
    else
    {
      $errors['reason'] = 'Invalid Input detected. Please check and try again.';
      $this->renderText(json_encode(array('success' => false, 'errors' => $errors)));
    }

    return sfView::NONE;

  }

  public function executeNonbillDelete(sfWebRequest $request)
  {
    $this->forward404Unless($rate = NonbillTypePeer::retrieveByPk($request->getParameter('id')));
    $this->forward404Unless($request->isMethod('post'));
    //$this->forward404Unless($request->isXmlHttpRequest());

    //edit existing timelogs
    $select_c = new Criteria(TimelogPeer::DATABASE_NAME);
    $select_c->add(TimelogPeer::NONBILL_TYPE_ID, $rate->getId());
    $newval_c = new Criteria(TimelogPeer::DATABASE_NAME);
    $newval_c->add(TimelogPeer::NONBILL_TYPE_ID, null);
    $con = Propel::getConnection(TimelogPeer::DATABASE_NAME);
    BasePeer::doUpdate($select_c, $newval_c, $con);

    //delete
    $rate->delete();

    $this->renderText(json_encode(array('success' => true)));
    return sfView::NONE;

  }

}
