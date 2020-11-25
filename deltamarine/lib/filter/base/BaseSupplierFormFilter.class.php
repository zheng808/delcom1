<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Supplier filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseSupplierFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'wf_crm_id'      => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'account_number' => new sfWidgetFormFilterInput(),
      'credit_limit'   => new sfWidgetFormFilterInput(),
      'net_days'       => new sfWidgetFormFilterInput(),
      'hidden'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'wf_crm_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRM', 'column' => 'id')),
      'account_number' => new sfValidatorPass(array('required' => false)),
      'credit_limit'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'net_days'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hidden'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('supplier_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Supplier';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'wf_crm_id'      => 'ForeignKey',
      'account_number' => 'Text',
      'credit_limit'   => 'Number',
      'net_days'       => 'Number',
      'hidden'         => 'Boolean',
    );
  }
}
