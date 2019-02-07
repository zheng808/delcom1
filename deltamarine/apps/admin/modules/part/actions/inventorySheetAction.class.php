<?php

class inventorySheetAction extends sfAction
{

  public function execute($request)
  {
    set_time_limit(0);
    ini_set('memory_limit','512M');
    
    //set it up!
    $show_blank_new = true;
    $output = array();
    $header[] = 'Part';
    $header[] = 'Location';
    $header[] = 'Category';
    $header[] = 'Delta SKU';
    $header[] = 'Manu. SKU';
    $header[] = 'Origin';
    $header[] = 'Avg. Unit Cost';
    $header[] = 'Inventory'.($show_blank_new ? ' (Current)' : '');
    $header[] = 'Total Cost'.($show_blank_new ? ' (Current)' : '');
    if ($show_blank_new)
    {
        $header[] = 'NEW INVENTORY';
    }
    if ($this->getRequestParameter('cycle')) 
    {
      $header[] = 'Inv. Last Updated';
    }    
    $total_cols = count($header);
    $total_value = 0;

    require_once("Spreadsheet/Excel/Writer.php");
    $filename = "inventory-".date('m-j-Y').".xls";
    $workbook = new Spreadsheet_Excel_Writer();
    $workbook->send($filename);
    $workbook->setVersion(8);
    $worksheet = $workbook->addWorksheet();
    $worksheet->setLandscape();
    $worksheet->setMargins(0.25);

    //set up the formats
    $format_header = $workbook->addFormat();
    $format_header->setBold();
    $format_header->setBottom(2);
    $format_header->setRight(2);
    $format_header->setTop(2);
    $format_header->setLeft(2);
    $format_header->setSize(10);
    $format_header->setTextWrap();
    $workbook->setCustomColor(15, 200, 200, 200);
    $format_header->setFgColor(15); //grey
    $format_header->setHAlign('center');


    $bold_format = $workbook->addFormat();
    $bold_format->setBold();

    $border_format = $workbook->addFormat();
    $border_format->setBorder(2);

    $right_format = $workbook->addFormat();
    $right_format->setHAlign('right');

    $center_format = $workbook->addFormat();
    $center_format->setHAlign('center');

    $worksheet->setColumn(0, 0, 35);
    $worksheet->setColumn(1, 1, 20);
    $worksheet->setColumn(2, 2, 30);
    $worksheet->setColumn(3, 3, 20);
    $worksheet->setColumn(4, 4, 15);
    $worksheet->setColumn(5, 5, 15);
    $worksheet->setColumn(6, 6, 10);
    $worksheet->setColumn(7, 7, 10);
    $worksheet->setColumn(8, 8, 10);
    $worksheet->setColumn(9, 9, 20);
    $worksheet->setColumn(10, 10, 18);

    foreach ($header AS $col => $cell)
    {
        $worksheet->writeString(0, $col, $cell, $format_header);
    }
    $worksheet->setRow(0, 30);

    //load up category names
    $cats = PartCategoryPeer::retrieveAllPaths(' > ');

    //generate it!
    $sql = 'SELECT DISTINCT UPPER('.PartVariantPeer::LOCATION.')'.
            ' FROM '.PartPeer::TABLE_NAME.','.PartVariantPeer::TABLE_NAME;
    if ($this->getRequestParameter('supplier'))
    {
      $sql .= ','.PartSupplierPeer::TABLE_NAME.','.SupplierPeer::TABLE_NAME.','.wfCRMPeer::TABLE_NAME;
    }
    $sql .= ' WHERE '.PartPeer::ID.' = '.PartVariantPeer::PART_ID.
            ' AND '.PartPeer::ACTIVE.' = 1';

    if ($this->getRequestParameter('supplier'))
    {
      $sql .= ' AND '.PartVariantPeer::ID.' = '.PartSupplierPeer::PART_VARIANT_ID.
              ' AND '.PartSupplierPeer::SUPPLIER_ID.' = '.SupplierPeer::ID.
              ' AND '.SupplierPeer::WF_CRM_ID.' = '.wfCRMPeer::ID.
              ' AND '.wfCRMPeer::DEPARTMENT_NAME." like '".str_replace('*','%',trim($this->getRequestParameter('supplier')))."%'";
    }
    if ($this->getRequestParameter('location'))
    {
      $sql .= ' AND '.PartVariantPeer::LOCATION." like '".str_replace('*','%',trim($this->getRequestParameter('location')))."%'";
    }
    if ($this->getRequestParameter('name'))
    {
      $sql .= ' AND '.PartPeer::NAME." like '".str_replace('*','%',trim($this->getRequestParameter('name')))."%'";
    }
    if ($this->getRequestParameter('category'))
    {
      $sql .= ' AND '.PartPeer::PART_CATEGORY_ID." = ".(int) $this->getRequestParameter('category');
    }    
    if ($this->getRequestParameter('cycle'))
    {
      $sql .= ' ORDER BY RAND()';
    }
    else
    {
      $sql .= ' ORDER BY '.PartVariantPeer::LOCATION.' ASC';
    }

    $con = Propel::getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $locations = array();
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
        if ($row[0] != '')
        {
          $locations[] = $row[0];
        }
    }
    if (!$this->getRequestParameter('location') && !$this->getRequestParameter('cycle'))
    {
      $locations[] = '';
    }

    $cycle_limit = $limit = (is_numeric($this->getRequestParameter('limit')) ? $this->getRequestParameter('limit') : 0);
    $total_count = 0;
    $row = 0;
    foreach ($locations as $location)
    {
        $row++;
        //output title
        $this_row = array_fill(0, $total_cols, '');
      
        $location_title = ($location == '' ? 'Unknown Location' : $location);
        $worksheet->writeString($row, 0, $location_title, $bold_format);

        //get products
        $offset = 0;
        $limit = $cycle_limit ? $cycle_limit : 200;
        do
        {
          $c = new Criteria();
          $c->addJoin(PartPeer::PART_CATEGORY_ID, PartCategoryPeer::ID);
          if ($this->getRequestParameter('name'))
          {
            $c->add(PartPeer::NAME, '%'.$this->getRequestParameter('name').'%', Criteria::LIKE);
          }
          $c->setLimit((int) $limit);
          $c->add(PartPeer::ACTIVE, true);
          if ($location == '')
          {
            $c->add(PartVariantPeer::LOCATION, null, Criteria::ISNULL);
          }
          else
          {
           $c->add(PartVariantPeer::LOCATION, $location); 
          }
          if ($this->getRequestParameter('supplier'))
          {
            $c->addJoin(PartVariantPeer::ID, PartSupplierPeer::PART_VARIANT_ID);
            $c->addJoin(PartSupplierPeer::SUPPLIER_ID, SupplierPeer::ID);
            $c->addJoin(SupplierPeer::WF_CRM_ID, wfCRMPeer::ID);
            $c->add(wfCRMPeer::DEPARTMENT_NAME, '%'.$this->getRequestParameter('supplier').'%', Criteria::LIKE);
          }          
          if ($this->getRequestParameter('category'))
          {
            $c->add(PartPeer::PART_CATEGORY_ID, (int) $this->getRequestParameter('category'));
          }
          if ($this->getRequestParameter('cycle'))
          {

            if (is_numeric($this->getRequestParameter('min_age')))
            {
              $c1 = $c->getNewCriterion(PartVariantPeer::LAST_INVENTORY_UPDATE, (time() - (((int) $this->getRequestParameter('min_age')) * 2592000)), Criteria::LESS_THAN);
              $c2 = $c->getNewCriterion(PartVariantPeer::LAST_INVENTORY_UPDATE, null, Criteria::ISNULL);
              $c2->addOr($c1);
              $c->addAnd($c2);
            }
            $c->addAscendingOrderByColumn('rand()');
          }
          else
          {
              $c->addAscendingOrderByColumn(PartCategoryPeer::LEFT_COL);
              $c->addAscendingOrderByColumn(PartPeer::NAME);
          }
          if ($offset > 0)
          {
            $c->setOffset($offset);
          }
          
          $parts = PartPeer::doSelectJoinPartVariants($c);
          $partcount = count($parts);

          if ($partcount == 0)
          {
            unset ($c, $parts);
            $row--;
            break;
          }

          foreach ($parts AS $part)
          {
              $row++;
              $total_count ++;
              $this_cat = (array_key_exists($part->getPartCategoryId(), $cats) ? $cats[$part->getPartCategoryId()] : null);
              if ($part->getIsMultisku())
              {
                  $worksheet->writeString($row, 0, str_repeat(' ', 6).$part->getName());
                  foreach ($part->getPartVariants() as $variant)
                  {
                      $row++;
                      $worksheet->writeString($row, 0, str_repeat(' ', 7).$variant->outputOptionValuesList(', '));
                      $worksheet->writeString($row, 1, $variant->getLocation());
                      $worksheet->writeString($row, 2, ($this_cat ? $this_cat : 'Unknown'));
                      $worksheet->writeString($row, 3, $variant->getInternalSku(), $center_format);
                      $worksheet->writeString($row, 4, $variant->getManufacturerSku(), $center_format);
                      $worksheet->writeString($row, 5, $part->getOrigin(), $center_format);
                      $worksheet->writeString($row, 6, $variant->outputUnitCost(), $right_format);
                      $worksheet->writeString($row, 7, $variant->getQuantity('onhand'), $center_format);
                      $cost = $variant->calculateInventoryCost();
                      $worksheet->writeString($row, 8, number_format($cost,2), $right_format);
                      $total_value += $cost;
                      if ($show_blank_new)
                      {
                          $worksheet->writeString($row, 9, '', $border_format);
                      }
                      if ($this->getRequestParameter('cycle')) 
                      {
                        $worksheet->writeString($row, 10, $variant->getLastInventoryUpdate('M j, Y'), $center_format);
                      }                            
                  }
              }
              else if ($variant = $part->getDefaultVariant())
              {
                  $worksheet->writeString($row, 0, str_repeat(' ', 6).$part->getName());
                  $worksheet->writeString($row, 1, $variant->getLocation());
                  $worksheet->writeString($row, 2, ($this_cat ? $this_cat : 'Unknown'));
                  $worksheet->writeString($row, 3, $variant->getInternalSku(), $center_format);
                  $worksheet->writeString($row, 4, $variant->getManufacturerSku(), $center_format);
                  $worksheet->writeString($row, 5, $part->getOrigin(), $center_format);                  
                  $worksheet->writeString($row, 6, $variant->outputUnitCost(), $right_format);
                  $worksheet->writeString($row, 7, $variant->getQuantity('onhand'), $center_format);
                  $cost = $variant->calculateInventoryCost();
                  $worksheet->writeString($row, 8, number_format($cost,2), $right_format);
                  $total_value += $cost;
                  if ($show_blank_new)
                  {
                      $worksheet->writeString($row, 9, '', $border_format);
                  }
                  if ($this->getRequestParameter('cycle')) 
                  {
                    $worksheet->writeString($row, 10, $variant->getLastInventoryUpdate('M j, Y'), $center_format);
                  }         
              }
              if ($cycle_limit && ($total_count >= $cycle_limit))
              {
                break(3);
              }
          }
          unset ($c, $parts, $variant, $part);
          $offset += $limit;

        } while (($partcount == $limit) && !$this->getRequestParameter('cycle'));
    }

    $row++;
    $worksheet->writeString($row + 1, 6, 'TOTAL', $format_header);
    $worksheet->writeString($row + 1, 7, number_format($total_value, 2), $right_format);

    $workbook->close();

    return sfView::NONE;
  }


}
