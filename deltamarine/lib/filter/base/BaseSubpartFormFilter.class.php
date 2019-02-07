<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Subpart filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseSubpartFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_id'      => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'child_id'       => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'child_quantity' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'parent_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'child_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'child_quantity' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('subpart_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Subpart';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'parent_id'      => 'ForeignKey',
      'child_id'       => 'ForeignKey',
      'child_quantity' => 'Number',
    );
  }
}
