<?php

/**
 * parts actions.
 *
 * @package    deltamarine
 * @subpackage parts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class partsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('parts/workorderselect');
  }

  public function executeWorkorderselect(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->add(WorkorderPeer::STATUS, 'In Progress');
    $c->addAscendingOrderByColumn(WorkorderPeer::STARTED_ON);

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

  public function executeSearch(sfWebRequest $request)
  {
    $this->workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->item = WorkorderItemPeer::retrieveByPk($request->getParameter('item'));

    $do_search = false;
    $results = false;
    $c = new Criteria();
    $exact_c = new Criteria();

    $c->add(PartPeer::ACTIVE, true);
    $exact_c->add(PartPeer::ACTIVE, true);
    $skip_exact = false;
    if ($request->hasParameter('sku'))
    {
      if (trim($request->getParameter('sku')) == '')
      {
        $request->getParameterHolder()->set('sku_error', 1);
      }
      else
      {
        $do_search = true;
        $c->add(PartVariantPeer::INTERNAL_SKU, '%'.$request->getParameter('sku').'%', Criteria::LIKE);
        $exact_c->add(PartVariantPeer::INTERNAL_SKU, $request->getParameter('sku'), Criteria::LIKE);
      }
    }
    else if ($request->hasParameter('name'))
    {
      if (trim($request->getParameter('name')) == '')
      {
        $request->getParameterHolder()->set('name_error', 1);
      }
      else
      {
        $do_search = true;
        $c->add(PartPeer::NAME, '%'.$request->getParameter('name').'%', Criteria::LIKE);
        $skip_exact = true;
      } 
    }
    else if ($this->hasRequestParameter('code'))
    {
      //check for parts
      PartPeer::addBarcodeSearch($this->getRequestParameter('code'), $exact_c);
      $do_search = true;
    }

    if ($do_search)
    {
      //STEP 1: try the exact match
      if (!$skip_exact && ($parts = PartPeer::doSelectJoinPartVariants($exact_c)))
      {
        if (count($parts) == 1)
        {
          $part_id = $parts[0]->getId();
          $this->redirect('parts/details?id='.$this->workorder->getId().'&item='.$this->item->getId().'&part='.$part_id);
        }
        else
        {
          $this->page = $request->getParameter('page', 1);
          $this->parts = $parts;
        }
      }
      else if (!$this->hasRequestParameter('code') && ($parts = PartPeer::doSelectJoinPartVariants($c)))
      {
        $this->page = $request->getParameter('page', 1);
        $this->parts = $parts;
      }
      else
      {
        $request->setParameter('notfound_error', 1);
      }
    }

    $this->getResponse()->addJavascript('barcodes_base');
    return sfView::SUCCESS;
  }

  public function executeDetails(sfWebRequest $request)
  {
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $item = WorkorderItemPeer::retrieveByPk($request->getParameter('item'));
    $part = PartPeer::retrieveByPk($request->getParameter('part'));

    if ($request->getMethod() == sfRequest::POST && $request->hasParameter('addpart'))
    {
      $valid = true;

      //check for valid quantity
      $quantity = $request->getParameter('quantity');
      $qtyfloat = (float) $quantity;
      if ($quantity == 0)
      {
        $request->getParameterHolder()->set('qtyerr', 'Invalid Quantity Entered!');
        $valid = false;
      }
      else if (!is_numeric($quantity))
      {
        $request->getParameterHolder()->set('qtyerr', 'Invalid Quantity Entered!');
        $valid = false;
      }
      else if ($qtyfloat < 0)
      {
        $request->getParameterHolder()->set('qtyerr', 'Negative Values not allowed!');
        $valid = false;
      }
      /* disabled by request, January 2011 
      else if (!$part->getDefaultVariant()->getUnits() && (round($qtyfloat) != $qtyfloat))
      {
        $request->getParameterHolder()->set('qtyerr', 'Cannot use decimal quantity for non-bulk items');
        $valid = false;
      }
      */
      else if ($qtyfloat > $part->getDefaultVariant()->getCurrentAvailable())
      {
        $request->getParameterHolder()->set('qtyerr', 'Not enough available items in inventory! Some items might be on hold. Check with parts manager.');
        $valid = false;
      }

      if ($valid)
      {
        //add the item
        $inst = new PartInstance();
        $inst->setQuantity($qtyfloat);
        $inst->setWorkorderItem($item);
        $inst->setPartVariant($part->getDefaultVariant());
        $inst->setAddedBy($this->getUser()->getEmployee()->getId());
        $inst->copyDefaults($workorder->getHstExempt(), $workorder->getPstExempt(), $workorder->getGstExempt()); //copies pricing from variant
        $inst->save();
        $inst->allocate();
        $inst->deliver();

        $this->workorder = $workorder;
        $this->item = $item;
        return 'Done';
      } 
    }

    $this->workorder = $workorder;
    $this->item = $item;
    $this->part = $part;

    return sfView::SUCCESS;
  }

}
