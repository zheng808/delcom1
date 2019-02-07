<?php

/**
 * PartVariant form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartVariantSingleForm extends PartVariantForm
{
  public function configure()
  {
    parent::configure();

    if ($this->isNew())
    {
      $this->getObject()->setIsDefaultVariant(true);

      unset($this['part_id']);
      unset($this['id']);
    }
  }
}
