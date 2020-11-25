<?php

/**
 * WorkorderDependencies form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderDependenciesForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'source' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => false)),
      'target' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => false)),
      'id'     => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'source' => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id')),
      'target' => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id')),
      'id'     => new sfValidatorPropelChoice(array('model' => 'WorkorderDependencies', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_dependencies[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderDependencies';
  }


}
