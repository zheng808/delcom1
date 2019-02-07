<?php

class InventoryValuesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'admin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
    ));

    $this->namespace        = 'project';
    $this->name             = 'inventory-values';
    $this->briefDescription = 'Generates a file (XLS) of all inventory items and quantities available.';
    $this->detailedDescription = <<<EOF
The [InventorySheet|INFO] task does things.
Call it with:

  [php symfony InventorySheet|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $date = null;

    fwrite(STDOUT, "\n".'Costs = '.number_format(PartPeer::getInventoryValue(false, false, $date),2));
    fwrite(STDOUT, "\n".'Prices = '.number_format(PartPeer::getInventoryValue(false, true, $date),2));
    fwrite(STDOUT, "\n");

    $date = mktime(0,0,0,10,31,2011);

    fwrite(STDOUT, "\n".'Costs = '.number_format(PartPeer::getInventoryValue(false, false, $date),2));
    fwrite(STDOUT, "\n".'Prices = '.number_format(PartPeer::getInventoryValue(false, true, $date),2));
    fwrite(STDOUT, "\n");

  }
}
