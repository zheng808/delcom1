<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderItemFile filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderItemFileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_item_id' => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'file_id'           => new sfWidgetFormPropelChoice(array('model' => 'File', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'workorder_item_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'file_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'File', 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemFile';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'workorder_item_id' => 'ForeignKey',
      'file_id'           => 'ForeignKey',
      'created_at'        => 'Date',
    );
  }
}
