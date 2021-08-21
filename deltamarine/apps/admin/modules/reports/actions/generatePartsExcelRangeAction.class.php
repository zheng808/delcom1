<?php

class generatePartsExcelRangeAction extends sfAction{
    public function execute($request){
        $from =  $request->getParameter('from', null);
        $to = $request->getParameter('to', null);
        ini_set('memory_limit','512M');
        $show_blank_new = true;
        $output = array();
        $header[] = 'Part Label';
        $header[] = 'Part Name';
        $header[] = 'Quantity';
        $header[] = 'Customer Name';
        $header[] = 'WorkOrder ID';
        $header[] = 'Unit Cost';
        $total_cols = count($header);

        $currentDateTime = date('Y-m-d H:i:s');

        require_once("Spreadsheet/Excel/Writer.php");
        $filename = $currentDateTime . "_partRange.xls";
        $workbook = new Spreadsheet_Excel_Writer();
        $workbook->send($filename);
        $workbook->setVersion(8);
        $worksheet = $workbook->addWorksheet();
        $worksheet->setPortrait();
        $worksheet->setMargins(0.25);

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

        $worksheet->setColumn(0, 0, 60);
        $worksheet->setColumn(1, 1, 35);
        $worksheet->setColumn(2, 2, 30);
        $worksheet->setColumn(3, 3, 30);
        $worksheet->setColumn(4, 4, 50);
        $worksheet->setColumn(5, 5, 50);
        $worksheet->writeString($row, 1, 'List of Parts as of '.date('m-j-Y h:i a'), $bold_format);

        $row ++;
        foreach ($header AS $col => $cell)
        {
            $worksheet->writeString($row, $col, $cell, $format_header);
        }
        $row = 1;
        $worksheet->setRow(0, 30);
        $parts = PartPeer::getPartsDateRange($from, $to);
        foreach ($parts As $key=>$part)
        {
                $row++;
      
                if(is_null($part[1])){
                  $partName = $part[5];
                }else{
                  $partName = $part[1];
                }
                $worksheet->writeString($row, 0, $part[0]);
                $worksheet->writeString($row, 1, $partName); 
                $worksheet->writeString($row, 2, $part[2]); 
                $worksheet->writeString($row, 3, $part[3]);
                $worksheet->writeString($row, 4, $part[4]);
                $worksheet->writeString($row, 5, $part[6]);
        }

        $row++;
        $workbook->close();
        if (sfConfig::get('sf_logging_enabled'))
        {
        $message = 'DONE inventorySheetAction.execute====================';
        sfContext::getInstance()->getLogger()->info($message);
        }

        if (sfConfig::get('sf_logging_enabled'))
        {
        $message = 'DONE inventorySheetAction.execute====================';
        sfContext::getInstance()->getLogger()->info($message);
        }

        return sfView::NONE;
    }
}