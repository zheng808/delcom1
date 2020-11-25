<?php

/**
 * PartOption form.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class PartOptionForm extends BasePartOptionForm
{
  public function configure()
  {
    unset($this['part_id'], $this['is_color']);
    $this->widgetSchema['existing_value'] = new sfWidgetFormInput();
    $this->widgetSchema['existing_value']->setLabel('Value to set for Existing Variant(s)');
    $this->widgetSchema['existing_value']->setAttribute('size', 20);
    $this->widgetSchema['name']->setAttribute('size', 20);
    $this->validatorSchema['existing_value'] = new sfValidatorString(
      array('max_length' => 255), 
      array('required' => 'You must specify a value for this new option to use for the existing variant(s)'));
  }

  public function updateObject($values = null)
  {
    $object = parent::updateObject($values);

    $object->setIsColor((strtolower($object->getName()) == 'color' || strtolower($object->getName()) == 'colour'));

    return $object;
  }
}
