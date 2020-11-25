<?php

class WorkorderPermTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      // add your own options here
    ));

    $this->namespace        = 'project';
    $this->name             = 'WorkorderPerm';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [WorkorderPerm|INFO] task does things.
Call it with:

  [php symfony WorkorderPerm|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // add your code here

    //get the old edit permission
    $c = new Criteria();
    $c->add(sfGuardPermissionPeer::NAME, 'workorder_edit');
    if ($found = sfGuardPermissionPeer::doSelectOne($c))
    {
      //create the new permission
      $perm = new sfGuardPermission();
      $perm->setName('workorder_add');
      $perm->setDescription('Work Orders::Add Items Only (no editing)');
      $perm->save();

      //grant new permission to anyone who used to have old permission
      $c = new Criteria();
      $c->addJoin(sfGuardUserPermissionPeer::PERMISSION_ID, sfGuardPermissionPeer::ID);
      $c->add(sfGuardPermissionPeer::NAME, 'workorder_edit');
      $emps = sfGuardUserPermissionPeer::doSelect($c);
      foreach ($emps AS $emp)
      {
        $empperm = new sfGuardUserPermission();
        $empperm->setPermissionId($perm->getId());
        $empperm->setUserId($emp->getUserId());
        $empperm->save();
      }
    }
  }
}
