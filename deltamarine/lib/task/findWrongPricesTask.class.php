<?php

class updatelabouractualTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
    ));

    $this->namespace        = 'project';
    $this->name             = 'updatelabouractual';
    $this->briefDescription = 'updates the labour actual for all workorder items';
    $this->detailedDescription = <<<EOF
The [updateonhand|INFO] task does things.
Call it with:

  [php symfony updateonhand|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $c = new Criteria();
    $items = WorkorderItemPeer::doSelect($c);
    $counter = 0;
    foreach ($items AS $item)
    {
      $counter++;
      $old = $item->getLabourActual();
      $item->calculateActualLabour();
      fwrite(STDOUT, $counter." - ".$item->getLabel()."from ".$old." to ".$item->getLabourActual()."\n");
    }
    
    fwrite(STDOUT, "\nDONE\n");
  }
}
