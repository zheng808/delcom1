<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartCategory filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartCategoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'  => new sfWidgetFormFilterInput(),
      'lft'   => new sfWidgetFormFilterInput(),
      'rgt'   => new sfWidgetFormFilterInput(),
      'scope' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'  => new sfValidatorPass(array('required' => false)),
      'lft'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'scope' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('part_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartCategory';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'name'  => 'Text',
      'lft'   => 'Number',
      'rgt'   => 'Number',
      'scope' => 'Number',
    );
  }
}
