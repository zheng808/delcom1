<?php

class updateonhandTask extends sfBaseTask
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
    $this->name             = 'updateonhand';
    $this->briefDescription = 'updates the CURRENT_ON_HAND values for ALL part variants';
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

    // create the supplier and manufacturer categories
    $variant = true;
    $counter = 0;
    while ($variant){
      $c = new Criteria();
      $c->setLimit(1);
      $c->setOffset($counter);
      $variant = PartVariantPeer::doSelectOne($c);
      fwrite(STDOUT, $counter." - ".$variant->getCurrentOnHand()." -> ".$variant->calculateCurrentOnHand()."\n");
      $counter++;
    }
    fwrite(STDOUT, "\nDONE\n");
  }
}
