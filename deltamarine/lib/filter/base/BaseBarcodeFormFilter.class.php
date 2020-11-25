<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Barcode filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseBarcodeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'             => new sfWidgetFormFilterInput(),
      'default_symbology' => new sfWidgetFormFilterInput(),
      'part_variant_id'   => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'part_supplier_id'  => new sfWidgetFormPropelChoice(array('model' => 'PartSupplier', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'value'             => new sfValidatorPass(array('required' => false)),
      'default_symbology' => new sfValidatorPass(array('required' => false)),
      'part_variant_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'part_supplier_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartSupplier', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('barcode_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Barcode';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'value'             => 'Text',
      'default_symbology' => 'Text',
      'part_variant_id'   => 'ForeignKey',
      'part_supplier_id'  => 'ForeignKey',
    );
  }
}
