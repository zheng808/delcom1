<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartPhoto filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartPhotoFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'         => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'photo_id'        => new sfWidgetFormPropelChoice(array('model' => 'Photo', 'add_empty' => true)),
      'is_primary'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'part_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Part', 'column' => 'id')),
      'part_variant_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'photo_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Photo', 'column' => 'id')),
      'is_primary'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('part_photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartPhoto';
  }

  public function getFields()
  {
    return array(
      'part_id'         => 'ForeignKey',
      'part_variant_id' => 'ForeignKey',
      'photo_id'        => 'ForeignKey',
      'is_primary'      => 'Boolean',
      'id'              => 'Number',
    );
  }
}
