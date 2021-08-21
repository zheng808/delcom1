<?php
class generateUnitCostPartsExcelAction extends sfAction
{
    
    public function execute($request)
    {
        $workorder_id =  json_decode($request->getParameter('id', null));

       
        set_time_limit(0);
        ini_set('memory_limit','512M');
        $show_blank_new = true;
        $output = array();
        $header[] = 'Task Name';
        $header[] = 'Task Number';
        $header[] = 'Part Name';
        $header[] = 'Quantity';
        $header[] = 'Unit Cost Price';
        $header[] = 'Entry Date';
        $header[] = 'Total Amount';
        $header[] = 'One-off Part';
        $total_cols = count($header);

        $currentDateTime = date('Y-m-d H:i:s');

        require_once("Spreadsheet/Excel/Writer.php");
        $filename = $currentDateTime . "_UnitCostPart.xls";
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
    
        $worksheet->setColumn(0, 0, 60);
        $worksheet->setColumn(1, 1, 35);
        $worksheet->setColumn(2, 2, 30);
        $worksheet->setColumn(3, 3, 30);
        $worksheet->setColumn(4, 4, 50);
        $worksheet->setColumn(5, 5, 50);
        $worksheet->setColumn(6, 6, 50);
        $worksheet->setColumn(7, 7, 50);
        $worksheet->setColumn(8, 8, 50);
        $worksheet->setColumn(9, 9, 50);
        $worksheet->writeString($row, 1, 'Unit Cost Part WorkOrder: '.$workorder_id, $bold_format);
    
        $row ++;
        foreach ($header AS $col => $cell)
        {
            $worksheet->writeString($row, $col, $cell, $format_header);
        }
        $worksheet->setRow(0, 30);
        $parts = PartPeer::getPartUnitCostCSV($workorder_id);
        if(is_null($parts)){
          $this->renderText("{failed:true}");
        }
        $row = 1;
        $taskcount = 0;
        if(isset($workorder_id)){
          foreach ($parts As $key=>$part)
          {
              $row++;
              $len = count($parts);
              $previndex = $key - 1;
              $task = 'Task';
              if($nextindex < $len){
                if($parts[$key][0]!=$parts[$previndex][0]){
                  $taskcount = $taskcount + 1;
                  $task = $task . $taskcount;
                }else{
                  $task = $task . $taskcount;
                } 
              }
    
              if(is_null($part[1])){
                $partName = $part[4];
                $worksheet->writeString($row, 7, "Yes");
              }else{
                $partName = $part[1];
                $worksheet->writeString($row, 7, "No");
              }

              //unit cost
              if(is_null($part[7])){
                $unit_cost = $part[3];
                
              }else{
                $unit_cost = $part[7];
              }

              //total
              $total = round($part[2] * $unit_cost, 2);
    
              $worksheet->writeString($row, 0, $part[0]);
              $worksheet->writeString($row, 1, $task);
              $worksheet->writeString($row, 2, $partName); 
              $worksheet->writeString($row, 3, $part[2]); 
              $worksheet->writeString($row, 4, $unit_cost);
              $worksheet->writeString($row, 5, $part[5]);
              $worksheet->writeString($row, 6, $total);
          }
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

