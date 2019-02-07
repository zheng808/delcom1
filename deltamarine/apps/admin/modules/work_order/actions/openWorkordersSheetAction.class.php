<?php

class openWorkordersSheetAction extends sfAction
{

  public function execute($request)
  {
    set_time_limit(0);
    ini_set('memory_limit','512M');
    
    //set it up!
    $show_blank_new = true;
    $output = array();
    $header[] = 'WO #';
    $header[] = 'Customer Name';
    $header[] = 'Boat Name';
    $header[] = 'Boat Type';
    $header[] = 'Start Date';
    $total_cols = count($header);

    require_once("Spreadsheet/Excel/Writer.php");
    $filename = "open_workorders-".date('m-j-Y').".xls";
    $workbook = new Spreadsheet_Excel_Writer();
    $workbook->send($filename);
    $workbook->setVersion(8);
    $worksheet = $workbook->addWorksheet();
    $worksheet->setPortrait();
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
    $bold_format->setSize(12);

    $border_format = $workbook->addFormat();
    $border_format->setBorder(2);

    $right_format = $workbook->addFormat();
    $right_format->setHAlign('right');

    $center_format = $workbook->addFormat();
    $center_format->setHAlign('center');

    //TODO set column widths
    $worksheet->setColumn(0, 0, 6);
    $worksheet->setColumn(1, 1, 35);
    $worksheet->setColumn(2, 2, 30);
    $worksheet->setColumn(3, 3, 30);
    $worksheet->setColumn(4, 4, 15);

    $row = 0;
    $worksheet->writeString($row, 1, 'List of Currently Open Workorders as of '.date('m-j-Y h:i a'), $bold_format);

    $row ++;
    foreach ($header AS $col => $cell)
    {
        $worksheet->writeString($row, $col, $cell, $format_header);
    }
    $worksheet->setRow(0, 30);

    //generate it!
    $c = new Criteria();
    $c->add(WorkorderPeer::STATUS, 'In Progress');
    $c->addAscendingOrderByColumn(WorkorderPeer::ID);
    $workorders = WorkorderPeer::doSelectForListing($c);
    
    $row = 1;
    foreach ($workorders AS $workorder)
    {
        $row++;
        $worksheet->writeString($row, 0, $workorder->getId());
        $worksheet->writeString($row, 1, $workorder->getCustomer()->getName(false,false,false));
        $worksheet->writeString($row, 2, $workorder->getCustomerBoat()->getName()); 
        $worksheet->writeString($row, 3, $workorder->getCustomerBoat()->getMakeModel()); 
        $worksheet->writeString($row, 4, $workorder->getCreatedOn('m/d/Y'), $center_format);
    }

    $row++;

    $workbook->close();

    return sfView::NONE;
  }


}
