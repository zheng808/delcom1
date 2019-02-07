<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PartSupplier filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BasePartSupplierFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'part_variant_id' => new sfWidgetFormPropelChoice(array('model' => 'PartVariant', 'add_empty' => true)),
      'supplier_id'     => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'supplier_sku'    => new sfWidgetFormFilterInput(),
      'notes'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'part_variant_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'PartVariant', 'column' => 'id')),
      'supplier_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Supplier', 'column' => 'id')),
      'supplier_sku'    => new sfValidatorPass(array('required' => false)),
      'notes'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('part_supplier_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PartSupplier';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'part_variant_id' => 'ForeignKey',
      'supplier_id'     => 'ForeignKey',
      'supplier_sku'    => 'Text',
      'notes'           => 'Text',
    );
  }
}
