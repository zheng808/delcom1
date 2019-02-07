<?php

class updateWorkorderTotalsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('workorder_id', sfCommandArgument::REQUIRED, 'The workorder number to update'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
    ));

    $this->namespace        = 'project';
    $this->name             = 'updateWorkorderTotals';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateWorkorderTotals|INFO] task does things.
Call it with:

  [php symfony updateWorkorderTotals|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here
    $wos = array();
    if ($wo = WorkorderPeer::retrieveByPk($arguments['workorder_id']))
    {
      $wos[] = $wo;
    }
    else if ($arguments['workorder_id'] === 'all')
    {
      $c = new Criteria();
      $wos = WorkOrderPeer::doSelect($c); 
    }

    if (count($wos) > 0)
    {
      foreach ($wos AS $wo)
      {
        fwrite(STDOUT, "=== ".$wo->getId()." ===\n");
        $net = 0;
        $items = $wo->getWorkorderItems();
        foreach ($items AS $item)
        {
          $oldval = $item->getLabourActual();
          $item->calculateActualLabour();
          $newval = $item->getLabourActual();
          if ($oldval != $newval)
          {
            $net += ($oldval - $newval);
            fwrite(STDOUT, $item->getLabel()." Labour from ".$oldval." to ".$newval."\n");
          }

          $oldval = $item->getPartActual();
          $item->calculateActualPart();
          $newval = $item->getPartActual();
          if ($oldval != $newval)
          {
            $net += ($oldval - $newval);
            fwrite(STDOUT, $item->getLabel()." Part from ".$oldval." to ".$newval."\n");
          }

          $oldval = $item->getOtherActual();
          $item->calculateActualOther();
          $newval = $item->getOtherActual();
          if ($oldval != $newval)
          {
            $net += ($oldval - $newval);
            fwrite(STDOUT, $item->getLabel()." Expense from ".$oldval." to ".$newval."\n");
          }  
        }

        fwrite(STDOUT, "\n\n Net Difference is ".$net."\n");

      }
      fwrite(STDOUT, "\nDONE\n");
    }
    else
    {
      fwrite(STDOUT, "\nWORKORDER NOT FOUND!!!!\n");
    } 

  }
}
