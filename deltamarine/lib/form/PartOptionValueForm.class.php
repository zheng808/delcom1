<?php

/**
 * PartOptionValue form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartOptionValueForm extends BasePartOptionValueForm
{
  public function configure()
  {
    unset($this['color_val']);

    $this->widgetSchema['part_option_id']->setOption('add_empty', false);
    $this->widgetSchema['part_option_id']->setLabel('Option Name');
    $this->widgetSchema['value']->setLabel('New Value');

    $this->validatorSchema->setPostValidator(new sfValidatorPropelSemiUnique(array('model' => 'PartOptionValue', 'column' => array('value'), 'scope' => 'part_option_id'), array('invalid' => 'That option value already exists!')));
  }
}
