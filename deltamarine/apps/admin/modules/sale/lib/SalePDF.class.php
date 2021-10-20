<?php

class SalePDF extends sfTCPDF
{
    var $sale;
    var $items;
    var $payments;
    var $returns;

    var $cols;
    var $title_font = 20;
    var $subtitle_font = 10;
    var $oddrow;
    var $adding_notes = false;

    var $margin_width = 7;
    var $margin_height = 8;
    var $column_padding = 3;
    var $default_fontsize = 9;
    var $header_fontsize = 12;
    var $totals_fontisze = 14;
    var $page_width;
    var $max_fontsize = 7;

    var $paymentsstart = 40;

    var $small_cols = array(); //these are set at 80% of regular font size
    var $small_col_factor = 0.8;
    var $expand_col;

    var $regpricecol = 'Reg. Price';

    public function __construct($sale, $items, $payments, $returns)
    {
      $this->sale = $sale;
      $this->items = $items;
      $this->payments = $payments;
      $this->returns = $returns;

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
        $title = 'SALE INVOICE';
      }

      //insert logo
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
      //$this->Cell(50, $this->subtitle_font/1.5, ($this->sale->getForRigging() ? 'Delta Rigging and Welding' : 'Delta Marine Service'), 0, 2, 'L');
      $this->setFont("Arial", '', 8);
      $this->MultiCell(50, $this->default_fontsize/2, "2075 Tryon Road\nSidney, B.C.  V8L 3X9\nTel: (250) 656-2639\nFax: (250) 656-2619", 0, 'L', 0, 0);

      //main title
      $this->SetY($page_top);
      $this->SetX(90);
      $this->setFont("Arial", "B", $this->title_font);
      $this->Cell(0, $this->title_font/2.5, $title, 0, 2, 'R');

      $this->SetY(35);
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
      $customer = $this->sale->getCustomer()->getWfCRM();
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

      //load sale info
      $sale_data = array();
      $sale_data[] = array('Sale Date:',$this->sale->getDateOrdered('M j, Y h:iA'));
      $sale_data[] = array('Sale ID:',$this->sale->getId());
      if ($this->sale->getPoNum())
      {
        $sale_data[] = array('PO Number:',$this->sale->getPoNum());
      }      
      if ($this->sale->getBoatName())
      {
        $sale_data[] = array('Boat Name:',$this->sale->getBoatName());
      }

      //equialize section lengths
      $diff = (count($customer_data) - count($sale_data));
      for ($i = 0; $i < abs($diff); $i ++)
      {
        ($diff < 0 ? $customer_data[] = ' ' : $sale_data[] = array(' ',' '));
      }

      //output customer information
      $start_y = $this->getY();
      $this->SetX(20);
      $this->setFont('Arial', 'B', $this->subtitle_font);
      $this->Cell(80, 4, 'Customer Information', 1, 2, 'C', 1);
      $this->setFont("Arial", '', $this->default_fontsize);
      $this->MultiCell(80, $this->default_fontsize/2.5, implode("\n", $customer_data), 1, 'L', 0, 0);

      //output sale information
      $this->setXY(115, $start_y);
      $this->setFont('Arial', 'B', $this->subtitle_font);
      $this->Cell(80, 4, 'Sale Information', 1, 2, 'C', 1);
      $count = 0;
      foreach ($sale_data AS $data)
      {
        $this->setX(115);
        $this->setFont("Arial", 'B', $this->default_fontsize);
        $last = ($count == count($sale_data) - 1);
        $first = ($count == 0);
        $this->Cell(30, $this->default_fontsize/2, $data[0], 'L'.($first ? 'T' : '').($last ? 'B' : ''), 0, 'R', 0);
        $this->setFont("Arial", '', $this->default_fontsize);
        $this->Cell(50, $this->default_fontsize/2, $data[1], 'R'.($first ? 'T' : '').($last ? 'B' : ''), 1, 'L', 0);
        $count ++;
      }

      $this->ln(1);
    }

    function AddPage($orientation = '', $format = '', $keepmargins=false, $tocpage=false)
    {
      parent::AddPage($orientation, $format, $keepmargins=false, $tocpage=false);
    }

    function Footer()
    {
        $this->SetY(-11);

        $this->SetFont('Arial', 'BI', 8);
        $this->Cell($this->page_width/3, 5, 'Sale #'.$this->sale->getId(), 0, 0, 'C');
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
        }

        $this->SetFont('Arial', 'B', $this->header_fontsize);
        $this->SetDrawColor(200);
        $this->SetFillColor(200);
        $this->SetTextColor(0);
        $this->SetLineWidth(0.2);
        $line_height = $this->header_fontsize / 2;

        foreach ($cols AS $key => $this_col)
        {
            $this->Cell($this_col[0], $line_height, $key, 1, 0, ($first_item ? 'L' : 'C'), 1);
            $first_item = false;
        }
        $this->ln($line_height + 0.1);
        $this->oddrow = false;
    }

    function drawRow($data, $colwidths, $notes = null)
    {
        $first = current($colwidths);
        $line_height = $first[1] / 1.5;
        $newpage_barrier = 266;
        $height = $line_height;
        if ($notes)
        {
          $notes = implode("\n", $notes);
          $this->SetFont('Arial', '', $this->default_fontsize - 1);
          $note_height = $this->getStringHeight($colwidths[0], $notes);
          $height += $note_height;
        }
        if (($this->GetY() + $height) > $newpage_barrier)
        {
          $this->AddPage();
          $this->drawTableHeader($colwidths);
        }

        $idx = 0;

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
            if ($data[$idx] == 0 && ($idx != count($colwidths) - 1))
            {
              $text = ' - ';
              $align = 'R';
            }
            else if (strpos($data[$idx],',') === false)
            {
              $text = number_format($data[$idx],2);
            }
            else
            {
              $text = $data[$idx];
            }
          }
          else if ($colwidths[$key][3] == 'number')
          {
              $text = $data[$idx] == 0 ? ' - ' : ((int) $data[$idx] == (float) $data[$idx] ? number_format($data[$idx]) : $data[$idx]);
          }

          $this->SetDrawColor(200);
          $discounted = false;
          if ($key == $this->regpricecol)
          {
            $discounted = ($data[$idx] > $data[$idx + 1]);
            if ($data[$idx] < $data[$idx + 1]){
              $text = number_format($data[$idx + 1], 2);
            }
          }
          ($discounted) ? $this->SetTextColor(150, 50, 50) : $this->SetTextColor(0, 0, 0);

          if ($notes)
          {
            if ($idx == 0)
            {
              $this->Cell($this_col[0], $line_height, $text, 'LTR', 1, $align, 1);
              $this->setTextColor(100);
              $padding = $this->GetCellPaddings();
              $this->SetCellPaddings(5, $padding['T'],$padding['R'],$padding['B']);
              $this->setFont('Arial', '', $this->default_fontsize - 1);
              $this->MultiCell($this_col[0], $note_height, $notes, 'LBR', 'L', 1, 0);
              $this->SetY($this->GetY() - $line_height, false);
              $this->setTextColor(0);
              $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
            }
            else
            {
              $this->Cell($this_col[0], $line_height + $note_height, $text, 1, 0, $align, 1);
            }
          }
          else
          {
            $this->Cell($this_col[0], $line_height, $text, 1, 0, $align, 1);
          }

          if ($discounted)
          {
            $this->SetDrawColor(250,100,100);
            $textwidth = $this->GetStringWidth($text);
            $line_offset = ($notes ? $line_height + $note_height : $line_height)/2;
            $this->Line($this->GetX() - 1, $this->GetY() + $line_offset, $this->GetX() - $textwidth - 1, $this->GetY() + $line_offset);
          }

          $idx ++;
        }

        $this->ln();

        $this->paymentsstart = $this->GetY();
    }

    function drawTableFooter($totals, $colwidths)
    {
      end($colwidths);
      $totals_width = current($colwidths);
      $labels_width = $this->page_width - $totals_width[0];

      $extras = array(
        'subtotal' => 'Subtotal',
        'enviro_levy'   => 'Enviro Fees',
        'battery_levy'  => 'Battery Levies',
        'hst'      => 'HST',
        'pst'      => 'PST',
        'gst'      => 'GST',
        'total'    => 'Total'
      );
      foreach ($extras AS $idx => $label)
      {
          if (isset($totals[$idx]) && ($totals[$idx] != 0 || $idx == 'subtotal' || $idx == 'total'))
          {
            $number = number_format(round($totals[$idx], 2), 2);
            $this->SetFillColor(255);
            $this->SetFont("Arial", "B", $this->default_fontsize);
            $this->SetDrawColor(0);
            $this->Cell($labels_width, $this->default_fontsize/1.5, $label.':', 0, 0, 'R', 1);
            ($idx == 'total' ? $this->SetLineWidth(0.5) : $this->SetLineWidth(0.2));
            ($idx == 'total' ? $this->SetFillColor(206, 220, 255) : $this->SetFillColor(221, 235, 255));
            $this->Cell(0, $this->default_fontsize/1.5, $number, 1, 1, 'R', 1);
          }
          else if ($idx == 'pst' && $this->sale->getPstExempt() && $this->sale->getCustomer()->getPstNumber())
          {
            $this->SetFillColor(245);
            $this->SetFont("Arial", '', $this->default_fontsize);
            $this->SetX($this->GetX() + 20); 
            $this->Cell(80, $this->default_fontsize/1.5, 'BC PST Exempted / PST Number: '.$this->sale->getCustomer()->getPstNumber(), 0, 0, 'C', 1);
            //$this->SetX($this->GetX() + 100); 
            //$this->Cell(80, $this->default_fontsize/1.5, 'BC PST Exempted / PST Number: '.$this->sale->getCustomer()->getPstNumber(), 0, 0, 'L', 1);
            $this->SetFont("Arial", 'B', $this->default_fontsize);
            $this->Cell($labels_width - 100, $this->default_fontsize/1.5, 'PST (7%): ', 0, 0, 'R', 1);
            //$this->Cell($labels_width - 180, $this->default_fontsize/1.5, 'PST (7%): ', 0, 0, 'R', 1);
            $this->SetFont("Arial", '', $this->default_fontsize);
            $this->SetFillColor(231,239,239);
            $this->Cell(0, $this->default_fontsize/1.5, 'N/A', 1, 1, 'R', 1);
            $this->SetFont("Arial", 'B', $this->default_fontsize);
          }
      }

      $this->SetLineWidth(0.2);
      $this->setFillColor(255,255,255);
    }

    function drawSummary($total, $payments)
    {
      if (count($this->payments) > 0)
      {
        $newpage_barrier = 266;
        $height = 15;
        if (($this->GetY() + $height + 4) > $newpage_barrier)
        {
          $this->addPage();
        }

        $this->setDrawColor(120);
        $this->SetY($newpage_barrier - $height - 4);
        $this->Rect($this->GetX(), $this->GetY(), $this->page_width, $height, 'F', false, array(231,239,239));

        $start_y = $this->GetY() + 2;
        $owing = 0;
        $this->setFont('Arial', '', $this->totals_fontsize);
        $this->SetDrawColor(240);

        //draw total
        $this->SetXY(43, $start_y);
        $this->SetFillColor(240);
        $this->Cell(40, 4, 'Total Charges', 1, 2, 'C', 1);
        $owing = $total;
        $text = $total;
        $text = ($text == 0 ? ' - ' : number_format(round($text, 2), 2));
        $this->SetFillColor(255);
        $this->Cell(40, 5, $text, 1, 0, 'C', 1);

        //draw payments
        $this->SetY($start_y, false);
        $this->Cell(5, 9, '+', 0, 0, 'C', 0);

        $this->SetFillColor(240);
        $this->Cell(40, 4, 'Payments', 1, 2, 'C', 1);
        $text = $payments;
        $owing -= $text;
        $text = ($text == 0 ? ' - ' : number_format(round($text, 2), 2));
        $this->SetFillColor(255);
        $this->Cell(40, 5, $text, 1, 0, 'C', 1);

        //draw owing
        $this->SetY($start_y, false);
        $this->Cell(5, 9, '=', 0, 0, 'C', 0);

        $this->SetY($start_y + 0.2, false);
        $this->SetFillColor(240);
        $this->SetLineWidth(0.5);
        $this->setFont('Arial', 'B', $this->default_fontsize);
        $this->Cell(40, 3.7, 'Total Owing', 1, 2, 'C', 1);
        $text = number_format(round($owing,2), 2);
        $this->SetFillColor(255);
        $this->Cell(40, 4.7, $text, 1, 1, 'C', 1);
        $this->SetLineWidth(0.2);
      }
    }

    //OUTPUTS the various bits for a particular section (workorder item)
    function generatePartsList()
    {
        //determine if we need a page break
        //=================================
        $newpage_barrier = 266;
        if ($this->GetY() + 60 > $newpage_barrier)
        {
          $this->addPage();
        }
        else
        {
          $this->ln(10);
        }
        
        //setup totals array for this section
        $totals = array('subtotal' => 0, 'hst' => 0, 'pst' => 0, 'gst' => 0, 'battery_levy' => 0, 'enviro_levy' => 0, 'total' => 0);

        //add parts
        //===================================

        //set up columns
        $colwidths = array(
          'Part Information' => array(0,0,'L','text'),
          'SKU'              => array(0,0,'C','text'),
          'Quantity'         => array(0,0,'C','number'),
          'Returned'         => array(0,0,'C','number'),
          'Reg. Price'       => array(0,0,'R','money'),
          'Unit Price'       => array(0,0,'R','money'),
          'Total'            => array(0,0,'R','money')
        );

        if (empty($this->returns))
        {
          unset($colwidths['Returned']);
        }

        //loop through parts once to get category totals and combine duplicate parts
        $parts_list = array();
        $parts_notes = array();
        $has_discounts = false;
        foreach ($this->items AS $item)
        {
          $var = $item->getPartInstance()->getPartVariant();
          $partsinfo = array(
            ($var ? $var->__toString() : $item->getPartInstance()->getCustomName()),
            ($var ? $var->getInternalSku() : ''),
            $item->getPartInstance()->getQuantity(),
            '',
            ($var ? $var->calculateUnitPrice() : $item->GetPartInstance()->getUnitPrice()),
            $item->getPartInstance()->getUnitPrice(),
            $item->getPartInstance()->getSubtotal(false)
          );

          $totals['subtotal']     += $item->getPartInstance()->getSubtotal(false);
          $totals['hst']          += $item->getPartInstance()->getHstTotal(false);
          $totals['pst']          += $item->getPartInstance()->getPstTotal(false);
          $totals['gst']          += $item->getPartInstance()->getGstTotal(false);
          $totals['battery_levy'] += $item->getPartInstance()->getBatteryLevyTotal(false);
          $totals['enviro_levy']  += $item->getPartInstance()->getEnviroLevyTotal(false);
          $totals['total']        += $item->getPartInstance()->getTotal(false);

          //discounted parts
          if ($var && ($item->getPartInstance()->getUnitPrice() * 100) < (round($item->getPartInstance()->getPartVariant()->calculateUnitPrice() * 100)))
          {
            $has_discounts = true;
          }

          //modify line item by return
          if (empty($this->returns))
          {
            unset($partsinfo[3]);
            $partsinfo = array_values($partsinfo);
          }
          else if (isset($this->returns[$item->getId()]))
          {
            $rets = $this->returns[$item->getId()];
            foreach ($rets AS $ret)
            {
              $partsinfo[3] = -1 * $ret->getPartInstance()->getQuantity();
              $partsinfo[5] = $partsinfo[5] + $ret->getPartInstance()->getSubtotal(false);
              $totals['subtotal']     += $ret->getPartInstance()->getSubtotal(false);
              $totals['hst']          += $ret->getPartInstance()->getHstTotal(false);
              $totals['pst']          += $ret->getPartInstance()->getPstTotal(false);
              $totals['gst']          += $ret->getPartInstance()->getGstTotal(false);
              $totals['battery_levy'] += $ret->getPartInstance()->getBatteryLevyTotal(false);
              $totals['enviro_levy']  += $ret->getPartInstance()->getEnviroLevyTotal(false);
              $totals['total']        += $ret->getPartInstance()->getTotal(false);
            }
          }

          //add all sorts of notes
          if ($item->getPartInstance()->getBatteryLevyTotal())
          {
            if (!isset($part_notes[$item->getId()])) $part_notes[$item->getId()] = array();
            $parts_notes[$item->getId()][] = 'Battery Levy @ '.number_format($item->getPartInstance()->getBatteryLevy(), 2).' ea.';
          }
          if ($item->getPartInstance()->getEnviroLevyTotal())
          {
            if (!isset($part_notes[$item->getId()])) $part_notes[$item->getId()] = array();
            $parts_notes[$item->getId()][] = 'Enviro Levy @ '.number_format($item->getPartInstance()->getEnviroLevy(), 2).' ea.';
          }
          if ($item->getPartInstance()->getSerialNumber())
          {
            if (!isset($part_notes[$item->getId()])) $part_notes[$item->getId()] = array();
            $parts_notes[$item->getId()] = 'Serial Number: '.$item->getPartInstance()->getSerialNumber();
          }

          $parts_list[$item->getId()] = $partsinfo;
        }

        //remove discounts if not set
        if (!$has_discounts)
        {
          unset($colwidths['Reg. Price']);
          foreach ($parts_list AS $idx => $part)
          {
            //count backwards to delete the column, since return column may have been deleted}
            unset($part[count($part) - 3]); 
            $parts_list[$idx] = array_values($part);
          }
        }

        //output table
        $colwidths = $this->autoSizeTable($parts_list, $colwidths);
        $this->drawTableHeader($colwidths);
        foreach ($parts_list AS $item_id => $part)
        {
          if (isset($parts_notes[$item_id]))
          {
            $this->drawRow($part, $colwidths, $parts_notes[$item_id]);
          }
          else
          {
           $this->drawRow($part, $colwidths);
          }
        }

        //output subtotals
        $this->ln(0.1);
        $this->drawTableFooter($totals, $colwidths);

        return $totals['total'];
    }

    public function generatePayments()
    {
      if ($this->payments)
      {
        $indent = $this->margin_width;
        sfContext::getInstance()->getLogger()->info('Payments Start Y: '.$this->paymentsstart);
        sfContext::getInstance()->getLogger()->info('Current Y Position: '.$this->GetY());
        $this->paymentsstart = $this->GetY();

        $start_y = $this->paymentsstart + 0.5;

        sfContext::getInstance()->getLogger()->info('New Start Y Position: '.$start_y);

        $newpage_boundary = 260;
        $height = max(50, (count($this->payments) * ($this->default_fontsize/1.5)) + 8);
        if ($start_y + $height > $newpage_boundary)
        {
          $this->AddPage();
        }

        $this->setXY($indent, $start_y);
        $this->SetFillColor(231,239,239);
        $this->SetDrawColor(231,239,239);
        $this->setFont('Arial', 'B', $this->subtitle_font);
        $this->Cell(100, 4, 'Payments', 1, 2, 'C', 1);
        $count = 0;


        $start_y = $this->GetY();
        $this->setLineWidth(0.2);
        foreach ($this->payments AS $payment)
        {
          $line_height = $height = $this->default_fontsize / 1.5;
          if ($payment->getPaymentDetails() != '')
          {
            $details = $payment->getPaymentDetails();
            $details_height += 1 * ($this->default_fontsize - 2) / 1.5;
            $height += $details_height;
          }
          else if ($payment->getPaymentMethod() == 'Cash' && $payment->getChange() != 0)
          {
            $details = 'Tendered: '.number_format($payment->getTendered(),2)."\n".
              'Change Given: '.number_format($payment->getChange(),2);
            $details_height = 2 * ($this->default_fontsize - 2) / 1.5;
            $height += $details_height;
          }
          else
          {
            $details = false;
          }
          $payments_total += $payment->getAmount();
          $this->setX($indent);
          $this->setFont('Arial', '', $this->default_fontsize);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(231,239,239);

          $this->Cell(25, $height, $payment->getCreatedAt('M j, Y'), 1, 0, 'L', 1);
          if ($details)
          {
            $this->Cell(45, $line_height, $payment->getPaymentMethod(), 'LTR', 1, 'L', 1);
            $this->setTextColor(100);
            $padding = $this->GetCellPaddings();
            $this->SetCellPaddings(5, $padding['T'],$padding['R'],$padding['B']);
            $this->setFont('Arial', '', $this->default_fontsize - 2);
            $this->setX(25 + $this->margin_width);
            $this->MultiCell(45, $details_height, $details, 'LBR', 'L', 1, 0);
            $this->SetY($this->GetY() - $line_height, false);
            $this->setTextColor(0);
            $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
            $this->setFont('Arial', '', $this->default_fontsize);
          }
          else
          {
            $this->Cell(45, $height, $payment->getPaymentMethod(), 1, 0, 'L', 1);
          }
          $this->SetFillColor(240);
          $this->setDrawColor(200);
          $this->Cell(30, $height, number_format($payment->getAmount(), 2), 1, 0, 'R', 1);
          $this->ln($height);
        }

        return $payments_total;
      }
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

        $max_width = $this->page_width;

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

        //add the introduction page (only done for customer payers)
        $this->AddPage();
        $this->generateIntro();

        //populate the section data based on the requested information
        $total = $this->generatePartslist();

        //include the payments status page
        $payments = $this->generatePayments();

        //include final summary footer
        $this->drawSummary($total, $payments);
    }

}
