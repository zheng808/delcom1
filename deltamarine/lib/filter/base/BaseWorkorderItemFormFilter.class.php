<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderItem filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderItemFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_id'    => new sfWidgetFormPropelChoice(array('model' => 'Workorder', 'add_empty' => true)),
      'label'           => new sfWidgetFormFilterInput(),
      'lft'             => new sfWidgetFormFilterInput(),
      'rgt'             => new sfWidgetFormFilterInput(),
      'owner_company'   => new sfWidgetFormFilterInput(),
      'labour_estimate' => new sfWidgetFormFilterInput(),
      'labour_actual'   => new sfWidgetFormFilterInput(),
      'other_estimate'  => new sfWidgetFormFilterInput(),
      'other_actual'    => new sfWidgetFormFilterInput(),
      'part_estimate'   => new sfWidgetFormFilterInput(),
      'part_actual'     => new sfWidgetFormFilterInput(),
      'completed'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'completed_by'    => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'completed_date'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'customer_notes'  => new sfWidgetFormFilterInput(),
      'internal_notes'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'workorder_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Workorder', 'column' => 'id')),
      'label'           => new sfValidatorPass(array('required' => false)),
      'lft'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'owner_company'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'labour_estimate' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'labour_actual'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'other_estimate'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'other_actual'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'part_estimate'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'part_actual'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'completed'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'completed_by'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Employee', 'column' => 'id')),
      'completed_date'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'customer_notes'  => new sfValidatorPass(array('required' => false)),
      'internal_notes'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItem';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'workorder_id'    => 'ForeignKey',
      'label'           => 'Text',
      'lft'             => 'Number',
      'rgt'             => 'Number',
      'owner_company'   => 'Number',
      'labour_estimate' => 'Number',
      'labour_actual'   => 'Number',
      'other_estimate'  => 'Number',
      'other_actual'    => 'Number',
      'part_estimate'   => 'Number',
      'part_actual'     => 'Number',
      'completed'       => 'Boolean',
      'completed_by'    => 'ForeignKey',
      'completed_date'  => 'Date',
      'customer_notes'  => 'Text',
      'internal_notes'  => 'Text',
    );
  }
}
