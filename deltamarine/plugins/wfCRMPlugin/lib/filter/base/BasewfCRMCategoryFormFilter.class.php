<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * wfCRMCategory filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMCategoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'tree_left'                => new sfWidgetFormFilterInput(),
      'tree_right'               => new sfWidgetFormFilterInput(),
      'tree_id'                  => new sfWidgetFormFilterInput(),
      'parent_node_id'           => new sfWidgetFormPropelChoice(array('model' => 'wfCRMCategory', 'add_empty' => true)),
      'private_name'             => new sfWidgetFormFilterInput(),
      'public_name'              => new sfWidgetFormFilterInput(),
      'is_subscribable'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'wf_crm_category_ref_list' => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'tree_left'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tree_right'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tree_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parent_node_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'wfCRMCategory', 'column' => 'id')),
      'private_name'             => new sfValidatorPass(array('required' => false)),
      'public_name'              => new sfValidatorPass(array('required' => false)),
      'is_subscribable'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'wf_crm_category_ref_list' => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm_category_filters[%s]');

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

    $criteria->addJoin(wfCRMCategoryRefPeer::CATEGORY_ID, wfCRMCategoryPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(wfCRMCategoryRefPeer::CRM_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(wfCRMCategoryRefPeer::CRM_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'wfCRMCategory';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'tree_left'                => 'Number',
      'tree_right'               => 'Number',
      'tree_id'                  => 'Number',
      'parent_node_id'           => 'ForeignKey',
      'private_name'             => 'Text',
      'public_name'              => 'Text',
      'is_subscribable'          => 'Boolean',
      'wf_crm_category_ref_list' => 'ManyKey',
    );
  }
}
