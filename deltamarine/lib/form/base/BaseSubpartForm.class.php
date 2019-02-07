<?php

/**
 * Subpart form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseSubpartForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'parent_id'      => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => false)),
      'child_id'       => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => false)),
      'child_quantity' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Subpart', 'column' => 'id', 'required' => false)),
      'parent_id'      => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id')),
      'child_id'       => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id')),
      'child_quantity' => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('subpart[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Subpart';
  }


}
