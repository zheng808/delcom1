<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wfCRMAddress filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMAddressFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'crm_id'  => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'type'    => new sfWidgetFormFilterInput(),
      'line1'   => new sfWidgetFormFilterInput(),
      'line2'   => new sfWidgetFormFilterInput(),
      'city'    => new sfWidgetFormFilterInput(),
      'region'  => new sfWidgetFormFilterInput(),
      'postal'  => new sfWidgetFormFilterInput(),
      'country' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'crm_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRM', 'column' => 'id')),
      'type'    => new sfValidatorPass(array('required' => false)),
      'line1'   => new sfValidatorPass(array('required' => false)),
      'line2'   => new sfValidatorPass(array('required' => false)),
      'city'    => new sfValidatorPass(array('required' => false)),
      'region'  => new sfValidatorPass(array('required' => false)),
      'postal'  => new sfValidatorPass(array('required' => false)),
      'country' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_address_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMAddress';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'crm_id'  => 'ForeignKey',
      'type'    => 'Text',
      'line1'   => 'Text',
      'line2'   => 'Text',
      'city'    => 'Text',
      'region'  => 'Text',
      'postal'  => 'Text',
      'country' => 'Text',
    );
  }
}
