<?php

class TimelogsReportPDF extends sfTCPDF
{
    var $titletext;
    var $subtitletext;

    var $cols;
    var $header_font;
    var $title_font = 16;
    var $subtitle_font = 10;
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
    var $expand_col = 1;

    public function __construct($title, $subtitle)
    {
      $this->titletext = $title;
      $this->subtitletext = $subtitle;

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
        //main title
        $this->SetXY(0,$this->margin_height);;
        $this->setFont("Arial", "B", $this->title_font);
        $this->Cell(0, $this->title_font/2.5, $this->titletext, 0, 1, 'C');

        if ($this->subtitletext)
        {
            $this->SetTextColor(180, 0, 0);
            $this->setFont('Arial', '', $this->title_font);
            $this->MultiCell(0, $this->subtitle_font/2.5, $this->subtitletext, 0, 'C', 0, 0);
            $this->SetTextColor(0);
        }

    }

    function AddPage()
    {
        parent::AddPage();
        $this->SetY($this->GetY() + $this->margin_height*2);
    }

    function Footer()
    {
        $this->SetY(-11);

        $this->SetFont('Arial', 'BI', 8);
        $this->Cell($this->page_width/2, 5, "Generated ".date('Y-M-j g:i a'), 0, 0, 'C');
        $this->AliasNbPages();
        $this->Cell(0, 5, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'C');
    }

    function addSection($alldata, $index)
    {
        $section_data = $alldata[$index];

        //draw section header, unless there's only one section
        if (count($alldata) > 0)
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
            $this->Cell(0, $this->row_font, "None Found", 'LBR', 1, 'C');
            $this->SetTextColor(0);
            $this->SetFont("Arial", '', $this->row_font);
            $this->ln(4);
        }
        else
        {
            $this->AddTableHeader();
            $rowspan = 1;
            $skipping_rowspan = false;
            foreach ($section_data['items'] AS $item)
            {
                if ($item['rowspan'] > 1)
                {
                    $rowspan = $item['rowspan'];
                    $skipping_rowspan = false;
                }                
                $this->addRow($item, max($rowspan,1), $skipping_rowspan);
                $rowspan--;
                $skipping_rowspan = ($rowspan > 0);
            }
             
            //draw totals boxes
            $totals_width = $this->cols[count($this->cols) - 1][4];
            $labels_width = $this->page_width - $totals_width;
            $extras = array('all' => 'Total');
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
                  $this->Cell(0, $this->row_font/1.5, number_format($section_data[$idx],2), 1, 1, 'C', 1);
              }
            }
    
            $this->ln(4);
            $this->SetLineWidth(0.2);
            $this->SetFillColor(255);
        }
    }

    function addRow($data, $rowspan, $skipping_rowspan)
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
        if (!$skipping_rowspan) $this->oddrow = !$this->oddrow;

        $i = 0;
        foreach ($this->cols AS $key => $this_col)
        {
            if ($this->oddrow) $this->SetFillColor(240,240,240);
            else $this->SetFillColor(255,255,255);            

            $i++;
            $hidden = ($i == 1 && $skipping_rowspan);
            $single_row = (count(explode("\n", $data[$key])) == 1);
            $font_size = (isset($this->small_cols[$key]) ? $this->row_font * $this->small_col_factor : $this->row_font);
            $this->SetFont('Arial', '', $font_size);
            $text = $data[$key];

            $this_line_height = ($i == 1 && !$skipping_rowspan ? $rowspan*$line_height : $line_height);
            if ($hidden)
            {
                $this->SetXY($this->GetX() + $this_col[4], $this->GetY());
            }
            else
            {
                $this->Cell($this_col[4], $this_line_height, ($hidden ? '' : $text), ($hidden ? 0 : 1), 0, $this_col[1], 1);
            }
        }
        $this->ln($line_height);
    }

    public function generate($section_data, $startdate, $enddate, $summary)
    {

        if ($summary)
        {
            $this->generateSummary($section_data);
        }
        else
        {
            $this->generateFull($section_data, $startdate, $enddate);
        }

    }

    public function generateSummary($section_data)
    {
        //preload all the data
        $loaded_data = array(); //column-indexed
        $columns = array();
        $loaded_data['a'] = array('items' => array(), 'title' => 'Employee Timelogs Summary');
        foreach ($section_data AS $employee_id => $employee_data)
        {
            $details = (isset($employee_data['items']['bill'])) ? $employee_data['items'] : array('bill' => 0, 'nonbill' => 0);

            $this->setFont('Arial', '', $this->initial_fontsize);
            $this_data = array();
            $colnum = 0;

            //SET EMPLOYEE
            if (!isset($columns[$colnum])) $columns[$colnum] = array("Employee Name", "L", 0, "");
            $name_text = $employee_data['title'];
            $name_width = $this->GetStringWidth($name_text);
            if ($columns[$colnum][2] < $name_width)
            {
              $columns[$colnum][2] = $name_width;
              $columns[$colnum][3] = $name_text;
            }
            $this_data[$colnum] = $name_text;

            //billable
            $colnum++;
            if (!isset($columns[$colnum])) $columns[$colnum] = array("Billable Hours", "R", 0, "");
            $hrs_text = number_format($details['bill'], 2);
            $hrs_width = $this->GetStringWidth($hrs_text);
            if ($columns[$colnum][2] < $hrs_width)
            {
              $columns[$colnum][2] = $hrs_width;
              $columns[$colnum][3] = $hrs_text;
            }
            $this_data[$colnum] = $hrs_text;


            //non-billable
            $colnum++;
            if (!isset($columns[$colnum])) $columns[$colnum] = array("Non-Billable", "R", 0, "");
            $hrs_text = number_format($details['nonbill'], 2);
            $hrs_width = $this->GetStringWidth($hrs_text);
            if ($columns[$colnum][2] < $hrs_width)
            {
              $columns[$colnum][2] = $hrs_width;
              $columns[$colnum][3] = $hrs_text;
            }
            $this_data[$colnum] = $hrs_text;


            //total
            $colnum++;
            if (!isset($columns[$colnum])) $columns[$colnum] = array("Total Hours", "R", 0, "");
            $hrs_text = number_format($details['bill'] + $details['nonbill'], 2);
            $hrs_width = $this->GetStringWidth($hrs_text);
            if ($columns[$colnum][2] < $hrs_width)
            {
              $columns[$colnum][2] = $hrs_width;
              $columns[$colnum][3] = $hrs_text;
            }
            $this_data[$colnum] = $hrs_text;

            $loaded_data['a']['items'][] = $this_data;
        }

        //setup auto-scaling font sizes
        $this->calculateFontSizes($columns);
        $this->AddPage();
        foreach ($loaded_data AS $idx => $data)
        {
            $this->addSection($loaded_data, $idx);
        }
    }

    public function generateFull($section_data, $startdate, $enddate)
    {
        //preload all the data
        $loaded_data = array(); //column-indexed
        $columns = array();

        foreach ($section_data AS $section_index => $result_section)
        {
            //track the totals per section
            $total_bill      = 0;

            $loaded_data[$section_index] = array('items' => array(), 'title' => $result_section['title']);

            if (count($result_section['items']) == 0)
            {
                //don't bother filling out empty sections, just pass along the title and empty items array.
                continue;
            }

            //loop through all dates in range.
            for ($date = $startdate; $date <= ($enddate + 86399); $date += 86400)
            {

                if (!isset($result_section['items'][date('Y-m-d', $date)]))
                {
                    $result_section['items'][date('Y-m-d', $date)] = array(array('customer' => '', 'rate' => 0, 'hours' => 0));
                }

                $i = 0;
                foreach ($result_section['items'][date('Y-m-d', $date)] AS $details)
                {
                    $this->setFont('Arial', '', $this->initial_fontsize);
                    $this_data = array('rowspan' => ($i == 0 ? count($result_section['items'][date('Y-m-d', $date)]) : 1) );
                    $colnum = 0;
                    $i++;

                    //SET DATE
                    if (!isset($columns[$colnum])) $columns[$colnum] = array("Date", "L", 0, "");
                    $date_text = date('D M j, Y', $date);
                    $date_width = $this->GetStringWidth($date_text);
                    if ($columns[$colnum][2] < $date_width)
                    {
                      $columns[$colnum][2] = $date_width;
                      $columns[$colnum][3] = $date_text;
                    }
                    $this_data[$colnum] = $date_text;

                    //customer
                    $colnum++;
                    if (!isset($columns[$colnum])) $columns[$colnum] = array("Customer", "L", 0, "");
                    $customer_text = $details['customer'];
                    $customer_width = $this->GetStringWidth($customer_text);
                    if ($columns[$colnum][2] < $customer_width)
                    {
                      $columns[$colnum][2] = $customer_width;
                      $columns[$colnum][3] = $customer_text;
                    }
                    $this_data[$colnum] = $customer_text;                

                    //rate
                    $colnum++;
                    if (!isset($columns[$colnum])) $columns[$colnum] = array("Rate", "R", 0, "");
                    $rate_text = ($details['rate'] > 0 ? number_format($details['rate'], 2) : '');
                    $rate_width = $this->GetStringWidth($rate_text);
                    if ($columns[$colnum][2] < $rate_width)
                    {
                      $columns[$colnum][2] = $rate_width;
                      $columns[$colnum][3] = $rate_text;
                    }
                    $this_data[$colnum] = $rate_text;

                    //hours
                    $colnum++;
                    if (!isset($columns[$colnum])) $columns[$colnum] = array("Hours", "C", 0, "");
                    $hrs_text = ($details['hours'] ? number_format($details['hours'], 2): '-');
                    $hrs_width = $this->GetStringWidth($hrs_text);
                    if ($columns[$colnum][2] < $hrs_width)
                    {
                      $columns[$colnum][2] = $hrs_width;
                      $columns[$colnum][3] = $hrs_text;
                    }
                    $this_data[$colnum] = $hrs_text;
                    $total_bill += $details['hours'];

                    $loaded_data[$section_index]['items'][] = $this_data;

                }
            }

            $loaded_data[$section_index]['all']    = round($total_bill,2);

            if (isset($colnum) && $colnum > 0)
            {
              //check the TOTAL field for widest value
              $this->setFont('Arial', 'B', $this->initial_fontsize);
              $amt_text = $loaded_data[$section_index]['all'];
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
