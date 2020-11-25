<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartOptionValue filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartOptionValueFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'part_option_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartOption', 'add_empty' => true)),
      'value'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'part_variant_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'part_option_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartOption', 'column' => 'id')),
      'value'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_option_value_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartOptionValue';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'part_variant_id' => 'ForeignKey',
      'part_option_id'  => 'ForeignKey',
      'value'           => 'Text',
    );
  }
}
