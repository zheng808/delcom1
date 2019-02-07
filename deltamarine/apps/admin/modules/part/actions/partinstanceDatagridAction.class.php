<?php

class partinstanceDatagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());

    $c = new Criteria();

    if ($request->hasParameter('id') && ($part = PartPeer::retrieveByPk($request->getParameter('id'))))
    {
      $c->add(PartPeer::ID, $part->getId());
    }
    else if ($request->hasParameter('workorder_id') && ($workorder = WorkorderPeer::retrieveByPk($request->getParameter('workorder_id'))))
    {
      $c->addJoin(WorkorderItemPeer::WORKORDER_ID, WorkorderPeer::ID);
      $c->add(WorkorderPeer::ID, $workorder->getId());
      $c->addJoin(PartInstancePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
    }
    else if ($request->getParameter('onhold'))
    {
      $c->add(PartPeer::ACTIVE, true);
      $c->add(PartInstancePeer::ALLOCATED, true);
      $c->add(PartInstancePeer::DELIVERED, false);
      $c->addJoin(PartInstancePeer::ID, CustomerOrderItemPeer::PART_INSTANCE_ID, Criteria::LEFT_JOIN);
      $c1 = $c->getNewCriterion(PartInstancePeer::WORKORDER_ITEM_ID, null, Criteria::ISNOTNULL);
      $c2 = $c->getNewCriterion(CustomerOrderItemPeer::ID, null, Criteria::ISNOTNULL);
      $c1->addOr($c2);
      $c->addAnd($c1);
    }

    //copy for getting total count later
    $c2 = clone $c;
    $c2->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID, ($workorder ? Criteria::LEFT_JOIN : null));
    $c2->addJoin(PartVariantPeer::PART_ID, PartPeer::ID, ($workorder ? Criteria::LEFT_JOIN : null));
    

    //sort
    if ($request->getParameter('sort', 'id') == 'status')
    {
      if ($request->getParameter('dir', 'DESC') == 'ASC')
      {
        $c->addAscendingOrderByColumn(PartInstancePeer::ALLOCATED);
        $c->addAscendingOrderByColumn(PartInstancePeer::DELIVERED);
      }
      else
      {
        $c->addDescendingOrderByColumn(PartInstancePeer::DELIVERED);
        $c->addDescendingOrderByColumn(PartInstancePeer::ALLOCATED);
      }
    }
    else
    {
      switch ($request->getParameter('sort', 'id'))
      {
      case 'id':
        $col = PartInstancePeer::ID;
        break;
      case 'date':
        $col = PartInstancePeer::DATE_USED;
        break;
      case 'quantity':
        $col = PartInstancePeer::QUANTITY;
        break;
      case 'task':
        $col = WorkorderItemPeer::LEFT_COL;
        break;
      case 'sku':
        $col = PartVariantPeer::INTERNAL_SKU;
        break;
      case 'name':
        $col = PartPeer::NAME;
        break;
      }
      ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                     :  $c->addDescendingOrderByColumn($col));
    }

    //paging
    if ($request->getParameter('limit'))
    {
      $c->setLimit($request->getParameter('limit'));
    }
    if ($request->getParameter('start'))
    {
      $c->setOffset($request->getParameter('start'));
    }

    $instances = PartInstancePeer::doSelectForListing($c);

    //generate data array
    $instancesarray = array();
    foreach ($instances AS $item)
    {
      $task = '';
      if ($item instanceof CustomerOrderItem)
      {
        $instance = $item->getPartInstance();
        $coid = $item->getCustomerOrderId();
        $description = "Customer Sale #".$coid." (".$item->getCustomerOrder()->getCustomer()->generateName().")";
        $description_url = $this->getController()->genUrl('sale/view?id='.$coid);
      }
      else if ($item instanceof CustomerReturnItem)
      {
        $instance = $item->getPartInstance();
        $coid = $item->getCustomerReturn()->getCustomerOrderId();
        $description = "Customer Sale #".$coid." (".$item->getCustomerReturn()->getCustomerOrder()->getCustomer()->generateName().")";
        $description_url = $this->getController()->genUrl('sale/view?id='.$coid);
      }
      else if ($item->getWorkorderItem())
      {
        $instance = $item;
        $woid = $item->getWorkorderItem()->getWorkorderId();
        $description = "Work Order #".$woid." (".$item->getWorkorderItem()->getWorkorder()->getCustomer()->generateName().")";
        $description_url = $this->getController()->genUrl('work_order/view?id='.$woid);
        $task = $item->getWorkorderItem()->getLabel();
      }
      else
      {
        $instance = $item;
        $description = ($item->getIsInventoryAdjustment() ? 'Inventory Adjustment' : 'Unknown Item');
        $description_url = '';
      }
      if ($item instanceof CustomerReturnItem)
      {
        $status = '<span style="color:orange">Returned</span>';
        $base_status = 'Returned';
      }
      else if ($instance->getDelivered())
      {
        $status = '<span style="color:green">Utilized</span>';
        $base_status = 'Utilized';
      }
      else if ($instance->getSupplierOrderItem())
      {
        $order = $instance->getSupplierOrderItem()->getSupplierOrder();
        $status = '<span style="font-weight: bold; color: orange;">Special Order from '.$order->getSupplier()->getName().'</span>';
        $base_status = 'On Special Order';
      }
      else if ($instance->getAllocated())
      {
        $status = '<span style="font-weight: bold; color: red;">ON HOLD</span>';
        $base_status = 'On Hold';
      }
      else
      {
        $status = '<span style="color: #aaa;">Estimate</span>';
        $base_status = 'Estimate (not used)';
      }

      //handle one-off parts
      if ($instance->getCustomName())
      {
        $partid = null;
        $sku = '<span style="color: #888; font-style: italic;">[One-Off]</span>';
        $manufacturer_sku = '';
      }
      else
      {
        $partid = $instance->getPartVariant()->getPartId();
        $sku = $instance->getPartVariant()->getInternalSku();
        $manufacturer_sku = $instance->getPartVariant()->getManufacturerSku();
      }
      $instancesarray[] = array('id' => $instance->getId(),
                                'description' => $description,
                                'description_url' => $description_url,
                                'status' => $status,
                                'base_status' => $base_status,
                                'quantity' => $instance->outputQuantity(),
                                'name' => $name = $instance->__toString(),
                                'part_id' => $partid,
                                'sku' => $sku,
                                'manufacturer_sku' => $manufacturer_sku,
                                'date' => $instance->getDateUsed('m/d/Y'),
                                'task' => $task,
                                'custom' => ($instance->getCustomName() ? true : false)
                               );
    }

    //count the totals and add stuff to the final array
    $count_all = PartInstancePeer::doCount($c2);
    $dataarray = array('totalCount' => $count_all, 'instances' => $instancesarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }

}
