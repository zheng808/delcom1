<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Part filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_category_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartCategory', 'add_empty' => true)),
      'name'              => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'has_serial_number' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_multisku'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'manufacturer_id'   => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'active'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'part_category_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartCategory', 'column' => 'id')),
      'name'              => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'has_serial_number' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_multisku'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'manufacturer_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Manufacturer', 'column' => 'id')),
      'active'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('part_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Part';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'part_category_id'  => 'ForeignKey',
      'name'              => 'Text',
      'description'       => 'Text',
      'has_serial_number' => 'Boolean',
      'is_multisku'       => 'Boolean',
      'manufacturer_id'   => 'ForeignKey',
      'active'            => 'Boolean',
    );
  }
}
