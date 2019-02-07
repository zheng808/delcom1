<?php

/**
 * WorkorderItemBillable form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseWorkorderItemBillableForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                          => new sfWidgetFormInputHidden(),
      'workorder_item_id'           => new sfWidgetFormPropelChoice(array('model' => 'WorkorderItem', 'add_empty' => true)),
      'manufacturer_id'             => new sfWidgetFormPropelChoice(array('model' => 'Manufacturer', 'add_empty' => true)),
      'supplier_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'manufacturer_parts_percent'  => new sfWidgetFormInput(),
      'manufacturer_labour_percent' => new sfWidgetFormInput(),
      'supplier_parts_percent'      => new sfWidgetFormInput(),
      'supplier_labour_percent'     => new sfWidgetFormInput(),
      'in_house_parts_percent'      => new sfWidgetFormInput(),
      'in_house_labour_percent'     => new sfWidgetFormInput(),
      'customer_parts_percent'      => new sfWidgetFormInput(),
      'customer_labour_percent'     => new sfWidgetFormInput(),
      'recurse'                     => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                          => new sfValidatorPropelChoice(array('model' => 'WorkorderItemBillable', 'column' => 'id', 'required' => false)),
      'workorder_item_id'           => new sfValidatorPropelChoice(array('model' => 'WorkorderItem', 'column' => 'id', 'required' => false)),
      'manufacturer_id'             => new sfValidatorPropelChoice(array('model' => 'Manufacturer', 'column' => 'id', 'required' => false)),
      'supplier_id'                 => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'manufacturer_parts_percent'  => new sfValidatorInteger(),
      'manufacturer_labour_percent' => new sfValidatorInteger(),
      'supplier_parts_percent'      => new sfValidatorInteger(),
      'supplier_labour_percent'     => new sfValidatorInteger(),
      'in_house_parts_percent'      => new sfValidatorInteger(),
      'in_house_labour_percent'     => new sfValidatorInteger(),
      'customer_parts_percent'      => new sfValidatorInteger(),
      'customer_labour_percent'     => new sfValidatorInteger(),
      'recurse'                     => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('workorder_item_billable[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'WorkorderItemBillable';
  }


}
