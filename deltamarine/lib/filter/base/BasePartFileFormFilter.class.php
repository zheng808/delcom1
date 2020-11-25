<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartFile filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartFileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'         => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'file_id'         => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'part_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Part', 'column' => 'id')),
      'part_variant_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'file_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'File', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('part_file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartFile';
  }

  public function getFields()
  {
    return array(
      'part_id'         => 'ForeignKey',
      'part_variant_id' => 'ForeignKey',
      'file_id'         => 'ForeignKey',
      'id'              => 'Number',
    );
  }
}
