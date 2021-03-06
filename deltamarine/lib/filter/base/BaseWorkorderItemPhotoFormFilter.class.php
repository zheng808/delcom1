<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderItemPhoto filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderItemPhotoFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_item_id' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'photo_id'          => new sfWidgetFormPropelChoice(array('model' => 'Photo', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'workorder_item_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'photo_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Photo', 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemPhoto';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'workorder_item_id' => 'ForeignKey',
      'photo_id'          => 'ForeignKey',
      'created_at'        => 'Date',
    );
  }
}
