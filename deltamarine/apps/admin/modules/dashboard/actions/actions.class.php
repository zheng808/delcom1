<?php

/**
 * dashboard actions.
 *
 * @package    deltamarine
 * @subpackage dashboard
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class dashboardActions extends sfActions
{

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    sfConfig::set('app_selected_menu', 'dashboard');
    sfLoader::loadHelpers(array('Url'));

    $dashboard_notes = array();

    // retrive special order parts that are ready for customers to pick up
    /* todo filter customer orders by ready to pick up!!
    $special_order_items_quant = CustomerOrderItemPeer::doCountReadyToPickUp();
    if ( $special_order_items_quant > 0 )
      $dashboard_notes[] = array( 
        'text' => sprintf("There are %d special order part%s that are ready for customers to pick up.", $special_order_items_quant, ($special_order_items_quant > 1 ? 's' : '')),
        'link' => url_for('dashboard/specialOrderItems')
      );
*/
    // retrive part variants that are below minimum stock levels
    $min_part_variants_quant = count(PartVariantPeer::doSelectBelowMin());
    if ( $min_part_variants_quant > 0 )
    {
      $dashboard_notes[] = array(
        'text' => sprintf("There are %d part%s that are below minimum stock levels.", $min_part_variants_quant, ($min_part_variants_quant > 1 ? 's' : '')),
        'link' => url_for('part/index?filter=belowmin')
      );
  }

    // retrive parts that are on hold
    $hold_part_variants_quant = PartVariantPeer::doCountOnHold();
    if ( $hold_part_variants_quant > 0 )
    {
      $dashboard_notes[] = array(
        'text' => sprintf("There are %d part%s that are on hold.", $hold_part_variants_quant, ($hold_part_variants_quant > 1 ? 's' : '')),
        'link' => url_for('part/index?filter=onhold')
      );
    }

    // retrieve duplicate part SKUs
    $dupe_skus = PartPeer::doCountDupeSkus();
    if ($dupe_skus > 0)
    {
      $dashboard_notes[] = array(
        'text' => sprintf("There are %d SKU%s used more than once.", $dupe_skus, ($dupe_skus > 1 ? 's' : '')),
        'link' => url_for('part/index?filter=ondupe')
      );
    }

/* TODO, add filters to supplier orders and enable
    // retrive supplier orders that are not sent
    $not_sent_supplier_orders_quant = new Criteria();
    $not_sent_supplier_orders_quant->add(SupplierOrderPeer::SENT, 0);
    $not_sent_supplier_orders_quant = SupplierOrderPeer::doCount($not_sent_supplier_orders_quant);
    if ( $not_sent_supplier_orders_quant > 0 )
      $dashboard_notes[] = array(
        'text' => sprintf("There are %d supplier order%s that are not sent.", $not_sent_supplier_orders_quant, ($not_sent_supplier_orders_quant > 1 ? 's' : '')),
        'link' => url_for('dashboard/notSentSupplierOrders')
      );

    // retrive supplier orders that have not yet been shipped
    $not_shipped_supplier_orders_quant = new Criteria();
    $not_shipped_supplier_orders_quant->add(SupplierOrderPeer::SENT, 1);
    $not_shipped_supplier_orders_quant->add(SupplierOrderPeer::RECEIVED_SOME, 1);
    $not_shipped_supplier_orders_quant->add(SupplierOrderPeer::RECEIVED_ALL, 0);
    $not_shipped_supplier_orders_quant = SupplierOrderPeer::doCount($not_shipped_supplier_orders_quant);
    if ( $not_shipped_supplier_orders_quant > 0 )
      $dashboard_notes[] = array(
        'text' => sprintf("There are %d supplier order%s that have not yet been shipped fully.", $not_shipped_supplier_orders_quant, ($not_shipped_supplier_orders_quant > 1 ? 's' : '')),
        'link' => url_for('dashboard/notShippedSupplierOrders')
      );
*/
    $this->dashboard_notes = $dashboard_notes;
  }

  public function executeSpecialOrderItems()
  {
    return $this->renderText($this->loadDatagridReadyToPickUp());
  }

  public function executeMissPartVariants()
  {
    return $this->renderText($this->loadDatagridBelowMin());
  }

  public function executeNotSentSupplierOrders()
  {
    $c = new Criteria();
    $c->add(SupplierOrderPeer::SENT, 0);
    return $this->renderText($this->loadDatagridSupplierOrders($c));
  }

  public function executeNotShippedSupplierOrders()
  {
    $c = new Criteria();
    $c->add(SupplierOrderPeer::SENT, 1);
    $c->add(SupplierOrderPeer::RECEIVED_SOME, 1);
    $c->add(SupplierOrderPeer::RECEIVED_ALL, 0);
    return $this->renderText($this->loadDatagridSupplierOrders($c));
  }

  public function executeNotSentCustomerOrders()
  {
    $c = new Criteria();
    $c->add(CustomerOrderPeer::FINALIZED, 1);
    $c->add(CustomerOrderPeer::SENT_SOME, 0);
    return $this->renderText($this->loadDatagridCustomerOrders($c));
  }

  public function executeNotShippedCustomerOrders()
  {
    $c = new Criteria();
    $c->add(CustomerOrderPeer::SENT_SOME, 1);
    $c->add(CustomerOrderPeer::SENT_ALL, 0);
    return $this->renderText($this->loadDatagridCustomerOrders($c));
  }
 
  private function loadDatagridReadyToPickUp()
  {
    $grid = new sfDatagridPropel('notification_datagrid', 'CustomerOrderItem');
    $grid->setModuleAction('dashboard/specialOrderItems');
    $grid->setColumns(array('supplier_order_id' => 'Order ID',
                            'part_variant_description' => 'Part Description',
                            'quantity' => 'Quantity',
                            'customer' => 'Customer'
                            ));
    $grid->setColumnsSorting(array('supplier_order_id' => 'CustomerOrderItemPeer::CUSTOMER_ORDER_ID',
                                   'part_variant_description' => 'nosort',
                                   'quantity' => 'nosort',
                                   'customer' => 'nosort'
                            ));
    //$grid->setColumnsOptions(array('supplier_name' => array('class' => 'left')));
    $grid->setDefaultSortingColumn('supplier_order_id', 'asc');
    $grid->setRowLimit(30);
    $grid->renderSearch(false);
    $grid->renderPager(true);
    //$grid->setRowAction('supplier_order/view?id=', 'id');

    sfLoader::loadHelpers(array('Javascript', 'Url', 'Tag', 'Form'));

    $pager = $grid->prepare('doSelectReadyToPickUp', 'doCountReadyToPickUp');

    $values = array();
    foreach ($pager->getResults() AS $customer_order_item)
    {
      $part = $customer_order_item->getPartInstance()->getPartVariant()->getPart();
      $description = $part->getName();
      $description = link_to($description, 'part/view?id='.$part->getId());
      if ($part->getIsMultisku())
        $description .= ' ('.$customer_order_item->getPartInstance()->getPartVariant()->outputOptionValuesList().')';
      $values[] = array(
        link_to($customer_order_item->getCustomerOrderId(), 'sale/view?id='.$customer_order_item->getCustomerOrderId()),
        $description,
        $customer_order_item->getPartInstance()->getQuantity(),
        link_to($customer_order_item->getCustomerOrder()->getCustomer(), 'customer/view?id='.$customer_order_item->getCustomerOrder()->getCustomer()->getId())
      );
    }

    return $grid->getContent($values, array('odd', null));
  }

  private function loadDatagridBelowMin()
  {
    $grid = new sfDatagridPropel('notification_datagrid', 'PartVariant');
    $grid->setModuleAction('dashboard/missPartVariants');
    $grid->setColumns(array('supplier_order_id' => 'ID',
                            'part_variant_description' => 'Part Description',
                            'min_quantity' => 'Min On Hand',
                            'on_hand' => 'Current On Hand',
                            'max_quantity' => 'Max On Hand'
                            ));
    $grid->setColumnsSorting(array('supplier_order_id' => 'PartVariantPeer::ID',
                                   'part_variant_description' => 'nosort',
                                   'min_quantity' => 'nosort',
                                   'on_hand' => 'nosort',
                                   'max_quantity' => 'nosort'
                            ));
    //$grid->setColumnsOptions(array('supplier_name' => array('class' => 'left')));
    $grid->setDefaultSortingColumn('supplier_order_id', 'asc');
    $grid->setRowLimit(30);
    $grid->renderSearch(false);
    $grid->renderPager(true);
    //$grid->setRowAction('supplier_order/view?id=', 'id');

    sfLoader::loadHelpers(array('Javascript', 'Url', 'Tag', 'Form'));

    $pager = $grid->prepare('doSelectBelowMin', 'doCountBelowMin');

    $values = array();
    foreach ($pager->getResults() AS $part_variant)
    {
      $part = $part_variant->getPart();
      $description = $part->getName();
      $description = link_to($description, 'part/view?id='.$part->getId());
      if ($part->getIsMultisku())
        $description .= ' ('.$part_variant->outputOptionValuesList().')';
      $values[] = array(
        $part_variant->getId(),
        $description,
        $part_variant->getMinimumOnHand(),
        $part_variant->getCurrentOnHand(),
        $part_variant->getMaximumOnHand() ? $part_variant->getMaximumOnHand() : '-'
      );
    }

    return $grid->getContent($values, array('odd', null));
  }

  private function loadDatagridSupplierOrders($c = null)
  {
    if ( !$c )
      $c = new Criteria();
    $grid = new sfDatagridPropel('notification_datagrid', 'SupplierOrder', $c);
    $grid->setModuleAction('dashboard/'.sfContext::getInstance()->getActionName());
    $grid->setColumns(array('supplier_order_id' => 'ID',
                            'supplier' => 'Supplier Name'
                            ));
    $grid->setColumnsSorting(array('supplier_order_id' => 'SupplierOrderPeer::ID',
                                   'supplier' => 'nosort'
                            ));
    //$grid->setColumnsOptions(array('supplier_name' => array('class' => 'left')));
    $grid->setDefaultSortingColumn('supplier_order_id', 'asc');
    $grid->setRowLimit(30);
    $grid->renderSearch(false);
    $grid->renderPager(true);
    $grid->setRowAction('supplier_order/view?id=', 'supplier_order_id');

    sfLoader::loadHelpers(array('Javascript', 'Url', 'Tag', 'Form'));

    $pager = $grid->prepare('doSelectForListing', 'doCount');

    $values = array();
    foreach ($pager->getResults() AS $supplier_order)
    {
      $values[] = array(
        $supplier_order->getId(),
        $supplier_order->getSupplier()
      );
    }

    return $grid->getContent($values, array('odd', null));
  }

  private function loadDatagridCustomerOrders($c = null)
  {
    if ( !$c )
      $c = new Criteria();
    $grid = new sfDatagridPropel('notification_datagrid', 'CustomerOrder', $c);
    $grid->setModuleAction('dashboard/'.sfContext::getInstance()->getActionName());
    $grid->setColumns(array('supplier_order_id' => 'ID',
                            'customer' => 'Supplier Name'
                            ));
    $grid->setColumnsSorting(array('supplier_order_id' => 'CustomerOrderPeer::ID',
                                   'customer' => 'nosort'
                            ));
    //$grid->setColumnsOptions(array('supplier_name' => array('class' => 'left')));
    $grid->setDefaultSortingColumn('supplier_order_id', 'asc');
    $grid->setRowLimit(30);
    $grid->renderSearch(false);
    $grid->renderPager(true);
    $grid->setRowAction('sale/view?id=', 'supplier_order_id');

    sfLoader::loadHelpers(array('Javascript', 'Url', 'Tag', 'Form'));

    // TODO retrive by one query but fix CustomerOrderPeer::doSelectForListing first
    $pager = $grid->prepare('doSelect', 'doCount');

    $values = array();
    foreach ($pager->getResults() AS $customer_order)
    {
      $values[] = array(
        $customer_order->getId(),
        $customer_order->getCustomer()
      );
    }

    return $grid->getContent($values, array('odd', null));
  }
  
  function show_notification(sfEvent $event, $result)
  {
        return;
  }
  //$dispatcher->connect('dashboard_actions.filter_result', 'show_notification');


  function executeError404()
  {
    return sfView::SUCCESS;
  }

  public function executeFireDate(sfWebRequest $request){
    $NewDate=Date('y:m:d', strtotime('-335 days'));
    $c = new Criteria();
    $c2 = new Criteria();
    $c->add(CustomerBoatPeer::FIRE_DATE, null, Criteria::ISNOTNULL);
    $c->add(CustomerBoatPeer::FIRE_DATE, $NewDate, Criteria::LESS_EQUAL);
  
    

    ($request->getParameter('dir', 'DESC') == 'ASC' ?  $c->addAscendingOrderByColumn(CustomerBoatPeer::FIRE_DATE)
    :  $c->addDescendingOrderByColumn(CustomerBoatPeer::FIRE_DATE));

    $boats = CustomerBoatPeer::doSelectForListing($c);
    //generate JSON output
    $boatarray = array();
    foreach ($boats AS $boat)
    {
      $c2->add(CustomerPeer::ID, $boat['data']->getCustomerId());
      $c2->addJoin(CustomerPeer::WF_CRM_ID, wfCRMPeer::ID);
      //
      
      $customers = CustomerPeer::doSelectForListing($c2);
      foreach($customers AS $customer){
          $boatarray[] = array('id'    => $boat['data']->getId(),
          'name'  => $boat['data']->getName(), 
          'make'  => $boat['data']->getMake(),
          'model' => $boat['data']->getModel(),
          'fire_date' =>$boat['data']->getFire_Date(),
          'customer_name' => $customer->getName(),
          'lastworkorder' => ($boat['latest'] ? date('m/d/Y', $boat['latest']) : 'Never')
        );
      }
    }


    $this->renderText(json_encode($boatarray));

    return sfView::NONE;
  }
}
