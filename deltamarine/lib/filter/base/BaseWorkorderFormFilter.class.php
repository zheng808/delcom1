<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Workorder filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseWorkorderFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id'             => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'customer_boat_id'        => new sfWidgetFormPropelChoice(array('model' => 'CustomerBoat', 'add_empty' => true)),
      'workorder_category_id'   => new sfWidgetFormPropelChoice(array('model' => 'WorkorderCategory', 'add_empty' => true)),
      'status'                  => new sfWidgetFormFilterInput(),
      'summary_color'           => new sfWidgetFormFilterInput(),
      'summary_notes'           => new sfWidgetFormFilterInput(),
      'haulout_date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'haulin_date'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'created_on'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'started_on'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'completed_on'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'hst_exempt'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'gst_exempt'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'pst_exempt'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'customer_notes'          => new sfWidgetFormFilterInput(),
      'internal_notes'          => new sfWidgetFormFilterInput(),
      'for_rigging'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'shop_supplies_surcharge' => new sfWidgetFormFilterInput(),
      'moorage_surcharge'       => new sfWidgetFormFilterInput(),
      'moorage_surcharge_amt'   => new sfWidgetFormFilterInput(),
      'exemption_file'          => new sfWidgetFormFilterInput(),
      'canada_entry_num'          => new sfWidgetFormFilterInput(),
      'canada_entry_date'          => new sfWidgetFormFilterInput(),
      'usa_entry_num'          => new sfWidgetFormFilterInput(),
      'usa_entry_date'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'customer_id'             => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Customer', 'column' => 'id')),
      'customer_boat_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CustomerBoat', 'column' => 'id')),
      'workorder_category_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'WorkorderCategory', 'column' => 'id')),
      'status'                  => new sfValidatorPass(array('required' => false)),
      'summary_color'           => new sfValidatorPass(array('required' => false)),
      'summary_notes'           => new sfValidatorPass(array('required' => false)),
      'haulout_date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'haulin_date'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_on'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'started_on'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'completed_on'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'hst_exempt'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'gst_exempt'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'pst_exempt'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'customer_notes'          => new sfValidatorPass(array('required' => false)),
      'internal_notes'          => new sfValidatorPass(array('required' => false)),
      'for_rigging'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'shop_supplies_surcharge' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'moorage_surcharge'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'moorage_surcharge_amt'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'exemption_file'          => new sfValidatorPass(array('required' => false)),
      'canada_entry_num'           => new sfValidatorPass(array('required' => false)),
      'canada_entry_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'usa_entry_num'           => new sfValidatorPass(array('required' => false)),
      'usa_entry_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('workorder_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Workorder';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'customer_id'             => 'ForeignKey',
      'customer_boat_id'        => 'ForeignKey',
      'workorder_category_id'   => 'ForeignKey',
      'status'                  => 'Text',
      'summary_color'           => 'Text',
      'summary_notes'           => 'Text',
      'haulout_date'            => 'Date',
      'haulin_date'             => 'Date',
      'created_on'              => 'Date',
      'started_on'              => 'Date',
      'completed_on'            => 'Date',
      'hst_exempt'              => 'Boolean',
      'gst_exempt'              => 'Boolean',
      'pst_exempt'              => 'Boolean',
      'customer_notes'          => 'Text',
      'internal_notes'          => 'Text',
      'for_rigging'             => 'Boolean',
      'shop_supplies_surcharge' => 'Number',
      'moorage_surcharge'       => 'Number',
      'moorage_surcharge_amt'   => 'Number',
      'exemption_file'          => 'Text',
      'canada_entry_num'        => 'Text',
      'canada_entry_date'       => 'Date',
      'usa_entry_num'           => 'Text',
      'usa_entry_date'          => 'Date',
    );
  }
}
