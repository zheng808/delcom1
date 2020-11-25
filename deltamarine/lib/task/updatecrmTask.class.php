<?php

class updatecrmTask extends sfBaseTask
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
    $this->name             = 'updatecrm';
    $this->briefDescription = 'Converts old CRM data to new Tree-based CRM data. Rename old table to wf_crm_old before running.';
    $this->detailedDescription = <<<EOF
The [updatecrm|INFO] task does things.
Call it with:

  [php symfony updatecrm|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // create the supplier and manufacturer categories
    $supcat = new wfCRMCategory();
    $supcat->makeRoot();
    $supcat->setScopeIdValue(1);
    $supcat->setPrivateName('Suppliers');
    $supcat->setPublicName('Suppliers');
    $supcat->save();

    $mancat = new wfCRMCategory();
    $mancat->makeRoot();
    $mancat->setScopeIdValue(2);
    $mancat->setPrivateName('Manufacturers');
    $mancat->setPublicName('Manufacturers');
    $mancat->save();

    $sql = "SET FOREIGN_KEY_CHECKS = 0";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $sql = "select * from wf_crm_old  WHERE department_name IS NOT NULL ORDER BY id ASC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows AS $row)
    {
      $newcrm = new wfCRM();
      $newcrm->makeRoot();
      $newcrm->setScopeIdValue(strtotime($row['created_at']));
      $newcrm->setDepartmentName(trim($row['department_name']));
      if ($row['work_phone']) $newcrm->setWorkPhone($row['work_phone']);
      $newcrm->setCreatedAt($row['created_at']);
      $newcrm->setUpdatedAt($row['updated_at']);
      $newcrm->setPrivateNotes($row['public_notes']); //this is intentional.
      $newcrm->setIsCompany(true);
      $newcrm->save();
      $old_id = $newcrm->getId();
      fwrite(STDOUT, "id ".$old_id." - ".$row['department_name']);
      $sql = "update wf_crm set id = ".(1000 + $row['id'])." where id = ".$old_id;
      $stmt = $connection->prepare($sql);
      $stmt->execute();
      fwrite(STDOUT, " - tempid ".(1000 + $row['id'])."\n");
    }

    $sql = "update wf_crm set id = (id - 1000)";
    $stmt = $connection->prepare($sql);
    fwrite(STDOUT, "\n\nCHANGING TEMP IDS");
    $stmt->execute();
    unset($stmt);

    //add to categories
    $sql = "select * from sf_tagging where taggable_model = 'wfCRM'";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    fwrite(STDOUT, "\nADDING TO CATEGORIES");
    foreach ($cats AS $row)
    {
      if ($row['tag_id'] == 1) $thiscatid = $mancat->getId();
      if ($row['tag_id'] == 2) $thiscatid = $supcat->getId();
      if (isset($thiscatid))
      {
        $crmcat = new wfCRMCategoryRef();
        $crmcat->setCrmId($row['taggable_id']);
        $crmcat->setCategoryId($thiscatid);
        $crmcat->save();
      }
    }
    unset($stmt, $cats, $row);

    $sql = "select max(id) FROM wf_crm";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    unset($stmt);
    $max_val = $row[0];
    fwrite(STDOUT, "\nFOUND MAX: ".$max_val);

    $sql = "alter table wf_crm AUTO_INCREMENT = ".($max_val + 1);
    $stmt = $connection->prepare($sql);
    fwrite(STDOUT, "\nUPDATING AUTO INCREMENT VALUE\n");
    $stmt->execute();
    unset($stmt);
  }
}
