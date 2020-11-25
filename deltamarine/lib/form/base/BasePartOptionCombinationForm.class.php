<?php

/**
 * PartOptionCombination form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 15484 2009-02-13 13:13:51Z fabien $
 */
class BasePartOptionCombinationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'part_variant_id'      => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'part_option_id'       => new sfWidgetFormPropelChoice(array('model' => 'PartOption', 'add_empty' => true)),
      'part_option_value_id' => new sfWidgetFormPropelChoice(array('model' => 'PartOptionValue', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'PartOptionCombination', 'column' => 'id', 'required' => false)),
      'part_variant_id'      => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'part_option_id'       => new sfValidatorPropelChoice(array('model' => 'PartOption', 'column' => 'id', 'required' => false)),
      'part_option_value_id' => new sfValidatorPropelChoice(array('model' => 'PartOptionValue', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_option_combination[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartOptionCombination';
  }


}
