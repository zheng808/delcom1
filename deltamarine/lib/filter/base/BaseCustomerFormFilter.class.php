<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Customer filter form base class.
 *
 * @package    deltamarine
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'wf_crm_id'     => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'guard_user_id' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'pst_number'    => new sfWidgetFormFilterInput(),
      'hidden'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'wf_crm_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRM', 'column' => 'id')),
      'guard_user_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'pst_number'    => new sfValidatorPass(array('required' => false)),
      'hidden'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('customer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Customer';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'wf_crm_id'     => 'ForeignKey',
      'guard_user_id' => 'ForeignKey',
      'pst_number'    => 'Text',
      'hidden'        => 'Boolean',
    );
  }
}
