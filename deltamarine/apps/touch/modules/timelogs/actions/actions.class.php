<?php

/**
 * timelogs actions.
 *
 * @package    deltamarine
 * @subpackage timelogs
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class timelogsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('timelogs/workorderselect');
  }

  public function executeWorkorderselect(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->add(WorkorderPeer::STATUS, 'In Progress');
    $c->addAscendingOrderByColumn(wfCRMPeer::ALPHA_NAME);

    if ($filter_name = $request->getParameter('filter_name', false))
    {
      $c->add(wfCRMPeer::LAST_NAME, $filter_name.'%', Criteria::LIKE);
    }
    if ($filter_boat = $request->getParameter('filter_boat', false))
    {
      $c->add(CustomerBoatPeer::NAME, $filter_boat.'%', Criteria::LIKE);
    }

    $paging = ($request->hasParameter('page'));
    $pager = new sfPropelPager('Workorder', 20);
    $pager->setPeerMethod('doSelectForListing');
    $pager->setPeerCountMethod('doCountforListing');
    $pager->setCriteria($c);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();
    $this->pager = $pager;

    $this->names = WorkorderPeer::getActiveByNameLetter($filter_boat);
    $this->boats = WorkorderPeer::getActiveByBoatLetter($filter_name);
    $this->filter_name = $filter_name;
    $this->filter_boat = $filter_boat;

    return sfView::SUCCESS;
  }

  public function executeWorkorderitemselect(sfWebRequest $request)
  {
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    if (!$workorder) $this->redirect('timelogs/workorderselect');

    $this->page = $request->getParameter('page', 1);
    $parent_id = $request->getParameter('parent_id');
    if ($parent_id && $parent = WorkorderItemPeer::retrieveByPk($parent_id))
    {
      if ($children = $parent->getChildren())
      {
        $this->path = $parent->getPath();
        $this->parent = $parent;
        $this->children = $children;
      }
      else
      {
        $this->redirect('timelogs/details?id='.$workorder->getId().'&item='.$parent->getId());
      }
    } 
    else
    {
      $this->children = $workorder->getRootItem()->getChildren();
    }

    $this->workorder = $workorder;

    return sfView::SUCCESS;
  }

  public function executeDetails(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->add(LabourTypePeer::ACTIVE, true);
    $c->addAscendingOrderByColumn(LabourTypePeer::NAME);
    $this->labours = LabourTypePeer::doSelect($c);
    $this->workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->item = WorkorderItemPeer::retrieveByPk($request->getParameter('item'));

    if ($request->getMethod() == sfRequest::POST)
    {
      //DO ERROR CHECKING
      $result = true;

      //check for labour type
      $labour_id = $request->getParameter('labour_type_id');
      if (!$labour_id || (!(LabourTypePeer::retrieveByPk($labour_id))))
      {
        $request->setParameter('labour_error', true);
        $result = false;
      }

      //check for proper hours
      $hrs = $request->getParameter('timelog_time');
      if (preg_match('/^[012]?[0-9](\.[0-9]+)?$/', trim($hrs)))
      {
        //ok
      }
      else if (preg_match('/^[012]?[0-9]?:[0123456][0-9]$/', trim($hrs)))
      {
        //ok
      }
      else
      {
        $request->setParameter('time_error', true);
        $result = false;
      }

      //save the timelog
      if ($result)
      {
        $timelog = new Timelog();
        $timelog->setEmployeeId($this->getUser()->getEmployee()->getId());
        $timelog->setWorkorderItemId($this->item->getId());
        $timelog->setLabourTypeId($request->getParameter('labour_type_id'));
        $timelog->setEmployeeNotes($request->getParameter('notes'));
        $timelog->setTaxableHst(($this->workorder->getHstExempt() ? 0 : sfConfig::get('app_hst_rate')));
        $timelog->setTaxablePst(($this->workorder->getPstExempt() ? 0 : sfConfig::get('app_pst_rate')));
        $timelog->setTaxableGst(($this->workorder->getGstExempt() ? 0 : sfConfig::get('app_gst_rate')));

        $date = $request->getParameter('date');
        $timelog->setEndTime(($date ? strtotime($date) : time()));

        $hours = $request->getParameter('timelog_time');
        if (strpos($hours, ':') !== false)
        {
          $hours = explode(':',$hours);
          $hours = $hours[0] + ($hours[1] / 60);
        }
        if ($this->getUser()->hasCredential('timelogs_approve'))
        {
          $timelog->setApproved(true);
        }
        $timelog->setPayrollHours($hours);
        $timelog->setBillableHours($hours);
        $timelog->calculateCost();
        $timelog->save();

        $this->labour_type = $request->getParameter('labour_type_id');

        return 'Done';
      }
    }

    return sfView::SUCCESS;
  }

  public function executeNonbillable(sfWebRequest $request)
  {

    $this->labours = array('General', 'Vacation', 'Lunch', 'Break', 'Sick');

    if ($request->getMethod() == sfRequest::POST)
    {
      //DO ERROR CHECKING
      $result = true;

      //check for labour type
      $labour_id = $request->getParameter('labour_type_id');
      if (!$labour_id || (!in_array($labour_id, $this->labours)))
      {
        $request->setParameter('labour_error', true);
        $result = false;
      }

      //check for proper hours
      $hrs = $request->getParameter('timelog_time');
      if (preg_match('/^[012]?[0-9](\.[0-9]+)?$/', trim($hrs)))
      {
        //ok
      }
      else if (preg_match('/^[012]?[0-9]?:[0123456][0-9]$/', trim($hrs)))
      {
        //ok
      }
      else
      {
        $request->setParameter('time_error', true);
        $result = false;
      }

      //save the timelog
      if ($result)
      {
        $timelog = new Timelog();
        $timelog->setEmployeeId($this->getUser()->getEmployee()->getId());
        //$timelog->setType($labour_id);
        $timelog->setEmployeeNotes($request->getParameter('notes'));

        $date = $request->getParameter('date');
        $timelog->setEndTime(($date ? strtotime($date) : time()));

        $hours = $request->getParameter('timelog_time');
        if (strpos($hours, ':') !== false)
        {
          $hours = explode(':',$hours);
          $hours = $hours[0] + ($hours[1] / 60);
        }
        if ($this->getUser()->hasCredential('timelogs_approve'))
        {
          $timelog->setApproved(true);
        }
        $timelog->setPayrollHours($hours);
        $timelog->save();

        $this->labour_type = $request->getParameter('labour_type_id');

        return 'Done';
      }
    }

    return sfView::SUCCESS;
  }


}
