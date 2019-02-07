<?php

/**
 * wfCRM form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class BasewfCRMForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'tree_left'                => new sfWidgetFormInput(),
      'tree_right'               => new sfWidgetFormInput(),
      'parent_node_id'           => new sfWidgetFormPropelChoice(array('model' => 'wfCRM', 'add_empty' => true)),
      'tree_id'                  => new sfWidgetFormInput(),
      'department_name'          => new sfWidgetFormInput(),
      'first_name'               => new sfWidgetFormInput(),
      'middle_name'              => new sfWidgetFormInput(),
      'last_name'                => new sfWidgetFormInput(),
      'salutation'               => new sfWidgetFormInput(),
      'titles'                   => new sfWidgetFormInput(),
      'job_title'                => new sfWidgetFormInput(),
      'alpha_name'               => new sfWidgetFormInput(),
      'email'                    => new sfWidgetFormInput(),
      'work_phone'               => new sfWidgetFormInput(),
      'mobile_phone'             => new sfWidgetFormInput(),
      'home_phone'               => new sfWidgetFormInput(),
      'fax'                      => new sfWidgetFormInput(),
      'homepage'                 => new sfWidgetFormInput(),
      'private_notes'            => new sfWidgetFormTextarea(),
      'public_notes'             => new sfWidgetFormTextarea(),
      'is_company'               => new sfWidgetFormInputCheckbox(),
      'is_in_addressbook'        => new sfWidgetFormInputCheckbox(),
      'created_at'               => new sfWidgetFormDateTime(),
      'updated_at'               => new sfWidgetFormDateTime(),
      'wf_crm_category_ref_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'wfCRMCategory')),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id', 'required' => false)),
      'tree_left'                => new sfValidatorInteger(array('required' => false)),
      'tree_right'               => new sfValidatorInteger(array('required' => false)),
      'parent_node_id'           => new sfValidatorPropelChoice(array('model' => 'wfCRM', 'column' => 'id', 'required' => false)),
      'tree_id'                  => new sfValidatorInteger(array('required' => false)),
      'department_name'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'first_name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'middle_name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'last_name'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'salutation'               => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'titles'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'job_title'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'alpha_name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'work_phone'               => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'mobile_phone'             => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'home_phone'               => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'fax'                      => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'homepage'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'private_notes'            => new sfValidatorString(array('required' => false)),
      'public_notes'             => new sfValidatorString(array('required' => false)),
      'is_company'               => new sfValidatorBoolean(),
      'is_in_addressbook'        => new sfValidatorBoolean(),
      'created_at'               => new sfValidatorDateTime(array('required' => false)),
      'updated_at'               => new sfValidatorDateTime(array('required' => false)),
      'wf_crm_category_ref_list' => new sfValidatorPropelChoiceMany(array('model' => 'wfCRMCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wf_crm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'wfCRM';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['wf_crm_category_ref_list']))
    {
      $values = array();
      foreach ($this->object->getwfCRMCategoryRefs() as $obj)
      {
        $values[] = $obj->getCategoryId();
      }

      $this->setDefault('wf_crm_category_ref_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->savewfCRMCategoryRefList($con);
  }

  public function savewfCRMCategoryRefList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['wf_crm_category_ref_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(wfCRMCategoryRefPeer::CRM_ID, $this->object->getPrimaryKey());
    wfCRMCategoryRefPeer::doDelete($c, $con);

    $values = $this->getValue('wf_crm_category_ref_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new wfCRMCategoryRef();
        $obj->setCrmId($this->object->getPrimaryKey());
        $obj->setCategoryId($value);
        $obj->save();
      }
    }
  }

}
