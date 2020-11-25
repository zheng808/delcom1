<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wfCRMCorrespondence filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMCorrespondenceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'wf_crm_id' => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'received'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'method'    => new sfWidgetFormFilterInput(),
      'subject'   => new sfWidgetFormFilterInput(),
      'message'   => new sfWidgetFormFilterInput(),
      'whendone'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'is_new'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'wf_crm_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRM', 'column' => 'id')),
      'received'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'method'    => new sfValidatorPass(array('required' => false)),
      'subject'   => new sfValidatorPass(array('required' => false)),
      'message'   => new sfValidatorPass(array('required' => false)),
      'whendone'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'is_new'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_correspondence_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRMCorrespondence';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'wf_crm_id' => 'ForeignKey',
      'received'  => 'Boolean',
      'method'    => 'Text',
      'subject'   => 'Text',
      'message'   => 'Text',
      'whendone'  => 'Date',
      'is_new'    => 'Boolean',
    );
  }
}
