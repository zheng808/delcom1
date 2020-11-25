<?php

/**
 * PartSupplier form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BasePartSupplierForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'supplier_sku'    => new sfWidgetFormInput(),
      'notes'           => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'PartSupplier', 'column' => 'id', 'required' => false)),
      'part_variant_id' => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'supplier_id'     => new sfValidatorPropelChoice(array('model' => 'Supplier', 'column' => 'id', 'required' => false)),
      'supplier_sku'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'notes'           => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_supplier[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartSupplier';
  }


}
