<?php

class WorkorderPDF extends sfTCPDF
{
    var $workorder;
    var $settings;

    var $labourtypes;

    var $current_section_title;
    var $current_section_number;

    var $cols;
    var $title_font = 20;
    var $subtitle_font = 8;
    var $section_font = 12;
    var $totals_font = 9;
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

    function generateLogoHeader($append = null)
    {
      $page_top = $this->GetY();
      $title = ($this->workorder->getStatus() == 'Completed' ? 'INVOICE' : 'WORK ORDER').' #'.$this->workorder->getId().$append;

      if($this->workorder->getDivision() == '1'){
          //insert logo
        $this->Image(sfConfig::get('sf_web_dir').'/images/invoice_header.jpg', $page_top + 4, $page_top, 62, 18);
      }else{
        $this->Image(sfConfig::get('sf_web_dir').'/images/ELITELOGO.jpeg', $page_top + 4, $page_top, 62, 18);
      }
      

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
      $workorder_data[] = array('Workorder Number:',$this->workorder->getId());
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
      $this->Cell(80, 4, 'Workorder Information', 1, 2, 'C', 1);
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

      $this->ln(5);

      //if necessary, output who this invoice/statement is for
      if ($this->settings['whom'] != 'cust')
      {
        $whom = $this->settings['whom'];
        if ($whom == 'inhouse')
        {
          $prepared_for = 'In House / Delta Marine Discounts & Warranty';
        }
        else if (substr($whom, 0, 2) == 's_')
        {
          $s_id = substr($whom, 2);
          if ($sup = SupplierPeer::retrieveByPk($s_id))
          {
            $prepared_for = $sup->getName();
          }
          else
          {
            $prepared_for = 'Unknown Supplier (ID '.$s_id.')';
          }
        }
        else if (substr($whom, 0, 2) == 'm_')
        {
          $m_id = substr($whom, 2);
          if ($man = ManufacturerPeer::retrieveByPk($m_id))
          {
            $prepared_for = $man->getName();
          }
          else
          {
            $prepared_for = 'Unknown Manufacturer (ID '.$m_id.')';
          }
        }

        $this->SetFillColor(239,138,121);
        $this->SetDrawColor(239,138,121);
        $this->setX(20);
        $this->setFont('Arial', 'B', $this->subtitle_font);
        $this->Cell(175, 4, 'Statement Prepared For', 1, 2, 'C', 1);
        $padding = $this->GetCellPaddings();
        $this->SetCellPadding(3);
        $this->MultiCell(175, $this->subtitle_font/2.5, $prepared_for, 1, 'C', 0, 1);
        $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);

        $this->ln(5);
      }


      //INSERT CUSTOMER NOTES
      if ($this->settings['tasks_notes'])
      {
        $this->SetFillColor(231,239,239);
        $this->SetDrawColor(231,239,239);
        $this->setX(20);
        $this->setFont('Arial', 'B', $this->subtitle_font);
        $this->Cell(175, 4, 'Workorder Notes', 1, 2, 'C', 1);
        $notes = $this->workorder->getCustomerNotes() ? $this->workorder->getCustomerNotes() : 'N/A';
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
        $this->Cell(175, 4, 'Workorder Notes (cont\'d)', 1, 2, 'C', 1);
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
        $this->Cell($this->page_width/3, 5, 'Work Order #'.$this->workorder->getId(), 0, 0, 'C');
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

    function drawRow($data, $colwidths, $notes = null, $blue = true, $fullnotes = true, $highlight = false)
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
        if ($highlight)
        {
          $this->SetTextColor(180,0,0);
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
            $val = (is_numeric($data[$idx]) ? (float) str_replace(',','', str_replace('$', '', $data[$idx])) : $data[$idx]);
            if ($val == 0 && ($idx != count($colwidths) - 1))
            {
              $text = ' - ';
              $align = 'R';
            }
            else if (is_numeric($val))
            {
              $text = ' '.number_format($val,2); //forcing to a string, and then casting to float will strip trailing zeroes, then this adds 'em back
            }
            else
            {
              $text = $val;
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
          $this->SetTextColor(100);
          $padding = $this->GetCellPaddings();
          $this->SetCellPaddings(5, $padding['T'],$padding['R'],$padding['B']);
          $this->SetFont('Arial', '', $this->default_fontsize - 1);
          $this->SetX($this->GetX() + $this->row_indent);
          $this->MultiCell($note_width, $note_height, $notes, 'LBR', 'L', 1, 1);
          $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
        }

        $this->SetTextColor(0);
    }

    function drawTableFooter($totals, $colwidths, $type)
    {
      end($colwidths);
      $totals_width = current($colwidths);
      $labels_width = $this->page_width - $totals_width[0];

      $extras = array('subtotal' => 'Subtotal');
      foreach ($extras AS $idx => $label)
      {
          $this->SetFillColor(255);
          $this->SetFont("Arial", "B", $this->default_fontsize);
          $this->SetDrawColor(0);
          $this->Cell($labels_width, $this->default_fontsize/1.5, $this->current_section_number.' '.$type.' '.$label.':', 0, 0, 'R', 1);
          ($idx == 'total' ? $this->SetLineWidth(0.5) : $this->SetLineWidth(0.2));
          ($idx == 'total' ? $this->SetFillColor(206, 220, 255) : $this->SetFillColor(221, 235, 255));
          $this->Cell(0, $this->default_fontsize/1.5, number_format($totals[$idx],2), 1, 1, 'R', 1);
      }
    }//drawTableFooter()-------------------------------------------------------



    function drawPartsTable($parts, $totals, $nodetail)
    {
      //SET UP COLUMNS
      $short = false;
      if (!$nodetail && ($this->settings['parts_detail'] == 'all' || $this->settings['parts_detail'] == 'value'))
      {
        $colwidths = array('PARTS'        => array(0,0,'L','text'), 
                           'SKU'          => array(0,0,'C','text'), 
                           'Quantity'     => array(0,0,'C','number'), 
                           'Unit Price'   => array(0,0,'R','money'), 
                           'Price'        => array(0,0,'R','money'));
      }
      else
      {
        $colwidths = array('PARTS'        => array(0,0,'L','text'), 
                           'Price'        => array(0,0,'R','money'));
        $short = true;
      }
      if ($this->settings['origin'])
      {
        $colwidths['Country of Origin'] = array(0,0,'C', 'text');
      }
      $colwidths = $this->autoSizeTable($parts, $colwidths);

      //OUTPUT DATA
      if (count($parts) > 0 || ($this->settings['parts_detail'] == 'none' && ($totals['subtotal'] != 0)))
      {
        $this->drawTableHeader($colwidths);
        if (count($parts) > 0)
        {
          foreach ($parts AS $part)
          {
              $this->drawRow(($short ? array($part[0],$part[4]) : $part), $colwidths, null, true, true, $part[6]);
          }
        }
        else
        {
          $this->drawRow(array('Parts Total', $totals['subtotal']), $colwidths);
        }
      }
    }//drawPartsTable()--------------------------------------------------------


    function drawLabourTable($labours, $totals, $nodetail)
    {
      $short = false;
      if (!$nodetail && $this->settings['labour_detail'] != 'none')
      {
        $colwidths = array('LABOUR'  => array(0,0,'L','text'), 
                           'Hours'   => array(0,0,'C','text','Quantity'), 
                           'Rate'    => array(0,0,'R','money','Unit Price'), 
                           'Price'   => array(0,0,'R','money'));
      }
      else
      {
        $colwidths = array('LABOUR'  => array(0,0,'L','text'), 
                           'Price'   => array(0,0,'R','money'));
        $short = true;
      }
      $colwidths = $this->autoSizeTable($labours, $colwidths);

      if (count($labours) > 0 || ($this->settings['labour_detail'] == 'none' && ($totals['subtotal'] != 0)))
      {
        $this->drawTableHeader($colwidths);
        if (count($labours) > 0)
        {
          foreach ($labours AS $labour)
          {
            $notes = (!$short) ? $labour[4] : '';
            $this->drawRow(($short ? array($labour[0], $labour[3]) : $labour), $colwidths, $notes, true, false, $labour[5]);
          }
        }
        else
        {
          $this->drawRow(array('Labour Total', $totals['subtotal']), $colwidths);
        }
      }
    }//drawLabourTable()-------------------------------------------------------



    function drawExpensesTable($expenses, $totals)
    {
      $colwidths = array('EXPENSES'        => array(0,0,'L','text'), 
                         'Price'           => array(0,0,'R','money'));
      if ($this->settings['origin'])
      {
        $colwidths['Country of Origin'] = array(0,0,'C', 'text');
      }
      $colwidths = $this->autoSizeTable($expenses, $colwidths);

      if (count($expenses) > 0 || ($this->settings['expense_detail'] == 'none' && ($totals['subtotal'] != 0)))
      {
        $this->drawTableHeader($colwidths);
        if (count($expenses) > 0)
        {
          foreach ($expenses AS $expense)
          {
            $this->drawRow($expense, $colwidths, $expense[3], true, true, $expense[4]);
          }
        }
        else
        {
          $this->drawRow(array('Expenses Total', $totals['subtotal']), $colwidths);
        }
      }
    }//drawExpensesTable()-----------------------------------------------------
  
    function drawSubtotals($number, $totals, $progress_totals, $paid = 0)
    {
        $newpage_barrier = 266;
        $start_x = 43;
        $box_width = 30;
        $row_height = 8;

        //check for discount
        $has_discount = ($totals['discounts']['parts'] > 0 || $totals['discounts']['labour'] > 0);
        if ($has_discount) $start_x -= 12;
        if ($paid > 0) 
        {
          $box_width -= 5;
          $start_x -= 8;
        }

        if ($progress_totals['subtotal'] != 0)
        {
          $draw_progress = true;
          $height = (2 * $row_height) + 4 + 4;
          $label_width = $start_x - $this->GetX();
          $start_x = $this->GetX();
          $operand_height = 2 * $row_height;
        }
        else
        {
          $label_width = 0;
          $draw_progress = false;
          $height = $row_height + 4 + 4;
          $operand_height = $row_height;
        }

        if (($this->GetY() + $height) > $newpage_barrier)
        {
          $this->addPage();
          $this->drawSectionHeader(true);
        }

        //draw the background
        $this->Rect($this->GetX(), $this->GetY(), $this->page_width, $height, 'F', false, array(231,239,239));

        //draw subtotals text
        $start_y = $this->GetY() + 2;
        $this->setFont('Arial', '', $this->default_fontsize);
        $this->SetDrawColor(240);  

        //set up the column headers
        $this->SetFillColor(240);
        $this->SetXY($start_x + $label_width, $start_y);
        $this->Cell($box_width, 4, 'Parts', 1, 2, 'C', 1);
        $this->SetX($this->GetX() + $box_width);
        $this->Cell(5, $operand_height, '+', 0, 0, 'C', 0);
        $this->SetY($start_y, false);
        $this->Cell($box_width, 4, 'Labour', 1, 2, 'C', 1);
        $this->SetX($this->GetX() + $box_width);
        $this->Cell(5, $operand_height, '+', 0, 0, 'C', 0);
        $this->SetY($start_y, false);
        $this->Cell($box_width, 4, 'Expenses', 1, 2, 'C', 1);
        if ($has_discount && $this->settings['show_discounts'])
        {
            $this->SetX($this->GetX() + $box_width);
            $this->Cell(5, $operand_height, '-', 0, 0, 'C', 0);
            $this->SetY($start_y, false);
            $this->Cell($box_width, 4, $this->settings['whom'] == 'cust' ? 'Discounts' : 'Customer\'s Share', 1, 2, 'C', 1);
        }
        $this->SetX($this->GetX() + $box_width);
        $this->Cell(5, $operand_height, '=', 0, 0, 'C', 0);
        $this->SetY($start_y, false);
        $this->SetLineWidth(0.5);
        $this->setFont('Arial', 'B', $this->default_fontsize);
        $this->Cell($box_width, 3.7, $number." Subtotal", 1, 2, 'C', 1);
        $this->setFont('Arial', '', $this->default_fontsize);

        $this->SetX($start_x);
        $this->SetFillColor(255);
        $this->SetLineWidth(0.2);

        //draw the first line of stuff
        if ($draw_progress)
        {
          $total = 0;

          $label = $this->settings['progress_title'];
          $this->Cell($label_width, $row_height, $label, 0, 0, 'C', 0);

          //draw parts
          $text = $progress_totals['parts'];
          $total += $text;
          $text = ($text == 0 ? ' - ' : number_format($text, 2));
          $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
          $this->SetX($this->GetX() + 5);

          //draw labour
          $text = $progress_totals['labour'];
          $total += $text;
          $text = ($text == 0 ? ' - ' : number_format($text, 2));
          $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
          $this->SetX($this->GetX() + 5);

          //draw expenses
          $text = $progress_totals['expenses'];
          $total += $text;
          $text = ($text == 0 ? ' - ' : number_format($text, 2));
          $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
          $this->SetX($this->GetX() + 5);

          if ($has_discount && $this->settings['show_discounts'])
          {
            $discount = round($totals['discounts']['parts'] * $progress_totals['parts'], 2);
            $discount += round($totals['discounts']['labour'] * $progress_totals['labour'], 2);
            $discount += round($totals['discounts']['labour'] * $progress_totals['expenses'], 2);
            $total -= $discount;
            $text = ($discount == 0 ? ' - ' : number_format($discount, 2));
            $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
            $this->SetX($this->GetX() + 5);
          }

          //draw total
          $text = number_format($total, 2);
          $this->SetLineWidth(0.5);
          $this->setFont('Arial', 'B', $this->default_fontsize);
          $this->Cell($box_width, $row_height - 0.3, $text, 1, 2, 'C', 1);
          $this->SetLineWidth(0.2);

          $this->SetX($start_x);
          $this->setFont('Arial', '', $this->default_fontsize);
          $label = 'Task Total to Date';
          $this->Cell($label_width, $row_height, $label, 0, 0, 'C', 0);
        }

        $total = 0;

        //draw parts
        $text = $totals['parts']['subtotal'];
        $total += $text;
        $text = ($text == 0 ? ' - ' : number_format($text, 2));
        $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
        $this->SetX($this->GetX() + 5);

        //draw labour
        $text = $totals['labour']['subtotal'];
        $total += $text;
        $text = ($text == 0 ? ' - ' : number_format($text, 2));
        $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
        $this->SetX($this->GetX() + 5);

        //draw expenses
        $text = $totals['expenses']['subtotal'];
        $total += $text;
        $text = ($text == 0 ? ' - ' : number_format($text, 2));
        $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
        $this->SetX($this->GetX() + 5);

        if ($has_discount && $this->settings['show_discounts'])
        {
          $discount = round($totals['discounts']['parts'] * $totals['parts']['subtotal'], 2);
          $discount += round($totals['discounts']['labour'] * $totals['labour']['subtotal'], 2);
          $discount += round($totals['discounts']['labour'] * $totals['expenses']['subtotal'], 2);
          $total -= $discount;
          $text = number_format($discount, 2);
          $this->Cell($box_width, $row_height, $text, 1, 0, 'C', 1);
          $this->SetX($this->GetX() + 5);
        }

        //draw total
        $this->SetLineWidth(0.5);
        $this->setFont('Arial', 'B', $this->default_fontsize);
        $text = number_format($total, 2);
        $this->Cell($box_width, $row_height, $text, 1, ($paid > 0 ? 0 : 1), 'C', 1);
        $this->SetLineWidth(0.2);

        //draw paid
        if ($paid > 0)
        {
          $this->Cell(15, 9, '', 0, 0, 'C', 0);
          $this->SetY($start_y + 0.2, false);
          $this->SetFillColor(240);
          $this->SetLineWidth(0.5);
          $this->setFont('Arial', 'B', $this->default_fontsize);
          $this->Cell($box_width, 3.7, "Paid", 1, 2, 'C', 1);
          $text = number_format($paid, 2);
          $this->SetFillColor(255);
          $this->Cell($box_width, $row_height - 0.3, $text, 1, 1, 'C', 1);
          $this->SetLineWidth(0.2);
        }
    }

    function drawSectionHeader($continued = false)
    {
        $this->SetFont("Arial", "B", $this->section_font);
        $this->SetFillColor(231,239,239);
        $this->SetDrawColor(231,239,239);
        $this->SetTextColor(0);
        $this->Cell(0, $this->section_font/1.8, ' '.$this->current_section_title.($continued ? ' (Cont\'d)' : ''), 1, 1, 'L', 1);
        $this->SetFillColor(255);
        $this->SetFont("Arial", "", $this->default_fontsize);
    }//drawSectionHeader()-----------------------------------------------------

    //OUTPUTS the various bits for a particular section (workorder item)
    function generateSection($number, $title, $section, $totals, $billable, $invoice_dates)
    {
        $this->current_section_title = $title;
        $this->current_section_number = $number;

        $new_subtotals = array('parts' => 0, 'labour' => 0, 'expenses' => 0, 'subtotal' => 0);

        //determine if we need a page break
        //=================================
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
            $this->ln(10);
          }
        }
        else if ($this->GetY() + 60 > $newpage_barrier)
        {
          $this->addPage();
        }
        else
        {
          $this->ln(10);
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



        //CALCULATE DISCOUNTS/BILLABLE
        //==================================
        $parts_billable_factor = 0;
        $labour_billable_factor = 0;
        if ($billable)
        {
          if ($this->settings['whom'] == 'cust')
          {
            $parts_billable_factor = ($billable->getCustomerPartsPercent() / 100);
            $labour_billable_factor = ($billable->getCustomerLabourPercent() / 100);
          }
          else if ($this->settings['whom'] == 'inhouse')
          {
            $parts_billable_factor = ($billable->getInHousePartsPercent() / 100);
            $labour_billable_factor = ($billable->getInHouseLabourPercent() / 100);
          }
          else if (substr($this->settings['whom'], 0, 2) == 's_')
          {
            if (substr($this->settings['whom'], 2) == $billable->getSupplierId())
            {
              $parts_billable_factor = ($billable->getSupplierPartsPercent() / 100);
              $labour_billable_factor = ($billable->getSupplierLabourPercent() / 100);
            }
          }
          else if (substr($this->settings['whom'], 0, 2) == 'm_')
          {
            if (substr($this->settings['whom'], 2) == $billable->getManufacturerId())
            {
              $parts_billable_factor = ($billable->getManufacturerPartsPercent() / 100);
              $labour_billable_factor = ($billable->getManufacturerLabourPercent() / 100);
            }
          }
        }
        else
        {
          $parts_billable_factor = ($this->settings['whom'] == 'cust' ? 1 : 0);
          $labour_billable_factor = ($this->settings['whom'] == 'cust' ? 1 : 0);
        }
        if ($this->settings['show_discounts'])
        {
          $totals['sections'][$title]['discounts'] = array('parts' => 1 - $parts_billable_factor, 'labour' => 1 - $labour_billable_factor);
        }
        $part_factor = $this->settings['show_discounts'] ? 1 : $parts_billable_factor;
        $labour_factor = $this->settings['show_discounts'] ? 1 : $labour_billable_factor;

        //add parts
        //===================================
        $parts_c = new Criteria();
        $parts_c->add(PartInstancePeer::WORKORDER_ITEM_ID, $section->getId());
        $parts_c->add(PartInstancePeer::ALLOCATED, true);
        $parts_c->addAscendingOrderByColumn(PartPeer::NAME);
        $parts = PartInstancePeer::doSelectJoinPart($parts_c);

        //loop through parts once to get category totals and combine duplicate parts
        $compiled_parts = array();

        foreach ($parts AS $part)
        {
          list($skip, $is_new) = $this->_is_new($part, $invoice_dates);
          if ($skip) continue;

          //sfContext::getInstance()->getLogger()->info('start: ');

          $sub  = round($part_factor * $part->getSubtotal(false), 2);
          $hst  = $part_factor * (!$this->workorder->getHstExempt() ? $part->getHstTotal(false) : 0);

          //sfContext::getInstance()->getLogger()->info('start: 1');
          $pst  = $part_factor * ($this->settings['taxable_pst'] ? $part->getPstTotal(false) : 0);
          //sfContext::getInstance()->getLogger()->info('start: 2');

          $gst  = $part_factor * ($this->settings['taxable_gst'] ? $part->getGstTotal(false) : 0);
          $batt = round($part_factor * $part->getBatteryLevyTotal(false), 2);
          $env  = round($part_factor * $part->getEnviroLevyTotal(false), 2);
          $envpst = 0.00;

          //Add PST for the enviro levy if the Workorder default is set to charge PST, 
          //or if the Part is overriden to charge PST
          if (!$this->workorder->getPstExempt() || $part->getEnviroTaxableFlg() == 'Y')
          {
            $envpst = $env * ($this->settings['taxable_gst'] ? sfConfig::get('app_pst_rate')/100 : 0);
            //PST for enviro levy is already included the parts taxes if required.
            //$pst = $pst + $envpst;
          }

          //sfContext::getInstance()->getLogger()->info('Sub: '.$sub);
          //sfContext::getInstance()->getLogger()->info('PST: '.$pst);
          //sfContext::getInstance()->getLogger()->info('GST: '.$gst);
          //sfContext::getInstance()->getLogger()->info('Enviro: '.$env);
          //sfContext::getInstance()->getLogger()->info('Batt: '.$batt);

          //sfContext::getInstance()->getLogger()->info('pst total: '.$part->getPstTotal(false));

          //sfContext::getInstance()->getLogger()->info('Enviro Taxable Flg: '.$part->getEnviroTaxableFlg());
          //sfContext::getInstance()->getLogger()->info('Enviro PST: '.$envpst);
          //sfContext::getInstance()->getLogger()->info('PST: '.$pst);

        

          $totals['sections'][$title]['parts']['subtotal']     += $sub; 
          $totals['sections'][$title]['parts']['hst']          += $hst;
          $totals['sections'][$title]['parts']['pst']          += $pst;
          $totals['sections'][$title]['parts']['gst']          += $gst;
          $totals['sections'][$title]['parts']['battery_levy'] += $batt;
          $totals['sections'][$title]['parts']['enviro_levy']  += $env;

          if ($is_new)
          {
            $new_subtotals['parts'] += $sub;
            $new_subtotals['subtotal'] += $sub;
          }

          $origin = null;
          if ($this->settings['origin'])
          {
            $origin = ($part->getPartVariantId() ? $part->getPartVariant()->getPart()->getOrigin() : $part->getCustomOrigin());
          }
          if ($this->settings['summary_parts'])
          {
            if (!isset($totals['parts_summary'])) $totals['parts_summary'] = array();
            $id = ($part->getPartVariantId() ? $part->getPartVariant()->getPart()->getPartCategoryId(): null);
            if (!$id) $id = 'uncat';
            if (!isset($totals['parts_summary'][$id])) $totals['parts_summary'][$id] = 0;
            $totals['parts_summary'][$id] += $sub;
          }

          $compiled_idx = ($is_new ? 'z_' : 'o_').$part->__toString().($part->getPartVariantId() ? $part->getPartVariantId().$part->getSerialNumber() : 'custom'.$part->getId());
          if (isset($compiled_parts[$compiled_idx]))
          {
            $compiled_parts[$compiled_idx][2] += $part->getQuantity();
            $compiled_parts[$compiled_idx][4] += $sub; 

            if ($compiled_parts[$compiled_idx][3] != $part->getUnitPrice())
            {
              //calculate average price
              $compiled_parts[$compiled_idx][3] = round($compiled_parts[$compiled_idx][4] / $compiled_parts[$compiled_idx][2], 2);
            }
          }
          else
          {
            $label = $part->__toString().($part->getSerialNumber() ? ' ('.$part->getSerialNumber().')' : '');
            $compiled_parts[$compiled_idx] = array($label,
                                                   ($part->getPartVariantId() ? $part->getPartVariant()->getInternalSku() : null),
                                                   $part->getQuantity(),
                                                   round($part_factor * $part->getUnitPrice(), 2),
                                                   $sub,
                                                   $origin,
                                                   $is_new);
          }
        }

        $no_parts_detail = false;
        $parts_list = array();
        if ($this->settings['parts_detail'] == 'all')
        {
          ksort($compiled_parts);
          $parts_list = array_values($compiled_parts);
          unset($compiled_parts, $parts, $part);
        }
        else if ($this->settings['parts_detail'] == 'cat')
        {
          //add all items by category
          $cats = array();
          $uncat = 0;
          foreach ($parts AS $part)
          {
            $obj = ($part->getPartVariantId() ? $part->getPartVariant()->getPart() : null);
            if ($obj && $obj->getPartCategory())
            {
              $catname = $obj->getPartCategory()->getName();
              if (!isset($cats[$catname])) $cats[$catname] = 0;
              $cats[$catname] += round($part_factor * $part->getSubtotal(false), 2);
            }
            else
            {
              $uncat += $sub; 
            }
          }
          ksort($cats);

          //output the category listings
          foreach ($cats AS $label => $cat)
          {
            $parts_list[] = array($label, $cat);
          }
          if ($uncat > 0)
          {
            $parts_list[] = array('Uncategorized Parts', $uncat);
          }

          //clean up
          unset($parts, $part, $cats, $cat);
        }
        else if ($this->settings['parts_detail'] == 'value')
        {
          $otherval = 0;
          //add other items as per regular
          foreach ($compiled_parts AS $part)
          {
            if ($part[4] < $this->settings['parts_minvalue'])
            {
              $otherval += $part[4];
            }
            else
            {
              $parts_list[] = $part;
            }
          }

          //add other items as a single lineitem
          if ($otherval != 0)
          {
            $parts_list[] = array('Other Parts', null, null, null, $otherval);
          }
          unset($compiled_parts, $parts, $part, $otherval);
        }
        else
        {
          //show no detail, just add everything up
          $no_parts_detail = true;
        }

        $this->drawPartsTable($parts_list, $totals['sections'][$title]['parts'], $no_parts_detail);
        unset($parts_list);


        //add labour
        //===================================
        $labour_list = array();
        
        $labour_c = new Criteria();
        $labour_c->add(TimelogPeer::WORKORDER_ITEM_ID, $section->getId());
        $labour_c->add(TimelogPeer::APPROVED, true);
        $labour_c->add(TimelogPeer::ESTIMATE, false);
        $labour_c->add(TimelogPeer::BILLABLE_HOURS, 0, Criteria::GREATER_THAN);
        $labours = TimelogPeer::doSelect($labour_c);

        //loop through timelogs once to get category totals
        foreach ($labours AS $labour)
        {
          $id = $labour->getLabourTypeId() ? $labour->getLabourTypeId() : $labour->getCustomLabel();

          list($skip, $is_new) = $this->_is_new($labour, $invoice_dates);
          if ($skip) continue;

          $sub = round($labour_factor * $labour->getSubtotal(), 2);
          $hst = $labour_factor * (!$this->workorder->getHstExempt() ? $labour->getHstTotal() : 0);
          
          //PST on labour when full TAX is chagred - i.e. both PST and GST are charged
          $pst = $labour_factor * (($this->settings['taxable_pst'] && !$this->workorder->getPstExempt() && !$this->workorder->getGstExempt() ) ? $labour->getPstTotal() : 0);

          $gst = $labour_factor * ($this->settings['taxable_gst'] ? $labour->getGstTotal() : 0);
          
          $totals['sections'][$title]['labour']['subtotal'] += $sub; 
          $totals['sections'][$title]['labour']['hst']      += $hst;
          $totals['sections'][$title]['labour']['pst']      += $pst;
          $totals['sections'][$title]['labour']['gst']      += $gst;

          if ($is_new)
          {
            $new_subtotals['labour'] += $sub;
            $new_subtotals['subtotal'] += $sub;
          }

          if ($this->settings['summary_labour'])
          {
            if (!isset($totals['labour_summary'])) $totals['labour_summary'] = array();
            if (!isset($totals['labour_summary'][$id])) $totals['labour_summary'][$id] = array('hours' => 0, 'total' => 0, 'ratemin' => 0, 'ratemax' => 0);
            $totals['labour_summary'][$id]['hours'] += $labour->getBillableHours();
            $totals['labour_summary'][$id]['total'] += $sub; 
            $totals['labour_summary'][$id]['ratemin'] = min($totals['labour_summary'][$id]['ratemin'] == 0 ? 1000 : $totals['labour_summary'][$id]['ratemin'], $labour->getRate());
            $totals['labour_summary'][$id]['ratemax'] = max($totals['labour_summary'][$id]['ratemax'], $labour->getRate());
          }
        }

        $no_labour_detail = false;
        $labour_list = array();
        if ($this->settings['labour_detail'] == 'all' || $this->settings['labour_detail'] == 'allnotes') 
        {
          //add all items regularly
          foreach ($labours AS $labour)
          {
            if ($labour->getLabourTypeId())
            {
              $id = $labour->getLabourTypeId();
              $label = $this->labourtypes[$id]['name'];
            }
            else
            {
              $id = $labour->getCustomLabel();
              $label = $labour->getCustomLabel();
            }

            list($skip, $is_new) = $this->_is_new($labour, $invoice_dates);
            if ($skip) continue;

            $id = ($is_new ? 'z_' : 'o_').$id.$labour->getId();

            $labour_list[$id] = array($label,
                                   (float) $labour->getBillableHours(),
                                   round($labour_factor * $labour->getRate(), 2),
                                   round($labour_factor * $labour->getSubtotal(), 2),
                                   ($this->settings['labour_detail'] == 'allnotes' ? $labour->getEmployeeNotes() : ''),
                                   $is_new);

          }

          ksort($labour_list);
          $labour_list = array_values($labour_list);

          //clean up
          unset($labours, $labour);
        }
        else if ($this->settings['labour_detail'] == 'cat')
        {
          //add all items by category
          $cats = array();
          $uncat = 0;
          foreach ($labours AS $labour)
          {
            list($skip, $is_new) = $this->_is_new($labour, $invoice_dates);
            if ($skip) continue;

            $cat_id = $labour->getLabourTypeId() ? $labour->getLabourTypeId() : $labour->getCustomLabel();
            $id = ($is_new ? 'z_' : 'o_').$cat_id;
            if (!isset($cats[$id]))
            {
              $cats[$id] = array('cat_id' => $cat_id, 'hours' => 0, 'total' => 0, 'ratemin' => 0, 'ratemax' => 0, 'is_new' => $is_new);
            }
            $cats[$id]['total'] += round($labour_factor * $labour->getSubtotal(), 2);
            $cats[$id]['hours'] += $labour->getBillableHours();
            $cats[$id]['ratemin'] = min($cats[$id]['ratemin'] == 0 ? 1000 : $cats[$id]['ratemin'], $labour->getRate());
            $cats[$id]['ratemax'] = max($cats[$id]['ratemax'], $labour->getRate());
          }

          ksort($cats);

          //output the category listings
          foreach ($cats AS $id => $cat)
          {
            $name = (is_int($cat['cat_id'])) ? $this->labourtypes[$cat['cat_id']]['name'] : $cat_id;
            $rate = $cat['ratemin'];
            
            if ($cat['ratemin'] != $cat['ratemax'])
            {
              $rate .= '-'.$cat['ratemax'];
            }
            $labour_list[] = array($name, $cat['hours'], $rate, $cat['total'], '', $cat['is_new']);
          }

          //clean up
          unset($labours, $labour, $cats, $cat, $catname);
        }
        else
        {
          //show no detail, just add everything up
          $no_labour_detail = true;
        }

        $this->drawLabourTable($labour_list, $totals['sections'][$title]['labour'], $no_labour_detail);
        unset($labour_list);


        //add expenses
        //===================================
        $expenses_list = array();

        $expense_c = new Criteria();
        $expense_c->add(WorkorderExpensePeer::WORKORDER_ITEM_ID, $section->getId());
        $expense_c->add(WorkorderExpensePeer::INVOICE, true);
        $expenses = WorkorderExpensePeer::doSelect($expense_c);

        //loop through expenses once to get category totals
        foreach ($expenses AS $expense)
        {
          list($skip, $is_new) = $this->_is_new($expense, $invoice_dates);
          if ($skip) continue;

          $sub = round($labour_factor * $expense->getSubtotal(),2);
          $hst = $labour_factor * (!$this->workorder->getHstExempt() ? $expense->getHstTotal() : 0);
          $pst = $labour_factor * ($this->settings['taxable_pst'] ? $expense->getPstTotal() : 0);
          $gst = $labour_factor * ($this->settings['taxable_gst'] ? $expense->getGstTotal() : 0);
          $totals['sections'][$title]['expenses']['subtotal'] += $sub; 
          $totals['sections'][$title]['expenses']['hst']      += $hst;
          $totals['sections'][$title]['expenses']['pst']      += $pst;
          $totals['sections'][$title]['expenses']['gst']      += $gst;

          if ($is_new)
          {
            $new_subtotals['expenses'] += $sub;
            $new_subtotals['subtotal'] += $sub;
          }
        }

        $no_expense_detail = false;
        $expense_list = array();
        if ($this->settings['expense_detail'] == 'all' || $this->settings['expense_detail'] == 'allnotes')
        {
          //add all items regularly
          foreach ($expenses AS $expense)
          {
            list($skip, $is_new) = $this->_is_new($expense, $invoice_dates);
            if ($skip) continue;

            $id = ($is_new ? 'z_' : 'o_').$expense->getId();
            $expense_list[$id] = array(
              $expense->getLabel(), 
              round($labour_factor * $expense->getSubtotal(), 2),
              ($this->settings['origin']) ? $expense->getOrigin() : '',
              ($this->settings['expense_detail'] == 'allnotes') ? $expense->getCustomerNotes() : '',
              $is_new
            );

            ksort($expense_list);
          }

          //clean up
          unset($expenses, $expense);
        }
        else
        {
          //show no detail, just add everything up
          $no_expense_detail = true;
        }

        $this->drawExpensesTable($expense_list, $totals['sections'][$title]['expenses']);
        unset($expense_list);

        //output task subtotals
        $this->drawSubtotals($number, $totals['sections'][$title], $new_subtotals, $section->getAmountPaid());

        return $totals;
    }//generateSection()-------------------------------------------------------

    private function _recurse_sections($parent, $task_prefix)
    {
      $sections = array();

      //Step 1: add this item
      if (!$parent->isRoot())
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
          foreach ($return AS $retkey => $ret)
          {
            $sections[$retkey] = $ret;
          }
        }
      }

      return $sections;
    }//_recurse_sections()-----------------------------------------------------

    private function _recurse_billables($parent, $billables, $active_billable = null)
    {
      $out_billables = array();

      //Step 1: add this item
      if (!$parent->isRoot())
      {
        if (isset($billables[$parent->getId()]))
        {
          $active_billable = $billables[$parent->getId()];
          if ($active_billable->getCustomerPartsPercent() == 100 && $active_billable->getCustomerLabourPercent() == 100)
          {
            $active_billable = null;
          }
          else
          {
            $out_billables[$parent->getId()] = $active_billable;
            if (!$active_billable->getRecurse())
            {
              //don't apply to the children
              $active_billable = null;
            }
          }
        }
        else if ($active_billable)
        {
          //apply inherited recursive billable
          $out_billables[$parent->getId()] = $active_billable;
        }
      }

      //step 2: recurse down into child items
      if ($parent->hasChildren())
      {
        $counter = 0;
        foreach ($parent->getChildren() AS $child)
        {
          $return = $this->_recurse_billables($child, $billables, $active_billable);
          foreach ($return AS $retkey => $ret)
          {
            $out_billables[$retkey] = $ret;
          }
        }
      }
      return $out_billables;
    }//_recurse_billables()----------------------------------------------------

    private function _recurse_other_billables($parent, $billables, $task_prefix, $active_billable = null)
    {
      $whom = $this->settings['whom'];
      $out_sections = array();
      $out_billables = array();

      //Step 1: add this item
      if (!$parent->isRoot())
      {
        if (isset($billables[$parent->getId()]))
        {
          $test_billable = $billables[$parent->getId()];
          if (($whom == 'inhouse' && ($test_billable->getInHousePartsPercent() > 0 || $test_billable->getInHouseLabourPercent() > 0))
            || (substr($whom, 0, 2) == 's_' && substr($whom,2) == $test_billable->getSupplierId() 
              && ($test_billable->getSupplierPartsPercent() > 0 || $test_billable->getSupplierLabourPercent() > 0))
            || (substr($whom, 0, 2) == 'm_' && substr($whom,2) == $test_billable->getManufacturerId() 
              && ($test_billable->getManufacturerPartsPercent() > 0 || $text_billable->getManufacturerLabourPercent() > 0)))
          {
            $out_sections['Task '.$task_prefix.': '.$parent->getLabel()] = $parent;
            $out_billables[$parent->getId()] = $test_billable;
            if ($test_billable->getRecurse())
            {
              $active_billable = $test_billable;
            }
          }
        }
        else if ($active_billable)
        {
          //apply inherited recursive billable
          $out_sections['Task '.$task_prefix.': '.$parent->getLabel()] = $parent;
          $out_billables[$parent->getId()] = $active_billable;
        }
      }

      //step 2: recurse down into child items
      if ($parent->hasChildren())
      {
        $counter = 0;
        foreach ($parent->getChildren() AS $child)
        {
          $counter++;
          $new_task_prefix = $task_prefix.($task_prefix == '' ? '' : '.').$counter;
          list($return_section,$return_billable) = $this->_recurse_other_billables($child, $billables, $new_task_prefix, $active_billable);
          foreach ($return_section AS $retkey => $ret)
          {
            $out_sections[$retkey] = $ret;
          }
          foreach ($return_billable AS $retkey => $ret)
          {
            $out_billables[$retkey] = $ret;
          }
        }
      }

      return array($out_sections, $out_billables);
    }//_recurse_other_billables()----------------------------------------------


    //generates an ordered, flat array of sections that need to be displayed, indexed by title
    public function loadSections()
    {
      //load all billable items
      $c = new Criteria();
      $c->addJoin(WorkorderItemBillablePeer::WORKORDER_ITEM_ID, WorkorderItemPeer::ID);
      $c->add(WorkorderItemPeer::WORKORDER_ID, $this->workorder->getId());
      $bill_list = WorkorderItemBillablePeer::doSelect($c);
      $billables = array();
      foreach ($bill_list AS $billable)
      {
        $billables[$billable->getWorkorderItemId()] = $billable;
      }

      $parent = $this->workorder->getRootItem();
      $parent->getDescendants();
      if ($this->settings['whom'] == 'cust')
      {
        $task_prefix = '';
        $sections = $this->_recurse_sections($parent, $task_prefix);
        $section_billables = $this->_recurse_billables($parent, $billables);
      }
      else
      {
        //based on the billable records, only include sections that will need to be displayed. Parents tasks will be added as needed.
        list($sections,$section_billables) = $this->_recurse_other_billables($parent, $billables, '');
      }

      return array($sections, $section_billables);
    }//loadSections()----------------------------------------------------------

    public function generateSections($sections, $section_billables)
    {
      $totals = array();

      $invoice_dates = array();
      $invoices = $this->workorder->getWorkorderInvoices();
      foreach ($invoices AS $invoice)
      {
        $invoice_dates[$invoice->getId()] = $invoice->getInvoice()->getIssuedDate('U');
      }

      foreach ($sections AS $key => $section)
      {
        $numbering = explode(':', $key);
        $billable = (isset($section_billables[$section->getId()]) ? $section_billables[$section->getId()] : null);
        $totals = $this->generateSection($numbering[0], $key, $section, $totals, $billable, $invoice_dates);
      }

      return $totals;
    }//generateSections()------------------------------------------------------

    public function generateSummary($sections, $totals)
    {
      $this->addPage();
      $this->generateLogoHeader(' TOTALS');

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
      $total_shopsupplies = round(($this->workorder->getShopSuppliesSurcharge()/100) * ($total_parts + $total_labour + $total_expenses),2);
      if ($total_shopsupplies > 0)
      {
        if (!$this->workorder->getHstExempt()) $total_hst += $total_shopsupplies * (sfconfig::get('app_hst_rate')/100);
        if (!$this->workorder->getPstExempt() && $this->settings['taxable_pst']) $total_pst += $total_shopsupplies * (sfconfig::get('app_pst_rate')/100);
        if (!$this->workorder->getGstExempt() && $this->settings['taxable_gst']) $total_gst += $total_shopsupplies * (sfconfig::get('app_gst_rate')/100);
      }

      //add power & moorage
      $total_moorage = round(($this->workorder->getMoorageSurchargeAmt()/100) * ($total_parts + $total_labour + $total_expenses),2);
      if ($total_moorage > 0)
      {
        if (!$this->workorder->getHstExempt()) $total_hst += $total_moorage * (sfconfig::get('app_hst_rate')/100);
        if (!$this->workorder->getPstExempt() && $this->settings['taxable_pst']) $total_pst += $total_moorage * (sfconfig::get('app_pst_rate')/100);
        if (!$this->workorder->getGstExempt() && $this->settings['taxable_gst']) $total_gst += $total_moorage * (sfconfig::get('app_gst_rate')/100);
      }


  
      //tally final totals
      $total_hst = round($total_hst,2);
      $total_pst = round($total_pst,2);
      $total_gst = round($total_gst,2);
      $total_subtotal = $total_parts + $total_labour + $total_expenses;
      $total_total = $total_parts + $total_labour + $total_expenses - $total_discounts + $total_shopsupplies + $total_moorage + $total_hst + $total_pst + $total_gst + $total_enviro + $total_battery;

      //START OUTPUTTING SUMMARIES
      $started_summaries = false;
      if ($this->settings['summary_tasks'] && isset($totals['sections']) && count($totals['sections']) > 1)
      {
        $newpage_boundary = 260;
        $height = 10 + (count($totals['sections']) * ($this->default_fontsize/1.5));
        $started_summaries = true;
        $this->current_section_title = 'TASKS SUMMARY';
        $this->drawSectionHeader();

        $this_sections = $totals['sections'];
        $colwidths = array('Task'           => array(0,0,'L','text'),
                           'Parts'          => array(0,0,'R','money'),
                           'Labour'         => array(0,0,'R','money'),
                           'Expenses'       => array(0,0,'R','money'),
                           'Subtotal'       => array(0,0,'R','money'));

        $tasks = array();
        foreach ($this_sections AS $section_name => $section)
        {
          $this_totals = $totals['sections'][$section_name];
          $sum = $this_totals['parts']['subtotal'] + $this_totals['labour']['subtotal'] + $this_totals['expenses']['subtotal'];
          $tasks[] = array($section_name, 
                           number_format($this_totals['parts']['subtotal'],2),
                           number_format($this_totals['labour']['subtotal'],2),
                           number_format($this_totals['expenses']['subtotal'],2),
                           $sum);
        }
        $colwidths = $this->autoSizeTable($tasks, $colwidths);

        $this->drawTableHeader($colwidths);
        foreach ($tasks AS $task)
        {
          $this->drawRow($task, $colwidths);
        }
        unset($this_sections,$colwidths,$section,$data,$labours,$labour);
      }

      //output the parts summary table
      if ($this->settings['summary_parts'] && isset($totals['parts_summary']))
      {
        $newpage_boundary = 260;
        $height = 10 + (count($totals['parts_summary']) * ($this->default_fontsize/1.5));
        if ($this->GetY() + $height > $newpage_boundary)
        {
          $this->AddPage();
        }
        else if ($started_summaries)
        {
          $this->ln(5);
        }
        $started_summaries = true;
        $this->current_section_title = 'PARTS SUMMARY';
        $this->drawSectionHeader();

        $cat_amounts = $totals['parts_summary'];
        $keys = array_keys($totals['parts_summary']);
        unset($keys['uncat']);
        $c = new Criteria();
        $c->add(PartCategoryPeer::ID, $keys, Criteria::IN);
        $c->addAscendingOrderByColumn(PartCategoryPeer::NAME);
        $cats = PartCategoryPeer::doSelect($c);
        $data = array();
        foreach ($cats AS $cat)
        {
          $data[] = array($cat->getName(), $cat_amounts[$cat->getId()]);
        }
        if (isset($cat_amounts['uncat']))
        {
          $data[] = array('Uncategorized', $cat_amounts['uncat']);
        }
        if ($total_shopsupplies > 0)
        {
          $data[] = array('Shop Supplies & Misc', $total_shopsupplies);
        }

        unset($keys,$cat_amounts,$cats,$cat);

        $colwidths = array('Part Category'  => array(0,0,'L','text'),
                           'Price'          => array(0,0,'R','money'));
        $colwidths = $this->autoSizeTable($data, $colwidths);

        $this->drawTableHeader($colwidths);
        foreach ($data AS $part)
        {
          $this->drawRow($part, $colwidths);
        }
        unset($data, $part, $colwidths, $data);
      }

      //output the labour types table
      if ($this->settings['summary_labour'] && isset($totals['labour_summary']))
      {
        $newpage_boundary = 260;
        $height = 10 + (count($totals['labour_summary']) * ($this->default_fontsize/1.5));
        if ($this->GetY() + $height > $newpage_boundary)
        {
          $this->AddPage();
        }
        else if ($started_summaries)
        {
          $this->ln(5);
        }
        $started_summaries = true;
        $this->current_section_title = 'LABOUR SUMMARY';
        $this->drawSectionHeader();

        $cats = $totals['labour_summary'];
        foreach ($cats AS $cat_id => $cat)
        {
            $name = (is_int($cat_id)) ? $this->labourtypes[$cat_id]['name'] : $cat_id;
            $rate = $cat['ratemin'];
            if ($cat['ratemin'] != $cat['ratemax'])
            {
              $rate .= '-'.$cat['ratemax'];
            }
            $data['k'.((int)$rate).$name] = array($name, $cat['hours'], $rate, $cat['total']);          
        }
        ksort($data);

        unset($cats,$cat);

        $colwidths = array('Labour Type'  => array(0,0,'L','text'),
                           'Hours'        => array(0,0,'C','number'),
                           'Rate'         => array(0,0,'R','money'),
                           'Price'        => array(0,0,'R','money'));
        $colwidths = $this->autoSizeTable($data, $colwidths);

        $this->drawTableHeader($colwidths);
        foreach ($data AS $labour)
        {
          $this->drawRow($labour, $colwidths);
        }
        unset($data, $labour, $colwidths);
      }

      //generate totals page
      $newpage_boundary = 200;
      if ($this->GetY() + $height > $newpage_boundary)
      {
        $this->AddPage();
      }
      else if ($started_summaries)
      {
        $this->ln(5);
      }

      //DRAW TOTALS
      //*************
      
      $this->setFont('Arial', '', $this->totals_font);
      $this->SetDrawColor(200);

      $this->Rect($this->GetX(), $this->GetY(), $this->page_width, 32, 'F', false, array(231,239,239));
      $this->ln(8);
      $start_y = $this->GetY();

      //draw parts
      $this->SetXY(23, $start_y);
      $this->SetFillColor(200);
      $this->Cell(35, 6, 'Parts', 1, 2, 'C', 1);
      $text = $total_parts;
      $text = ($text == 0 ? ' - ' : number_format($text, 2));
      $this->SetFillColor(255);
      $this->Cell(35, 10, $text, 1, 0, 'C', 1);

      //draw labour
      $this->SetY($start_y, false);
      $this->Cell(10, 16, '+', 0, 0, 'C', 0);

      $this->SetFillColor(200);
      $this->Cell(35, 6, 'Labour', 1, 2, 'C', 1);
      $text = $total_labour;
      $text = ($text == 0 ? ' - ' : number_format($text, 2));
      $this->SetFillColor(255);
      $this->Cell(35, 10, $text, 1, 0, 'C', 1);

      //draw expenses
      $this->SetY($start_y, false);
      $this->Cell(10, 16, '+', 0, 0, 'C', 0);

      $this->SetFillColor(200);
      $this->Cell(35, 6, 'Expenses', 1, 2, 'C', 1);
      $text = $total_expenses;
      $text = ($text == 0 ? ' - ' : number_format($text, 2));
      $this->SetFillColor(255);
      $this->Cell(35, 10, $text, 1, 0, 'C', 1);

      //draw subtotal
      $this->SetY($start_y, false);
      $this->Cell(10, 16, '=', 0, 0, 'C', 0);

      $this->SetFillColor(200);
      $this->Cell(35, 6, 'Subtotal', 1, 2, 'C', 1);
      $text = $total_subtotal;
      $text = ($text == 0 ? ' - ' : number_format($text, 2));
      $this->SetFillColor(255);
      $this->Cell(35, 10, $text, 1, 0, 'C', 1);

      $start_y += 28;
      $this->SetY($start_y);

      //output summary column of totals
      $totals_width = 35;
      $labels_width = $this->page_width - $totals_width - 16;
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

      return $total_total;
    }//generateSummary()-------------------------------------------------------

    public function generatePayments($total)
    {
      //add payments
      if ($this->settings['payments'] && ($all_payments = $this->workorder->getPaymentsByPayer()))
      {
        if (isset($all_payments[$this->settings['whom']]))
        {
          $payments = $all_payments[$this->settings['whom']]['payments'];
          $newpage_boundary = 260;
          $height = max(50, (count($totals['labour_sumary']) * ($this->default_fontsize/1.5)));
          if ($this->GetY() + $height > $newpage_boundary)
          {
            $this->AddPage();
          }
          else 
          {
            $this->ln(4);
          }
    
          $start_y = $this->GetY();
          $payments_total = 0;
          $colwidths = array('Date'    => array(60,9,'L','text'),
                             'Amount'  => array(20,9,'R','money'));

          $this->setLineWidth(0.2);
          foreach ($payments AS $payment)
          {
            $payments_total += $payment->getAmount();
            $this->setX(20);
            if ($this->settings['payments_existing'])
            {
              $this->drawRow(array(($payment->getAmount() > 0 ? 'Payment Received' : 'Refund Issued').' on '.$payment->getCreatedAt('M j, Y'), 
                $payment->getAmount()), $colwidths, null, false);
            }
          }
          unset($colwidths,$payment_item, $colwidths, $payments, $payment);

          $owing = $total - $payments_total;
        
          $this->SetY($start_y);
          $totals_width = 35;
          $labels_width = $this->page_width - $totals_width - 16;
          $this->SetLineWidth(0.2);
          $this->SetFillColor(231,239,239);
          $this->SetDrawColor(0);
          $this->SetFont("Arial", "B", $this->totals_font);

          $this->Cell($labels_width, $this->totals_font, 'Total Payments: ', 0, 0, 'R', 0);
          $this->Cell($totals_width, $this->totals_font, number_format($payments_total,2), 1, 1, 'R', 1);

          $this->Cell($labels_width, $this->totals_font, 'BALANCE OWING: ', 0, 0, 'R', 0);
          $this->SetLineWidth(0.5);
          $this->SetFillColor(206,220,255);
          $this->Cell($totals_width, $this->totals_font, number_format($owing,2), 1, 1, 'R', 1);
        }
      }
    }//generatePayments()------------------------------------------------------

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
    }//autoSizeTable()---------------------------------------------------------

    function _is_new($obj, $invoice_dates)
    {
      $is_new = false;
      $skip = false;

      if ($this->settings['invoice'] && !($obj->getWorkorderInvoiceId()))
      {
          //leave out this item because it's newer than the requested invoice
          $skip = true;
      }
      else if ($this->settings['invoice'] && $invoice_dates[$obj->getWorkorderInvoiceId()] > $invoice_dates[$this->settings['invoice']->getId()])
      {
          //leave out this item because it's newer than the requested invoice
          $skip = true;
      }
      else if ($this->settings['invoice'] && $this->settings['show_progress'])
      {
          //only separate it out if it's assigned to the current chosen invoice
          $is_new = ($obj->getWorkorderInvoiceId() == $this->settings['invoice']->getId());
      }
      else if ($this->settings['show_progress'] && count($invoice_dates) > 0)
      {
          //only separate it out if it hasn't been assigned to an invoice yet (for final billing)
          $is_new = (!($obj->getWorkorderInvoiceId()));
      }

      return array($skip, $is_new);
    }//_is_new()---------------------------------------------------------------

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

    public function generate()
    {
        set_time_limit(0);

        //preload labour types
        $this->labourtypes = LabourTypePeer::loadTypesArray();

        //add the introduction page (only done for customer payers)
        $this->AddPage();
        $this->generateIntro();

        list($sections, $section_billables) = $this->loadSections();

        //populate the section data based on the requested information
        $totals = $this->generateSections($sections, $section_billables);

        //include the summary page
        $final_total = $this->generateSummary($sections, $totals);

        //include the payments status page
        $this->generatePayments($final_total);
    }

}
