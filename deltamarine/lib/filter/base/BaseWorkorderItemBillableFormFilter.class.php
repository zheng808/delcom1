<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * WorkorderItemBillable filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderItemBillableFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'workorder_item_id'           => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'manufacturer_id'             => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'supplier_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_parts_percent'  => new sfWidgetFormFilterInput(),
      'manufacturer_labour_percent' => new sfWidgetFormFilterInput(),
      'supplier_parts_percent'      => new sfWidgetFormFilterInput(),
      'supplier_labour_percent'     => new sfWidgetFormFilterInput(),
      'in_house_parts_percent'      => new sfWidgetFormFilterInput(),
      'in_house_labour_percent'     => new sfWidgetFormFilterInput(),
      'customer_parts_percent'      => new sfWidgetFormFilterInput(),
      'customer_labour_percent'     => new sfWidgetFormFilterInput(),
      'recurse'                     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'workorder_item_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderItem', 'column' => 'id')),
      'manufacturer_id'             => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Manufacturer', 'column' => 'id')),
      'supplier_id'                 => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Supplier', 'column' => 'id')),
      'manufacturer_parts_percent'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'manufacturer_labour_percent' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier_parts_percent'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier_labour_percent'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'in_house_parts_percent'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'in_house_labour_percent'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customer_parts_percent'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customer_labour_percent'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recurse'                     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_billable_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemBillable';
  }

  public function getFields()
  {
    return array(
      'id'                          => 'Number',
      'workorder_item_id'           => 'ForeignKey',
      'manufacturer_id'             => 'ForeignKey',
      'supplier_id'                 => 'ForeignKey',
      'manufacturer_parts_percent'  => 'Number',
      'manufacturer_labour_percent' => 'Number',
      'supplier_parts_percent'      => 'Number',
      'supplier_labour_percent'     => 'Number',
      'in_house_parts_percent'      => 'Number',
      'in_house_labour_percent'     => 'Number',
      'customer_parts_percent'      => 'Number',
      'customer_labour_percent'     => 'Number',
      'recurse'                     => 'Boolean',
    );
  }
}
