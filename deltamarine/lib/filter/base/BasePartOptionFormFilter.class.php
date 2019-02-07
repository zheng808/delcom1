<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartOption filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartOptionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'  => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'name'     => new sfWidgetFormFilterInput(),
      'is_color' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'part_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Part', 'column' => 'id')),
      'name'     => new sfValidatorPass(array('required' => false)),
      'is_color' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('part_option_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartOption';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'part_id'  => 'ForeignKey',
      'name'     => 'Text',
      'is_color' => 'Boolean',
    );
  }
}
