<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * SupplierOrder filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseSupplierOrderFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'supplier_id'    => new sfWidgetFormPropelChoice(array('model' => 'Supplier', 'add_empty' => true)),
      'purchase_order' => new sfWidgetFormFilterInput(),
      'date_ordered'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'date_expected'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'date_received'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'finalized'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'approved'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sent'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'received_some'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'received_all'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'invoice_id'     => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'supplier_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Supplier', 'column' => 'id')),
      'purchase_order' => new sfValidatorPass(array('required' => false)),
      'date_ordered'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'date_expected'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'date_received'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'finalized'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'approved'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sent'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'received_some'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'received_all'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'invoice_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('supplier_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SupplierOrder';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'supplier_id'    => 'ForeignKey',
      'purchase_order' => 'Text',
      'date_ordered'   => 'Date',
      'date_expected'  => 'Date',
      'date_received'  => 'Date',
      'finalized'      => 'Boolean',
      'approved'       => 'Boolean',
      'sent'           => 'Boolean',
      'received_some'  => 'Boolean',
      'received_all'   => 'Boolean',
      'invoice_id'     => 'ForeignKey',
    );
  }
}
