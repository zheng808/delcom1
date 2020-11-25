<?php

/**
 * PartPhoto form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartPhotoForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_id'         => new sfWidgetFormPropelChoice(array('model' => 'Part', 'add_empty' => true)),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'photo_id'        => new sfWidgetFormPropelChoice(array('model' => 'Photo', 'add_empty' => true)),
      'is_primary'      => new sfWidgetFormInputCheckbox(),
      'id'              => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'part_id'         => new sfValidatorPropelChoice(array('model' => 'Part', 'column' => 'id', 'required' => false)),
      'part_variant_id' => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'photo_id'        => new sfValidatorPropelChoice(array('model' => 'Photo', 'column' => 'id', 'required' => false)),
      'is_primary'      => new sfValidatorBoolean(),
      'id'              => new sfValidatorPropelChoice(array('model' => 'PartPhoto', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_photo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartPhoto';
  }


}
