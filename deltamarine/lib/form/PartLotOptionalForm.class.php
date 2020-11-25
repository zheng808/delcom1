<?php

/**
 * PartLot form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartLotOptionalForm extends PartLotForm
{
  public function configure()
  {
    parent::configure();

     $this->widgetSchema->setFormFormatterName('cells');
     $this->validatorSchema['quantity_received']->setOption('required', false);
     $this->widgetSchema->setDefault('quantity_received', '');
  }
}
