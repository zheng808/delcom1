<?php

class TimelogsPDF extends sfTCPDF
{
    var $workorder;
    var $cost;
    var $status;

    var $cols;
    var $header_font;
    var $title_font = 16;
    var $subtitle_font = 8;
    var $section_font = 12;
    var $row_font;
    var $oddrow;

    var $margin_width = 7;
    var $margin_height = 8;
    var $column_padding = 3;
    var $initial_fontsize = 7;
    var $header_fontsize = 9;
    var $page_width;
    var $max_fontsize = 11;

    var $first_page = true;
    var $small_cols = array(); //these are set at 80% of regular font size
    var $small_col_factor = 0.8;
    var $expand_col;

    public function __construct($workorder, $cost, $status)
    {
      $this->workorder = $workorder;
      $this->cost = $cost;
      $this->status = $status;

      parent::__construct('P', 'mm', 'Letter', false);

      $this->SetMargins($this->margin_width, $this->margin_height);
      $this->SetFont('Arial', 'B', $this->initial_fontsize);
      $this->SetAutoPageBreak(false);
      $this->page_width =  216 - (2 * $this->margin_width);
    }

    function AddTableHeader()
    {
        $this->SetFont('Arial', 'B', $this->header_font);
        $this->SetDrawColor(0,0,0);
        $this->SetFillColor(200);
        $this->SetTextColor(0);
        $this->SetLineWidth(0.2);
        $first_item = true;
        $line_height = $this->header_font / 2;
        foreach ($this->cols AS $key => $this_col)
        {
            $this->Cell($this_col[4], $line_height, $this_col[0], 1, 0, 'C', 1);
        }
        $this->ln($line_height + 0.1);
    }

    function Header()
    {

    }

    function AddPage($orientation = '', $format = '', $keepmargins=false, $tocpage=false)
    {
        parent::AddPage($orientation, $format, $keepmargins=false, $tocpage=false);

        if ($this->first_page)
        {
            $this->first_page = false;
            $subtitles=array();
            $maxwidth = 0;
            $page_top = $this->GetY();

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
            $this->SetX(120);
            $this->setFont("Arial", "B", $this->title_font);
            $title_text = 'WORK ORDER #'.$this->workorder->getId();
            $this->Cell(0, $this->title_font/2.5, $title_text, 0, 2, 'R');
            $title_text = 'TIMELOGS SUMMARY'; 
            $this->Cell(0, $this->title_font/2.5, $title_text, 0, 2, 'R');

            //insert customer information
            $this->setFont("Arial", "B", $this->subtitle_font);
            $this->Cell(0, $this->subtitle_font/2.5, 'Boat Name: '.$this->workorder->getCustomerBoat(), 0, 2, 'R');
            $customer = $this->workorder->getCustomerBoat()->getCustomer()->getWfCRM();
            $customer_data = array();
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
            $this->setFont("Arial", "", $this->subtitle_font);
            $this->MultiCell(0, $this->subtitle_font/2.5, implode("\n", $customer_data), 0, 'R');

            $this->setY($page_top + 40);
        }
    }

    function Footer()
    {
        $this->SetY(-11);

        $this->SetFont('Arial', 'BI', 8);
        $this->Cell($this->page_width/3, 5, "Work Order #".$this->workorder->getId()." Timelogs List", 0, 0, 'C');
        $this->Cell($this->page_width/3, 5, "Generated ".date('Y-M-j g:i a'), 0, 0, 'C');
        $this->AliasNbPages();
        $this->Cell(0, 5, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'C');
    }

    function addSection($alldata, $index)
    {
        $section_data = $alldata[$index];

        //draw section header, unless there's only one section
        if (count($alldata) > 1)
        {
            $newpage_barrier = (count($section_data['items']) == 0 ? 250 : 220);
            if ($this->GetY() > $newpage_barrier)
            {
                $this->AddPage();
            }

            $this->SetFont("Arial", "B", $this->section_font);
            $this->SetFillColor(221,235,255);
            $this->SetDrawColor(0);
            $this->SetTextColor(0);
            $this->Cell(0, $this->section_font/1.8, $section_data['title'], 1, 1, 'L', 1);
            $this->SetFillColor(255);
            $this->SetFont("Arial", "", $this->row_font);
        }

        //add individual items
        if (count($section_data['items']) == 0)
        {
            $this->SetTextColor(100);
            $this->SetDrawColor(200);
            $this->SetFont("Arial", "I", $this->row_font);
            $this->Cell(0, $this->row_font, "There Are No Timelogs to Show Here", 'LBR', 1, 'C');
            $this->SetTextColor(0);
            $this->SetFont("Arial", '', $this->row_font);
            $this->ln(4);
        }
        else
        {
            $this->AddTableHeader();
            foreach ($section_data['items'] AS $item)
            {
                $this->addRow($item);
            }
             
            //draw totals boxes
            if ($this->cost)
            {
                $totals_width = $this->cols[count($this->cols) - 1][4];
                $labels_width = $this->page_width - $totals_width;
                $extras = array('subtotal' => 'Subtotal', 'hst' => 'HST', 'pst' => 'PST', 'gst' => 'GST', 'total' => 'Total');
                foreach ($extras AS $idx => $label)
                {
                  if ($section_data[$idx] != 0)
                  {
                      $this->SetFillColor(255);
                      $this->SetFont("Arial", "B", $this->row_font);
                      $this->SetDrawColor(0);
                      $this->Cell($labels_width, $this->row_font/1.5, $label.':', 0, 0, 'R', 1);
                      ($idx == 'total' ? $this->SetLineWidth(0.5) : $this->SetLineWidth(0.2));
                      ($idx == 'total' ? $this->SetFillColor(206, 220, 255) : $this->SetFillColor(221, 235, 255));
                      $this->Cell(0, $this->row_font/1.5, number_format($section_data[$idx],2), 1, 1, 'R', 1);
                  }
                }
            }
            
            $this->ln(4);
            $this->SetLineWidth(0.2);
            $this->SetFillColor(255);
        }
    }

    function addRow($data)
    {
        $line_height = $this->row_font / 2;
        $newpage_barrier = 266;
        $height = $line_height * 2;
        if (($this->GetY() + $height) > $newpage_barrier)
        {
            $this->AddPage();
            $this->AddTableHeader();
        }

        $this->SetDrawColor(200);
        $this->SetTextColor(0,0,0);

        if (!isset($this->oddrow)) $this->oddrow = true;
        if ($this->oddrow) $this->SetFillColor(240,240,240);
        else $this->SetFillColor(255,255,255);
        $this->oddrow = !$this->oddrow;

        foreach ($this->cols AS $key => $this_col)
        {
            $single_row = (count(explode("\n", $data[$key])) == 1);
            $font_size = (isset($this->small_cols[$key]) ? $this->row_font * $this->small_col_factor : $this->row_font);
            $this->SetFont('Arial', '', $font_size);
            $text = $data[$key];
            $this->Cell($this_col[4], $line_height, $text, 1, 0, $this_col[1], 1);
        }
        $this->ln($line_height);
    }

    public function generate($section_data)
    {
        //preload all the data
        $loaded_data = array(); //column-indexed
        $columns = array();
        
        foreach ($section_data AS $section_index => $result_section)
        {
            //track the totals per section
            $total_subtotal = 0;
            $total_hst      = 0;
            $total_pst      = 0;
            $total_gst      = 0;
            $total_amt      = 0;

            $loaded_data[$section_index] = array('items' => array());
            foreach ($result_section['items'] AS $item)
            {
                $this->setFont('Arial', '', $this->initial_fontsize);
                $this_data = array();
                $colnum = 0;

                //SET DATE
                if (!isset($columns[$colnum])) $columns[$colnum] = array("Date", "L", 0, "");
                $date_text = $item->getEndTime('m/d/Y');
                $date_width = $this->GetStringWidth($date_text);
                if ($columns[$colnum][2] < $date_width)
                {
                  $columns[$colnum][2] = $date_width;
                  $columns[$colnum][3] = $date_text;
                }
                $this_data[$colnum] = $date_text;

                //SET EMPLOYEE
                $colnum++;
                if (!isset($columns[$colnum])) $columns[$colnum] = array("Employee", "L", 0, "");
                $this->expand_col = $colnum;
                $emp_text = $item->getEmployee()->getName(false, false, false);
                $emp_width = $this->GetStringWidth($emp_text);
                if ($columns[$colnum][2] < $emp_width)
                {
                  $columns[$colnum][2] = $emp_width;
                  $columns[$colnum][3] = $emp_text;
                }
                $this_data[$colnum] = $emp_text;

                //SET LABOUR TYPE
                $colnum++;
                if (!isset($columns[$colnum])) $columns[$colnum] = array("Labour Type", "L", 0, "");
                if ($item->getLabourTypeId())
                {
                  $type_text = $item->getLabourType()->getName();
                }
                else if ($item->getNonbillTypeId())
                {
                  $type_text = $item->getNonbillType()->getName();
                }
                else
                {
                  $type_text = $item->getCustomLabel();
                }
                $type_width = $this->GetStringWidth($type_text);
                if ($columns[$colnum][2] < $type_width)
                {
                  $columns[$colnum][2] = $type_width;
                  $columns[$colnum][3] = $type_text;
                }
                $this_data[$colnum] = $type_text;

                //SET payroll HOURS
                $colnum++;
                if (!isset($columns[$colnum])) $columns[$colnum] = array("Payroll", "R", 0, "");
                $time_text = $item->getHoursAndMinutes(true);
                $time_width = $this->GetStringWidth($time_text);
                if ($columns[$colnum][2] < $time_width)
                {
                  $columns[$colnum][2] = $time_width;
                  $columns[$colnum][3] = $time_text;
                }
                $this_data[$colnum] = $time_text;

                //SET billable HOURS
                $colnum++;
                if (!isset($columns[$colnum])) $columns[$colnum] = array("Billed", "R", 0, "");
                $time_text = $item->getHoursAndMinutes(false);
                $time_width = $this->GetStringWidth($time_text);
                if ($columns[$colnum][2] < $time_width)
                {
                  $columns[$colnum][2] = $time_width;
                  $columns[$colnum][3] = $time_text;
                }
                $this_data[$colnum] = $time_text;

                //SET STATUS
                if ($this->status)
                {
                  $colnum++;
                  if (!isset($columns[$colnum])) $columns[$colnum] = array("Status", "C", 0, "");
                  $status_text = $item->getStatus();
                  $status_width = $this->GetStringWidth($status_text);
                  if ($columns[$colnum][2] < $status_width)
                  {
                    $columns[$colnum][2] = $status_width;
                    $columns[$colnum][3] = $status_text;
                  }
                  $this_data[$colnum] = $status_text;
                }

                //SET COST
                if ($this->cost)
                {
                  $colnum++;
                  if (!isset($columns[$colnum])) $columns[$colnum] = array("Cost", "R", 0, "");
                  $cost_text = ($item->getCost() ? $item->getCost() : '');
                  $cost_width = $this->GetStringWidth($cost_text);
                  if ($columns[$colnum][2] < $cost_width)
                  {
                    $columns[$colnum][2] = $cost_width;
                    $columns[$colnum][3] = $cost_text;
                  }
                  $this_data[$colnum] = $cost_text;
                } 

                //calculate taxes and fees
                $total_subtotal += $item->getSubtotal(false);
                $total_hst      += $item->getHstTotal(false);
                $total_pst      += $item->getPstTotal(false);
                $total_gst      += $item->getGstTotal(false);
                $total_amt      += $item->getTotal(false);

                $loaded_data[$section_index]['items'][] = $this_data;
            }

            $loaded_data[$section_index]['subtotal'] = $total_subtotal;
            $loaded_data[$section_index]['hst']      = $total_hst;
            $loaded_data[$section_index]['pst']      = $total_pst;
            $loaded_data[$section_index]['gst']      = $total_gst;
            $loaded_data[$section_index]['total']    = $total_amt;
            $loaded_data[$section_index]['title']    = $result_section['title'];

            if (isset($colnum) && $colnum > 0)
            {
              //check the TOTAL field for widest value
              $this->setFont('Arial', 'B', $this->initial_fontsize);
              $amt_text = number_format($total_amt, 2);
              $amt_width = $this->GetStringWidth($amt_text);
              if ($columns[$colnum][2] < $amt_width)
              {
                $columns[$colnum][2] = $amt_width;
                $columns[$colnum][3] = $amt_text;
              }           
            }
        }

        //setup auto-scaling font sizes
        $this->calculateFontSizes($columns);

        //start output
        $this->AddPage();

        //draw sections
        foreach ($loaded_data AS $idx => $data)
        {
            $this->addSection($loaded_data, $idx);
        }
    }

    function calculateFontSizes($columns)
    {
        //FIND APPROPRIATE FONT SIZE
        $longest_width = 0;
        foreach ($columns AS $key => $this_col)
        {
            $this->setFont('Arial', 'B', $this->header_fontsize);
            $header_width = $this->GetStringWidth($this_col[0]);
            $this_width = max($header_width, $this_col[2]);
            if ($this_width != 0)
            {
                $longest_width += $this->column_padding + $this_width;
            }
        }
        
        //increase font size until we go over, then go back one step
        $this->setFont('Arial', '', $this->initial_fontsize);
        if ($longest_width < $this->page_width)
        { 
            $test_width = $longest_width;
            $test_fontsize = $this->initial_fontsize;
            $final_fontsize = $this->initial_fontsize;
            $final_width = $longest_width;
            while ($test_width < $this->page_width && $test_fontsize <= $this->max_fontsize)
            {
                //echo "<br>test width = ".$test_width;
                $final_width = $test_width;
                $final_fontsize = $test_fontsize;

                $test_width = 0;
                $test_fontsize += 0.5;
                //echo " test fontsize = ".$test_fontsize;
                $all_headers_are_biggest = true;
                foreach ($columns AS $key => $this_col)
                {
                    $this->setFont('Arial', 'B', $this->header_fontsize);
                    $header_width = $this->GetStringWidth($this_col[0]) + 3;
                    if ($header_width == 3) $header_width = 0;
                    $this->setFont('Arial', '', (isset($this->small_cols[$key]) ? $test_fontsize * $this->small_col_factor : $test_fontsize));
                    $text_width = $this->GetStringWidth($this_col[3]);
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
                //echo "<br>test width: ".$test_width." test fontsize: ".$test_fontsize;
            }
        }
        else //decrease font size until we go under, then stay
        { 
            $test_width = $longest_width;
            $test_fontsize = $this->initial_fontsize;
            while ($test_width > $this->page_width)
            {
                $test_width = 0;
                $test_fontsize -= 0.5;
                $all_headers_are_biggest = true;
                foreach ($columns AS $key => $this_col)
                {
                    $this->setFont('Arial', 'B', $this->header_fontsize);
                    $header_width = $this->GetStringWidth($this_col[0]) + 3;
                    if ($header_width == 3) $header_width = 0;
                    $this->setFont('Arial', '', (isset($this->small_cols[$key]) ? $test_fontsize * $this->small_col_factor : $test_fontsize));
                    $text_width = $this->GetStringWidth($this_col[3]);
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

        //echo "<br><br>final width = ".$final_width.", final fontsize = ".$final_fontsize;
        //fix address column to be wider to full width of page
        $expand_amt = $this->page_width - $final_width;

        //get final column widths
        $total = 0;
        foreach ($columns AS $key => $this_col)
        {
            $this->setFont('Arial', 'B', $this->header_fontsize);
            $header_width = $this->GetStringWidth($this_col[0]) + 3;
            if ($header_width == 3) $header_width = 0;
            $this->setFont('Arial', '', (isset($this->small_cols[$key]) ? $final_fontsize * $this->small_col_factor : $final_fontsize));
            $text_width = $this->GetStringWidth($this_col[3]);
            if ($header_width > $text_width)
            {
                $test_width = $header_width;
            }
            else
            {
                $all_headers_are_biggest = false;
                $test_width = $text_width;
            }
            if ($key == $this->expand_col) $test_width += $expand_amt;
            if ($test_width > 0) $test_width += $this->column_padding;
            $columns[$key][4] = $test_width;
            $total += $test_width;
        }
        //set columns in document
        $this->cols = $columns;

        //set document font sizes
        $this->header_font = $this->header_fontsize;
        $this->row_font = $final_fontsize;

    }

}
