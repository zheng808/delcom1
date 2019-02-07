<?php

class WorkorderSummaryPDF extends sfTCPDF
{
    var $current_section_title;
    var $current_section_color;
    var $current_section_number;

    var $cols;
    var $title_font = 20;
    var $subtitle_font = 8;
    var $section_font = 10;
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

    public function __construct($workorder)
    {
      $this->workorder = $workorder;

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
        $title = ($this->workorder->getStatus() == 'Estimate' ? 'ESTIMATE' : 'WORK ORDER').' #'.$this->workorder->getId();
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
      $this->SetX(80);
      $this->setFont("Arial", "B", $this->title_font);
      $this->Cell(0, $this->title_font/2.5, $title, 0, 2, 'R');

      //insert workorder status box
      $color = $this->workorder->getSummaryColor();
      $this->SetFillColor(hexdec(substr($color,0,2)), hexdec(substr($color,2,2)), hexdec(substr($color,4,2)));
      $this->SetDrawColor(0,0,0);
      $this->Rect($this->page_width - 32, 18, 32, 18,'FD');

      //haulout date
      if ($this->workorder->getHauloutDate())
      {
        $this->SetY(20);
        $this->SetX($this->page_width - 32 - 4 - 100);
        $this->setFont("Arial", "B", $this->subtitle_font*1.5);
        $this->setTextColor(0);
        $this->Cell(100, $this->subtitle_font/1.5, 'Haulout: '.$this->workorder->getHauloutDateTime('m/d/Y'), 0, 2, 'R');
      }

      //relaunch date
      if ($this->workorder->getHaulinDate())
      {
        $this->SetY(25);
        $this->SetX($this->page_width - 32 - 4 - 100);
        $this->setFont("Arial", "B", $this->subtitle_font*1.5);
        $this->setTextColor(0);
        $this->Cell(100, $this->subtitle_font/1.5, 'Relaunch: '.$this->workorder->getHaulinDateTime('m/d/Y'), 0, 2, 'R');
      }

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

      //INSERT CUSTOMER NOTES
      if ($this->workorder->getCustomerNotes())
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
        $this->Cell($this->page_width/3, 5, ($this->workorder->getStatus() == 'Estimate' ? 'Estimate' : 'Work Order').' #'.$this->workorder->getId(), 0, 0, 'C');
        $this->Cell($this->page_width/3, 5, "Generated ".date('Y-M-j g:i a'), 0, 0, 'C');
        $this->AliasNbPages();
        $this->Cell(0, 5, 'Page '.$this->PageNo().' of {nb}', 0, 0, 'C');
    }

    function drawSectionHeader($continued = false)
    {
        $this->SetFont("Arial", "B", $this->section_font);
        $this->SetFillColor(231,239,239);
        $this->SetDrawColor(231,239,239);
        $this->SetTextColor(0);
        $this->SetX(20);
        $rowheight = $this->section_font/1.8;
        $this->Cell($this->page_width - 30, $rowheight, ' '.$this->current_section_title.($continued ? ' (Cont\'d)' : ''), 1, 1, 'L', 1);

        $color = $this->current_section_color;
        $this->SetFillColor(hexdec(substr($color,0,2)), hexdec(substr($color,2,2)), hexdec(substr($color,4,2)));
        $this->SetDrawColor(0,0,0);
        $this->Rect($this->page_width - 18, $this->getY() - $rowheight, 8, $rowheight, 'FD');

        $this->SetFillColor(255);
        $this->SetDrawColor(231,239,239);
        $this->SetFont("Arial", "", $this->default_fontsize);
    }

    //OUTPUTS the various bits for a particular section (workorder item)
    function generateSection($number, $title, $section)
    {
        $this->current_section_title = $title;
        $this->current_section_color = $section->getColorCode();
        $this->current_section_number = $number;

        //determine if we need a page break
        //=================================
        $newpage_barrier = 266;
        if (trim($section->getCustomerNotes()))
        {
          $this->SetFont('Arial', '', $this->default_fontsize);
          $idx = 0;
          $note_width = $this->page_width - 18; //accounting for extra padding that will be added = 3-1.00... on each side
          $height = $this->getStringHeight($note_width, trim($section->getcustomerNotes()));
          if ($this->GetY() + $height + 40 > $newpage_barrier)
          {
            $this->AddPage();
          }
          else
          {
            $this->ln(4);
          }
        }
        else if ($this->GetY() + 40 > $newpage_barrier)
        {
          $this->addPage();
        }
        else
        {
          $this->ln(4);
        }
        
        //draw section header
        //====================
        $this->drawSectionHeader();


        //draw notes section
        //==================
        if (trim($section->getCustomerNotes()))
        {
          $padding = $this->GetCellPaddings();
          $this->SetCellPaddings(5, 1, 5, 1);
          $this->SetAutoPageBreak(true, 15);
          $this->adding_tasknotes = true;
          $this->setFont('Arial', '', $this->default_fontsize);
          $this->SetX(25);
          $this->SetFillColor(248,252,252);
          $this->MultiCell($this->page_width - 35, $this->default_fontsize/2, $section->getCustomerNotes(), 1, 'L', 1, 1);
          $this->SetCellPaddings($padding['L'],$padding['T'],$padding['R'],$padding['B']);
          $this->SetAutoPageBreak(false);
          $this->adding_tasknotes = false;
        }
    }

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
    }

    //generates an ordered, flat array of sections that need to be displayed, indexed by title
    public function loadSections()
    {
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
        $this->generateSection($numbering[0], $key, $section);
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
    }

}
