<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderDependencies filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderDependenciesFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'source' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'target' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'source' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'target' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('workorder_dependencies_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderDependencies';
  }

  public function getFields()
  {
    return array(
      'source' => 'ForeignKey',
      'target' => 'ForeignKey',
      'id'     => 'Number',
    );
  }
}
