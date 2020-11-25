<?php

class partslistprintAction extends sfAction
{

  public function execute($request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $workorder = WorkorderPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($workorder, sprintf('Workorder does not exist (id = %s).', $request->getParameter('id')));

    //get the settings
    $price     = (bool) $request->getParameter('price', true);
    $category  = (bool) $request->getParameter('category', false);
    $combine   = (bool) $request->getParameter('combine', false);
    $status    = (bool) $request->getParameter('status', true);
    $origin    = (bool) $request->getParameter('origin', false);
    $format    = $request->getParameter('format', 'alllevel');
    $sorting   = $request->getParameter('sorting', 'name');
 
    //load the PDF generator class
    $final_data = array();

    //create base criteria for sorting
    $c = new Criteria();
    $c->addJoin(PartInstancePeer::PART_VARIANT_ID, PartVariantPeer::ID, Criteria::LEFT_JOIN);
    $c->addJoin(PartVariantPeer::PART_ID, PartPeer::ID, Criteria::LEFT_JOIN);

    //apply the sorting rules
    if ($sorting == 'name')
    {
      $c->addAscendingOrderByColumn(PartPeer::NAME);
    }
    else if ($sorting == 'sku')
    {
      $c->addAscendingOrderByColumn(PartVariantPeer::INTERNAL_SKU);
    }
    else if ($sorting == 'category')
    {
      $c->addJoin(PartPeer::PART_CATEGORY_ID, PartCategoryPeer::ID, Criteria::LEFT_JOIN);
      $c->addAscendingOrderByColumn(PartCategoryPeer::NAME);
      $c->addAscendingOrderByColumn(PartPeer::NAME);
    }

    //determine the format and collate items as needed
    if ($format == 'single')
    {
      $c1 = clone $c;
      $c1->add(WorkorderItemPeer::WORKORDER_ID, $workorder->getId());
      $c1->addJoin(WorkorderItemPeer::ID, PartInstancePeer::WORKORDER_ITEM_ID);

      $items = PartInstancePeer::doSelectJoinPartVariant($c1);
      $sections = array(array('title' => 'Overall List', 'items' => $items));
    }
    else if ($format == 'toplevel')
    {
      $children = $workorder->getRootItem()->getChildren();
      $counter = 0;
      $sections = array();
      foreach ($children AS $child)
      {
        $descendant_ids = array($child->getId());
        $descendants = $child->getDescendants();
        foreach ($descendants AS $descendant)
        {
          $descendant_ids[] = $descendant->getId();
        }
        unset($descendants);
        $counter++;
        $c1 = clone $c;
        $c1->add(PartInstancePeer::WORKORDER_ITEM_ID, $descendant_ids, Criteria::IN);
        $items = PartInstancePeer::doSelectJoinPartVariant($c1);
        $sections[] = array('title' => 'Task '.$counter.': '.$child->getLabel(), 'items' => $items);
      }
    }
    else if ($format == 'alllevel')
    {
      $sections = array();
      $task_prefix = '';
      $parent = $workorder->getRootItem();
      $sections = $this->_recurse_sections($parent, $c, $task_prefix);
    }

    if ($combine)
    {
      foreach ($sections AS $section_idx => $section_data)
      {
        $items_arr = array();
        foreach ($section_data['items'] AS $item)
        {
          if ($item->getPartVariantId())
          {
            if (isset($items_arr[$item->getPartVariantId()]) && ($item->getAllocated() == $items_arr[$item->getPartVariantId()]->getAllocated())
                                                             && ($item->getDelivered() == $items_arr[$item->getPartVariantId()]->getDelivered()))
            {
              //combine the quantities and change average price
              $existing = $items_arr[$item->getPartVariantId()];
              $old_unit = $existing->getUnitPrice();
              $old_qty  = $existing->getQuantity();
              $add_unit = $item->getUnitPrice();
              $add_qty  = $item->getQuantity();

              $new_unit = ($old_unit*$old_qty + $add_unit*$add_qty) / ($old_qty+$add_qty);
              $existing->setUnitPrice(round($new_unit, 2));
              $existing->setQuantity($existing->getQuantity() + $item->getQuantity());
              //don't you dare save it!!
              $existing->setId(null);
            }
            else if (isset($items_arr[$item->getPartVariantId()]))
            {
              $items_arr[$item->getPartVariantId().'_'.($item->getAllocated() ? 1 : 0).($item->getDelivered() ? 1 : 0)] = $item;
            }
            else
            {
              $items_arr[$item->getPartVariantId()] = $item;
            }
          }
          else
          {
            $items_arr['custom'.$item->getId()] = $item;
          }
        }
        $sections[$section_idx]['items'] = array_values($items_arr);
      }
    }

    $pdf = new PartsListPDF($workorder, $price, $status, $category, $origin);
    $pdf->generate($sections);
    $pdf->Output('partslist.pdf', 'D');

    return sfView::NONE;
  }

  private function _recurse_sections($parent, $c, $task_prefix)
  {
    $sections = array();

    //step 1: get all instances which have this has parent
    if (!$parent->isRoot())
    {
      $c1 = clone $c;
      $c1->add(PartInstancePeer::WORKORDER_ITEM_ID, $parent->getId());
      $items = PartInstancePeer::doSelectJoinPartVariant($c1);
      $sections[] = array('title' => 'Task '.$task_prefix.': '.$parent->getLabel(), 'items' => $items);
    }

    //step 2: look for children
    if ($parent->hasChildren())
    {
      $counter = 0;
      foreach ($parent->getChildren() AS $child)
      {
        $counter ++;
        $new_task_prefix = $task_prefix.($task_prefix == '' ? '' : '.').$counter;
        $return = $this->_recurse_sections($child, $c, $new_task_prefix);
        foreach ($return AS $ret)
        {
          $sections[] = $ret;
        }
      }
    }

    return $sections;
  }

}
