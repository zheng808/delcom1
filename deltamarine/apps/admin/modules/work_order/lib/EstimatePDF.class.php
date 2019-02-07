<?php

class EstimatePDF extends sfTCPDF
{
    var $workorder;
    var $settings;

    var $current_section_title;
    var $current_section_number;

    var $cols;
    var $title_font = 20;
    var $subtitle_font = 8;
    var $section_font = 12;
    var $totals_font = 8;
    var $oddrow;
    var $adding_notes = false;
    var $adding_tasknotes = false;

    var $margin_width = 7;
    var $margin_height = 8;
    var $column_padding = 3;
    var $default_fontsize = 7;
    var $header_fontsize = 9;
    var $page_width;
    var $row_indent = 5;
    var $max_fontsize = 7;

    var $small_cols = array(); //these are set at 80% of regular font size
    var $small_col_factor = 0.8;
    var $expand_col;

    var $drawn_sections = 0;
    var $uncat_items = array();

    public function __construct($workorder, $settings)
    {
      $this->workorder = $workorder;
      $this->settings = $settings;

      parent::__construct('P', 'mm', 'Letter', false);

      $this->SetMargins($this->margin_width, $this->margin_height);
      $this->SetFont('Arial', 'B', $this->default_fontsize);
      $this->SetAutoPageBreak(false);
      $this->page_width =  216 - (2 * $this->margin_width);
    }


    function Header()
    {

    }

    function generateLogoHeader($title = null)
    {
      $page_top = $this->GetY();
      if (!$title)
      {
        $title = 'ESTIMATE #'.$this->workorder->getId(); 
      }

      //insert logo
      $this->Image(sfConfig::get('sf_web_dir').'/images/invoice_header.jpg', $page_top + 4, $page_top, 62, 18);

      //insert delta info
      $this->SetXY(80, $page_top + 2);
      $this->setFont("Arial", "B", $this->subtitle_font);
      $this->setTextColor(0);
      //$this->Cell(50, $this->subtitle_font/1.5, ($this->workorder->getForRigging() ? 'Delta Rigging and Welding' : 'Delta Marine Service'), 0, 2, 'L');
      $this->setFont("Arial", '', 8);
      $this->MultiCell(50, $this->default_fontsize/2, "2075 Tryon Road\nSidney, B.C.  V8L 3X9\nTel: (250) 656-2639\nFax: (250) 656-2619", 0, 'L', 0, 0);

      //main title
      $this->SetY($page_top);
      $this->SetX(90);
      $this->setFont("Arial", "B", $this->title_font);
      $this->Cell(0, $this->title_font/2.5, $title, 0, 2, 'R');

      $this->SetY(40);
    }

    function generateIntro()
    {
      $subtitles=array();
      $maxwidth = 0;

      $this->generateLogoHeader();

      $this->SetFillColor(231,239,239);
      $this->SetDrawColor(231,239,239);

      //load customer info
      $customer_data = array();
      $customer = $this->workorder->getCustomerBoat()->getCustomer()->getWfCRM();
      if ($name = $customer->getName())
      {
        $customer_data[] = $name;
      }
      if ($email = $customer->getEmail())
      {
        $customer_data[] = $email;
      }
      if ($phone = $customer->getHomePhone())
      {
        $customer_data[] = 'Home: '.$phone;
      }
      if ($phone = $customer->getMobilePhone())
      {
        $customer_data[] = 'Mobile: '.$phone;
      }
      if ($phone = $customer->getWorkPhone())
      {
        $customer_data[] = 'Work: '.$phone;
      }
      if ($phone = $customer->getFax())
      {
        $customer_data[] = 'Fax: '.$phone;
      }
      if ($address = $customer->getWfCRMAddresss())
      {
        $customer_data[] = $address[0]->getAddress(', ');
      }
      $customer_lines = count($customer_data);

      //load workorder info
      $workorder_data = array();
      $boat = $this->workorder->getCustomerBoat();
      $workorder_data[] = array('Estimate Number:',$this->workorder->getId());
      $workorder_data[] = array('Boat Name:', $boat->getName());
      if ($boat->getMakeModel())
      {
        $workorder_data[] = array('Boat Type:',$boat->getMakeModel());
      }
      if ($this->workorder->getStartedOn())
      {
        $workorder_data[] = array('Started On:',$this->workorder->getStartedOn('Y-M-j'));
      }
      if ($this->workorder->getCompletedOn())
      {
        $workorder_data[] = array('Completed On:',$this->workorder->getCompletedOn('Y-M-j'));
      }
      if ($this->settings['delivery_time'])
      {
        $workorder_data[] = array('Delivery:', $this->settings['delivery_time']);
      }

      //equialize section lengths
      $diff = (count($customer_data) - count($workorder_data));
      for ($i = 0; $i < abs($diff); $i ++)
      {
        ($diff < 0 ? $customer_data[] = ' ' : $workorder_data[] = array(' ',' '));
      }

      //output customer information
      $start_y = $this->getY();
      $this->SetX(20);
      $this->setFont('Arial', 'B', $this->subtitle_font);
      $this->Cell(80, 4, 'Customer Information', 1, 2, 'C', 1);
      $this->setFont("Arial", '', $this->subtitle_font);
      $this->MultiCell(80, $this->subtitle_font/2.5, implode("\n", $customer_data), 1, 'L', 0, 0);

      //output workorder information
      $this->setXY(115, $start_y);
      $this->setFont('Arial', 'B', $this->subtitle_font);
      $this->Cell(80, 4, 'Estimate Details', 1, 2, 'C', 1);
      $this->setFont("Arial", '', $this->subtitle_font);
      $count = 0;
      foreach ($workorder_data AS $data)
      {
        $this->setX(115);
        $this->setFont("Arial", 'B', $this->subtitle_font);
        $last = ($count == count($workorder_data) - 1);
        $first = ($count == 0);
        $this->Cell(30, $this->subtitle_font/2, $data[0], 'L'.($first ? 'T' : '').($last ? 'B' : ''), 0, 'R', 0);
        $this->setFont("Arial", '', $this->subtitle_font);
        $this->Cell(50, $this->subtitle_font/2, $data[1], 'R'.($first ? 'T' : '').($last ? 'B' : ''), 1, 'L', 0);
        $count ++;
      }

      $this->ln(3);

      //INSERT CUSTOMER NOTES
      if ($this->settings['customer_notes'] && $this->workorder->getCustomerNotes())
      {
        $this->SetFillColor(231,239,239);
        $this->SetDrawColor(231,239,239);
        $this->setX(20);
        $this->setFont('Arial', 'B', $this->subtitle_font);
        $this->Cell(175, 4, 'Estimate Notes', 1, 2, 'C', 1);
        $notes = $this->workorder->getCustomerNotes();
        $padding = $this->GetCellPaddings();
        $this->SetCellPadding(3);
        $this->SetAutoPageBreak(true, 20);
        $this->adding_notes = true;
        $this->setFont('Arial', '', $this->subtitle_font);
        $this->MultiCell(175, $this->subtitle_font/2, $notes, 1, 'L', 0, 1);
        $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
        $this->SetAutoPageBreak(false);
        $this->adding_notes = false;
      }
    }

    function AddPage($orientation = '', $format = '', $keepmargins=false, $tocpage=false)
    {
      parent::AddPage($orientation, $format, $keepmargins=false, $tocpage=false);

      if ($this->adding_notes)
      {
        $this->setX(20);
        $this->setFont('Arial', 'B', $this->subtitle_font);
        $this->Cell(175, 4, 'Estimate Notes (cont\'d)', 1, 2, 'C', 1);
        $this->ln(5);
        $this->setFont('Arial', '', $this->subtitle_font);
      }
      else if ($this->adding_tasknotes)
      {
        $this->drawSectionHeader(true);
      }
    }

    function Footer()
    {
        $this->SetY(-11);

        $this->SetFont('Arial', 'BI', 8);
        $this->Cell($this->page_width/3, 5, 'Estimate #'.$this->workorder->getId(), 0, 0, 'C');
        $this->Cell($this->page_width/3, 5, "Generated ".date('Y-M-j g:i a'), 0, 0, 'C');
        $this->AliasNbPages();
        $this->Cell(0, 5, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'C');
    }

    function drawTableHeader($cols)
    {
        $newpage_barrier = 255;
        $height = $this->header_fontsize / 2;
        if (($this->GetY() + $height) > $newpage_barrier)
        {
          $this->addPage();
          $this->drawSectionHeader(true);
        }

        $this->Rect($this->GetX(), $this->GetY(), ($this->row_indent / 2), $height + 0.2, 'F', false, array(231,239,239));
        $this->SetX($this->GetX() + ($this->row_indent / 2));

        $this->SetFont('Arial', 'B', $this->header_fontsize);
        $this->SetDrawColor(200);
        $this->SetFillColor(200);
        $this->SetTextColor(0);
        $this->SetLineWidth(0.2);
        $line_height = $this->header_fontsize / 2;

        $first_item = true;
        foreach ($cols AS $key => $this_col)
        {
            $this->Cell($first_item ? ($this->row_indent/2) + $this_col[0] : $this_col[0], $line_height, $key, 1, 0, ($first_item ? 'L' : 'C'), 1);
            $first_item = false;
        }
        $this->ln($line_height + 0.1);
        $this->oddrow = false;
    }

    function drawRow($data, $colwidths, $notes = null, $blue = true, $fullnotes = true)
    {
        $first = current($colwidths);
        $line_height = $first[1] / 1.5;
        $newpage_barrier = 266;
        $height = $line_height;
        if ($notes)
        {
          $this->SetFont('Arial', '', $this->default_fontsize - 1);
          $note_width = 0;
          $idx = 0;
          if ($full_notes)
          {
            foreach ($colwidths AS $this_col)
            {
              $idx ++;
              $note_width += ($idx == count($colwidths) ? 0 : $this_col[0]);
            }
          }
          else
          {
            $note_width = $colwidths[0];
          }
          $note_height = $this->getStringHeight($note_width, $notes);
          $height += $note_height;
        }
        if (($this->GetY() + $height) > $newpage_barrier)
        {
          $this->AddPage();
          $this->drawSectionHeader(true);
          $this->drawTableHeader($colwidths);
        }

        $this->SetDrawColor(200);
        $this->SetTextColor(0,0,0);

        $idx = 0;
        $first_col = true;

        //draw the blue rectangle to the left
        if ($blue)
        {
          $this->Rect($this->GetX(), $this->GetY(), $this->row_indent, $height + 0.2, 'F', false, array(231,239,239));
          $this->SetX($this->GetX() + $this->row_indent);
        }

        if (!isset($this->oddrow)) $this->oddrow = true;
        if ($this->oddrow) $this->SetFillColor(240,240,240);
        else $this->SetFillColor(255,255,255);
        $this->oddrow = !$this->oddrow;

        foreach ($colwidths AS $key => $this_col)
        {
          $align = $colwidths[$key][2];
          $this->SetFont('Arial', '', $this_col[1]);
          if ($colwidths[$key][3] == 'text')
          {
            if ($data[$idx] == '')
            {
              $text = ' - ';
              $align = 'C';
            }
            else
            {
              $text = $data[$idx];
            }
          }
          else if ($colwidths[$key][3] == 'money')
          {
            $val = (float) str_replace(',','', str_replace('$', '', $data[$idx]));
            if ($val == 0 && ($idx != count($colwidths) - 1))
            {
              $text = ' - ';
              $align = 'R';
            }
            else 
            {
              $text = ' '.number_format($val,2); //I have no idea why the blank space fixes things, but it does. don't touch.
            }
          }
          else if ($colwidths[$key][3] == 'number')
          {
              $text = $data[$idx] == 0 ? ' - ' : (is_numeric($data[$idx]) && ((int) $data[$idx] == (float) $data[$idx]) ? number_format($data[$idx]) : $data[$idx]);
          }
          if ($notes)
          {
            if ((!$fullnotes && $idx > 0) || ($fullnotes && $idx == (count($colwidths) - 1)))
            {
              //make the last column extra high
              $this->Cell($this_col[0], $line_height + $note_height, $text, 'LTR', 0, $align, 1);
            }
            else
            {
              $this->Cell($this_col[0], $line_height, $text, 'LTR', 0, $align, 1);
            }
          }
          else
          {
            $this->Cell($this_col[0], $line_height, $text, 1, 0, $align, 1);
          }
          $idx ++;
          $first_col = false;
        }
        $this->ln($line_height);
        if ($notes)
        {
          $this->setTextColor(100);
          $padding = $this->GetCellPaddings();
          $this->SetCellPaddings(5, $padding['T'],$padding['R'],$padding['B']);
          $this->setFont('Arial', '', $this->default_fontsize - 1);
          $this->SetX($this->GetX() + $this->row_indent);
          $this->MultiCell($note_width, $note_height, $notes, 'LBR', 'L', 1, 1);
          $this->setTextColor(0);
          $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
        }
    }

    function drawTableFooter($colwidths, $subtotal)
    {
      end($colwidths);
      $totals_width = current($colwidths);
      $labels_width = $this->page_width - $totals_width[0];

      $this->SetFillColor(255);
      $this->SetFont("Arial", "B", $this->default_fontsize);
      $this->SetDrawColor(0);
      $this->Cell($labels_width, $this->default_fontsize/1.5, 'Subtotal:', 0, 0, 'R', 1);
      $this->Cell(0, $this->default_fontsize/1.5, number_format($subtotal,2), 1, 1, 'R', 1);
    }



    function drawItemsTable($items, $subtotal = null)
    {
      //SET UP COLUMNS
      $short = false;
      $colwidths = array(''             => array(0,0,'L','text'), 
                         'Quantity'     => array(0,0,'C','number'), 
                         'Unit Price'   => array(0,0,'R','money'), 
                         'Price'        => array(0,0,'R','money'));

      $colwidths = $this->autoSizeTable($items, $colwidths);

      //OUTPUT DATA
      if (count($items) > 0)
      {
        $this->drawTableHeader($colwidths);
        foreach ($items AS $item)
        {
            $this->drawRow($item, $colwidths, isset($item['notes']) ? $item['notes'] : null, true, false);
        }

        if ($subtotal > 0)
        {
          $this->drawTableFooter($colwidths, $subtotal);
        }
      }
    }

    function drawSectionHeader($continued = false)
    {
        if ($this->current_section_title != '')
        {
          $this->SetFont("Arial", "B", $this->section_font);
          $this->SetFillColor(231,239,239);
          $this->SetDrawColor(231,239,239);
          $this->SetTextColor(0);
          $this->Cell(0, $this->section_font/1.8, ' '.$this->current_section_title.($continued ? ' (Cont\'d)' : ''), 1, 1, 'L', 1);
          $this->SetFillColor(255);
          $this->SetFont("Arial", "", $this->default_fontsize);
        }
    }

    //OUTPUTS the various bits for a particular section (workorder item)
    function generateSection($number, $title, $section, $totals, $billable)
    {
        $orig_title = $title;
        $title = $number.': '.$title;
        $this->current_section_title = $title;
        $this->current_section_number = $number;
        $labour_factor = 1;
        $part_factor = 1;

        //setup totals array for this section
        if (!isset($totals['sections']))
        {
          $totals['sections'] = array();
        }
        $totals['sections'][$title] = array('parts' => array('subtotal' => 0, 'hst' => 0, 'pst' => 0, 'gst' => 0, 'battery_levy' => 0, 'enviro_levy' => 0),
                                            'labour' => array('subtotal' => 0, 'hst' => 0, 'pst' => 0, 'gst' => 0, ),
                                            'expenses' => array('subtotal' => 0, 'hst' => 0, 'pst' => 0, 'gst' => 0, ),
                                            'discount' => 0
                                           );

        $section_items = array();

        //add parts
        //===================================
        $parts_c = new Criteria();
        $parts_c->add(PartInstancePeer::WORKORDER_ITEM_ID, $section->getId());
        if ($this->settings['parts_detail'] == 'allused')
        {
          $c1 = $parts_c->getNewCriterion(PartInstancePeer::DELIVERED, true);
          $c2 = $parts_c->getNewCriterion(PartInstancePeer::ESTIMATE, true);
          $c1->addOr($c2);
          $parts_c->addAnd($c1);
        }
        else
        {
          $parts_c->add(PartInstancePeer::ESTIMATE, true); 
        }
        $parts_c->addAscendingOrderByColumn(PartPeer::NAME);
        $parts = PartInstancePeer::doSelectJoinPart($parts_c);

        //loop through parts once to get category totals and combine duplicate parts
        $compiled_parts = array();
        foreach ($parts AS $part)
        {
          $sub  = round($part_factor * $part->getSubtotal(false), 2);
          $hst  = $part_factor * (!$this->workorder->getHstExempt() ? $part->getHstTotal(false) : 0);
          $pst  = $part_factor * ($this->settings['taxable_pst'] ? $part->getPstTotal(false) : 0);
          $gst  = $part_factor * ($this->settings['taxable_gst'] ? $part->getGstTotal(false) : 0);
          $batt = round($part_factor * $part->getBatteryLevyTotal(false), 2);
          $env  = round($part_factor * $part->getEnviroLevyTotal(false), 2);
          $totals['sections'][$title]['parts']['subtotal']     += $sub; 
          $totals['sections'][$title]['parts']['hst']          += $hst;
          $totals['sections'][$title]['parts']['pst']          += $pst;
          $totals['sections'][$title]['parts']['gst']          += $gst;
          $totals['sections'][$title]['parts']['battery_levy'] += $batt;
          $totals['sections'][$title]['parts']['enviro_levy']  += $env;

          $compiled_idx = ($part->getPartVariantId() ? $part->getPartVariantId().$part->getSerialNumber() : 'custom'.$part->getId());

          if (isset($compiled_parts[$compiled_idx]))
          {
            $compiled_parts[$compiled_idx][1] += $part->getQuantity();
            $compiled_parts[$compiled_idx][3] += $sub; 

            if ($compiled_parts[$compiled_idx][2] != $part->getUnitPrice())
            {
              //calculate average price
              $compiled_parts[$compiled_idx][2] = round($compiled_parts[$compiled_idx][4] / $compiled_parts[$compiled_idx][2], 2);
            }
          }
          else
          {
            $label = trim($part->__toString()).($part->getSerialNumber() ? ' ('.$part->getSerialNumber().')' : '');
            $label .= ($part->getPartVariant() ? ' (SKU: '.$part->getPartVariant()->getInternalSku().')' : '');
            $compiled_parts[$compiled_idx] = array($label,
                                                    $part->getQuantity(),
                                                    round($part_factor * $part->getUnitPrice(), 2),
                                                    $sub);
          }
        }

        if ($this->settings['parts_detail'] != 'total')
        {
          if ($this->settings['subtasks']) {
            $section_items = array_values($compiled_parts);
          } else {
            $this->uncat_items = array_merge($this->uncat_items , array_values($compiled_parts));
          }
          unset($compiled_parts, $parts, $part);
        }

        //add Parts Line item
        $sub = 0;
        if ($section->getPartEstimate())
        {
          $sub = round($part_factor * $section->getPartEstimate(), 2);
          $hst = (!$this->workorder->getHstExempt() ? ($sub * (sfConfig::get('app_hst_rate')/100)) : 0);
          $pst = ($this->settings['taxable_pst'] ? ($sub * (sfConfig::get('app_pst_rate')/100)) : 0);
          $gst = ($this->settings['taxable_gst'] ? ($sub * (sfConfig::get('app_gst_rate')/100)) : 0);
          $totals['sections'][$title]['parts']['subtotal']     += $sub; 
          $totals['sections'][$title]['parts']['hst']          += $hst;
          $totals['sections'][$title]['parts']['pst']          += $pst;
          $totals['sections'][$title]['parts']['gst']          += $gst;
        }

        $output_amt = ($this->settings['parts_detail'] != 'total') ? $sub : $totals['sections'][$title]['parts']['subtotal'];
        if ($output_amt != 0)
        {
          if ($this->settings['subtasks']) {
            $section_items[] = array((count($section_items) > 0 ? 'Additional ' : '').'Estimated Parts', null, null, $output_amt);
          } else {
            $this->uncat_items[] = array($orig_title.' - Parts', null, null, $output_amt);
          }
        }

        //add labour
        //===================================
        $labour_c = new Criteria();
        $labour_c->add(TimelogPeer::WORKORDER_ITEM_ID, $section->getId());
        $labour_c->add(TimelogPeer::BILLABLE_HOURS, 0, Criteria::GREATER_THAN);
        if ($this->settings['labour_detail'] == 'allused')
        {
          $c1 = $labour_c->getNewCriterion(TimelogPeer::APPROVED, true);
          $c2 = $labour_c->getNewCriterion(TimelogPeer::ESTIMATE, true);
          $c1->addOr($c2);
          $labour_c->addAnd($c1);
        }
        else
        {
          $labour_c->add(TimelogPeer::ESTIMATE, true); 
        }        
        $labours = TimelogPeer::doSelect($labour_c);

        //loop through timelogs once to get category totals
        $sub = 0;
        $compiled_labour = array();
        foreach ($labours AS $labour)
        {
          $sub = round($labour_factor * $labour->getSubtotal(), 2);
          $hst = $labour_factor * (!$this->workorder->getHstExempt() ? $labour->getHstTotal() : 0);
          $pst = $labour_factor * ($this->settings['taxable_pst'] ? $labour->getPstTotal() : 0);
          $gst = $labour_factor * ($this->settings['taxable_gst'] ? $labour->getGstTotal() : 0);
          $totals['sections'][$title]['labour']['subtotal'] += $sub; 
          $totals['sections'][$title]['labour']['hst']      += $hst;
          $totals['sections'][$title]['labour']['pst']      += $pst;
          $totals['sections'][$title]['labour']['gst']      += $gst;


          $compiled_idx = ($labour->getLabourTypeId() ?  $labour->getLabourTypeId() : 'custom'.$labour->getId());

          if (isset($compiled_labour[$compiled_idx]))
          {
            $compiled_labour[$compiled_idx][1] += $labour->getBillableHours();
            $compiled_labour[$compiled_idx][3] += $sub; 

            if ($compiled_labour[$compiled_idx][2] != $labour->getRate())
            {
              //calculate average price
              $compiled_parts[$compiled_idx][2] = round($compiled_labour[$compiled_idx][4] / $compiled_labour[$compiled_idx][2], 2);
            }
          }
          else
          {
            $label = $labour->getLabourType() ? $labour->getLabourType()->getName() : 'Custom Labour Rate';
            $compiled_labour[$compiled_idx] = array($label,
                                                    $labour->getBillableHours(),
                                                    $labour->getRate(),
                                                    $sub);
          }
        }

        if ($this->settings['labour_detail'] != 'total')
        {
          if ($this->settings['subtasks']) {
            $section_items = array_merge($section_items, array_values($compiled_labour));
          } else {
            $this->uncat_items = array_merge($this->uncat_items, array_values($compiled_labour));
          }
        }

        //show labour estimates
        //figure out what to show as additional expenses
        $additional_labour = $total = $section->getLabourEstimate();
        if ($this->settings['labour_detail'] == 'total'){
          $total = $additional_labour + $totals['sections'][$title]['expenses']['subtotal'];
        } else {
          $total = $additional_labour;
        }
        if ($total != 0) 
        {
          if ($this->settings['subtasks']) {
            $section_items[] = array((($additional_labour > 0) && ($sub > 0) && ($this->settings['labour_detail'] != 'total') ? 'Additional ' : '').'Estimated Labour', null, null, $total);
          } else {
            $this->uncat_items[] = array($orig_title.' - Labour', null, null, $total);
          }
        }        

        //add to the running total
        if ($section->getLabourEstimate())
        {
          $sub = round($labour_factor * $section->getLabourEstimate(), 2);
          $hst = (!$this->workorder->getHstExempt() ? ($sub * (sfConfig::get('app_hst_rate')/100)) : 0);
          $pst = ($this->settings['taxable_pst'] ? ($sub * (sfConfig::get('app_pst_rate')/100)) : 0);
          $gst = ($this->settings['taxable_gst'] ? ($sub * (sfConfig::get('app_gst_rate')/100)) : 0);
          $totals['sections'][$title]['labour']['subtotal']     += $sub; 
          $totals['sections'][$title]['labour']['hst']          += $hst;
          $totals['sections'][$title]['labour']['pst']          += $pst;
          $totals['sections'][$title]['labour']['gst']          += $gst;
        }

        //add expenses
        //===================================

        $expense_c = new Criteria();
        $expense_c->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $section->getId());
        if ($this->settings['other_detail'] != 'allused')
        {
          $expense_c->add(WorkorderExpensePeer::ESTIMATE, true); 
        }
        $expenses = WorkorderExpensePeer::doSelect($expense_c);

        //loop through expenses once to get category totals
        $sub = 0;
        foreach ($expenses AS $expense)
        {
          $sub = round($expense->getSubtotal(),2);
          $hst = $labour_factor * (!$this->workorder->getHstExempt() ? $expense->getHstTotal() : 0);
          $pst = $labour_factor * ($this->settings['taxable_pst'] ? $expense->getPstTotal() : 0);
          $gst = $labour_factor * ($this->settings['taxable_gst'] ? $expense->getGstTotal() : 0);
          $totals['sections'][$title]['expenses']['subtotal'] += $sub; 
          $totals['sections'][$title]['expenses']['hst']      += $hst;
          $totals['sections'][$title]['expenses']['pst']      += $pst;
          $totals['sections'][$title]['expenses']['gst']      += $gst;
          if ($this->settings['other_detail'] != 'total')
          {
            $this_data = array($expense->getLabel(), null, null, $sub);
            if ($this->settings['customer_notes'] && $expense->getCustomerNotes()) {
              $this_data['notes'] = $expense->getCustomerNotes();
            }
            if ($this->settings['subtasks']) {
              $section_items[] = $this_data;
            } else {
              $this->uncat_items[] = $this_data;
            }
          }
        }

        //figure out what to show as additional expenses
        $additional_expenses = $total = $section->getOtherEstimate();
        if ($this->settings['other_detail'] == 'total'){
          $total = $additional_expenses + $totals['sections'][$title]['expenses']['subtotal'];
        } else {
          $total = $additional_expenses;
        }
        if ($total != 0) 
        {
          if ($this->settings['subtasks']) {
            $section_items[] = array((($additional_expenses > 0) && ($sub > 0) && ($this->settings['other_detail'] != 'total') ? 'Additional ' : '').'Estimated Expenses', null, null, $total);
          } else {
            $this->uncat_items[] = array($orig_title.' - Expenses', null, null, $total);
          }
        }

        //add to the running total
        if ($section->getOtherEstimate())
        {
          $sub = round($labour_factor * $section->getOtherEstimate(), 2);
          $hst = (!$this->workorder->getHstExempt() ? ($sub * (sfConfig::get('app_hst_rate')/100)) : 0);
          $pst = ($this->settings['taxable_pst'] ? ($sub * (sfConfig::get('app_pst_rate')/100)) : 0);
          $gst = ($this->settings['taxable_gst'] ? ($sub * (sfConfig::get('app_gst_rate')/100)) : 0);
          $totals['sections'][$title]['expenses']['subtotal'] += $sub; 
          $totals['sections'][$title]['expenses']['hst']      += $hst;
          $totals['sections'][$title]['expenses']['pst']      += $pst;
          $totals['sections'][$title]['expenses']['gst']      += $gst;
        }


        //determine if we need a page break
        //=================================
        if ($this->settings['subtasks'] && ($this->settings['show_blank'] || count($section_items) > 0))
        {
          $this->drawn_sections += 1;

          $newpage_barrier = 266;
          if (trim($section->getCustomerNotes()))
          {
            $this->SetFont('Arial', '', $this->default_fontsize);
            $idx = 0;
            $note_width = $this->page_width - 18; //accounting for extra padding that will be added = 3-1.00... on each side
            $height = $this->getStringHeight($note_width, trim($section->getcustomerNotes()));
            if ($this->GetY() + $height + 60 > $newpage_barrier)
            {
              $this->AddPage();
            }
            else
            {
              $this->ln(8);
            }
          }
          else if ($this->GetY() + 60 > $newpage_barrier)
          {
            $this->addPage();
          }
          else
          {
            $this->ln(8);
          }
          
          //draw section header
          //====================
          $this->drawSectionHeader();


          //draw notes section
          //==================
          if (trim($section->getCustomerNotes()))
          {
            $padding = $this->GetCellPaddings();
            $this->SetCellPaddings(10, 3, 10, 3);
            $this->SetAutoPageBreak(true, 15);
            $this->adding_tasknotes = true;
            $this->setFont('Arial', '', $this->default_fontsize);
            $this->SetX($this->GetX());
            $this->SetFillColor(248,252,252);
            $this->MultiCell($this->page_width, $this->default_fontsize/2, $section->getCustomerNotes(), 1, 'L', 1, 1);
            $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
            $this->SetAutoPageBreak(false);
            $this->adding_tasknotes = false;
          }

          $subtotal = $this->generateSectionSubtotal($totals, $title);
          $this->drawItemsTable($section_items, $subtotal);
        }
        unset($section_items);

        return $totals;
    }

    private function _recurse_sections($parent, $task_prefix)
    {
      $sections = array();

      $estimate = $parent->getTotalEstimate(true);
      $actual = $parent->getTotalActual();
      if (!$parent->isRoot() && ($this->settings['show_blank'] || ($estimate > 0 || $actual > 0)))
      {
        $sections['Task '.$task_prefix.': '.$parent->getLabel()] = $parent;
      }

      //step 2: recurse down into child items
      if ($parent->hasChildren())
      {
        $counter = 0;
        foreach ($parent->getChildren() AS $child)
        {
          $counter ++;
          $new_task_prefix = $task_prefix.($task_prefix == '' ? '' : '.').$counter;
          $return = $this->_recurse_sections($child, $new_task_prefix);
          if (count($return) == 0)
          {
            //$counter --;
          }
          else
          {
            foreach ($return AS $retkey => $ret)
            {
              $sections[$retkey] = $ret;
            }
          }
        }
      }

      return $sections;

    }

    //generates an ordered, flat array of sections that need to be displayed, indexed by title
    public function loadSections()
    {
      //load all billable items
      $c = new Criteria();
      $c->addJoin(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
      $c->add(WorkorderItemPeer::WORKORDER_ID, $this->workorder->getId());

      $parent = $this->workorder->getRootItem();
      $parent->getDescendants();
      $task_prefix = '';
      $sections = $this->_recurse_sections($parent, $task_prefix);

      return $sections;
    }

    public function generateSections($sections)
    {
      $totals = array();

      foreach ($sections AS $key => $section)
      {
        $numbering = explode(':', $key);
        //$totals = $this->generateSection($numbering[0], trim($numbering[1]), $section, $totals);
        $totals = $this->generateSection($numbering[0], trim($numbering[1]), $section, $totals, true);
      }

      return $totals;
    }

    public function generateSectionSubtotal($totals, $section_name)
    {
      if (isset($totals['sections']) && isset($totals['sections'][$section_name]))
      {
        $section = $totals['sections'][$section_name];
        $total_parts    = $section['parts']['subtotal'];
        $total_labour   = $section['labour']['subtotal'];
        $total_expenses = $section['expenses']['subtotal'];

        $subtotal = $total_parts + $total_labour + $total_expenses;

        return $subtotal;
      }

      return 0;
    }

    public function generateSummary($sections, $totals)
    {
      //calculate totals
      $total_parts = 0;
      $total_labour = 0;
      $total_expenses = 0;
      $total_enviro = 0;
      $total_battery = 0;
      $total_hst = 0;
      $total_pst = 0;
      $total_gst = 0;
      $total_discounts = 0;
      foreach ($totals['sections'] AS $section)
      {
        $parts_discount    = $section['discounts']['parts'];
        $parts_bill_factor = 1 - $parts_discount;
        $labour_discount   = $section['discounts']['labour'];
        $labour_bill_factor = 1 - $labour_discount;
        $total_parts     += $section['parts']['subtotal'];
        $total_labour    += $section['labour']['subtotal'];
        $total_expenses  += $section['expenses']['subtotal'];
        $total_enviro    += $parts_bill_factor * $section['parts']['enviro_levy'];
        $total_battery   += $parts_bill_factor * $section['parts']['battery_levy'];
        $total_hst       += $parts_bill_factor * ($section['parts']['hst']) + $labour_bill_factor * ($section['labour']['hst'] + $section['expenses']['hst']);
        $total_pst       += $parts_bill_factor * ($section['parts']['pst']) + $labour_bill_factor * ($section['labour']['pst'] + $section['expenses']['pst']);
        $total_gst       += $parts_bill_factor * ($section['parts']['gst']) + $labour_bill_factor * ($section['labour']['gst'] + $section['expenses']['gst']);
        $total_discounts += $parts_discount * ($section['parts']['subtotal']) + $labour_discount * ($section['labour']['subtotal'] +  $section['expenses']['subtotal']);
      }

      //add shopsupplies
      if ($this->settings['shop_supplies'])
      {
        $total_shopsupplies = round(($this->workorder->getShopSuppliesSurcharge()/100) * ($total_parts + $total_labour + $total_expenses),2);
        if ($total_shopsupplies > 0)
        {
          if (!$this->workorder->getHstExempt()) $total_hst += $total_shopsupplies * (sfconfig::get('app_hst_rate')/100);
          if ($this->settings['taxable_pst']) $total_pst += $total_shopsupplies * (sfconfig::get('app_pst_rate')/100);
          if ($this->settings['taxable_gst']) $total_gst += $total_shopsupplies * (sfconfig::get('app_gst_rate')/100);
        }
      }

      //add power & moorage
      if ($this->settings['moorage'])
      {
        $total_moorage = $this->workorder->getMoorageSurchargeAmt();
        if ($total_moorage > 0)
        {
          if (!$this->workorder->getHstExempt()) $total_hst += $total_moorage * (sfconfig::get('app_hst_rate')/100);
          if ($this->settings['taxable_pst']) $total_pst += $total_moorage * (sfconfig::get('app_pst_rate')/100);
          if ($this->settings['taxable_gst']) $total_gst += $total_moorage * (sfconfig::get('app_gst_rate')/100);
        }
      }

      //tally final totals
      $total_hst = round($total_hst,2);
      $total_pst = round($total_pst,2);
      $total_gst = round($total_gst,2);
      $total_subtotal = $total_parts + $total_labour + $total_expenses;
      $total_total = $total_parts + $total_labour + $total_expenses - $total_discounts + $total_shopsupplies + $total_moorage + $total_hst + $total_pst + $total_gst + $total_enviro + $total_battery;

      $newpage_boundary = 250;
      if ($this->GetY() > $newpage_boundary)
      {
        $this->AddPage();
      }
      else
      {
        $this->ln(8);
      }

      $start_y = $this->GetY();

      //output summary column of totals
      $totals_width = 30;
      $labels_width = $this->page_width - $totals_width;
      $this->SetLineWidth(0.2);
      $this->SetFillColor(231,239,239);
      $this->SetDrawColor(0);

      $this->SetFont("Arial", "B", $this->totals_font);
      $this->Cell($labels_width, $this->totals_font, 'Subtotal: ', 0, 0, 'R', 0);
      $this->Cell($totals_width, $this->totals_font, number_format($total_subtotal,2), 1, 1, 'R', 1);
  
      if ($total_discounts > 0 && $this->settings['show_discounts'])
      {
        $this->Cell($labels_width, $this->totals_font, $this->settings['whom'] == 'cust' ? 'Total Discounts:' : 'Customer\'s Share:', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_discounts,2), 1, 1, 'R', 1);
        $this->SetLineWidth(0.5);

        $this->SetFillColor(206,220,255);
        $this->Cell($labels_width, $this->totals_font, $this->settings['whom'] == 'cust' ? 'Discounted Subtotal:' : 'Remaining Amount:', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_subtotal - $total_discounts,2), 1, 1, 'R', 1);
        $this->ln(2);
        $this->SetFillColor(231,239,239);
      }

      $this->SetLineWidth(0.2);
      $extras = false;
      if ($total_shopsupplies > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'Shop Supplies & Misc: ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_shopsupplies,2), 1, 1, 'R', 1);
        $extras = true;
      }
      if ($total_moorage > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'Moorage & Power: ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_moorage,2), 1, 1, 'R', 1);
        $extras = true;
      }
      if ($total_enviro > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'Enviromental Fees: ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_enviro,2), 1, 1, 'R', 1);
        $extras = true;
      }
      if ($total_battery > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'Battery Levies: ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_battery,2), 1, 1, 'R', 1);
        $extras = true;
      }
      if ($total_hst > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'HST (12%): ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_hst,2), 1, 1, 'R', 1);
        $extras = true;
      }
      if ($total_pst > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'PST (7%): ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_pst,2), 1, 1, 'R', 1);
        $extras = true;
      }
      else if ($this->workorder->getPstExempt() && $this->workorder->getCustomer()->getPstNumber())
      {
        $this->SetFillColor(245);
        $this->SetFont("Arial", '', $this->totals_font);
        $this->SetX($this->GetX() + 20);
        $this->Cell(80, $this->totals_font, 'BC PST Exempted / PST Number: '.$this->workorder->getCustomer()->getPstNumber(), 0, 0, 'C', 1);
        $this->SetFont("Arial", 'B', $this->totals_font);
        $this->Cell($labels_width - 100, $this->totals_font, 'PST (7%): ', 0, 0, 'R', 1);
        $this->SetFont("Arial", '', $this->totals_font);
        $this->SetFillColor(231,239,239);
        $this->Cell($totals_width, $this->totals_font, 'N/A', 1, 1, 'R', 1);
        $this->SetFont("Arial", 'B', $this->totals_font);
        $extras = true;  
      }
      if ($total_gst > 0)
      {
        $this->Cell($labels_width, $this->totals_font, 'GST (5%): ', 0, 0, 'R', 0);
        $this->Cell($totals_width, $this->totals_font, number_format($total_gst,2), 1, 1, 'R', 1);
        $extras = true;
      }

      if ($extras)
      {
        $this->ln(2);
      }

      $this->Cell($labels_width, $this->totals_font, 'GRAND TOTAL: ', 0, 0, 'R', 0);
      $this->SetLineWidth(0.5);
      $this->SetFillColor(206,220,255);
      $this->Cell($totals_width, $this->totals_font, number_format($total_total,2), 1, 1, 'R', 1);


      //output the warning about prices and delivery time if specified:
      $new_y = $this->GetY() - 40;
      $new_y = max($new_y, $start_y + 5);
      $this->SetY($new_y);
      $this->SetFont('Arial', 'I', $this->default_fontsize);
      $this->ln(3);
      $this->Cell(100, $this->default_fontsize/2, 'Note: Prices subject to change after 30 days', 0, 1, 'C');
      if ($this->settings['delivery_time'])
      {
        $this->SetFont('Arial', '', $this->default_fontsize);
        $this->Cell(100, $this->default_fontsize, 'Delivery: '.$this->settings['delivery_time'], 0, 1, 'C');
      }

      return $total_total;
    }

    public function autoSizeTable($items, $cols)
    {
      $max_info = array();

      //go through and determine the header sizes
      $this->setFont('Arial', 'B', $this->header_fontsize);
      foreach ($cols AS $idx => $col)
      {
        //allows overriding actual header text to get similar column widths
        if (isset($col[4]))
        {
          $max_info[$idx] = array($this->GetStringWidth($col[4]),$idx,$col[2],$col[3],$col[4]);
        }
        else
        {
          $max_info[$idx] = array($this->GetStringWidth($idx),$idx,$col[2],$col[3]);
        }
      }

      //go through all the data elements one by one and check for the largest/widest size for each element
      $this->setFont('Arial', '', $this->default_fontsize);
      foreach ($items AS $item)
      {
        $colnum = 0;
        foreach ($cols AS $idx => $col)
        {
          $this->setFontSize(isset($this->small_cols[$idx]) ? $this->default_fontsize * $this->small_col_factor : $this->default_fontsize);
          $size = $this->GetStringWidth($item[$colnum]);
          if ($size > $max_info[$idx][0])
          {
            $max_info[$idx][0] = $size;
            $max_info[$idx][1] = $item[$colnum];
          }
          $colnum ++;
        }
      }

      //automatically make last column big enough to fit a large number
      $this->setFont('Arial', 'B', $this->default_fontsize);
      end($cols);
      $key = key($cols);
      $size = $this->GetStringWidth('$888,888.88');
      if ($size > $max_info[$key][0])
      {
        $max_info[$key][0] = $size;
        $max_info[$key][1] = '$888,888.88';
      }

      //perform font scaling
      return $this->calculateFontSizes($max_info);
    }

    function calculateFontSizes($columns)
    {
        //FIND APPROPRIATE FONT SIZE
        $longest_width = 0;
        foreach ($columns AS $key => $this_col)
        {
          $longest_width += $this_col[0] + $this->column_padding;
        }

        $max_width = $this->page_width - $this->row_indent;

        $this->setFont('Arial', '', $this->default_fontsize);
        if ($longest_width > $max_width) //decrease font size until we go under, then stay
        { 
            $test_width = $longest_width;
            $test_fontsize = $this->default_fontsize;
            while ($test_width > $max_width)
            {
                $test_width = 0;
                $test_fontsize -= 0.5;
                $all_headers_are_biggest = true;
                foreach ($columns AS $key => $this_col)
                {
                    $this->setFont('Arial', 'B', $this->header_fontsize);
                    $header_width = $this->GetStringWidth((isset($this_col[4]) ? $this_col[4] : $key));
                    if ($header_width == 3) $header_width = 0;
                    $this->setFont('Arial', '', (isset($this->small_cols[$key]) ? $test_fontsize * $this->small_col_factor : $test_fontsize));
                    $text_width = $this->GetStringWidth($this_col[1]);
                    if ($header_width > $text_width)
                    {
                        $test_width += $header_width;
                    }
                    else
                    {
                        $all_headers_are_biggest = false;
                        $test_width += $text_width;
                    }
                    if ($text_width != 0 || $header_width != 0) $test_width += $this->column_padding;
                }
                if ($all_headers_are_biggest)
                {
                    $this->header_fontsize -= 0.5;
                }
            }
            $final_fontsize = $test_fontsize;
            $final_width = $test_width;
        }
        else 
        {
          $final_fontsize = $this->default_fontsize;
          $final_width = $longest_width;
        }

        //echo "<br><br>final width = ".$final_width.", final fontsize = ".$final_fontsize;
        //fix address column to be wider to full width of page
        $expand_amt = $max_width - $final_width;

        //get final column widths
        $first_col = true;
        $final_cols = array();
        foreach ($columns AS $key => $this_col)
        {
            $this->setFont('Arial', 'B', $this->header_fontsize);
            $header_width = $this->GetStringWidth((isset($this_col[4]) ? $this_col[4] : $key));
            if ($header_width == 3) $header_width = 0;
            $col_fontsize = (isset($this->small_cols[$key]) ? $final_fontsize * $this->small_col_factor : $final_fontsize);
            $this->setFont('Arial', '', $col_fontsize);
            $text_width = $this->GetStringWidth($this_col[1]);
            if ($header_width > $text_width)
            {
                $test_width = $header_width;
            }
            else
            {
                $all_headers_are_biggest = false;
                $test_width = $text_width;
            }
            if ($first_col) 
            {
              $test_width += $expand_amt;
              $first_col = false;
            }
            if ($test_width > 0) $test_width += $this->column_padding;
            $final_cols[$key] = array($test_width, $col_fontsize, $columns[$key][2], $columns[$key][3]);
        }

        return $final_cols;
    }

    public function generateGeneralSection()
    {
      if (count($this->uncat_items) > 0)
      {
          //output a header to separate this from other sections if needed
          $this->current_section_title = ($this->drawn_sections > 0 ? 'Other Tasks' : '');
          $newpage_barrier = 266;
          if ($this->GetY() + 60 > $newpage_barrier)
          {
            $this->addPage();
          }
          else
          {
            $this->ln(8);
          }          
          $this->drawSectionHeader();


          //draw the table
          $this->drawItemsTable($this->uncat_items);
      }
    }

    public function generate()
    {
        set_time_limit(0);

        //add the introduction page (only done for customer payers)
        $this->AddPage();
        $this->generateIntro();

        $sections = $this->loadSections();

        //populate the section data based on the requested information
        $totals = $this->generateSections($sections);

        //output the rest of the stuff
        $this->generateGeneralSection();

        //include the summary page
        $final_total = $this->generateSummary($sections, $totals);
    }

}
