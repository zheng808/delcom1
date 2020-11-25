<?php

/**
 * PartCategory form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'name'  => new sfWidgetFormInput(),
      'lft'   => new sfWidgetFormInput(),
      'rgt'   => new sfWidgetFormInput(),
      'scope' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(array('model' => 'PartCategory', 'column' => 'id', 'required' => false)),
      'name'  => new sfValidatorString(array('max_length' => 255)),
      'lft'   => new sfValidatorInteger(),
      'rgt'   => new sfValidatorInteger(),
      'scope' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartCategory';
  }


}
