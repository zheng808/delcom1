<?php

/**
 * Barcode form base class.
 *
 * @package    deltamarine
 * @subpackage form
 * @author     Your name here
 */
class BaseBarcodeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'value'             => new sfWidgetFormInput(),
      'default_symbology' => new sfWidgetFormInput(),
      'part_variant_id'   => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'part_supplier_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartSupplier', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'Barcode', 'column' => 'id', 'required' => false)),
      'value'             => new sfValidatorString(array('max_length' => 255)),
      'default_symbology' => new sfValidatorString(array('max_length' => 8, 'required' => false)),
      'part_variant_id'   => new sfValidatorPropelChoice(array('model' => 'PartVariant', 'column' => 'id', 'required' => false)),
      'part_supplier_id'  => new sfValidatorPropelChoice(array('model' => 'PartSupplier', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('barcode[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Barcode';
  }


}
