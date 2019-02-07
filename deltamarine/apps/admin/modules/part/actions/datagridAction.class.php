<?php

class datagridAction extends sfAction
{

  public function execute($request)
  {
    //$this->forward404Unless($request->isXmlHttpRequest());    

    $c = new Criteria();
    $cats_c = new Criteria();
    $show_supplier_info = false;
    $show_order_info = false;
    $show_pricing = (bool) ($request->getParameter('show_pricing'));

    //match by part id
    if ($request->getParameter('part_id'))
    {
      $c->add(PartVariantPeer::PART_ID, $request->getParameter('part_id'));
    }
    //show inactive items (false by default)
    if ($request->getParameter('show_inactive') == '1')
    {
      //include all parts     
    }
    else if ($request->getParameter('show_inactive') == '2')
    {
      //show just inactive
       $c->add(PartPeer::ACTIVE, false);
    }
    else
    {
      $c->add(PartPeer::ACTIVE, true);
    }

    //match by part_variant_id (used to add part to sale after error from part screen)
    if ($request->getParameter('part_variant_id'))
    {
      $c->add(PartVariantPeer::ID, $request->getParameter('part_variant_id'));
    }
    //filter by supplier id
    if ($request->getParameter('supplier_id'))
    {
      $c->addJoin(PartVariantPeer::ID, PartSupplierPeer::PART_VARIANT_ID);
      $c->add(PartSupplierPeer::SUPPLIER_ID, $request->getParameter('supplier_id'));
      $show_supplier_info = $request->getParameter('supplier_info', 0);
    }
    if ($request->getParameter('supplier'))
    {
      $c->addJoin(PartVariantPeer::ID, PartSupplierPeer::PART_VARIANT_ID);
      $c->addJoin(PartSupplierPeer::SUPPLIER_ID, SupplierPeer::ID);
      $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
      $c->add(wfCRMPeer::DEPARTMENT_NAME, '%'.$request->getParameter('supplier').'%', Criteria::LIKE);
      $show_supplier_info = true;
    }    
    //filter by manufacturer id
    if ($request->getParameter('manufacturer_id'))
    {
      $c->add(PartPeer::MANUFACTURER_ID, $request->getParameter('manufacturer_id'));
    }
    //filter by category
    if ($cat = PartCategoryPeer::retrieveByPk($request->getParameter('category_id')))
    {
      if ($request->getParameter('category_inclusive', true))
      {
        $c->add(PartCategoryPeer::LEFT_COL, $cat->getLeftValue(), Criteria::GREATER_EQUAL);
        $c->add(PartCategoryPeer::RIGHT_COL, $cat->getRightValue(), Criteria::LESS_EQUAL);
        $c->addJoin(PartPeer::PART_CATEGORY_ID, PartCategoryPeer::ID);
        $cats_c->add(PartCategoryPeer::LEFT_COL, $cat->getLeftValue(), Criteria::GREATER_EQUAL);
        $cats_c->add(PartCategoryPeer::RIGHT_COL, $cat->getRightValue(), Criteria::LESS_EQUAL);
      }
      else
      {
        $c->add(PartPeer::PART_CATEGORY_ID, $cat->getId());
        $cats_c->add(PartPeer::PART_CATEGORY_ID, $cat->getId());
      }
    }
    //filter by name
    if ($request->getParameter('name'))
    {
      $q = $request->getParameter('name');
      if (strpos($q,'*') !== false)
      {
        $c->add(PartPeer::NAME, str_replace('*','_%', $q), Criteria::LIKE);
      }
      else
      {
        $c->add(PartPeer::NAME, '%'.$q.'%', Criteria::LIKE);
      }
    }
    if ($request->getParameter('location'))
    {
      $c->add(PartVariantPeer::LOCATION, $request->getParameter('location').'%', Criteria::LIKE);
    }    
    //filter by sku (match beginning only)
    if ($request->getParameter('sku'))
    {
      $q = $request->getParameter('sku');
      if (strpos($q,'*') !== false)
      {
        $c1 = $c->getNewCriterion(PartVariantPeer::INTERNAL_SKU, str_replace('*','_%', $q), Criteria::LIKE);
        $c2 = $c->getNewCriterion(PartVariantPeer::MANUFACTURER_SKU, str_replace('*','_%', $q), Criteria::LIKE);
        $c1->addOr($c2);
        $c->addAnd($c1);
      }
      else
      {      
        $c1 = $c->getNewCriterion(PartVariantPeer::INTERNAL_SKU, $q.'%', Criteria::LIKE);
        $c2 = $c->getNewCriterion(PartVariantPeer::MANUFACTURER_SKU, $q.'%', Criteria::LIKE);
        $c1->addOr($c2);
        $c->addAnd($c1);
      }
    }
    //filter by inventory status
    if ($request->getParameter('inv'))
    {
      $setting = $request->getParameter('inv');
      if ($setting == 'Below Minimum Level' || $setting == 'under')
      {
        $show_order_info = true;
        $c->add(PartVariantPeer::CURRENT_ON_HAND, PartVariantPeer::CURRENT_ON_HAND.'<'.PartVariantPeer::MINIMUM_ON_HAND, Criteria::CUSTOM);
      }
      else if ($setting == 'Above Maximum Level' || $setting == 'over')
      {
        $c->add(PartVariantPeer::CURRENT_ON_HAND, PartVariantPeer::CURRENT_ON_HAND.'>'.PartVariantPeer::MAXIMUM_ON_HAND, Criteria::CUSTOM);
      }
      else if ($setting == 'Within Max/Min Levels' || $setting == 'mid')
      {
        $c_a = $c->getNewCriterion(PartVariantPeer::CURRENT_ON_HAND, 
                                   PartVariantPeer::CURRENT_ON_HAND.'>'.PartVariantPeer::MINIMUM_ON_HAND, 
                                   Criteria::CUSTOM);
        $c_b = $c->getNewCriterion(PartVariantPeer::CURRENT_ON_HAND, 
                                   PartVariantPeer::CURRENT_ON_HAND.'<'.PartVariantPeer::MAXIMUM_ON_HAND, 
                                   Criteria::CUSTOM);
        $c_a->addAnd($c_b);
        $c->add($c_a);
      }
    }
    //filter by stock status
    if ($request->getParameter('stock'))
    {
      $setting = $request->getParameter('stock');
      if ($stting == 'In Stock' || $setting == 'in')
      {
        $c_a = $c->getNewCriterion(PartVariantPeer::CURRENT_ON_HAND, 0, Criteria::GREATER_THAN);
        $c->addAnd($c_a);
      }
      else if ($setting == 'Out of Stock' || $setting == 'out')
      {
        $c_a = $c->getNewCriterion(PartVariantPeer::CURRENT_ON_HAND, 0);
        $c_b = $c->getNewCriterion(PartVariantPeer::MINIMUM_ON_HAND, 0, Criteria::GREATER_THAN);
        $c->addAnd($c_a);
        $c->addAnd($c_b);
      }
    }
    //search by barcode (used in barcode scanner listener lookup)
    if ($request->getParameter('code'))
    {
      PartPeer::addBarcodeSearch($request->getParameter('code'), $c);
      $c->setDistinct(true);
    }

    //copy for getting total count later
    $c2 = clone $c;

    //sort
    switch ($request->getParameter('sort', 'name'))
    {
    case 'sku':
      $col = PartVariantPeer::INTERNAL_SKU;
      break;
    case 'quantity':
      $col = PartVariantPeer::CURRENT_ON_HAND;
      break;
    case 'min_quantity':
      $col = PartVariantPeer::MINIMUM_ON_HAND;
      break;
    case 'category_path':
      $c->addJoin(PartPeer::PART_CATEGORY_ID, PartCategoryPeer::ID);
      $col = PartCategoryPeer::LEFT_COL;
      break;
    case 'max_quantity':
      $col = PartVariantPeer::MAXIMUM_ON_HAND;
      break;
    case 'origin':
      $col = PartPeer::ORIGIN;
      break;
    case 'location':
      $col = PartVariantPeer::LOCATION;
      break;
    case 'created_at':
      $col = PartVariantPeer::CREATED_AT;
      break;
    default:
      $col = PartPeer::NAME;
      break;
    }
    ($request->getParameter('dir', 'ASC') == 'ASC' ?  $c->addAscendingOrderByColumn($col)
                                                   :  $c->addDescendingOrderByColumn($col));
    if ($col != PartPeer::NAME)
    {
      $c->addAscendingOrderByColumn(PartPeer::NAME);
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

    $parts = PartPeer::doSelectJoinPartVariants($c);

    //get categories
    $categories = PartCategoryPeer::retrieveAllPaths(' / ', $cats_c);

    //get supplier info
    if ($show_supplier_info)
    {
      $var_ids = array();
      foreach ($parts AS $part)
      {
        if ($part->getIsMultisku())
        {
          foreach ($part->getPartVariants() AS $variant)
          {
            $var_ids[] = $variant->getId();
          }
        }
        else
        {
          $var_ids[] = $part->getDefaultVariant()->getId();
        }
      }
      $c3 = new Criteria();
      if ($request->getParameter('supplier_id'))
      {
        $c3->add(PartSupplierPeer::SUPPLIER_ID, $request->getParameter('supplier_id'));
      }
      else if ($request->getParameter('supplier'))
      {
        $c3->addJoin(PartSupplierPeer::SUPPLIER_ID, SupplierPeer::ID);
        $c3->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
        $c3->add(wfCRMPeer::DEPARTMENT_NAME, '%'.$request->getParameter('supplier').'%', Criteria::LIKE);
      }
      $c3->add(PartSupplierPeer::PART_VARIANT_ID, $var_ids, Criteria::IN);
      $partsuppliers = PartSupplierPeer::doSelect($c3);
      unset($var_ids);
      $supplier_info = array();
      foreach ($partsuppliers AS $partsupplier)
      {
        $supplier_info[$partsupplier->getPartVariantId()] = $partsupplier;
      }
    }


    //generate data array
    $partsarray = array();
    foreach ($parts AS $part)
    {
      $category_path = (isset($categories[$part->getPartCategoryId()]) ? $categories[$part->getPartCategoryId()] : 'None');

      if ($part->getIsMultiSku())
      {
        $new_entry = array('part_id' => $part->getId(),
                           'part_variant_id' => '',
                           'name' => $part->getName(),
                           'origin' => $part->getOrigin(),
                           'sku' => '',
                           'onhand' => '',
                           'available' => '',
                           'category_path' => $category_path,
                           'has_serial_number' => '',
                           'track_inventory' => '',
                           'min_quantity' => '',
                           'max_quantity' => '',
                           'location' => '',
                           'supplier' => '',
                           'enviro_levy' => '',
                           'battery_levy' => '',
                           'taxable_pst' => '',
                           'taxable_gst' => '',
                           'unit_cost' => '',
                           'unit_price' => '',
                          );
        if ($show_supplier_info)
        {
          $new_entry['supplier_sku'] = '';
          $new_entry['supplier_notes'] = '';
        }
        $partsarray[] = $new_entry;
        foreach ($part->getPartVariants() AS $variant)
        {
          $new_entry = array('part_id' => $part->getId(),
                             'part_variant_id' => $variant->getId(),
                             'name' => '<span class="subpart">'.$variant->outputOptionValuesList().'</span>',
                             'origin' => $part->getOrigin(),                             
                             'sku' => $variant->getInternalSku(),
                             'units' => (string) $variant->getUnits(),
                             'onhand' => $variant->getQuantity('onhand', false),
                             'available' => $variant->getQuantity('available', false),
                             'category_path' => $category_path,
                             'has_serial_number' => $part->getHasSerialNumber(),
                             'track_inventory' => $part->getDefaultVariant()->getTrackInventory(),
                             'min_quantity' => $variant->getQuantity('minimum', false),
                             'max_quantity' => $variant->getQuantity('maximum', false),
                             'location' => (string) $variant->getLocation(),
                             'enviro_levy' => ($show_pricing && $variant->getEnviroLevy() ? $variant->getEnviroLevy() : 0),
                             'battery_levy' => ($show_pricing && $variant->getBatteryLevy() ? $variant->getBatteryLevy() : 0),
                             'unit_cost' => ($show_pricing ? $variant->calculateUnitCost() : null),
                             'regular_price' => ($show_pricing ? $variant->calculateUnitPrice() : null)
                           );
          if ($show_supplier_info)
          {
            $new_entry['supplier'] = isset($supplier_info[$variant->getId()]) 
                                          ? $supplier_info[$variant->getId()]->getSupplier()->getWfCRM()->getDepartmentName() : 'Unknown';
            $new_entry['supplier_sku'] = isset($supplier_info[$variant->getId()]) 
                                          ? $supplier_info[$variant->getId()]->getSupplierSku() : 'Unknown';
            $new_entry['supplier_notes'] = isset($supplier_info[$variant->getId()]) 
                                          ? nl2br($supplier_info[$variant->getId()]->getNotes()) : 'None';
          }

          $partsarray[] = $new_entry;
        }
      }
      else
      {
        $variant = $part->getDefaultVariant();
        $new_entry = array('part_id' => $part->getId(),
                           'part_variant_id' => $variant->getId(),
                           'name' => $part->getName(),
                           'origin' => $part->getOrigin(),                           
                           'active' => $part->getActive(),
                           'sku' => $variant->getInternalSku(),
                           'manufacturer_sku' => $variant->getManufacturerSku(),
                           'units' => (string) $variant->getUnits(),
                           'onhand' => $variant->getQuantity('onhand', false),
                           'available' => $variant->getQuantity('available', false),
                           'category_path' => $category_path,
                           'has_serial_number' => $part->getHasSerialNumber(),
                           'track_inventory' => $variant->getTrackInventory(),
                           'min_quantity' => $variant->getQuantity('minimum', false),
                           'max_quantity' => $variant->getQuantity('maximum', false),
                           'location' => (string) $variant->getLocation(),
                           'enviro_levy' => ($show_pricing && $variant->getEnviroLevy() ? $variant->getEnviroLevy() : 0),
                           'battery_levy' => ($show_pricing && $variant->getBatteryLevy() ? $variant->getBatteryLevy() : 0),
                           'unit_cost' => ($show_pricing ? $variant->calculateUnitCost() : null),
                           'regular_price' => ($show_pricing ? $variant->calculateUnitPrice() : null),
                           'standard_package_qty' => ($variant->getStandardPackageQty() ? $variant->getQuantity('standard', false) : ''),
                           'stocking_notes' => (string) str_replace("\n",'<br />',$variant->getStockingNotes()),
                           'created_at' => ($variant->getCreatedAt() ? $variant->getCreatedAt('M j, Y') : '')
                         );
        if ($show_supplier_info)
        {
          $new_entry['supplier'] = isset($supplier_info[$variant->getId()]) 
                                          ? $supplier_info[$variant->getId()]->getSupplier()->getWfCRM()->getDepartmentName() : 'Unknown';          
          $new_entry['supplier_sku'] = isset($supplier_info[$variant->getId()]) 
                                        ? $supplier_info[$variant->getId()]->getSupplierSku() : 'Unknown';
          $new_entry['supplier_notes'] = isset($supplier_info[$variant->getId()]) 
                                        ? nl2br($supplier_info[$variant->getId()]->getNotes()) : 'None';
        }
        if ($show_order_info)
        {
          $on_order = $variant->getQuantity('onorder', false);
          if ($on_order > 0)
          {
            $new_entry['on_order'] = $on_order;
            $c = new Criteria();
            $c->add(SupplierOrderItemPeer::PART_VARIANT_ID, $variant->getId());
            $c->addJoin(SupplierOrderItemPeer::SUPPLIER_ORDER_ID, SupplierOrderPeer::ID);
            $c->add(SupplierOrderPeer::SENT, true);
            $c->add(SupplierOrderPeer::RECEIVED_ALL, false);
            $c->addAscendingOrderByColumn(SupplierOrderPeer::DATE_ORDERED);
            $order = SupplierOrderPeer::doSelectOne($c);
            if ($order)
            {
              $new_entry['date_expected'] = $order->getDateExpected() ? $order->getDateExpected('M j') : 'Unknown';
            }
            else
            {
              $new_entry['date_expected'] = 'Not Sent';
            }
          }
        }
        $partsarray[] = $new_entry;
      }
    }

    //count the totals and add stuff to the final array
    $c2->addJoin(PartPeer::ID, PartVariantPeer::PART_ID);
    $count_all = PartVariantPeer::doCount($c2);
    $dataarray = array('totalCount' => $count_all, 'parts' => $partsarray);

    $this->renderText(json_encode($dataarray));

    return sfView::NONE;
  }


}
