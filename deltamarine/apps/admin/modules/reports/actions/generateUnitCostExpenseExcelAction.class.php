<?php
class generateUnitCostExpenseExcelAction extends sfAction
{
    
    public function execute($request)
    {
        $workorder_id =  $request->getParameter('id', null);
        set_time_limit(0);
        ini_set('memory_limit','512M');
        $show_blank_new = true;
        $output = array();
        $header[] = 'Task Name';
        $header[] = 'Task Number';
        $header[] = 'Expense Label Name';
        $header[] = 'Unit Cost Price';
        $header[] = 'Entry Date';
        $total_cols = count($header);

        $currentDateTime = date('Y-m-d H:i:s');

        require_once("Spreadsheet/Excel/Writer.php");
        $filename = $currentDateTime . "_UnitCostExpense.xls";
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
        $worksheet->writeString($row, 1, 'Unit Cost Expense Report '.$workorder_id, $bold_format);
    
        $row ++;
        foreach ($header AS $col => $cell)
        {
            $worksheet->writeString($row, $col, $cell, $format_header);
        }
        $worksheet->setRow(0, 30);
        $expenses = PartPeer::getExpenseUnitCostCSV($workorder_id);
    
        $row = 1;
        $taskcount = 0;
        if(isset($workorder_id)){
          foreach ($expenses As $key=>$expense)
          {
              $row++;
              $len = count($expenses);
              $previndex = $key - 1;
              $task = 'Task';
              if($nextindex < $len){
                if($expenses[$key][0]!=$expenses[$previndex][0]){
                  $taskcount = $taskcount + 1;
                  $task = $task . $taskcount;
                }else{
                  $task = $task . $taskcount;
                } 
              }
    
    
              $worksheet->writeString($row, 0, $expense[0]);
              $worksheet->writeString($row, 1, $task);
              $worksheet->writeString($row, 2, $expense[1]); 
              $worksheet->writeString($row, 3, $expense[2]); 
              $worksheet->writeString($row, 4, $expense[3]); 
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

