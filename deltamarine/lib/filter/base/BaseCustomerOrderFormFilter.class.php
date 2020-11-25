<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerOrder filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerOrderFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id'          => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'finalized'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'approved'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sent_some'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sent_all'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'invoice_per_shipment' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'invoice_id'           => new sfWidgetFormPropelChoice(array('model' => 'Invoice', 'add_empty' => true)),
      'date_ordered'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'hst_exempt'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'gst_exempt'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'pst_exempt'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'for_rigging'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'discount_pct'         => new sfWidgetFormFilterInput(),
      'po_num'               => new sfWidgetFormFilterInput(),
      'boat_name'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'customer_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Customer', 'column' => 'id')),
      'finalized'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'approved'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sent_some'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sent_all'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'invoice_per_shipment' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'invoice_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Invoice', 'column' => 'id')),
      'date_ordered'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'hst_exempt'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'gst_exempt'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'pst_exempt'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'for_rigging'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'discount_pct'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'po_num'               => new sfValidatorPass(array('required' => false)),
      'boat_name'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerOrder';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'customer_id'          => 'ForeignKey',
      'finalized'            => 'Boolean',
      'approved'             => 'Boolean',
      'sent_some'            => 'Boolean',
      'sent_all'             => 'Boolean',
      'invoice_per_shipment' => 'Boolean',
      'invoice_id'           => 'ForeignKey',
      'date_ordered'         => 'Date',
      'hst_exempt'           => 'Boolean',
      'gst_exempt'           => 'Boolean',
      'pst_exempt'           => 'Boolean',
      'for_rigging'          => 'Boolean',
      'discount_pct'         => 'Number',
      'po_num'               => 'Text',
      'boat_name'            => 'Text',
    );
  }
}
