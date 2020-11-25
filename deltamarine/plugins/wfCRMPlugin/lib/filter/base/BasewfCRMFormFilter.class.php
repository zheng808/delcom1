<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wfCRM filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'tree_left'                => new sfWidgetFormFilterInput(),
      'tree_right'               => new sfWidgetFormFilterInput(),
      'parent_node_id'           => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'tree_id'                  => new sfWidgetFormFilterInput(),
      'department_name'          => new sfWidgetFormFilterInput(),
      'first_name'               => new sfWidgetFormFilterInput(),
      'middle_name'              => new sfWidgetFormFilterInput(),
      'last_name'                => new sfWidgetFormFilterInput(),
      'salutation'               => new sfWidgetFormFilterInput(),
      'titles'                   => new sfWidgetFormFilterInput(),
      'job_title'                => new sfWidgetFormFilterInput(),
      'alpha_name'               => new sfWidgetFormFilterInput(),
      'email'                    => new sfWidgetFormFilterInput(),
      'work_phone'               => new sfWidgetFormFilterInput(),
      'mobile_phone'             => new sfWidgetFormFilterInput(),
      'home_phone'               => new sfWidgetFormFilterInput(),
      'fax'                      => new sfWidgetFormFilterInput(),
      'homepage'                 => new sfWidgetFormFilterInput(),
      'private_notes'            => new sfWidgetFormFilterInput(),
      'public_notes'             => new sfWidgetFormFilterInput(),
      'is_company'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_in_addressbook'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'wf_crm_category_ref_list' => new sfWidgetFormPropelChoice(array('model' => 'wfCRMCategory', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'tree_left'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tree_right'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parent_node_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRM', 'column' => 'id')),
      'tree_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'department_name'          => new sfValidatorPass(array('required' => false)),
      'first_name'               => new sfValidatorPass(array('required' => false)),
      'middle_name'              => new sfValidatorPass(array('required' => false)),
      'last_name'                => new sfValidatorPass(array('required' => false)),
      'salutation'               => new sfValidatorPass(array('required' => false)),
      'titles'                   => new sfValidatorPass(array('required' => false)),
      'job_title'                => new sfValidatorPass(array('required' => false)),
      'alpha_name'               => new sfValidatorPass(array('required' => false)),
      'email'                    => new sfValidatorPass(array('required' => false)),
      'work_phone'               => new sfValidatorPass(array('required' => false)),
      'mobile_phone'             => new sfValidatorPass(array('required' => false)),
      'home_phone'               => new sfValidatorPass(array('required' => false)),
      'fax'                      => new sfValidatorPass(array('required' => false)),
      'homepage'                 => new sfValidatorPass(array('required' => false)),
      'private_notes'            => new sfValidatorPass(array('required' => false)),
      'public_notes'             => new sfValidatorPass(array('required' => false)),
      'is_company'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_in_addressbook'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'wf_crm_category_ref_list' => new sfValidatorPropelChoice(array('model' => 'wfCRMCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addwfCRMCategoryRefListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(wfCRMCategoryRefPeer::CRM_ID, wfCRMPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(wfCRMCategoryRefPeer::CATEGORY_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(wfCRMCategoryRefPeer::CATEGORY_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'wfCRM';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'tree_left'                => 'Number',
      'tree_right'               => 'Number',
      'parent_node_id'           => 'ForeignKey',
      'tree_id'                  => 'Number',
      'department_name'          => 'Text',
      'first_name'               => 'Text',
      'middle_name'              => 'Text',
      'last_name'                => 'Text',
      'salutation'               => 'Text',
      'titles'                   => 'Text',
      'job_title'                => 'Text',
      'alpha_name'               => 'Text',
      'email'                    => 'Text',
      'work_phone'               => 'Text',
      'mobile_phone'             => 'Text',
      'home_phone'               => 'Text',
      'fax'                      => 'Text',
      'homepage'                 => 'Text',
      'private_notes'            => 'Text',
      'public_notes'             => 'Text',
      'is_company'               => 'Boolean',
      'is_in_addressbook'        => 'Boolean',
      'created_at'               => 'Date',
      'updated_at'               => 'Date',
      'wf_crm_category_ref_list' => 'ManyKey',
    );
  }
}
