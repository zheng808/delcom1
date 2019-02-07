<?php

/**
 * WorkorderItemPhoto form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderItemPhotoForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'workorder_item_id' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'photo_id'          => new sfWidgetFormPropelChoice(array('model' => 'Photo', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'WorkorderItemPhoto', 'column' => 'id', 'required' => false)),
      'workorder_item_id' => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'photo_id'          => new sfValidatorPropelChoice(array('model' => 'Photo', 'column' => 'id', 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_photo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemPhoto';
  }


}
