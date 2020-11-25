<?php

class updateWorkorderMoorageTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
    ));

    $this->namespace        = 'project';
    $this->name             = 'updateWorkorderMoorage';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateWorkorderMoorage|INFO] task does things.
Call it with:

  [php symfony updateWorkorderMoorage|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here
    $c = new Criteria();
    $c->add(WorkorderPeer::MOORAGE_SURCHARGE, 0, Criteria::GREATER_THAN);
    $wos = WorkorderPeer::doSelect($c);

    if (count($wos) > 0)
    {
      foreach ($wos AS $wo)
      {
        fwrite(STDOUT, "\n\n=== ".$wo->getId()." ===\n");
        fwrite(STDOUT, "Percentage: ".$wo->getMoorageSurcharge()."%\n");
        fwrite(STDOUT, "Amount:     $".$wo->getMoorageSurchargeAmt()."\n");

        //load up and calculate totals (taken from billingDatagridAction)
        $totals = $wo->getTotalsByPayer();
        $total_moorage = 0;
        foreach ($totals AS $key => $payer_total)
        {
          $total = $payer_total['amount'];
          $fees = $payer_total['fees'];
          $total_moorage += round($total * ($wo->getMoorageSurcharge()/100), 2);
        }
    
        fwrite(STDOUT, "Moorage (old): $".$total_moorage."\n");
        fwrite(STDOUT, "Updating.... "); 
        $wo->setMoorageSurchargeAmt($total_moorage);
        $wo->save();
        fwrite(STDOUT, " Done!\n");
      }
    }
    else
    {
      fwrite(STDOUT, "\nWORKORDERS NOT FOUND!!!!\n");
    } 

  }
}
