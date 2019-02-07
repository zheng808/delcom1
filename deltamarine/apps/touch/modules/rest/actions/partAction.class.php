<?php

class partAction extends restInterfaceAction
{
  //list all or one
  public function get($request)
  {

    //get the workorders for the given period
    $parts = array();
    if ($request->getParameter('id'))
    {
      if ($var = PartVariantPeer::retrieveByPk($request->getParameter('id')))
      {
        $c = new Criteria();
        $c->add(PartPeer::ID, $var->getPartId());
        $parts = PartPeer::doSelectJoinPartVariants($c);
        $count_all = 1;
      } 
      else 
      {
        $count_all = 0;
      }
    }
    else if ($this->hasRequestParameter('code'))
    {
      $parts = PartPeer::searchByBarcode($this->getRequestParameter('code'));
      $count_all = count($parts);
    }
    else
    {
        $exact_c = new Criteria();
        $inexact_c = new Criteria();
        $exact_c->add(PartPeer::ACTIVE, true);
        $inexact_c->add(PartPeer::ACTIVE, true);

        $skusearch = '';
        $namesearch = '';
        $catsearch = '';
        if ($filters = json_decode($this->getRequestParameter('filter')))
        {
          foreach ($filters AS $filter)
          {
            if ($filter->property == 'name') $namesearch = $filter->value;
            if ($filter->property == 'sku') $skusearch = $filter->value;
            if ($filter->property == 'cat') $catsearch = $filter->value;
          } 
        }

        if ($skusearch)
        {
            $do_search = true;
            $inexact_c->add(PartVariantPeer::INTERNAL_SKU, $skusearch.'%', Criteria::LIKE);
            $exact_c->add(PartVariantPeer::INTERNAL_SKU, $skusearch, Criteria::LIKE);
        }
        if ($namesearch)
        {
            $do_search = true;
            $exact_c->add(PartPeer::NAME, $namesearch, Criteria::LIKE);
            $name_parts = explode(' ', $namesearch);
            $first_part = array_shift($name_parts);
            $first_namec = $inexact_c->getNewCriterion(PartPeer::NAME, '%'.$first_part.'%', Criteria::LIKE);
            foreach ($name_parts AS $name_part)
            {
              $this_namec = $inexact_c->getNewCriterion(PartPeer::NAME, '%'.$name_part.'%', Criteria::LIKE);
              $first_namec->addAnd($this_namec);
            }
            $inexact_c->addAnd($first_namec);
        }
        if ($catsearch)
        {
            //filter by part category
            $exact_c->add(PartPeer::PART_CATEGORY_ID, $catsearch);
            $inexact_c->add(PartPeer::PART_CATEGORY_ID, $catsearch);
        }

        $exact_c->addAscendingOrderByColumn(PartPeer::NAME);
        $inexact_c->addAscendingOrderByColumn(PartPeer::NAME);
        $count_c = clone $inexact_c;

        $parts = array();
        $exclude_ids = array();
        if ($do_search)
        {
          foreach (PartPeer::doSelectJoinPartVariants($exact_c) AS $part)
          {
            $parts[] = $part;
            $exclude_ids[] = $part->getId();
          }
        }
      
        //determine paging
        $limit = ($catsearch ? 0 : $request->getParameter('limit'));
        $start = $request->getParameter('start');
        if ($limit > 0)
        {
          if ($start > 0)
          {
            //exact matches are subtracted from inexact matches' start
            if (count($parts) > 0)
            {
              $start -= count($parts);
              $parts = array(); //reset since we're not on first page and don't want exact matches anymore
            }
          } else {
            $limit -= count($parts);
          }
          
          $inexact_c->setOffset($start);
          $inexact_c->setLimit($limit);
        }

        //remove the ids of exact matches
        if ($exclude_ids)
        {
          $inexact_c->add(PartPeer::ID, $exclude_ids, Criteria::NOT_IN);
        }

        foreach (PartPeer::doSelectJoinPartVariants($inexact_c) AS $part)
        {
          $parts[] = $part;
        }

        //get the count for paging purposes
        $count_all = WorkorderPeer::doCount($count_c);
    } 
 
    $categories = PartCategoryPeer::retrieveAllPaths(' &gt; ', $cats_c);
    $output = array();
    foreach ($parts AS $part)
    {
        $category_path = (isset($categories[$part->getPartCategoryId()]) ? $categories[$part->getPartCategoryId()] : 'None');
        $var = $part->getDefaultVariant();
        $output[] = array(
          'part_variant_id' => $part->getDefaultVariant()->getId(),
          'name'  => $part->getName(),
          'internal_sku' => $var->getInternalSku(),
          'category_path' => $category_path,
          'price' => $var->outputUnitPrice(true),
          'has_serial_number' => $part->getHasSerialNumber(),
          'manufacturer' => ($part->getManufacturer() ? $part->getManufacturer()->getWfCrm()->getName() : ''),
          'units' => (string) $var->getUnits(),
          'on_hand' => $var->getQuantity('onhand'),
          'on_hold' => $var->getQuantity('onhold'),
          'on_order' => $var->getQuantity('onorder'),
          'location' => (string) $var->getLocation()
        ); 
    }

    $dataarray = array('success' => true, 'totalCount' => $count_all, 'parts' => $output);

    return $dataarray;
  }

}
